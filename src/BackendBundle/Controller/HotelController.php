<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use VientoSur\App\AppBundle\Entity\AmenityHotel;
use VientoSur\App\AppBundle\Entity\Hotel;
use BackendBundle\Form\HotelFormType;

/**
 * @Route("dashboard-hotel")
 */
class HotelController extends Controller
{
    /**
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/", name="hotel_list")
     * @Method("GET")
     * @return response
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository("VientoSurAppAppBundle:Hotel")->findAll();

        $images = array();
        for($i = 0; $i < count($entities); $i++){
            $images[$i] = $em->getRepository('VientoSurAppAppBundle:Picture')->findOneBy(
                array('hotel' => $entities[$i]->getId()),
                array('id' =>'ASC'),
            1);
        }

        $dql = "SELECT h
                FROM VientoSurAppAppBundle:Hotel h 
                ORDER BY h.id ASC";
        $query = $em->createQuery($dql);

        $page = $request->query->getInt('page', 1);
        $paginator = $this->get('knp_paginator');
        $items_per_page = $this->getParameter('items_per_page');

        $pagination = $paginator->paginate($query, $page, $items_per_page);

        return $this->render(':admin/hotel:list.html.twig', array(
            'pagination' => $pagination,
            'images' => $images
        ));
    }

    /**
     * @param Request $request
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/new", name="hotel_new")
     * @return response
     */
    public function newAcion(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $hotel = $em->getRepository('VientoSurAppAppBundle:Hotel')->findBy(array(
            'created_by' => $this->getUser()->getId()
        ));

        if($hotel){
            $route = $this->get('router')->generate('hotel_list');
            $this->addFlash(
                'info',
                $this->get('translator')->trans('admin.messages.limit_hotel_by_user')
            );
            return new RedirectResponse($route);
        }

        $entity = new Hotel();
        $amenities = $em->getRepository('VientoSurAppAppBundle:Amenity')->findAll();

        $form = $this->createForm(new HotelFormType(), $entity);

        if($form->handleRequest($request)->isValid())
        {
            $entity->setPercentageGain(0);
            $entity->setOrigen('VS');
            $entity->setCreatedBy($this->getUser());
            $em->persist($entity);

            foreach($_POST['amenity'] as $data){
                $amenity_hotel = new AmenityHotel();
                $amenity = $em->getRepository('VientoSurAppAppBundle:Amenity')->find($data);
                $amenity_hotel->setAmenity($amenity);
                $amenity_hotel->setHotel($entity);
                $em->persist($amenity_hotel);
            }
            $em->flush();

            $this->addFlash(
                'success',
                $this->get('translator')->trans('admin.messages.added')
            );
            return $this->redirectToRoute('hotel_list');
        }
        return $this->render(':admin/hotel:form.html.twig', array(
            'form' => $form->createView(),
            'amenities' => $amenities
        ));
    }

    /**
     * @param Request $request
     * @param Hotel $entity entity
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/edit/{id}", name="hotel_edit")
     * @return response
     */
    public function putAction(Request $request, Hotel $entity)
    {
        $request->setMethod('PATCH');
        $em = $this->getDoctrine()->getManager();

        $amenities = $em->getRepository('VientoSurAppAppBundle:Amenity')->findAll();
        $amenityHotels = $em->getRepository('VientoSurAppAppBundle:AmenityHotel')->findAll();

        $form = $this->createForm(new HotelFormType(), $entity, ["method" => $request->getMethod()]);
        if ($form->handleRequest($request)->isValid())
        {

            $em->persist($entity);
            $em->flush();
            $this->addFlash(
                'success',
                $this->get('translator')->trans('admin.messages.updated')
            );
            return $this->redirectToRoute('hotel_list');
        }
        return $this->render(':admin/hotel:form.html.twig', array(
            'form' => $form->createView(),
            'entity' => $entity,
            'amenities' => $amenities,
            'amenityHotels' => $amenityHotels
    ));
    }

    /**
     * @param Hotel $entity entity
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/delete/{id}", name="hotel_delete")
     * @return response
     */
    public function deleteAction(Hotel $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $rooms = $em->getRepository('VientoSurAppAppBundle:Room')->findBy(array('hotel' => $entity));

        foreach ($rooms as $room){
            $room->setHotel(null);
            $em->persist($room);
        }

        $em->remove($entity);
        $em->flush();
        $this->addFlash(
            'success',
            $this->get('translator')->trans('admin.messages.deleted')
        );
        return $this->redirectToRoute('hotel_list');
    }
}