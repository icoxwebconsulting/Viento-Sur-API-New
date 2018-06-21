<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use VientoSur\App\AppBundle\Entity\Activity;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use VientoSur\App\AppBundle\Repository\DatesDisableActivityRepository;
use VientoSur\App\AppBundle\Entity\DatesDisableActivity;


/**
 * @Route("/{_locale}/dashboard-date-disable-activity", requirements={"_locale": "es|en|pt"}, defaults={"_locale": "es"})
 */
class DatesDisableActivityController extends Controller{
    /**
     * @param Activity $activity
     * @Security("has_role('ROLE_ACTIVITY')")
     * @Route("/activity-date-disable/{id}", name="activity_date_disable")
     * @return response
     */
    public function activityDateDisableAcion(Request $request, Activity $activity)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository("VientoSurAppAppBundle:DatesDisableActivity")->findOneBy(array('activity' => $activity));
        
        if(!$entity){
            $entity = new DatesDisableActivity();
        }
        
        if($request->isMethod('POST')){
            $date = $request->get('date');
            
            $entity->setDisableAt($date);
            $entity->setActivity($activity);
            $entity->setCreatedBy($this->getUser());
            $em->persist($entity);
            $em->flush();
            
            $this->addFlash(
                'success',
                $this->get('translator')->trans('Se ha registrado correctamente!')
            );
            return $this->redirectToRoute('activity_date_disable',['id'=>$activity->getId()]);
        }
            
        return $this->render(':admin/activity:activity_date_disable.html.twig',
                ['activity'=>$activity,
                 'entity'=>$entity]
        );
    }
}