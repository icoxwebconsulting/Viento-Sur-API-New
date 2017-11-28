<?php


namespace VientoSur\App\AppBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use VientoSur\App\AppBundle\Controller\HotelController;
use VientoSur\App\AppBundle\Entity\Passengers;
use VientoSur\App\AppBundle\Entity\Reservation;
use VientoSur\App\AppBundle\Entity\ResultLog;
use Knp\Bundle\SnappyBundle\Snappy\LoggableGenerator;


class Hotel
{
    private $despegar;
    private $emailService;
    private $em;
    private $logger;
    private $formHelper;
    private $isTest;
    private $session;
    private $knp_snappy;

    public function __construct(Despegar $dp, Email $email, EntityManager $entityManager, LoggerInterface $logger, $formHelper, $isTest, Session $session, $knp_snappy, TwigEngine $templating)
    {
        $this->despegar = $dp;
        $this->emailService = $email;
        $this->em = $entityManager;
        $this->logger = $logger;
        $this->knp_snappy = $knp_snappy;
        $this->formHelper = $formHelper;
        $this->isTest = $isTest;
        $this->session = $session;
        $this->templating = $templating;
    }

    public function bookingHotel($formBooking, $formPay, $bookingId, $hotelId, $priceDetail, $checkinDate, $checkoutDate, $lang, $email)
    {
        $vaultToken = $this->getVaultToken($formPay);

        $fillData = $this->formHelper->fillFormData($formBooking, $formPay);
        $selectedPack = $this->formHelper->getSelectedPack();
        $formIdBooking = $selectedPack['id'];

        $patchParams = [];
        $patchParams['payment_method_choice'] = $formPay['paymentMethod'];
        $patchParams['secure_token_information'] = array('secure_token' => $vaultToken);
        $patchParams['form'] = $fillData;

        $booking = $this->patchHotelsBooking($bookingId, $formIdBooking, $patchParams);
        if (isset($booking['code'])){
            return $booking;
        }
        if (isset($booking['status']) && $booking['status'] == 'NEW_CREDIT_CARD') {
            throw new \Exception('CREDIT_CARD');
        }

        $reservation = $this->saveReservation($hotelId, $booking, $priceDetail, $formPay, $checkinDate, $checkoutDate, $fillData['passengers']);

        $hotelDetails = $this->despegar->getHotelsDetails(array(
            'ids' => $hotelId,
            'language' => $lang,
            'options' => 'information,amenities,pictures,room_types(pictures,information,amenities)',
            'resolve' => 'merge_info',
            'catalog_info' => 'true'
        ));
        $hotelDetails = (is_array($hotelDetails)) ? $hotelDetails[0] : null;

        $reservationDetails = $this->despegar->getReservationDetails(
            $booking['reservation_id'],
            array(
                'email' => 'info@vientosur.net',
                'language' => $lang,
                'site' => 'AR'
            ), $this->isTest
        );

//        $this->sendBookingEmail($booking, $reservation, $hotelId, $lang, $email, $hotelDetails, $reservationDetails);

        return [
            'reservationDetails' => $reservationDetails,
            'hotelDetails' => $hotelDetails,
            'booking' => $booking,
            'reservation' => $reservation
        ];
    }

    private function getVaultToken($form)
    {
        $response = $this->despegar->dVault($form);
        if ($response && isset($response->secure_token)) {
            return $response->secure_token;
        } else {
            throw new \Exception('Algo no ha salido bien con el pago.');
        }
    }

    private function patchHotelsBooking($bookingId, $formIdBooking, $params)
    {
        $booking = $this->despegar->patchHotelsBookings($bookingId, $formIdBooking, $params);

        if (isset($booking['code'])) {
            array_push($booking,$params);
            $resultLog = new ResultLog();
            $resultLog->setData(json_encode($booking));
            $this->em->persist($resultLog);
            $this->em->flush();
//            throw new \Exception('No se ha podido realizar la reserva exitosamente.');
            return $booking;
        } else {
            return $booking;
        }
    }

