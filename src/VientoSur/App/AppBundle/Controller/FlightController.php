<?php


namespace VientoSur\App\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use VientoSur\App\AppBundle\Entity\Airlines;
use VientoSur\App\AppBundle\Entity\AirlineAlliance;
use VientoSur\App\AppBundle\Entity\Airport;

/**
 * Flight Controller
 *
 * @Route("/{_locale}/vuelos", requirements={"_locale": "es|en|pt"}, defaults={"_locale": "es"})
 */
class FlightController extends Controller
{
    /**
     * @Route("/send/flights/process-search", name="viento_sur_process_search_flights")
     * @Method("POST")
     */
    public function sendFlightsProcessSearch(Request $request)
    {
        if ($this->getParameter('is_test')) {
            $fromText = 'Buenos Aires, Argentina';
            $toText = 'Miami, Estados Unidos';
            $from = 'BUE';
            $to = 'MIA';
        } else {
            $fromText = $request->get('from-flight');
            $toText = $request->get('to-flight');
            $from = $request->get('originFlight');
            $to = $request->get('destinationFlight');
        }

        list($day, $month, $year) = explode("/", $request->get('start'));
        $fromDate = $year . '-' . $month . '-' . $day;
        if ($request->get('only_out') == 'true') {
            $toDate = null;
        }elseif($request->get('multipledestination') == 'true'){
            $toDate = null;
        } else{
            list($day, $month, $year) = explode("/", $request->get('end'));
            $toDate = $year . '-' . $month . '-' . $day;
        }

        $adults = $request->get('adultsPassengers');
        $childrens = $request->get('childrenPassengers');
        $childrenQty = 0;
        $infantQty = 0;
        $dataPassengers['adults'] = $request->get('adultsPassengers');;
        for ($i = 1; $i <= $childrens; $i++) {
            $dataPassengers['children'][$i] = $request->get('field-menor-' . $i);
            if ($request->get('field-menor-' . $i) == 'A') {
                $infantQty++;
            } else {
                $childrenQty++;
            }
        }

        $session = $request->getSession();
        $session->set('data_passengers', $dataPassengers);
        $session->set('departure_date', $request->get('start'));
        $session->set('return_date', $request->get('end'));
        $session->set('origin_flight', [
            'text' => $fromText,
            'id' => $from
        ]);
        $session->set('destination_flight', [
            'text' => $toText,
            'id' => $to
        ]);

        $params = [
            'departure_date' => $fromDate,
            'return_date' => $toDate,
            'from' => $from,
            'to' => $to,
            'adults' => $adults,
            'childrens' => $childrenQty, // I para presentar infante en asiento, C para representar niño de 2 a 11 años, si hay más de uno, separados por guion "-"
            'infants' => $infantQty //cantidad de infantes en brazos
        ];

        $session->remove('multipledestinations');
        if($request->get('multipledestination') == 'true'){
            $session->set('multipledestinations', $request->get('multidestination'));
            $toDate = null;
        }

        if ($toDate) {
            $name = 'viento_sur_send_flights';
        } elseif($request->get('multipledestination') == 'true'){
                $name = 'viento_sur_send_flights_multi_destination';
                unset($params['return_date']);
        } else {
            $name = 'viento_sur_send_flights_one_way';
            unset($params['return_date']);
        }
        return $this->redirectToRoute($name, $params);
    }


