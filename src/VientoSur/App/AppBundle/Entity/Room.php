<?php

namespace VientoSur\App\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use VientoSur\App\AppBundle\Entity\Traits\TimestampableTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Room
 *
 * @ORM\Table(name="rooms")
 * @ORM\Entity(repositoryClass="VientoSur\App\AppBundle\Repository\RoomRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Room
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
     *
     * @ORM\Column(name="room_code", type="string", length=255)
     */
    private $roomCode;

    /**
     * @var integer
     * @Assert\NotBlank()
     * @ORM\Column(name="availability", type="integer")
     */
    private $availability;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="capacity", type="integer")
     */
    private $capacity;

    /**
     * @var float
     * @Assert\NotBlank()
     * @ORM\Column(name="nightly_price", type="float")
     */
    private $nightlyPrice;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="cancellation_policity", type="string", length=255)
     */
    private $cancellationPolicity;

    /**
     * @ORM\ManyToOne(targetEntity="PaymentType", inversedBy="rooms")
     * @ORM\JoinColumn(name="payment_type", referencedColumnName="id")
     */
    protected $paymentType;

    /**
     * @ORM\ManyToOne(targetEntity="MealPlan", inversedBy="rooms")
     * @ORM\JoinColumn(name="meal_plan", referencedColumnName="id")
     */
    protected $mealPlan;

    /**
     * @ORM\ManyToOne(targetEntity="Hotel", inversedBy="rooms")
     * @ORM\JoinColumn(name="hotel  ", referencedColumnName="id")
     */
    protected $hotel;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    protected $created_by;

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
     * @return Room
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
     * Set roomCode
     *
     * @param string $roomCode
     * @return Room
     */
    public function setRoomCode($roomCode)
    {
        $this->roomCode = $roomCode;

        return $this;
    }

    /**
     * Get roomCode
     *
     * @return string 
     */
    public function getRoomCode()
    {
        return $this->roomCode;
    }

    /**
     * Set availability
     *
     * @param integer $availability
     * @return Room
     */
    public function setAvailability($availability)
    {
        $this->availability = $availability;

        return $this;
    }

    /**
     * Get availability
     *
     * @return integer 
     */
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * Set capacity
     *
     * @param string $capacity
     * @return Room
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * Get capacity
     *
     * @return string 
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * Set nightlyPrice
     *
     * @param float $nightlyPrice
     * @return Room
     */
    public function setNightlyPrice($nightlyPrice)
    {
        $this->nightlyPrice = $nightlyPrice;

        return $this;
    }

    /**
     * Get nightlyPrice
     *
     * @return float 
     */
    public function getNightlyPrice()
    {
        return $this->nightlyPrice;
    }

    /**
     * Set cancellationPolicity
     *
     * @param string $cancellationPolicity
     * @return Room
     */
    public function setCancellationPolicity($cancellationPolicity)
    {
        $this->cancellationPolicity = $cancellationPolicity;

        return $this;
    }

    /**
     * Get cancellationPolicity
     *
     * @return string 
     */
    public function getCancellationPolicity()
    {
        return $this->cancellationPolicity;
    }

    /**
     * Set paymentType
     *
     * @param \VientoSur\App\AppBundle\Entity\PaymentType $paymentType
     * @return Room
     */
    public function setPaymentType(\VientoSur\App\AppBundle\Entity\PaymentType $paymentType = null)
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    /**
     * Get paymentType
     *
     * @return \VientoSur\App\AppBundle\Entity\PaymentType 
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * Set mealPlan
     *
     * @param \VientoSur\App\AppBundle\Entity\MealPlan $mealPlan
     * @return Room
     */
    public function setMealPlan(\VientoSur\App\AppBundle\Entity\MealPlan $mealPlan = null)
    {
        $this->mealPlan = $mealPlan;

        return $this;
    }

    /**
     * Get mealPlan
     *
     * @return \VientoSur\App\AppBundle\Entity\MealPlan 
     */
    public function getMealPlan()
    {
        return $this->mealPlan;
    }

    /**
     * Set hotels
     *
     * @param \VientoSur\App\AppBundle\Entity\Hotel $hotels
     * @return Room
     */
    public function setHotels(\VientoSur\App\AppBundle\Entity\Hotel $hotels = null)
    {
        $this->hotels = $hotels;

        return $this;
    }

    /**
     * Get hotels
     *
     * @return \VientoSur\App\AppBundle\Entity\Hotel 
     */
    public function getHotels()
    {
        return $this->hotels;
    }

    /**
     * Set hotel
     *
     * @param \VientoSur\App\AppBundle\Entity\Hotel $hotel
     * @return Room
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

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Set created_by
     *
     * @param \VientoSur\App\AppBundle\Entity\User $createdBy
     * @return Room
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->picture = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return $this->name;
    }
}
