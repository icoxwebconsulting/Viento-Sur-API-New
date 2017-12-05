<?php

namespace VientoSur\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOSRestBundleAnnotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use VientoSur\App\AppBundle\Entity\Reservation;
use VientoSur\App\AppBundle\Entity\Hotel;

/**
 * Class HotelController
 *
 * @package ApiBundel\Controller
 *
 * @FOSRestBundleAnnotations\View()
 * */
class HotelController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get hotels with available rooms
     *
     * @param Request $request
     * @return array
     *
     * @FOSRestBundleAnnotations\Route("/hotel/availabilities/")
     * @ApiDoc(
     *  section="Hotel",
     *  description="Get hotels with available rooms",
     *  parameters={
     *     {
     *          "name"="country_code",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="AR",
     *          "description"="The country code in which the request is made."
     *      },
     *     {
     *          "name"="checkin_date",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="YYYY-MM-DD",
     *          "description"="Date of checkin."
     *      },
     *     {
     *          "name"="checkout_date",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="YYYY-MM-DD",
     *          "description"="Date of checkout."
     *      },
     *     {
     *          "name"="destination",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="0000",
     *          "description"="Id city or Id hotel"
     *      },
     *     {
     *          "name"="distribution",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="1-2-5!1-12-9",
     *          "description"="Room distribution."
     *      },
     *     {
     *          "name"="language",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="en, es, pt",
     *          "description"="Language of texts involved in the response."
     *      },
     *     {
     *          "name"="currency",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="ARS, USD",
     *          "description"="The currency upon which the prices will be shown."
     *      },
     *     {
     *          "name"="offset",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="0",
     *          "description"="The pagination offset for the current collection."
     *      },
     *     {
     *          "name"="limit",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="0",
     *          "description"="The number of collection results to display during pagination. Should be between 0 and 100 inclusive."
     *      },
     *     {
     *          "name"="sorting",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="Sorting criteria. Available sortings are in sorting field of response."
     *      },
     *     {
     *          "name"="classify_roompacks_by",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="Use this parameter to group roompacks in classes according to shared attributes."
     *      },
     *     {
     *          "name"="amenities",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="Limits the result to the given amenities."
     *      },
     *     {
     *          "name"="hotel_type",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="Limits the result to the given hotel types"
     *      },
     *     {
     *          "name"="payment_type",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="Limits the result to the given payment types"
     *      },
     *     {
     *          "name"="meal_plans",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="Limits the result to the given meal plans."
     *      },
     *     {
     *          "name"="stars",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="Limits the result to hotels with the given stars."
     *      },
     *     {
     *          "name"="zones",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="Limits the result to the given zones."
     *      },
     *     {
     *          "name"="profiles",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="Limits the result to the given profiles."
     *      },
     *     {
     *          "name"="hotel_chains",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"=""
     *      },
     *     {
     *          "name"="total_price_range",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="Limits the result to the given total price range (Nightly rate). ie: 120-270."
     *      }
     *  },
     *  statusCodes={
     *     200="Returned when successful",
     *     404="Wrong data"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function getAvailabilitiesAction(Request $request)
    {

        /**
         * Parameters
         */
        $countryCode = $request->query->get('country_code');
        $checkinDate = $request->query->get('checkin_date');
        $checkoutDate = $request->query->get('checkout_date');
        $distributions = $request->query->get('distribution');
        $destination = $request->query->get('destination');
        $language = $request->query->get('language');
        $currency = $request->query->get('currency');
        $sorting = $request->query->get('sorting');
        $amenities = $request->query->get('amenities');
        $hotelType = $request->query->get('hotel_type');
        $paymentType = $request->query->get('payment_type');
        $mealPlans = $request->query->get('meal_plans');
        $stars = $request->query->get('stars');
        $zones = $request->query->get('zones');
        $profiles = $request->query->get('profiles');
        $hotelChains = $request->query->get('hotel_chains');
        $totalPriceRange = $request->query->get('total_price_range');
        $offset = $request->query->get('offset');
        $limit = $request->query->get('limit');

        /**
         * Processed date
         */

        if (!$currency) {
            $currency = 'ARS';
        }

        if (!$language) {
            $language = 'es';
        }

        if (!$sorting) {
            $sorting = 'best_selling_descending';
        }

        if (!$offset) {
            $offset = 0;
        }

        if (!$limit) {
            $limit = 10;
        }

        $urlParams = array(
            'country_code' => $countryCode,
            'checkin_date' => $checkinDate,
            'checkout_date' => $checkoutDate,
            'destination' => $destination,
            'distribution' => $distributions,
            'language' => $language,
            'radius' => '200',
            'currency' => $currency,
            'sorting' => $sorting,
            'amenities' => $amenities,
            'hotel_type' => $hotelType,
            'classify_roompacks_by' => 'none',
            'roompack_choices' => 'recommended',
            'payment_type' => $paymentType,
            'meal_plans' => $mealPlans,
            'stars' => $stars,
            'zones' => $zones,
            'profiles' => $profiles,
            'hotel_chains' => $hotelChains,
            'total_price_range' => $totalPriceRange,
            'offset' => $offset,
            'limit' => $limit
        );

//        echo "<pre>" . print_r($urlParams, true) . "</pre>";
//        die();


        $data = $this->get('despegar')->getHotelsAvailabilities($urlParams);

        if (!isset($data['code'])) {
            $results = [
                'status' => 'success',
                'code' => 200,
                'data' => $data
            ];

        } else {
            $results = [
                'status' => 'error',
                'code' => 404,
            ];
        }

        $response = new JsonResponse($results);

        return $response;
    }

    /**
     * Get hotel details as available hotels
     *
     * @param Request $request
     * @return array
     *
     * @FOSRestBundleAnnotations\Route("/hotels/")
     * @ApiDoc(
     *  section="Hotel",
     *  description="Get room availability for hotel",
     *  parameters={
     *     {
     *          "name"="ids",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="0000,0001,0002",
     *          "description"="List ids."
     *      },*     {
     *          "name"="language",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="en, es, pt",
     *          "description"="Language of texts involved in the response."
     *      }
     *  },
     *  statusCodes={
     *     200="Returned when successful",
     *     404="Wrong data"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function getHotelsAction(Request $request)
    {
        /**
         * Parameters
         */
        $ids = $request->query->get('ids');
        $language = $request->query->get('language');

        /*echo "<pre>" . print_r($ids, true) . "</pre>";
        die();*/

        if ($ids == '' || $language == '') {
            $results = [
                'status' => 'error',
                'code' => 404,
            ];
        } else {
            $urlParams = array(
                'ids' => $ids,
                'language' => $language,
                'options' => 'information,amenities,pictures,room_types(pictures,information,amenities)',
                'resolve' => 'merge_info',
                'catalog_info' => 'true'
            );

            $data = $this->get('despegar')->getHotelsDetails($urlParams, true);

            $results = [
                'status' => 'success',
                'code' => 200,
                'data' => $data
            ];

        }

        $response = new JsonResponse($results);

        return $response;

    }

    /**
     * Get id for booking form
     *
     * @param Request $request
     * @param String $id
     * @return array
     *
     * @FOSRestBundleAnnotations\Route("/hotel/availabilities/{id}")
     * @ApiDoc(
     *  section="Hotel",
     *  description="Get room availability for hotel",
     *  parameters={
     *     {
     *          "name"="language",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="en, es, pt",
     *          "description"="Language of texts involved in the response."
     *      },
     *     {
     *          "name"="country_code",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="AR",
     *          "description"="The country code in which the request is made."
     *      },
     *     {
     *          "name"="currency",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="ARS, USD",
     *          "description"="The currency upon which the prices will be shown."
     *      },
     *     {
     *          "name"="checkin_date",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="YYYY-MM-DD",
     *          "description"="Date of checkin."
     *      },
     *     {
     *          "name"="checkout_date",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="YYYY-MM-DD",
     *          "description"="Date of checkout."
     *      },
     *     {
     *          "name"="distribution",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="1-2-5!1-12-9",
     *          "description"="Room distribution."
     *      }
     *  },
     *  statusCodes={
     *     200="Returned when successful",
     *     404="Wrong data"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function getAvailabilitiesIdAction(Request $request, $id)
    {
        /**
         * Parameters
         */
        $countryCode = $request->query->get('country_code');
        $checkinDate = $request->query->get('checkin_date');
        $checkoutDate = $request->query->get('checkout_date');
        $distributions = $request->query->get('distribution');
        $language = $request->query->get('language');
        $currency = $request->query->get('currency');

        $urlParams = array(
            'language' => $language,
            'country_code' => $countryCode,
            'currency' => $currency,
            'checkin_date' => $checkinDate,
            'checkout_date' => $checkoutDate,
            'distribution' => $distributions,
            'classify_roompacks_by' => 'rate_plan',
        );

        $data = $this->get('despegar')->getHotelsAvailabilitiesDetail($id, $urlParams);


        if (!isset($data['code'])) {
            $results = [
                'status' => 'success',
                'code' => 200,
                'data' => $data
            ];

        } else {
            $results = [
                'status' => 'error',
                'code' => 404,
            ];
        }

        $response = new JsonResponse($results);

        return $response;

    }


    /**
     * Get room availability for hotel
     *
     * @param Request $request
     * @return array
     *
     * @FOSRestBundleAnnotations\Route("/hotel/booking/")
     * @ApiDoc(
     *  section="Hotel",
     *  description="Get room availability for hotel",
     *  parameters={
     *     {
     *          "name"="country_code",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="AR",
     *          "description"="The country code in which the request is made."
     *      },
     *     {
     *          "name"="context_language",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="en, es, pt",
     *          "description"="Language of texts"
     *      },
     *     {
     *          "name"="availability_token",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="",
     *          "description"="Token booking."
     *      }
     *  },
     *  statusCodes={
     *     200="Returned when successful",
     *     404="Wrong data"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function postHotelBookingAction(Request $request)
    {
        /**
         * Parameters
         */
        $params = $this->getRequest()->request->all();

        if (!isset($params['country_code']) || !isset($params['context_language']) || !isset($params['availability_token'])) {
            $results = [
                'status' => 'error',
                'code' => 404,
            ];
        } else {
            $countryCode = $params['country_code'];
            $language = $params['context_language'];
            $availabilityToken = $params['availability_token'];
            $clientIp = $request->getClientIp();
            $agentCode = $this->getParameter('agent_code');
            $userAgent = $request->headers->get('User-Agent');

            $urlParams = array(
                "source" => array(
                    "country_code" => $countryCode
                ),
                "reservation_context" => array(
                    "context_language" => $language,
                    "shown_currency" => "ARS",
                    "threat_metrix_id" => "25",
                    "agent_code" => $agentCode,
                    "client_ip" => $clientIp,
                    "user_agent" => $userAgent
                ),
                "keys" => array(
                    "availability_token" => $availabilityToken
                )
            );

            $data = $this->get('despegar')->postHotelsBookings($urlParams);

            $results = [
                'status' => 'success',
                'code' => 200,
                'data' => $data
            ];
        }

        $response = new JsonResponse($results);

        return $response;

    }

    /**
     * Get form bookings
     *
     * @param String $id
     * @return array
     *
     * @FOSRestBundleAnnotations\Route("/hotel/booking/{id}/forms")
     * @ApiDoc(
     *  section="Hotel",
     *  description="Get room availability for hotel",
     *  statusCodes={
     *     200="Returned when successful",
     *     404="Wrong data"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function getHotelBookingAction($id)
    {

        $data = $this->get('despegar')->getHotelsBookingsForms($id);

        if (!in_array('data', $data)) {
            $results = [
                'status' => 'success',
                'code' => 200,
                'data' => $data
            ];
        } else {
            $results = [
                'status' => 'error',
                'code' => 404,
            ];
        }

        $response = new JsonResponse($results);

        return $response;

    }

    /**
     * Get room availability for hotel
     *
     * @param Request $request
     * @return array
     *
     * @FOSRestBundleAnnotations\Route("/hotel/booking/")
     * @ApiDoc(
     *  section="Hotel",
     *  description="Get room availability for hotel",
     *  parameters={
     *     {
     *          "name"="hotel_availabilitiesId",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="JSON Data",
     *          "description"="Hotel Availabilities JSON"
     *      },
     *     {
     *          "name"="price_detail",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="JSON Data",
     *          "description"="Price Detail JSON"
     *      },
     *     {
     *          "name"="language",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="en, es, pt",
     *          "description"="Language of texts involved in the response."
     *      },
     *     {
     *          "name"="currency",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="ARS,USD",
     *          "description"="Currency."
     *      },
     *     {
     *          "name"="checkin_date",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="YYYY-MM-DD",
     *          "description"="Date of checkin."
     *      },
     *     {
     *          "name"="checkout_date",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="YYYY-MM-DD",
     *          "description"="Date of checkout."
     *      },
     *     {
     *          "name"="distribution",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="1-2-5!1-12-9",
     *          "description"="Room distribution."
     *      },
     *     {
     *          "name"="booking_id",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="",
     *          "description"="Booking ID"
     *      },
     *     {
     *          "name"="tokenize_key",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="",
     *          "description"="Tokenize Key"
     *      },
     *     {
     *          "name"="payment_method",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Payment Method"
     *      },
     *     {
     *          "name"="number_card",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="0000-0000-0000-0000",
     *          "description"="Number Card"
     *      },
     *     {
     *          "name"="expiration",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="2018-02-01 00:00:00.000000",
     *          "description"="Expiration Date card"
     *      },
     *     {
     *          "name"="security_code",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="000 or 0000",
     *          "description"="Security Code"
     *      },
     *     {
     *          "name"="owner_name",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Owner Name"
     *      },
     *     {
     *          "name"="owner_documenttype",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Type document card"
     *      },
     *     {
     *          "name"="owner_documentnumber",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Number document card"
     *      },
     *     {
     *          "name"="owner_gender",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Gender"
     *      },
     *     {
     *          "name"="bank_code",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Bank Code"
     *      },
     *     {
     *          "name"="card_code",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Card Code"
     *      },
     *     {
     *          "name"="card_type",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Card Type"
     *      },
     *     {
     *          "name"="card",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Card"
     *      },
     *     {
     *          "name"="tax_status",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Taxt status"
     *      },
     *     {
     *          "name"="invoice_name",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Invoice name"
     *      },
     *     {
     *          "name"="fiscal_document",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Fiscal document"
     *      },
     *     {
     *          "name"="billing_addressstreet",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Street address billing"
     *      },
     *     {
     *          "name"="billing_addressnumber",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Number address billing"
     *      },
     *     {
     *          "name"="billing_addressfloor",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Floor address billing"
     *      },
     *     {
     *          "name"="billing_addressdepartment",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Departament address billing"
     *      },
     *     {
     *          "name"="billing_addressstate_id",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Id state address billing"
     *      },
     *     {
     *          "name"="billing_addresscity_id",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Id city address billing"
     *      },
     *     {
     *          "name"="billing_addresspostal_code",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Code Postal address billing"
     *      },
     *     {
     *          "name"="passengers",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="Data json",
     *          "description"="Full name passengers"
     *      },
     *     {
     *          "name"="email",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="email"
     *      },
     *     {
     *          "name"="type0",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Type Phone: Local, Cell Phone, Office"
     *      },
     *     {
     *          "name"="number0",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Phone Number"
     *      },
     *     {
     *          "name"="country_code0",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="+54 Country Code"
     *      },
     *     {
     *          "name"="area_code0",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Area Code"
     *      },
     *     {
     *          "name"="comment",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Comment"
     *      },
     *     {
     *          "name"="should_use_nightly_prices",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Should use nightly prices"
     *      },
     *     {
     *          "name"="vouchers",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Vouchers"
     *      },
     *     {
     *          "name"="selected_pack",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Selected pack"
     *      },
     *     {
     *          "name"="cancellation_status",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Cancellation status"
     *      },
     *     {
     *          "name"="name_hotel",
     *          "dataType"="string",
     *          "required"=false,
     *          "format"="",
     *          "description"="Name hotel"
     *      }
     *  },
     *  statusCodes={
     *     200="Returned when successful",
     *     404="Wrong data"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function patchBookingAction(Request $request)
    {
        $this->get('hotel_service')->deleteFile();
        $session = $request->getSession();
        $params = $this->getRequest()->request->all();
        $priceDetail = json_decode(json_encode($params['price_detail']));
        $bookingId = $params['booking_id'];
        $tokenizeKey = $params['tokenize_key'];
        $dataPassengers = $params['passengers'];
        $hotelAvailabilitiesId = $params['hotel_availabilitiesId'];
        $paymentMethod = $params['payment_method'];
        $numberCard = $params['number_card'];
        $number = $params['number0'];
        $expirationDate = $params['expiration'];
        $securityCode = $params['security_code'];
        $ownerName = $params['owner_name'];
        $ownerDocumenttype = $params['owner_documenttype'];
        $ownerDocumentnumber = $params['owner_documentnumber'];
        $ownerGender = $params['owner_gender'];
        $bankCode = $params['bank_code'];
        $cardCode = $params['card_code'];
        $cardType = $params['card_type'];
        $card = $params['card'];
        $currency = $params['currency'];
        $taxStatus = $params['tax_status'];
        $invoiceName = $params['invoice_name'];
        $fiscalDocument = $params['fiscal_document'];
        $billingAddressStreet = $params['billing_addressstreet'];
        $billingAddressNumber = $params['billing_addressnumber'];
        $billingAddressFloor = $params['billing_addressfloor'];
        $billingAddressDepartment = $params['billing_addressdepartment'];
        $billingAddressStateId = $params['billing_addressstate_id'];
        $billingAddressCityId = $params['billing_addresscity_id'];
        $billingAddressPostalCode = $params['billing_addresspostal_code'];
        $typePhone = $params['type0'];
        $countryCode = '+'.$params['country_code0'];
        $areaCode = $params['area_code0'];
        $comment = $params['comment'];
        $nameHotel = $params['name_hotel'];
        $shouldUseNightlyPrices = $params['should_use_nightly_prices'];
        $cancellationStatus = $params['cancellation_status'];
        $date = new \DateTime($expirationDate);
        $checkinDate = $params['checkin_date'];
        $checkoutDate = $params['checkout_date'];
        $lang = $params['lang'];
        $email = $params['email'];
        $vouchers = $params['vouchers'];
        $selectedPack = $params['selected_pack'];
        $formBookingUrl = '/v3/hotels/bookings/' . $bookingId . '/forms';

        $formBooking = $this->get('despegar')->getHotelsBookingsForms($bookingId);
        unset($formBooking['tokenize_key']);
        $formBooking['tokenize_key'] = $tokenizeKey;

        $formData = [];
        $passengers = [];
        for ($i = 0; count($dataPassengers) > $i; $i++){
            if($dataPassengers[$i]['first_name'] != ''){
                array_push($passengers, $dataPassengers[$i]);
            }
        }
        $session->remove('price_detail');
        $session->set('price_detail', $priceDetail);
        $formData['paymentMethod'] = $paymentMethod;
        if(!empty($numberCard)){$formData['number'] = $numberCard;}
        if(!empty($date)){$formData['expiration'] = $date;}
        if(!empty($securityCode)){$formData['security_code'] = $securityCode;}
        if(!empty($ownerName)){$formData['owner_name'] = $ownerName;}
        if(!empty($ownerDocumenttype)){$formData['owner_documenttype'] = $ownerDocumenttype;}
        if(!empty($ownerDocumentnumber)){$formData['owner_documentnumber'] = $ownerDocumentnumber;}
        if(!empty($ownerGender)){$formData['owner_gender'] = $ownerGender;}
        if(!empty($bankCode)){$formData['bank_code'] = $bankCode;}
        if(!empty($cardCode)){$formData['card_code'] = $cardCode;}
        if(!empty($cardType)){$formData['card_type'] = $cardType;}
        if(!empty($card)){$formData['card'] = $card;}
        if(!empty($taxStatus)){$formData['tax_status'] = $taxStatus;}
        if(!empty($invoiceName)){$formData['invoice_name'] = $invoiceName;}
        if(!empty($fiscalDocument)){$formData['fiscal_document']= $fiscalDocument;}
        if(!empty($billingAddressStreet)){$formData['billing_addressstreet'] = $billingAddressStreet;}
        if(!empty($billingAddressNumber)){$formData['billing_addressnumber'] = $billingAddressNumber;}
        if(!empty($billingAddressFloor)){$formData['billing_addressfloor'] = $billingAddressFloor;}
        if(!empty($billingAddressDepartment)){$formData['billing_addressdepartment'] = $billingAddressDepartment;}
        if(!empty($billingAddressStateId)){$formData['billing_addressstate_id'] = $billingAddressStateId;}
        if(!empty($billingAddressCityId)){$formData['billing_addresscity_id'] = $billingAddressCityId;}
        if(!empty($billingAddressPostalCode)){$formData['billing_addresspostal_code'] = $billingAddressPostalCode;}
        if(!empty($email)){$formData['email'] = $email;}
        if(!empty($typePhone)){$formData['type0'] = $typePhone;}
        if(!empty($number)){$formData['number0'] = $number;}
        if(!empty($countryCode)){$formData['country_code0'] = $countryCode;}
        if(!empty($areaCode)){$formData['area_code0'] = $areaCode;}
        if(!empty($passengers)){$formData['passengers'] = $passengers;}
        if(!empty($shouldUseNightlyPrices)){$formData['should_use_nightly_prices'] = $shouldUseNightlyPrices;}
        $formData['comment'] = $comment;
        $formData['id0'] = '';
        $formData['amount0'] = '';
        $formData['vouchers'] = $vouchers;
        $formData['idspecial_requests'] = '';
        $formData['paramsspecial_requests'] = '';
        $formData['tokenize_key'] = $tokenizeKey;
        $session->set('room_cancellation_status', $cancellationStatus);

        $last_digits = explode(' ',$numberCard);
        $data = [];
        for($i = 0; $i < 3; $i++){
            if(isset($passengers[$i]['first_name'])){
                $data[$i] = array(
                    'full_name' => $passengers[$i]['first_name'].' '.$passengers[$i]['last_name'],
                    'first_name' => $passengers[$i]['first_name'],
                    'last_name' => $passengers[$i]['last_name'],
                    'document_number' => $passengers[$i]['document_number']
                );
            }
        }

        $session->set('booking_all_data',[
            'payment' => [
                'last_digits' => $last_digits[3],
                'card_code' => $formData['card_code'],
                'selected' => $card
            ],
            'travelers' => $data,
            'contact' => $formData['country_code0'].' '.$formData['area_code0'].' '.$formData['number0']
        ]);
        $session->set('destination', [
            'text' => $nameHotel,
            'id' => $hotelAvailabilitiesId
        ]);

//        echo "<pre>".print_r($session->get('destination'), true)."</pre>";
//        die();
        $booking = $this->get('hotel_service')->bookingHotelApi(
            $formData,
            $formBookingUrl,
            $selectedPack,
            $hotelAvailabilitiesId,
            $priceDetail,
            $checkinDate,
            $checkoutDate,
            $lang,
            $email);

        if(isset($booking['code']) && $booking['code'] == 500){
            foreach ($booking['causes'] as $cause){
                if(strpos($cause, 'INVALID_LENGTH') !== false){
                    $error = 'Documento de identidad inválido';
                }elseif(strpos($cause, 'Invalid credit card for selected roompack ') !== false){
                    $error = 'Tarjeta de crédito seleccionada no válida';
                }else{
                    $error = 'No se pudo hacer la compra';
                }
            }

            $results = [
                'status' => 'error',
                'code' => 500,
                'data' => $error
            ];

        }else{
            $pnr = NULL;
            if(isset($booking['booking']['pnr'])) {
                $pnr = $booking['booking']['pnr'];
            }
            $session->set('hotel_entrance_code', $pnr);
            $session->set('reservationInternalId', $booking['reservation']->getId());
            $session->set('despegarReservationId', $booking['reservation']->getReservationId());
            $session->set('targetCurrency', $currency);

            $this->get('hotel_service')->savePdfToAttach(
                $booking['booking'],
                $hotelAvailabilitiesId,
                $email,
                $booking['reservation']->getId());

            $this->get('hotel_service')->sendBookingEmailApi(
                $booking,
                $booking['reservation']->getId(),
                $hotelAvailabilitiesId,
                $lang,
                $email,
                $booking['hotelDetails'],
                $booking['reservationDetails']
            );
            $results = [
                'status' => 'success',
                'code' => 200,
                'data' => $booking
            ];
        }

        $response = new JsonResponse($results);

        return $response;
    }

    /**
     * Get vs hotels with available rooms
     *
     * @param Request $request
     * @return response
     *
     * @FOSRestBundleAnnotations\Route("/vs/hotel/availabilities/")
     * @ApiDoc(
     *  section="Hotel",
     *  description="Get vs hotels with available rooms",
     *  parameters={
     *     {
     *          "name"="checkin_date",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="YYYY-MM-DD",
     *          "description"="Date of checkin."
     *      },
     *     {
     *          "name"="checkout_date",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="YYYY-MM-DD",
     *          "description"="Date of checkout."
     *      },
     *     {
     *          "name"="destination",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="0000",
     *          "description"="City or Hotel name"
     *      },
     *     {
     *          "name"="language",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="en, es, pt",
     *          "description"="Language of texts involved in the response."
     *      },
     *     {
     *          "name"="offset",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="0",
     *          "description"="The pagination offset for the current collection."
     *      },
     *     {
     *          "name"="limit",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="0",
     *          "description"="The number of collection results to display during pagination. Should be between 0 and 100 inclusive."
     *      },
     *     {
     *          "name"="sorting",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="Sorting criteria. Available sortings are in sorting field of response."
     *      },
     *     {
     *          "name"="amenities",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="Limits the result to the given amenities."
     *      },
     *     {
     *          "name"="hotel_type",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="Limits the result to the given hotel types"
     *      },
     *     {
     *          "name"="payment_type",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="Limits the result to the given payment types"
     *      },
     *     {
     *          "name"="meal_plans",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="Limits the result to the given meal plans."
     *      },
     *     {
     *          "name"="stars",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="Limits the result to hotels with the given stars."
     *      },
     *     {
     *          "name"="zones",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="Limits the result to the given zones."
     *      },
     *     {
     *          "name"="profiles",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="Limits the result to the given profiles."
     *      },
     *     {
     *          "name"="hotel_chains",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"=""
     *      },
     *     {
     *          "name"="total_price_range",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="Limits the result to the given total price range (Nightly rate). ie: 120-270."
     *      },
     *     {
     *          "name"="origen",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="Origen from hotel"
     *      }
     *  },
     *  statusCodes={
     *     200="Returned when successful",
     *     404="Wrong data"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function getVsAvailabilitiesAction(Request $request)
    {
        $serializer = $this->get('jms_serializer');

        /**
         * Parameters
         */
        $checkinDate = $request->query->get('checkin_date');
        $checkoutDate = $request->query->get('checkout_date');
        $destination = $request->query->get('destination');
        $language = $request->query->get('language', 'es');
        $sorting = $request->query->get('sorting');
        $amenities = $request->query->get('amenities');
        $hotelType = $request->query->get('hotel_type');
        $paymentType = $request->query->get('payment_type');
        $mealPlans = $request->query->get('meal_plans');
        $stars = $request->query->get('stars');
        $zones = $request->query->get('zones');
        $profiles = $request->query->get('profiles');
        $hotelChains = $request->query->get('hotel_chains');
        $totalPriceRange = $request->query->get('total_price_range');
        $offset = $request->query->get('offset', 1);
        $limit = $request->query->get('limit', 10);
        $origen = $request->query->get('origen', 'VS');

        /**
         * Processed date
         */
        $urlParams = array(
            'checkin_date' => $checkinDate,
            'checkout_date' => $checkoutDate,
            'destination' => $destination,
            'language' => $language,
            'sorting' => $sorting,
            'amenities' => $amenities,
            'hotel_type' => $hotelType,
            'payment_type' => $paymentType,
            'meal_plans' => $mealPlans,
            'stars' => $stars,
            'zones' => $zones,
            'profiles' => $profiles,
            'hotel_chains' => $hotelChains,
            'total_price_range' => $totalPriceRange,
            'offset' => $offset,
            'limit' => $limit,
            'origen' => $origen
        );

        $em = $this->getDoctrine()->getManager();

        if($hotelType){
            $entity = $em->getRepository('VientoSurAppAppBundle:HotelType')->findOneBy(array('name' => $hotelType));
            $urlParams['hotel_type'] = $entity->getId();
        }

        if($hotelChains){
            $entity = $em->getRepository("VientoSurAppAppBundle:HotelChain")->findOneBy(array('name' => $hotelChains));
            $urlParams['hotel_chains'] = $entity->getId();
        }

        if($mealPlans){
            $entity = $em->getRepository('VientoSurAppAppBundle:MealPlan')->findOneBy(array('name' => $mealPlans));

            $rooms = $em->getRepository('VientoSurAppAppBundle:Room')->findBy(array('mealPlan' => $entity->getId()));

            $hotel = [];
            foreach ($rooms as $room){
                if (!in_array($room->getHotel()->getId(), $hotel)){
                    $hotel[] = $room->getHotel()->getId();
                }
            }

            $urlParams['meal_plans'] = $hotel;
        }

        if($paymentType){
            $entity = $em->getRepository('VientoSurAppAppBundle:PaymentType')->findOneBy(array('name' => $paymentType));
            $rooms = $em->getRepository('VientoSurAppAppBundle:Room')->findBy(array('paymentType' => $entity->getId()));

            $hotel = [];
            foreach ($rooms as $room){
                if (!in_array($room->getHotel()->getId(), $hotel)){
                    $hotel[] = $room->getHotel()->getId();
                }
            }

            $urlParams['payment_type'] = $hotel;
        }

        if ($profiles){
            $urlParams['profiles'] = $profiles;
        }

        $dql = $em->getRepository("VientoSurAppAppBundle:Hotel")->findHotelWithParameters($urlParams);
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $offset, $limit);

        if ($pagination) {
            $array = [];
            foreach ($pagination->getItems() as $entity){
                $amenitiesService = $em->getRepository('VientoSurAppAppBundle:AmenityHotel')->findBy(array('hotel' => $entity->getId()));

                $picturesEntity = $em->getRepository('VientoSurAppAppBundle:Picture')->findBy(array('hotel' => $entity->getId()));

                $appPath = $this->container->getParameter('kernel.root_dir');
                $webPath = $appPath.'/../web/uploads/gallery_image/';
                $pictures = [];
                foreach($picturesEntity as $value){
                    $pictures[] = $webPath.$value->getImageName();
                }

                $services = [];
                foreach ($amenitiesService as $service){
                    $services[] = $service->getAmenity()->getName();
                }

                $room= $em->getRepository('VientoSurAppAppBundle:Room')->findOneBy(array('hotel'=>$entity->getId()));

                $array[] = [
                    'id' => $entity->getId(),
                    'hotel' => [
                        'id' => $entity->getId(),
                        'name' => $entity->getName(),
                        'address' => $entity->getAddress(),
                        'hotel_type_id' => $entity->getHotelTypes()->getName(),
                        'location' => [
                            'latitude' => $entity->getLatitude(),
                            'longitude' => $entity->getLongitude()
                        ],
                        'amenity_ids' => $services,
                        'stars' => $entity->getStars(),
                        'origen' => $entity->getOrigen(),
                        'percentage_gain' => $entity->getPercentageGain(),
                        'pictures' => $pictures,
                        'hotel_chain' => $entity->getHotelChain()->getName(),
                        'hotel_profile' => $entity->getProfileTrip(),
                        'room' => $room
                    ]
                ];
            }

            $results = [
                'status' => 'success',
                'code' => 200,
                'data' => $pagination,
                'items' => $array
            ];

        } else {
            $results = [
                'status' => 'error',
                'code' => 404,
            ];
        }

        return new Response($serializer->serialize($results, 'json'));
    }

    /**
     * Get detail for vs hotel
     *
     * @param Hotel $hotel
     * @return response
     *
     * @FOSRestBundleAnnotations\Route("/vs/hotel/availabilities/{id}")
     * @ApiDoc(
     *  section="Hotel",
     *  description="Get hotel detail for id",
     *  statusCodes={
     *     200="Returned when successful",
     *     404="Wrong data"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function getVsAvailabilitiesIdAction(Hotel $hotel)
    {
        $serializer = $this->get('jms_serializer');

        if ($hotel) {
            $results = [
                'status' => 'success',
                'code' => 200,
                'data' => $hotel
            ];

        } else {
            $results = [
                'status' => 'error',
                'code' => 404,
            ];
        }

        return new Response($serializer->serialize($results, 'json'));
    }


    /**
     * Get roompacks by hotel
     *
     * @param Request $request
     * @param Hotel $hotel
     * @return response
     *
     * @FOSRestBundleAnnotations\Route("/vs/hotel/{id}/roompacks/")
     * @ApiDoc(
     *  section="Hotel",
     *  description="Get roompacks by hotel",
     *  parameters={
     *     {
     *          "name"="language",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="en, es, pt",
     *          "description"="Language of texts involved in the response."
     *      },
     *     {
     *          "name"="distribution",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="1-2-5!1-12-9",
     *          "description"="Room distribution."
     *      }
     *  },
     *  statusCodes={
     *     200="Returned when successful",
     *     404="Wrong data"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function getRoompacksAction(Request $request, Hotel $hotel)
    {
        $serializer = $this->get('jms_serializer');

        $language = $request->query->get('language', 'es');
        $distributions = $request->query->get('distribution');

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VientoSurAppAppBundle:Room')->findRoomAvailables($hotel->getId());

        $roomDistribution = explode('!', $distributions);

        $roomGuests = array();

        for ($i = 0; $i < count($roomDistribution); $i++){
            if(strpos($roomDistribution[$i], '-')){
                $guests[$i] = explode('-', $roomDistribution[$i]);
                $roomGuests[$i]['adults'] = $guests[$i][0];
                $childs = 0;
                for ($j = 0; $j < count($guests[$i]); $j++){
                    $roomGuests[$i]['childs'] = $childs++;
                }
                $roomGuests[$i]['total'] = $roomGuests[$i]['childs'] + $roomGuests[$i]['adults'];
            }else{
                $roomGuests[$i]['adults'] = $roomDistribution[$i];
                $roomGuests[$i]['total'] = $roomGuests[$i]['adults'];
            }
        }

        $array = [
            'hotel' => [
                'id' => $hotel->getId()
            ],
            'roomGuests' => $roomGuests
        ];

        for ($i = 0; $i < count($entities); $i++){
            for ($j = 0; $j < count($roomGuests); $j++){
                if ($entities[$i]->getCapacity() >= $roomGuests[$j]['total']) {
                    $beds = $em->getRepository('VientoSurAppAppBundle:Bed')->findBy(array('room' => $entities[$i]->getId()));

                    $array['roompacks'][$i]['rooms'] = [
                        'id' => $entities[$i]->getId(),
                        'payment_type' => $entities[$i]->getPaymenttype()->getName(),
                        'meal_plan' => [
                            'id' => $entities[$i]->getMealPlan()->getName()
                        ],
                        'bed_options' => $beds,
                        'cancellation_policy' => [
                            'text' => $entities[$i]->getCancellationPolicity(),
                            'status' =>$entities[$i]->getCancellationPolicityStatus(),
                        ],
                        'price_detail' => [
                            'price_by_nigth' => $entities[$i]->getNightlyPrice()
                        ]
                    ];
                }
            }
        }

        $results = [
            'status' => 'success',
            'code' => 200,
            'data' => $array
        ];
        return new Response($serializer->serialize($results, 'json'));
    }
}