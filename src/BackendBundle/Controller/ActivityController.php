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

        $form = $this->createForm(new ActivityType(), $entity, [
            "method" => $request->getMethod(),
            "rol"=>$rol
        ]);
        $id = $this->formAction($form, $request, $em, $entity, 'agregado', 'new', $securityContext);
        $entities = $em->getRepository("VientoSurAppAppBundle:Picture")->findBy(array('activity' => $entity));

            if($id == 0){
                $action=0;
            }else{
                $action=1;
            }
        return $this->render(':admin/activity:form.html.twig', array(
            'form' => $form->createView(),
            'rol'  => $rol,
            'id' => $id,
            'action' => $action,
            'entities' => $entities,
            'activity_agency' => $activity_agency,
            'entity'=>$entity
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
        $entities = $em->getRepository("VientoSurAppAppBundle:Picture")->findBy(array('activity' => $entity));

        $this->formAction($form, $request, $em, $entity, 'editado', 'edit', $securityContext);
        $id=$entity->getId();

        return $this->render(':admin/activity:form.html.twig', array(
            'form' => $form->createView(),
            'entity' => $entity,
            'translations' => $translations,
            'rol'  => $rol,
            'id'  => $id,
            'action' => 2,
            'entities' => $entities,
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
        $managerEntity = $this->getDoctrine()->getManager();

        if($form->handleRequest($request)->isValid())
        {
            //var_dump($form);exit;
            $namePt = $form->get('namePt')->getData();
            $nameEn = $form->get('nameEn')->getData();
            
            $descriptionPt = $form->get('descriptionPt')->getData();
            $descriptionEn = $form->get('descriptionEn')->getData();
            
            $am = $form->get('am')->getData();
            $pm = $form->get('pm')->getData();
            $all_day = $form->get('all_day')->getData();
            $several_day = $form->get('several_day')->getData();
            
            
            if($am == 1 || $pm == 1){
                $entity->setAllDay(0);
                $entity->setSeveralDay(0);
                $entity->setAm(1);
                $entity->setPm(1);
            }
            if($all_day == 1){
                $entity->setAm(0);
                $entity->setPm(0);
                $entity->setSeveralDay(0);
                $entity->setAllDay(1);
            }
            if($several_day == 1){
                $entity->setAm(0);
                $entity->setPm(0);
                $entity->setAllDay(0);
                $entity->setSeveralDay(1);
            }
            
           
            $activity_agency = $request->get('activity_agency_id');
            $activity_agency_object = $em->getRepository('VientoSurAppAppBundle:ActivityAgency')->findOneById($activity_agency);
            $entity->setActivityAgency($activity_agency_object);                
           

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

            $id=$entity->getId();


            $dql = 'SELECT a.id, ag.name as agency, a.name, a.address_destination, a.latitude_destination, a.longitude_destination
                FROM VientoSurAppAppBundle:Activity as a, VientoSurAppAppBundle:ActivityAgency as ag
                WHERE a.activity_agency = ag.id
                AND a.id <>'.$entity->getId();
            $query = $managerEntity->createQuery($dql);
            $resultQuery = $query->getResult();

            $requestActivity = $request->get('appbundle_activity');

            $dataActivity = [
                    'agency' => $entity->getActivityAgency()->getName(),
                    'activity' => $requestActivity['name'],
                    'address_destination' => $requestActivity['address_destination'],
                    'latitude_destination' => $requestActivity['latitude_destination'],
                    'longitude_destination' => $requestActivity['longitude_destination']
                ];

            $resultPoints = $this->orderPoints($dataActivity, $resultQuery, 5);

            $emailParams = ['activities' => $resultPoints, 'dataActivity' => $dataActivity];

            if(count($resultPoints) > 0){
                $template = 'nearbyActivities';
                $messages = $this->container->get('mail_manager');
                $messages->sendEmail($template, $emailParams, 'activity');
            }

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

            //Modificacion Breiddy



            return $id;
            //return $this->redirectToRoute('activity_picture_list', array('id' => 2));
            //return $this->redirect($this->generateUrl('actyvity_list'));
            
            //$this->redirect('activity_picture_list', array('id' => 1));

            //return $this->generateUrl('activity_picture_list', array('id' => '1'));
        }
        return $id=0;
    }


    /**
     * @param array $pointInit Some argument description
     * @param array  $pointEnd
     * @return string result
     */
    function distance($pointInit, $pointEnd) {
        $latInit = (float) $pointInit['latitude_destination'];
        $longInit = (float) $pointInit['longitude_destination'];

        $latEnd = (float) $pointEnd['latitude_destination'];
        $longEnd = (float) $pointEnd['longitude_destination'];

        $theta = $longInit - $longEnd;
        $dist = sin(deg2rad($latInit)) * sin(deg2rad($latEnd)) +  cos(deg2rad($latInit)) * cos(deg2rad($latEnd)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        $results = round($miles * 1.609344, 2);

        return $results;

    }


    /**
     * @param $pointInit array initial location parameters
     * @param $listPoints array list list of nearby points
     * @param $limit limit of distances in km
     * @return array
     */
    function orderPoints($pointInit, $listPoints, $limit ){
        $arrDistance = [];
        for ($i=0; $i < count($listPoints) ; $i++) {
            $distance = $this->distance($pointInit,$listPoints[$i]);


            if($distance <= $limit){
                array_push($arrDistance, [
                    "address_destination" => $listPoints[$i]['address_destination'],
                    "agency" => $listPoints[$i]['agency'],
                    "name" => $listPoints[$i]['name'],
                    "distance" => $distance
                ]);
            }

        }
        array_multisort(array_column($arrDistance, 'distance'), SORT_ASC, $arrDistance);

        return $arrDistance;
    }

}