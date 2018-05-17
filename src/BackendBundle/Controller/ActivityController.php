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
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/", name="actyvity_list")
     * @Method("GET")
     * @return array
     */
    public function indexAction()
    {
        return $this->render(':admin/activity:list.html.twig');
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