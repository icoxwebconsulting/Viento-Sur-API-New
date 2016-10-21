<?php

namespace VientoSur\App\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use VientoSur\App\AppBundle\Controller\DistributionController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Company controller.
 *
 * @Route("/hotel")
 */
class HotelController extends Controller {

    public $session;

    /**
     * Lists all Company entities.
     *
     * @Route("/index/", name="hotel_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request) {
        return array();
    }

    /**
     * Autocomplete
     *
     * @Route("/autocomplete/{type}", name="despegar_autocomplete")
     * @Method("GET")
     */
    public function autoCompleteHotelCountryAction($type, Request $request)
    {
        $query = $request->get('query');
        $urlParams = [
            'query' => $query,
            'product' => $type,
            'locale' => 'es',
            'city_result' => '10'
        ];
        $despegar = $this->get('despegar');
        $result = $despegar->autocomplete($urlParams);
        $result = json_decode($result, true);

        foreach ($result as $item) {
            $city = Array();
            $city["value"] = $item["description"];
            $city["data"] = $item["code"];
            $response[] = $city;
        }
        return new JsonResponse(
            array(
                "suggestions" => $response
            )
        );
    }

    /**
     * Lists all Company entities.
     *
     * @Route("/send/flights/itineraries", name="viento_sur_app_app_homepage_send_flights")
     * @Method("POST")
     * @Template()
     */
    public function sendFlightsItinerariesAction(Request $request) {

        echo $from = $request->get('origen-city');
        echo $destination = $request->get('destino-city');
        echo $fromDate = $request->get('fromCalendarVuelo');
        echo $toDate = $request->get('toCalendarVuelo');
        echo $adultsSelect = $request->get('adultsSelect');
        echo $childrenSelect = $request->get('childrenSelect');
        echo $infantsSelect = $request->get('infantsSelect');

        if ($toDate != "") {
            $url = "https://api.despegar.com/v3/flights/itineraries?site=ar&from=" . $from . "&to=" . $destination . "&departure_date=" . $fromDate . "&adults=" . $adultsSelect . "&return_date=" . $toDate . "&children=" . $childrenSelect . "&infants=" . $infantsSelect;
        } else {
            $url = "https://api.despegar.com/v3/flights/itineraries?site=ar&from=" . $from . "&to=" . $destination . "&departure_date=" . $fromDate . "&adults=" . $adultsSelect . "&children=" . $childrenSelect . "&infants=" . $infantsSelect;
        }
// https://api.despegar.com/v3/flights/itineraries?site=ar&from=BUE&to=MIA&departure_date=2015-08-21&adults=1&group_by=default
        $items = $this->cUrlExecAction($url);
        $results = json_decode($items, true);
        return $this->render('VientoSurAppAppBundle:Hotel:listFlightsItineraries.html.twig', array('items' => $results['items']));
    }

    /**
     * Lists all Company entities.
     *
     * @Route("/send/hotels/availabilities/{offset}/{limit}", name="viento_sur_send_hotels")
     * @Method("GET")
     * @Template()
     */
    public function sendHotelsAvailabilitiesAction($offset, $limit, Request $request) {

        //TODO: destination representa la ciudad que está disponible a través del api geography (o autocomplete) cuando esté en producción
        $destination = 982;

        list($day,$month,$year)=explode("/",$request->get('start'));
        $fromCalendarHotel = $year.'-'.$month.'-'.$day;

        list($day,$month,$year)=explode("/",$request->get('end'));
        $toCalendarHotel = $year.'-'.$month.'-'.$day; //echo $fromCalendarHotel.' '.$toCalendarHotel; die();
        $habitacionesCant = $request->get('habitacionesCant'); //echo ' habitacion '.$habitacionesCant;
        $adultsSelector1 = $request->get('adultsSelector1');//echo ' adulto '.$adultsSelector1;
        $adultsSelector2 = $request->get('adultsSelector2');
        $adultsSelector3 = $request->get('adultsSelector3');
        $adultsSelector4 = $request->get('adultsSelector4');
        $childrenSelectOne = $request->get('childrenRoomSelector1');//echo ' ni7o '.$adultsSelector1; die();
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

        $urlParams = array(
            "country_code" => "AR",
            "checkin_date" => $fromCalendarHotel,
            "checkout_date" => $toCalendarHotel,
            "destination" => $destination,
            "distribution" => $distribucion,
            "language" => "es",
            "radius" => "200",
            "accepts" => "partial",
            "currency" => "USD",
            "sorting" => "best_selling_descending",
            "classify_roompacks_by" => "none",
            "roompack_choices" => "recommended",
            "offset" => $offset,
            "limit" => "10"
        );

        $despegar = $this->get('despegar');
        $results = $despegar->getHotelsAvailabilities($urlParams);
        $results = json_decode($results, true);

        $restUrl = '?' . http_build_query(array(
                "site" => "AR",
                "checkin_date" => $fromCalendarHotel,
                "checkout_date" => $toCalendarHotel,
                "distribution" => $distribucion
            ));

        $session = $request->getSession();
        $session->set('checkin_date', $request->get('start'));
        $session->set('checkout_date', $request->get('end'));

        return $this->render('VientoSurAppAppBundle:Hotel:listHotelsAvailabilities.html.twig', array(
            'items'   => $results,
            'restUrl' => $restUrl,
            'offset'   =>   $offset,
            'limit'    =>   $limit
        ));
    }


