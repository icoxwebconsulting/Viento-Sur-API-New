<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use VientoSur\App\AppBundle\Entity\Reservation;

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

    /**
     * @param $entity
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/show/{id}", name="reservation_show")
     * @Method("GET")
     * @return response
     */
    public function showAction(Reservation $entity)
    {
        $em = $this->getDoctrine()->getManager();


        return $this->render(':admin/reservation:show.html.twig', array(
            'entity' => $entity
        ));
    }

    /**
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/test-reservation", name="test_reservation_new")
     * @Method("GET")
     * @return response
     */
    public function testNewAction()
    {
        $em = $this->getDoctrine()->getManager();

        $extraData = array(
          ''
        );


        $entity = new Reservation();
        $entity->setHolderName('phils garcia');
        $entity->setCheckin(new \DateTime("now"));
        $entity->setCheckout(new \DateTime("now"));
        $entity->setPhoneNumber('+584125983290');
        $entity->setEmail('eivanphils@gmail.com');
        $entity->setCardType('CA');
        $entity->setReservationId('13456');
        $entity->setHotelId(23);
        $entity->setTotalPrice(1000);
        $entity->setComments('comentarios');
        $entity->setOrigin('vientosur');

        $em->persist($entity);
        $em->flush();

        return $this->redirectToRoute('reservation_list');
    }
}