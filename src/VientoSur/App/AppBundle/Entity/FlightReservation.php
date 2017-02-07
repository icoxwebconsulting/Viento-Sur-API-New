<?php

namespace VientoSur\App\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="flight_reservation")
 * @ORM\Entity
 */
class FlightReservation
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="flight_id", type="integer", nullable=true)
     */
    private $flightId;

    /**
     * @ORM\Column(name="reservation_id", type="string", nullable=true)
     */
    private $reservationId;

    /**
     * @ORM\Column(name="total_price", type="float", nullable=true)
     */
    private $totalPrice;

    /**
     * @ORM\Column(name="card_type", type="string", length=100, nullable=true)
     */
    private $cardType;

    /**
     * @ORM\Column(name="holder_name", type="string", length=255, nullable=true)
     */
    private $holderName;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=100, nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @ORM\Column(name="inbound", type="datetime", nullable=true)
     */
    private $inbound;

    /**
     * @ORM\Column(name="outbound", type="datetime", nullable=true)
     */
    private $outbound;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set flightId
     *
     * @param integer $flightId
     * @return FlightReservation
     */
    public function setFlightId($flightId)
    {
        $this->flightId = $flightId;

        return $this;
    }

    /**
     * Get flightId
     *
     * @return integer 
     */
    public function getFlightId()
    {
        return $this->flightId;
    }

    /**
     * Set reservationId
     *
     * @param string $reservationId
     * @return FlightReservation
     */
    public function setReservationId($reservationId)
    {
        $this->reservationId = $reservationId;

        return $this;
    }

    /**
     * Get reservationId
     *
     * @return string 
     */
    public function getReservationId()
    {
        return $this->reservationId;
    }

    /**
     * Set totalPrice
     *
     * @param float $totalPrice
     * @return FlightReservation
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * Get totalPrice
     *
     * @return float 
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * Set cardType
     *
     * @param string $cardType
     * @return FlightReservation
     */
    public function setCardType($cardType)
    {
        $this->cardType = $cardType;

        return $this;
    }

    /**
     * Get cardType
     *
     * @return string 
     */
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * Set holderName
     *
     * @param string $holderName
     * @return FlightReservation
     */
    public function setHolderName($holderName)
    {
        $this->holderName = $holderName;

        return $this;
    }

    /**
     * Get holderName
     *
     * @return string 
     */
    public function getHolderName()
    {
        return $this->holderName;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     * @return FlightReservation
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string 
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return FlightReservation
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return FlightReservation
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set inbound
     *
     * @param \DateTime $inbound
     * @return FlightReservation
     */
    public function setInbound($inbound)
    {
        $this->inbound = $inbound;

        return $this;
    }

    /**
     * Get inbound
     *
     * @return \DateTime 
     */
    public function getInbound()
    {
        return $this->inbound;
    }

    /**
     * Set outbound
     *
     * @param \DateTime $outbound
     * @return FlightReservation
     */
    public function setOutbound($outbound)
    {
        $this->outbound = $outbound;

        return $this;
    }

    /**
     * Get outbound
     *
     * @return \DateTime 
     */
    public function getOutbound()
    {
        return $this->outbound;
    }
}
