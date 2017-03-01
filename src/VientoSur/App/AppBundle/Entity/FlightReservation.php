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
     * @ORM\Column(name="itinerary_id", type="string", length=255, nullable=false)
     */
    private $itineraryId;

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
     * @var string
     *
     * @ORM\Column(name="origin", type="string", length=50, nullable=false)
     */
    private $origin;

    /**
     * @var string
     *
     * @ORM\Column(name="destination", type="string", length=50, nullable=false)
     */
    private $destination;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;


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
     * Set itineraryId
     *
     * @param string $itineraryId
     * @return FlightReservation
     */
    public function setItineraryId($itineraryId)
    {
        $this->itineraryId = $itineraryId;

        return $this;
    }

    /**
     * Get itineraryId
     *
     * @return string 
     */
    public function getItineraryId()
    {
        return $this->itineraryId;
    }

    /**
     * Set origin
     *
     * @param string $origin
     * @return FlightReservation
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get origin
     *
     * @return string 
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Set destination
     *
     * @param string $destination
     * @return FlightReservation
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * Get destination
     *
     * @return string 
     */
    public function getDestination()
    {
        return $this->destination;
    }
}
