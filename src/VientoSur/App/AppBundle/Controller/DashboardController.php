<?php

namespace VientoSur\App\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("dashboard")
 */
class DashboardController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/", name="dashboard")
     * @Method("GET")
     * @return array
     */
    public function indexAction()
    {
//        $em = $this->getDoctrine()->getManager();
//
//        $users = $em->getRepository('AppBundle:User')->findBy(array('reported' => 1),
//            array('modified' => 'DESC'),
//            10);
//        $news = $em->getRepository('AppBundle:News')->findBy(array('reported' => 1),
//            array('modified' => 'DESC'),
//            10);
//        $reports = $em->getRepository('AppBundle:Report')->findAllUserReportedByOtherUsers();
//
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/home/", name="dashboard_user")
     * @return array
     */
    public function homeAction()
    {
//        $user = $this->getUser();
//        $em = $this->getDoctrine()->getManager();
//        $medias = $em->getRepository('AppBundle:Media')->findBy(array('created_by' => $user->getId()),
//            array('modified' => 'DESC'),
//            10);
//        $news = $em->getRepository('AppBundle:News')->findBy(array('created_by' => $user->getId()),
//            array('modified' => 'DESC'),
//            10);
//
//        return $this->render('admin/home.html.twig', array(
//            'medias' => $medias,
//            'news' => $news,
//        ));
        return new Response('fsdfss');
    }
}