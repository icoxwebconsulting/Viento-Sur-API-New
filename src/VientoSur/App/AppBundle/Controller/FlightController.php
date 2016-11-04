<?php


namespace VientoSur\App\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
     *
     * @Route("/send/flights/itineraries", name="viento_sur_send_flights")
     * @Method("GET")
     */
    public function sendFlightsItinerariesAction(Request $request)
    {
        $params = $request->getQueryString();
        $from = 192635;//$request->get('from-flight');
        $destination = 197776;//$request->get('to-flight');
        $fromDate = $request->get('start-date');
        $toDate = $request->get('end-date');

        $adults = $request->get('adults');
        $adultsSelect = $request->get('adults-plus');

        $children = $request->get('childrens');
        $childrenSelect = $request->get('childrens-plus');

        $request->get('field-menor-');

        if ($toDate != "") {
            $url = "https://api.despegar.com/v3/flights/itineraries?site=ar&from=" . $from . "&to=" . $destination . "&departure_date=" . $fromDate . "&adults=" . $adultsSelect . "&return_date=" . $toDate . "&children=" . $childrenSelect . "&infants=" . $infantsSelect;
        } else {
            $url = "https://api.despegar.com/v3/flights/itineraries?site=ar&from=" . $from . "&to=" . $destination . "&departure_date=" . $fromDate . "&adults=" . $adultsSelect . "&children=" . $childrenSelect . "&infants=" . $infantsSelect;
        }
// https://api.despegar.com/v3/flights/itineraries?site=ar&from=BUE&to=MIA&departure_date=2015-08-21&adults=1&group_by=default
        $items = $this->cUrlExecAction($url);
        $results = json_decode($items, true);
        return $this->render('VientoSurAppAppBundle:Flight:listFlightsItineraries.html.twig', array('items' => $results['items']));
    }

    /**
     *
     * @Route("/autocomplete/", name="flight_autocomplete")
     * @Method("GET")
     */
    public function autoCompleteFlightAction(Request $request)
    {
        $type = 'HOTELS';
        $urlParams = [
            'query' => $request->get('query'),
            'product' => $type,
            'locale' => 'es-AR',
            'city_result' => '10'
        ];

        $results = $this->get('despegar')->autocomplete($urlParams);
        $response = [];
        if ($results && !isset($results['code'])) {
            foreach ($results as $item) {
                $city = Array();
                $city["value"] = $item["description"];
                $city["data"] = substr($item["id"], 5);
                $response[] = $city;
            }
        }
        return new JsonResponse(array("suggestions" => $response));
    }
}