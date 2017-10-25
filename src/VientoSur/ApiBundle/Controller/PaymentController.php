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


/**
 * Class AutocompleteController
 *
 * @package ApiBundel\Controller
 *
 * @FOSRestBundleAnnotations\View()
 * */
class PaymentController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get list cards
     *
     * @return mixed
     *
     * @FOSRestBundleAnnotations\Route("/cards")
     * @ApiDoc(
     *  section="Payment",
     *  description="Get list cards",
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
    public function getCardsAction(){
//        echo '<pre>'.print_r($this->get('app.card')->getCards(), true).'</pre>';

        $data = $this->get('app.card')->getCards();

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
     * Get list banks
     *
     * @return mixed
     *
     * @FOSRestBundleAnnotations\Route("/banks")
     * @ApiDoc(
     *  section="Payment",
     *  description="Get list banks",
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
    public function getBanksAction(){
//        echo '<pre>'.print_r($this->get('app.card')->getCards(), true).'</pre>';

        $data = $this->get('app.bank')->getBanks();

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
}