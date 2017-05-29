<?php

namespace VientoSur\App\AppBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use VientoSur\App\AppBundle\Entity\FlightPassengers;
use VientoSur\App\AppBundle\Entity\FlightReservation;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

class Flights
{
    private $despegar;
    private $em;
    private $formNewPay;
    private $fieldNames;
    private $passengersForm;
    private $paymentsForm;
    private $contact_infoForm;
    private $agentCode;
    private $emailService;
    private $logger;
    private $resources = [
        'LOCAL_DOCUMENT' => 'DNI',
        'FINAL' => 'Consumidor Final',
        'INSCR' => 'Responsable Inscripto',
        'EXENT' => 'Exento',
        'MONOTRIBUTO' => 'Monotributo',
        'CELULAR' => 'Celular',
        'HOME' => 'Casa',
        'WORK' => 'Trabajo',
        'FAX' => 'Fax',
        'OTHER' => 'Otro',
        'MALE' => 'Masculino',
        'FEMALE' => 'Femenino',
        'AR' => 'Argentina'
    ];

    public function __construct(Despegar $dp, Email $email, EntityManager $entityManager, $agentCode, LoggerInterface $logger)
    {
        $this->despegar = $dp;
        $this->emailService = $email;
        $this->em = $entityManager;
        $this->agentCode = $agentCode;
        $this->logger = $logger;
    }

    public function getCheckoutData($urlParams)
    {
        $results = $this->despegar->getFlightCheckoutHints($urlParams);
        return $results;
    }

    public function getItineraryDetail($id)
    {
        $results = $this->despegar->getFlightItineraryDetail($id);
        return $results;
    }

    public function initForm($booking, $formNewPay)
    {
        $this->formNewPay = $formNewPay;

        $this->fieldNames = array(
            'payments' => [],
            'contact_info' => [],
            'passengers' => [],
            'legals' => []
        );

        $this->passengersForm = $this->formNewPay->create('passengers', 'form', array('inherit_data' => true, 'allow_extra_fields' => true));
        $this->paymentsForm = $this->formNewPay->create('payments', 'form', array('inherit_data' => true, 'allow_extra_fields' => true));
        $this->contact_infoForm = $this->formNewPay->create('contact_info', 'form', array('inherit_data' => true, 'allow_extra_fields' => true));

        foreach ($booking as $key => $item) {
            if ($key == 'passengers') {
                $this->processPassengers($item);
            } else if ($key == 'payments') {
                $this->processPayments($item);
            } else if ($key == 'contact_info') {
                $this->processContactInfo($item);
            }
        }

        //Agrego campo para la sesión usada en las métricas
        $this->formNewPay->add(
            $this->paymentsForm->add(
                'session_id',
                'hidden',
                array(
                    'required' => true,
                )
            )
        );

        return $this->formNewPay;
    }

    private function processPassengers($passengers)
    {
        $adultQty = $passengers['adults'][0]['metadata']['quantity'];
        $cantChildren = $passengers['children'][0]['metadata']['quantity'];
        $childrenQty = 0;
        $cantInfant = $passengers['infants'][0]['metadata']['quantity'];
        $infantQty = 0;
        for ($i = 1; $i <= $passengers['metadata']['quantity']; $i++) {
            if ($i <= $adultQty) {
                $passenger = $passengers['adults'][0];
                $type = 'adults';
                $count = $i;
            }else{
                if ($childrenQty != $cantChildren){
                    $passenger = $passengers['children'][0];
                    $type = 'children';
                    $childrenQty++;
                    $count = $childrenQty;
                }elseif ($infantQty != $cantInfant){
                        $passenger = $passengers['infants'][0];
                        $type = 'infants';
                        $infantQty++;
                        $count = $infantQty;
                }
            }

            foreach ($passenger as $key => $item) {
                if ($key != 'metadata' && $key != 'validations') {
                    switch ($key) {
                        case 'identification':
                            foreach ($item as $key2 => $detail) {
                                if ($key2 != 'metadata' && $key2 != 'validations') {
                                    $this->processFormElement('passengers', $type, $key2, $count, $detail);
                                }
                            }
                            break;
                        default: //first_name, last_name, birthdate, nationality, gender
                            $this->processFormElement('passengers', $type, $key, $count, $item);
                            break;
                    }
                }
            }
        }
    }

