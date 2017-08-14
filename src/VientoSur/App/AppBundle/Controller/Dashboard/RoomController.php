<?php

namespace VientoSur\App\AppBundle\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use VientoSur\App\AppBundle\Entity\Hotel;
use VientoSur\App\AppBundle\Entity\Room;
use VientoSur\App\AppBundle\Form\RoomsType;

/**
 * @Route("dashboard-room")
 */
class RoomController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/", name="room_list")
     * @Method("GET")
     * @return response
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository("VientoSurAppAppBundle:Room")->findBy(array('created_by' => $user->getId()));

        return $this->render(':admin/room:list.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * @param Request $request
     * @Security("has_role('ROLE_USER')")
     * @Route("/new", name="room_new")
     * @return response
     */
    public function newAcion(Request $request)
    {
        $entity = new Room();
        $form = $this->createForm(new RoomsType(), $entity);

        if($form->handleRequest($request)->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $payment_type = $em->getRepository('VientoSurAppAppBundle:PaymentType')->findOneBy(array('name' => 'at_destination'));

            $entity->setPaymentType($payment_type);
            $entity->setCreatedBy($this->getUser());
            $em->persist($entity);
            $em->flush();
            $this->addFlash(
                'success',
                $this->get('translator')->trans('admin.messages.added')
            );
            return $this->redirectToRoute('room_list');
        }
        return $this->render(':admin/room:form.html.twig', array(
            'form' => $form->createView()
        ));
    }
}