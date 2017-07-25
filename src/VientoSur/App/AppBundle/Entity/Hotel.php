<?php

namespace VientoSur\App\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use VientoSur\App\AppBundle\Entity\Traits\TimestampableTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * Hotel
 *
 * @ORM\Table(name="hotels")
 * @ORM\Entity(repositoryClass="VientoSur\App\AppBundle\Repository\HotelRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Hotel
{
    use TimestampableTrait;

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
     * @Gedmo\Translatable
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="stars", type="integer")
     */
    private $stars;

    /**
     * @var string
     *
     * @ORM\Column(name="origen", type="string", length=255)
     */
    private $origen;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=255)
     */
    private $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=255)
     */
    private $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="percentage_gain", type="float")
     */
    private $percentageGain;

    /**
     * @ORM\ManyToMany(targetEntity="Amenity")
     * @ORM\JoinTable(name="amenity_hotels",
     *     joinColumns={@ORM\JoinColumn(name="hotel_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="amenity_id", referencedColumnName="id")}
     * )
     */
    protected $amenityHotels;

    /**
     * @ORM\ManyToOne(targetEntity="HotelType", inversedBy="hotels")
     * @ORM\JoinColumn(name="hotel_types", referencedColumnName="id")
     */
    protected $hotelTypes;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale;

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
     * @return Hotel
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
     * Set stars
     *
     * @param integer $stars
     * @return Hotel
     */
    public function setStars($stars)
    {
        $this->stars = $stars;

        return $this;
    }

    /**
     * Get stars
     *
     * @return integer 
     */
    public function getStars()
    {
        return $this->stars;
    }

    /**
     * Set origen
     *
     * @param string $origen
     * @return Hotel
     */
    public function setOrigen($origen)
    {
        $this->origen = $origen;

        return $this;
    }

    /**
     * Get origen
     *
     * @return string 
     */
    public function getOrigen()
    {
        return $this->origen;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Hotel
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Hotel
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set percentageGain
     *
     * @param float $percentageGain
     * @return Hotel
     */
    public function setPercentageGain($percentageGain)
    {
        $this->percentageGain = $percentageGain;

        return $this;
    }

    /**
     * Get percentageGain
     *
     * @return float 
     */
    public function getPercentageGain()
    {
        return $this->percentageGain;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->amenityHotels = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add amenityHotels
     *
     * @param \VientoSur\App\AppBundle\Entity\Amenity $amenityHotels
     * @return Hotel
     */
    public function addAmenityHotel(\VientoSur\App\AppBundle\Entity\Amenity $amenityHotels)
    {
        $this->amenityHotels[] = $amenityHotels;

        return $this;
    }

    /**
     * Remove amenityHotels
     *
     * @param \VientoSur\App\AppBundle\Entity\Amenity $amenityHotels
     */
    public function removeAmenityHotel(\VientoSur\App\AppBundle\Entity\Amenity $amenityHotels)
    {
        $this->amenityHotels->removeElement($amenityHotels);
    }

    /**
     * Get amenityHotels
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAmenityHotels()
    {
        return $this->amenityHotels;
    }

    /**
     * Set hotelTypes
     *
     * @param \VientoSur\App\AppBundle\Entity\HotelType $hotelTypes
     * @return Hotel
     */
    public function setHotelTypes(\VientoSur\App\AppBundle\Entity\HotelType $hotelTypes = null)
    {
        $this->hotelTypes = $hotelTypes;

        return $this;
    }

    /**
     * Get hotelTypes
     *
     * @return \VientoSur\App\AppBundle\Entity\HotelType 
     */
    public function getHotelTypes()
    {
        return $this->hotelTypes;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Hotel
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
}
