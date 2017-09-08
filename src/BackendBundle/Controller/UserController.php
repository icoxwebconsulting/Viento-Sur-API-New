<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use VientoSur\App\AppBundle\Entity\User;
use BackendBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * @Route("/profile")
 */
class UserController extends Controller
{
    /**
     * @param $request
     * @param $user
     * @Security("has_role('ROLE_USER')")
     * @Route("/edit/{id}", name="user_edit")
     * @return array
     */
    public function updateAction(Request $request, User $user)
    {
        $request->setMethod('PATCH');
        $form = $this->createForm(new UserType(), $user, ["method" => $request->getMethod()]);
        $em = $this->getDoctrine()->getManager();

        if ($form->handleRequest($request)->isValid())
        {
            $em->persist($user);
            $em->flush();
            $this->addFlash(
                'success',
                $this->get('translator')->trans('admin.user_update')
            );
            return $this->redirectToRoute('fos_user_profile_show');
        }
        return $this->render('@FOSUser/Profile/edit.html.twig', array(
            'form' => $form->createView(),
            'entity' => $user,
        ));
    }
    
    /**
     * Generate the redirection url when editing is completed.
     *
     * @param \FOS\UserBundle\Model\UserInterface $user
     *
     * @return string
     */
    protected function getRedirectionUrl(UserInterface $user)
    {
        return $this->container->get('router')->generate('fos_user_profile_show');
    }

    /**
     * @param string $action
     * @param string $value
     */
    protected function setFlash($action, $value)
    {
        $this->container->get('session')->getFlashBag()->set($action, $value);
    }
}