    /**
     * @Route("/send/flights/results/{from}/{to}/{departure_date}/{return_date}/{adults}/{childrens}/{infants}", name="viento_sur_send_flights")
     * @Method("GET")
     */
    public function sendFlightsItinerariesAction($from, $to, $departure_date, $return_date, $adults, $childrens, $infants, Request $request)
    {
        $page = $request->query->get('page');
        if (!$page) {
            $page = 1;
            $offset = 0;
        } else {
            $offset = ($page - 1) * 25;
        }

        $locale = $request->getLocale();
        $lang = ($locale && in_array($locale, ['en', 'es', 'pt'])) ? $locale : 'es';

        $urlParams = [
            "site" => "AR",
            "departure_date" => $departure_date,
            "return_date" => $return_date,
            "language" => $lang,
            "from" => $from,
            "to" => $to,
            "adults" => $adults,
            "children" => $childrens,
            "infants" => $infants,
            "currency" => "ARS",
            "offset" => $offset,
            "limit" => "25"
        ];

        $results = $this->get('despegar')->getFlightItineraries($urlParams, $request->query->all());
        if(isset($results['status'])){
            $airlineData = [];
            $airportData = [];
            $airportCity = [];
            $total = 1;
            $viewParams = [
                'flightMenu' => true,
                'items' => $results,
                'airlineNames' => $airlineData,
                'airportNames' => $airportData,
                'airportCities' => $airportCity,
                'total' => $total,
                'page' => $page,
                'adults' => $adults
            ];

            return $this->render('@VientoSurAppApp/Flight/listFlightsItineraries.html.twig', $viewParams);
        }else{
            $total = ceil($results['paging']['total'] / 25);

            $airlines = [];
            $airports = [];
            if (isset($results['items'])) {
                foreach ($results['items'] as $item) {
                    if (isset($item['validating_carrier'])) {
                        if (!in_array($item['validating_carrier'], $airlines)) {
                            $airlines[] = $item['validating_carrier'];
                        }
                    }
                    foreach ($item['outbound_choices'] as $outbound) {
                        foreach ($outbound['segments'] as $segment) {
                            if (!in_array($segment['airline'], $airlines)) {
                                $airlines[] = $segment['airline'];
                            }
                            if (!in_array($segment['from'], $airports)) {
                                $airports[] = $segment['from'];
                            }
                            if (!in_array($segment['to'], $airports)) {
                                $airports[] = $segment['to'];
                            }
                        }
                    }
                    foreach ($item['inbound_choices'] as $inbound) {
                        foreach ($inbound['segments'] as $segment) {
                            if (!in_array($segment['airline'], $airlines)) {
                                $airlines[] = $segment['airline'];
                            }
                            if (!in_array($segment['from'], $airports)) {
                                $airports[] = $segment['from'];
                            }
                            if (!in_array($segment['to'], $airports)) {
                                $airports[] = $segment['to'];
                            }
                        }
                    }
                }

                if (count($results['facets']) > 0) {
                    foreach ($results['facets'][1]['values'] as $detail) {
                        if (!in_array($detail['value'], $airlines)) {
                            $airlines[] = $detail['value'];
                        }
                    }

                    foreach ($results['facets'][3]['values'] as $detail) {
                        if (!in_array($detail['value'], $airports)) {
                            $airports[] = $detail['value'];
                        }
                    }

                    foreach ($results['facets'][4]['values'] as $detail) {
                        if (!in_array($detail['value'], $airports)) {
                            $airports[] = $detail['value'];
                        }
                    }
                }
            }

            $em = $this->getDoctrine()->getManager();

            $airlineData = [];
            if (!empty($airlines)) {
                $airlineResults = $em->getRepository('VientoSurAppAppBundle:Airlines')->findAirlinesIn($airlines);
                foreach ($airlineResults as $ar) {
                    $airlineData[$ar->getId()] = $ar->getName();
                }
            }

            $airportData = [];
            $airportCity = [];
            if (!empty($airports)) {
                $airportResults = $em->getRepository('VientoSurAppAppBundle:Airport')->findAirportsIn($airports);
                foreach ($airportResults as $ap) {
                    $airportData[$ap->getCode()] = $ap->getName();
                    $airportCity[$ap->getCode()] = $ap->getCity();
                }
            }

            $viewParams = [
                'flightMenu' => true,
                'items' => $results,
                'airlineNames' => $airlineData,
                'airportNames' => $airportData,
                'airportCities' => $airportCity,
                'total' => $total,
                'page' => $page,
                'adults' => $adults
            ];

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(
                    array(
                        'html' => $this->renderView('VientoSurAppAppBundle:Flight:listDetailFlights.html.twig',
                            $viewParams
                        ),
                        'paging' => $results['paging'],
                        'total' => $total,
                        'page' => $page
                    )
                );
            } else {
                return $this->render('VientoSurAppAppBundle:Flight:listFlightsItineraries.html.twig', $viewParams);
            }
        }
    }


    /**
     * @Route("/oneway/flights/results/{from}/{to}/{departure_date}/{adults}/{childrens}/{infants}", name="viento_sur_send_flights_one_way")
     * @Method("GET")
     */
    public function sendFlightsItinerariesOneWayAction($from, $to, $departure_date, $adults, $childrens, $infants, Request $request)
    {
        $page = $request->query->get('page');
        if (!$page) {
            $page = 1;
            $offset = 0;
        } else {
            $offset = ($page - 1) * 25;
        }

        $locale = $request->getLocale();
        $lang = ($locale && in_array($locale, ['en', 'es', 'pt'])) ? $locale : 'es';

        $urlParams = [
            "site" => "AR",
            "departure_date" => $departure_date,
            "language" => $lang,
            "from" => $from,
            "to" => $to,
            "adults" => $adults,
            "children" => $childrens,
            "infants" => $infants,
            "currency" => "ARS",
            "offset" => $offset,
            "limit" => "25"
        ];

        $results = $this->get('despegar')->getFlightItineraries($urlParams, $request->query->all());

        if (isset($results['items'])) {
            $airlines = [];
            foreach ($results['items'] as $item) {
                if (isset($item['validating_carrier'])) {
                    if (!in_array($item['validating_carrier'], $airlines)) {
                        $airlines[] = $item['validating_carrier'];
                    }
                }
                foreach ($item['outbound_choices'] as $outbound) {
                    foreach ($outbound['segments'] as $segment) {
                        if (!in_array($segment['airline'], $airlines)) {
                            $airlines[] = $segment['airline'];
                        }
                    }
                }
                foreach ($results['facets'][1]['values'] as $detail) {
                    if (!in_array($detail['value'], $airlines)) {
                        $airlines[] = $detail['value'];
                    }
                }
            }
        }

        $em = $this->getDoctrine()->getManager();
        $airlineResults = $em->getRepository('VientoSurAppAppBundle:Airlines')->findAirlinesIn($airlines);
        $airlineData = [];
        foreach ($airlineResults as $ar) {
            $airlineData[$ar->getId()] = $ar->getName();
        }

        return $this->render('VientoSurAppAppBundle:Flight:listFlightsItineraries.html.twig', array(
            'flightMenu' => true,
            'items' => $results,
            'airlineNames' => $airlineData
        ));
    }

    /**
     * @param $departure_date
     * @param $adults
     * @param $childrens
     * @param $infants
     * @param $request
     * @Route("/multi-destination/flights/results/{departure_date}/{adults}/{childrens}/{infants}", name="viento_sur_send_flights_multi_destination")
     * @Method("GET")
     * @return array
     */
    public function sendFlightsItinerariesMultiDestinationAction($departure_date, $adults, $childrens, $infants, Request $request)
    {
        $page = $request->query->get('page');
        if (!$page) {
            $page = 1;
            $offset = 0;
        } else {
            $offset = ($page - 1) * 25;
        }

        $locale = $request->getLocale();
        $lang = ($locale && in_array($locale, ['en', 'es', 'pt'])) ? $locale : 'es';

        $urlParams = [
            "site" => "AR",
            "departure_date" => $departure_date,
            "language" => $lang,
//            "from" => $from,
//            "to" => $to,
            "adults" => $adults,
            "children" => $childrens,
            "infants" => $infants,
//            "currency" => "ARS",
            "offset" => $offset,
            "limit" => "25"
        ];

        $session = $this->container->get('session');
        $multidestination = $session->get('multipledestinations');

        foreach($multidestination as $dataKey => $data){
            $i = $dataKey;
            $i++;

            foreach ($data as $key => $value){
                if($i == 1){
                    if ($key == "originFlight"){
                        $urlParams["from"] = $value;
                    }
                    if ($key == "destinationFlight"){
                        $urlParams["to"] = $value;
                    }
                }elseif($i >1){
                    if ($key == "originFlight"){
                        $urlParams["from".$i] = $value;
                    }
                    if ($key == "destinationFlight"){
                        $urlParams["to".$i] = $value;
                    }
                    if ($key == "start"){
                        list($day, $month, $year) = explode("/", $value);
                        $date = $year . '-' . $month . '-' . $day;
                        $urlParams["departure_date".$i] = $date;
                    }
                }
            }
        }

        $results = $this->get('despegar')->getFlightItineraries($urlParams, $request->query->all());
        if(isset($results['status'])){

            $airlineData = [];
            $airportData = [];
            $airportCity = [];
            $total = 1;
            $viewParams = [
                'flightMenu' => true,
                'items' => $results,
                'airlineNames' => $airlineData,
                'airportNames' => $airportData,
                'airportCities' => $airportCity,
                'total' => $total,
                'page' => $page,
                'adults' => $adults,
                'multidestination' => $multidestination,
            ];
            return $this->render('VientoSurAppAppBundle:Flight/MultiDestination:listFlightsItineraries.html.twig', $viewParams);

        }else{
            $total = ceil($results['paging']['total'] / 25);

            $airlines = [];
            $airports = [];
            if (isset($results['items'])) {
                foreach ($results['items'] as $item) {
                    if (isset($item['validating_carrier'])) {
                        if (!in_array($item['validating_carrier'], $airlines)) {
                            $airlines[] = $item['validating_carrier'];
                        }
                    }
                    foreach ($item['routes'] as $routes) {
                        foreach ($routes['segments'] as $segment) {
                            if (!in_array($segment['airline'], $airlines)) {
                                $airlines[] = $segment['airline'];
                            }
                            if (!in_array($segment['from'], $airports)) {
                                $airports[] = $segment['from'];
                            }
                            if (!in_array($segment['to'], $airports)) {
                                $airports[] = $segment['to'];
                            }
                        }
                    }
    //                foreach ($item['inbound_choices'] as $inbound) {
    //                    foreach ($inbound['segments'] as $segment) {
    //                        if (!in_array($segment['airline'], $airlines)) {
    //                            $airlines[] = $segment['airline'];
    //                        }
    //                        if (!in_array($segment['from'], $airports)) {
    //                            $airports[] = $segment['from'];
    //                        }
    //                        if (!in_array($segment['to'], $airports)) {
    //                            $airports[] = $segment['to'];
    //                        }
    //                    }
    //                }
                }

                if (count($results['facets']) > 0) {
                    foreach ($results['facets'][1]['values'] as $detail) {
                        if (!in_array($detail['value'], $airlines)) {
                            $airlines[] = $detail['value'];
                        }
                    }

                    foreach ($results['facets'][3]['values'] as $detail) {
                        if (!in_array($detail['value'], $airports)) {
                            $airports[] = $detail['value'];
                        }
                    }

    //                foreach ($results['facets'][4]['values'] as $detail) {
    //                    if (!in_array($detail['value'], $airports)) {
    //                        $airports[] = $detail['value'];
    //                    }
    //                }
                }
            }

            $em = $this->getDoctrine()->getManager();

            $airlineData = [];
            if (!empty($airlines)) {
                $airlineResults = $em->getRepository('VientoSurAppAppBundle:Airlines')->findAirlinesIn($airlines);
                foreach ($airlineResults as $ar) {
                    $airlineData[$ar->getId()] = $ar->getName();
                }
            }

            $airportData = [];
            $airportCity = [];
            if (!empty($airports)) {
                $airportResults = $em->getRepository('VientoSurAppAppBundle:Airport')->findAirportsIn($airports);
                foreach ($airportResults as $ap) {
                    $airportData[$ap->getCode()] = $ap->getName();
                    $airportCity[$ap->getCode()] = $ap->getCity();
                }
            }

            $viewParams = [
                'flightMenu' => true,
                'items' => $results,
                'airlineNames' => $airlineData,
                'airportNames' => $airportData,
                'airportCities' => $airportCity,
                'total' => $total,
                'page' => $page,
                'adults' => $adults,
                'multidestination' => $multidestination,
            ];

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(
                    array(
                        'html' => $this->renderView('VientoSurAppAppBundle:Flight/MultiDestination:listDetailFlights.html.twig',
                            $viewParams
                        ),
                        'paging' => $results['paging'],
                        'total' => $total,
                        'page' => $page
                    )
                );
            } else {
                return $this->render('VientoSurAppAppBundle:Flight/MultiDestination:listFlightsItineraries.html.twig', $viewParams);
            }
        }
    }

    /**
     *
     * @Route("/autocomplete-flight", name="flight_autocomplete")
     * @Method("GET")
     */
    public function autoCompleteFlightAction(Request $request)
    {
        $type = 'HOTELS';
        $urlParams = [
            'query' => $request->get('query'),
            'product' => $type,
            'locale' => 'es-AR',
            'city_result' => '5',
            'airport_result' => '5'
        ];

        $results = $this->get('despegar')->autocomplete($urlParams);
        $response = [];
        if ($results && !isset($results['code'])) {
            foreach ($results as $item) {
                $category = '';
                if ($item['facet'] == 'CITY') {
                    $category = 'Ciudades';
                } else if ($item['facet'] == 'AIRPORT') {
                    $category = 'Aereopuertos';
                }
                $response[] = [
                    'value' => $item["description"],
                    'data' => [
                        'category' => $category,
                        'id' => $item["code"]
                    ]
                ];
            }
        }
        return new JsonResponse(array("suggestions" => $response, 'query' => $request->get('query'), 'test' => $results));
    }

    /**
     * @Route("/load-arlines", name="load_airlines")
     * @Method("GET")
     */
    public function loadAirlinesAction()
    {
        $results = $this->get('despegar')->getFlightAirlines([
            "site" => "AR"
        ]);

        $em = $this->getDoctrine()->getManager();
        $arr = [];
        for ($i = 0; $i < count($results); $i++) {
            $airline = $results[$i];
            if (!in_array($airline['id'], $arr)) {
                $objAirline = new Airlines();
                $objAirline->setId($airline['id']);
                $arr[] = $airline['id'];
                $objAirline->setName($airline['name']);
                if (isset($airline['alliance'])) {
                    $ojbAlliance = new AirlineAlliance();
                    $ojbAlliance->setCode($airline['alliance']['id']);
                    $ojbAlliance->setName($airline['alliance']['name']);
                    $ojbAlliance->setAirline($objAirline);
                    $em->persist($ojbAlliance);
                }
                $em->persist($objAirline);
            }
        }
        $em->flush();

        return new JsonResponse(array("result" => "ready"));
    }

    /**
     * @Route("/load-arline-detail", name="load_airline-detail")
     * @Method("GET")
     */
    public function loadAirlineDetailAction()
    {
        $results = $this->get('despegar')->getFlightAirlineDetail('V0', [
            "site" => "AR"
        ]);

        return new JsonResponse(array("result" => $results));
    }

    /**
     * @Route("/booking/flight/send/booking", name="viento_sur_send_flight_booking")
     * @Method("POST")
     */
    public function sendFlightBookingAction(Request $request)
    {
        $fields = $request->request->all();
        if (isset($fields['item_multiple'])){
            $date = new \DateTime();
            //id del itinerario
            $itineraryId = 'combination_' . $fields['item_id'] .'-'. $date->format('Y-m-d H:i:s');

            return $this->redirectToRoute('viento_booking_flight_pay_multi_destination', array(
                'item_id' => $fields['item_id'],
                'itinerary_id' => $fields['item_id'],
            ));
        }else{
            $optionId = $fields['option_id'];
            $outbound = $fields['optionsRadiosOut' . $optionId];
            $inbound = (isset($fields['optionsRadiosIn' . $optionId])) ? $fields['optionsRadiosIn' . $optionId] : null;

            $itineraryId = 'combination_' . $outbound . (($inbound) ? '_' . $inbound : '');

            return $this->redirectToRoute('viento_booking_flight_pay', array(
                'outbound' => $outbound,
                'inbound' => $inbound,
                'item_id' => $fields['item_id'],
                'itinerary_id' => $fields[$itineraryId]
            ));
        }
    }

    /**
     * @Route("/booking/pay", name="viento_booking_flight_pay")
     */
    public function bookingFlightPayAction(Request $request)
    {
        $params = $request->query->all();

        $locale = $request->getLocale();
        $lang = ($locale && in_array($locale, ['en', 'es', 'pt'])) ? $locale : 'es';

        $urlParams = [
            'itinerary_id' => $params['itinerary_id'],
            'language' => $lang,
            'tracker_id' => '',
            'country' => 'AR'
        ];

        $flightService = $this->get('flights_service');

        if ($request->getMethod() == 'GET') {
            $itineraryDetail = $flightService->getItineraryDetail($params['itinerary_id']);

            $booking = $flightService->getCheckoutData($urlParams);
            $request->getSession()->set('flightBooking', json_encode($booking));
            $request->getSession()->set('itineraryDetail', json_encode($itineraryDetail));
        } else {
            $itineraryDetail = json_decode($request->getSession()->get('itineraryDetail'), true);
            $booking = json_decode($request->getSession()->get('flightBooking'), true);
            //$session->remove('formBooking');
        }


        $formNewPay = $this->createFormBuilder($booking, ['allow_extra_fields' => true]);
        $formNewPay = $flightService->initForm($booking, $formNewPay);
        $formNewPaySend = $formNewPay->getForm();

        $paymentMethods = $flightService->getPaymentMethods($itineraryDetail);

        $string = bin2hex(random_bytes(16));
        $riskAnalysis = [
            "a" => [
                'orgId' => $this->getParameter('risk_provider_id_a')
            ],
            "b" => [
                'orgId' => ($this->getParameter('is_test')) ? $this->getParameter('risk_provider_id_b_test') : $this->getParameter('risk_provider_id_b')
            ],
            'sessionId' => $string
        ];

        if ($request->getMethod() == 'POST') {
            $formNewPaySend->handleRequest($request);

            if ($formNewPaySend->isValid()) {
                $formData = $formNewPaySend->getData();

                $status = 'ok';
                try {
                    $reservation = $flightService->processReservation(
                        $formData,
                        $booking,
                        $request->getClientIp(),
                        $params, $itineraryDetail,
                        $request->getSession()->get('origin_flight'),
                        $request->getSession()->get('destination_flight')
                    );

                } catch (\Exception $e) {
                    if ($e->getMessage() == 'CREDIT_CARD') {
                        $this->get('session')->getFlashBag()->add(
                            'card_msg',
                            ''
                        );
                        return $this->render('VientoSurAppAppBundle:Flight:bookingFlightPay.html.twig', array(
                            'flightMenu' => true,
                            'formNewPay' => $formNewPaySend->createView(),
                            'formChoice' => $booking,
                            'itineraryDetail' => $itineraryDetail,
                            'paymentMethods' => $paymentMethods,
                            'riskAnalysis' => $riskAnalysis,
                            'cardList' => $this->get('app.card')->getCards(),
                            'bankList' => $this->get('app.bank')->getBanks()
                        ));
                    } else {
                        throw new \Exception($e);
                    }
                }

                return $this->redirectToRoute('viento_sur_app_booking_flight_summary', array(
                    'status' => $status,
                    'itinerary' => $params['itinerary_id'],
                    'reservation' => $reservation
                ));
            }
        }

        return $this->render('VientoSurAppAppBundle:Flight:bookingFlightPay.html.twig', array(
            'flightMenu' => true,
            'formNewPay' => $formNewPaySend->createView(),
            'formChoice' => $booking,
            'itineraryDetail' => $itineraryDetail,
            'paymentMethods' => $paymentMethods,
            'riskAnalysis' => $riskAnalysis,
            'cardList' => $this->get('app.card')->getCards(),
            'bankList' => $this->get('app.bank')->getBanks()
        ));
    }

    /**
     * @Route("/booking/pay/multi-destination", name="viento_booking_flight_pay_multi_destination")
     */
    public function bookingFlightPayMultidestinationAction(Request $request)
    {
        $params = $request->query->all();

        $locale = $request->getLocale();
        $lang = ($locale && in_array($locale, ['en', 'es', 'pt'])) ? $locale : 'es';

        $urlParams = [
            'itinerary_id' => $params['itinerary_id'],
            'language' => $lang,
            'tracker_id' => '',
            'country' => 'AR'
        ];

        $flightService = $this->get('flights_service');

        if ($request->getMethod() == 'GET') {
            $itineraryDetail = $flightService->getItineraryDetail($params['itinerary_id']);

            $booking = $flightService->getCheckoutData($urlParams);
            $request->getSession()->set('flightBooking', json_encode($booking));
            $request->getSession()->set('itineraryDetail', json_encode($itineraryDetail));
        } else {
            $itineraryDetail = json_decode($request->getSession()->get('itineraryDetail'), true);
            $booking = json_decode($request->getSession()->get('flightBooking'), true);
            //$session->remove('formBooking');
        }


        $formNewPay = $this->createFormBuilder($booking, ['allow_extra_fields' => true]);
        $formNewPay = $flightService->initForm($booking, $formNewPay);
        $formNewPaySend = $formNewPay->getForm();

        $paymentMethods = $flightService->getPaymentMethods($itineraryDetail);

        $string = bin2hex(random_bytes(16));
        $riskAnalysis = [
            "a" => [
                'orgId' => $this->getParameter('risk_provider_id_a')
            ],
            "b" => [
                'orgId' => ($this->getParameter('is_test')) ? $this->getParameter('risk_provider_id_b_test') : $this->getParameter('risk_provider_id_b')
            ],
            'sessionId' => $string
        ];

        if ($request->getMethod() == 'POST') {
            $formNewPaySend->handleRequest($request);

            if ($formNewPaySend->isValid()) {
                $formData = $formNewPaySend->getData();

                $status = 'ok';
                try {
                    $reservation = $flightService->processReservation(
                        $formData,
                        $booking,
                        $request->getClientIp(),
                        $params, $itineraryDetail,
                        $request->getSession()->get('origin_flight'),
                        $request->getSession()->get('destination_flight')
                    );

                } catch (\Exception $e) {
//                    if ($e->getMessage() == 'CREDIT_CARD') {
                        $this->get('session')->getFlashBag()->add(
                            'card_msg',
                            ''
                        );
                        return $this->render('@VientoSurAppApp/Flight/MultiDestination/bookingFlightPay.html.twig', array(
                            'flightMenu' => true,
                            'formNewPay' => $formNewPaySend->createView(),
                            'formChoice' => $booking,
                            'itineraryDetail' => $itineraryDetail,
                            'paymentMethods' => $paymentMethods,
                            'riskAnalysis' => $riskAnalysis,
                            'cardList' => $this->get('app.card')->getCards(),
                            'bankList' => $this->get('app.bank')->getBanks(),
                            'e' => $e
                        ));
//                    } else {
//                        mostrar error de pago
//                        throw new \Exception($e);
//                    }
                }

                return $this->redirectToRoute('viento_sur_app_booking_flight_summary', array(
                    'status' => $status,
                    'itinerary' => $params['itinerary_id'],
                    'reservation' => $reservation
                ));
            }
        }

        return $this->render('VientoSurAppAppBundle:Flight/MultiDestination:bookingFlightPay.html.twig', array(
            'flightMenu' => true,
            'formNewPay' => $formNewPaySend->createView(),
            'formChoice' => $booking,
            'itineraryDetail' => $itineraryDetail,
            'paymentMethods' => $paymentMethods,
            'riskAnalysis' => $riskAnalysis,
            'cardList' => $this->get('app.card')->getCards(),
            'bankList' => $this->get('app.bank')->getBanks()
        ));
    }

    /**
     *
     * @Route("/booking/summary/", name="viento_sur_app_booking_flight_summary")
     */
    public function payFlightBookingAction(Request $request)
    {
        $status = $request->get('status');
        $itinerary = $request->get('itinerary');
        $reservationId = $request->get('reservation');

        if ($status == 'ok') {

            $itineraryDetail = $this->get('despegar')->getFlightItineraryDetail($itinerary);

            $em = $this->getDoctrine()->getManager();
            $reservationResult = $em->getRepository('VientoSurAppAppBundle:FlightReservation')->find($reservationId);

            return $this->render('VientoSurAppAppBundle:Flight:payFlightBooking.html.twig', array(
                'flightMenu' => true,
                'itineraryDetail' => $itineraryDetail,
                'status' => $status,
                'id' => $itinerary,
                'reservation' => $reservationResult
            ));
        } else {
            return $this->render('VientoSurAppAppBundle:Flight:errorFlightBooking.html.twig', array(
                'flightMenu' => true,
                'status' => $status
            ));
        }
    }

    /**
     * @Route("/fill-airports", name="viento_sur_app_fill_airports")
     */
    public function fillAirportsAction()
    {
        $path = $this->get('kernel')->getRootDir() . '/../web' . '/airports_list.json';
        $content = file_get_contents($path);
        $airports = json_decode($content, true);
        $em = $this->getDoctrine()->getManager();

        foreach ($airports['airports'] as $airport) {
            if ($airport['name'] != null) {
                $obj = new Airport();
                $obj->setCode($airport['code']);
                $obj->setName($airport['name']);
                $obj->setCity($airport['city']);
                $obj->setCountry($airport['country']);
                $obj->setTimezone($airport['timezone']);
                $obj->setLatitude($airport['lat']);
                $obj->setLongitude($airport['lng']);
                $obj->setTerminal($airport['terminal']);
                $obj->setGate($airport['gate']);
                $em->persist($obj);
            }
        }
        $em->flush();

        return new JsonResponse([
            'result' => 'Registros agregados'
        ]);
    }
}