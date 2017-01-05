<?php


namespace VientoSur\App\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Flight Controller
 *
 * @Route("/vuelos")
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
        list($day, $month, $year) = explode("/", $request->get('end'));
        $toDate = $year . '-' . $month . '-' . $day;

        $adults = $request->get('adultsPassengers');
        $childrens = $request->get('childrenPassengers');
        $childrenQty = 0;
        $infantQty = 0;
        for ($i = 1; $i <= $childrens; $i++) {
            if ($request->get('field-menor-' . $i) == 'A') {
                $infantQty++;
            } else {
                $childrenQty++;
            }
        }

        $session = $request->getSession();
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

        return $this->redirectToRoute('viento_sur_send_flights', array(
            'departure_date' => $fromDate,
            'return_date' => $toDate,
            'from' => $from,
            'to' => $to,
            'adults' => $adults,
            'childrens' => $childrenQty, // I para presentar infante en asiento, C para representar niño de 2 a 11 años, si hay más de uno, separados por guion "-"
            'infants' => $infantQty //cantidad de infantes en brazos
        ));
    }


    /**
     * @Route("/send/flights/results/{from}/{to}/{departure_date}/{return_date}/{adults}/{childrens}/{infants}", name="viento_sur_send_flights")
     * @Method("GET")
     */
    public function sendFlightsItinerariesAction($from, $to, $departure_date, $return_date, $adults, $childrens, $infants, Request $request)
    {


        $urlParams = [
            "site" => "AR",
            "departure_date" => $departure_date,
            "return_date" => $return_date,
            "language" => "es",
            "from" => $from,
            "to" => $to,
            "adults" => $adults,
            "children" => $childrens,
            "infants" => $infants,
            "offset" => '0',
            "limit" => "10",
            "currency" => "ARS"
        ];
        $results = $this->get('despegar')->getFlightItineraries($urlParams);

        return $this->render('VientoSurAppAppBundle:Flight:listFlightsItineraries.html.twig', array(
            'flightMenu' => true,
            'items' => $results
        ));
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
                        'id' => $item["facet"] . '-' . $item["code"]
                    ]
                ];
            }
        }
        return new JsonResponse(array("suggestions" => $response, 'query' => $request->get('query'), 'test' => $results));
    }
}