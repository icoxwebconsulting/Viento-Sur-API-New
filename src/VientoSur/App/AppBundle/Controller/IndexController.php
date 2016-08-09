<?php
/**
 * Created by Yolanda Gonzalez.
 * User: yolandag0302@gmail.com
 * Date: 3/8/16
 * Time: 1:57 PM
 */

namespace VientoSur\App\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template("VientoSurAppAppBundle:Index:index.html.twig")
     */
    public function indexAction()
    {
        return array();
    }
}
