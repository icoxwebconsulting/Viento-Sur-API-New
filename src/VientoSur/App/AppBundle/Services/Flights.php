<?php

namespace VientoSur\App\AppBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use VientoSur\App\AppBundle\Entity\FlightPassengers;
use VientoSur\App\AppBundle\Entity\FlightReservation;


class Flights
{
    private $despegar;
    private $em;
    private $formNewPay;
    private $fieldNames;
    private $passengersForm;
    private $paymentsForm;
    private $contact_infoForm;
    private $agentCode;
    private $resources = [
        'LOCAL_DOCUMENT' => 'DNI',
        'FINAL' => 'Consumidor Final',
        'INSCR' => 'Responsable Inscripto',
        'EXENT' => 'Exento',
        'MONOTRIBUTO' => 'Monotributo',
        'CELULAR' => 'Celular',
        'HOME' => 'Casa',
        'WORK' => 'Trabajo',
        'FAX' => 'Fax',
        'OTHER' => 'Otro',
        'MALE' => 'Masculino',
        'FEMALE' => 'Femenino',
        'AR' => 'Argentina'
    ];

    public function __construct(Despegar $dp, EntityManager $entityManager, $agentCode)
    {
        $this->despegar = $dp;
        $this->em = $entityManager;
        $this->agentCode = $agentCode;
    }