    private function processPayments($payments)
    {
        foreach ($payments['credit_cards'] as $count => $elements) {
            foreach ($elements as $key => $item) {
                if ($key != 'metadata') {
                    switch ($key) {
                        case 'invoice':
                            foreach ($item as $key2 => $detail) {
                                if ($key2 != 'address') {
                                    $this->processFormElement('payments', $key, $key2, $count, $detail);
                                } else {
                                    foreach ($detail as $key3 => $detail2) {
                                        $this->processFormElement('payments', $key, $key3, $count, $detail2);
                                    }
                                }
                            }
                            break;
                        case 'card_holder_identification':
                        case 'card':
                            foreach ($item as $key2 => $detail) {
                                $this->processFormElement('payments', $key, $key2, $count, $detail);
                            }
                            break;
                        case 'card_code':
                            $this->processFormElement('payments', $key, null, $count, $item);
                            break;
                        default:// installments
                            $this->processFormElement('payments', $key, '', $count, $item);
                            break;
                    }
                }
            }
        }
    }

    private function processContactInfo($contactInfo)
    {
        foreach ($contactInfo as $key => $elements) {
            if ($key != 'phones') {
                $this->processFormElement('contact_info', $key, '', '', $elements);
            } else {
                $count = 0;
                foreach ($elements as $element) {
                    foreach ($element as $key2 => $detail) {
                        if ($key2 != 'metadata') {
                            $this->processFormElement('contact_info', 'phones', $key2, $count, $detail);
                        }
                    }
                    $count++;
                }
            }
        }
    }

    private function processFormElement($groupKey, $subcategory, $key, $count, $element)
    {
        switch ($element['data_type']) {
            case 'text':
            case 'card_type':
            case 'security_card_code':
            case 'card_number':
            case 'card_code':
            case 'city_id':
            case 'local_fiscal_id':
            case 'phone_country_code':
            case 'numeric':
                $name = $subcategory . (($key) ? '-' . $key . $count : $count);
                $this->formNewPay->add(
                    $this->{$groupKey . 'Form'}->add(
                        $name,
                        'text',
                        array(
                            'required' => true,
                        )
                    )
                );
                break;
            case 'email':
                $this->formNewPay->add(
                    $this->{$groupKey . 'Form'}->add(
                        $subcategory . '-' . $key . $count,
                        'email',
                        array(
                            'required' => true,
                        )
                    )
                );
                break;
            case 'month_year':
                $this->formNewPay->add(
                    $this->{$groupKey . 'Form'}->add(
                        $subcategory . '-' . $key . $count,
                        'date',
                        array(
                            'format' => 'MM-yyyy  d',
                            'years' => range(date('Y'), date('Y') + 12),
                            'days' => array(1),
                            'empty_value' => array('year' => 'Año', 'month' => 'Mes', 'day' => false)
                        )
                    )
                );
                break;
            case 'date':
                $this->formNewPay->add(
                    $this->{$groupKey . 'Form'}->add(
                        $subcategory . '-' . $key . $count,
                        'date', [
                            'widget' => 'single_text',
                            'format' => 'dd-MM-yyyy'
                        ]
                    )
                );
                break;
            case 'document_type':
            case 'country_code':
            case 'gender_type':
            case 'fiscal_status':
            case 'phone_type':
                $optionField = $this->generateChoiceField($element['validations'][0]['allowed_values']);
                $this->formNewPay->add(
                    $this->{$groupKey . 'Form'}->add(
                        $subcategory . '-' . $key . $count,
                        'choice',
                        array(
                            'choices' => $optionField,
                            // *this line is important*
                            'choices_as_values' => false,
                        )
                    )
                );
                break;
        }
    }

    private function generateChoiceField($optionsArray)
    {
        $optionField = [];
        foreach ($optionsArray as $item) {
            if (array_key_exists($item, $this->resources)) {
                $optionField[$item] = $this->resources[$item];
            }
        }

        return $optionField;
    }

    public function processdVault($formNewPaySend)
    {
        try {
            $params = [
                'brand_code' => $formNewPaySend['card_code0'],
                'number' => str_replace(' ', '', $formNewPaySend['card-number0']),
                'expiration_month' => $formNewPaySend['card-expiration0']->format('m'),
                'expiration_year' => $formNewPaySend['card-expiration0']->format('Y'),
                'security_code' => $formNewPaySend['card-security_code0'],
                'bank' => $formNewPaySend['card_code0'],
                'seconds_to_live' => '600',
                'holder_name' => $formNewPaySend['card-card_holder_name0'],
            ];
            $key = $formNewPaySend['payments']['credit_cards'][0]['card']['token']['metadata']['public_key'];
            $response = $this->despegar->dVaultValidation($key, $params, true);

            if ($response) {
                return $this->despegar->vaultPbdyy($key, $params, true);
            }
        } catch (Exception $exception) {
            return false;
        }
        return false;
    }

