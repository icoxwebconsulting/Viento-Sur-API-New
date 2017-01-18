<?php

namespace VientoSur\App\AppBundle\Services;


class Flights
{
    private $despegar;
    private $formNewPay;
    private $fieldNames;
    private $passengersForm;
    private $paymentsForm;
    private $contact_infoForm;

    public function __construct(Despegar $dp)
    {
        $this->despegar = $dp;
    }

    public function getCheckoutData($urlParams)
    {
        $results = $this->despegar->getFlightCheckoutHints($urlParams);
        //$results = json_decode('{"id":"-1","risk_analysis_providers":["threat_metrix"],"payments":{"metadata":{"min_quantity":1,"max_quantity":2,"optional":false},"credit_cards":[{"metadata":{"min_quantity":0,"max_quantity":1,"optional":false},"card_holder_identification":{"type":{"validations":[{"validation_type":"multivalue","error_code":"NOT_ALLOWED_VALUE","allowed_values":["LOCAL_DOCUMENT"]}],"data_type":"document_type"},"number":{"validations":[{"validation_type":"regex","error_code":"INVALID_LOCAL_DOCUMENT_NUMBER","regex":"^[0-9]{7,8}$"}],"data_type":"text"}},"card":{"expiration":{"data_type":"month_year"},"token":{"metadata":{"optional":false,"public_key":"5bac5018192c04700480f7d35de6a3519fd1dc17e24d7df59f32ab4119e8128b37ff9f2c0879e3967c6cdd8cb27090101381e6"},"validations":[{"validation_type":"dummy"}],"data_type":"text"},"type":{"validations":[{"validation_type":"multivalue","error_code":"NOT_ALLOWED_VALUE","allowed_values":["CREDIT","DEBIT"]}],"data_type":"card_type"},"security_code":{"data_type":"security_card_code"},"number":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":6}],"data_type":"card_number"},"card_holder_name":{"validations":[{"validation_type":"regex","error_code":"INVALID_VALUE","regex":"^.*[a-zA-Z].* +.*[a-zA-Z].*$"}],"data_type":"text"}},"card_code":{"data_type":"card_code"},"installments":{"data_type":"numeric"},"invoice":{"address":{"state":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1}],"default_value":"C","data_type":"text"},"city_id":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1},{"validation_type":"regex","error_code":"INVALID_VALUE","regex":"^[0-9]*$"}],"data_type":"city_id"},"number":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1,"max":255}],"data_type":"text"},"street":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1,"max":150}],"data_type":"text"},"floor":{"metadata":{"optional":true},"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":0,"max":255}],"data_type":"text"},"department":{"metadata":{"optional":true},"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":0,"max":255}],"data_type":"text"}},"fiscal_status":{"validations":[{"validation_type":"multivalue","error_code":"NOT_ALLOWED_VALUE","allowed_values":["FINAL","INSCR","EXENT","MONOTRIBUTO"]}],"default_value":"FINAL","data_type":"fiscal_status"},"business_name":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1,"max":100}],"data_type":"text"},"fiscal_id":{"validations":[{"validation_type":"regex","error_code":"INVALID_FISCAL_DOCUMENT_NUMBER","regex":"^(20|27|30|23|33|24|34)(-|)[0-9]{8}(-|)[0-9]$"}],"data_type":"local_fiscal_id"}}}],"coupons":[{"metadata":{"min_quantity":0,"max_quantity":1,"optional":false},"coupon_id":{"data_type":"coupon"},"beneficiary":{"data_type":"text"},"invoice":{"metadata":{"optional":true},"address":{"state":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1}],"default_value":"C","data_type":"text"},"city_id":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1},{"validation_type":"regex","error_code":"INVALID_VALUE","regex":"^[0-9]*$"}],"data_type":"city_id"},"number":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1,"max":255}],"data_type":"text"},"street":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1,"max":150}],"data_type":"text"},"floor":{"metadata":{"optional":true},"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":0,"max":255}],"data_type":"text"},"department":{"metadata":{"optional":true},"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":0,"max":255}],"data_type":"text"}},"fiscal_status":{"validations":[{"validation_type":"multivalue","error_code":"NOT_ALLOWED_VALUE","allowed_values":["FINAL","INSCR","EXENT","MONOTRIBUTO"]}],"default_value":"FINAL","data_type":"fiscal_status"},"business_name":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1,"max":100}],"data_type":"text"},"fiscal_id":{"validations":[{"validation_type":"regex","error_code":"INVALID_FISCAL_DOCUMENT_NUMBER","regex":"^(20|27|30|23|33|24|34)(-|)[0-9]{8}(-|)[0-9]$"}],"data_type":"local_fiscal_id"}}}]},"contact_info":{"email":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":0,"max":100},{"validation_type":"regex","error_code":"INVALID_VALUE","regex":"^(?!.*[@.][@.])(?!.)(?=.*[a-zA-Z0-9].*@.*.[a-zA-Z]{2,6}$)[.-_+a-zA-Z0-9]+(?!.*[@.][^a-zA-Z0-9]*.)@[.-+a-zA-Z0-9]+$"}],"data_type":"email"},"phones":[{"metadata":{"min_quantity":1,"optional":false},"type":{"validations":[{"validation_type":"multivalue","error_code":"NOT_ALLOWED_VALUE","allowed_values":["CELULAR","HOME","WORK","FAX","OTHER"]}],"data_type":"phone_type"},"country_code":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1,"max":100},{"validation_type":"regex","error_code":"INVALID_VALUE","regex":"^.*[0-9].*$"}],"default_value":"54","data_type":"phone_country_code"},"number":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":5,"max":20},{"validation_type":"regex","error_code":"INVALID_VALUE","regex":"^.*[0-9].*[0-9].*[0-9].*[0-9].*[0-9].*$"}],"data_type":"numeric"},"area_code":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1,"max":100},{"validation_type":"regex","error_code":"INVALID_VALUE","regex":"^.*[0-9].*$"}],"default_value":"11","data_type":"numeric"}}],"accept_offers":{"data_type":"boolean"}},"passengers":{"metadata":{"quantity":1,"optional":false},"adults":[{"metadata":{"quantity":1,"optional":false},"validations":[{"validation_type":"sum_fields","error_code":"INVALID_PASSENGER_NAMES_LENGTH","fields":["first_name","last_name"],"max_length":58,"with_white_space":false}],"identification":{"type":{"validations":[{"validation_type":"multivalue","error_code":"NOT_ALLOWED_VALUE","allowed_values":["LOCAL_DOCUMENT"]}],"data_type":"document_type"},"number":{"validations":[{"validation_type":"regex","error_code":"INVALID_LOCAL_DOCUMENT_NUMBER","regex":"^[0-9]{7,8}$"}],"data_type":"text"}},"first_name":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":0,"max":28},{"validation_type":"regex","error_code":"INVALID_VALUE","regex":"^[a-zA-Zsu00c1u00c9u00cdu00d3u00dau00e1u00e9u00edu00f3u00fau00e4u00ebu00efu00f6u00fcu00c4u00cbu00cfu00d6u00dcu00c7u00e7u1e08u1e09u1e10u1e11u0228u0229u1e1cu1e1du0122u0123u1e28u1e29u0136u0137u013bu013cu0145u0146u0156u0157u015eu015fu0162u0163u00e3u00f5u0169u00c3u00d5u0168u00f1u00d1u00c2u00e2u00cau00eau00d4u00f4u00dbu00fbu00ceu00ee]+$"}],"data_type":"text"},"last_name":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":0,"max":28},{"validation_type":"regex","error_code":"INVALID_VALUE","regex":"^[a-zA-Zsu00c1u00c9u00cdu00d3u00dau00e1u00e9u00edu00f3u00fau00e4u00ebu00efu00f6u00fcu00c4u00cbu00cfu00d6u00dcu00c7u00e7u1e08u1e09u1e10u1e11u0228u0229u1e1cu1e1du0122u0123u1e28u1e29u0136u0137u013bu013cu0145u0146u0156u0157u015eu015fu0162u0163u00e3u00f5u0169u00c3u00d5u0168u00f1u00d1u00c2u00e2u00cau00eau00d4u00f4u00dbu00fbu00ceu00ee]+$"}],"data_type":"text"},"birthdate":{"validations":[{"validation_type":"date_range","error_code":"OUT_OF_RANGE","from":"1917-04-23T00:00:00Z","to":"2005-04-23T23:59:59Z"}],"data_type":"date"},"nationality":{"validations":[{"validation_type":"multivalue","error_code":"NOT_ALLOWED_VALUE","allowed_values":["AR"]}],"data_type":"country_code"},"gender":{"validations":[{"validation_type":"multivalue","error_code":"NOT_ALLOWED_VALUE","allowed_values":["MALE","FEMALE"]}],"data_type":"gender_type"}}]},"legals":{"metadata":{"optional":true},"terms_and_conditions":{"validations":[{"validation_type":"multivalue","error_code":"NOT_ALLOWED_VALUE","allowed_values":[true]}],"data_type":"boolean"}},"agent_code":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":0,"max":100}],"data_type":"agent_code"}}', true);
        return $results;
    }

