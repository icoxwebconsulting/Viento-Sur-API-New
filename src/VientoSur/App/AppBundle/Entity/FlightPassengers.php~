<?php

namespace VientoSur\App\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FlightPassengers
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class FlightPassengers
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="document", type="string", length=100)
     */
    private $document;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="datetimetz")
     */
    private $birthdate;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=100)
     */
    private $gender;

    /**
     * @ORM\ManyToOne(targetEntity="FlightReservation")
     * @ORM\JoinColumn(name="flight_reservation", referencedColumnName="id")
     */
    protected $flight_reservation;

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
     * Set name
     *
     * @param string $name
     * @return FlightPassengers
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return FlightPassengers
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set document
     *
     * @param string $document
     * @return FlightPassengers
     */
    public function setDocument($document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document
     *
     * @return string 
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     * @return FlightPassengers
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime 
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return FlightPassengers
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set flight_reservation
     *
     * @param \VientoSur\App\AppBundle\Entity\FlightReservation $flightReservation
     * @return FlightPassengers
     */
    public function setFlightReservation(\VientoSur\App\AppBundle\Entity\FlightReservation $flightReservation = null)
    {
        $this->flight_reservation = $flightReservation;

        return $this;
    }

    /**
     * Get flight_reservation
     *
     * @return \VientoSur\App\AppBundle\Entity\FlightReservation 
     */
    public function getFlightReservation()
    {
        return $this->flight_reservation;
    }
}
