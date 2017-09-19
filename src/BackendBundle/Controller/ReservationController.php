<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("dashboard-reservation")
 */
class ReservationController extends Controller
{
    /**
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/", name="reservation_list")
     * @Method("GET")
     * @return response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $securityContext = $this->container->get('security.context');

        if ($securityContext->isGranted('ROLE_ADMIN')) {
            $entities = $em->getRepository("VientoSurAppAppBundle:Reservation")->findAll();
        }else{
            $hotel = $em->getRepository('VientoSurAppAppBundle:Hotel')->findOneBy(array(
                'created_by' => $this->getUser()->getId()
            ));
            $entities = $em->getRepository("VientoSurAppAppBundle:Reservation")->findBy(array(
                'hotelId' => $hotel->getId()
            ));
        }



        return $this->render(':admin/reservation:list.html.twig', array(
            'entities' => $entities
        ));
    }
}