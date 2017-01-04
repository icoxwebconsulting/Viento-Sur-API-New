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
            $from = '';
            $to = '';
        } else {
            $from = $request->get('from-flight');
            $to = $request->get('to-flight');
        }

        list($day, $month, $year) = explode("/", $request->get('start'));
        $fromDate = $year . '-' . $month . '-' . $day;
        list($day, $month, $year) = explode("/", $request->get('end'));
        $toDate = $year . '-' . $month . '-' . $day;

        $adults = $request->get('adults-value');
        $childrens = $request->get('childrens-value');
        $childrenQty = 0;
        $infantQty = 0;
        for ($i = 1; $i <= $childrens; $i++) {
            if ($request->get('field-menor-' . $i) == 'A') {
                $infantQty++;
            } else {
                $childrenQty++;
            }
        }

        return $this->redirectToRoute('', array(
        ));
    }


    /**
     *
     * @Route("/send/flights/itineraries/{page}", name="viento_sur_send_flights")
     * @Method("GET")
     */
    public function sendFlightsItinerariesAction($page, Request $request)
    {
        //TODO: traer los valores de la consulta del api
        $from = 'BUE';//$request->get('from-flight');
        $to = 'MAD';//$request->get('to-flight');
        list($day, $month, $year) = explode("/", $request->get('start'));
        $fromDate = $year . '-' . $month . '-' . $day;
        list($day, $month, $year) = explode("/", $request->get('end'));
        $toDate = $year . '-' . $month . '-' . $day;

        $adults = $request->get('adults-value');
        $childrens = $request->get('childrens-value');
        $childrenQty = 0;
        $infantQty = 0;
        for ($i = 1; $i <= $childrens; $i++) {
            if ($request->get('field-menor-' . $i) == 'A') {
                $infantQty++;
            } else {
                $childrenQty++;
            }
        }

        $offset = ($page - 1) * 10;

        $urlParams = [
            "site" => "AR",
            "from" => $from,
            "to" => $to,
            "departure_date" => $fromDate,
            "adults" => $adults,
            "return_date" => $toDate,
            "children" => $childrenQty,
            "infants" => $infantQty,
            "offset" => $offset,
            "limit" => "10"
        ];
        $results = $this->get('despegar')->getFlightItineraries($urlParams);

        return $this->render('VientoSurAppAppBundle:Flight:listFlightsItineraries.html.twig', array(
            'items' => $results,
            'offset' => $offset,
            'limit' => "10"
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
                if ($item['facet'] == 'CITY') {
                    $response[] = [
                        'value' => $item["description"],
                        'data' => [
                            'category' => 'Ciudades',
                            'id' => $item["id"]
                        ]
                    ];
                } else if ($item['facet'] == 'AIRPORT') {
                    $response[] = [
                        'value' => $item["description"],
                        'data' => [
                            'category' => 'Aereopuertos',
                            'id' => 'AIRPORT-' . $item["id"]
                        ]
                    ];
                }
            }
        }
        return new JsonResponse(array("suggestions" => $response, 'query' => $request->get('query'), 'test' => $results));
    }
}