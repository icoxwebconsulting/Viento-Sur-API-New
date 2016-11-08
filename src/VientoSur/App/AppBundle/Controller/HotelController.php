<?php

namespace VientoSur\App\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use VientoSur\App\AppBundle\Services\Distribution;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Hotel controller.
 *
 * @Route("/hotel")
 */
class HotelController extends Controller {

    public $session;

    /**
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
     *
     * @Route("/send/hotels/availabilities/{page}", name="viento_sur_send_hotels")
     * @Method("GET")
     */
    public function sendHotelsAvailabilitiesAction($page, Request $request) {

        if ($this->getParameter('is_test')) {
            $destinationText = 'Buenos Aires, Ciudad de Buenos Aires, Argentina';
            $destination = 982;
        } else {
            $destinationText = $request->get('autocomplete');
            $destination = $request->get('cityInput');
        }

        list($day,$month,$year)=explode("/",$request->get('start'));
        $fromCalendarHotel = $year.'-'.$month.'-'.$day;

        list($day,$month,$year)=explode("/",$request->get('end'));
        $toCalendarHotel = $year.'-'.$month.'-'.$day;
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

        $distribucionClass = new Distribution();
        $distribucion = $distribucionClass->createDistribution($habitacionesCant, $adultsSelector1, $adultsSelector2, $adultsSelector3, $adultsSelector4, $childrenSelectOne, $childrenSelectTwo, $childrenSelectTree, $childrenSelectFour, $OneChildrenOne, $OneChildrenTwo, $OneChildrenTree, $OneChildrenFour, $OneChildrenFive, $OneChildrenSix, $TwoChildrenOne, $TwoChildrenTwo, $TwoChildrenTree, $TwoChildrenFour, $TwoChildrenFive, $TwoChildrenSix, $TreeChildrenOne, $TreeChildrenTwo, $TreeChildrenTree, $TreeChildrenFour, $TreeChildrenFive, $TreeChildrenSix, $FourChildrenOne, $FourChildrenTwo, $FourChildrenTree, $FourChildrenFour, $FourChildrenFive, $FourChildrenSix);

        //TODO: falta por desarrollar el código de ordenamiento, está comentado en listHotelsAvailabilities.html.twig
        $offset = ($page - 1) * 10;
        $urlParams = array(
            "country_code" => "AR",
            "checkin_date" => $fromCalendarHotel,
            "checkout_date" => $toCalendarHotel,
            "destination" => $destination,
            "distribution" => $distribucion,
            "language" => "es",
            "radius" => "200",
            "accepts" => "partial",
            "currency" => "ARS",
            "sorting" => "best_selling_descending",
            "classify_roompacks_by" => "none",
            "roompack_choices" => "recommended",
            "offset" => $offset,
            "limit" => "10"
        );
        //TODO: hay un tema con el api de despegar, cuando se envía el offset en 0 o no se envía a veces no retorna el total de elementos
        if ($offset == 0) {
            $urlParams['offset'] = 1;
        }

        $results = $this->get('despegar')->getHotelsAvailabilities($urlParams);

        $restUrl = '?' . http_build_query(array(
                "site" => "AR",
                "checkin_date" => $fromCalendarHotel,
                "checkout_date" => $toCalendarHotel,
                "distribution" => $distribucion
            ));

        $session = $request->getSession();
        $session->set('checkin_date', $request->get('start'));
        $session->set('checkout_date', $request->get('end'));
        $session->set('destination', [
            'text' => $destinationText,
            'id' => $destination
        ]);

        $total = ceil($results['paging']['total'] / 10);

        if ($request->isXmlHttpRequest()) {
            return $this->render('VientoSurAppAppBundle:Hotel:listDetailHotels.html.twig', array(
                'items' => $results,
                'restUrl' => $restUrl,
                'offset' => $offset,
                'limit' => 10,
                'total' => $total,
                'page' => $page
            ));
        } else {
            return $this->render('VientoSurAppAppBundle:Hotel:listHotelsAvailabilities.html.twig', array(
                'items' => $results,
                'restUrl' => $restUrl,
                'offset' => $offset,
                'limit' => 10,
                'total' => $total,
                'page' => $page
            ));
        }
    }


