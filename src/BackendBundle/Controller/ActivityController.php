<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use VientoSur\App\AppBundle\Entity\Activity;
use BackendBundle\Form\ActivityType;
use VientoSur\App\AppBundle\Repository\ActivityRepository;

/**
 * @Route("/{_locale}/activity", requirements={"_locale": "es|en|pt"}, defaults={"_locale": "es"})
 */
class ActivityController extends Controller
{
     /**
     * @param Request $request
     * @Security("has_role('ROLE_ACTIVITY')")
     * @Route("/list", name="actyvity_list")
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
        
        return $this->render(':admin/activity:list.html.twig',array(
            'pagination' => $pagination
        ));
    }
    
    /**
     * @param Request $request
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/new", name="actyvity_new")
     * @return response
     */
    public function newAction(Request $request)
    {
        $entity = new Activity();
        $form = $this->createForm(new ActivityType(), $entity);
        $em = $this->getDoctrine()->getManager();
        
        $this->formAction($form, $request, $em, $entity, 'agregado', 'new');
        
        return $this->render(':admin/activity:form.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /**
     * @param Request $request
     * @param Activity $entity entity
     * @Security("has_role('ROLE_ACTIVITY')")
     * @Route("/edit/{id}", name="actyvity_edit")
     * @return response
     */
    public function editAction(Request $request, Activity $entity) {
        
        $request->setMethod('PATCH');

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new ActivityType(), $entity, [
            "method" => $request->getMethod(),
        ]);
        
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $translations = $repository->findTranslations($entity);
        
        $this->formAction($form, $request, $em, $entity, 'editado', 'edit');
        
        return $this->render(':admin/activity:form.html.twig', array(
            'form' => $form->createView(),
            'entity' => $entity,
            'translations' => $translations
        ));
    }    
    
    /**
     * @param Activity $entity entity
     * @Security("has_role('ROLE_ACTIVITY')")
     * @Route("/activate/{id}/{activate}", name="actyvity_activate")
     * @return route
     */
    public function activateAction(Request $request, Activity $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $activate = $request->get('activate');
        
        $entity->setAvailability($activate);
        $em->persist($entity);
        $em->flush();
        
        $text = $activate?'activado':'desactivado'; 
        
        $this->addFlash(
            'success',
            'La actividad se ha '.$text.' correctamente!'
        );
        return $this->redirectToRoute('actyvity_list');
    }    
    
    /**
     * @param Activity $entity entity
     * @Security("has_role('ROLE_ACTIVITY')")
     * @Route("/delete/{id}", name="actyvity_delete")
     * @return route
     */
    public function deleteAction(Activity $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($entity);
        $em->flush();
        $this->addFlash(
            'success',
            'La actividad se ha eliminado correctamente!'
        );
        return $this->redirectToRoute('actyvity_list');
    }    
    
    /**
     * formAction
     * @param Activity $form
     * @param Request $request
     * @param DoctrineManager $em
     * @param Activity $entity entity
     * @param String $textMsj msj
     * @param String $action action
     * @return response
     */
    protected function formAction($form, $request, $em, $entity, $textMsj, $action){
        
        if($form->handleRequest($request)->isValid())
        {
            
            $namePt = $form->get('namePt')->getData();
            $nameEn = $form->get('nameEn')->getData();
            
            $descriptionPt = $form->get('descriptionPt')->getData();
            $descriptionEn = $form->get('descriptionEn')->getData();
            
            $entity->setCreatedBy($this->getUser());
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
                $this->get('translator')->trans('Se ha '.$textMsj.' correctamente La Actividad!')
            );
            return $this->redirectToRoute('actyvity_list');
        }
    }
}