    private function fillFormData($dvault, $formData, $booking, $clientIp)
    {
        $providers = [
            [
                "provider" => "threat_metrix",
                "parameters" => [
                    "offshore" => false,
                    "session_id" => $formData['session_id']
                ]
            ]
        ];

        $toCheckout = [
            'risk_analysis' => [
                'additional_evaluators' => $providers,
                'sem_info' => 'string',
                'client_ip' => $clientIp
            ],
            'booking_information' => [
                'payments' => [
                    'credit_cards' => []
                ],
                'contact_info' => [],
                'passengers' => [
                    'adults' => [],
                    'children' => [],
                    'infants' => []
                ],
                'agent_code' => $this->agentCode
            ]
        ];

        //proceso credit cards en payments
        for ($j = 0; $j < count($booking['payments']['credit_cards']); $j++) {
            $card = [
                "card_code" => $formData['card_code' . $j],
                "type" => "CREDIT_CARD",
                "card_type" => "CREDIT",
                "installments" => $formData['installments' . $j],
                "contact_full_name" => $formData['card-card_holder_name' . $j],
                'card_holder_identification' => [
                    'type' => $formData['card_holder_identification-type' . $j],
                    'number' => $formData['card_holder_identification-number' . $j]
                ],
                'invoice' => [
                    'address' => [
                        'state' => 'BUE',
                        'city_id' => $formData['invoice-city_id' . $j],
                        'postal_code' => $formData['invoice-city_id' . $j],
                        'street' => $formData['invoice-street' . $j],
                        'number' => $formData['invoice-number' . $j]
                    ],

                    'fiscal_id' => $formData['invoice-fiscal_id' . $j],
                    'fiscal_status' => $formData['invoice-fiscal_status' . $j],
                    'business_name' => $formData['invoice-business_name' . $j]
                ],
                'card' => [
                    'token' => 'secure://' . $dvault,
                    'type' => 'CREDIT'
                ]
            ];

            $toCheckout['booking_information']['payments']['credit_cards'][] = $card;
        }

        //proceso de contact_info
        $contactInfo = [];
        foreach ($booking['contact_info'] as $key => $data) {
            if ($key != 'metadata') {
                if ($key == 'email') {
                    $contactInfo['email'] = $formData['email-'];
                }
                for ($j = 0; $j < count($booking['contact_info']['phones']); $j++) {
                    if ($key == 'phones') {
                        for ($i = 0; $i < count($data); $i++) {
                            $phones = [
                                "number" => $formData['phones-number' . $j],
                                "country_code" => str_replace('+', '', $formData['phones-country_code' . $j]),
                                "area_code" => $formData['phones-area_code' . $j],
                                "type" => $formData['phones-type' . $j]
                            ];
                            $contactInfo['phones'][] = $phones;
                        }
                    }
                }
                $contactInfo['accept_offers'] = false;//TODO: poner campo en formulario
            }
        }
        $toCheckout['booking_information']['contact_info'] = $contactInfo;

        //proceso de passengers
        $j = 0;
        $adultCount = $booking['passengers']['adults'][0]['metadata']['quantity'];
        $adultQty = 0;
        $childrenQty = 0;
        $infantQty = 0;
        $auxInfant = 0;
        $childCount = (isset($booking['passengers']['children'][0]['metadata']['quantity']) ? $booking['passengers']['children'][0]['metadata']['quantity'] : 0);
        $infantCount = (isset($booking['passengers']['infants'][0]['metadata']['quantity']) ? $booking['passengers']['infants'][0]['metadata']['quantity'] : 0);
        for ($i = 1; $i <= $booking['passengers']['metadata']['quantity']; $i++) {
            if($adultCount != 0 and $adultQty < $adultCount){
                $type = 'ADULT';
                $typeField = 'adults';
                $j = ($j == $adultCount) ? 1 : $j + 1;
                $adultQty++;
            }elseif ($childCount != 0 and $childrenQty < $childCount){
                $type = 'CHILDREN';
                $typeField = 'children';
                $j = ($j == $adultCount) ? 1 : $j + 1;
                $childrenQty++;
            }elseif($infantCount != 0 and $infantQty <= $infantCount){
                $type = 'INFANT';
                $typeField = 'infants';
                $sum = $childCount+ $adultCount;
                if($i > $sum and $j < $infantCount){
                    $j++;
                }else{
                    $j = 1;
                }
                $auxInfant++;
                $infantQty++;
            }
            $passenger = [];

            if (isset($booking['passengers']['adults'][0]['identification'])) {
                $passenger["identification"] = [
                    "number" => $formData[$typeField . '-number' . $j],
                    "issuing_country" => "AR",
                    "type" => $formData[$typeField . '-type' . $j]
                ];
            }

            if (isset($booking['passengers']['adults'][0]['type'])) {
                $passenger["type"] = $type;
            }

            if (isset($booking['passengers']['adults'][0]['first_name'])) {
                $passenger["first_name"] = $formData[$typeField . '-first_name' . $j];
            }

            if (isset($booking['passengers']['adults'][0]['last_name'])) {
                $passenger["last_name"] = $formData[$typeField . '-last_name' . $j];
            }

            if (isset($booking['passengers']['adults'][0]['birthdate'])) {
                $passenger["birthdate"] = $formData[$typeField . '-birthdate' . $j]->format('Y-m-d');
            }

            if (isset($booking['passengers']['adults'][0]['gender'])) {
                $passenger["gender"] = $formData[$typeField . '-gender' . $j];
            }

            if (isset($booking['passengers']['adults'][0]['nationality'])) {
                $passenger["nationality"] = "AR";
            }

            $toCheckout['booking_information']['passengers'][$typeField][] = $passenger;
        }

        return $toCheckout;
    }