    /**
     *
     * @Route("/consult", name="viento_sur_app_consult")
     * @Method("POST")
     */
    public function consultAction(Request $request) {
        $message = \Swift_Message::newInstance(null)
            ->setSubject("Consulta web Viento Sur")
            ->setFrom("not-reply@vientosur.com")
            ->setTo("davidjdr@gmail.com")
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
        return new JsonResponse(array("status" => 'success'));
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
            'currency' => 'ARS'
        );

        $despegar = $this->get('despegar');
        $dispoHotel = $despegar->getHotelsAvailabilitiesDetail($idHotel, $restUrl, $urlParams);
        $session->set('hotelAvailabilities', json_encode($dispoHotel));

        $urlParams = array(
            'ids' => $idHotel,
            'language' => 'es',
            'options' => 'information,amenities,pictures,room_types(pictures,information,amenities)',
            'resolve' => 'merge_info',
            'catalog_info' => 'true'
        );
        $hotelDetails = $despegar->getHotelsDetails($urlParams);

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
                "shown_currency" => "ARS",
                "threat_metrix_id" => "25",
                "agent_code" => 'AG32502',
                "client_ip" => $request->getClientIp(),
                "user_agent" => $request->headers->get('User-Agent')
            ),
            "keys" => array(
                "availability_token" => $request->get('availability_token')
            )
        );

        $formUrl = $this->get('despegar')->postHotelsBookings($postParams);

        return $this->redirect($this->generateUrl('viento_sur_app_boking_hotel_pay', array(
            'formUrl' => $formUrl["next_step_url"],
            'booking_id' => $formUrl["id"],
            "roompack_choice" => $request->get('roompack_choice')
        )));
    }

    /**
     *
     * @Route("/booking/pay/", name="viento_sur_app_boking_hotel_pay")
     * @Template()
     */
    public function bookingHotelPayAction(Request $request) {

        $session = $request->getSession();
        $priceDetail = $session->get('price_detail');
        $roompackChoice = $request->get('roompack_choice');
        $hotelAvailabilities = json_decode($session->get('hotelAvailabilities'));

        $roompack = null;
        foreach ($hotelAvailabilities->roompacks as $item) {
            if ($item->choice = $roompackChoice) {
                $roompack = $item;
                break;
            }
        }

        $formUrl     = $request->get('formUrl');
        $bookingId = $request->query->get('formUrl');
        $booking_id = $request->get('booking_id');

        $session->set('booking-id', $bookingId);

        $despegar = $this->get('despegar');
        $sessionForm = $request->getSession();
        $sessionForm->set('url_detail_form', $despegar->getHotelsBookingsNextStepUrl($bookingId));

        if($request->getMethod() == 'GET') {
            $formBooking = $despegar->hotelsBookingsNextStep($bookingId);
            $session->set('formBooking', json_encode($formBooking));
        } else {
            $formBooking = json_decode($session->get('formBooking'),true);
            //$session->remove('formBooking');
        }

        /* start form */
        $formNewPay = $this->createFormBuilder($formBooking);
        $formHelper = $this->get('form_helper');
        $formNewPay = $formHelper->initForm($formBooking, $formNewPay, $roompackChoice, $roompack->payment_methods);
        $formNewPaySend = $formNewPay->getForm();
        
        if($request->getMethod() == 'POST'){
            
            $formNewPaySend->handleRequest($request);
            
            if ($formNewPaySend->isValid()) {
               
               $formNewPaySend = $formNewPaySend->getData();
                $session->set('email', $formNewPaySend['email']);
                
               //procesar formulario recibido
                $dvaultQuery = [
                    'brand_code'       =>$formNewPaySend['card_code'],
                    'number'           =>$formNewPaySend['number'],
                    'expiration_month' =>$formNewPaySend['expiration']->format('m'),
                    'expiration_year'  =>$formNewPaySend['expiration']->format('Y'),
                    'security_code'    =>$formNewPaySend['security_code'],
                    'bank'             =>$formNewPaySend['bank_code'],
                    'seconds_to_live'  =>'600',
                    'holder_name'      =>$formNewPaySend['owner_name'],
                ];

                $response = $despegar->dVault($formNewPaySend['tokenize_key'], $dvaultQuery);
                $status = 'ok';
                $detail = [];
                if ($response && isset($response->secure_token)) {
                    //obtengo los valores ya seteados según la selección
                    $fillData = $formHelper->fillFormData($formBooking, $formNewPaySend);
                    $seletedPack = $formHelper->getSelectedPack();
                    $form_id_booking = $seletedPack['id'];

                    $patchParams = [];
                    $patchParams['payment_method_choice'] = $formNewPaySend['paymentMethod'];
                    $patchParams['secure_token_information'] = array('secure_token' => $response->secure_token);
                    $patchParams['form'] = $fillData;

                    $response = $despegar->patchHotelsBookings($bookingId, $form_id_booking, $patchParams);
                    if (isset($response['code'])) {
                        $status = 'patch';
                    }
                    $detail = $response;
                } else {
                    //TODO: Error en dVault response token
//                     echo 'Response: <pre>';
//                     print_r($response);
//                     echo '</pre><br/>';
                    $status = 'dvault';
                }

                return $this->redirect($this->generateUrl('viento_sur_app_booking_hotel_summary', array(
                    'status' => $status,
                    'hotel_id' => $hotelAvailabilities->hotel->id,
                    'detail' => $detail
                )));
            }
        }

        $selectedPack = $formHelper->getSelectedPack();
        $formChoice = $formBooking['dictionary']['form_choices'][$selectedPack['form_choice']];

        return array(
            'formBooking'      => $formBooking,
            'formChoice'       => $formChoice,
            'price_detail'     => $priceDetail,
            'formUrl'          => $formUrl,
            'roompack_choice'  => $roompackChoice,
            'booking_id'       => $booking_id,
            'formNewPay'       => $formNewPaySend->createView(),
            'paymentMethods'   => $roompack->payment_methods,
            'rooms'            => $roompack->rooms
        );
    }

    /**
     *
     * @Route("/booking/summary/", name="viento_sur_app_booking_hotel_summary")
     * @Template()
     */
    public function payHotelBookingAction(Request $request)
    {
        $status = $request->get('status');
        $detail = $request->get('detail');
        $hotelId = $request->get('hotel_id');

        $urlParams = array(
            'ids' => $hotelId,
            'language' => 'es',
            'options' => 'information,amenities,pictures,room_types(pictures,information,amenities)',
            'resolve' => 'merge_info',
            'catalog_info' => 'true'
        );
        $hotelDetails = $this->get('despegar')->getHotelsDetails($urlParams);

        try{
            if ($status == 'ok' && $request->getSession()->get('email')) {
                $this->get('email.service')->sendBookingEmail($request->getSession()->get('email'), array(
                    'hotelDetails' => $hotelDetails[0],
                    'detail' => $detail,
                    'hotelId' => $hotelId
                ));
            }
        } catch(\Exception $e){
            $this->get('logger')->error('Booking email error');
        }

        return array(
            'status' => $status,
            'detail' => $detail,
            'hotelId' => $hotelId,
            'hotelDetails' => $hotelDetails[0]
        );
    }

    /**
     * @Route("/booking/pdf/", name="viento_sur_app_booking_hotel_pdf")
     */
    public function showPdfBookingAction(Request $request)
    {
        $hotelId = $request->get('hotel_id');
        $detail = $request->get('detail');
        $urlParams = array(
            'ids' => $hotelId,
            'language' => 'es',
            'options' => 'information,amenities,pictures,room_types(pictures,information,amenities)',
            'resolve' => 'merge_info',
            'catalog_info' => 'true'
        );
        $hotelDetails = $this->get('despegar')->getHotelsDetails($urlParams);

        $html = $this->renderView('VientoSurAppAppBundle:Pdf:booking.html.twig',array(
            'hotelDetails' => $hotelDetails[0],
            'detail' => $detail,
            'hotelId' => $hotelId
        ));
        $pdfGenerator = $this->get('spraed.pdf.generator');

        return new Response($pdfGenerator->generatePDF($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="out.pdf"'
            )
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
