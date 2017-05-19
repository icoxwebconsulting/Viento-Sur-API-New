<?php

namespace VientoSur\App\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class TestController
 * @package VientoSur\App\AppBundle\Controller
 * @route("/test")
 */
class TestController extends Controller
{
    /**
     * @return Response
     * @route("/", name="test_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('@VientoSurAppApp/test.html.twig');
    }

    /**
     * @route("/sendCommentsEmailAction/{email}", name="test_send_comments_email")
     */
    public function sendCommentsEmailAction($email)
    {
//        $email = 'eivanphils@gmail.com';
        $html = '<p>correo de sendCommentsEmailAction';
        $this->get('email.service')->sendCommentsEmail($html, $email);
        return $this->redirectToRoute('test_index');
    }

    /**
     * @route("/sendBookingEmail/{email}", name="test_sendBookingEmail")
     */
    public function sendBookingEmailAction($email)
    {
//        $email = 'eivanphils@gmail.com';
        $data = array(
            'pdf' => 'ok',
            'internal' => array(
                                'holderName' => 'holdername',
                                'email' => 'email',
                                'id' => 1,
                                'totalPrice' => 1234
            ),
            'hotelDetails' => array(
                'main_picture' => array(
                    'url' => 'mainpicture url'
                ),
                'name' => 'name hoteldetails',
                'location' => array(
                    'address' => 'address hotel details'
                ),
                'information' => array(
                    'es' => 'es'
                )
            ),
            'reservationDetails' => array(
                'night_count' => 'ningth count',
                'rooms' => array(
                    '0' => array(
                        'id' => 0,
                        'adults' => 1,
                        'description' => array(
                            'es' => 'es decirption'
                        ),
                        'children' => 1
                    ),
                    '1' => array(
                        'id' => 1,
                        'adults' => 2,
                        'description' => array(
                            'es' => 'es decirption'
                        ),
                        'children' => 1
                    ),
                    '2' => array(
                        'id' => 2,
                        'adults' => 3,
                        'description' => array(
                            'es' => 'es decirption'
                        ),
                        'children' => 1
                    )
                ),
                'checkin_date' => '07/06/2017',
                'checkout_date' => '07/08/2017',
                'id' => 1
            )
        );
        $this->get('email.service')->sendBookingEmail($email, $data);
        return $this->redirectToRoute('test_index');
    }
    /**
     * @route("/sendCancellationEmail/{email}", name="test_sendCancellationEmail")
     */
    public function sendCancellationEmailAction($email)
    {
//        $email = 'eivanphils@gmail.com';
        $data = array(
            'pdf' => 'ok',
            'idCancellation' => 12354,
            'internal' => array(
                                'holderName' => 'holdername',
                                'email' => 'email',
                                'id' => 1,
                                'totalPrice' => 1234
            ),
            'hotelDetails' => array(
                'main_picture' => array(
                    'url' => 'mainpicture url'
                ),
                'name' => 'name hoteldetails',
                'location' => array(
                    'address' => 'address hotel details'
                ),
                'information' => array(
                    'es' => 'es'
                )
            ),
            'reservationDetails' => array(
                'night_count' => 'ningth count',
                'rooms' => array(
                    '0' => array(
                        'id' => 0,
                        'adults' => 1,
                        'description' => array(
                            'es' => 'es decirption'
                        ),
                        'children' => 1
                    ),
                    '1' => array(
                        'id' => 1,
                        'adults' => 2,
                        'description' => array(
                            'es' => 'es decirption'
                        ),
                        'children' => 1
                    ),
                    '2' => array(
                        'id' => 2,
                        'adults' => 3,
                        'description' => array(
                            'es' => 'es decirption'
                        ),
                        'children' => 1
                    )
                ),
                'checkin_date' => '07/06/2017',
                'checkout_date' => '07/08/2017',
                'id' => 1
            )
        );
        $this->get('email.service')->sendCancellationEmail($email, $data);
        return $this->redirectToRoute('test_index');
    }
}