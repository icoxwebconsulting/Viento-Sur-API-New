<?php

namespace VientoSur\App\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use VientoSur\App\AppBundle\Controller\DistributionController;

/**
 * Company controller.
 *
 * @Route("/hotel")
 */
class HotelController extends Controller {

    /**
     * Lists all Company entities.
     *
     * @Route("/index/", name="hotel_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request) {
        return array();
        //return $this->render('VientoSurAppAppBundle:Hotel:index.html.twig', array('name' => $name));
    }

    /**
     * Lists all Company entities.
     *
     * @Route("/autocomplete/", name="hotel_autocomplete")
     * @Method("GET")
     * @Template()
     */
    public function autocompleteHotelAction(Request $request) {
        $query = $request->get('query');
        $url = "https://api.despegar.com/v3/autocomplete?query=" . $query . "&product=HOTELS&locale=es&city_result=10";
        $cities = $this->cUrlExecAction($url);
        $results = json_decode($cities, true);

        foreach ($results as $item) {
            $city = Array();

            $city["value"] = $item["description"];
            $city["data"] = substr($item["id"], 5);
            $response[] = $city;
        }
        return new JsonResponse(array("suggestions" => $response));
        //return $this->render('VientoSurAppAppBundle:Hotel:index.html.twig', array('name' => $name));
    }

    /**
     * Lists all Company entities.
     *
     * @Route("/send/flights/itineraries", name="viento_sur_app_app_homepage_send_flights")
     * @Method("POST")
     * @Template()
     */
    public function sendFlightsItinerariesAction(Request $request) {

        echo $from = $request->get('origin');
        echo $destination = $request->get('destination');
        echo $fromDate = $request->get('fromDate');
        echo $toDate = $request->get('toDate');
        echo $adultsSelect = $request->get('adultsSelect');
        echo $childrenSelect = $request->get('childrenSelect');
        echo $infantsSelect = $request->get('infantsSelect');

        $url = "https://api.despegar.com/v3/flights/itineraries?site=ar&from=" . $from . "&to=" . $destination . "&departure_date=" . $fromDate . "&adults=" . $adultsSelect . "&return_date=" . $toDate . "&children=" . $childrenSelect . "&infants=" . $infantsSelect;
        $items = $this->cUrlExecAction($url);
        $results = json_decode($items, true);
        return $this->render('VientoSurAppAppBundle:Hotel:listFlightsItineraries.html.twig', array('items' => $results['items']));
    }

    /**
     * Lists all Company entities.
     *
     * @Route("/send/hotels/availabilities", name="viento_sur_app_app_homepage_send_hotels")
     * @Method("POST")
     * @Template()
     */
    public function sendHotelsAvailabilitiesAction(Request $request) {
        //step2
        $destination = $request->get('cityInput');
        $fromCalendarHotel = $request->get('fromCalendarHotel');
        $toCalendarHotel = $request->get('toCalendarHotel');
        $habitacionesCant = $request->get('habitacionesCant');
        $adultsSelector1 = $request->get('adultsSelector1');
        $adultsSelector2 = $request->get('adultsSelector2');
        $adultsSelector3 = $request->get('adultsSelector3');
        $adultsSelector4 = $request->get('adultsSelector4');
        $childrenSelectOne = $request->get('childrenRoomSelector1');
        $childrenSelectTwo = $request->get('childrenRoomSelector2');
        $childrenSelectTree = $request->get('childrenRoomSelector3');
        $childrenSelectFour = $request->get('childrenRoomSelector4');

        $OneChildrenOne = $request->get('childAgeSelector-1-1');
        $OneChildrenTwo = $request->get('childAgeSelector-1-2');
        $OneChildrenTree = $request->get('childAgeSelector-1-3');
        $OneChildrenFour = $request->get('childAgeSelector-1-4');
        $OneChildrenFive = $request->get('childAgeSelector-1-5');
        $OneChildrenSix = $request->get('childAgeSelector-1-6');

        $TwoChildrenOne = $request->get('childAgeSelector-2-1');
        $TwoChildrenTwo = $request->get('childAgeSelector-2-2');
        $TwoChildrenTree = $request->get('childAgeSelector-2-3');
        $TwoChildrenFour = $request->get('childAgeSelector-2-4');
        $TwoChildrenFive = $request->get('childAgeSelector-2-5');
        $TwoChildrenSix = $request->get('childAgeSelector-2-6');

        $TreeChildrenOne = $request->get('childAgeSelector-3-1');
        $TreeChildrenTwo = $request->get('childAgeSelector-3-2');
        $TreeChildrenTree = $request->get('childAgeSelector-3-3');
        $TreeChildrenFour = $request->get('childAgeSelector-3-4');
        $TreeChildrenFive = $request->get('childAgeSelector-3-5');
        $TreeChildrenSix = $request->get('childAgeSelector-3-6');

        $FourChildrenOne = $request->get('childAgeSelector-4-1');
        $FourChildrenTwo = $request->get('childAgeSelector-4-2');
        $FourChildrenTree = $request->get('childAgeSelector-4-3');
        $FourChildrenFour = $request->get('childAgeSelector-4-4');
        $FourChildrenFive = $request->get('childAgeSelector-4-5');
        $FourChildrenSix = $request->get('childAgeSelector-4-6');

        $infantsSelect = $request->get('infantsSelect1');
        $distribucionClass = new DistributionController();
        $distribucion = $distribucionClass->createDistribution($habitacionesCant, $adultsSelector1, $adultsSelector2, $adultsSelector3, $adultsSelector4, $childrenSelectOne, $childrenSelectTwo, $childrenSelectTree, $childrenSelectFour, $OneChildrenOne, $OneChildrenTwo, $OneChildrenTree, $OneChildrenFour, $OneChildrenFive, $OneChildrenSix, $TwoChildrenOne, $TwoChildrenTwo, $TwoChildrenTree, $TwoChildrenFour, $TwoChildrenFive, $TwoChildrenSix, $TreeChildrenOne, $TreeChildrenTwo, $TreeChildrenTree, $TreeChildrenFour, $TreeChildrenFive, $TreeChildrenSix, $FourChildrenOne, $FourChildrenTwo, $FourChildrenTree, $FourChildrenFour, $FourChildrenFive, $FourChildrenSix);
        $url = "https://api.despegar.com/v3/hotels/availabilities?site=AR&checkin_date=" . $fromCalendarHotel . "&checkout_date=" . $toCalendarHotel . "&destination=" . $destination . "&distribution=" . $distribucion . "&language=es&accepts=partial";
        $hotels = $this->cUrlExecAction($url);
        $results = json_decode($hotels, true);
        //return print_r($results);
        $restUrl = "?site=AR&checkin_date=" . $fromCalendarHotel . "&checkout_date=" . $toCalendarHotel . "&distribution=" . $distribucion;

        return $this->render('VientoSurAppAppBundle:Hotel:listHotelsAvailabilities.html.twig', array(
                    'items' => $results,
                    'restUrl' => $restUrl
        ));
    }

