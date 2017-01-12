<?php

namespace VientoSur\App\AppBundle\Services;

use Symfony\Component\Config\Definition\Exception\Exception;
use GuzzleHttp\Client;

class Despegar
{
    private $serviceUrl;
    private $serviceVersion;
    private $apiKey;
    private $xClient;
    private $clientVault;
    private $clientDespegar;
    private $isTest;
    private $urlVault;
    private $apiKeyTest;
    private $apiKeyProd;

    public function __construct($guzzleVault, $guzzleDespegar, $apiKey, $apiKeyTest, $serviceVersion, $serviceUrl, $vaultUrl, $vaultUrlTest, $isTest)
    {
        $this->isTest = $isTest;
        $this->clientVault = $guzzleVault;
        $this->clientDespegar = $guzzleDespegar;
        $this->serviceUrl = $serviceUrl;
        $this->serviceVersion = $serviceVersion;
        $this->apiKeyTest = $apiKeyTest;
        $this->apiKeyProd = $apiKey;
        if ($isTest) {
            $this->urlVault = $vaultUrlTest;
            $this->apiKey = $apiKeyTest;
            $this->xClient = $apiKeyTest;
        } else {
            $this->urlVault = $vaultUrl;
            $this->apiKey = $apiKey;
            $this->xClient = $apiKey;
        }
    }

    public function getServiceUrl()
    {
        return $this->serviceUrl . '/' . $this->serviceVersion . '/';
    }