    public function initForm($booking, $formNewPay)
    {
        $this->formNewPay = $formNewPay;

        $this->fieldNames = array(
            'payments' => [],
            'contact_info' => [],
            'passengers' => [],
            'legals' => []
        );

        $this->passengersForm = $this->formNewPay->create('passengers', 'form', array('inherit_data' => true));
        $this->paymentsForm = $this->formNewPay->create('payments', 'form', array('inherit_data' => true));
        $this->contact_infoForm = $this->formNewPay->create('contact_info', 'form', array('inherit_data' => true));

        foreach ($booking as $key => $item) {
            if ($key == 'passengers') {
                $this->processPassengers($item);
            } else if ($key == 'payments') {
                $this->processPayments($item);
            } else if ($key == 'contact_info') {
                $this->processContactInfo($item);
            }
        }

        return $this->formNewPay;
    }

    private function processPassengers($passengers)
    {
        foreach ($passengers['adults'] as $count => $elements) {
            foreach ($elements as $key => $item) {
                if ($key != 'metadata' && $key != 'validations') {
                    switch ($key) {
                        case 'identification':
                            foreach ($item as $key2 => $detail) {
                                if ($key2 != 'metadata' && $key2 != 'validations') {
                                    $this->processFormElement('passengers', 'adults', $key2, $count, $detail);
                                }
                            }
                            break;
                        default: //first_name, last_name, birthdate, nationality, gender
                            $this->processFormElement('passengers', 'adults', $key, $count, $item);
                            break;
                    }
                }
            }
        }
    }

