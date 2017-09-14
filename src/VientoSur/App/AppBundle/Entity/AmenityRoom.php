<?php

namespace VientoSur\App\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="amenity_rooms")
 */
class AmenityRoom
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Amenity", inversedBy="amenity_rooms")
     * @ORM\JoinColumn(name="amenity", referencedColumnName="id")
     */
    protected $amenity;

    /**
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="amenity_rooms")
     * @ORM\JoinColumn(name="room", referencedColumnName="id")
     */
    protected $room;

    /**
     * @ORM\Column(name="price", type="float")
     */
    protected $price;

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
     * Set price
     *
     * @param float $price
     * @return AmenityRoom
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set amenity
     *
     * @param \VientoSur\App\AppBundle\Entity\Amenity $amenity
     * @return AmenityRoom
     */
    public function setAmenity(\VientoSur\App\AppBundle\Entity\Amenity $amenity = null)
    {
        $this->amenity = $amenity;

        return $this;
    }

    /**
     * Get amenity
     *
     * @return \VientoSur\App\AppBundle\Entity\Amenity 
     */
    public function getAmenity()
    {
        return $this->amenity;
    }

    /**
     * Set room
     *
     * @param \VientoSur\App\AppBundle\Entity\Room $room
     * @return AmenityRoom
     */
    public function setRoom(\VientoSur\App\AppBundle\Entity\Room $room = null)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * Get room
     *
     * @return \VientoSur\App\AppBundle\Entity\Room 
     */
    public function getRoom()
    {
        return $this->room;
    }
}
