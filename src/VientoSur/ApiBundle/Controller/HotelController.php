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
     *          "name"="hotel_availabilities",
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
     *          "required"=true,
     *          "format"="",
     *          "description"="Payment Method"
     *      },
     *     {
     *          "name"="number_card",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="0000-0000-0000-0000",
     *          "description"="Number Card"
     *      },
     *     {
     *          "name"="expiration_date",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="2018-02-01 00:00:00.000000",
     *          "description"="Expiration Date"
     *      },
     *     {
     *          "name"="security_code",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="000 or 0000",
     *          "description"="Security Code"
     *      },
     *     {
     *          "name"="owner_name",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="",
     *          "description"="Owner Name"
     *      },
     *     {
     *          "name"="bank_code",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="",
     *          "description"="Bank Code"
     *      },
     *     {
     *          "name"="card_code",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="",
     *          "description"="Card Code"
     *      },
     *     {
     *          "name"="card_type",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="",
     *          "description"="Card Type"
     *      },
     *     {
     *          "name"="reservation_name",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="",
     *          "description"="First name, Last name, Document Number"
     *      },
     *     {
     *          "name"="email",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="",
     *          "description"="email"
     *      },
     *     {
     *          "name"="type_phone",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="",
     *          "description"="Type Phone: Local, Cell Phone, Office"
     *      },
     *     {
     *          "name"="number",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="",
     *          "description"="Phone Number"
     *      },
     *     {
     *          "name"="country_code",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="",
     *          "description"="+54 Country Code"
     *      },
     *     {
     *          "name"="area_code",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="",
     *          "description"="Area Code"
     *      },
     *     {
     *          "name"="comment",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="",
     *          "description"="Comment"
     *      },
     *     {
     *          "name"="should_use_nightly_prices",
     *          "dataType"="string",
     *          "required"=true,
     *          "format"="",
     *          "description"="should_use_nightly_prices"
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
        /**
         * Parameters
         */
//        $formBooking: formulario bookin
//        $formPay: formulario bookin  $bookingId, $hotelId, $priceDetail, $checkinDate, $checkoutDate, $lang, $email
//        paymentMethod: payment_methods->choice
//        hotel_availabilities: obtener el availability_token, idHotel

        $params = $this->getRequest()->request->all();

        $bookingId = $params['booking_id'];
        $paymentMethod = $params['payment_method'];
        $numberCard = $params['number_card'];
        $expirationDate = $params['expiration_date'];
        $securityCode = $params['security_code'];
        $ownerName = $params['owner_name'];
        $bankCode = $params['bank_code'];
        $cardCode = $params['card_code'];
        $cardType = $params['card_type'];
        $typePhone = $params['type_phone'];
        $number = $params['number'];
        $countryCode = $params['country_code'];
        $areaCode = $params['area_code'];
        $comment = $params['comment'];
        $email = $params['email'];
        $shouldUseNightlyPrices = $params['should_use_nightly_prices'];

        $hotelAvailabilities = $params['hotel_availabilities'];
        $priceDetail = $params['price_detail'];
        $checkinDate = $params['checkin_date'];
        $checkoutDate = $params['checkout_date'];
        $lang = $params['lang'];
        $$email = $params['email'];

        $formBookingId = '/v3/hotels/bookings/' . $bookingId . '/forms';

        $date = new \DateTime($expirationDate);

        $formBooking = $this->get('despegar')->getHotelsBookingsForms($bookingId);

        // Adding data to booking form
        $formData = $this->get('despegar')->getHotelsBookingsForms($bookingId);
        $formData['paymentMethod'] = $paymentMethod;
        $formData['$number'] = $numberCard;
        $formData['expiration'] = $date;
        $formData['security_code'] = $securityCode;
        $formData['owner_name'] = $ownerName;
        $formData['bank_code'] = $bankCode;
        $formData['card_code'] = $cardCode;
        $formData['card_type'] = $cardType;
        $formData['email'] = $email;
        $formData['type0'] = $typePhone;
        $formData['number0'] = $number;
        $formData['country_code0'] = $countryCode;
        $formData['area_code0'] = $areaCode;
        $formData['comment'] = $comment;
        $formData['should_use_nightly_prices'] = $shouldUseNightlyPrices;

        $bookingHotel = $this->get('hotel_service')->bookingHotel(
            $formBooking,
            $formData,
            $formBookingId,
            $hotelAvailabilities->hotel->id,
            $priceDetail,
            $checkinDate,
            $checkoutDate,
            $lang,
            $email);

        $results = [
            'status' => 'success',
            'code' => 200,
            'data' => $bookingHotel
        ];

        if ($results['data'] == null) {
            $results = [
                'status' => 'error',
                'code' => 404,
            ];
        }

        $response = new JsonResponse($results);

        return $response;
    }
}