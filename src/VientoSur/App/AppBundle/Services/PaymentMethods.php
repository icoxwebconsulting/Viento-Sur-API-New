<?php


namespace VientoSur\App\AppBundle\Services;


class PaymentMethods
{
    private $testMethods;

    public function __construct()
    {
        $this->testMethods = json_decode('{"payment_methods": [{
          "choice": "7",
          "type": "credit_card",
          "amounts": {
            "currency": "USD",
            "total": 209.45,
            "installment": 209.45,
            "total_interest_amount": 0,
            "discount": 0
          },
          "card_ids": [
            "AR-VI-*-CREDIT",
            "AR-CA-*-CREDIT",
            "AR-AX-*-CREDIT",
            "AR-DC-*-CREDIT",
            "AR-CL-*-CREDIT",
            "AR-TN-*-CREDIT",
            "AR-NV-*-CREDIT"
          ],
          "installment_quantity": 1,
          "with_bank_interest": false
        },
        {
          "choice": "10",
          "type": "credit_card",
          "amounts": {
            "currency": "USD",
            "total": 209.45,
            "installment": 69.82,
            "total_interest_amount": 0,
            "discount": 0
          },
          "card_ids": [
            "AR-VI-RIO-CREDIT",
            "AR-AX-RIO-CREDIT",
            "AR-VI-MAC-CREDIT",
            "AR-AX-MAC-CREDIT",
            "AR-CA-MAC-CREDIT",
            "AR-VI-HIP-CREDIT",
            "AR-CA-ITU-CREDIT",
            "AR-VI-ITU-CREDIT",
            "AR-VI-ICBC-CREDIT",
            "AR-VI-PRO-CREDIT",
            "AR-VI-PAT-CREDIT",
            "AR-AX-PAT-CREDIT",
            "AR-CA-PAT-CREDIT",
            "AR-VI-SUP-CREDIT",
            "AR-CA-SUP-CREDIT",
            "AR-VI-CIU-CREDIT",
            "AR-CA-CIU-CREDIT",
            "AR-DC-*-CREDIT",
            "AR-CA-CS-CREDIT",
            "AR-VI-COM-CREDIT",
            "AR-CA-COM-CREDIT",
            "AR-VI-AZU-CREDIT",
            "AR-CA-BERSA-CREDIT",
            "AR-VI-BERSA-CREDIT",
            "AR-VI-BLP-CREDIT",
            "AR-CL-CHA-CREDIT",
            "AR-VI-CHA-CREDIT",
            "AR-CA-NBSF-CREDIT",
            "AR-VI-NBSF-CREDIT",
            "AR-VI-SAENZ-CREDIT",
            "AR-CA-SCR-CREDIT",
            "AR-VI-SCR-CREDIT",
            "AR-CA-SJU-CREDIT",
            "AR-VI-SJU-CREDIT",
            "AR-VI-TUC-CREDIT",
            "AR-CS-*-CREDIT",
            "AR-VI-BTDF-CREDIT",
            "AR-CA-BLP-CREDIT",
            "AR-CA-CHA-CREDIT",
            "AR-CA-TUC-CREDIT",
            "AR-VI-SHO-CREDIT",
            "AR-VI-PVC-CREDIT",
            "AR-CA-AZU-CREDIT",
            "AR-VIS-BCO-CREDIT"
          ],
          "installment_quantity": 3,
          "with_bank_interest": false
        },
        {
          "choice": "6",
          "type": "credit_card",
          "amounts": {
            "currency": "USD",
            "total": 209.45,
            "installment": 34.91,
            "total_interest_amount": 0,
            "discount": 0
          },
          "card_ids": [
            "AR-VI-RIO-CREDIT",
            "AR-AX-RIO-CREDIT",
            "AR-VI-MAC-CREDIT",
            "AR-AX-MAC-CREDIT",
            "AR-CA-MAC-CREDIT",
            "AR-VI-HIP-CREDIT",
            "AR-CA-ITU-CREDIT",
            "AR-VI-ITU-CREDIT",
            "AR-VI-ICBC-CREDIT",
            "AR-VI-PRO-CREDIT",
            "AR-VI-PAT-CREDIT",
            "AR-AX-PAT-CREDIT",
            "AR-CA-PAT-CREDIT",
            "AR-VI-SUP-CREDIT",
            "AR-CA-SUP-CREDIT",
            "AR-VI-CIU-CREDIT",
            "AR-CA-CIU-CREDIT",
            "AR-DC-*-CREDIT",
            "AR-CA-CS-CREDIT",
            "AR-VI-COM-CREDIT",
            "AR-CA-COM-CREDIT",
            "AR-VI-AZU-CREDIT",
            "AR-CA-BERSA-CREDIT",
            "AR-VI-BERSA-CREDIT",
            "AR-VI-BLP-CREDIT",
            "AR-CL-CHA-CREDIT",
            "AR-VI-CHA-CREDIT",
            "AR-CA-NBSF-CREDIT",
            "AR-VI-NBSF-CREDIT",
            "AR-VI-SAENZ-CREDIT",
            "AR-CA-SCR-CREDIT",
            "AR-VI-SCR-CREDIT",
            "AR-CA-SJU-CREDIT",
            "AR-VI-SJU-CREDIT",
            "AR-VI-TUC-CREDIT",
            "AR-CS-*-CREDIT",
            "AR-VI-BTDF-CREDIT",
            "AR-CA-BLP-CREDIT",
            "AR-CA-CHA-CREDIT",
            "AR-CA-TUC-CREDIT",
            "AR-VI-SHO-CREDIT",
            "AR-VI-PVC-CREDIT",
            "AR-CA-AZU-CREDIT",
            "AR-VIS-BCO-CREDIT"
          ],
          "installment_quantity": 6,
          "with_bank_interest": false
        },
        {
          "choice": "13",
          "type": "credit_card",
          "amounts": {
            "currency": "USD",
            "total": 209.45,
            "installment": 23.27,
            "total_interest_amount": 0,
            "discount": 0
          },
          "card_ids": [
            "AR-VI-PRO-CREDIT",
            "AR-VI-SUP-CREDIT",
            "AR-CA-SUP-CREDIT",
            "AR-DC-*-CREDIT",
            "AR-VI-COM-CREDIT",
            "AR-CA-COM-CREDIT",
            "AR-VI-BLP-CREDIT",
            "AR-CL-CHA-CREDIT",
            "AR-VI-CHA-CREDIT",
            "AR-CA-BLP-CREDIT",
            "AR-CA-CHA-CREDIT"
          ],
          "installment_quantity": 9,
          "with_bank_interest": false
        },
        {
          "choice": "5",
          "type": "credit_card",
          "amounts": {
            "currency": "USD",
            "total": 209.45,
            "installment": 17.45,
            "total_interest_amount": 0,
            "discount": 0
          },
          "card_ids": [
            "AR-VI-ICBC-CREDIT",
            "AR-VI-PRO-CREDIT",
            "AR-VI-SUP-CREDIT",
            "AR-CA-SUP-CREDIT",
            "AR-VI-CIU-CREDIT",
            "AR-CA-CIU-CREDIT",
            "AR-DC-*-CREDIT",
            "AR-VI-COM-CREDIT",
            "AR-CA-COM-CREDIT",
            "AR-VI-AZU-CREDIT",
            "AR-VI-BLP-CREDIT",
            "AR-CL-CHA-CREDIT",
            "AR-VI-CHA-CREDIT",
            "AR-VI-ROS-CREDIT",
            "AR-VI-SAENZ-CREDIT",
            "AR-CA-BLP-CREDIT",
            "AR-CA-CHA-CREDIT",
            "AR-VI-PVC-CREDIT",
            "AR-CA-AZU-CREDIT",
            "AR-VIS-BCO-CREDIT"
          ],
          "installment_quantity": 12,
          "with_bank_interest": false
        },
        {
          "choice": "3",
          "type": "credit_card",
          "amounts": {
            "currency": "USD",
            "total": 228.2,
            "installment": 76.07,
            "total_interest_amount": 18.75,
            "discount": 0
          },
          "card_ids": [
            "AR-VI-*-CREDIT",
            "AR-CA-*-CREDIT",
            "AR-CL-*-CREDIT",
            "AR-CA-HSBC-CREDIT",
            "AR-VI-HSBC-CREDIT"
          ],
          "installment_quantity": 3,
          "with_bank_interest": false
        },
        {
          "choice": "11",
          "type": "credit_card",
          "amounts": {
            "currency": "USD",
            "total": 228.76,
            "installment": 76.25,
            "total_interest_amount": 19.31,
            "discount": 0
          },
          "card_ids": [
            "AR-AX-*-CREDIT"
          ],
          "installment_quantity": 3,
          "with_bank_interest": false
        },
        {
          "choice": "2",
          "type": "credit_card",
          "amounts": {
            "currency": "USD",
            "total": 243.17,
            "installment": 40.53,
            "total_interest_amount": 33.72,
            "discount": 0
          },
          "card_ids": [
            "AR-VI-*-CREDIT",
            "AR-CA-*-CREDIT",
            "AR-CL-*-CREDIT",
            "AR-CA-HSBC-CREDIT",
            "AR-VI-HSBC-CREDIT"
          ],
          "installment_quantity": 6,
          "with_bank_interest": false
        },
        {
          "choice": "8",
          "type": "credit_card",
          "amounts": {
            "currency": "USD",
            "total": 244.22,
            "installment": 40.7,
            "total_interest_amount": 34.77,
            "discount": 0
          },
          "card_ids": [
            "AR-AX-*-CREDIT"
          ],
          "installment_quantity": 6,
          "with_bank_interest": false
        },
        {
          "choice": "9",
          "type": "credit_card",
          "amounts": {
            "currency": "USD",
            "total": 280.98,
            "installment": 23.41,
            "total_interest_amount": 71.53,
            "discount": 0
          },
          "card_ids": [
            "AR-VI-*-CREDIT",
            "AR-CA-*-CREDIT",
            "AR-CL-*-CREDIT",
            "AR-CA-HSBC-CREDIT",
            "AR-VI-HSBC-CREDIT"
          ],
          "installment_quantity": 12,
          "with_bank_interest": false
        },
        {
          "choice": "1",
          "type": "credit_card",
          "amounts": {
            "currency": "USD",
            "total": 284.75,
            "installment": 23.73,
            "total_interest_amount": 75.3,
            "discount": 0
          },
          "card_ids": [
            "AR-AX-*-CREDIT"
          ],
          "installment_quantity": 12,
          "with_bank_interest": false
        },
        {
          "choice": "4",
          "type": "credit_card",
          "amounts": {
            "currency": "USD",
            "total": 318.05,
            "installment": 17.67,
            "total_interest_amount": 108.6,
            "discount": 0
          },
          "card_ids": [
            "AR-VI-*-CREDIT",
            "AR-CA-*-CREDIT",
            "AR-CL-*-CREDIT",
            "AR-CA-HSBC-CREDIT",
            "AR-VI-HSBC-CREDIT"
          ],
          "installment_quantity": 18,
          "with_bank_interest": false
        },
        {
          "choice": "12",
          "type": "credit_card",
          "amounts": {
            "currency": "USD",
            "total": 323.92,
            "installment": 18,
            "total_interest_amount": 114.47,
            "discount": 0
          },
          "card_ids": [
            "AR-AX-*-CREDIT"
          ],
          "installment_quantity": 18,
          "with_bank_interest": false
        }
      ]}')->payment_methods;
    }

    public function getTestMethods()
    {
        return $this->testMethods;
    }
}