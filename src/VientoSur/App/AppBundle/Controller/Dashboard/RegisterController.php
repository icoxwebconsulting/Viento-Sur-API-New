<?php

namespace VientoSur\App\AppBundle\Controller\Dashboard;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Session\Session;
use VientoSur\App\AppBundle\Entity\User;

/**
 * @Route("register")
 */
class RegisterController extends BaseController
{
    /**
     * Register user
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/", name="user_new")
     */
    public function registerAction()
    {
        $form = $this->container->get('fos_user.registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');

        $process = $formHandler->process($confirmationEnabled);
        if ($process) {
            $user = $form->getData();

            $userManager = $this->container->get('fos_user.user_manager');
            $user->setRole(array('ROLE_ADMIN'));
            $userManager->updateUser($user);

            if ($confirmationEnabled) {
                $route = 'fos_user_security_login';
                $this->setFlash(
                    'success',
//                    $this->get('translator')->trans('admin.confirm_account')
                    'sd'
                );
            } else {
                $this->_authenticateAccount($user);
                $route = 'fos_user_registration_confirmed';
            }

            $this->setFlash('fos_user_success', 'registration.flash.user_created');
            $url = $this->container->get('router')->generate($route);

            return new RedirectResponse($url);
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function _authenticateAccount($user)
    {
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);
        $this->get('session')->set('_security_main', serialize($token));
    }

    /**
     * @param $token
     * @Route("/confirm/{token}", name="registration_confirm")
     * @return response
     */
    public function confirmAction($token)
    {
        $user = $this->container->get('fos_user.user_manager')->findUserByConfirmationToken($token);
        $router = $this->container->get('router');
        if (null === $user) {
            return new RedirectResponse($router->generate('dashboard'));
        }

        $user->setConfirmationToken(null);
        $user->setEnabled(true);
        $user->setLastLogin(new \DateTime());

        $this->container->get('fos_user.user_manager')->updateUser($user);

        $response = new RedirectResponse($this->container->get('router')->generate('fos_user_registration_confirmed'));
        $this->authenticateUser($user, $response);

        return new RedirectResponse($this->container->get('router')->generate('dashboard'));
    }
}