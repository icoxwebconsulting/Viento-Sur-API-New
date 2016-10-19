<?php

namespace VientoSur\App\AppBundle\Services;


class Despegar
{
    private $serviceUrl = 'https://api.despegar.com/v3/';

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
        return json_decode($results);
    }

    public function getStates()
    {
        $header = [
            'Content-Type: application/json',
            'X-Client: 2864680fe4d74241aa613874fa20705f',
            'X-ApiKey: 2864680fe4d74241aa613874fa20705f',
        ];

        $url = $this->serviceUrl . 'administrative-divisions?country_id=20010&product=HOTELS&language=ES&limit=30';
        $states = $this->curlExec($url, $header, 'GET');
        return $states;
    }

    public function getHotelsAvailabilities($fromCalendarHotel, $toCalendarHotel, $destination, $distribucion, $offset)
    {
        $url = $this->serviceUrl . "hotels/availabilities?country_code=AR&checkin_date=" . $fromCalendarHotel . "&checkout_date=" . $toCalendarHotel . "&destination=" . $destination . "&distribution=" . $distribucion . "&language=es&radius=200&accepts=partial&currency=USD&sorting=best_selling_descending&classify_roompacks_by=none&roompack_choices=recommended&offset=".$offset."&limit=10";


    }
}