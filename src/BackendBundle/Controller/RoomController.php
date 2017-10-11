<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use VientoSur\App\AppBundle\Entity\AmenityRoom;
use VientoSur\App\AppBundle\Entity\Room;
use BackendBundle\Form\RoomsType;

/**
 * @Route("dashboard-room")
 */
class RoomController extends Controller
{
    /**
     * @param $request
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/", name="room_list")
     * @Method("GET")
     * @return response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository("VientoSurAppAppBundle:Room")->findAll();

        $images = array();
        for($i = 0; $i < count($entities); $i++){
            $images[$i] = $em->getRepository('VientoSurAppAppBundle:Picture')->findOneBy(
                array('room' => $entities[$i]->getId()),
                array('id' =>'ASC'),
                1);
        }

        $querySearch = $request->get('query');

        if(!empty($querySearch)){
            $finder = $this->container->get('fos_elastica.finder.app.room');
            $query = $finder->createPaginatorAdapter($querySearch);
        }else {
            $dql = "SELECT r 
                FROM VientoSurAppAppBundle:Room r 
                ORDER BY r.id ASC";
            $query = $em->createQuery($dql);
        }

        $page = $request->query->getInt('page', 1);
        $paginator = $this->get('knp_paginator');
        $items_per_page = $this->getParameter('items_per_page');

        $pagination = $paginator->paginate($query, $page, $items_per_page);

        return $this->render(':admin/room:list.html.twig', array(
            'pagination' => $pagination,
            'images' => $images
        ));
    }

    /**
     * @param Request $request
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/new", name="room_new")
     * @return response
     */
    public function newAcion(Request $request)
    {
        $entity = new Room();

        $em = $this->getDoctrine()->getManager();

        $amenities = $em->getRepository('VientoSurAppAppBundle:Amenity')->findAll();

        $form = $this->createForm(new RoomsType(), $entity, array(
            'id' => $this->getUser()->getId()
        ));

        if($form->handleRequest($request)->isValid())
        {
            $payment_type = $em->getRepository('VientoSurAppAppBundle:PaymentType')->findOneBy(array('name' => 'at_destination'));
//            $amenity_value = $request->get('amenity');
//            $amenity_price = $request->get('amenity_price');

            $entity->setPaymentType($payment_type);
            $entity->setCreatedBy($this->getUser());
            $em->persist($entity);

//            for($i = 0; $i < count($amenity_value); $i++){
//                $amenity_room = new AmenityRoom();
//                $amenity = $em->getRepository('VientoSurAppAppBundle:Amenity')->find($amenity_value[$i]);
//
//                $amenity_room->setRoom($entity);
//                $amenity_room->setAmenity($amenity);
//                $amenity_room->setPrice($amenity_price[$i]);
//                $em->persist($amenity_room);
//            }

            $em->flush();
            $this->addFlash(
                'success',
                $this->get('translator')->trans('admin.messages.added')
            );
            return $this->redirectToRoute('room_list');
        }
        return $this->render(':admin/room:form.html.twig', array(
            'form' => $form->createView(),
            'amenities' => $amenities
        ));
    }

    /**
     * @param Request $request
     * @param Room $entity entity
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/edit/{id}", name="room_edit")
     * @return response
     */
    public function putAction(Request $request, Room $entity)
    {
        $em = $this->getDoctrine()->getManager();

        $request->setMethod('PATCH');

        $amenities = $em->getRepository('VientoSurAppAppBundle:Amenity')->findAll();
        $amenityRooms = $em->getRepository('VientoSurAppAppBundle:AmenityRoom')->findBy(array(
            'room' => $entity
        ));

        $form = $this->createForm(new RoomsType(), $entity, [
            "method" => $request->getMethod(),
            "id" => $this->getUser()->getId()
            ]);

        if ($form->handleRequest($request)->isValid())
        {
//            $amenity_value = $request->get('amenity');
//            $amenity_price = $request->get('amenity_price');

            $em->persist($entity);

//            for($i = 0; $i < count($amenity_value); $i++) {
//                if (is_array($amenity_value[$i])) {
//                    foreach ($amenity_value[$i] as $key => $value) {
//                        $amenity_room = $em->getRepository('VientoSurAppAppBundle:AmenityRoom')->find($key);
//                        $amenity = $em->getRepository('VientoSurAppAppBundle:Amenity')->find($value);
//                    }
//                    foreach ($amenity_price[$i] as $value) {
//                        $amenity_room->setPrice($value);
//                    }
//                } else {
//                    $amenity_room = new AmenityRoom();
//                    $amenity = $em->getRepository('VientoSurAppAppBundle:Amenity')->find($amenity_value[$i]);
//                    $amenity_room->setPrice($amenity_price[$i]);
//                }
//
//                $amenity_room->setRoom($entity);
//                $amenity_room->setAmenity($amenity);
//                $em->persist($amenity_room);
//            }
//
//            //Remove amenity from amenityRooms
//            for($j = 0; $j < count($amenityRooms); $j++){
//                if (!isset($amenity_value[$j])) {
//                    $em->remove($amenityRooms[$j]);
//                }
//            }

            $em->flush();
            $this->addFlash(
                'success',
                $this->get('translator')->trans('admin.messages.updated')
            );
            return $this->redirectToRoute('room_list');
        }
        return $this->render(':admin/room:form.html.twig', array(
            'form' => $form->createView(),
            'entity' => $entity,
            'amenities' => $amenities,
            'amenityRooms' => $amenityRooms
        ));
    }

    /**
     * @param Room $entity entity
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/delete/{id}", name="room_delete")
     * @return response
     */
    public function deleteAction(Room $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $beds = $em->getRepository('VientoSurAppAppBundle:Bed')->findBy(array('room' => $entity));

        foreach ($beds as $bed){
            $bed->setRoom(null);
            $em->persist($bed);
        }

        $em->remove($entity);
        $em->flush();
        $this->addFlash(
            'success',
            $this->get('translator')->trans('admin.messages.deleted')
        );
        return $this->redirectToRoute('room_list');
    }
}