    private function saveReservation($hotelId, $booking, $priceDetail, $formPay, $checkinDate, $checkoutDate, $passengers)
    {
        $cancellation_status = $this->session->get('room_cancellation_status');

        $reservation = new Reservation();
        $reservation->setHotelId($hotelId);
        $reservation->setReservationId($booking['reservation_id']);
        $reservation->setTotalPrice($priceDetail->total);
        $reservation->setCardType($formPay['card_code']);
        $reservation->setHolderName($formPay['owner_name']);
        $reservation->setPhoneNumber($formPay['country_code0'] . '-' . $formPay['area_code0'] . '-' . $formPay['number0']);
        $reservation->setEmail($formPay['email']);
        $reservation->setComments($formPay['comment']);
        $checkin = explode("/", $checkinDate);
        $checkout = explode("/", $checkoutDate);
        $reservation->setCheckin(new \DateTime($checkin[1] . '/' . $checkin[0] . '/' . $checkin[2]));
        $reservation->setCheckout(new \DateTime($checkout[1] . '/' . $checkout[0] . '/' . $checkout[2]));

        // checking if the reservation can be canceled
        if($cancellation_status == 'non_refundable') {
            $reservation->setRefundable(0);
        }else{
            $reservation->setRefundable(1);
        }

        $this->em->persist($reservation);

        foreach ($passengers as $key => $value) {
            $passenger = new Passengers();
            $passenger->setName($formPay['first_name' . $key]);
            $passenger->setLastName($formPay['last_name' . $key]);
            $passenger->setDocument($formPay['document_number' . $key]);
            $passenger->setReservation($reservation);
            $this->em->persist($passenger);
        }
        $this->em->flush();

        return $reservation;
    }

    public function sendBookingEmail($booking, $reservation, $hotelId, $lang, $email, $hotelDetails, $reservationDetails)
    {
        $internal = $this->em->getRepository('VientoSurAppAppBundle:Reservation')->find($reservation);
//        try {
//            if ($email) {
                $this->emailService->sendBookingEmail($email, array(
                    'hotelDetails' => $hotelDetails,
                    'reservationDetails' => $reservationDetails,
                    'reservationId' => base64_encode($reservation),
                    'detail' => $booking,
                    'hotelId' => $hotelId,
                    'internal' => $internal,
                    'pdf' => false
                ));
//            }
//        } catch (\Exception $e) {
//            $this->logger->error('Booking email error: ' . $email);
//        }
    }

    public function getCardsGroup($paymentMethods)
    {
        $cardsGroup = [];
        foreach ($paymentMethods as $pm) {
            $temp = [];
            if (isset($pm->card_ids)) {
                foreach ($pm->card_ids as $cardId) {
                    $cardParts = explode("-", $cardId);
                    $bank = $cardParts[2];
                    $temp[$bank][] = $cardId;
                }
                $cardsGroup[$pm->choice] = $temp;
            }
        }

        return $cardsGroup;
    }

