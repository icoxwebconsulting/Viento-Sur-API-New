<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use VientoSur\App\AppBundle\Entity\Hotel;
use VientoSur\App\AppBundle\Entity\Room;

/**
 * @Route("/{_locale}/activity", requirements={"_locale": "es|en|pt"}, defaults={"_locale": "es"})
 */
class ActivityController extends Controller
{
     /**
     * @param Request $request
     * @Security("has_role('ROLE_ACTIVITY')")
     * @Route("/", name="actyvity_list")
     * @Method("GET")
     * @return array
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $dql = "SELECT a
                FROM VientoSurAppAppBundle:Activity a 
                ORDER BY a.id ASC";
        
        $query = $em->createQuery($dql);
        
        $page = $request->query->getInt('page', 1);
        $paginator = $this->get('knp_paginator');
        $items_per_page = $this->getParameter('items_per_page');

        $pagination = $paginator->paginate($query, $page, $items_per_page);
        
        return $this->render(':admin/activity_agency:list.html.twig', array(
            'pagination' => $pagination
        ));
        
        return $this->render(':admin/activity:list.html.twig',array(
            'pagination' => $pagination
        ));
    }
    
    /**
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/new", name="actyvity_new")
     * @Method("GET")
     * @return array
     */
    public function newAction()
    {
        return $this->render(':admin/activity:new.html.twig');
    }
}