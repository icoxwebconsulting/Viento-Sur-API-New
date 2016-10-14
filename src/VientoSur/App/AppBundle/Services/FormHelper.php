<?php

namespace VientoSur\App\AppBundle\Services;

class FormHelper
{
    private $formNewPay;

    public function __construct()
    {

    }

    public function initForm($formBooking, $formNewPay)
    {
        $this->formNewPay = $formNewPay;

        $resultado = array();

        foreach ($formBooking['dictionary']['form_choices']["1"] as $key => $option) {
            if (is_array($option)) {
                if ($this->is_assoc($option)) {
                    $resultado[] = array(
                        "$key" => 'Es asociativo'
                    );
                    if ($key != 'payment') {
                        $this->processSimpleElement($key, $option);
                    } else {
                        //manejando payment
                        foreach ($option as $item) {
                            if (is_array($item)) {
                                $this->processSimpleElement($key, $item);
                            } else {
                                //es texto
                            }
                        }
                    }
                } else {
                    $resultado[] = array(
                        "$key" => 'NO es asociativo'
                    );
                    $this->processNestedElement($key, $option);
                }
            } else {
                $hola = 'no es un array';
            }
        }

        return $this->formNewPay;
    }

    private function is_assoc($array)
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    private function processNestedElement($key, $data)
    {
        foreach ($data as $element) {
            $this->processSimpleElement($key, $element);
        }
    }

    private function processSimpleElement($groupKey, $elements, $subKey = null)
    {
        foreach ($elements as $key => $element) {
            if (is_array($element)) {
                if ($this->is_assoc($element)) {
                    //determinar si es un campo anidado
                    if (array_key_exists('value', $element)) {
                        //si está seteado value se sobreentiende que es un elemento simple, sino está anidado y se debe iterar
                        $this->processFormElement($groupKey, $key, $element, $subKey);
                    } else {
                        foreach ($element as $key2 => $item) {
                            if (array_key_exists('value', $element)) {
                                $this->processFormElement($groupKey, $key, $element, $subKey);
                            }
                        }
                    }
                } else {//elemento anidado, procesar c/u
                    for ($j = 0; $j < count($element); $j++) {
                        $this->processSimpleElement($groupKey, $element[$j], $key);
                    }
                }
            } else {
                //sólo texto
            }
        }
    }

    private function processFormElement($groupKey, $key, $element)
    {
        switch ($element['type']) {
            case 'TEXT':

                break;

            case 'BOOLEAN':

                break;

            case 'DATE':

                break;

            case 'DATE_YEAR_MONTH':

                break;

            case 'MULTIVALUED':

                break;


        }
    }
}