    public function getCheckoutData($urlParams)
    {
        $results = $this->despegar->getFlightCheckoutHints($urlParams);
        //$results = json_decode('{"id":"-1","risk_analysis_providers":["threat_metrix"],"payments":{"metadata":{"min_quantity":1,"max_quantity":2,"optional":false},"credit_cards":[{"metadata":{"min_quantity":0,"max_quantity":1,"optional":false},"card_holder_identification":{"type":{"validations":[{"validation_type":"multivalue","error_code":"NOT_ALLOWED_VALUE","allowed_values":["LOCAL_DOCUMENT"]}],"data_type":"document_type"},"number":{"validations":[{"validation_type":"regex","error_code":"INVALID_LOCAL_DOCUMENT_NUMBER","regex":"^[0-9]{7,8}$"}],"data_type":"text"}},"card":{"expiration":{"data_type":"month_year"},"token":{"metadata":{"optional":false,"public_key":"5bac5018192c04700480f7d35de6a3519fd1dc17e24d7df59f32ab4119e8128b37ff9f2c0879e3967c6cdd8cb27090101381e6"},"validations":[{"validation_type":"dummy"}],"data_type":"text"},"type":{"validations":[{"validation_type":"multivalue","error_code":"NOT_ALLOWED_VALUE","allowed_values":["CREDIT","DEBIT"]}],"data_type":"card_type"},"security_code":{"data_type":"security_card_code"},"number":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":6}],"data_type":"card_number"},"card_holder_name":{"validations":[{"validation_type":"regex","error_code":"INVALID_VALUE","regex":"^.*[a-zA-Z].* +.*[a-zA-Z].*$"}],"data_type":"text"}},"card_code":{"data_type":"card_code"},"installments":{"data_type":"numeric"},"invoice":{"address":{"state":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1}],"default_value":"C","data_type":"text"},"city_id":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1},{"validation_type":"regex","error_code":"INVALID_VALUE","regex":"^[0-9]*$"}],"data_type":"city_id"},"number":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1,"max":255}],"data_type":"text"},"street":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1,"max":150}],"data_type":"text"},"floor":{"metadata":{"optional":true},"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":0,"max":255}],"data_type":"text"},"department":{"metadata":{"optional":true},"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":0,"max":255}],"data_type":"text"}},"fiscal_status":{"validations":[{"validation_type":"multivalue","error_code":"NOT_ALLOWED_VALUE","allowed_values":["FINAL","INSCR","EXENT","MONOTRIBUTO"]}],"default_value":"FINAL","data_type":"fiscal_status"},"business_name":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1,"max":100}],"data_type":"text"},"fiscal_id":{"validations":[{"validation_type":"regex","error_code":"INVALID_FISCAL_DOCUMENT_NUMBER","regex":"^(20|27|30|23|33|24|34)(-|)[0-9]{8}(-|)[0-9]$"}],"data_type":"local_fiscal_id"}}}],"coupons":[{"metadata":{"min_quantity":0,"max_quantity":1,"optional":false},"coupon_id":{"data_type":"coupon"},"beneficiary":{"data_type":"text"},"invoice":{"metadata":{"optional":true},"address":{"state":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1}],"default_value":"C","data_type":"text"},"city_id":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1},{"validation_type":"regex","error_code":"INVALID_VALUE","regex":"^[0-9]*$"}],"data_type":"city_id"},"number":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1,"max":255}],"data_type":"text"},"street":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1,"max":150}],"data_type":"text"},"floor":{"metadata":{"optional":true},"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":0,"max":255}],"data_type":"text"},"department":{"metadata":{"optional":true},"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":0,"max":255}],"data_type":"text"}},"fiscal_status":{"validations":[{"validation_type":"multivalue","error_code":"NOT_ALLOWED_VALUE","allowed_values":["FINAL","INSCR","EXENT","MONOTRIBUTO"]}],"default_value":"FINAL","data_type":"fiscal_status"},"business_name":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1,"max":100}],"data_type":"text"},"fiscal_id":{"validations":[{"validation_type":"regex","error_code":"INVALID_FISCAL_DOCUMENT_NUMBER","regex":"^(20|27|30|23|33|24|34)(-|)[0-9]{8}(-|)[0-9]$"}],"data_type":"local_fiscal_id"}}}]},"contact_info":{"email":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":0,"max":100},{"validation_type":"regex","error_code":"INVALID_VALUE","regex":"^(?!.*[@.][@.])(?!.)(?=.*[a-zA-Z0-9].*@.*.[a-zA-Z]{2,6}$)[.-_+a-zA-Z0-9]+(?!.*[@.][^a-zA-Z0-9]*.)@[.-+a-zA-Z0-9]+$"}],"data_type":"email"},"phones":[{"metadata":{"min_quantity":1,"optional":false},"type":{"validations":[{"validation_type":"multivalue","error_code":"NOT_ALLOWED_VALUE","allowed_values":["CELULAR","HOME","WORK","FAX","OTHER"]}],"data_type":"phone_type"},"country_code":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1,"max":100},{"validation_type":"regex","error_code":"INVALID_VALUE","regex":"^.*[0-9].*$"}],"default_value":"54","data_type":"phone_country_code"},"number":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":5,"max":20},{"validation_type":"regex","error_code":"INVALID_VALUE","regex":"^.*[0-9].*[0-9].*[0-9].*[0-9].*[0-9].*$"}],"data_type":"numeric"},"area_code":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":1,"max":100},{"validation_type":"regex","error_code":"INVALID_VALUE","regex":"^.*[0-9].*$"}],"default_value":"11","data_type":"numeric"}}],"accept_offers":{"data_type":"boolean"}},"passengers":{"metadata":{"quantity":1,"optional":false},"adults":[{"metadata":{"quantity":1,"optional":false},"validations":[{"validation_type":"sum_fields","error_code":"INVALID_PASSENGER_NAMES_LENGTH","fields":["first_name","last_name"],"max_length":58,"with_white_space":false}],"identification":{"type":{"validations":[{"validation_type":"multivalue","error_code":"NOT_ALLOWED_VALUE","allowed_values":["LOCAL_DOCUMENT"]}],"data_type":"document_type"},"number":{"validations":[{"validation_type":"regex","error_code":"INVALID_LOCAL_DOCUMENT_NUMBER","regex":"^[0-9]{7,8}$"}],"data_type":"text"}},"first_name":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":0,"max":28},{"validation_type":"regex","error_code":"INVALID_VALUE","regex":"^[a-zA-Zsu00c1u00c9u00cdu00d3u00dau00e1u00e9u00edu00f3u00fau00e4u00ebu00efu00f6u00fcu00c4u00cbu00cfu00d6u00dcu00c7u00e7u1e08u1e09u1e10u1e11u0228u0229u1e1cu1e1du0122u0123u1e28u1e29u0136u0137u013bu013cu0145u0146u0156u0157u015eu015fu0162u0163u00e3u00f5u0169u00c3u00d5u0168u00f1u00d1u00c2u00e2u00cau00eau00d4u00f4u00dbu00fbu00ceu00ee]+$"}],"data_type":"text"},"last_name":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":0,"max":28},{"validation_type":"regex","error_code":"INVALID_VALUE","regex":"^[a-zA-Zsu00c1u00c9u00cdu00d3u00dau00e1u00e9u00edu00f3u00fau00e4u00ebu00efu00f6u00fcu00c4u00cbu00cfu00d6u00dcu00c7u00e7u1e08u1e09u1e10u1e11u0228u0229u1e1cu1e1du0122u0123u1e28u1e29u0136u0137u013bu013cu0145u0146u0156u0157u015eu015fu0162u0163u00e3u00f5u0169u00c3u00d5u0168u00f1u00d1u00c2u00e2u00cau00eau00d4u00f4u00dbu00fbu00ceu00ee]+$"}],"data_type":"text"},"birthdate":{"validations":[{"validation_type":"date_range","error_code":"OUT_OF_RANGE","from":"1917-04-23T00:00:00Z","to":"2005-04-23T23:59:59Z"}],"data_type":"date"},"nationality":{"validations":[{"validation_type":"multivalue","error_code":"NOT_ALLOWED_VALUE","allowed_values":["AR"]}],"data_type":"country_code"},"gender":{"validations":[{"validation_type":"multivalue","error_code":"NOT_ALLOWED_VALUE","allowed_values":["MALE","FEMALE"]}],"data_type":"gender_type"}}]},"legals":{"metadata":{"optional":true},"terms_and_conditions":{"validations":[{"validation_type":"multivalue","error_code":"NOT_ALLOWED_VALUE","allowed_values":[true]}],"data_type":"boolean"}},"agent_code":{"validations":[{"validation_type":"length","error_code":"INVALID_LENGTH","min":0,"max":100}],"data_type":"agent_code"}}', true);
        return $results;
    }

