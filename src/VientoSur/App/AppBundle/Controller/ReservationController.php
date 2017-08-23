<?php

namespace VientoSur\App\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use VientoSur\App\AppBundle\Entity\Reservation;

/**
 * Hotel controller.
 *
 * @Route("/{_locale}/reservation", requirements={"_locale": "es|en|pt"}, defaults={"_locale": "es"})
 */
class ReservationController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/", name="dashboard_reservation_list")
     * @Method("GET")
     * @return response
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository("VientoSurAppAppBundle:Reservation")->findAll();
        $hotels = $em->getRepository('VientoSurAppAppBundle:Hotel')->findBy(array('origen' => 'VS'));

        return $this->render(':admin/reservation:list.html.twig', array(
            'entities' => $entities
        ));
    }
}