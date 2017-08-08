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
        $countryCode        = $request->query->get('country_code');
        $checkinDate        = $request->query->get('checkin_date');
        $checkoutDate       = $request->query->get('checkout_date');
        $distributions       = $request->query->get('distribution');
        $destination        = $request->query->get('destination');
        $language           = $request->query->get('language');
        $currency           = $request->query->get('currency');
        $sorting            = $request->query->get('sorting');
        $amenities          = $request->query->get('amenities');
        $hotelType          = $request->query->get('hotel_type');
        $paymentType        = $request->query->get('payment_type');
        $mealPlans          = $request->query->get('meal_plans');
        $stars              = $request->query->get('stars');
        $zones              = $request->query->get('zones');
        $profiles           = $request->query->get('profiles');
        $hotelChains        = $request->query->get('hotel_chains');
        $totalPriceRange    = $request->query->get('total_price_range');

        /**
         * Processed date
         */

        if(!$currency){
            $currency = 'ARS';
        }

        if(!$language){
            $language = 'es';
        }

        if(!$sorting){
            $sorting = 'best_selling_descending';
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
            'payment_type'=>$paymentType,
            'meal_plans'=>$mealPlans,
            'stars'=>$stars,
            'zones'=>$zones,
            'profiles'=>$profiles,
            'hotel_chains'=>$hotelChains,
            'total_price_range'=>$totalPriceRange,
            'offset' => '0',
            'limit' => '25'
        );

//        echo "<pre>" . print_r($urlParams, true) . "</pre>";
//        die();
        $results = $this->get('despegar')->getHotelsAvailabilities($urlParams);
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
        $countryCode        = $request->query->get('country_code');
        $checkinDate        = $request->query->get('checkin_date');
        $checkoutDate       = $request->query->get('checkout_date');
        $distributions       = $request->query->get('distribution');
        $language           = $request->query->get('language');
        $currency           = $request->query->get('currency');

        $urlParams = array(
            'language' => $language,
            'country_code' => $countryCode,
            'currency' => $currency,
            'checkin_date' => $checkinDate,
            'checkout_date' => $checkoutDate,
            'distribution' => $distributions,
            'classify_roompacks_by' => 'rate_plan',
        );

        $results = $this->get('despegar')->getHotelsAvailabilitiesDetail($id, $urlParams);
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

        $countryCode        = $params['country_code'];
        $language           = $params['context_language'];
        $availabilityToken  = $params['availability_token'];
        $clientIp           = $request->getClientIp();
        $agentCode          = $this->getParameter('agent_code');
        $userAgent          = $request->headers->get('User-Agent');

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

        $results = $this->get('despegar')->postHotelsBookings($urlParams);
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

        $results = $this->get('despegar')->getHotelsBookingsForms($id);
        $response = new JsonResponse($results);

        return $response;

    }
}