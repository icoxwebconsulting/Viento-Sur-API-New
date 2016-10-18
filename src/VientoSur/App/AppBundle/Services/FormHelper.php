<?php

namespace VientoSur\App\AppBundle\Services;

use Assetic\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Response;

class FormHelper
{
    private $formNewPay;

    private $fieldNames;

    private $selectedPack;

    public function __construct()
    {

    }

    public function initForm($formBooking, $formNewPay, $roompackChoice)
    {

        $this->formNewPay = $formNewPay;
        $this->fieldNames = array(
            'passengers' => [],
            'payment' => [],
            'contact' => [],
            'additional_data' => [],
            'vouchers' => []
        );
        $this->selectedPack = null;

        foreach ($formBooking['items'] as $roomPack) {
            if ($roomPack['roompack_choice'] == $roompackChoice) {
                $this->selectedPack = $roomPack;
                break;
            }
        }

        if ($this->selectedPack == null) {
            throw new \Exception('No se consigue roompack');
        }

        $resultado = array();

        foreach ($formBooking['dictionary']['form_choices'][$this->selectedPack['form_choice']] as $key => $option) {
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
                    if($key == 'vouchers'){
                        $this->processSimpleElement($key, $option);
                    }else{
                        $this->processNestedElement($key, $option);
                    }
                }
            } else {
                $hola = 'no es un array';
            }
        }

        return $this->formNewPay;
    }

    public function fillFormData($formBooking, $formNewPaySend)
    {
        $data = $this->processReverseNames($formNewPaySend);

        $expMonth = $formNewPaySend['expiration']->format('m');
        $expYear = $formNewPaySend['expiration']->format('Y');
        $form = $formBooking['dictionary']['form_choices'][$this->selectedPack['form_choice']];
        $dataArray = [];

        foreach ($form as $key => $option) {
            if (is_array($option)) {
                $temp = [];
                switch ($key) {
                    case 'passengers':
                        foreach ($option as $key2 => $item) {
                            $temp = [];
                            foreach ($item as $key3 => $element) {
                                if (is_array($element) && array_key_exists('value', $element)) {
                                    $temp[$key3] = $data[$form[$key][$key2][$key3]['qualified_name']];
                                }
                            }
                            $dataArray[$key][] = $temp;
                        }

                        break;

                    case 'payment':
                        if (is_array($option)) {
                            foreach ($option as $key2 => $item) {
                                if (is_array($item)) {
                                    $temp = [];
                                    foreach ($item as $key3 => $element) {
                                        if (is_array($element) && array_key_exists('value', $element)) {
                                            if ($key3 != 'expiration') {
                                                if ($key3 == 'bank_code') {
                                                    //$temp[$key3] = "null";
                                                } else {
                                                    $temp[$key3] = $data[$form[$key][$key2][$key3]['qualified_name']];
                                                }
                                            } else {
                                                $temp[$key3] = $expYear . '-' . $expMonth;
                                            }
                                        }
                                    }
                                    $dataArray[$key][$key2] = $temp;
                                }
                            }
                        }
                        break;

                    case 'contact':
                        $temp = [];
                        foreach ($option as $key2 => $item) {
                            if (is_array($item) && array_key_exists('value', $item)) {
                                $temp[$key2] = $data[$form[$key][$key2]['qualified_name']];
                            } else if ($key2 == 'phones') {
                                $temp2 = [];
                                foreach ($item as $key3 => $element) {
                                    foreach ($element as $key4 => $element0) {
                                        if ((is_array($element0) && array_key_exists('value', $element0))) {
                                            $temp2[$key4] = $data[$form[$key][$key2][$key3][$key4]['qualified_name']];
                                        }
                                    }
                                    $temp['phones'][] = $temp2;
                                }
                            }
                        }
                        $dataArray[$key] = $temp;
                        break;
                    case 'additional_data':
                        $temp = [];
                        foreach ($option as $key2 => $item) {
                            if (is_array($item) && array_key_exists('value', $item)) {
                                $temp[$key2] = $data[$form[$key][$key2]['qualified_name']];
                            }
                        }
                        $dataArray[$key] = $temp;
                        break;
                    case 'vouchers':
                        $temp = [];
                        foreach ($option as $key2 => $item) {
                            if (is_array($item) && array_key_exists('value', $item)) {
                                $temp[$key2] = $data[$form[$key][$key2]['qualified_name']];
                            }
                        }
                }
            }
        }

        return $dataArray;
    }

    public function getFieldNames()
    {
        return $this->fieldNames;
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

    private function sanitizeName($name)
    {
        return str_replace(array('[', ']', '.'), array('_-', '-_', ':'), $name);
    }

    private function reverseSanitizeName($name)
    {
        return str_replace(array('_-', '-_', ':'), array('[', ']', '.'), $name);
    }

    private function processReverseNames($toReverse)
    {
        $array = [];

        foreach ($toReverse as $key => $value) {
            if (!strncmp($key, 'hotelInputDefinition', 20)) {
                $array[$this->reverseSanitizeName($key)] = $value;
            }
        }

        return $array;
    }

    private function processFormElement($groupKey, $key, $element)
    {
        $fieldName = $this->sanitizeName($element['qualified_name']);

        switch ($element['type']) {
            case 'TEXT':
                $this->formNewPay->add(
                    $fieldName,
                    'text',
                    array(
                        'required' => ($element['requirement_type'] == 'REQUIRED') ? true : false,
                    )
                );
                break;

            case 'BOOLEAN':
                $this->formNewPay->add(
                    $fieldName,
                    'checkbox',
                    array(
                        'label' => '',
                        'required' => ($element['requirement_type'] == 'REQUIRED') ? true : false,
                    )
                );
                break;

            case 'DATE':
            case 'DATE_YEAR_MONTH':
                $this->formNewPay->add(
                    $key,
                    'date',
                    array(
                        'format' => 'MMM-yyyy  d',
                        'years' => range(date('Y'), date('Y') + 12),
                        'days' => array(1),
                        'empty_value' => array('year' => 'Año', 'month' => 'Mes', 'day' => false)
                    )
                );
                break;

            case 'MULTIVALUED':
                $optionField = $this->generateChoiceField($element['options']);

                $this->formNewPay->add(
                    $fieldName,
                    'choice',
                    array(
                        'choices' => $optionField,
                        // *this line is important*
                        'choices_as_values' => true,
                    )
                );
                break;


        }

        $this->fieldNames[$groupKey][] = ($fieldName != '') ? $fieldName : $key;
    }

    private
    function generateChoiceField($optionsArray)
    {
        $optionField = array();
        foreach ($optionsArray as $item) {
            $optionField[$item['key']] = $item['key'];
        }

        return $optionField;
    }

    public
    function getSelectedPack()
    {
        return $this->selectedPack;
    }
}