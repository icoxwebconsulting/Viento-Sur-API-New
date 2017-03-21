<?php
/**
 * Created by Yolanda Gonzalez.
 * User: yolandag0302@gmail.com
 * Date: 3/8/16
 * Time: 1:57 PM
 */

namespace VientoSur\App\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class IndexController extends Controller
{
    /**
     * @Route("/{_locale}", name="homepage", requirements={"_locale": "es|en|pt"}, defaults={"_locale": "es"})
     * @Template("VientoSurAppAppBundle:Index:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $country = $request->get('country', null);
        $this->get('session')->set('country', ($country) ? $country : 'ar');
        $request->setLocale(($request->getLocale() == '/') ? 'es' : $request->getLocale());
        return array(
            'isTest' => $this->getParameter('is_test')
        );
    }

    /**
     * @Route("/change-option", name="change_option")
     * @Template("VientoSurAppAppBundle:Index:option.html.twig")
     */
    public function changeOptionAction(Request $request)
    {

        $languages['es']['icon'] = 'bundles/vientosurappapp/images/fl-ar.png';
        $languages['es']['name'] = 'Español';
        $languages['en']['icon'] = 'bundles/vientosurappapp/images/fl-en.png';
        $languages['en']['name'] = 'English';
        $languages['pt']['icon'] = 'bundles/vientosurappapp/images/fl_br.png';
        $languages['pt']['name'] = 'Portuguese';

        $currencies['ars']['name'] = "AR$";
        //$currencies['usd']['name'] = "USD";
        //$currencies['eur']['name'] = "EUR";


        $language = $request->get('language', null);
        if ($language) {
            $this->get('session')->set('language', $language);
            $currencies[$language]['active'] = true;
        }

        $currency = $request->get('currency', null);
        if ($currency) {
            $this->get('session')->set('currency', $currency);
            $currencies[$currency]['active'] = true;
        }

        return array(
            'currencies' => $currencies,
            'languages' => $languages
        );
    }

    /**
     *
     * @Route("/autocomplete/", name="search_hotel_autocomplete")
     * @Method("GET")
     */
    public function autoCompleteDespegarAction(Request $request)
    {
        $type = 'HOTELS';
        $urlParams = [
            'query' => $request->get('query'),
            'product' => $type,
            'locale' => 'es-AR',
            'city_result' => '10',
            'hotel_result' => '5'
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
                } else if ($item['facet'] == 'HOTEL') {
                    $response[] = [
                        'value' => $item["description"],
                        'data' => [
                            'category' => 'Hoteles',
                            'id' => 'HOTEL-' . $item["hotel_id"]
                        ]
                    ];
                }
            }
        }
        return new JsonResponse(array("suggestions" => $response, 'query' => $request->get('query'), 'test' => $results));
    }

    /**
     *
     * @Route("/autocomplete-hotel/{division}", name="hotel_autocomplete")
     * @Method("GET")
     */
    public function autoCompleteHotelAction($division, Request $request)
    {
        $type = 'HOTELS';
        $urlParams = [
            'query' => $request->get('query'),
            'product' => $type,
            'locale' => 'es-AR',
            'hotel_result' => '5',
            'political_divisions_to_filter_results' => $division
        ];

        $results = $this->get('despegar')->autocomplete($urlParams);
        $response = [];
        if ($results && !isset($results['code'])) {
            foreach ($results as $item) {
                $temp = explode(',', $item["description"]);
                $response[] = [
                    'value' => $temp[0],
                    'data' => $item["hotel_id"]
                ];
            }
        }
        return new JsonResponse(array("suggestions" => $response, 'query' => $request->get('query'), 'test' => $results));
    }


    /**
     *
     * @Route("/autocomplete-state/", name="state_autocomplete")
     * @Method("GET")
     */
    public function autoCompleteStateAction(Request $request)
    {
        $type = 'HOTELS';
        $urlParams = [
            'query' => $request->get('query'),
            'product' => $type,
            'locale' => 'es-AR',
            'administrative_division_result' => '10'
        ];

        $results = $this->get('despegar')->autocomplete($urlParams);
        $response = [];
        if ($results && !isset($results['code'])) {
            foreach ($results as $item) {
                $city = [
                    'value' => $item["description"],
                    'data' => $item['item']["id"]
                ];
                $response[] = $city;
            }
        }
        return new JsonResponse(array("suggestions" => $response, 'query' => $request->get('query'), 'test' => $results));
    }

    /**
     *
     * @Route("/autocomplete-city/{state}", name="city_autocomplete")
     * @Method("GET")
     */
    public function autoCompleteCityAction($state, Request $request)
    {
        $type = 'HOTELS';
        $urlParams = [
            'query' => $request->get('query'),
            'product' => $type,
            'locale' => 'es-AR',
            'city_result' => '10',
            'political_divisions_to_filter_results' => $state
        ];

        $results = $this->get('despegar')->autocomplete($urlParams);
        $response = [];
        if ($results && !isset($results['code'])) {
            foreach ($results as $item) {
                $city = [
                    'value' => $item["description"],
                    'data' => $item['item']["id"]
                ];
                $response[] = $city;
            }
        }
        return new JsonResponse(array("suggestions" => $response, 'query' => $request->get('query'), 'test' => $results));
    }


    /**
     *
     * @Route("/autocomplete-city-arg/{state}", name="city_autocomplete_argentina")
     * @Method("GET")
     */
    public function autoCompleteCityArgentinaAction($state, Request $request)
    {
        $type = 'HOTELS';
        $urlParams = [
            'query' => $request->get('query'),
            'product' => $type,
            'locale' => 'es-AR',
            'city_result' => '10',
            //'language' => 'ES',
            'political_divisions_to_filter_results' => $state,
            'country_id' => '20010'
        ];

        $results = $this->get('despegar')->autocomplete($urlParams);
        $response = [];
        if ($results && !isset($results['code'])) {
            foreach ($results as $item) {
                $city = [
                    'value' => $item["description"],
                    'data' => $item['item']["id"]
                ];
                $response[] = $city;
            }
        }
        return new JsonResponse(array("suggestions" => $response, 'query' => $request->get('query'), 'test' => $results));
    }

    /**
     *
     * @Route("/pdf", name="pdf_test")
     * @Method("GET")
     */
    public function pdfTestAction(Request $request)
    {
        $html = $this->renderView('VientoSurAppAppBundle:Email:booking2.html.twig', array(
            'some' => 1
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
}
