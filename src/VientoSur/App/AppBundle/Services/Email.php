<?php

namespace VientoSur\App\AppBundle\Services;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Templating\EngineInterface;

class Email
{
    private $mailer;
    private $templating;
    private $container;
    private $session;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, Container $container, Session $session)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->container = $container;
        $this->session = $session;
    }

    public function sendCommentsEmail($html, $email= 'web@vientosur.net')
    {
        $message = \Swift_Message::newInstance(null)
            ->setSubject("Consulta web Viento Sur")
            ->setFrom($email, $email)
            ->setTo('web@vientosur.net','web@vientosur.net')
            ->setBody(
                $html,
                'text/html'
            );

        $this->mailer->send($message);
    }

    public function sendBookingEmail($email, $data)
    {
        $fs = new Filesystem();
        $internalId = $this->session->get('reservationInternalId');
        $filePath = $this->container->getParameter('kernel.root_dir') . '/../web/'.$internalId.'.pdf';
        $fs->rename($filePath, 'voucher-vs.pdf');
        $filenName = $this->container->getParameter('kernel.root_dir') . '/../web/voucher-vs.pdf';
        $message = \Swift_Message::newInstance()
            ->setSubject('Confirmación de reserva')
            ->setFrom('info@vientosur.net','VientoSur.net')
            ->setTo([$email])
            ->setBody(
                $this->templating->render(
                    '@VientoSurAppApp/layoutEmailHotelPdf.html.twig',
                    $data
                ),
                'text/html'
            )->attach(\Swift_Attachment::fromPath($filenName))
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'Emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;
        $this->mailer->send($message);
    }

    public function sendCancellationEmail($email, $data)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Cancelación de reserva')
//            ->setFrom('info@vientosur.net','info@vientosur.net')
            ->setFrom('info@vientosur.net','VientoSur.net')
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'VientoSurAppAppBundle:Email:HotelCancellation.html.twig',
                    $data
                ),
                'text/html'
            )/*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'Emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;
        $this->mailer->send($message);
    }

    public function sendBookingFlightEmail($email, $data)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Confirmación de reserva')
//            ->setFrom('info@vientosur.net','info@vientosur.net')
            ->setFrom('info@vientosur.net','VientoSur.net')
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    '@VientoSurAppApp/layoutEmailFligthPdf.html.twig',
                    $data
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}