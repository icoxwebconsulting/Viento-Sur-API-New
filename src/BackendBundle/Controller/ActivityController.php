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
use VientoSur\App\AppBundle\Entity\ActivityAgency;
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
        $securityContext = $this->container->get('security.context');
        $user = $this->getUser();
        
        if($securityContext->isGranted('ROLE_ADMIN')){
            $dql = "SELECT a
                FROM VientoSurAppAppBundle:Activity a 
                ORDER BY a.id ASC";
        }else{
            $dql = "SELECT a
                FROM VientoSurAppAppBundle:Activity a 
                WHERE a.created_by =".$user->getId()."
                ORDER BY a.id ASC";
        }
        
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
     * @Security("has_role('ROLE_ACTIVITY')")
     * @Route("/new", name="actyvity_new")
     * @return response
     */
    public function newAction(Request $request)
    {
        $entity = new Activity();
        $securityContext = $this->container->get('security.context');
        $em = $this->getDoctrine()->getManager();
        $rol = 0;
        $activity_agency = new ActivityAgency();
        
        if (false === $securityContext->isGranted('ROLE_ADMIN')) {
            $rol = 1;
            $activity_agency = $em->getRepository('VientoSurAppAppBundle:ActivityAgency')->findOneBy(array(
                'user' => $this->getUser()
            ));
        }
        
        $form = $this->createForm(new ActivityType(), $entity, array('rol'=>$rol));
        
        $this->formAction($form, $request, $em, $entity, 'agregado', 'new', $rol);
        
        return $this->render(':admin/activity:form.html.twig', array(
            'form' => $form->createView(),
            'rol'  => $rol,
            'activity_agency' => $activity_agency
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
        $securityContext = $this->container->get('security.context');
        $em = $this->getDoctrine()->getManager();
        $rol = 0;
        $activity_agency = new ActivityAgency();
        
        if (false === $securityContext->isGranted('ROLE_ADMIN')) {
            $rol = 1;
            $activity_agency = $em->getRepository('VientoSurAppAppBundle:ActivityAgency')->findOneBy(array(
                'user' => $this->getUser()
            ));
        }
        
        $form = $this->createForm(new ActivityType(), $entity, [
            "method" => $request->getMethod(),
            "rol"=>$rol
        ]);
        
        $repository = $em->getRepository('Gedmo\Translatable\Entity\Translation');
        $translations = $repository->findTranslations($entity);
        
        $this->formAction($form, $request, $em, $entity, 'editado', 'edit', $securityContext);
        
        return $this->render(':admin/activity:form.html.twig', array(
            'form' => $form->createView(),
            'entity' => $entity,
            'translations' => $translations,
            'rol'  => $rol,
            'activity_agency' => $activity_agency
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
    protected function formAction($form, $request, $em, $entity, $textMsj, $action, $rol){
        
        if($form->handleRequest($request)->isValid())
        {
            
            $namePt = $form->get('namePt')->getData();
            $nameEn = $form->get('nameEn')->getData();
            
            $descriptionPt = $form->get('descriptionPt')->getData();
            $descriptionEn = $form->get('descriptionEn')->getData();
            
            if($rol===1){
                $activity_agency = $request->get('activity_agency_id');
                $activity_agency_object = $em->getRepository('VientoSurAppAppBundle:ActivityAgency')->findOneById($activity_agency);
                $entity->setActivityAgency($activity_agency_object);                
            }
            
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

            switch ($textMsj){
                case 'agregado':
                    $message = 'admin.messages.add_activity';
                    break;
                case 'editado':
                    $message = 'admin.messages.edit_activity';
                    break;
            }

            $this->addFlash(
                'success',
                $this->get('translator')->trans($message)
            );
            return $this->redirectToRoute('actyvity_list');
        }
    }
}