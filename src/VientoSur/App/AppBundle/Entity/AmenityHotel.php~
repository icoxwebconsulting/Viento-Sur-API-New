<?php

namespace VientoSur\App\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="amenity_hotels")
 */
class AmenityHotel
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\ManyToOne(targetEntity="Amenity", inversedBy="amenity_hotels")
     * @ORM\JoinColumn(name="amenity", referencedColumnName="id")
     */
    protected $amenity;

    /**
     * @ORM\ManyToOne(targetEntity="Hotel", inversedBy="amenity_hotels")
     * @ORM\JoinColumn(name="hotel", referencedColumnName="id")
     */
    protected $hotel;

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
     * Set amenity
     *
     * @param \VientoSur\App\AppBundle\Entity\Amenity $amenity
     * @return AmenityHotel
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
     * Set hotel
     *
     * @param \VientoSur\App\AppBundle\Entity\Hotel $hotel
     * @return AmenityHotel
     */
    public function setHotel(\VientoSur\App\AppBundle\Entity\Hotel $hotel = null)
    {
        $this->hotel = $hotel;

        return $this;
    }

    /**
     * Get hotel
     *
     * @return \VientoSur\App\AppBundle\Entity\Hotel 
     */
    public function getHotel()
    {
        return $this->hotel;
    }
}