    public function bookingHotelApi($formData, $formBookingUrl, $selectedPack, $hotelId, $priceDetail, $checkinDate, $checkoutDate, $lang, $email)
    {

        $vaultToken = $this->getVaultToken($formData);
//        $vaultToken = '12fb709eada0b38b6e2d01019bc9c561c115583d1782de250c8456285dd7e3501f084e7cf953008344e1b535bbe022547ccba91bcbfe8560c48fa05b933c2efd621ee5';

        $formIdBooking = $selectedPack['id'];
        $patchParams = [];
        $patchParams['payment_method_choice'] = $formData['paymentMethod'];
        $patchParams['secure_token_information'] = array('secure_token' => $vaultToken);

        $passengers = $formData['passengers'];

        for($i = 0; count($passengers) > $i; $i++){
            $formData['first_name' . $i] = $passengers[$i]['first_name'];
            $formData['last_name' . $i] = $passengers[$i]['last_name'];
            $formData['document_number' . $i] = $passengers[$i]['document_number'];
            $formData['room_reference' . $i] = '';
        }

        if(isset($formData['passengers'])){
            $patchParams['form']['passengers'] = $formData['passengers'];
        }

        if(isset($formData['owner_name'])){
            $creditCard['owner_name'] = $formData['owner_name'];
        }
        if(isset($formData['owner_documenttype'])){
            $creditCard['owner_document']['type'] = $formData['owner_documenttype'];
        }
        if(isset($formData['owner_documenttype'])){
            $creditCard['owner_document']['number'] = $formData['owner_documentnumber'];
        }
        if(isset($formData['owner_gender'])){
            $creditCard['owner_gender'] = $formData['owner_gender'];
        }
        if(isset($formData['card_type'])){
            $creditCard['card_type'] = $formData['card_type'];
        }

        if(isset($formData['invoice_name'])){
            if(isset($formData['tax_status'])){$invoice['tax_status'] = $formData['tax_status'];}
            if(isset($formData['fiscal_document'])){$invoice['fiscal_document'] = $formData['fiscal_document'];}
            if(isset($formData['billing_addressstreet'])){
                $invoice['billing_address'] = [
                    'street' => $formData['billing_addressstreet'],
                    'number' => $formData['billing_addressnumber'],
                    'floor' => $formData['billing_addressfloor'],
                    'department' => $formData['billing_addressdepartment'],
                    'state_id' => $formData['billing_addressstate_id'],
                    'city_id' => $formData['billing_addresscity_id'],
                    'postal_code' => $formData['billing_addresspostal_code']
                ];
            }
        }

        if(isset($formData['email'])){
            $contact = [
                'email' => $formData['email'],
                'phones' => [
                    [
                        'type' => $formData['type0'],
                        'number' => $formData['number0'],
                        'country_code' =>  str_replace("+", "", $formData['country_code0']),
                        'area_code' => $formData['area_code0']
                    ]
                ]
            ];
        }

        $additionalData = [
            'comment' => $formData['comment'],
            'should_use_nightly_prices' => $formData['should_use_nightly_prices']
        ];

        $vouchers = [$formData['vouchers']];


        $patchParams['form']['payment']['credit_card'] = $creditCard;
        if(isset($formData['invoice_name'])){$patchParams['form']['payment']['invoice'] = $invoice;}
        $patchParams['form']['payment']['overridden_information'] = ['shown_total_amount' => '', 'fees' => []];
        $patchParams['form']['contact'] = $contact;
        $patchParams['form']['additional_data'] = $additionalData;
        $patchParams['form']['vouchers'] = $vouchers;

        $booking = $this->patchHotelsBooking($formBookingUrl, $formIdBooking, $patchParams);

        if (isset($booking['code'])){
            return $booking;
        }
        if (isset($booking['status']) && $booking['status'] == 'NEW_CREDIT_CARD') {
            throw new \Exception('CREDIT_CARD');
        }

        $reservation = $this->saveReservation($hotelId, $booking, $priceDetail, $formData, $checkinDate, $checkoutDate, $formData['passengers']);

        $hotelDetails = $this->despegar->getHotelsDetails(array(
            'ids' => $hotelId,
            'language' => $lang,
            'options' => 'information,amenities,pictures,room_types(pictures,information,amenities)',
            'resolve' => 'merge_info',
            'catalog_info' => 'true'
        ));

        $hotelDetails = (is_array($hotelDetails)) ? $hotelDetails[0] : null;

        $reservationDetails = $this->despegar->getReservationDetails(
            $booking['reservation_id'],
            array(
                'email' => 'info@vientosur.net',
                'language' => $lang,
                'site' => 'AR'
            ), $this->isTest
        );

        return [
            'reservationDetails' => $reservationDetails,
            'hotelDetails' => $hotelDetails,
            'booking' => $booking,
            'reservation' => $reservation
        ];
    }


    public function savePdfToAttach($detail, $hotelId, $email, $reservationId)
    {

        $reservation = $this->em->getRepository('VientoSurAppAppBundle:Reservation')->findOneById($reservationId);

        $hotelDetails = $this->despegar->getHotelsDetails(array(
            'ids' => $hotelId,
            'language' => 'es',
            'options' => 'information,amenities,pictures,room_types(pictures,information,amenities)',
            'resolve' => 'merge_info',
            'catalog_info' => 'true'
        ));

//        echo "<pre>".print_r($reservation->getReservationId(), true)."</pre>";die();
        $reservationDetails = $this->despegar->getReservationDetails($detail['reservation_id'], array(
            'email' => 'info@vientosur.net',
            'language' => 'es',
            'site' => 'AR'
        ), $this->isTest);

        $logoUrl = 'https://www.vientosur.net/bundles/vientosurappapp/images/vientosur-logo-color.png';


        $this->knp_snappy->generateFromHtml(
            $this->templating->render(
                '@VientoSurAppApp/Pdf/booking.html.twig', array(
                'hotelDetails' => $hotelDetails[0],
                'reservationDetails' => $reservationDetails,
                'detail' => $detail,
                'hotelId' => $hotelId,
                'internal' => $reservation,
                'logoUrl' => $logoUrl,
                'pdf' => true
            )),
            $reservation->getId().'.pdf'
        );

        return new Response('work');
    }

}