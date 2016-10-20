<?php

namespace VientoSur\App\AppBundle\Services;


class Despegar
{
    private $serviceUrl = 'https://api.despegar.com/v3/';
    private $apiKey = '2864680fe4d74241aa613874fa20705f';
    private $xClient = '2864680fe4d74241aa613874fa20705f';

    public function __construct()
    {

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

        $url = $this->serviceUrl . 'administrative-divisions?country_id=20010&product=HOTELS&language=ES&limit=30';
        $states = $this->curlExec($url, $header, 'GET');
        return json_decode($states);
    }

    public function getHotelsAvailabilities($urlParams)
    {
        $url = $this->serviceUrl . "hotels/availabilities?" . http_build_query($urlParams);
        $header = [
            'X-ApiKey:' . $this->apiKey
        ];

        return $this->curlExec($url, $header, 'GET');
    }

    public function getHotelsAvailabilitiesDetail($idHotel, $restUrl, $urlParams)
    {
        $url = $this->serviceUrl . "hotels/availabilities/" . $idHotel . $restUrl . '&' . http_build_query($urlParams);
        $header = [
            'X-ApiKey:' . $this->apiKey
        ];

        return $this->curlExec($url, $header, 'GET');
    }

    public function getHotelsDetails($urlParams)
    {
        $url = $this->serviceUrl . "hotels?" . urldecode(http_build_query($urlParams));

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
}