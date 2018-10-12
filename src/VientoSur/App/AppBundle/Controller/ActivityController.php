<?php
namespace VientoSur\App\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ActivityController extends Controller
{
    /**
     *
     * @Route("/send/activity/process-search", name="viento_sur_process_search_activity")
     * @Method("GET")
     */
    public function sendActivityProcessSearch(Request $request)
    {
        $session = $request->getSession();
        
        $destinationText = $request->get('autocomplete');
        $lat = $request->get('latitude');
        $lgn = $request->get('longitude');
        
        //form filter 
        $from_price = $request->get('from_price', 250);
        $to_price   = $request->get('to_price', 1560);
        
        $day_1      = $request->get('day_1', 0);
        $day_2      = $request->get('day_2', 0);
        $day_3      = $request->get('day_3', 0);
        $day_4      = $request->get('day_4', 0);
        $day_5      = $request->get('day_5', 0);
        $day_6      = $request->get('day_6', 0);
        $day_7      = $request->get('day_7', 0);
        
        $available  = $request->get('available');
        
        $duration   = $request->get('duration');
        
        $session->set('destination_activity', [
            'text' => $destinationText,
        ]);
        
        $em = $this->getDoctrine()->getManager();
        
        $entities = $em->getRepository("VientoSurAppAppBundle:Activity")->findByLatAndLgn($lat, $lgn);
        
        $page = $request->query->getInt('page', 1);
        $paginator = $this->get('knp_paginator');
        $items_per_page = $this->getParameter('items_per_page');

        $pagination = $paginator->paginate($entities, $page, $items_per_page,array('wrap-queries'=>true));
        
        return $this->render('VientoSurAppAppBundle:Activity:listActivityAvailabilities.html.twig', array(
            'pagination' => $pagination,
            'autocomplete' => $destinationText,
            'latitude' => $lat,
            'longitude' => $lgn,
            'from_price'=>$from_price,
            'to_price'=>$to_price,
            'day_1'=>$day_1,
            'day_2'=>$day_2,
            'day_3'=>$day_3,
            'day_4'=>$day_4,
            'day_5'=>$day_5,
            'day_6'=>$day_6,
            'day_7'=>$day_7,
            'available'=>$available,
            'duration'=>$duration
        ));
        
    }
}