<?php

namespace VientoSur\App\AppBundle\Services;

use Symfony\Component\Config\Definition\Exception\Exception;

class BookingHelper
{


    public function __construct()
    {

    }

    public function getSearchText($checkin_date, $checkout_date, $distribution, $lang = 'es')
    {
        $checkin_date = new \DateTime($checkin_date);
        $checkout_date = new \DateTime($checkout_date);

        $trad = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $transText = [
            'entry' => 'Entrada',
            'exit' => 'Salida',
            'adults' => 'Adulto(s)',
            'childs' => 'Menor(es)'
        ];

        if ($lang == 'en') {
            $trad = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $transText = [
                'entry' => 'Entry',
                'exit' => 'Exit',
                'adults' => 'Adult(s)',
                'childs' => 'Child(s)'
            ];
        } else if ($lang == 'pt') {
            $trad = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $transText = [
                'entry' => 'Entrada',
                'exit' => 'Saída',
                'adults' => 'Adulto(s)',
                'childs' => 'Criança(s)'
            ];
        }

        $months = [
            'Jan' => $trad[0],
            'Feb' => $trad[1],
            'Mar' => $trad[2],
            'Apr' => $trad[3],
            'May' => $trad[4],
            'Jun' => $trad[5],
            'Jul' => $trad[6],
            'Aug' => $trad[7],
            'Sep' => $trad[8],
            'Oct' => $trad[9],
            'Nov' => $trad[10],
            'Dec' => $trad[11]
        ];

        $str = '<p><span>' . $transText['entry'] . ':</span> ' . $checkin_date->format('d') . ' ' . $months[$checkin_date->format('M')] . '</p>';
        $str .= '<p><span>' . $transText['exit'] . ':</span> ' . $checkout_date->format('d') . ' ' . $months[$checkout_date->format('M')].'</p>';
        $travellers = $this->processDistribution($distribution);
        $str .= '<p>' . $travellers['adults'] . ' ' . $transText['adults'] . '</p>';
        $str .= '<p>' .(($travellers['childrenCount'] > 0) ? $travellers['childrenCount'].' '. $transText['childs']  : ''). '</p>';
        return $str;
    }

    public function processDistribution($distribution)
    {
        $result = [
            'rooms' => 0,
            'adults' => 0,
            'childs' => 0
        ];

        if (strpos($distribution, '!')) {
            $temp = explode('!', $distribution);

            $result['rooms'] = count($temp);
        } else {
            $temp[0] = $distribution;
            $result['rooms'] = 1;
        }
        $childCount = 0;
        foreach ($temp as $item) {
            if (strpos($item, '-')) {
                $temp2 = explode('-', $item);
                if(isset($temp2[1])){
                    $childCount++;
                }
                if(isset($temp2[2])){
                    $childCount++;
                }if(isset($temp2[3])){
                    $childCount++;
                }if(isset($temp2[4])){
                    $childCount++;
                }if(isset($temp2[5])){
                    $childCount++;
                }if(isset($temp2[6])){
                    $childCount++;
                }
                if(isset($temp2[7])){
                    $childCount++;
                }
                foreach ($temp2 as $key => $item2) {
                    if ($key == 0) {
                        $result['adults'] += $item2;
                    } else {
                        $result['childs'] += $item2;
                    }
                }
            } else {
                $result['adults'] += $item;
            }
        }
        $result['childrenCount'] = $childCount;

        return $result;
    }

    public function getTripProfiles()
    {
        return [
            'businessTrip' => 'Viaje de negocios',
            'castle' => 'Castillo',
            'cheap' => 'Económico',
            'design' => 'Diseño',
            'family' => 'Familiar',
            'gourmet' => 'Gastronómico',
            'luxury' => 'Viaje de lujo',
            'nature' => 'Naturaleza',
            'other' => 'Otros',
            'relax' => 'Relax',
            'romantic' => 'Romántico',
            'shopping' => 'Shopping',
            'singles' => 'Solos y solas',
            'sport' => 'Deportes'
        ];
    }

}