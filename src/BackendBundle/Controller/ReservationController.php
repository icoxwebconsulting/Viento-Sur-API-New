<?php

namespace BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Date;
use VientoSur\App\AppBundle\Entity\Reservation;
use VientoSur\App\AppBundle\Entity\ReservationCancellation;

/**
 * @Route("/{_locale}/dashboard-reservation", requirements={"_locale": "es|en|pt"}, defaults={"_locale": "es"})
 */
class ReservationController extends Controller
{
    /**
     * @param $request
     * @Route("/", name="reservation_list")
     * @Method("GET")
     * @return response
     */
    public function indexAction(Request $request)
    {
        $querySearch = $request->get('query');

        $em = $this->getDoctrine()->getManager();
        $securityContext = $this->container->get('security.context');

        if(!empty($querySearch)){
            $finder = $this->container->get('fos_elastica.finder.app.reservation');
            $query = $finder->createPaginatorAdapter($querySearch);
        }else{
            if ($securityContext->isGranted('ROLE_ADMIN')) {
                $dql = "SELECT r
                    FROM VientoSurAppAppBundle:Reservation r
                    ORDER BY r.id ASC";
            }else{
                if ($securityContext->isGranted('ROLE_ACTIVITY')) {
                    return $this->redirectToRoute('actyvity_list');   
                }else{
                    $hotel = $em->getRepository('VientoSurAppAppBundle:Hotel')->findOneBy(array(
                        'created_by' => $this->getUser()->getId()
                    ));
                    if(!$hotel){
                     return $this->redirectToRoute('hotel_new');   
                    }
                    $dql = "SELECT r
                        FROM VientoSurAppAppBundle:Reservation r
                        WHERE r.hotelId = ".$hotel->getId()."
                        ORDER BY r.id ASC";
                }
            }    
        }

        $query = $em->createQuery($dql);

        $page = $request->query->getInt('page', 1);
        $paginator = $this->get('knp_paginator');
        $items_per_page = $this->getParameter('items_per_page');

        $pagination = $paginator->paginate($query, $page, $items_per_page);

        return $this->render(':admin/reservation:list.html.twig', array(
            'pagination' => $pagination
        ));
    }

    /**
     * @param $entity
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/show/{id}", name="reservation_show")
     * @Method("GET")
     * @return response
     */
    public function showAction(Reservation $entity)
    {
        $em = $this->getDoctrine()->getManager();
        $cancellationData = $em->getRepository("VientoSurAppAppBundle:ReservationCancellation")->findBy(array(
            'reservation' => $entity
        ));

        if ($entity->getOrigin() == 'despegar'){
            $guests = $em->getRepository('VientoSurAppAppBundle:Passengers')->findBy(array(
               'reservation' => $entity->getId()
            ));
            $reservationDetails = $this->container->get('despegar')->getReservationDetails(
                $entity->getReservationId(),
                array(
                    'email' => 'info@vientosur.net',
                    'language' => 'es',
                    'site' => 'AR'
                ), $this->getParameter('is_test')
            );

            $hotelDetails = $this->container->get('despegar')->getHotelsDetails(array(
                'ids' =>  $entity->getHotelId(),
                'language' => 'es',
                'options' => 'information',
                'resolve' => 'merge_info',
                'catalog_info' => 'true'
            ));
            $hotelDetails = (is_array($hotelDetails)) ? $hotelDetails[0] : null;

            $extraData = array(
                'hotelDetails' => $hotelDetails,
                'reservationDetails' => $reservationDetails,
                'guests' => $guests
            );

        }elseif ($entity->getOrigin() == 'vientosur'){
            $extraData = json_decode($entity->getExtraData());

            if ($extraData){
            $rooms = $em->getRepository('VientoSurAppAppBundle:Room')->findRoomsByIds($extraData->room);
            $this->get('session')->set('rooms', $rooms);
            }

        }

        return $this->render(':admin/reservation:show.html.twig', array(
            'entity' => $entity,
            'extraData' => $extraData,
            'cancellationData' => $cancellationData
        ));
    }

