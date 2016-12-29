<?php

namespace VientoSur\App\AppBundle\Services;

use Symfony\Component\Templating\EngineInterface;

class Email
{
    private $mailer;
    private $templating;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function sendCommentsEmail($html)
    {
        $message = \Swift_Message::newInstance(null)
            ->setSubject("Consulta web Viento Sur")
            ->setFrom("not-reply@vientosur.net")
            ->setTo("info@vientosur.net")
            ->setBody(
                $html,
                'text/html'
            );

        $this->get('mailer')->send($message);
    }

    public function sendBookingEmail($email, $data)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Confirmación de reserva')
            ->setFrom('no-responder@vientosur.net')
            ->setTo($email)
            ->setBody(
                $this->templating->render(
                    'VientoSurAppAppBundle:Email:booking.html.twig',
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

    public function sendCancellationEmail($email, $data)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Cancelación de reserva')
            ->setFrom('no-responder@vientosur.net')
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
}