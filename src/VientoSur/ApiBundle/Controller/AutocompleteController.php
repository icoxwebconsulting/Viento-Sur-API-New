<?php
namespace VientoSur\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as FOSRestBundleAnnotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


/**
 * Class AutocompleteController
 *
 * @package ApiBundel\Controller
 *
 * @FOSRestBundleAnnotations\View()
 * */
class AutocompleteController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get autocomplete
     *
     * @param String $query
     * @return mixed
     *
     * @FOSRestBundleAnnotations\Route("/autocomplete/{query}")
     * @ApiDoc(
     *  section="Autocomplete",
     *  description="Get Autocomplete",
     *  requirements={
     *     {
     *          "name"="query",
     *          "dataType"="string",
     *          "requirement"="true",
     *          "description"="city | hotel"
     *      }
     *  },
     *  statusCodes={
     *     200="Returned when successful",
     *     404="Result not found"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function getAutocompleteAction($query){
        $urlParams = array(
            'query' => $query,
            'product' => 'HOTELS',
            'locale' => 'es',
            'city_result' => '10',
            'hotel_result' => '5'
        );

//        $results = $this->get('despegar')->autocomplete($urlParams);
//        $response = new JsonResponse($results);

        $data = $this->get('despegar')->autocomplete($urlParams);

        if (!in_array('code', $data)) {
            $results = [
                'status' => 'success',
                'code' => 200,
                'data' => $data
            ];
        } else {
            $results = [
                'status' => 'error',
                'code' => 404,
            ];
        }

        return new JsonResponse($results);
    }


    /**
     * Get vs autocomplete
     *
     * @param String $query
     * @param $request
     * @return mixed
     *
     * @FOSRestBundleAnnotations\Route("/vs/autocomplete/{query}")
     * @ApiDoc(
     *  section="Autocomplete",
     *  description="Get VS Autocomplete",
     *  output="VientoSur\App\AppBundle\Entity\Hotel",
     *  requirements={
     *     {
     *          "name"="query",
     *          "dataType"="string",
     *          "requirement"="true",
     *          "description"="city | hotel"
     *      }
     *  },
     *  parameters={
     *     {
     *          "name"="limit",
     *          "dataType"="number",
     *          "required"="true",
     *          "description"="1"
     *      }
     *  },
     *  statusCodes={
     *     200="Returned when successful",
     *     404="Result not found"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "v1" = "#ff0000"
     *  }
     * )
     */
    public function getVsAutocompleteAction($query, Request $request){
        $serializer = $this->get('jms_serializer');

        $limit = $request->get('limit');

        $finder = $this->container->get('fos_elastica.finder.app.hotel');
        $entities = $finder->find($query, $limit);

        if ($entities) {
            $array = [];
            foreach ($entities as $entity){
                $array[] = [
                    'id' => '',
                    'item' => [
                        'id' => '',
                        'gid' => '',
                        'type' => 'GEO_POINT'
                    ],
                    'description' => $entity->getName().', '. $entity->getAddress(),
                    'geolocation' => [
                        'latitude' => $entity->getLatitude(),
                        'longitude' => $entity->getLongitude(),
                    ],
                    'facet' => 'HOTEL',
                    'fuzzy_search_result' => '',
                    'parent' => [
                        'id' => '',
                        'gid' => '',
                        'type' => ''
                    ],
                    'geo_point_type' => 'HOTEL',
                    'hotel_id' => $entity->getId(),
                    'origen' => $entity->getOrigen()
                ];

            }
            $results = [
                'status' => 'success',
                'code' => 200,
                'data' => $array,
                'count' => count($array)
            ];
        } else {
            $results = [
                'status' => 'error',
                'code' => 404,
            ];
        }

        return new Response($serializer->serialize($results, 'json'));
    }
}