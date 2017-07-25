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
     * Get hotel availabilities
     *
     * @param Request $request
     * @return array
     *
     * @FOSRestBundleAnnotations\Route("/hotel/availabilities/")
     * @ApiDoc(
     *  section="Hotel",
     *  description="Get all destinations",
     *  parameters={
     *     {
     *          "name"="country_code",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="The country code in which the request is made."
     *      },
     *     {
     *          "name"="checkin_date",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="Date of checkin."
     *      },
     *     {
     *          "name"="checkout_date",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="Date of checkout."
     *      },
     *     {
     *          "name"="destination",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="Id city or Id hotel"
     *      },
     *     {
     *          "name"="distribution",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="Room distribution."
     *      },
     *     {
     *          "name"="language",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="Language of texts involved in the response."
     *      },
     *     {
     *          "name"="currency",
     *          "dataType"="string",
     *          "required"=true,
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
     *      },
     *  },
     *  statusCodes={
     *     200="Returned when successful",
     *     404="Destination not found"
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
         * Data demo initial
         *
         * Array
         * (
         * [country_code] => AR
         * [checkin_date] => 2017-07-25
         * [checkout_date] => 2017-07-29
         * [destination] => 982
         * [distribution] => 1
         * [language] => es
         * [radius] => 200
         * [currency] => ARS
         * [sorting] => best_selling_descending
         * [classify_roompacks_by] => none
         * [roompack_choices] => recommended
         * [offset] => 0
         * [limit] => 25
         * )
         */

        /**
         * Parameters
         */
        $countryCode        = $request->query->get('country_code');
        $checkinDate        = $request->query->get('checkin_date');
        $checkoutDate       = $request->query->get('checkout_date');
        $distribution       = $request->query->get('distribution');
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
        $distributions = str_replace(',', '!', str_replace(' ', '', $distribution));

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
}