    private function processPayments($payments)
    {
        foreach ($payments['credit_cards'] as $count => $elements) {
            foreach ($elements as $key => $item) {
                if ($key != 'metadata') {
                    switch ($key) {
                        case 'invoice':
                            foreach ($item as $key2 => $detail) {
                                if ($key2 != 'address') {
                                    $this->processFormElement('payments', $key, $key2, $count, $detail);
                                } else {
                                    foreach ($detail as $key3 => $detail2) {
                                        $this->processFormElement('payments', $key, $key3, $count, $detail2);
                                    }
                                }
                            }
                            break;
                        case 'card_holder_identification':
                        case 'card':
                            foreach ($item as $key2 => $detail) {
                                $this->processFormElement('payments', $key, $key2, $count, $detail);
                            }
                            break;
                        default: //card_code, installments
                            $this->processFormElement('payments', $key, $key, $count, $item);
                            break;
                    }
                }
            }
        }
    }

    private function processContactInfo($contactInfo)
    {
        foreach ($contactInfo as $key => $elements) {
            if ($key != 'phones') {
                $this->processFormElement('contact_info', $key, '', '', $elements);
            } else {
                $count = 0;
                foreach ($elements as $element) {
                    foreach ($element as $key2 => $detail) {
                        if ($key2 != 'metadata') {
                            $this->processFormElement('contact_info', 'phones', $key2, $count, $detail);
                        }
                    }
                    $count++;
                }
            }
        }
    }

    private function processFormElement($groupKey, $subcategory, $key, $count, $element)
    {
        switch ($element['data_type']) {
            case 'text':
            case 'card_type':
            case 'security_card_code':
            case 'card_number':
            case 'card_code':
            case 'city_id':
            case 'local_fiscal_id':
            case 'phone_country_code':
            case 'numeric':
                $this->formNewPay->add(
                    $this->{$groupKey . 'Form'}->add(
                        $subcategory . '-' . $key . $count,
                        'text',
                        array(
                            'required' => true,
                        )
                    )
                );
                break;
            case 'email':
                $this->formNewPay->add(
                    $this->{$groupKey . 'Form'}->add(
                        $subcategory . '-' . $key . $count,
                        'email',
                        array(
                            'required' => true,
                        )
                    )
                );
                break;
            case 'month_year':
                $this->formNewPay->add(
                    $this->{$groupKey . 'Form'}->add(
                        $subcategory . '-' . $key . $count,
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
            case 'date':
                $this->formNewPay->add(
                    $this->{$groupKey . 'Form'}->add(
                        $subcategory . '-' . $key . $count,
                        'date',
                        array(
                            'format' => 'MMM-yyyy  d',
                            'years' => range(date('Y'), date('Y') + 12),
                            'days' => array(1),
                            'empty_value' => array('year' => 'Año', 'month' => 'Mes', 'day' => 'Dia')
                        )
                    )
                );
                break;
            case 'document_type':
            case 'country_code':
            case 'gender_type':
            case 'fiscal_status':
            case 'phone_type':
                $optionField = $this->generateChoiceField($element['validations'][0]['allowed_values']);
                $this->formNewPay->add(
                    $this->{$groupKey . 'Form'}->add(
                        $subcategory . '-' . $key . $count,
                        'choice',
                        array(
                            'choices' => $optionField,
                            // *this line is important*
                            'choices_as_values' => true,
                        )
                    )
                );
                break;
        }
    }

    private function generateChoiceField($optionsArray)
    {
        $optionField = array();
        foreach ($optionsArray as $item) {
            $optionField[$item] = $item;
        }

        return $optionField;
    }
}