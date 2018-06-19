<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use VientoSur\App\AppBundle\Entity\Hotel;
use VientoSur\App\AppBundle\Entity\Activity;
use VientoSur\App\AppBundle\Entity\Picture;
use BackendBundle\PictureType;
use VientoSur\App\AppBundle\Entity\Room;

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
        return $this->render(':admin/activity:activity_date_disable.html.twig',['activity'=>$activity]);
    }
}