    private function curlExec($url, $header, $method, $params = null)
    {
        $cSession = curl_init();
        curl_setopt($cSession, CURLOPT_URL, $url);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_CUSTOMREQUEST, $method);
        if ($params) {
            curl_setopt($cSession, CURLOPT_POSTFIELDS, $params);
        }
        curl_setopt($cSession, CURLOPT_HTTPHEADER, $header);
        curl_setopt($cSession, CURLOPT_HEADER, false);
        //step3
        $results = curl_exec($cSession);
        //step4
        curl_close($cSession);
        // do anything you want with your response
        $results = json_decode($results, true);
        if (isset($results['code']) && $results['code'] == 403) {
            return false;
        }
        return $results;
    }

    public function getStates()
    {
        $header = [
            'Content-Type: application/json',
            'X-Client: ' . $this->xClient,
            'X-ApiKey: ' . $this->apiKey,
        ];

        $url = $this->getServiceUrl() . 'administrative-divisions?country_id=20010&product=HOTELS&language=ES&limit=30';
        $states = $this->curlExec($url, $header, 'GET');
        return json_decode($states);
    }

    public function getHotelsAvailabilities($urlParams)
    {
        $url = $this->getServiceUrl() . "hotels/availabilities?" . http_build_query($urlParams);
        $header = [
            'X-ApiKey:' . $this->apiKey
        ];
//        $data = $this->clientVault->request('GET',"v3/hotels/availabilities", [
//            'query' => http_build_query($urlParams),
//            'headers' => [
//                'X-ApiKey:'     => $this->apiKey
//            ]
//        ]);

        return $this->curlExec($url, $header, 'GET');

    }

    public function getHotelsAvailabilitiesDetail($idHotel, $urlParams)
    {
        $url = $this->getServiceUrl() . "hotels/availabilities/" . $idHotel . '?' . http_build_query($urlParams);
        $header = [
            'X-ApiKey:' . $this->apiKey
        ];

        return $this->curlExec($url, $header, 'GET');
    }

    public function getHotelsDetails($urlParams, $orderById = false)
    {
        $url = $this->getServiceUrl() . "hotels?" . urldecode(http_build_query($urlParams));

        $cSession = curl_init();
        curl_setopt($cSession, CURLOPT_URL, $url);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_HTTPHEADER, array('X-ApiKey:' . $this->apiKey));
        curl_setopt($cSession, CURLOPT_HEADER, false);
        curl_setopt($cSession, CURLOPT_ACCEPT_ENCODING, "");
        $results = curl_exec($cSession);
        curl_close($cSession);
        $results = json_decode($results, true);

        if (!$orderById) {
            return $results;
        } else {
            $hDetail = [];
            foreach ($results as $detail) {
                $hDetail[$detail['id']] = $detail;
            }
            return $hDetail;
        }
    }

    public function postHotelsBookings($params)
    {
        $url = $this->getServiceUrl() . "hotels/bookings" . (($this->isTest) ? '?example=true' : '');
        $header = [
            'Content-Type: application/json',
            'X-Client: ' . $this->apiKey,
            'X-ApiKey: ' . $this->apiKey
        ];

        return $this->curlExec($url, $header, 'POST', json_encode($params));
    }

    public function getHotelsBookingsNextStepUrl($bookingId)
    {
        return $this->serviceUrl . $bookingId . (($this->isTest) ? '?example=true' : '');
    }

    public function hotelsBookingsNextStep($bookingId)
    {
        $header = [
            'X-ApiKey:' . $this->apiKey
        ];

        return $this->curlExec($this->getHotelsBookingsNextStepUrl($bookingId), $header, 'GET');
    }

    public function patchHotelsBookings($bookingId, $form_id_booking, $params)
    {
        $url = $this->serviceUrl . $bookingId . '/' . $form_id_booking . (($this->isTest) ? '?example=true' : '');
        $header = [
            'Content-Type: application/json',
            'X-Client: ' . $this->apiKey,
            'X-ApiKey: ' . $this->apiKey
        ];

        return $this->curlExec($url, $header, 'PATCH', json_encode($params));
    }

    public function dVaultValidation($tokenizeKey, $params)
    {
        $url = $this->urlVault . '/vault/pbdyy/validation';
        $header = [
            'Content-Type: application/json',
            'X-Tokenize-Key: ' . $tokenizeKey,
            'X-Client: ' . $this->apiKey,
            'X-ApiKey: ' . $this->apiKey
        ];

        $cSession = curl_init();
        curl_setopt($cSession, CURLOPT_URL, $url);
        curl_setopt($cSession, CURLOPT_POST, true);
        curl_setopt($cSession, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_HTTPHEADER, $header);
        curl_setopt($cSession, CURLOPT_HEADER, false);
        $response = curl_exec($cSession);
        $httpCode = curl_getinfo($cSession, CURLINFO_HTTP_CODE);
        curl_close($cSession);
//        echo 'Response0: <pre>';
//        print_r($response);
//        echo '</pre><br/>';
        // do anything you want with your response
        if ($httpCode == 204) {
            return true;
        } else {
            return false;
        }
    }

    public function dVault($formNewPaySend)
    {
        try {
            $params = [
                'brand_code' => $formNewPaySend['card_code'],
                'number' => str_replace(' ', '', $formNewPaySend['number']),
                'expiration_month' => $formNewPaySend['expiration']->format('m'),
                'expiration_year' => $formNewPaySend['expiration']->format('Y'),
                'security_code' => $formNewPaySend['security_code'],
                'bank' => $formNewPaySend['bank_code'],
                'seconds_to_live' => '600',
                'holder_name' => $formNewPaySend['owner_name'],
            ];

            $response = $this->dVaultValidation($formNewPaySend['tokenize_key'], $params);

            if ($response) {
                $header = [
                    'Content-Type: application/json',
                    'X-Tokenize-Key: ' . $formNewPaySend['tokenize_key'],
                    'X-Client: ' . $this->apiKey,
                    'X-ApiKey: ' . $this->apiKey
                ];

                //step1
                $params = json_encode($params);
                $cSession = curl_init();
                curl_setopt($cSession, CURLOPT_URL, $this->urlVault . '/vault/pbdyy');
                curl_setopt($cSession, CURLOPT_POST, true);
                curl_setopt($cSession, CURLOPT_POSTFIELDS, $params);
                curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cSession, CURLOPT_HTTPHEADER, $header);
                curl_setopt($cSession, CURLOPT_HEADER, false);
                //step3
                $results = curl_exec($cSession);
                //step4
                curl_close($cSession);

                return json_decode($results);
            }
        } catch (Exception $exception) {
            return false;
        }
        return false;
    }

    public function autocomplete($urlParams)
    {
        $url = $this->getServiceUrl() . 'autocomplete?' . http_build_query($urlParams);
        $header = [
            'X-ApiKey: ' . $this->apiKey
        ];
        return $this->curlExec($url, $header, 'GET');
    }

    public function getFlightItineraries($urlParams)
    {
        $url = $this->getServiceUrl() . 'flights/itineraries?' . http_build_query($urlParams);

        $header = [
            'X-ApiKey: ' . $this->apiKey
        ];
        if ($this->isTest) {
            $header[] = 'XDESP-TEST:true';
        }

        return $this->curlExec($url, $header, 'GET');
    }

    public function getFlightAirlines($urlParams)
    {
        $url = $this->getServiceUrl() . 'airlines?' . http_build_query($urlParams);

        $header = [
            'X-ApiKey: ' . $this->apiKey
        ];
        if ($this->isTest) {
            $header[] = 'XDESP-TEST:true';
        }

        return $this->curlExec($url, $header, 'GET');
    }

    public function getFlightAirlineDetail($code, $urlParams)
    {
        $url = $this->getServiceUrl() . 'airlines/' . $code . '?' . http_build_query($urlParams);

        $header = [
            'X-ApiKey: ' . $this->apiKey
        ];
        if ($this->isTest) {
            $header[] = 'XDESP-TEST:true';
        }

        return $this->curlExec($url, $header, 'GET');
    }

    public function getReservationDetails($id, $urlParams, $isTest)
    {
        if ($isTest) {
            return json_decode('{
  "id": "11141355",
  "penalty": {
    "total_to_refund": 0,
    "status": "NOT_ALLOW_BY_NOT_REFUNDABLE",
    "penalty_customer_amount": 0,
    "cancellation_type": "Total",
    "known_penalty_cost": false,
    "penalty_provider_amount": 0,
    "cancellation_policies": []
  },
  "billing": {
    "receipts": [],
    "country": "AR",
    "pay_at_destination": false
  },
  "status": "VOUCHER_SENT",
  "rooms": [
    {
      "id": "8956",
      "adults": 1,
      "children": 1,
      "descriptions": {},
      "children_ages": [
        4
      ],
      "holder_first_name": "room holder",
      "holder_lastname": "room holder",
      "holder_identification_number": "123"
    }
  ],
  "checkin_date": "2017-11-14T12:00:00+0000",
  "checkout_date": "2017-11-22T12:00:00+0000",
  "hotel": {
    "id": "202719"
  },
  "night_count": 7,
  "customer_information": {
    "mail": "testfenixH-desa@2despegar.com",
    "phones": [
      {
        "type": "HOME",
        "number": "52(55)1234-5678"
      }
    ],
    "alternative_emails": [],
    "first_name": "Test",
    "last_name": "Booking"
  },
  "commission_collect_in_advance": false,
  "client_amount_to_collect": 217,
  "amount_to_collect_on_arrival": 0,
  "site_currency": {
    "code": "USD",
    "rate": 1
  },
  "contract_currency": {
    "code": "BRL",
    "rate": 0.21
  }
}', true);
        }

        $url = $this->getServiceUrl() . 'hotels/reservations/' . $id . '?' . http_build_query($urlParams);
        $header = [
            'X-ApiKey: ' . $this->apiKey
        ];

        $cSession = curl_init();
        curl_setopt($cSession, CURLOPT_URL, $url);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_HTTPHEADER, $header);
        curl_setopt($cSession, CURLOPT_HEADER, false);
        curl_setopt($cSession, CURLOPT_ACCEPT_ENCODING, "");
        $results = curl_exec($cSession);
        curl_close($cSession);
        $results = json_decode($results, true);
        return $results;
    }

    public function getCityInformation($id, $urlParams)
    {
        $url = $this->getServiceUrl() . 'cities/' . $id . '?' . http_build_query($urlParams);
        $header = [
            'X-ApiKey: ' . $this->apiKey
        ];
        return $this->curlExec($url, $header, 'GET');
    }

    public function getCardDetails($id)
    {
        $url = $this->getServiceUrl() . 'cards/' . $id;
        $header = [
            'X-ApiKey: ' . $this->apiKey
        ];
        return $this->curlExec($url, $header, 'GET');
    }

    public function cancelReservation($id)
    {
        $url = $this->getServiceUrl() . 'hotels/reservations/' . $id . '/operations' . (($this->isTest) ? '?test=true' : '');
        $params = [
            "email" => "info@vientosur.net",
            "language" => "es",
            "operation_id" => "CANCELLATION_REQUEST",
            "site" => "AR"
        ];
        $header = [
            'Content-Type: application/json',
            'Accept: application/json',
            'X-ApiKey: ' . $this->apiKey
        ];

        $cSession = curl_init();
        curl_setopt($cSession, CURLOPT_URL, $url);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_HTTPHEADER, $header);
        curl_setopt($cSession, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($cSession, CURLOPT_HEADER, false);
        curl_setopt($cSession, CURLOPT_ACCEPT_ENCODING, "");
        $results = curl_exec($cSession);
        curl_close($cSession);
        $results = json_decode($results, true);
        return $results;
    }
}