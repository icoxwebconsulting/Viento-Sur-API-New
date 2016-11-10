<?php

namespace VientoSur\App\AppBundle\Services;

use Assetic\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use VientoSur\App\AppBundle\Services\Despegar;

class FormHelper
{
    private $formNewPay;
    private $fieldNames;
    private $selectedPack;
    private $despegar;

    //
    private $dataFill;
    private $expDate;

    private $passengersForm;
    private $paymentForm;
    private $contactForm;
    private $additional_dataForm;
    private $vouchersForm;

    public function __construct(Despegar $dp)
    {
        $this->despegar = $dp;
    }

    public function initForm($formBooking, $formNewPay, $roompackChoice, $paymentMethods)
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
        $this->passengersForm = $this->formNewPay->create('passengers', 'form', array('inherit_data' => true));
        $this->paymentForm = $this->formNewPay->create('payment', 'form', array('inherit_data' => true));
        $this->contactForm = $this->formNewPay->create('contact', 'form', array('inherit_data' => true));
        $this->additional_dataForm = $this->formNewPay->create('additionalData', 'form', array('inherit_data' => true));
        $this->vouchersForm = $this->formNewPay->create('vouchers', 'form', array('inherit_data' => true));

        foreach ($formBooking['items'] as $roomPack) {
            if ($roomPack['roompack_choice'] == $roompackChoice) {
                $this->selectedPack = $roomPack;
                break;
            }
        }

        if ($this->selectedPack == null) {
            throw new \Exception('No se consigue roompack');
        }

        //Agregar los radiobuttons de los métodos de pago
        $this->addPaymentMethods($paymentMethods);

        foreach ($formBooking['dictionary']['form_choices'][$this->selectedPack['form_choice']] as $key => $option) {
            if (is_array($option)) {
                if ($this->is_assoc($option)) {
                    //es asociativo
                    if ($key != 'payment') {
                        $this->processSimpleElement($key, $option);
                    } else {
                        //manejando payment
                        foreach ($option as $item) {
                            if (is_array($item)) {
                                $this->processSimpleElement($key, $item);
                            }
                        }
                    }
                } else {
                    //no es asociativo
                    if ($key == 'vouchers') {
                        $this->processSimpleElement($key, $option);
                    } else {
                        $this->processNestedElement($key, $option);
                    }
                }
            }
        }

