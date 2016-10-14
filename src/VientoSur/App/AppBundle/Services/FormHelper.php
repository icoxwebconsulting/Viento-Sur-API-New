<?php

namespace VientoSur\App\AppBundle\Services;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class FormHelper
{
    private $formNewPay;

    private $fieldNames;

    public function __construct()
    {

    }

    public function initForm($formBooking, $formNewPay)
    {
        $this->formNewPay = $formNewPay;
        $this->fieldNames = array(
            'passengers' => [],
            'payment' => [],
            'contact' => [],
            'additional_data' => [],
            'vouchers' => []
        );

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

    private function processFormElement($groupKey, $key, $element)
    {
        $fieldName = $this->sanitizeName($element['qualified_name']);

        switch ($element['type']) {
            case 'TEXT':
                $this->formNewPay->add(
                    $fieldName,
                    'text'
                );
                break;

            case 'BOOLEAN':
                $this->formNewPay->add(
                    $fieldName,
                    'checkbox',
                    array(
                        'label' => 'Show this entry publicly?',
                        'required' => false,
                    ));
                break;

            case 'DATE':
            case 'DATE_YEAR_MONTH':
                $this->formNewPay->add(
                    $key,
                    'date',
                    array(
                        'widget' => 'single_text',
                        // do not render as type="date", to avoid HTML5 date pickers
                        'html5' => false,
                        // add a class that can be selected in JavaScript
                        'attr' => ['class' => 'js-datepicker_test'],
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

        $this->fieldNames[$groupKey][] = $fieldName;
    }

    private function generateChoiceField($optionsArray)
    {
        $optionField = array();
        foreach ($optionsArray as $item) {
            $optionField[$item['key']] = $item['key'];
        }

        return $optionField;
    }
}