    public function getItineraryDetail($urlParams, $id)
    {
        $results = $this->despegar->getFlightItineraryDetail($urlParams, $id);
        //$results = json_decode('{"id":"prism_AR_0_FLIGHTS_A-1_C-0_I-0_RT-BUEMIA20170312-MIABUE20170319_xorigin-api_applyDynaprov-false_channel-travel-agency-api_hvMock-true!0!C_3TwSfObnYjefaa1d586912210d!1,1","outbound_choices":[{"choice":1,"duration":"32:00","segments":[{"from":"EZE","to":"LIM","departure_datetime":"2017-03-12T07:40:00.000-03:00","arrival_datetime":"2017-03-12T10:40:00.000-05:00","duration":"05:00","airline":"LA","flight_id":"LA2428","cabin_type":"economy","stopovers":[],"seats_remaining":9,"equipment_code":"763"},{"from":"LIM","to":"BOG","departure_datetime":"2017-03-12T19:00:00.000-05:00","arrival_datetime":"2017-03-12T22:15:00.000-05:00","duration":"03:15","airline":"LA","flight_id":"LA3530","cabin_type":"economy","stopovers":[],"seats_remaining":9,"equipment_code":"320"},{"from":"BOG","to":"MIA","departure_datetime":"2017-03-13T09:30:00.000-05:00","arrival_datetime":"2017-03-13T14:40:00.000-04:00","duration":"04:10","airline":"LA","flight_id":"LA3514","cabin_type":"economy","stopovers":[],"seats_remaining":9,"equipment_code":"320"}],"delay_info":[{"flight_id":"LA2428","from":"EZE","to":"LIM","on_time":80.33,"cancelled":0,"more_than30_minutes":16.39,"more_than60_minutes":3.28,"provider":"FS","category":"undefined"}]}],"inbound_choices":[{"choice":1,"duration":"19:50","segments":[{"from":"MIA","to":"BOG","departure_datetime":"2017-03-19T21:30:00.000-04:00","arrival_datetime":"2017-03-20T00:10:00.000-05:00","duration":"03:40","airline":"LA","flight_id":"LA573","cabin_type":"economy","stopovers":[],"seats_remaining":9,"equipment_code":"787"},{"from":"BOG","to":"LIM","departure_datetime":"2017-03-20T04:15:00.000-05:00","arrival_datetime":"2017-03-20T07:25:00.000-05:00","duration":"03:10","airline":"LA","flight_id":"LA3531","cabin_type":"economy","stopovers":[],"seats_remaining":9,"equipment_code":"320"},{"from":"LIM","to":"EZE","departure_datetime":"2017-03-20T11:50:00.000-05:00","arrival_datetime":"2017-03-20T18:20:00.000-03:00","duration":"04:30","airline":"LA","flight_id":"LA2429","cabin_type":"economy","stopovers":[],"seats_remaining":9,"equipment_code":"763"}],"delay_info":[{"flight_id":"LA2429","from":"LIM","to":"EZE","on_time":79.66,"cancelled":0,"more_than30_minutes":15.25,"more_than60_minutes":5.08,"provider":"FS","category":"undefined"}]}],"price_detail":{"commercial_policy":"9505","currency":"ARS","total":14478,"base":11157,"adult_base":11157,"adult_total":14478,"adults_subtotal":11157,"fees":422,"taxes":2899},"validating_carrier":"LA","payment_methods":[{"id":"1909","card_code":"VI_RIO","installments":6,"first_installment_amount":2413,"other_installments_amount":2413,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"RIO","generic_card_code":"VI","currency":"ARS","excluded_bines":[],"as_fee":false},{"id":"1283","card_code":"AX_RIO","installments":6,"first_installment_amount":2413,"other_installments_amount":2413,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"RIO","generic_card_code":"AX","currency":"ARS","excluded_bines":[],"as_fee":false},{"id":"1722","card_code":"VI_CIT","installments":6,"first_installment_amount":2768,"other_installments_amount":2342,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"CIT","generic_card_code":"VI","currency":"ARS","excluded_bines":[],"as_fee":false},{"id":"1824","card_code":"CA_CIT","installments":6,"first_installment_amount":2768,"other_installments_amount":2342,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"CIT","generic_card_code":"CA","currency":"ARS","excluded_bines":["525419","553652"],"as_fee":false},{"id":"30","card_code":"VI","installments":1,"first_installment_amount":14478,"other_installments_amount":0,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","generic_card_code":"VI","currency":"ARS","excluded_bines":["451761","451757","451764","451769","451765","451377","439818"],"as_fee":false},{"id":"1939","card_code":"VI","installments":6,"first_installment_amount":3054,"other_installments_amount":2633,"total_interest_amount":1736,"with_bank_interest":false,"type":"CREDIT","generic_card_code":"VI","currency":"ARS","excluded_bines":["451761","451757","451764","451769","451765","451377","439818"],"as_fee":false},{"id":"1939","card_code":"VI","installments":12,"first_installment_amount":1902,"other_installments_amount":1480,"total_interest_amount":3700,"with_bank_interest":false,"type":"CREDIT","generic_card_code":"VI","currency":"ARS","excluded_bines":["451761","451757","451764","451769","451765","451377","439818"],"as_fee":false},{"id":"664","card_code":"CA","installments":1,"first_installment_amount":14478,"other_installments_amount":0,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","generic_card_code":"CA","currency":"ARS","excluded_bines":[],"as_fee":false},{"id":"1940","card_code":"CA","installments":6,"first_installment_amount":3054,"other_installments_amount":2633,"total_interest_amount":1736,"with_bank_interest":false,"type":"CREDIT","generic_card_code":"CA","currency":"ARS","excluded_bines":[],"as_fee":false},{"id":"1940","card_code":"CA","installments":12,"first_installment_amount":1895,"other_installments_amount":1473,"total_interest_amount":3614,"with_bank_interest":false,"type":"CREDIT","generic_card_code":"CA","currency":"ARS","excluded_bines":[],"as_fee":false},{"id":"663","card_code":"AX","installments":1,"first_installment_amount":14478,"other_installments_amount":0,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","generic_card_code":"AX","currency":"ARS","excluded_bines":[],"as_fee":false},{"id":"1761","card_code":"AX","installments":6,"first_installment_amount":3084,"other_installments_amount":2662,"total_interest_amount":1915,"with_bank_interest":false,"type":"CREDIT","generic_card_code":"AX","currency":"ARS","excluded_bines":[],"annual_rate":56.99,"as_fee":false},{"id":"1761","card_code":"AX","installments":12,"first_installment_amount":1940,"other_installments_amount":1518,"total_interest_amount":4157,"with_bank_interest":false,"type":"CREDIT","generic_card_code":"AX","currency":"ARS","excluded_bines":[],"annual_rate":64.78,"as_fee":false},{"id":"1919","card_code":"VI_BBVAF","installments":6,"first_installment_amount":2768,"other_installments_amount":2342,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"BBVAF","generic_card_code":"VI","currency":"ARS","excluded_bines":["404510","434617"],"as_fee":false},{"id":"1919","card_code":"VI_BBVAF","installments":12,"first_installment_amount":1597,"other_installments_amount":1171,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"BBVAF","generic_card_code":"VI","currency":"ARS","excluded_bines":["404510","434617"],"as_fee":false},{"id":"1908","card_code":"CA_ICBC","installments":6,"first_installment_amount":2768,"other_installments_amount":2342,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"ICBC","generic_card_code":"CA","currency":"ARS","excluded_bines":["474209","448441","493781","532379","546624","532198","539909","421528","548315","527609","530841","519714","521872"],"as_fee":false},{"id":"1908","card_code":"CA_ICBC","installments":12,"first_installment_amount":1597,"other_installments_amount":1171,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"ICBC","generic_card_code":"CA","currency":"ARS","excluded_bines":["474209","448441","493781","532379","546624","532198","539909","421528","548315","527609","530841","519714","521872"],"as_fee":false},{"id":"1908","card_code":"VI_ICBC","installments":6,"first_installment_amount":2768,"other_installments_amount":2342,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"ICBC","generic_card_code":"VI","currency":"ARS","excluded_bines":["474209","448441","493781","532379","546624","532198","539909","421528","548315","527609","530841","519714","521872"],"as_fee":false},{"id":"1908","card_code":"VI_ICBC","installments":12,"first_installment_amount":1597,"other_installments_amount":1171,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"ICBC","generic_card_code":"VI","currency":"ARS","excluded_bines":["474209","448441","493781","532379","546624","532198","539909","421528","548315","527609","530841","519714","521872"],"as_fee":false},{"id":"1918","card_code":"VI_MAC","installments":6,"first_installment_amount":2768,"other_installments_amount":2342,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"MAC","generic_card_code":"VI","currency":"ARS","excluded_bines":["401722","442186","377777","377778","377779","457073","457074","448730","411011","457075","448729","411010","432959","493717","558814","542744","542755","491956","450972","529206","550218","512533","519767","523793"],"as_fee":false},{"id":"1922","card_code":"AX_MAC","installments":6,"first_installment_amount":2768,"other_installments_amount":2342,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"MAC","generic_card_code":"AX","currency":"ARS","excluded_bines":["401722","442186","377777","377778","377779","457073","457074","448730","411011","457075","448729","411010","432959","493717","558814","542744","542755"],"as_fee":false},{"id":"1919","card_code":"VI_CIU","installments":6,"first_installment_amount":2768,"other_installments_amount":2342,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"CIU","generic_card_code":"VI","currency":"ARS","excluded_bines":["404510","434617"],"as_fee":false},{"id":"1919","card_code":"VI_CIU","installments":12,"first_installment_amount":1597,"other_installments_amount":1171,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"CIU","generic_card_code":"VI","currency":"ARS","excluded_bines":["404510","434617"],"as_fee":false},{"id":"1920","card_code":"VI_SUP","installments":6,"first_installment_amount":2768,"other_installments_amount":2342,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"SUP","generic_card_code":"VI","currency":"ARS","excluded_bines":["536091","410855","410856","410857","433846","433844","455636","455635","455637"],"as_fee":false},{"id":"1920","card_code":"VI_SUP","installments":12,"first_installment_amount":1597,"other_installments_amount":1171,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"SUP","generic_card_code":"VI","currency":"ARS","excluded_bines":["536091","410855","410856","410857","433846","433844","455636","455635","455637"],"as_fee":false},{"id":"1918","card_code":"CA_NBSF","installments":6,"first_installment_amount":2768,"other_installments_amount":2342,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"NBSF","generic_card_code":"CA","currency":"ARS","excluded_bines":["401722","442186","377777","377778","377779","457073","457074","448730","411011","457075","448729","411010","432959","493717","558814","542744","542755","491956","450972","529206","550218","512533","519767","523793"],"as_fee":false},{"id":"1920","card_code":"CA_SUP","installments":6,"first_installment_amount":2768,"other_installments_amount":2342,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"SUP","generic_card_code":"CA","currency":"ARS","excluded_bines":["536091","410855","410856","410857","433846","433844","455636","455635","455637"],"as_fee":false},{"id":"1920","card_code":"CA_SUP","installments":12,"first_installment_amount":1597,"other_installments_amount":1171,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"SUP","generic_card_code":"CA","currency":"ARS","excluded_bines":["536091","410855","410856","410857","433846","433844","455636","455635","455637"],"as_fee":false},{"id":"1738","card_code":"DC_COM","installments":6,"first_installment_amount":2768,"other_installments_amount":2342,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"COM","generic_card_code":"DC","currency":"ARS","excluded_bines":[],"as_fee":false},{"id":"1738","card_code":"DC_COM","installments":12,"first_installment_amount":1597,"other_installments_amount":1171,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"COM","generic_card_code":"DC","currency":"ARS","excluded_bines":[],"as_fee":false},{"id":"1918","card_code":"VI_TUC","installments":6,"first_installment_amount":2768,"other_installments_amount":2342,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"TUC","generic_card_code":"VI","currency":"ARS","excluded_bines":["401722","442186","377777","377778","377779","457073","457074","448730","411011","457075","448729","411010","432959","493717","558814","542744","542755","491956","450972","529206","550218","512533","519767","523793"],"as_fee":false},{"id":"1919","card_code":"VI_NEU","installments":6,"first_installment_amount":2768,"other_installments_amount":2342,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"NEU","generic_card_code":"VI","currency":"ARS","excluded_bines":["404510","434617"],"as_fee":false},{"id":"1919","card_code":"VI_NEU","installments":12,"first_installment_amount":1597,"other_installments_amount":1171,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"NEU","generic_card_code":"VI","currency":"ARS","excluded_bines":["404510","434617"],"as_fee":false},{"id":"1919","card_code":"CA_BBVAF","installments":6,"first_installment_amount":2768,"other_installments_amount":2342,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"BBVAF","generic_card_code":"CA","currency":"ARS","excluded_bines":["404510","434617"],"as_fee":false},{"id":"1919","card_code":"CA_BBVAF","installments":12,"first_installment_amount":1597,"other_installments_amount":1171,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"BBVAF","generic_card_code":"CA","currency":"ARS","excluded_bines":["404510","434617"],"as_fee":false},{"id":"1918","card_code":"VI_NBSF","installments":6,"first_installment_amount":2768,"other_installments_amount":2342,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"NBSF","generic_card_code":"VI","currency":"ARS","excluded_bines":["401722","442186","377777","377778","377779","457073","457074","448730","411011","457075","448729","411010","432959","493717","558814","542744","542755","491956","450972","529206","550218","512533","519767","523793"],"as_fee":false},{"id":"1918","card_code":"CA_CS","installments":6,"first_installment_amount":2768,"other_installments_amount":2342,"total_interest_amount":0,"with_bank_interest":false,"type":"CREDIT","bank_code":"CS","generic_card_code":"CA","currency":"ARS","excluded_bines":["401722","442186","377777","377778","377779","457073","457074","448730","411011","457075","448729","411010","432959","493717","558814","542744","542755","491956","450972","529206","550218","512533","519767","523793"],"as_fee":false}],"max_installments":12,"booking_info":[{"outbound_choice":1,"inbound_choice":1,"itinerary_id":"prism_AR_0_FLIGHTS_A-1_C-0_I-0_RT-BUEMIA20170312-MIABUE20170319_xorigin-api_applyDynaprov-false_channel-travel-agency-api_hvMock-true!0!3TwSfObnYjefaa1d586912210d"}],"passenger_distribution":{"adults":1,"children":0,"infants":0},"commission_info":{"min":0.01,"max":0.01,"total":0.01,"emission_price":{"currency":"ARS","amount":14477.99},"emission_base_price":{"currency":"ARS","amount":11157}},"provider_info":{"price_detail":{"currency":"ARS","total":14056.3,"base":11157,"fees":0,"taxes":2899.3},"tax_details":[{"code":"AR","amount":781},{"code":"XY","amount":112.7},{"code":"US2","amount":579.6},{"code":"XR1","amount":788.9},{"code":"AY","amount":90.2},{"code":"XA","amount":63.8},{"code":"YC","amount":88.6},{"code":"TQ","amount":161},{"code":"QO","amount":161},{"code":"XF","amount":72.5}]},"last_minute":false}', true);
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

        $this->passengersForm = $this->formNewPay->create('passengers', 'form', array('inherit_data' => true, 'allow_extra_fields' => true));
        $this->paymentsForm = $this->formNewPay->create('payments', 'form', array('inherit_data' => true, 'allow_extra_fields' => true));
        $this->contact_infoForm = $this->formNewPay->create('contact_info', 'form', array('inherit_data' => true, 'allow_extra_fields' => true));

        foreach ($booking as $key => $item) {
            if ($key == 'passengers') {
                $this->processPassengers($item);
            } else if ($key == 'payments') {
                $this->processPayments($item);
            } else if ($key == 'contact_info') {
                $this->processContactInfo($item);
            }
        }

        //Agrego campo para la sesión usada en las métricas
        $this->formNewPay->add(
            $this->paymentsForm->add(
                'session_id',
                'hidden',
                array(
                    'required' => true,
                )
            )
        );

        return $this->formNewPay;
    }

