<?php

namespace VientoSur\App\AppBundle\Services;


use Symfony\Component\DependencyInjection\ContainerInterface;

class MailManager
{
    protected $mailer;
    protected $twig;
    protected $container;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, ContainerInterface $container)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->container = $container;
    }

    /**
     * Send email
     *
     * @param   string   $template      email template
     * @param   mixed    $parameters    custom params for template
     *
     * @return  boolean                 send status
     */
    public function sendEmail($template, $parameters, $action = null)
    {
        $from = $this->container->getParameter('mailer_user');
        if($action != null){
            $to = $from;
        }else{
            $to = $parameters['email'];
        }


        $template = $this->twig->loadTemplate('@VientoSurAppApp/Email/' . $template . '.html.twig');

        $subject  = $template->renderBlock('subject', $parameters);
        $bodyHtml = $template->renderBlock('body_html', $parameters);
        $bodyText = $template->renderBlock('body_text', $parameters);

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($bodyHtml, 'text/html')
            ->addPart($bodyText, 'text/plain')
        ;
        $response = $this->mailer->send($message);

        return $response;
    }
}