    private function postFlightBooking($fillData, $urlParams)
    {
        $bookingInfo = $this->despegar->postFlightBookings($fillData, $urlParams);

        if (isset($bookingInfo['status']) && $bookingInfo['status'] !== 'SUCCESS') {
            throw new \Exception($bookingInfo['status']);
        }
    }

    private function getVaultToken($formData)
    {
        $response = $this->processdVault($formData);
        if ($response && isset($response->secure_token)) {
            return $response->secure_token;
        } else {
            throw new \Exception('Algo no ha salido bien con el pago.');
        }
    }

    public function processReservation($formData, $booking, $clientIp, $params, $itineraryDetail, $origin, $destination)
    {
        $dvault = $this->getVaultToken($formData);

        $fillData = $this->fillFormData($dvault, $formData, $booking, $clientIp);

        $urlParams = [
            'itinerary_id' => $params['itinerary_id'],
            'language' => 'es',
            'country' => 'AR',
            'product_type' => 'FLIGHT'
        ];

        $bookingInfo = $this->despegar->postFlightBookings($fillData, $urlParams);

        if (isset($bookingInfo['status']) && $bookingInfo['status'] == 'NEW_CREDIT_CARD') {
            throw new \Exception('CREDIT_CARD');
        }

        if ($bookingInfo && $bookingInfo['status'] == 'SUCCESS') {
            $reservation = new FlightReservation();
            $reservation->setItineraryId($params['itinerary_id']);
            $reservation->setReservationId($bookingInfo['id']);
            $reservation->setTotalPrice($itineraryDetail['price_detail']['total']);
            $reservation->setCardType($fillData['booking_information']['payments']['credit_cards'][0]['card_code']);
            $reservation->setHolderName($fillData['booking_information']['payments']['credit_cards'][0]['contact_full_name']);
            $phone = $fillData['booking_information']['contact_info']['phones'][0];
            $reservation->setPhoneNumber($phone['country_code'] . ' ' . $phone['area_code'] . ' ' . $phone['number']);
            $reservation->setEmail($fillData['booking_information']['contact_info']['email']);
            $reservation->setOrigin($origin['id']);
            $reservation->setDestination($destination['id']);
            $reservation->setCreated(new \DateTime());
            $this->em->persist($reservation);

            foreach ($fillData['booking_information']['passengers'] as $key => $value) {
                foreach ($value as $key2 => $data) {
                    $passenger = new FlightPassengers();
                    $passenger->setName($data['first_name']);
                    $passenger->setLastName($data['last_name']);
                    $passenger->setDocument('');
                    $passenger->setBirthdate(new \DateTime($data['birthdate']));
                    $passenger->setGender($data['gender']);
                    $passenger->setFlightReservation($reservation);
                    $this->em->persist($passenger);
                }
            }
            $this->em->flush();

            //envío de correo
//            try {
                $email = $fillData['booking_information']['contact_info']['email'];
//                if ($email) {
                    $this->emailService->sendBookingFlightEmail($email, array(
                        'pdf' => false,
                        'reservation' => $reservation,
                        'itineraryDetail' => $itineraryDetail
                    ));
//                }
//            } catch (\Exception $e) {
//                $this->logger->error('Booking Flight email error: ' . $e->getMessage());
//            }

            return $reservation->getId();
        } else {
            return false;
        }
    }

    public function getPaymentMethods($itineraryDetail)
    {
        $paymentMethods = [];
        foreach ($itineraryDetail['payment_methods'] as $element) {
            $value = $element['installments'];
            if (isset($element['bank_code'])) {
                $paymentMethods[$value][$element['bank_code']][] = $element;
            } else {
                $paymentMethods[$value]['generic'][] = $element;
            }
        }
        ksort($paymentMethods, SORT_NUMERIC);
        return $paymentMethods;
    }
}