        return $this->formNewPay;
    }

    public function fillFormData($formBooking, $formNewPaySend)
    {
        $expMonth = $formNewPaySend['expiration']->format('m');
        $expYear = $formNewPaySend['expiration']->format('Y');
        $form = $formBooking['dictionary']['form_choices'][$this->selectedPack['form_choice']];
        $dataArray = [];
        $this->dataFill = $formNewPaySend;
        $this->expDate = $expYear . '-' . $expMonth;

        foreach ($form as $key => $option) {
            if (is_array($option)) {
                $temp = [];
                switch ($key) {
                    case 'passengers':
                        foreach ($option as $key2 => $item) {
                            $temp = [];
                            foreach ($item as $key3 => $element) {
                                if (is_array($element) && array_key_exists('value', $element)) {
                                    //$temp[$key3] = $data[$form[$key][$key2][$key3]['qualified_name']];
                                    $temp[$key3] = $formNewPaySend[$key3 . $key2];
                                }
                            }
                            $dataArray[$key][] = $temp;
                        }

                        break;

                    case 'payment':
                        $temp = $this->fillSimpleElement($key, $option);
                        if (isset($temp['credit_card'])) {
                            unset($temp['credit_card']['number']);
                            unset($temp['credit_card']['expiration']);
                            unset($temp['credit_card']['card_code']);
                            unset($temp['credit_card']['bank_code']);
                            unset($temp['credit_card']['security_code']);
                        }
                        $dataArray['payment'] = $temp;
                        break;

                    case 'contact':
                        $temp = [];
                        foreach ($option as $key2 => $item) {
                            if (is_array($item) && array_key_exists('value', $item)) {
                                $temp[$key2] = $formNewPaySend[$key2];
                            } else if ($key2 == 'phones') {
                                $temp2 = [];
                                foreach ($item as $key3 => $element) {
                                    foreach ($element as $key4 => $element0) {
                                        if ((is_array($element0) && array_key_exists('value', $element0))) {
                                            $temp2[$key4] = $formNewPaySend[$key4 . $key3];
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
                                $temp[$key2] = $formNewPaySend[$key2];
                            }
                        }
                        $dataArray[$key] = $temp;
                        break;
                    case 'vouchers':
                        $temp = [];
                        foreach ($option as $key2 => $item) {
                            foreach ($item as $key3 => $elm) {
                                if (is_array($item) && array_key_exists('value', $item)) {
                                    $temp[$key2][$key3] = $formNewPaySend[$key3 . $key2];
                                }
                            }
                        }
                        $dataArray[$key] = $temp;
                        break;
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
        foreach ($data as $subKey => $element) {
            $this->processSimpleElement($key, $element, $subKey);
        }
    }

    private function processSimpleElement($groupKey, $elements, $subKey = null, $extraKey = null)
    {
        foreach ($elements as $key => $element) {
            if (is_array($element)) {
                if ($this->is_assoc($element)) {
                    //determinar si es un campo anidado
                    if (array_key_exists('value', $element)) {
                        //si está seteado value se sobreentiende que es un elemento simple, sino está anidado y se debe iterar
                        $this->processFormElement($groupKey, $key, $element, $subKey, $extraKey);
                    } else {
                        foreach ($element as $key2 => $item) {
                            if (is_array($item) && array_key_exists('value', $item)) {
                                $this->processFormElement($groupKey, $key, $item, $key2);
                            }
                        }
                    }
                } else {//elemento anidado, procesar c/u
                    for ($j = 0; $j < count($element); $j++) {
                        $this->processSimpleElement($groupKey, $element[$j], $key, $j);
                    }
                }
            } else {
                //sólo texto
            }
        }
    }

    private function fillSimpleElement($groupKey, $elements, $subKey = null)
    {
        $dataArray = [];

        foreach ($elements as $key => $element) {
            if (is_array($element)) {
                $temp = [];
                if ($this->is_assoc($element)) {
                    //determinar si es un campo anidado
                    if (array_key_exists('value', $element)) {
                        //si está seteado value se sobreentiende que es un elemento simple, sino está anidado y se debe iterar
                        if ($subKey) {
                            $temp[$key][$subKey] = $this->dataFill[$subKey . $key];
                        } else {
                            $temp[$key] = $this->dataFill[$key];
                        }
                    } else {
                        $temp2 = [];
                        foreach ($element as $key2 => $item) {
                            if (is_array($item) && array_key_exists('value', $item)) {
                                if ($key2 == 'expiration') {
                                    $temp2[$key2] = $this->expDate;
                                } else {
                                    $temp2[$key2] = $this->dataFill[$key2];
                                }
                            } else {
                                if (is_array($item)) {
                                    foreach ($item as $key3 => $value) {
                                        if (is_array($value) && array_key_exists('value', $value)) {
                                            $temp2[$key2][$key3] = $this->dataFill[$key3 . $key2];
                                        }
                                    }
                                }
                            }
                        }
                        $temp = $temp2;
                    }
                    $dataArray[$key] = $temp;
                } else {//elemento anidado, procesar c/u
                    for ($j = 0; $j < count($element); $j++) {
                        $this->fillSimpleElement($groupKey, $element[$j], $key);
                    }
                }
            }
        }

        return $dataArray;
    }

    private function processFormElement($groupKey, $key, $element, $subKey = null, $extraKey = null)
    {
        $fieldName = $key;
        if ($subKey === 'phones') {
            $fieldName .= $extraKey;
        } else {
            $fieldName .= ($subKey !== null) ? '' . $subKey : '';
        }

        switch ($element['type']) {
            case 'TEXT':
                if (strstr($fieldName, 'stateId')) {
                    //consultar api de despegar
                    $stateArray = $this->despegar->getStates();
                    $optionField = array();
                    foreach ($stateArray->items as $item) {
                        if (property_exists($item->descriptions, 'es')) {
                            $optionField[$item->descriptions->es] = $item->id;
                        }
                    }
                    $this->generateMultiValues($fieldName, $optionField, $groupKey);
                } else {
                    $this->formNewPay->add(
                        $this->{$groupKey . 'Form'}->add(
                            $fieldName,
                            'text',
                            array(
                                'required' => ($element['requirement_type'] == 'REQUIRED') ? true : false,
                            )
                        )
                    );
                }
                break;

            case 'BOOLEAN':
                $this->formNewPay->add(
                    $this->{$groupKey . 'Form'}->add(
                        $fieldName,
                        'checkbox',
                        array(
                            'required' => ($element['requirement_type'] == 'REQUIRED') ? true : false,
                        )
                    )
                );
                break;

            case 'DATE':
            case 'DATE_YEAR_MONTH':
                $this->formNewPay->add(
                    $this->{$groupKey . 'Form'}->add(
                        $key,
                        'date',
                        array(
                            'format' => 'MMM-yyyy  d',
                            'years' => range(date('Y'), date('Y') + 12),
                            'days' => array(1),
                            'empty_value' => array('year' => 'Año', 'month' => 'Mes', 'day' => false)
                        )
                    )
                );
                break;

            case 'MULTIVALUED':
                $optionField = $this->generateChoiceField($element['options']);
                $this->generateMultiValues($fieldName, $optionField, $groupKey);
                break;


        }

        $this->fieldNames[$groupKey][] = ($fieldName != '') ? $fieldName : $key;
    }

    private function generateChoiceField($optionsArray)
    {
        $optionField = array();
        foreach ($optionsArray as $item) {
            $optionField[$item['description']] = $item['key'];
        }

        return $optionField;
    }

    public function getSelectedPack()
    {
        return $this->selectedPack;
    }

    private function generateMultiValues($fieldName, $optionField, $groupKey)
    {
        $this->formNewPay->add(
            $this->{$groupKey . 'Form'}->add(
                $fieldName,
                'choice',
                array(
                    'choices' => $optionField,
                    // *this line is important*
                    'choices_as_values' => true,
                )
            )
        );
    }

    private function addPaymentMethods($paymentMethods)
    {
        $options = [];
        foreach ($paymentMethods as $key => $paymentMethod) {
            $options[$paymentMethod->choice] = $paymentMethod->choice;
        }

        $this->formNewPay->add(
            $this->paymentForm->add(
                'paymentMethod',
                'choice',
                array(
                    'choices' => $options,
                    'multiple' => false,
                    'expanded' => true,
                    'required' => true,
                ))
        );
    }
}