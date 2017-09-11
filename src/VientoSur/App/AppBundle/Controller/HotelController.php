<?php

namespace VientoSur\App\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use VientoSur\App\AppBundle\Entity\Passengers;
use VientoSur\App\AppBundle\Entity\Reservation;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Config\Definition\Exception\Exception;
use VientoSur\App\AppBundle\Services\PaymentMethods;


/**
 * Hotel controller.
 *
 * @Route("/{_locale}/hotel", requirements={"_locale": "es|en|pt"}, defaults={"_locale": "es"})
 */
class HotelController extends Controller
{

    public $session;

    /**
     *
     * @Route("/index/", name="hotel_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
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
     * @Route("/send/hotels/process-search", name="viento_sur_process_search_hotels")
     * @Method("POST")
     */
    public function sendHotelsProcessSearch(Request $request)
    {
        /*if ($this->getParameter('is_test')) {
            $destinationText = 'Buenos Aires, Ciudad de Buenos Aires, Argentina';
//            $destinationText = 'Arbatax Park Resort Borgo Cala Moresca, Tortoli, Italia';
            $destination = 'CITY-982';
        } else {*/
            $destinationText = $request->get('autocomplete');
            $destination = $request->get('cityInput');
        //}

        $start = $request->get('start');
        $end = $request->get('end');
        if ($destination == '' || empty($start) || empty($end) || !strpos($start, '/') || !strpos($end, '/')) {
            return $this->redirect($this->generateUrl('homepage'));
        }

        list($day, $month, $year) = explode("/", $request->get('start'));
        $fromCalendarHotel = $year . '-' . $month . '-' . $day;

        list($day, $month, $year) = explode("/", $request->get('end'));
        $toCalendarHotel = $year . '-' . $month . '-' . $day;

        $habitacionesCant = $request->get('habitacionesCant');
        $distribution = '';
        $dataTravelers = array();

        if ($habitacionesCant == 1) {
            $distribution = $request->get('adultsSelector');
            $childrens = $request->get('childrenRoomSelector');
            $childAges = '';
            $dataTravelers['room'][1]['adults'] = $request->get('adultsSelector');
            for ($i = 1; $i <= $childrens; $i++) {
                $dataTravelers['room'][1]['children'][$i] = $request->get('childAgeSelector-' . $i);
                $childAges .= '-' . $request->get('childAgeSelector-' . $i);
            }
            $distribution .= $childAges;
        } else {
            for ($h = 1; $h <= $habitacionesCant; $h++) {
                $adults = $request->get('adultsSelector' . $h);
                $childrens = $request->get('childrenRoomSelector' . $h);
                $childAges = '';
                $dataTravelers['room'][$h]['adults'] = $request->get('adultsSelector' . $h);
                for ($i = 1; $i <= $childrens; $i++) {
                    $dataTravelers['room'][$h]['children'][$i] = $request->get('childAgeSelector-' . $h . '-' . $i);
                    $childAges .= '-' . $request->get('childAgeSelector-' . $h . '-' . $i);
                }
                $distribution .= (($h > 1) ? '!' : '') . $adults . $childAges;
            }
        }

        $session = $request->getSession();
        $session->set('data_travelers', $dataTravelers['room']);
        $session->set('checkin_date', $request->get('start'));
        $session->set('checkout_date', $request->get('end'));
        $session->set('distribution', $distribution);
        $session->set('destination', [
            'text' => $destinationText,
            'id' => $destination
        ]);

        $destination = explode('-', $destination);
        $destiny = trim($destination[1]);

        if ($destination[0] == 'CITY') {
            return $this->redirectToRoute('viento_sur_send_hotels', array(
                'destination' => $destiny,
                'checkin_date' => $fromCalendarHotel,
                "checkout_date" => $toCalendarHotel,
                'distribution' => $distribution
            ));
        } else {
            //show/{idHotel}/availabilities/{destination}/{checkin_date}/{checkout_date}/{distribution}/{latitude}/{longitude}
            $hotelDetail = $this->get('despegar')->getHotelsDetails(array(
                'ids' => $destiny,
                'language' => 'es',
                'resolve' => 'merge_info',
                'catalog_info' => 'true'
            ));
            return $this->redirectToRoute('viento_sur_app_app_homepage_show_hotel_id', array(
                'idHotel' => $destiny,
                'checkin_date' => $fromCalendarHotel,
                'checkout_date' => $toCalendarHotel,
                'distribution' => $distribution,
                'latitude' => $hotelDetail[0]['location']['latitude'],
                'longitude' => $hotelDetail[0]['location']['longitude']
            ));
        }
    }

