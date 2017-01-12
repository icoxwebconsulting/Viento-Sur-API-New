<?php

namespace VientoSur\App\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AirlineAlliance
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class AirlineAlliance
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
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * Many Alliances have One Airline.
     * @ORM\ManyToOne(targetEntity="Airlines", inversedBy="alliance")
     * @ORM\JoinColumn(name="airline_id", referencedColumnName="id")
     */
    private $airline;

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return AirlineAlliance
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
     * Set airline
     *
     * @param \VientoSur\App\AppBundle\Entity\Airlines $airline
     * @return AirlineAlliance
     */
    public function setAirline(\VientoSur\App\AppBundle\Entity\Airlines $airline = null)
    {
        $this->airline = $airline;

        return $this;
    }

    /**
     * Get airline
     *
     * @return \VientoSur\App\AppBundle\Entity\Airlines 
     */
    public function getAirline()
    {
        return $this->airline;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return AirlineAlliance
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }
}
