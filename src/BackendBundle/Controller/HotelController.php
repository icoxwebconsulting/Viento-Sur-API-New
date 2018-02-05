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
 * @Route("/{_locale}/dashboard-hotel", requirements={"_locale": "es|en|pt"}, defaults={"_locale": "es"})
 */
class HotelController extends Controller
{
    /**
     * @param $request
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/", name="hotel_list")
     * @Method("GET")
     * @return response
     */
    public function indexAction(Request $request)
    {
        $querySearch = $request->get('query');

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository("VientoSurAppAppBundle:Hotel")->findAll();

        $images = array();
        for($i = 0; $i < count($entities); $i++){
            $images[$i] = $em->getRepository('VientoSurAppAppBundle:Picture')->findOneBy(
                array('hotel' => $entities[$i]->getId()),
                array('id' =>'ASC'),
            1);
        }

        if(!empty($querySearch)){
            $finder = $this->container->get('fos_elastica.finder.app.hotel');
            $query = $finder->createPaginatorAdapter($querySearch);
        }else {
            $dql = "SELECT h
                FROM VientoSurAppAppBundle:Hotel h 
                ORDER BY h.id ASC";
            $query = $em->createQuery($dql);
        }

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
            $namePt = $form->get('namePt')->getData();
            $descriptionPt = $form->get('descriptionPt')->getData();
            $nameEn = $form->get('nameEn')->getData();
            $descriptionEn = $form->get('descriptionEn')->getData();

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

            $entity->setName($namePt);
            $entity->setDescription($descriptionPt);
            $entity->setTranslatableLocale('pt');
            $em->persist($entity);
            $em->flush();

            $entity->setName($nameEn);
            $entity->setDescription($descriptionEn);
            $entity->setTranslatableLocale('en');
            $em->persist($entity);
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
        $amenityHotels = $em->getRepository('VientoSurAppAppBundle:AmenityHotel')->findBy(array(
            'hotel' => $entity
        ));

        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $translations = $repository->findTranslations($entity);

        $form = $this->createForm(new HotelFormType(), $entity, ["method" => $request->getMethod()]);
        if ($form->handleRequest($request)->isValid())
        {
            $namePt = $form->get('namePt')->getData();
            $descriptionPt = $form->get('descriptionPt')->getData();
            $nameEn = $form->get('nameEn')->getData();
            $descriptionEn = $form->get('descriptionEn')->getData();

            $em->persist($entity);
            $em->flush();

            $entity->setName($namePt);
            $entity->setDescription($descriptionPt);
            $entity->setTranslatableLocale('pt');
            $em->persist($entity);
            $em->flush();

            $entity->setName($nameEn);
            $entity->setDescription($descriptionEn);
            $entity->setTranslatableLocale('en');
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
            'amenityHotels' => $amenityHotels,
            'translations' => $translations
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
    
    /**
     * @param Request $request
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/name", name="hotel_name")
     * @return response
     */
    public function nameAction(Request $request)
    {
        $hotel = $this->getHotelByUser();
        
        $em = $this->getDoctrine()->getManager();
        
        if($request->isMethod('POST')){
            $name = $request->get('name_hotel');
            
            $hotel->setName($name);
            $em->persist($hotel);
            $em->flush();
            
            $this->get('session')->set('hotel_id', $hotel->getId());
            $this->get('session')->set('hotel_name', $hotel->getName());
            
            $this->addFlash(
                'success',
                $this->get('translator')->trans('admin.messages.updated')
            );
            return $this->redirectToRoute('hotel_name');
        }
        
        $name = $hotel->getName();
        
        return $this->render(':admin/hotel:name.html.twig', array(
            'name' => $name,
        ));
    }
    
    /**
     * @param Request $request
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/address", name="hotel_address")
     * @return response
     */
    public function addressAction(Request $request)
    {
        $hotel = $this->getHotelByUser();
        
        $em = $this->getDoctrine()->getManager();
        
        if($request->isMethod('POST')){
            
            $address = $request->get('address');
            $lat     = $request->get('latitude');
            $log     = $request->get('longitude');
            
            $hotel->setAddress($address);
            $hotel->setLatitude($lat);
            $hotel->setLongitude($log);
            $em->persist($hotel);
            $em->flush();
            
            $this->get('session')->set('hotel_id', $hotel->getId());
            $this->get('session')->set('hotel_address', $hotel->getAddress());
            
            $this->addFlash(
                'success',
                $this->get('translator')->trans('admin.messages.updated')
            );
            return $this->redirectToRoute('hotel_address');
            
        }
        
        $address = $hotel->getAddress();
        $lat     = $hotel->getLatitude();
        $log     = $hotel->getLongitude();
        
        return $this->render(':admin/hotel:address.html.twig', array(
            'address' => $address,
            'lat'     => $lat,
            'log'     => $log,
        ));
    }
    
    /**
     * @param Request $request
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/stars", name="hotel_stars")
     * @return response
     */
    
    public function starsAction(Request $request)
    {
        $hotel = $this->getHotelByUser();
        
        $em = $this->getDoctrine()->getManager();
        
        if($request->isMethod('POST')){
            $stars = $request->get('stars');
            
            $hotel->setStars($stars);
            $em->persist($hotel);
            $em->flush();
            
            $this->get('session')->set('hotel_id', $hotel->getId());
            $this->get('session')->set('hotel_stars', $hotel->getStars());
            
            $this->addFlash(
                'success',
                $this->get('translator')->trans('admin.messages.updated')
            );
            return $this->redirectToRoute('hotel_stars');
        }
        
        $stars = $hotel->getStars();
        
        return $this->render(':admin/hotel:stars.html.twig', array(
            'stars' => $stars,
        ));
    }    
    
    /**
     * @param Request $request
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/type", name="hotel_type")
     * @return response
     */
    
    public function hotelTypesAction(Request $request)
    {
        $hotel = $this->getHotelByUser();
        
        $em = $this->getDoctrine()->getManager();
        
        if($request->isMethod('POST')){
            $type = $request->get('hotel_type');
            
            $hoteltypeId = $em->getRepository('VientoSurAppAppBundle:HotelType')->findOneById($type);
            
            $hotel->setHotelTypes($hoteltypeId);
            $em->persist($hotel);
            $em->flush();
            
            $this->get('session')->set('hotel_id', $hotel->getId());
            $this->get('session')->set('hotel_type', $hotel->getHotelTypes());
            
            $this->addFlash(
                'success',
                $this->get('translator')->trans('admin.messages.updated')
            );
            return $this->redirectToRoute('hotel_type');
        }
        
        $hoteltypeAll = $em->getRepository('VientoSurAppAppBundle:HotelType')->findAll();
        
        $type = $hotel->getHotelTypes()?$hotel->getHotelTypes()->getId():'';
        
        return $this->render(':admin/hotel:type.html.twig', array(
            'type' => $type,
            'hoteltypeAll'=> $hoteltypeAll
        ));
    }
    
    
    /**
     * @param Request $request
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/chain", name="hotel_chain")
     * @return response
     */
    
    public function hotelChainAction(Request $request)
    {
        $hotel = $this->getHotelByUser();
        
        $em = $this->getDoctrine()->getManager();
        
        if($request->isMethod('POST')){
            $chain = $request->get('hotel_chain');
            
            $hotelChainId = $em->getRepository('VientoSurAppAppBundle:HotelChain')->findOneById($chain);
            
            $hotel->setHotelChain($hotelChainId);
            $em->persist($hotel);
            $em->flush();
            
            $this->get('session')->set('hotel_id', $hotel->getId());
            $this->get('session')->set('hotel_chain', $hotel->getHotelChain());
            
            $this->addFlash(
                'success',
                $this->get('translator')->trans('admin.messages.updated')
            );
            return $this->redirectToRoute('hotel_chain');
        }
        
        $hotelChainAll = $em->getRepository('VientoSurAppAppBundle:HotelChain')->findAll();
        
        $chain = $hotel->getHotelChain()?$hotel->getHotelChain()->getId():'';
        
        return $this->render(':admin/hotel:chain.html.twig', array(
            'chain' => $chain,
            'hotelChainAll'=> $hotelChainAll
        ));
    }
    
    /**
     * @param Request $request
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/profile-trip", name="hotel_profile")
     * @return response
     */
    
    public function hotelProfileAction(Request $request)
    {
        $hotel = $this->getHotelByUser();
        
        $em = $this->getDoctrine()->getManager();
        
        if($request->isMethod('POST')){
            $profile = $request->get('hotel_profile');
            
            $hotel->setProfileTrip($profile);
            $em->persist($hotel);
            $em->flush();
            
            $this->get('session')->set('hotel_id', $hotel->getId());
            $this->get('session')->set('hotel_profile', $hotel->getProfileTrip());
            
            $this->addFlash(
                'success',
                $this->get('translator')->trans('admin.messages.updated')
            );
            return $this->redirectToRoute('hotel_profile');
        }
        
        $hotelProfile = array(
                       'businessTrip' => 'admin.profile_trip.businessTrip',
                       'castle' => 'admin.profile_trip.castle',
                       'cheap' => 'admin.profile_trip.cheap',
                       'design' => 'admin.profile_trip.design',
                       'family' => 'admin.profile_trip.family',
                       'gourmet' => 'admin.profile_trip.gourmet',
                       'luxury' => 'admin.profile_trip.luxury',
                       'nature' => 'admin.profile_trip.nature',
                       'other' => 'admin.profile_trip.other',
                       'relax' => 'admin.profile_trip.relax',
                       'romantic' => 'admin.profile_trip.romantic',
                       'shopping' => 'admin.profile_trip.shopping',
                       'singles' => 'admin.profile_trip.singles',
                       'sport' => 'admin.profile_trip.sport',
                    );
        
        $profile = $hotel->getProfileTrip();
        
        return $this->render(':admin/hotel:profile_trip.html.twig', array(
            'profile' => $profile,
            'hotelProfile'=> $hotelProfile
        ));
    }
    
    
    /**
     * 
     * @return \VientoSur\App\AppBundle\Entity\Hotel
     */
    private function getHotelByUser()
    {
        $em = $this->getDoctrine()->getManager();
        $hotel = $em->getRepository('VientoSurAppAppBundle:Hotel')->findOneBy(array(
            'created_by' => $this->getUser()->getId()
        ));
        
        if(!$hotel){
          $hotel = new Hotel(); 
          $hotel->setPercentageGain(0);
          $hotel->setOrigen('VS');
          $hotel->setCreatedBy($this->getUser());
        }
        
        return $hotel;
    }        
}