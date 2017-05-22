<?php


namespace VientoSur\App\AppBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use VientoSur\App\AppBundle\Entity\Passengers;
use VientoSur\App\AppBundle\Entity\Reservation;
use VientoSur\App\AppBundle\Entity\ResultLog;


class Hotel
{
    private $despegar;
    private $emailService;
    private $em;
    private $logger;
    private $formHelper;
    private $isTest;

    public function __construct(Despegar $dp, Email $email, EntityManager $entityManager, LoggerInterface $logger, $formHelper, $isTest)
    {
        $this->despegar = $dp;
        $this->emailService = $email;
        $this->em = $entityManager;
        $this->logger = $logger;
        $this->formHelper = $formHelper;
        $this->isTest = $isTest;
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

        $this->sendBookingEmail($booking, $reservation, $hotelId, $lang, $email, $hotelDetails, $reservationDetails);

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
        $reservation = new Reservation();
        $reservation->setHotelId($hotelId);
        $reservation->setReservationId($booking['reservation_id']);
        $reservation->setTotalPrice($priceDetail['total']);
        $reservation->setCardType($formPay['card_code']);
        $reservation->setHolderName($formPay['owner_name']);
        $reservation->setPhoneNumber($formPay['country_code0'] . '-' . $formPay['area_code0'] . '-' . $formPay['number0']);
        $reservation->setEmail($formPay['email']);
        $reservation->setComments($formPay['comment']);
        $checkin = explode("/", $checkinDate);
        $checkout = explode("/", $checkoutDate);
        $reservation->setCheckin(new \DateTime($checkin[1] . '/' . $checkin[0] . '/' . $checkin[2]));
        $reservation->setCheckout(new \DateTime($checkout[1] . '/' . $checkout[0] . '/' . $checkout[2]));
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

    private function sendBookingEmail($booking, $reservation, $hotelId, $lang, $email, $hotelDetails, $reservationDetails)
    {
//        try {
//            if ($email) {
                $this->emailService->sendBookingEmail($email, array(
                    'hotelDetails' => $hotelDetails,
                    'reservationDetails' => $reservationDetails,
                    'reservationId' => base64_encode($reservation->getId()),
                    'detail' => $booking,
                    'hotelId' => $hotelId,
                    'internal' => $reservation,
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
}