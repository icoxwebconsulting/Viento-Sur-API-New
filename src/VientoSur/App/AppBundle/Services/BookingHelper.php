<?php

namespace VientoSur\App\AppBundle\Services;

use Symfony\Component\Config\Definition\Exception\Exception;

class BookingHelper
{


    public function __construct()
    {

    }

    public function getSearchText($checkin_date, $checkout_date, $distribution)
    {
        $checkin_date = new \DateTime($checkin_date);
        $checkout_date = new \DateTime($checkout_date);

        $months = [
            'Jan' => 'Ene',
            'Feb' => 'Feb',
            'Mar' => 'Mar',
            'Apr' => 'Abr',
            'May' => 'May',
            'Jun' => 'Jun',
            'Jul' => 'Jul',
            'Aug' => 'Ago',
            'Sep' => 'Sep',
            'Oct' => 'Oct',
            'Nov' => 'Nov',
            'Dec' => 'Dic'
        ];

        $str = '<b>Entrada:</b> ' . $checkin_date->format('d') . ' ' . $months[$checkin_date->format('M')];
        $str .= ' <b>Salida:</b> ' . $checkout_date->format('d') . ' ' . $months[$checkin_date->format('M')];
        $travellers = $this->processDistribution($distribution);
        $str .= '<br /> <b>' . $travellers['adults'] . ' adulto' . (($travellers['adults'] > 1) ? 's' : '') . '</b>';
        $str .= (($travellers['childs'] > 0) ? $travellers['childs'] . ' menor' . (($travellers['childs'] > 1) ? 'es' : '') : '');

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

        foreach ($temp as $item) {
            if (strpos($item, '-')) {
                $temp2 = explode('-', $item);
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

        return $result;
    }

}