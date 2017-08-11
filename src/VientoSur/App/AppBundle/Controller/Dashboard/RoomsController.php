<?php

namespace VientoSur\App\AppBundle\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use VientoSur\App\AppBundle\Entity\Hotel;

/**
 * @Route("dashboard-rooms")
 */
class RoomsController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/", name="rooms_list")
     * @Method("GET")
     * @return response
     */
    public function indexAction()
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository("VientoSurAppAppBundle:Room")->findBy(array('created_by' => $user->getId()));

        return $this->render(':admin/room:list.html.twig', array(
            'entities' => $entities
        ));
    }
}