    /**
     *
     * @Route("/consult", name="viento_sur_app_consult")
     * @Method("POST")
     * @Template()
     */
    public function consultAction(Request $request) {
        $message = \Swift_Message::newInstance(null)
            ->setSubject("Consulta web Viento Sur")
            ->setFrom("not-reply@vientosur.com")
            ->setTo("sanchez91nestor@gmail.com")
            ->setBody(
                $this->renderView(
                    'VientoSurAppAppBundle:Email:contact.html.twig',
                    array(
                        'txtContactName' => $request->request->get('fullname'),
                        'txtEmail' => $request->request->get('email'),
                        'txtComments' => $request->request->get('message')
                    )
                ),
                'text/html'
            );

        $this->get('mailer')->send($message);
        $request->getSession()
            ->getFlashBag()
            ->add('success', 'Your message has been sent successfully');
    }


    /**
     *
     * @Route("/show/{idHotel}/availabilities/{restUrl}/latitude/{latitude}/longitude/{longitude}", name="viento_sur_app_app_homepage_show_hotel_id")
     * @Method("GET")
     * @Template()
     */
    public function showHotelIdAvailabilitiesAction(Request $request, $idHotel, $restUrl, $latitude, $longitude) {
        $session = $request->getSession();
        $urlParams = array(
            'language' => 'es',
            'currency' => 'USD'
        );

        $despegar = $this->get('despegar');
        $dispoHotel = $despegar->getHotelsAvailabilitiesDetail($idHotel, $restUrl, $urlParams);
        $session->set('hotelAvailabilities', $dispoHotel);
        $dispoHotel = json_decode($dispoHotel, true);

        $urlParams = array(
            'ids' => $idHotel,
            'language' => 'es',
            'options' => 'information,amenities,pictures,room_types(pictures,information,amenities)',
            'resolve' => 'merge_info',
            'catalog_info' => 'true'
        );
        $hotelDetails = $despegar->getHotelsDetails($urlParams);
        $hotelDetails = json_decode($hotelDetails, true);

        $session->set('price_detail', $dispoHotel['roompacks'][0]['price_detail']);

        return $this->render('VientoSurAppAppBundle:Hotel:showHotelIdAvailabilities.html.twig', array(
                'dispoHotel'   => $dispoHotel,
                'hotelDetails' => $hotelDetails,
                'latitude'     => $latitude,
                'longitude'    => $longitude,
                'idHotel'      => $idHotel,
                'restUrl'      => $restUrl
            )
        );
    }

    /**
     * Lists all Company entities.
     *
     * @Route("/show/details/{idHotel}", name="viento_sur_app_app_homepage_show_hotel_photo")
     * @Method("GET")
     * @Template()
     */
    public function detailsHotelListForIdAction(Request $request, $idHotel) {

        $hotelUrl = "https://api.despegar.com/v3/hotels?ids=" . $idHotel . "&language=es&options=pictures&resolve=merge_info&catalog_info=true";
        $hotel = $this->cUrlExecAction($hotelUrl);
        $hotelDetails = json_decode($hotel, true);

        return array(
            'hotelDetails' => $hotelDetails
        );
    }

    /**
     * Lists all Company entities.
     *
     * @Route("/booking/hotel/send/booking", name="viento_sur_app_app_homepage_send_hotel_booking")
     * @Method("POST")
     * @Template()
     */
    public function sendHotelBookingAction(Request $request) {

        $postParams = array(
            "source" => array(
                "country_code" => "AR"
            ),
            "reservation_context" => array(
                "context_language" => "es",
                "shown_currency" => "USD",
                "threat_metrix_id" => "25",
                "agent_code" => 'AG32502',
                "client_ip" => $request->getClientIp(),
                "user_agent" => $request->headers->get('User-Agent')
            ),
            "keys" => array(
                "availability_token" => $request->get('availability_token')
            )
        );

        $despegar = $this->get('despegar');
        //quitar ?example=true para PRODUCCION
        $formUrl = $despegar->postHotelsBookings($postParams);
        $formUrl = json_decode($formUrl, true);

        return $this->redirect($this->generateUrl('viento_sur_app_boking_hotel_pay', array(
            'formUrl' => $formUrl["next_step_url"],
            'booking_id' => $formUrl["id"],
            "roompack_choice" => $request->get('roompack_choice')
        )));
    }

