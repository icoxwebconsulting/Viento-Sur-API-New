<?php

namespace VientoSur\App\AppBundle\Services;

use Symfony\Component\Config\Definition\Exception\Exception;
use GuzzleHttp\Client;

class Despegar
{
    private $serviceUrl = 'https://api.despegar.com';
    private $serviceVersion = 'v3';
    private $apiKey = '2864680fe4d74241aa613874fa20705f';
    private $xClient = '2864680fe4d74241aa613874fa20705f';

    public function __construct()
    {

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

        return $results;
    }

    public function postHotelsBookings($params)
    {
        $url = $this->getServiceUrl() . "hotels/bookings?example=true";
        $header = [
            'Content-Type: application/json',
            'X-Client: ' . $this->apiKey,
            'X-ApiKey: ' . $this->apiKey
        ];

        return $this->curlExec($url, $header, 'POST', json_encode($params));
    }

    public function getHotelsBookingsNextStepUrl($bookingId)
    {
        return $this->serviceUrl . $bookingId . "?example=true";
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
        $url = $this->serviceUrl . $bookingId . '/' . $form_id_booking . '?example=true';
        $header = [
            'Content-Type: application/json',
            'X-Client: ' . $this->apiKey,
            'X-ApiKey: ' . $this->apiKey
        ];

        echo 'PATCH: '. $url.'<br/>';
        echo 'Header: <pre>';
        print_r(json_encode($header));
        echo '</pre><br/>';

        echo 'BODY: <pre>';
        print_r($params);
        echo '</pre><br/>';


        return $this->curlExec($url, $header, 'PATCH', json_encode($params));
    }

    public function dVaultValidation($tokenizeKey, $params)
    {
        //TODO: URL de prueba
        $url = 'https://www.despegar.com/sandbox/vault/pbdyy/validation';
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
        curl_exec($cSession);
        $httpCode = curl_getinfo($cSession, CURLINFO_HTTP_CODE);
        curl_close($cSession);

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
                //TODO: URL de prueba
                $url_test = 'https://www.despegar.com/sandbox/vault/pbdyy';
                $header = [
                    'Content-Type: application/json',
                    'X-Tokenize-Key: ' . $tokenizeKey,
                    'X-Client: ' . $this->apiKey,
                    'X-ApiKey: ' . $this->apiKey
                ];

                //step1
                $params = json_encode($params);
                $cSession = curl_init();
                curl_setopt($cSession, CURLOPT_URL, $url_test);
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
}