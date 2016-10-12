<?php

namespace VientoSur\App\AppBundle\Controller;

use GuzzleHttp\Exception\ClientException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use VientoSur\App\AppBundle\Controller\DistributionController;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;


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
     * Lists all Company entities.
     *
     * @Route("/autocomplete/", name="hotel_autocomplete")
     * @Method("GET")
     * @Template()
     */
    public function autoCompleteHotelAction(Request $request) {
        $query = $request->get('query');
        $url = "https://api.despegar.com/v3/autocomplete?query=" . $query . "&product=HOTELS&locale=es&city_result=10";
        $cities = $this->cUrlExecAutoCompleteAction($url);
        $results = json_decode($cities, true);
        //print_r($results);die();
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
     * @Route("/autocomplete/vuelo", name="vuelo_autocomplete")
     * @Method("GET")
     * @Template()
     */
    public function autoCompleteHotelCountryAction(Request $request) {
        $query = $request->get('query');
        $url = "https://api.despegar.com/v3/autocomplete?query=" . $query . "&product=HOTELS&locale=es&city_result=10";
        $cities = $this->cUrlExecAutoCompleteAction($url);
        $results = json_decode($cities, true);

        foreach ($results as $item) {
            $city = Array();

            $city["value"] = $item["description"];
            $city["data"] = $item["code"];
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

        //step2
        //echo $request->get('autocomplete');
        //$destination = $request->get('cityInput'); //echo '  '.$destination;
        $destination = 982;
        //$fromCalendarHotel = $request->get('start'); echo ' '.$fromCalendarHotel;
        list($day,$month,$year)=explode("/",$request->get('start'));
        $fromCalendarHotel = $year.'-'.$month.'-'.$day;
        //echo $fromCalendarHotel;
        //$toCalendarHotel = $request->get('end'); echo ' '.$toCalendarHotel; die();
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
        $url = "https://api.despegar.com/v3/hotels/availabilities?country_code=AR&checkin_date=" . $fromCalendarHotel . "&checkout_date=" . $toCalendarHotel . "&destination=" . $destination . "&distribution=" . $distribucion . "&language=es&radius=200&accepts=partial&currency=USD&sorting=best_selling_descending&classify_roompacks_by=none&roompack_choices=recommended&offset=".$offset."&limit=10";
        $hotels = $this->cUrlExecAction($url);
        $results = json_decode($hotels, true);
        //return print_r($results);die();
        $restUrl = "?site=AR&checkin_date=" . $fromCalendarHotel . "&checkout_date=" . $toCalendarHotel . "&distribution=" . $distribucion;

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
        $url = "https://api.despegar.com/v3/hotels/availabilities/" . $idHotel . $restUrl . "&language=en&currency=USD";
        $itemDispo = $this->cUrlExecAction($url);
        $dispoHotel = json_decode($itemDispo, true); //print_r($dispoHotel['roompacks'][1]['rooms']);die();

        $hotelUrl = "https://api.despegar.com/v3/hotels?ids=" . $idHotel . "&language=es%2Cen&options=information,amenities,pictures,room_types(pictures,information,amenities)&resolve=merge_info&catalog_info=true";
        $hotel = $this->cUrlExecAction($hotelUrl);
        $hotelDetails = json_decode($hotel, true);//print_r($hotelDetails);die();

        $session = $request->getSession();
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
     * @Route("/booking/hotel/send/booking", name="viento_sur_app_app_homepage_send_hotel_booking")
     * @Method("POST")
     * @Template()
     */
    public function sendHotelBookingAction(Request $request) {
        //$request->headers->get('User-Agent')
        //echo $request->get('availability_token');
        // echo $request->get('availability_token');die();
        //echo $request->get('availability_token');

        $arrayData = array("source" => array(
            "country_code" => "AR"),
            "reservation_context" => array(
                "context_language" => $request->getLocale(),
                "shown_currency" => "USD",
                "threat_metrix_id" => "25",
                "client_ip" => "190.200.42.169",
                "user_agent" => "Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:34.0) Gecko/20100101 Firefox/34.0 )"
            ),
            "keys" => array(
                // "availability_token" => "31e27bc2-fe88-473f-8bb8-1afb5b8d3a6b"
                "availability_token" => $request->get('availability_token')
            )
        );

        //print_r($arrayData);
        //quitar ?example=true para PRODUCCION
        $url = "https://api.despegar.com/v3/hotels/bookings?example=true";
        $response = $this->cUrlExecPostBookingAction($arrayData, $url);
        $formUrl = json_decode($response, true);
        //print_r($formUrl);die();

        return $this->redirect($this->generateUrl('viento_sur_app_boking_hotel_pay', array('formUrl' => $formUrl["next_step_url"]))
        );
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
        //quitar ?example=true para PRODUCCION
        $bookingId = $request->query->get('formUrl');
        $url = "https://api.despegar.com" . $bookingId ."?example=true";
        $expiration_years = [];

        $expiration_month = [0=>'Mes', 01, 02, 03, 04, 05, 06, 07, 08, 09, 10, 11, 12];
        
        $year = date('Y');
        $new_year = strtotime ( '+5 year' , strtotime ( $year ) ) ;
        $new_y_10 = date ( 'Y' , $new_year );
        
        foreach (range($year, $new_y_10) as $y) {
            $expiration_years[$y]= $y;
        }

        $array_card_brand = ['VI'=>'Visa', 'CA'=>'MasterCard', 'AX'=>'American Express', 'DC'=>'Diners Club', 'CL'=>'Cabal', 'TN'=>'Tarjeta Naranja', 'NV'=>'Tarjeta Nevada'];
        
        $phone_option     = ['CELULAR'=>'Celular', 'HOME'=>'Casa', 'WORK'=>'Trabajo', 'FAX'=>'Fax', 'OTHER'=>'Otro'];
        
        $this->get('session')->set('booking-id', $bookingId);

        $sessionForm = $request->getSession();
        $sessionForm->set('url_detail_form', $url);
        $formResponse = $this->cUrlExecAction($url);
        $formBooking = json_decode($formResponse, true);
        
        $formNewPay = $this->createFormBuilder($formBooking);
        
        foreach ($formBooking['dictionary']['form_choices'][1]['passengers'] AS $k=>$passengers){
            $formNewPay->add("first_name_$k", 'text',['label' => 'Nombre', 'required' => TRUE, 'attr' => array('class' => 'form-control', 'placeholder' => 'Como figura en el documento'), 'constraints' => array(
                    new Length(array('min' => 3)),
            )]);
            $formNewPay->add("last_name_$k", 'text',['label' => 'Apellido','required' => TRUE, 'placeholder' => 'Como figura en el documento', 'attr' => array('class' => 'form-control'), 'constraints' => array(
                    new Length(array('min' => 3)),
            )]);
            $formNewPay->add("document_number_$k", 'text',['label' => 'DNI','required' => TRUE, 'placeholder' => 'Como figura en el documento', 'attr' => array('class' => 'form-control'), 'constraints' => array(
                    new Length(array('min' => 7)), new Regex(array(
                'pattern'   => $passengers['document_number']['regex_validations'][2]['regex'],
                'match'     => true,
                'message'   => 'Número de Documento no Válido.'
            )))]);
        }
        
        $formNewPay->add("credit_card_card_brand", 'choice',['label' => 'Tarjeta','required' => TRUE, 'attr' => array('class' => 'form-control'), 'choices'=>$array_card_brand]);
        
        $formNewPay->add("credit_card_number", 'text',['label' => 'Número','required' => TRUE, 'attr' => array('class' => 'form-control'), 'constraints' => array(
                    new Length(array('min' => 6)))]);
        
        $formNewPay->add("credit_card_expiration_month", 'choice',['label' => 'Vencimiento','required' => TRUE, 'attr' => array('class' => 'form-control'), 'choices'=>$expiration_month, 'placeholder' => 'Mes']);
        $formNewPay->add("credit_card_expiration_year", 'choice',['label' => '&nbsp;','required' => TRUE, 'attr' => array('class' => 'form-control'), 'choices'=>$expiration_years, 'placeholder' => 'Año']);
        $formNewPay->add("credit_card_security_code", 'text',['label' => 'Código de Seguridad', 'required' => TRUE, 'attr' => array('class' => 'form-control'), 'constraints' => array(
                    new Length(array('min' => 3)),
            )]);
        
        $formNewPay->add("credit_card_owner_name", 'text',['label' => 'Titular de la tarjeta', 'required' => TRUE, 'placeholder' => 'Como figura en la tarjeta', 'attr' => array('class' => 'form-control'), 'constraints' => array(
                    new Length(array('min' => 2, 'max' => 35)) ,new Regex(array(
                'pattern'   => '^([a-zA-Z]+\s)+[a-zA-Z]+$',
                'match'     => true,
                'message'   => 'Nombre del propietario no válido.'
            )))]);
        
        $formNewPay->add("credit_card_owner_document_number",'text',['label' => 'DNI del titular de la tarjeta','required' => TRUE, 'attr' => array('class' => 'form-control'), 'constraints' => array(
                    new Length(array('min' => 7)), new Regex(array(
                'pattern'   => '^(?!([0-9])\1*$).*$',
                'match'     => true,
                'message'   => 'Número de Documento no Válido.'
            )))]);
         
         $formNewPay->add('contact_email', 'repeated', array(
            'type' => 'email',
            'invalid_message' => 'Los campos de correo electrónico deben coincidir.',
            'options' => array('attr' => array('class' => 'form-control')),
            'required' => TRUE,
            'first_options'  => array('label' => 'E-mail (donde recibirá su voucher)'),
            'second_options' => array('label' => 'Confirme su e-mail'),'constraints' => array(
                new Length(array('min' => 0, 'max'=>128)), new Regex(array(
            'pattern'   => '^[\w\.-]+@([a-zA-Z0-9-]+\.)+[a-zA-Z0-9]{2,4}$',
            'match'     => true,
            'message'   => 'Email Inválido.'
            )))));
       
        $formNewPay->add("contact_phones_options", 'choice',['label' => 'Teléfono','required' => TRUE, 'attr' => array('class' => 'form-control'), 'choices'=>$phone_option]);
        
        $formNewPay->add("contact_phones_country_code", 'text',['label' => 'País','required' => TRUE, 'attr' => array('class' => 'form-control'), 'constraints' => array(
                 new Regex(array(
                'pattern'   => '^[0-9]{1,3}$',
                'match'     => true,
                'message'   => 'Código de país no Válido.'
            )))]);
        
        $formNewPay->add("contact_phones_area_code", 'text',['label' => 'Área','required' => TRUE, 'attr' => array('class' => 'form-control'), 'constraints' => array(
                 new Regex(array(
                'pattern'   => '^[0-9]{1,4}$',
                'match'     => true,
                'message'   => 'Código de Área no Válido.'
            )))]);
        
        $formNewPay->add("contact_phones_number", 'text',['label' => 'Número','required' => TRUE, 'attr' => array('class' => 'form-control'), 'constraints' => array(
                 new Regex(array(
                'pattern'   => '^[0-9]{1,4}$',
                'match'     => true,
                'message'   => 'Número de Teléfono no Válido.'
            )))]);
        
        return array(
            'formBooking'      => $formBooking,
            'price_detail'     => $priceDetail,
            'expiration_years' => $expiration_years,
            'expiration_month' => $expiration_month,
            'formNewPay'       => $formNewPay->getForm()->createView()
        );
    }

    private function dVaultValidation($tokenizeKey, $param)
    {
        $url_test = 'https://www.despegar.com/sandbox/vault/pbdyy/validation';

        $params["brand_code"] = "VI";
        $params["number"] = "4111111111111111";
        $params["expiration_month"] = "12";
        $params["expiration_year"] = "2030";
        $params["security_code"] = "123";
        $params["bank"] = "Some bank";
        $params["seconds_to_live"] = "600";
        $params["holder_name"] = "John Teken";
        $tokenizeKey = $tokenizeKey;

        $header = [
            'Content-Type: application/json',
            'X-Tokenize-Key: '.$tokenizeKey,
            'X-Client: 2864680fe4d74241aa613874fa20705f',
            'X-ApiKey: 2864680fe4d74241aa613874fa20705f'
        ];

        //step1
        $params = json_encode($params);

        // echo 'Post: '. $url_test.'<br/>';

//        echo 'Header: <pre>';
//        print_r(json_encode($header));
//        echo '</pre><br/>';
//
//        echo 'Body: <pre>';
//        print_r(json_encode($params));
//        echo '</pre><br/>';
//
//
//
////        echo $postvars;
//        exit();

        $cSession = curl_init();
        curl_setopt($cSession, CURLOPT_URL, $url_test);
        curl_setopt($cSession, CURLOPT_POST, true);
        curl_setopt($cSession, CURLOPT_POSTFIELDS, $params);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_HTTPHEADER, $header);
        curl_setopt($cSession, CURLOPT_HEADER, false);
        //step3
        $results = curl_exec($cSession);
        $httpCode = curl_getinfo($cSession, CURLINFO_HTTP_CODE);


        //step4
        curl_close($cSession);

        // do anything you want with your response
        if($httpCode == 204)
            return true;
        else
            return false;

    }



    private function dVault($tokenizeKey, $params)
    {
        try{
            $response = $this->dVaultValidation($tokenizeKey, $params);

            if($response)
            {
                $url_test = 'https://www.despegar.com/sandbox/vault/pbdyy';

                $params["brand_code"] = "VI";
                $params["number"] = "4111111111111111";
                $params["expiration_month"] = "12";
                $params["expiration_year"] = "2030";
                $params["security_code"] = "123";
                $params["bank"] = "Some bank";
                $params["seconds_to_live"] = "600";
                $params["holder_name"] = "John Teken";
                $tokenizeKey = $tokenizeKey;

                $header = [
                    'Content-Type: application/json',
                    'X-Tokenize-Key: '.$tokenizeKey,
                    'X-Client: 2864680fe4d74241aa613874fa20705f',
                    'X-ApiKey: 2864680fe4d74241aa613874fa20705f'
                ];

                //step1
                $params = json_encode($params);

//        echo 'Post: '. $url_test.'<br/>';
//
//        echo 'Header: <pre>';
//        print_r(json_encode($header));
//        echo '</pre><br/>';
//
//        echo 'Body: <pre>';
//        print_r(json_encode($params));
//        echo '</pre><br/>';



                //echo $postvars;
                //exit();

                $cSession = curl_init();
                curl_setopt($cSession, CURLOPT_URL, $url_test);
                curl_setopt($cSession, CURLOPT_POST, true);
                curl_setopt($cSession, CURLOPT_POSTFIELDS, $params);
                curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cSession, CURLOPT_HTTPHEADER, $header);
                curl_setopt($cSession, CURLOPT_HEADER, false);
                //step3
                $results = curl_exec($cSession);
                //step4
                curl_close($cSession);


//                echo 'Response: <pre>';
//                print_r($results);
//                echo '</pre><br/>';

                // do anything you want with your response
                return json_decode($results);
            }

        }catch(Exception $exception)
        {
            return false;
        }

        return false;
    }

    /**
     * Lists all Company entities.
     *
     * @Route("/booking/hotel/pay", name="viento_sur_app_pay_hotel_booking")
     * @Method("POST")
     * @Template()
     */
    public function payHotelBookingAction(Request $request)
    {

        //echo "<br>";
        echo $number = $request->get('hotelInputDefinition.paymentDefinition.cardDefinition.number.value');
        echo $expiration = $request->get('hotelInputDefinition.paymentDefinition.cardDefinition.expiration.value');
        echo $secureCode = $request->get('hotelInputDefinition.paymentDefinition.cardDefinition.securityCode.value');
        echo $ownerName = $request->get('hotelInputDefinition.paymentDefinition.cardDefinition.ownerName.value');
        $params = array();

        $params["number"] = $number;
        $params["expiration_month"] = "12";
        $params["expiration_year"] = "2030";
        $params["security_code"] = $secureCode;
        $params["bank"] = "Some bank";
        $params["seconds_to_live"] = "600";
        $params["holder_name"] = $ownerName;
        $response = $this->dVault($request->get('tokenize_key'), $params);


        if(isset($response->secure_token))
        {
            $bookingId = $this->get('session')->get('booking-id');
            $formId = $request->get('form_id');


            //Q1JFRElUX0NBUkQtNQ
            $session = $request->getSession();
            $url = 'https://api.despegar.com'.$bookingId.'/'.$formId.'?example=true';
            //$url = $session->get('url_detail_form') . "/Q1JFRElUX0NBUkQtNQ==";
//            $url = " https://api.despegar.com/v3/hotels/bookings/45ad82b0-7c7e-11e4-ac22-fa163e7a50a2/forms/Q1JFRElUX0NBUkR8MQ==";
            //$url = "https://api.despegar.com/v3/hotels/bookings/ticketexample/forms/Q1JFRElUX0NBUkR8Mg%3D%3D?example=true";
            //echo "responsables";
            //echo "<br>";
            if ($request->get('passengers') == 1) {
                $passengerDefinitionsFirstName1 = $request->get('hotelInputDefinition.passengerDefinitions[0].firstName.value');
                $passengerDefinitionsLastName1 = $request->get('hotelInputDefinition.passengerDefinitions[0].lastName.value');
                $passengerDefinitionsDni1 = $request->get('hotelInputDefinition.passengerDefinitions[0].roomId.value');
            } elseif ($request->get('passengers') == 2) {
                echo $passengerDefinitionsFirstName1 = $request->get('hotelInputDefinition.passengerDefinitions[0].firstName.value');
                echo $passengerDefinitionsLastName1 = $request->get('hotelInputDefinition.passengerDefinitions[0].lastName.value');
                echo $passengerDefinitionsDni1 = $request->get('hotelInputDefinition.passengerDefinitions[0].roomId.value');
                echo $passengerDefinitionsFirstName2 = $request->get('hotelInputDefinition.passengerDefinitions[1].firstName.value');
                echo $passengerDefinitionsLastName2 = $request->get('hotelInputDefinition.passengerDefinitions[1].lastName.value');
                echo $passengerDefinitionsDni2 = $request->get('hotelInputDefinition.passengerDefinitions[1].roomId.value');
            }

            //creditcard
            //echo "carta";
//            //echo "<br>";
//            echo $number = $request->get('hotelInputDefinition.paymentDefinition.cardDefinition.number.value');
//            echo $expiration = $request->get('hotelInputDefinition.paymentDefinition.cardDefinition.expiration.value');
//            echo $secureCode = $request->get('hotelInputDefinition.paymentDefinition.cardDefinition.securityCode.value');
//            echo $ownerName = $request->get('hotelInputDefinition.paymentDefinition.cardDefinition.ownerName.value');

            echo $email = $request->get('hotelInputDefinition.contactDefinition.email.value');
            echo $emailConfirm = $request->get('hotelInputDefinition.contactDefinition.email.value');
            echo $phoneType = $request->get('hotelInputDefinition.contactDefinition.phoneDefinitions[0].type.value');
            echo $country = $request->get('hotelInputDefinition.contactDefinition.phoneDefinitions[0].countryCode.value');
            echo $countryCode = $request->get('hotelInputDefinition.contactDefinition.phoneDefinitions[0].areaCode.valu');
            echo $phoneNumber = $request->get('otelInputDefinition.contactDefinition.phoneDefinitions[0].number.value');


//            $arrayData = array(
//                "payment_method_choice" => "1",
//                "form" => array(
//                    "passengers" => array(
//                        "first_name" => "Test",
//                        "last_name" => "Booking",
//                        "room_reference" => "1",
//                    )
//                ),
//                "payment" => array(
//                    "credit_card" => array(
//                        "number" => "4242424242424242",
//                        "expiration" => "2020-12",
//                        "security_code" => "123",
//                        "owner_name" => "Test Booking",
//                        "owner_document" => array(
//                            "type" => "LOCAL",
//                            "number" => "12345678"
//                        ),
//                        "card_code" => "VI",
//                        "card_type" => "CREDIT"
//                    ),
//                    "billing_address" => array(
//                        "country" => "AR",
//                        "state" => "Buenos Aires",
//                        "city" => "BUE",
//                        "street" => "Calle Falsa",
//                        "number" => "123",
//                        "floor" => "1",
//                        "department" => "G",
//                        "postal_code" => "1234"
//                    ),
//                    "contact" => array(
//                        "email" => "testhoteles@despegar.com",
//                        "phones" => array(
//                            "type" => "CELULAR",
//                            "number" => "12345678",
//                            "country_code" => "54",
//                            "area_code" => "11"
//                        )
//                    )
//                ),
//                "secure_token_information" => array(
//                    'secure_token' => $response->secure_token
//                )
//            );


//            $arrayData = array("source" => array(
//                "payment_method_choice" => "1"),
//                "form" => array(
//                    "passengers" => array(
//                        "first_name" => "Felipe",
//                        "last_name" => "viÃ±oles",
//                        "room_reference" => "1",
//                    ),
//                    "payment" => array(
//                        "credit_card" => array(
//                            "number" => "254232323",
//                            "expiration" => "01/2018",
//                            "owner_name" => "Test Booking"
//                        )
//                    ),
//                    "contact" => array(
//                        "email" => "agustin.vicenteARROBAgmail.com",
//                        "payment_option_choice" => "CREDIT_VI_null"
//                    ),
//                ),
//                "keys" => array(
//                    //"availability_token" => "31e27bc2-fe88-473f-8bb8-1afb5b8d3a6b"820350c2-e64a-4a45-83c2-dae3b72236d2
//                    "availability_token" => "d911467c-4fd0-432e-ba2c-c949d4f15e99"
//                ),
//
//            );


            $arrayData = '{"payment_method_choice":"1",
            "form":{
                "passengers":[{"first_name":"Test","last_name":"Booking", "document_number": "29742594"}],
                "payment":
                    {"credit_card":
                        {"number":"4242424242424242",
                         "expiration":"2020-12",
                         "security_code":"123",
                         "owner_name":"Test Booking",
                         "owner_document":
                             {"type":"LOCAL",
                              "number":"12345678"
                             },
                          "card_code":"VI","card_type":"CREDIT"
                         },
                         "billing_address":
                            {"country":"AR",
                             "state":"Buenos Aires",
                             "city":"BUE",
                             "street":"Calle Falsa",
                             "number":"123",
                             "floor":"1",
                             "department":"G",
                             "postal_code":"1234"
                            }
                    },
                     "contact":{"email":"testhoteles@despegar.com",
                            "phones":[{"type":"CELULAR","number":"12345678","country_code":"54","area_code":"11"}]
                            }
                   }
        },"secure_token_information":{"secure_token":';
            $arrayData.= $response->secure_token.'}}';


            $response = $this->cUrlExecPatchBookingAction($arrayData, $url);
            var_dump($response);
        }

        return array();
    }

    private function cUrlExecAction($url) {

        //step1
        // api productiva ca8fe17f100646cbbefa4ecddcf51350
        // ca8fe17f100646cbbefa4ecddcf51350
        // api desarrollo 2864680fe4d74241aa613874fa20705f

//                echo 'GET: '. $url.'<br/>';
//
//        echo 'Header: <pre>';
//        print_r(json_encode(array('X-ApiKey:2864680fe4d74241aa613874fa20705f')));
//        echo '</pre><br/>';
//
//        echo 'Request: <pre>';
//        print_r(json_encode($url));
//        echo '</pre><br/>';

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

//        echo 'Response: <pre>';
//        print_r($results);
//        echo '</pre><br/>';
//        die('finish');

        return $results;
    }

    private function cUrlExecAutoCompleteAction($url) {

        //step1
        // api productiva ca8fe17f100646cbbefa4ecddcf51350
        $cSession = curl_init();
        curl_setopt($cSession, CURLOPT_URL, $url);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($cSession, CURLOPT_HTTPHEADER, array('X-ApiKey:ca8fe17f100646cbbefa4ecddcf51350'));
        curl_setopt($cSession, CURLOPT_HTTPHEADER, array('X-ApiKey:2864680fe4d74241aa613874fa20705f'));
        curl_setopt($cSession, CURLOPT_HEADER, false);
        //step3
        $results = curl_exec($cSession);
        //step4
        curl_close($cSession);

        return $results;
    }

    private function cUrlExecPostBookingAction($postvars, $url) {

//        echo 'Post: '. $url.'<br/>';
//        echo 'Header: <pre>';
//        print_r(json_encode(array('X-ApiKey:2864680fe4d74241aa613874fa20705f')));
//        echo '</pre><br/>';
//
//        echo 'BODY: <pre>';
//        print_r(json_encode($postvars));
//        echo '</pre><br/>';

        //step1
        $postvars = json_encode($postvars);
        $cSession = curl_init();
        curl_setopt($cSession, CURLOPT_URL, $url);
        curl_setopt($cSession, CURLOPT_POST, true);
        curl_setopt($cSession, CURLOPT_POSTFIELDS, $postvars);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_HTTPHEADER, array('X-ApiKey:2864680fe4d74241aa613874fa20705f',
                'Content-Type: application/json'
            )
        );
        curl_setopt($cSession, CURLOPT_HEADER, false);
        //step3
        $results = curl_exec($cSession);
        //step4
        curl_close($cSession);

//        echo 'Response: <pre>';
//        print_r($results);
//        echo '</pre><br/>';
//        die('finish');

        return $results;
    }


    private function cUrlExecPatchBookingAction($params, $url) {


        $header = [
            'Content-Type: application/json',
            'X-Client: 2864680fe4d74241aa613874fa20705f',
            'X-ApiKey: 2864680fe4d74241aa613874fa20705f',
        ];

        //$params = json_encode($params);

//        echo $url.'<br/>';
//
//        echo $params.'<br/>';

        echo 'PATCH: '. $url.'<br/>';
        echo 'Header: <pre>';
        print_r(json_encode($header));
        echo '</pre><br/>';

        echo 'BODY: <pre>';
        print_r($params);
        echo '</pre><br/>';

        $cSession = curl_init();
        curl_setopt($cSession, CURLOPT_URL, $url);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($cSession, CURLOPT_POSTFIELDS, $params);
        curl_setopt($cSession, CURLOPT_HTTPHEADER, $header);
        curl_setopt($cSession, CURLOPT_HEADER, false);
        //step3
        $results = curl_exec($cSession);
        //step4
        curl_close($cSession);

        echo 'Response: <pre>';
        print_r($results);
        echo '</pre><br/>';

        die('listo');
        // do anything you want with your response
        return json_decode($results);
    }

}
