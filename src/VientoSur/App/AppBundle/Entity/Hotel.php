<?php

namespace VientoSur\App\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use VientoSur\App\AppBundle\Entity\Traits\TimestampableTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var integer
     * @Assert\NotBlank()
     * @ORM\Column(name="stars", type="integer", nullable=true)
     */
    private $stars;

    /**
     * @var string
     *
     * @ORM\Column(name="origen", type="string", length=255, nullable=true)
     */
    private $origen;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="latitude", type="string", length=255)
     */
    private $latitude;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="longitude", type="string", length=255)
     */
    private $longitude;

    /**
     * @var float
     *
     * @ORM\Column(name="percentage_gain", type="decimal", precision=5, scale=2)
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      minMessage = "Min % is 0",
     *      maxMessage = "Max % is 100"
     * )
     */
    private $percentageGain;

    /**
     * @ORM\ManyToOne(targetEntity="HotelType", inversedBy="hotels")
     * @ORM\JoinColumn(name="hotel_types", referencedColumnName="id")
     */
    protected $hotelTypes;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    protected $created_by;

    /**
     * @ORM\ManyToOne(targetEntity="HotelChain", inversedBy="hotels")
     * @ORM\JoinColumn(name="hotel_chain", referencedColumnName="id")
     */
    protected $hotelChain;

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

    /**
     * Set created_by
     *
     * @param \VientoSur\App\AppBundle\Entity\User $createdBy
     * @return Hotel
     */
    public function setCreatedBy(\VientoSur\App\AppBundle\Entity\User $createdBy = null)
    {
        $this->created_by = $createdBy;

        return $this;
    }

    /**
     * Get created_by
     *
     * @return \VientoSur\App\AppBundle\Entity\User 
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    public function __toString() {
        return $this->name;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Hotel
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set hotelChain
     *
     * @param \VientoSur\App\AppBundle\Entity\HotelChain $hotelChain
     *
     * @return Hotel
     */
    public function setHotelChain(\VientoSur\App\AppBundle\Entity\HotelChain $hotelChain = null)
    {
        $this->hotelChain = $hotelChain;

        return $this;
    }

    /**
     * Get hotelChain
     *
     * @return \VientoSur\App\AppBundle\Entity\HotelChain
     */
    public function getHotelChain()
    {
        return $this->hotelChain;
    }
}
