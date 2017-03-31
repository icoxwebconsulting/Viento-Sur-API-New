<?php


namespace VientoSur\App\AppBundle\Utils;

class Card
{
    private $cards = [
        'VI' => 'Visa',
        'CA' => 'MasterCard',
        'AX' => 'American Express',
        'CAB' => 'MasterCard Black',
        'CL' => 'Cabal',
        'CM' => 'Argencard',
        'CS' => 'Cencosud',
        'DC' => 'Diners Club',
        'NT' => 'Nativa',
        'NV' => 'Tarjeta Nevada',
        'NVP' => 'Nevada - Nevaplan',
        'SHO' => 'Tarjeta shopping',
        'TN' => 'Tarjeta Naranja',
        'TNZ' => 'Tarjeta Naranja Plan Z',
        'VD' => 'Visa Débito',
        'VIS' => 'Visa Signature',
        'CB' => 'Carte Blanche',
        'EC' => 'Elo',
        'HC' => 'Hipercard',
        'MD' => 'MasterCard Débito',
        'VB' => 'Visa Electron',
        'MG' => 'Magna',
        'PR' => 'Presto',
        'CF' => 'Alia Solidario Cuotafácil',
        'DIS' => 'Discover',
        'AFIRM' => 'Tarjeta Banco Afirme',
        'BAJIO' => 'Tarjeta Bajio',
        'BANJE' => 'Tarjeta Banco Banjercito',
        'BANOR' => 'Tarjeta Banco Banorte',
        'BANRE' => 'Tarjeta Banco Banregio',
        'FAMSA' => 'Tarjeta Famsa',
        'HSBC' => 'Tarjeta Banco HSBC',
        'INBUR' => 'Tarjeta Banco Inbursa',
        'INVEX' => 'Tarjeta Invex',
        'ITAU' => 'Tarjeta Banco Itau',
        'MIFEL' => 'Tarjeta Mifel',
        'SAN' => 'Tarjeta Banco Santander',
        'SB' => 'Tarjeta Scotiabank',
        'OH' => 'OH',
    ];

    public function getCards()
    {
        return $this->cards;
    }
}