    /**
     * Lists all Company entities.
     *
     * @Route("/show/{idHotel}/availabilities/{restUrl}", name="viento_sur_app_app_homepage_show_hotel_id")
     * @Method("GET")
     * @Template()
     */
    public function showHotelIdAvailabilitiesAction(Request $request, $idHotel, $restUrl) {

        $url = "https://api.despegar.com/v3/hotels/availabilities/" . $idHotel . $restUrl . "&language=en&currency=USD";
        $itemDispo = $this->cUrlExecAction($url);
        $dispoHotel = json_decode($itemDispo, true);

        $hotelUrl = "https://api.despegar.com/v3/hotels?ids=" . $idHotel . "&language=es%2Cen&options=information,amenities,pictures&resolve=merge_info&catalog_info=true";
        $hotel = $this->cUrlExecAction($hotelUrl);
        $hotelDetails = json_decode($hotel, true);

        return $this->render('VientoSurAppAppBundle:Hotel:showHotelIdAvailabilities.html.twig', array(
                    'dispoHotel' => $dispoHotel,
                    'hotelDetails' => $hotelDetails
                        )
        );
    }

    /**
     * Lists all Company entities.
     *
     * @Route("/show/details{idHotel}", name="viento_sur_app_app_homepage_show_hotel_photo")
     * @Method("GET")
     * @Template()
     */
    public function detailsHotelListForIdAction(Request $request, $idHotel) {

        $hotelUrl = "https://api.despegar.com/v3/hotels?ids=" . $idHotel . "&language=es%2Cen&options=pictures&resolve=merge_info&catalog_info=true";
        $hotel = $this->cUrlExecAction($hotelUrl);
        $hotelDetails = json_decode($hotel, true);

        return array(
            'hotelDetails' => $hotelDetails
        );
    }

    /**
     * Lists all Company entities.
     *
     * @Route("/booking/hotel/send", name="viento_sur_app_app_homepage_send_hotel_booking")
     * @Method("POST")
     * @Template()
     */
    public function sendHotelBookingAction(Request $request) {

//        $hotelUrl = "https://api.despegar.com/v3/hotels?ids=" . $idHotel . "&language=es%2Cen&options=pictures&resolve=merge_info&catalog_info=true";
//        $hotel = $this->cUrlExecAction($hotelUrl);
//        $hotelDetails = json_decode($hotel, true);

        echo $request->getClientIp();
        echo $request->getLocale();
        echo $request->headers->get('User-Agent');
        $miArray = array("source"=>array(
                                "country_code"=>"AR"),
                         "reservation_context"=> array(
                                "shown_currency"=> "USD",
                                "threat_metrix_id"=> "25",
                                "context_language"=> "ES",
                                "client_ip"=> "120.12.352.25",
                                "user_agent"=> "Mozilla/5.0 (Windows NT 6.3; rv:36.0)Gecko/20100101 Firefox/36.0"),
                         "keys"=>array(
                                "availability_token"=>"3c81f6c0-6c8a-469c-a0d8-983ab56bef32")
                    );
       $postvars = json_encode($miArray);
       $url = "https://api.despegar.com/v3/hotels/bookings";
       $response = $this->cUrlExecPostBookingAction($url,$miArray);
      
       print_r($response);
        //192.168.1.6
        die();
    }

    private function cUrlExecAction($url) {

        //step1
        $cSession = curl_init();
        curl_setopt($cSession, CURLOPT_URL, $url);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_HTTPHEADER, array('X-ApiKey:2864680fe4d74241aa613874fa20705f'));
        curl_setopt($cSession, CURLOPT_HEADER, false);
        //step3
        $results = curl_exec($cSession);
        //step4
        curl_close($cSession);

        return $results;
    }
    
    
    private function cUrlExecPostBookingAction($url,$postvars) {

        //step1
        $postvars = json_encode($postvars);
        $cSession = curl_init();
        curl_setopt($cSession, CURLOPT_URL, $url);
        curl_setopt($cSession, CURLOPT_POST, true);                //0 for a get request
        curl_setopt($cSession, CURLOPT_POSTFIELDS,$postvars);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_HTTPHEADER, array('X-ApiKey:2864680fe4d74241aa613874fa20705f'));
        curl_setopt($cSession, CURLOPT_HEADER, false);
        //step3
        $results = curl_exec($cSession);
        //step4
        curl_close($cSession);

        return $results;
    }

}