    /**
     * Lists all Company entities.
     *
     * @Route("/booking/pay/", name="viento_sur_app_boking_hotel_pay")
     * @Template()
     */
    public function bookingHotelPayAction(Request $request) {

        $session = $request->getSession();
        $priceDetail = $session->get('price_detail');
       // $session->remove('price_detail');
        $roompackChoice = $request->get('roompack_choice');

        $hotelAvailabilities = json_decode($session->get('hotelAvailabilities'));
        //$session->remove('hotelAvailabilities');

        foreach ($hotelAvailabilities->roompacks as $item) {
            if ($item->choice = $roompackChoice) {
                $roompack = $item;
                break;
            }
        }

        //$roompack = json_encode($roompack, true);

        $formUrl     = $request->get('formUrl');
        $bookingId = $request->query->get('formUrl');
        $booking_id = $request->get('booking_id');

        $expiration_years = [];
        $expiration_month = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $year = date('Y');
        $new_year = strtotime ( '+5 year' , strtotime ( $year ) ) ;
        $new_y_10 = date ( 'Y' , $new_year );
        
        foreach (range($year, $new_y_10) as $y) {
            $expiration_years[$y]= $y;
        }

        $array_card_brand = ['VI'=>'Visa', 'CA'=>'MasterCard', 'AX'=>'American Express', 'DC'=>'Diners Club', 'CL'=>'Cabal', 'TN'=>'Tarjeta Naranja', 'NV'=>'Tarjeta Nevada'];
        $phone_option     = ['CELULAR'=>'Celular', 'HOME'=>'Casa', 'WORK'=>'Trabajo', 'FAX'=>'Fax', 'OTHER'=>'Otro'];

        $session->set('booking-id', $bookingId);

        $despegar = $this->get('despegar');
        $sessionForm = $request->getSession();
        $sessionForm->set('url_detail_form', $despegar->getHotelsBookingsNextStepUrl($bookingId));

        if($request->getMethod() == 'GET') {
            //quitar ?example=true para PRODUCCION
            $formBooking = $despegar->hotelsBookingsNextStep($bookingId);
            $session->set('formBooking', $formBooking);
        } else {
            $formBooking = $session->get('formBooking');
            //$session->remove('formBooking');
        }
        $formBooking = json_decode($formBooking, true);

        /* start form */
        $formNewPay = $this->createFormBuilder($formBooking);
        $formHelper = $this->get('form_helper');
        $formNewPay = $formHelper->initForm($formBooking, $formNewPay, $roompackChoice);
        $formNewPaySend = $formNewPay->getForm();
        
        if($request->getMethod() == 'POST'){
            
            $formNewPaySend->handleRequest($request);
            
            if ($formNewPaySend->isValid()) {
               
               $formNewPaySend = $formNewPaySend->getData(); 
                
               //procesar formulario recibido
                $array_for_dvault = [
                    'brand_code'       =>'VI',
                    'number'           =>$formNewPaySend['hotelInputDefinition:paymentDefinition:cardDefinition:number:value'],
                    'expiration_month' =>$formNewPaySend['expiration']->format('m'),
                    'expiration_year'  =>$formNewPaySend['expiration']->format('Y'),
                    'security_code'    =>$formNewPaySend['hotelInputDefinition:paymentDefinition:cardDefinition:securityCode:value'],
                    'bank'             =>"AR-VI-*-CREDIT",//TODO: Colocar valor requerido
                    'seconds_to_live'  =>'600',
                    'holder_name'      =>$formNewPaySend['hotelInputDefinition:paymentDefinition:cardDefinition:ownerName:value'],
                ];

                $response = $despegar->dVault($formNewPaySend['tokenize_key'], $array_for_dvault);
                if ($response) {
                    if (isset($response->secure_token)) {
                        //obtengo los valores ya seteados según la selección
                        $fillData = $formHelper->fillFormData($formBooking, $formNewPaySend);
                        $seletedPack = $formHelper->getSelectedPack();
                        $form_id_booking = $seletedPack['id'];

                        $patchParams = [];
                        $patchParams['payment_method_choice'] = "2";
                        $patchParams['secure_token_information'] = array('secure_token' => $response->secure_token);
                        $patchParams['form'] = $fillData;

                        //quitar ?example=true para PRODUCCION
                        $response = $despegar->patchHotelsBookings($bookingId, $form_id_booking, $patchParams);
                        echo 'Response: <pre>';
                        print_r($response);
                        echo '</pre><br/>';
                        exit();
                    } else {
                        //TODO: Error en dVault response token
                    }
                } else {
                    //TODO: Error en dVault validation
                }
            }
        }
        
        return array(
            'formBooking'      => $formBooking,
            'price_detail'     => $priceDetail,
            'formUrl'          => $formUrl,
            'roompack_choice'  => $roompackChoice,
            'expiration_years' => $expiration_years,
            'expiration_month' => $expiration_month,
            'booking_id'       => $booking_id,
            'formNewPay'       => $formNewPaySend->createView(),
            'paymentMethods'   => $roompack->payment_methods
        );
    }

    private function cUrlExecAction($url) {
        $cSession = curl_init();
        curl_setopt($cSession, CURLOPT_URL, $url);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_HTTPHEADER, array('X-ApiKey:2864680fe4d74241aa613874fa20705f'));
        curl_setopt($cSession, CURLOPT_HEADER, false);
        curl_setopt($cSession, CURLOPT_ACCEPT_ENCODING, "");
        //step3
        $results = curl_exec($cSession);
        //step4
        curl_close($cSession);

        return $results;
    }
}
