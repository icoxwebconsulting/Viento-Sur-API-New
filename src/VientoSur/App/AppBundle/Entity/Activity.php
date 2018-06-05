<?php 

namespace VientoSur\App\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use VientoSur\App\AppBundle\Entity\Traits\TimestampableTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Activity
 *
 * @ORM\Table(name="activity")
 * @ORM\Entity(repositoryClass="VientoSur\App\AppBundle\Repository\ActivityRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @Vich\Uploadable
 */
class Activity
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
    protected $name;
    
    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;
    
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
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    protected $created_by;
    
    /**
     * @ORM\ManyToOne(targetEntity="ActivityAgency")
     * @ORM\JoinColumn(name="activity_agency", referencedColumnName="id")
     */
    protected $activity_agency;
    
    /**
     * @var float
     * @Assert\NotBlank()
     * @ORM\Column(name="price", type="float")
     */
    private $price;
    
    /**
     * @var integer
     * @Assert\NotBlank()
     * @ORM\Column(name="availability", type="integer")
     */
    private $availability;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="capacity_for_day", type="integer")
     */
    private $capacity_for_shift;
    
    /**
     * @var bool
     * @ORM\Column(name="monday", type="boolean")
     */
    protected $monday;
    
    /**
     * @var bool
     * @ORM\Column(name="tuesday", type="boolean")
     */
    protected $tuesday;
    
    /**
     * @var bool
     * @ORM\Column(name="wednesday", type="boolean")
     */
    protected $wednesday;
    
    /**
     * @var bool
     * @ORM\Column(name="thursday", type="boolean")
     */
    protected $thursday;
    
    /**
     * @var bool
     * @ORM\Column(name="friday", type="boolean")
     */
    protected $friday;
    
    /**
     * @var bool
     * @ORM\Column(name="saturday", type="boolean")
     */
    protected $saturday;
    
    /**
     * @var bool
     * @ORM\Column(name="sunday", type="boolean")
     */
    protected $sunday;
    
    /**
     * @var time
     * @ORM\Column(name="from_am", type="time")
     */
    protected $from_am;
    
    /**
     * @var time
     * @ORM\Column(name="to_am", type="time")
     */
    protected $to_am;
    
    /**
     * @var time
     * @ORM\Column(name="from_pm", type="time")
     */
    protected $from_pm;
    
    /**
     * @var time
     * @ORM\Column(name="to_pm", type="time")
     */
    protected $to_pm;
    
    /**
     * @var time
     * @ORM\Column(name="from_all", type="time")
     */
    protected $from_all;
    
    /**
     * @var time
     * @ORM\Column(name="to_all", type="time")
     */
    protected $to_all;
    
    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="duration", type="string", length=255)
     */
    protected $duration;
    
    /**
     * @var string
     * @ORM\Column(name="pick_up_am", type="string", length=255)
     */
    protected $pick_up_am;
    
    /**
     * @var string
     * @ORM\Column(name="pick_up_pm", type="string", length=255)
     */
    protected $pick_up_pm;
    
    /**
     * @ORM\ManyToMany(targetEntity="GeneralInformation")
     */
    protected $general_information;

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
     *
     * @return Activity
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
     * Set description
     *
     * @param string $description
     *
     * @return Activity
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

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return Activity
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
     *
     * @return Activity
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
     * Set price
     *
     * @param float $price
     *
     * @return Activity
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
     * Set availability
     *
     * @param integer $availability
     *
     * @return Activity
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
     * Set capacityForShift
     *
     * @param integer $capacityForShift
     *
     * @return Activity
     */
    public function setCapacityForShift($capacityForShift)
    {
        $this->capacity_for_shift = $capacityForShift;

        return $this;
    }

    /**
     * Get capacityForShift
     *
     * @return integer
     */
    public function getCapacityForShift()
    {
        return $this->capacity_for_shift;
    }

    /**
     * Set monday
     *
     * @param boolean $monday
     *
     * @return Activity
     */
    public function setMonday($monday)
    {
        $this->monday = $monday;

        return $this;
    }

    /**
     * Get monday
     *
     * @return boolean
     */
    public function getMonday()
    {
        return $this->monday;
    }

    /**
     * Set tuesday
     *
     * @param boolean $tuesday
     *
     * @return Activity
     */
    public function setTuesday($tuesday)
    {
        $this->tuesday = $tuesday;

        return $this;
    }

    /**
     * Get tuesday
     *
     * @return boolean
     */
    public function getTuesday()
    {
        return $this->tuesday;
    }

    /**
     * Set wednesday
     *
     * @param boolean $wednesday
     *
     * @return Activity
     */
    public function setWednesday($wednesday)
    {
        $this->wednesday = $wednesday;

        return $this;
    }

    /**
     * Get wednesday
     *
     * @return boolean
     */
    public function getWednesday()
    {
        return $this->wednesday;
    }

    /**
     * Set thursday
     *
     * @param boolean $thursday
     *
     * @return Activity
     */
    public function setThursday($thursday)
    {
        $this->thursday = $thursday;

        return $this;
    }

    /**
     * Get thursday
     *
     * @return boolean
     */
    public function getThursday()
    {
        return $this->thursday;
    }

    /**
     * Set friday
     *
     * @param boolean $friday
     *
     * @return Activity
     */
    public function setFriday($friday)
    {
        $this->friday = $friday;

        return $this;
    }

    /**
     * Get friday
     *
     * @return boolean
     */
    public function getFriday()
    {
        return $this->friday;
    }

    /**
     * Set saturday
     *
     * @param boolean $saturday
     *
     * @return Activity
     */
    public function setSaturday($saturday)
    {
        $this->saturday = $saturday;

        return $this;
    }

    /**
     * Get saturday
     *
     * @return boolean
     */
    public function getSaturday()
    {
        return $this->saturday;
    }

    /**
     * Set sunday
     *
     * @param boolean $sunday
     *
     * @return Activity
     */
    public function setSunday($sunday)
    {
        $this->sunday = $sunday;

        return $this;
    }

    /**
     * Get sunday
     *
     * @return boolean
     */
    public function getSunday()
    {
        return $this->sunday;
    }

    /**
     * Set fromAm
     *
     * @param \DateTime $fromAm
     *
     * @return Activity
     */
    public function setFromAm($fromAm)
    {
        $this->from_am = $fromAm;

        return $this;
    }

    /**
     * Get fromAm
     *
     * @return \DateTime
     */
    public function getFromAm()
    {
        return $this->from_am;
    }

    /**
     * Set toAm
     *
     * @param \DateTime $toAm
     *
     * @return Activity
     */
    public function setToAm($toAm)
    {
        $this->to_am = $toAm;

        return $this;
    }

    /**
     * Get toAm
     *
     * @return \DateTime
     */
    public function getToAm()
    {
        return $this->to_am;
    }

    /**
     * Set fromPm
     *
     * @param \DateTime $fromPm
     *
     * @return Activity
     */
    public function setFromPm($fromPm)
    {
        $this->from_pm = $fromPm;

        return $this;
    }

    /**
     * Get fromPm
     *
     * @return \DateTime
     */
    public function getFromPm()
    {
        return $this->from_pm;
    }

    /**
     * Set toPm
     *
     * @param \DateTime $toPm
     *
     * @return Activity
     */
    public function setToPm($toPm)
    {
        $this->to_pm = $toPm;

        return $this;
    }

    /**
     * Get toPm
     *
     * @return \DateTime
     */
    public function getToPm()
    {
        return $this->to_pm;
    }

    /**
     * Set fromAll
     *
     * @param \DateTime $fromAll
     *
     * @return Activity
     */
    public function setFromAll($fromAll)
    {
        $this->from_all = $fromAll;

        return $this;
    }

    /**
     * Get fromAll
     *
     * @return \DateTime
     */
    public function getFromAll()
    {
        return $this->from_all;
    }

    /**
     * Set toAll
     *
     * @param \DateTime $toAll
     *
     * @return Activity
     */
    public function setToAll($toAll)
    {
        $this->to_all = $toAll;

        return $this;
    }

    /**
     * Get toAll
     *
     * @return \DateTime
     */
    public function getToAll()
    {
        return $this->to_all;
    }

    /**
     * Set createdBy
     *
     * @param \VientoSur\App\AppBundle\Entity\User $createdBy
     *
     * @return Activity
     */
    public function setCreatedBy(\VientoSur\App\AppBundle\Entity\User $createdBy = null)
    {
        $this->created_by = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \VientoSur\App\AppBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * Set activityAgency
     *
     * @param \VientoSur\App\AppBundle\Entity\ActivityAgency $activityAgency
     *
     * @return Activity
     */
    public function setActivityAgency(\VientoSur\App\AppBundle\Entity\ActivityAgency $activityAgency = null)
    {
        $this->activity_agency = $activityAgency;

        return $this;
    }

    /**
     * Get activityAgency
     *
     * @return \VientoSur\App\AppBundle\Entity\ActivityAgency
     */
    public function getActivityAgency()
    {
        return $this->activity_agency;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Activity
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
     * Set duration
     *
     * @param string $duration
     *
     * @return Activity
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set pickUpAm
     *
     * @param string $pickUpAm
     *
     * @return Activity
     */
    public function setPickUpAm($pickUpAm)
    {
        $this->pick_up_am = $pickUpAm;

        return $this;
    }

    /**
     * Get pickUpAm
     *
     * @return string
     */
    public function getPickUpAm()
    {
        return $this->pick_up_am;
    }

    /**
     * Set pickUpPm
     *
     * @param string $pickUpPm
     *
     * @return Activity
     */
    public function setPickUpPm($pickUpPm)
    {
        $this->pick_up_pm = $pickUpPm;

        return $this;
    }

    /**
     * Get pickUpPm
     *
     * @return string
     */
    public function getPickUpPm()
    {
        return $this->pick_up_pm;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->general_information = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add generalInformation
     *
     * @param \VientoSur\App\AppBundle\Entity\GeneralInformation $generalInformation
     *
     * @return Activity
     */
    public function addGeneralInformation(\VientoSur\App\AppBundle\Entity\GeneralInformation $generalInformation)
    {
        $this->general_information[] = $generalInformation;

        return $this;
    }

    /**
     * Remove generalInformation
     *
     * @param \VientoSur\App\AppBundle\Entity\GeneralInformation $generalInformation
     */
    public function removeGeneralInformation(\VientoSur\App\AppBundle\Entity\GeneralInformation $generalInformation)
    {
        $this->general_information->removeElement($generalInformation);
    }

    /**
     * Get generalInformation
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGeneralInformation()
    {
        return $this->general_information;
    }
}