    /**
     * @Security("has_role('ROLE_HOTELIER')")
     * @Route("/test-reservation", name="test_reservation_new")
     * @Method("GET")
     * @return response
     */
    public function testNewAction()
    {
        $em = $this->getDoctrine()->getManager();

        $extra_data = array(
            'tdc_data' => array(
                'holder_name' => 'phils garcia',
                'dni' => 123456,
                'card_number' => 234564,
                'type_card' => 'mastercard',
                'security_code' => 132,
                'expiration_date' => '10/17'
            ),
            'travelers' => array(
                array(
                    "first_name" => "Phils",
                    "last_name" => "Garcia Quiroz",
                    "room_reference" => null,
                    "document_number" => "29742594"
                ),
                array(
                    "first_name" => "Phils2",
                    "last_name" => "Garcia Quiroz2",
                    "room_reference" => null,
                    "document_number" => "297425942"
                )
            ),
            'room' => array(
                array(
                    'room_id' => 20
                ),
                array(
                    'room_id' => 21
                )
            )
        );

        $entity = new Reservation();
        $entity->setHolderName('phils garcia');
        $entity->setCheckin(new \DateTime("now"));
        $entity->setCheckout(new \DateTime("now"));
        $entity->setPhoneNumber('+584125983290');
        $entity->setEmail('eivanphils@gmail.com');
        $entity->setCardType('CA');
        $entity->setReservationId('13456');
        $entity->setHotelId(23);
        $entity->setTotalPrice(1000);
        $entity->setComments('comentarios');
        $entity->setOrigin('vientosur');
        $entity->setExtraData(json_encode($extra_data));
        $entity->setRefundable(1);

        $em->persist($entity);
        $em->flush();

        return $this->redirectToRoute('reservation_list');
    }


    /**
     * cancel reservation if it belongs to despegar
     * @param $id
     * @param $request
     * @Route("/reservation/despegar/edit/{id}", options={"expose"=true}, name="cancel_reservation_despegar")
     * @Method("PATCH")
     * @return response
     */
    public function cancelReservationAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $despegar = $this->get('despegar');
        $cancel = $despegar->cancelReservation($id);
        $result = false;

        if ($cancel && isset($cancel['id'])) {
            $result = true;
            $reason = $request->get('reason');

            $reservation = $em->getRepository('VientoSurAppAppBundle:Reservation')->findOneBy(array(
                'reservationId' => $id,
                'origin' => 'despegar'
            ));

            $reservation->setStatus('cancelled');

            $reservationCancellation = new ReservationCancellation();
            $reservationCancellation->setDescription($reason);
            $reservationCancellation->setCreatedBy($this->getUser());
            $reservationCancellation->setCode($cancel['id']);
            $reservationCancellation->setReservation($reservation);

            $em->persist($reservationCancellation);
            $em->persist($reservation);
            $em->flush();
        }
        return new JsonResponse(
            array(
                "cancelled" => $result,
                "id" => (($cancel != null) ? $cancel['id'] : 0)
            )
        );
    }
    /**
     * cancel reservation if it belongs to vientosur
     * @param $id
     * @param $request
     * @Route("/reservation/vientosur/edit/{id}", options={"expose"=true}, name="cancel_reservation_vientosur")
     * @Method("PATCH")
     * @return response
     */
    public function cancelReservationVientosurAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $result = true;
        $reason = $request->get('reason');

        $reservation = $em->getRepository('VientoSurAppAppBundle:Reservation')->find($id);

        $reservation->setStatus('cancelled');

        $code = $this->getCodeHash();

        $entity = $em->getRepository('VientoSurAppAppBundle:ReservationCancellation')->findBy(array(
            'code' => $code
        ));

        if ($entity){
            $code = $this->getCodeHash();
        }

        $reservationCancellation = new ReservationCancellation();
        $reservationCancellation->setDescription($reason);
        $reservationCancellation->setCreatedBy($this->getUser());
        $reservationCancellation->setCode($code);
        $reservationCancellation->setReservation($reservation);

        $em->persist($reservationCancellation);
        $em->persist($reservation);
        $em->flush();

        return new JsonResponse(
            array(
                "cancelled" => $result,
                "id" => (($code != null) ? $code : 0)
            )
        );
    }

    /**
     * Make reservation code to
     * @return number
     */
    public function getCodeHash() {
        $date  = new \DateTime();
        return hexdec($date->format('d-m-y his'));
    }
}