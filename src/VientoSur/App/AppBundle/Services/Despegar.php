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

    public function __construct($guzzleVault, $guzzleDespegar, $apiKey, $apiKeyTest, $serviceVersion, $serviceUrl, $vaultUrl, $vaultUrlTest, $isTest)
    {
        $this->isTest = $isTest;
        $this->clientVault = $guzzleVault;
        $this->clientDespegar = $guzzleDespegar;
        $this->serviceUrl = $serviceUrl;
        $this->serviceVersion = $serviceVersion;
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

    public function getHotelsAvailabilitiesDetail($idHotel, $restUrl, $urlParams)
    {
        $url = $this->getServiceUrl() . "hotels/availabilities/" . $idHotel . $restUrl . '&' . http_build_query($urlParams);
        $header = [
            'X-ApiKey:' . $this->apiKey
        ];

        return $this->curlExec($url, $header, 'GET');
    }

    public function getHotelsDetails($urlParams)
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

        return json_decode($results, true);
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

//        echo 'PATCH: ' . $url . '<br/>';
//        echo 'Header: <pre>';
//        print_r(json_encode($header));
//        echo '</pre><br/>';
//
//        echo 'BODY: <pre>';
//        print_r($params);
//        echo '</pre><br/>';


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

    public function dVault($tokenizeKey, $params)
    {
        try {
            $response = $this->dVaultValidation($tokenizeKey, $params);

            if ($response) {
                $header = [
                    'Content-Type: application/json',
                    'X-Tokenize-Key: ' . $tokenizeKey,
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
        $url = $this->getServiceUrl() . 'autocomplete?' . urldecode(http_build_query($urlParams));
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
        return $this->curlExec($url, $header, 'GET');
    }
}