    /**
     *
     * @Route("/send/hotels/availabilities/{destination}/{checkin_date}/{checkout_date}/{distribution}", name="viento_sur_send_hotels")
     * @Method("GET")
     */
    public function sendHotelsAvailabilitiesAction($destination, $checkin_date, $checkout_date, $distribution, Request $request)
    {

//        echo"<pre>".print_r($destination)."</pre>";
//        die();
        $page = $request->query->get('page');
        if (!$page) {
            $page = 1;
            $offset = 0;
        } else {
            $offset = ($page - 1) * 25;
        }

        $locale = $request->getLocale();
        $lang = ($locale && in_array($locale, ['en', 'es', 'pt'])) ? $locale : 'es';

        $session = $request->getSession();
        if (!$session->has('destination')) {
            $cityResponse = $this->get('despegar')->getCityInformation($destination, [
                'product' => 'HOTELS',
                'language' => 'ES,PT,EN'
            ]);
            if (isset($cityResponse['code']) && $cityResponse['code'] == 500) {
                $destinationData = [
                    'text' => '',
                    'id' => $destination
                ];
            } else {
                $destinationData = [
                    'text' => $cityResponse['descriptions'][$lang],
                    'id' => $destination
                ];
            }
            $session->set('destination', $destinationData);
        }

        $sorting = $request->query->get('sorting');
        if (!$sorting) {
            $sorting = 'best_selling_descending';
        }
        $priceRange = $request->query->get('price_range');
        $stars = $request->query->get('stars');
        $paymentType = $request->query->get('payment_type');
        $zones = $request->query->get('zones');
        $amenities = $request->query->get('amenities');
        $hotelType = $request->query->get('hotel_type');
        $hotelChains = $request->query->get('hotel_chains');
        $mealPlans = $request->query->get('meal_plans');
        $tripProfile = $request->query->get('profiles');
        $hotelName = $request->query->get('hotel_name');

        $urlParams = array(
            "country_code" => "AR",
            "checkin_date" => $checkin_date,
            "checkout_date" => $checkout_date,
            "destination" => $destination,
            "distribution" => $distribution,
            "language" => $lang,
            "radius" => "200",
            "currency" => "ARS",
            "sorting" => $sorting,
            "classify_roompacks_by" => "none",
            "roompack_choices" => "recommended",
            "offset" => $offset,
            "limit" => "25"
        );

        if ($priceRange) {
            $urlParams['total_price_range'] = $priceRange;
        }
        if ($stars) {
            $urlParams['stars'] = $stars;
        }
        if ($paymentType) {
            $urlParams['payment_type'] = $paymentType;
        }
        if ($zones) {
            $urlParams['zones'] = $zones;
        }
        if ($amenities) {
            $urlParams['amenities'] = $amenities;
        }
        if ($hotelType) {
            $urlParams['hotel_type'] = $hotelType;
        }
        if ($hotelChains) {
            $urlParams['hotel_chains'] = $hotelChains;
        }
        if ($mealPlans) {
            $urlParams['meal_plans'] = $mealPlans;
        }
        if ($tripProfile) {
            $urlParams['profiles'] = $tripProfile;
        }
        if ($hotelName) {
            $urlParams['hotel_name'] = $hotelName;
        }

        $results = $this->get('despegar')->getHotelsAvailabilities($urlParams);

        $idsHotels = [];
        foreach ($results['items'] as $item) {
            $idsHotels[] = $item['id'];
        }

        $hotelsDetails = $this->get('despegar')->getHotelsDetails(array(
            'ids' => implode(',', $idsHotels),
            'language' => $lang,
            'options' => 'pictures',
            'resolve' => 'merge_info',
            'catalog_info' => 'true'
        ), true);

        $reservation = array(
            'destination' => $destination,
            "checkin_date" => $checkin_date,
            "checkout_date" => $checkout_date,
            "distribution" => $distribution
        );

        $total = ceil($results['paging']['total'] / 25);

        $translator = $this->get('translator');

        $sortMapping = [
            "subtotal_price_ascending" => $translator->trans('hotels.order.subtotal_price_ascending'),
            "subtotal_price_descending" => $translator->trans('hotels.order.subtotal_price_descending'),
            "rate_descending" => $translator->trans('hotels.order.rate_descending'),
            "location_descending" => $translator->trans('hotels.order.location_descending'),
            "quality_price_descending" => $translator->trans('hotels.order.quality_price_descending'),
            "logged_user_descending" => $translator->trans('hotels.order.logged_user_descending'),
            "cross_selling_descending" => $translator->trans('hotels.order.cross_selling_descending'),
            "best_selling_descending" => $translator->trans('hotels.order.best_selling_descending'),
            "stars_descending" => $translator->trans('hotels.order.stars_descending'),
            "stars_ascending" => $translator->trans('hotels.order.stars_ascending')
        ];

        $sortArray = [
            'price' => '',
            'star' => '',
            'best' => '',
            'other' => '',
            'selected' => [
                'category' => '',
                'name' => ''
            ]
        ];

        foreach ($results['sorting']['values'] as $sort) {
            if (array_key_exists($sort['value'], $sortMapping)) {
                $element = ' class="nav-drop-menu-a" data-value="' . $sort['value'] . '">' . $sortMapping[$sort['value']] . '</a></li>';
                $category = '';
                if (in_array($sort['value'], ['best_selling_descending', 'subtotal_price_ascending', 'subtotal_price_descending'])) {
                    $category = 'price';
                    $sortArray['price'] .= '<li><a href="javascript:void(0);" data-category="' . $category . '" ' . $element;
                } else if (in_array($sort['value'], ['stars_descending', 'stars_ascending'])) {
                    $category = 'star';
                    $sortArray['star'] .= '<li><a href="javascript:void(0);" data-category="' . $category . '" ' . $element;
                } else if (in_array($sort['value'], ['rate_descending', 'location_descending', 'quality_price_descending'])) {
                    $category = 'best';
                    $sortArray['best'] .= '<li><a href="javascript:void(0);" data-category="' . $category . '" ' . $element;
                } else if (in_array($sort['value'], ['logged_user_descending', 'cross_selling_descending'])) {
                    $category = 'other';
                    $sortArray['other'] .= '<li><a href="javascript:void(0);" data-category="' . $category . '" ' . $element;
                }
                if ($sort['selected']) {
                    $sortArray['selected']['category'] = $category;
                    $sortArray['selected']['name'] = $sortMapping[$sort['value']];
                }
            }
        }

        $travellers = $this->get('booking_helper')->getSearchText($checkin_date, $checkout_date, $distribution, $lang);
        $viewParams = array(
            'items' => $results,
            'hotelsDetails' => $hotelsDetails,
            'reservation' => $reservation,
            'offset' => $offset,
            'limit' => 25,
            'total' => $total,
            'page' => $page,
            'sorting' => $sorting,
            'sortMapping' => $sortMapping,
            'travellers' => $travellers,
            'tripProfile' => $this->get('booking_helper')->getTripProfiles(),
            'destination' => $destination,
            'sortArray' => $sortArray
        );

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(
                array(
                    'html' => $this->renderView('VientoSurAppAppBundle:Hotel:listDetailHotels.html.twig',
                        $viewParams
                    ),
                    'paging' => $results['paging'],
                    'total' => $total,
                    'page' => $page
                )
            );
        } else {
            return $this->render('VientoSurAppAppBundle:Hotel:listHotelsAvailabilities.html.twig', $viewParams);
        }
    }


    /**
     *
     * @Route("/consult", name="viento_sur_app_consult")
     * @Method("POST")
     */
    public function consultAction(Request $request)
    {
        $email = $request->request->get('email');
        $html = $this->renderView(
            'VientoSurAppAppBundle:Email:contact.html.twig',
            array(
                'txtContactName' => $request->request->get('fullname'),
                'txtEmail' => $request->request->get('email'),
                'txtComments' => $request->request->get('message')
            )
        );

        $this->get('email.service')->sendCommentsEmail($html, $email);

        $request->getSession()->getFlashBag()->add('success', 'El mensaje se ha enviado exitosamente.');
        return new JsonResponse(array("status" => 'success'));
    }

    /**
     * @Route("/show/{idHotel}/availabilities/{checkin_date}/{checkout_date}/{distribution}", name="viento_sur_app_app_homepage_show_hotel_id_short")
     * @Method("GET")
     */
    public function showHotelIdAvailabilitiesShortAction(Request $request, $idHotel, $checkin_date, $checkout_date, $distribution)
    {
        return $this->redirectToRoute('viento_sur_app_app_homepage_show_hotel_id', array(
            'idHotel' => $idHotel,
            'checkin_date' => $checkin_date,
            'checkout_date' => $checkout_date,
            'distribution' => $distribution,
            'latitude' => '-0',
            'longitude' => '-0'
        ));
    }

    /**
     * @Route("/show/{idHotel}/availabilities/{checkin_date}/{checkout_date}/{distribution}/{latitude}/{longitude}", name="viento_sur_app_app_homepage_show_hotel_id", defaults={"latitude": -0.0, "longitude": -0.0})
     * @Method("GET")
     */
    public function showHotelIdAvailabilitiesAction(Request $request, $idHotel, $checkin_date, $checkout_date, $distribution, $latitude, $longitude)
    {
        $session = $request->getSession();
        $locale = $request->getLocale();
        $lang = ($locale && in_array($locale, ['en', 'es', 'pt'])) ? $locale : 'es';
        $urlParams = array(
            'language' => $lang,
            'country_code' => 'AR',
            'currency' => 'ARS',
            //'destination' => $destination,
            'checkin_date' => $checkin_date,
            'checkout_date' => $checkout_date,
            'distribution' => $distribution,
            'classify_roompacks_by' => 'rate_plan'
        );

        $despegar = $this->get('despegar');
        $dispoHotel = $despegar->getHotelsAvailabilitiesDetail($idHotel, $urlParams);
        if(isset($dispoHotel['code'])) {
            return $this->render('VientoSurAppAppBundle:Hotel:errorHotel.html.twig', array(
            )
        );
        }
        $session->set('hotelAvailabilities', json_encode($dispoHotel));

        $hotelDetails = $despegar->getHotelsDetails(array(
            'ids' => $idHotel,
            'language' => $lang,
            'options' => 'information,amenities,pictures,room_types(pictures,information,amenities)',
            'resolve' => 'merge_info',
            'catalog_info' => 'true'
        ));

        $roomDetail = [];
        $roomTypes = [];
        if (isset($hotelDetails[0]['room_types'])) {
            foreach ($dispoHotel['roompacks'] as $roompack) {
                foreach ($roompack['rooms'] as $room) {
                    if (isset($room['room_type_id'])) {
                        $roomDetail[$room['room_type_id']][$roompack['roompack_classification']][] = $roompack;
                    }
                }
            }
            foreach ($hotelDetails[0]['room_types'] as $room) {
                $roomTypes[$room['id']] = $room;
            }
        }

        $session->set('price_detail', $dispoHotel['roompacks'][0]['price_detail']);
        $travellers = $this->get('booking_helper')->getSearchText($checkin_date, $checkout_date, $distribution, $lang);


        return $this->render('VientoSurAppAppBundle:Hotel:showHotelIdAvailabilities.html.twig', array(
                'dispoHotel' => $dispoHotel,
                'hotelDetails' => $hotelDetails[0],
                'latitude' => $latitude,
                'longitude' => $longitude,
                'idHotel' => $idHotel,
                'reservation' => $urlParams,
                'roomDetail' => $roomDetail,
                'roomTypes' => $roomTypes,
                'roomTypesEncoded' => json_encode($roomTypes),
                'travellers' => $travellers
            )
        );
    }

    /**
     * @Route("/booking/hotel/send/booking", name="viento_sur_app_app_homepage_send_hotel_booking")
     * @Method("POST")
     */
    public function sendHotelBookingAction(Request $request)
    {
        $session = $request->getSession();
        $locale = $request->getLocale();
        $lang = ($locale && in_array($locale, ['en', 'es', 'pt'])) ? $locale : 'es';

        $params = $request->query->all();

        $price_detail = json_decode($request->get('price_detail'));
        $room_cancellation = $request->get('room_cancellation');

        $session->remove('price_detail');
        $session->set('price_detail', $price_detail);
        $session->remove('room_cancellation');
        $session->set('room_cancellation', $room_cancellation);

        $postParams = array(
            "source" => array(
                "country_code" => "AR"
            ),
            "reservation_context" => array(
                "context_language" => $lang,
                "shown_currency" => "ARS",
                "threat_metrix_id" => "25",
                "agent_code" => $this->getParameter('agent_code'),
                "client_ip" => $request->getClientIp(),
                "user_agent" => $request->headers->get('User-Agent')
            ),
            "keys" => array(
                "availability_token" => $request->get('availability_token')
            )
        );

        $formUrl = $this->get('despegar')->postHotelsBookings($postParams);

        $session = $request->getSession();
        $session->set('hotel_brief', [
            'name' => $request->get('hotel_name'),
            'img' => $request->get('hotel_img'),
            'stars' => $request->get('hotel_stars'),
            'address' => $request->get('hotel_address'),
            'room_cancellation' => $request->get('room_cancellation')
        ]);

        return $this->redirect($this->generateUrl('viento_sur_app_boking_hotel_pay', array(
            'formUrl' => $formUrl["next_step_url"],
            'booking_id' => $formUrl["id"],
            "roompack_choice" => $request->get('roompack_choice'),
            'checkin_date' => $params['checkin_date'],
            'checkout_date' => $params['checkout_date'],
            'distribution' => $params['distribution']
        )));
    }

    /**
     *
     * @Route("/booking/pay/", name="viento_sur_app_boking_hotel_pay")
     * @Template()
     */
    public function bookingHotelPayAction(Request $request)
    {
        $this->deleteFileAction();
        $session = $request->getSession();
        $priceDetail = $session->get('price_detail');
        $roompackChoice = $request->get('roompack_choice');
        $checkin = $request->get('checkin_date');
        $checkout = $request->get('checkout_date');
        $distribution = $request->get('distribution');
        $reservationTime = $diff = date_diff(new \DateTime($checkin), new \Datetime($checkout));
        $hotelAvailabilities = json_decode($session->get('hotelAvailabilities'));
        $cards = $this->get('app.card')->getCards();
        $bankList = $this->get('app.bank')->getBanks();

        $roompack = null;
        foreach ($hotelAvailabilities->roompacks as $item) {
            if ($item->choice = $roompackChoice) {
                $roompack = $item;
                break;
            }
        }

        $formUrl = $request->get('formUrl');
        $bookingId = $request->query->get('formUrl');
        $booking_id = $request->get('booking_id');

        $session->set('booking-id', $bookingId);

        $despegar = $this->get('despegar');
        $sessionForm = $request->getSession();
        $sessionForm->set('url_detail_form', $despegar->getHotelsBookingsNextStepUrl($bookingId));
        $locale = $request->getLocale();
        $lang = ($locale && in_array($locale, ['en', 'es', 'pt'])) ? $locale : 'es';
        if ($request->getMethod() == 'GET') {
            $formBooking = $despegar->hotelsBookingsNextStep($bookingId);
            $session->set('formBooking', json_encode($formBooking));
        } else {
            $formBooking = json_decode($session->get('formBooking'), true);
            //$session->remove('formBooking');
        }

        /* start form */
        $formNewPay = $this->createFormBuilder($formBooking, ['allow_extra_fields' => true]);
        $formHelper = $this->get('form_helper');
        $formNewPay = $formHelper->initForm($formBooking, $formNewPay, $roompackChoice, $roompack->payment_methods);
        $formNewPaySend = $formNewPay->getForm();

        $selectedPack = $formHelper->getSelectedPack();
        $formChoice = $formBooking['dictionary']['form_choices'][$selectedPack['form_choice']];

        $paymentMethods = $roompack->payment_methods;
        $travellers = $this->get('booking_helper')->getSearchText($checkin, $checkout, $distribution, $lang);
        $hotelService = $this->get('hotel_service');

        if ($request->getMethod() == 'POST') {

            $formNewPaySend->handleRequest($request);

            if ($formNewPaySend->isValid()) {

                $formData = $formNewPaySend->getData();
                $session->set('email', $formData['email']);

                $session->remove('booking_all_data');
                $last_digits = explode(' ', $formData['number']);
                $data = array();

                for($i = 0; $i < 3; $i++){
                    if(isset($formData['first_name'.$i])){
                        $data[$i] = array(
                            'full_name' => $formData['first_name'.$i].' '.$formData['last_name'.$i],
                            'first_name' => $formData['first_name'.$i],
                            'last_name' => $formData['last_name'.$i],
                            'document_number' => $formData['document_number'.$i]
                        );
                    }
                }
                $session->set('booking_all_data',[
                    'payment' => [
                        'last_digits' => $last_digits[3],
                        'card_code' => $formData['card_code'],
                        'selected' => $request->get('selected-card')
                    ],
                    'travelers' => $data,
                    'contact' => $formData['country_code0'].' '.$formData['area_code0'].' '.$formData['number0']
                ]);
//                echo "<pre>".print_r($request->get('selected-card'), true)."</pre>";die();
                try {
                    $booking = $hotelService->bookingHotel(
                        $formBooking,
                        $formData,
                        $bookingId,
                        $hotelAvailabilities->hotel->id,
                        $priceDetail,
                        $request->getSession()->get('checkin_date'),
                        $request->getSession()->get('checkout_date'),
                        $lang,
                        $request->getSession()->get('email')
                    );
                } catch (\Exception $e) {
                    if ($e->getMessage() == 'CREDIT_CARD') {
                        $cardsGroup = $hotelService->getCardsGroup($paymentMethods);
                        $this->get('session')->getFlashBag()->add(
                            'card_msg',
                            ''
                        );
                        return array(
                            'formBooking' => $formBooking,
                            'formChoice' => $formChoice,
                            'price_detail' => $priceDetail,
                            'formUrl' => $formUrl,
                            'roompack_choice' => $roompackChoice,
                            'booking_id' => $booking_id,
                            'formNewPay' => $formNewPaySend->createView(),
                            'paymentMethods' => $paymentMethods,
                            'rooms' => $roompack->rooms,
                            'cardsGroup' => $cardsGroup,
                            'reservationDays' => $reservationTime->days,
                            'roomNumbers' => count(explode("!", $distribution)),
                            'errors' => $formNewPaySend->getErrors(),
                            'travellers' => $travellers,
                            'hotelBrief' => $session->get('hotel_brief')
                        );
                    } else {
//                        cuando la tarjeta falla el error aparece aqui
                        throw new \Exception($e);
                    }
                }
                if (isset($booking['code']) and $booking['code'] == 500){
                    foreach ($booking['causes'] as $cause){
                        if(strpos($cause, 'INVALID_LENGTH') !== false){
                            $this->addFlash(
                                'danger',
                                $this->get('translator')->trans('index.invalid_document')
                            );
                        }elseif(strpos($cause, 'Invalid credit card for selected roompack ') !== false){
                            $this->addFlash(
                                'danger',
                                $this->get('translator')->trans('index.invalid_creditcard')
                            );
                        }else{
                            $this->addFlash(
                                'danger',
                                $this->get('translator')->trans('index.fail_purchase')
                            );
                        }
                    }
                    //procesado de métodos de pago agrupados por Banco
                    $cardsGroup = $hotelService->getCardsGroup($paymentMethods);

                    return array(
                        'formBooking' => $formBooking,
                        'formChoice' => $formChoice,
                        'price_detail' => $priceDetail,
                        'formUrl' => $formUrl,
                        'roompack_choice' => $roompackChoice,
                        'booking_id' => $booking_id,
                        'formNewPay' => $formNewPaySend->createView(),
                        'paymentMethods' => $paymentMethods,
                        'rooms' => $roompack->rooms,
                        'cardsGroup' => $cardsGroup,
                        'reservationDays' => $reservationTime->days,
                        'roomNumbers' => count(explode("!", $distribution)),
                        'errors' => $formNewPaySend->getErrors(),
                        'travellers' => $travellers,
                        'hotelBrief' => $session->get('hotel_brief'),
                        'cardList' => $cards,
                        'bankList' => $bankList
                    );
                }else{
                    $session->remove('hotel_entrance_code');
                    $pnr = NULL;
                    if(isset($booking['booking']['pnr'])) {
                        $pnr = $booking['booking']['pnr'];
                    }
                    $session->set('hotel_entrance_code', $pnr);

                    $hotelDetails = $this->container->get('despegar')->getHotelsDetails(array(
                        'ids' =>  $hotelAvailabilities->hotel->id,
                        'language' => $lang,
                        'options' => 'information,amenities,pictures,room_types(pictures,information,amenities)',
                        'resolve' => 'merge_info',
                        'catalog_info' => 'true'
                    ));
                    $hotelDetails = (is_array($hotelDetails)) ? $hotelDetails[0] : null;

                    $reservationDetails = $this->container->get('despegar')->getReservationDetails(
                        $booking['booking']['reservation_id'],
                        array(
                            'email' => 'info@vientosur.net',
                            'language' => $lang,
                            'site' => 'AR'
                        ), $this->getParameter('is_test')
                    );

                    $this->savePdfToAttachAction(
                        $booking['booking'],
                        $hotelAvailabilities->hotel->id,
                        $session->get('email'),
                        $booking['reservation']->getId());

                    $session->remove('reservationInternalId');
                    $session->remove('despegarReservationId');
                    $session->set('reservationInternalId', $booking['reservation']->getId());
                    $session->set('despegarReservationId', $booking['reservation']->getReservationId());

                    $this->container->get('hotel_service')->sendBookingEmail(
                        $booking,
                        $booking['reservation']->getId(),
                        $hotelAvailabilities->hotel->id,
                        $lang,
                        $session->get('email'),
                        $hotelDetails, $reservationDetails);

                    return $this->render('VientoSurAppAppBundle:Hotel:payHotelBooking.html.twig', array(
                        'hotelDetails' => $booking['hotelDetails'],
                        'reservationDetails' => $booking['reservationDetails'],
                        'reservationId' => base64_encode($booking['reservationDetails']['id']),
                        'detail' => $booking['booking'],
                        'hotelId' => $hotelAvailabilities->hotel->id,
                        'internal' => $booking['reservation'],
                        'status' => 'ok',
                        'pdf' => false
                    ));
                }
            }
        }

        //procesado de métodos de pago agrupados por Banco
        $cardsGroup = $hotelService->getCardsGroup($paymentMethods);

        return array(
            'formBooking' => $formBooking,
            'formChoice' => $formChoice,
            'price_detail' => $priceDetail,
            'formUrl' => $formUrl,
            'roompack_choice' => $roompackChoice,
            'booking_id' => $booking_id,
            'formNewPay' => $formNewPaySend->createView(),
            'paymentMethods' => $paymentMethods,
            'rooms' => $roompack->rooms,
            'cardsGroup' => $cardsGroup,
            'reservationDays' => $reservationTime->days,
            'roomNumbers' => count(explode("!", $distribution)),
            'errors' => $formNewPaySend->getErrors(),
            'travellers' => $travellers,
            'hotelBrief' => $session->get('hotel_brief'),
            'cardList' => $cards,
            'bankList' => $bankList
        );
    }
    /**
     * @Route("/booking/pdf/", name="viento_sur_app_booking_hotel_pdf")
     */
    public function showPdfBookingAction(Request $request)
    {
        $detail = $request->get('detail');
        $hotelId = $request->get('hotel_id');
        $email = $request->get('email');
        $reservationId = $request->get('id');

        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository('VientoSurAppAppBundle:Reservation')->findOneById($reservationId);

        $hotelDetails = $this->get('despegar')->getHotelsDetails(array(
            'ids' => $hotelId,
            'language' => 'es',
            'options' => 'information,amenities,pictures,room_types(pictures,information,amenities)',
            'resolve' => 'merge_info',
            'catalog_info' => 'true'
        ));

        $reservationDetails = $this->get('despegar')->getReservationDetails($detail['reservation_id'], array(
            'email' => 'info@vientosur.net',
            'language' => 'es',
            'site' => 'AR'
        ), $this->getParameter('is_test'));

        $logoUrl = 'https://www.vientosur.net/bundles/vientosurappapp/images/vientosur-logo-color.png';

        $html = $this->renderView('VientoSurAppAppBundle:Pdf:booking.html.twig', array(
            'hotelDetails' => $hotelDetails[0],
            'reservationDetails' => $reservationDetails,
            'detail' => $detail,
            'hotelId' => $hotelId,
            'internal' => $reservation,
            'logoUrl' => $logoUrl,
            'pdf' => true
        ));

        return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="reservacion.pdf"'
            )
        );
    }

    /**
     *
     * @Route("/booking/summary/", name="viento_sur_app_booking_hotel_summary")
     */
    public function payHotelBookingAction(Request $request)
    {
        $status = $request->get('status');
        $detail = $request->get('detail');
        $hotelId = $request->get('hotel_id');

        $hotelDetails = null;
        if ($status == 'ok') {
            $urlParams = array(
                'ids' => $hotelId,
                'language' => 'es',
                'options' => 'information,amenities,pictures,room_types(pictures,information,amenities)',
                'resolve' => 'merge_info',
                'catalog_info' => 'true'
            );
            $hotelDetails = $this->get('despegar')->getHotelsDetails($urlParams);
            $hotelDetails = (is_array($hotelDetails)) ? $hotelDetails[0] : null;
            return $this->render('VientoSurAppAppBundle:Hotel:payHotelBooking.html.twig', array(
                'status' => $status,
                'detail' => $detail,
                'hotelId' => $hotelId,
                'hotelDetails' => $hotelDetails
            ));
        } else {
            return $this->render('VientoSurAppAppBundle:Hotel:errorHotelBooking.html.twig', array(
                'status' => $status,
                'detail' => $detail,
                'hotelId' => $hotelId,
                'hotelDetails' => $hotelDetails
            ));
        }
    }

    /**
     * @Route("/delete/fs", name="dete_file")
     *
     */
    public function deleteFileAction()
    {
        $fs = new Filesystem();
        $file = $this->container->getParameter('kernel.root_dir') . '/../web/voucher-vs.pdf';
        if (file_exists($file)){
        $fs->remove($file);
        }
        return new Response('file deleted');
    }

    /**
     * @Route("/booking/pdf/save/{detail}/{hotelId}/{email}/{reservationId}", name="viento_sur_app_save_hotel_pdf")
     */
    public function savePdfToAttachAction($detail, $hotelId, $email, $reservationId)
    {
//        $detail = $request->get('detail');
//        $hotelId = $request->get('hotel_id');
//        $email = $request->get('email');
//        $reservationId = $request->get('id');

        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository('VientoSurAppAppBundle:Reservation')->findOneById($reservationId);

        $hotelDetails = $this->get('despegar')->getHotelsDetails(array(
            'ids' => $hotelId,
            'language' => 'es',
            'options' => 'information,amenities,pictures,room_types(pictures,information,amenities)',
            'resolve' => 'merge_info',
            'catalog_info' => 'true'
        ));

        $reservationDetails = $this->get('despegar')->getReservationDetails($detail['reservation_id'], array(
            'email' => 'info@vientosur.net',
            'language' => 'es',
            'site' => 'AR'
        ), $this->getParameter('is_test'));

        $logoUrl = 'https://www.vientosur.net/bundles/vientosurappapp/images/vientosur-logo-color.png';

//        $html = $this->renderView('VientoSurAppAppBundle:Pdf:booking.html.twig', array(
//            'hotelDetails' => $hotelDetails[0],
//            'reservationDetails' => $reservationDetails,
//            'detail' => $detail,
//            'hotelId' => $hotelId,
//            'internal' => $reservation,
//            'logoUrl' => $logoUrl,
//            'pdf' => true
//        ));

        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                'VientoSurAppAppBundle:Pdf:booking.html.twig', array(
                'hotelDetails' => $hotelDetails[0],
                'reservationDetails' => $reservationDetails,
                'detail' => $detail,
                'hotelId' => $hotelId,
                'internal' => $reservation,
                'logoUrl' => $logoUrl,
                'pdf' => true
            )),
            $reservation->getId().'.pdf'
        );

        return new Response('work');
    }

    /**
     * @Route("/card-detail", name="hotel_card_detail")
     */
    public function getCardDetailAction(Request $request)
    {
        return new JsonResponse(
            $this->get('despegar')->getCardDetails($request->get('card'))
        );
    }

    /**
     * @Route("/booking/edit/{id}", name="viento_sur_app_edit_reservation")
     * * @Method("GET")
     * @Template()
     */
    public function editReservationAction($id, Request $request)
    {
        $despegar = $this->get('despegar');
//        $id = base64_decode($id);
        $em = $this->getDoctrine()->getManager();
        $internal = $em->getRepository('VientoSurAppAppBundle:Reservation')->findOneById($id);

        $reservation = $despegar->getReservationDetails($internal->getReservationId(), array(
            'email' => 'info@vientosur.net',
            'language' => 'es',
            'site' => 'AR'
        ), $this->getParameter('is_test'));

        $hotelDetails = $this->get('despegar')->getHotelsDetails(array(
            'ids' => $reservation['hotel']['id'],
            'language' => 'es',
            'options' => 'information,amenities,pictures,room_types(pictures,information,amenities)',
            'resolve' => 'merge_info',
            'catalog_info' => 'true'
        ));

        return [
            'hotelDetails' => $hotelDetails[0],
            'internal' => $internal,
            'reservationDetails' => $reservation
        ];
    }

    /**
     * @Route("/booking/edit/{internalId}/{id}", name="viento_sur_app_edit_patch_reservation")
     * @Method("PATCH")
     */
    public function patchEditReservationAction($internalId, $id, Request $request)
    {
        $despegar = $this->get('despegar');
        $cancel = $despegar->cancelReservation($id);
        $result = false;

        if ($cancel && isset($cancel['id'])) {
            $result = true;
            $em = $this->getDoctrine()->getManager();
            $internal = $em->getRepository('VientoSurAppAppBundle:Reservation')->findOneById($internalId);

            if ($internal != null) {
                $reservation = $despegar->getReservationDetails($internal->getReservationId(), array(
                    'email' => 'info@vientosur.net',
                    'language' => 'es',
                    'site' => 'AR'
                ), $this->getParameter('is_test'));

                $hotelDetails = $this->get('despegar')->getHotelsDetails(array(
                    'ids' => $reservation['hotel']['id'],
                    'language' => 'es',
                    'options' => 'information,amenities,pictures,room_types(pictures,information,amenities)',
                    'resolve' => 'merge_info',
                    'catalog_info' => 'true'
                ));

                $this->get('email.service')->sendCancellationEmail($internal->getEmail(), array(
                    'hotelDetails' => $hotelDetails[0],
                    'reservationDetails' => $reservation,
                    'internal' => $internal,
                    'idCancellation' => $cancel['id']
                ));
            }
        }
        return new JsonResponse(
            array(
                "cancelled" => $result,
                "id" => (($cancel != null) ? $cancel['id'] : 0)
            )
        );
    }
}