    private function processPassengers($passengers)
    {
        $adultQty = $passengers['adults'][0]['metadata']['quantity'];
        $childrenQty = 0;
        for ($i = 1; $i <= $passengers['metadata']['quantity']; $i++) {
            if ($i <= $adultQty) {
                $passenger = $passengers['adults'][0];
                $type = 'adults';
                $count = $i;
            } else {
                $passenger = $passengers['children'][0];
                $type = 'children';
                $childrenQty++;
                $count = $childrenQty;
            }
            foreach ($passenger as $key => $item) {
                if ($key != 'metadata' && $key != 'validations') {
                    switch ($key) {
                        case 'identification':
                            foreach ($item as $key2 => $detail) {
                                if ($key2 != 'metadata' && $key2 != 'validations') {
                                    $this->processFormElement('passengers', $type, $key2, $count, $detail);
                                }
                            }
                            break;
                        default: //first_name, last_name, birthdate, nationality, gender
                            $this->processFormElement('passengers', $type, $key, $count, $item);
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
                        case 'card_code':
                            $this->processFormElement('payments', $key, null, $count, $item);
                            break;
                        default:// installments
                            $this->processFormElement('payments', $key, '', $count, $item);
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
                $name = $subcategory . (($key) ? '-' . $key . $count : $count);
                $this->formNewPay->add(
                    $this->{$groupKey . 'Form'}->add(
                        $name,
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
                            'format' => 'MM-yyyy  d',
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
                        'date', [
                            'widget' => 'single_text',
                            'format' => 'dd-MM-yyyy'
                        ]
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
                            'choices_as_values' => false,
                        )
                    )
                );
                break;
        }
    }

    private function generateChoiceField($optionsArray)
    {
        $optionField = [];
        foreach ($optionsArray as $item) {
            if (array_key_exists($item, $this->resources)) {
                $optionField[$item] = $this->resources[$item];
            }
        }

        return $optionField;
    }

    public function processdVault($formNewPaySend)
    {
        try {
            $params = [
                'brand_code' => $formNewPaySend['card_code0'],
                'number' => str_replace(' ', '', $formNewPaySend['card-number0']),
                'expiration_month' => $formNewPaySend['card-expiration0']->format('m'),
                'expiration_year' => $formNewPaySend['card-expiration0']->format('Y'),
                'security_code' => $formNewPaySend['card-security_code0'],
                'bank' => $formNewPaySend['card_code0'],
                'seconds_to_live' => '600',
                'holder_name' => $formNewPaySend['card-card_holder_name0'],
            ];
            $key = $formNewPaySend['payments']['credit_cards'][0]['card']['token']['metadata']['public_key'];
            $response = $this->despegar->dVaultValidation($key, $params, true);

            if ($response) {
                return $this->despegar->vaultPbdyy($key, $params, true);
            }
        } catch (Exception $exception) {
            return false;
        }
        return false;
    }

    private function fillFormData($dvault, $formData, $booking, $clientIp)
    {
        $providers = [
            [
                "provider" => "threat_metrix",
                "parameters" => [
                    "offshore" => false,
                    "session_id" => $formData['session_id']
                ]
            ]
        ];

        $toCheckout = [
            'risk_analysis' => [
                'additional_evaluators' => $providers,
                'sem_info' => 'string',
                'client_ip' => $clientIp
            ],
            'booking_information' => [
                'payments' => [
                    'credit_cards' => []
                ],
                'contact_info' => [],
                'passengers' => [
                    'adults' => [],
                    'children' => [],
                    'infants' => []
                ],
                'agent_code' => $this->agentCode
            ]
        ];

        //proceso credit cards en payments
        for ($j = 0; $j < count($booking['payments']['credit_cards']); $j++) {
            $card = [
                "card_code" => $formData['card_code' . $j],
                "type" => "CREDIT_CARD",
                "card_type" => "CREDIT",
                "installments" => $formData['installments' . $j],
                "contact_full_name" => $formData['card-card_holder_name' . $j],
                'card_holder_identification' => [
                    'type' => $formData['card_holder_identification-type' . $j],
                    'number' => $formData['card_holder_identification-number' . $j]
                ],
                'invoice' => [
                    'address'=> [
                        'state'=> 'BUE',
                        'city_id'=> $formData['invoice-city_id' . $j],
                        'postal_code'=> $formData['invoice-city_id' . $j],
                        'street'=> $formData['invoice-street' . $j],
                        'number'=> $formData['invoice-number' . $j]
                    ],

                    'fiscal_id' => $formData['invoice-fiscal_id' . $j],
                    'fiscal_status' => $formData['invoice-fiscal_status' . $j],
                    'business_name' => $formData['invoice-business_name' . $j]
                ],
                'card' => [
                    'token' => 'secure://' . $dvault,
                    'type' => 'CREDIT'
                ]
            ];

            $toCheckout['booking_information']['payments']['credit_cards'][] = $card;
        }

        //proceso de contact_info
        $contactInfo = [];
        foreach ($booking['contact_info'] as $key => $data) {
            if ($key != 'metadata') {
                if ($key == 'email') {
                    $contactInfo['email'] = $formData['email-'];
                }
                for ($j = 0; $j < count($booking['contact_info']['phones']); $j++) {
                    if ($key == 'phones') {
                        for ($i = 0; $i < count($data); $i++) {
                            $phones = [
                                "number" => $formData['phones-number' . $j],
                                "country_code" => str_replace('+', '', $formData['phones-country_code' . $j]),
                                "area_code" => $formData['phones-area_code' . $j],
                                "type" => $formData['phones-type' . $j]
                            ];
                            $contactInfo['phones'][] = $phones;
                        }
                    }
                }
                $contactInfo['accept_offers'] = false;//TODO: poner campo en formulario
            }
        }
        $toCheckout['booking_information']['contact_info'] = $contactInfo;

        //proceso de passengers
        $j = 0;
        $adultCount = $booking['passengers']['adults'][0]['metadata']['quantity'];
        $childCount = (isset($booking['passengers']['children'][0]['metadata']['quantity']) ? $booking['passengers']['children'][0]['metadata']['quantity'] : 0);
        $type = 'ADULT';
        $typeField = 'adults';
        for ($i = 1; $i <= $booking['passengers']['metadata']['quantity']; $i++) {
            if ($childCount > 0 && $j >= $childCount) {
                $j = ($j == $adultCount) ? 1 : $j + 1;
                $type = 'CHILDREN';
                $typeField = 'children';
            } else {
                $j += 1;
            }
            $passenger = [
                "identification" => [
                    "number" => $formData[$typeField . '-number' . $j],
                    "issuing_country" => "AR",
                    "type" => $formData[$typeField . '-type' . $j]
                ],
                "type" => $type,
                "first_name" => $formData[$typeField . '-first_name' . $j],
                "last_name" => $formData[$typeField . '-last_name' . $j],
                "birthdate" => $formData[$typeField . '-birthdate' . $j]->format('Y-m-d'),
                "gender" => $formData[$typeField . '-gender' . $j],
                "nationality" => "AR"
            ];
            $toCheckout['booking_information']['passengers'][$typeField][] = $passenger;
        }

        return $toCheckout;
    }

    public function processReservation($dvault, $formData, $booking, $clientIp, $params)
    {
        $fillData = $this->fillFormData($dvault, $formData, $booking, $clientIp);

        $urlParams = [
            'itinerary_id' => $params['item_id'],
            'outbound' => $params['outbound'],
            'language' => 'es',
            'country' => 'AR',
            'product_type' => 'FLIGHT'
        ];
        if (isset($params['inbound'])) {
            $urlParams['inbound'] = $params['inbound'];
        }

        $bookingInfo = $this->despegar->postFlightBookings($fillData, $urlParams);

        if ($bookingInfo) {
            //TODO: guardar los datos de la reserva y pasajeros
            $reservation = new FlightReservation();
//            $reservation->setFlightId();
//            $reservation->setReservationId();
//            $reservation->setTotalPrice();
//            $reservation->setCardType();
//            $reservation->setHolderName();
//            $reservation->setPhoneNumber();
//            $reservation->setEmail();
//            $reservation->setInbound();
//            $reservation->setOutbound();
            $reservation->setCreated(new \DateTime());
            $this->em->persist($reservation);

            foreach ($fillData['booking_information']['passengers'] as $key => $value) {
                foreach ($value as $key2 => $data) {
                    $passenger = new FlightPassengers();
                    $passenger->setName($data['first_name']);
                    $passenger->setLastName($data['last_name']);
                    $passenger->setDocument('');
                    $passenger->setBirthdate(new \DateTime($data['birthdate']));
                    $passenger->setGender($data['gender']);
                    $passenger->setFlightReservation($reservation);
                    $this->em->persist($passenger);
                }
            }
            $this->em->flush();

            //TODO: de ser necesario, traer los datos del vuelo y reserva

            //envío de correo
            try {
                //TODO: cambiar por el método de vuelos
                if ($fillData['email-']) {
                    $this->get('email.service')->sendBookingFlightEmail($fillData['email-'], array(
                        'pdf' => false
                    ));
                }
            } catch (\Exception $e) {
                $this->get('logger')->error('Booking Flight email error');
            }

            return true;
        } else {
            return false;
        }
    }

    public function flightsItineraries()
    {

    }
}