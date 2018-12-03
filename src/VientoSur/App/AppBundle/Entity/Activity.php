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
     * @ORM\Column(name="address_origin", type="string", length=255)
     */
    private $address_origin;
    
    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="latitude_origin", type="string", length=255)
     */
    private $latitude_origin;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="longitude_origin", type="string", length=255)
     */
    private $longitude_origin;
    
    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="address_destination", type="string", length=255)
     */
    private $address_destination;
    
    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="latitude_destination", type="string", length=255)
     */
    private $latitude_destination;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="longitude_destination", type="string", length=255)
     */
    private $longitude_destination;
    
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
     * @var bool
     * @ORM\Column(name="availability", type="boolean", options={"default" : 0})
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
     * @ORM\Column(name="monday", type="boolean", options={"default" : 0})
     */
    protected $monday;
    
    /**
     * @var bool
     * @ORM\Column(name="tuesday", type="boolean", options={"default" : 0})
     */
    protected $tuesday;
    
    /**
     * @var bool
     * @ORM\Column(name="wednesday", type="boolean", options={"default" : 0})
     */
    protected $wednesday;
    
    /**
     * @var bool
     * @ORM\Column(name="thursday", type="boolean", options={"default" : 0})
     */
    protected $thursday;
    
    /**
     * @var bool
     * @ORM\Column(name="friday", type="boolean", options={"default" : 0})
     */
    protected $friday;
    
    /**
     * @var bool
     * @ORM\Column(name="saturday", type="boolean", options={"default" : 0})
     */
    protected $saturday;
    
    /**
     * @var bool
     * @ORM\Column(name="sunday", type="boolean", options={"default" : 0})
     */
    protected $sunday;
    
    /**
     * @var string
     * @ORM\Column(name="from_am", type="string")
     */
    protected $from_am;
    
    /**
     * @var string
     * @ORM\Column(name="to_am", type="string")
     */
    protected $to_am;
    
    /**
     * @var bool
     * @ORM\Column(name="am", type="boolean",options={"default" : 0})
     */
    protected $am;
    
    /**
     * @var string
     * @ORM\Column(name="from_pm", type="string")
     */
    protected $from_pm;
    
    /**
     * @var string
     * @ORM\Column(name="to_pm", type="string")
     */
    protected $to_pm;
    
    /**
     * @var bool
     * @ORM\Column(name="pm", type="boolean",options={"default" : 0})
     */
    protected $pm;
    
    /**
     * @var string
     * @ORM\Column(name="from_all", type="string")
     */
    protected $from_all;
    
    /**
     * @var string
     * @ORM\Column(name="to_all", type="string")
     */
    protected $to_all;
    
    /**
     * @var bool
     * @ORM\Column(name="all_day", type="boolean",options={"default" : 0})
     */
    protected $all_day;
    
    /**
     * @var string
     * @ORM\Column(name="from_several", type="string")
     */
    protected $from_several;
    
    /**
     * @var string
     * @ORM\Column(name="to_several", type="string")
     */
    protected $to_several;
    
    /**
     * @var bool
     * @ORM\Column(name="several_day", type="boolean",options={"default" : 0})
     */
    protected $several_day;
    
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
     * @var string
     * @ORM\Column(name="pick_up_all", type="string", length=255)
     */
    protected $pick_up_all;
    
    /**
     * @var string
     * @ORM\Column(name="pick_up_several", type="string", length=255)
     */
    protected $pick_up_several;
    
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
    
    /*
     * array day availability
     */
    private $array_day = [];

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
     * @param boolean $availability
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
     * @return boolean
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

    /**
     * Set pickUpAll
     *
     * @param string $pickUpAll
     *
     * @return Activity
     */
    public function setPickUpAll($pickUpAll)
    {
        $this->pick_up_all = $pickUpAll;

        return $this;
    }

    /**
     * Get pickUpAll
     *
     * @return string
     */
    public function getPickUpAll()
    {
        return $this->pick_up_all;
    }

    /**
     * Set am
     *
     * @param boolean $am
     *
     * @return Activity
     */
    public function setAm($am)
    {
        $this->am = $am;

        return $this;
    }

    /**
     * Get am
     *
     * @return boolean
     */
    public function getAm()
    {
        return $this->am;
    }

    /**
     * Set pm
     *
     * @param boolean $pm
     *
     * @return Activity
     */
    public function setPm($pm)
    {
        $this->pm = $pm;

        return $this;
    }

    /**
     * Get pm
     *
     * @return boolean
     */
    public function getPm()
    {
        return $this->pm;
    }

    /**
     * Set allDay
     *
     * @param boolean $allDay
     *
     * @return Activity
     */
    public function setAllDay($allDay)
    {
        $this->all_day = $allDay;

        return $this;
    }

    /**
     * Get allDay
     *
     * @return boolean
     */
    public function getAllDay()
    {
        return $this->all_day;
    }
    
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
    
    /*
     * getArrayDAy()
     */
    public function getArrayDAy()
    {
        $this->array_day = [
            'Lunes'=>$this->monday,
            'Martes'=>$this->tuesday,
            'Miercoles'=>$this->wednesday,
            'Jueves'=>$this->thursday,
            'Viernes'=>$this->friday,
            'Sabado'=>$this->saturday,
            'Domingo'=>$this->sunday];
    }  
    
    /*
     * getFirstDay()
     */
    public function getFirstDay(){
      
        $this->getArrayDAy();
        $newValues=array_filter($this->array_day, "strlen");
        $coutn = count($newValues);
        $index = 0;
        $data = '';
      
        foreach ($newValues as $key => $value) {
            $index++;
            if($coutn === 1){
                $data.= $key;
            }elseif($index < $coutn){
                $data.= $key.', ';
            }else{
                $data = substr($data, 0, -1);
                $data.=' y '. $key;
            }    
        }
        return $data;
    } 
    
    /*
     * getEndDay
     */
    public function getEndDay(){
        $val = '';
        foreach ($this->array_day as $key => $value) {
            if($value == 1){
                $val = $key;
            }
        }
        
        return $val;
    } 

    /**
     * Set addressOrigin
     *
     * @param string $addressOrigin
     *
     * @return Activity
     */
    public function setAddressOrigin($addressOrigin)
    {
        $this->address_origin = $addressOrigin;

        return $this;
    }

    /**
     * Get addressOrigin
     *
     * @return string
     */
    public function getAddressOrigin()
    {
        return $this->address_origin;
    }

    /**
     * Set latitudeOrigin
     *
     * @param string $latitudeOrigin
     *
     * @return Activity
     */
    public function setLatitudeOrigin($latitudeOrigin)
    {
        $this->latitude_origin = $latitudeOrigin;

        return $this;
    }

    /**
     * Get latitudeOrigin
     *
     * @return string
     */
    public function getLatitudeOrigin()
    {
        return $this->latitude_origin;
    }

    /**
     * Set longitudeOrigin
     *
     * @param string $longitudeOrigin
     *
     * @return Activity
     */
    public function setLongitudeOrigin($longitudeOrigin)
    {
        $this->longitude_origin = $longitudeOrigin;

        return $this;
    }

    /**
     * Get longitudeOrigin
     *
     * @return string
     */
    public function getLongitudeOrigin()
    {
        return $this->longitude_origin;
    }

    /**
     * Set addressDestination
     *
     * @param string $addressDestination
     *
     * @return Activity
     */
    public function setAddressDestination($addressDestination)
    {
        $this->address_destination = $addressDestination;

        return $this;
    }

    /**
     * Get addressDestination
     *
     * @return string
     */
    public function getAddressDestination()
    {
        return $this->address_destination;
    }

    /**
     * Set latitudeDestination
     *
     * @param string $latitudeDestination
     *
     * @return Activity
     */
    public function setLatitudeDestination($latitudeDestination)
    {
        $this->latitude_destination = $latitudeDestination;

        return $this;
    }

    /**
     * Get latitudeDestination
     *
     * @return string
     */
    public function getLatitudeDestination()
    {
        return $this->latitude_destination;
    }

    /**
     * Set longitudeDestination
     *
     * @param string $longitudeDestination
     *
     * @return Activity
     */
    public function setLongitudeDestination($longitudeDestination)
    {
        $this->longitude_destination = $longitudeDestination;

        return $this;
    }

    /**
     * Get longitudeDestination
     *
     * @return string
     */
    public function getLongitudeDestination()
    {
        return $this->longitude_destination;
    }

    /**
     * Set fromSeveral
     *
     * @param string $fromSeveral
     *
     * @return Activity
     */
    public function setFromSeveral($fromSeveral)
    {
        $this->from_several = $fromSeveral;

        return $this;
    }

    /**
     * Get fromSeveral
     *
     * @return string
     */
    public function getFromSeveral()
    {
        return $this->from_several;
    }

    /**
     * Set toSeveral
     *
     * @param string $toSeveral
     *
     * @return Activity
     */
    public function setToSeveral($toSeveral)
    {
        $this->to_several = $toSeveral;

        return $this;
    }

    /**
     * Get toSeveral
     *
     * @return string
     */
    public function getToSeveral()
    {
        return $this->to_several;
    }

    /**
     * Set severalDay
     *
     * @param boolean $severalDay
     *
     * @return Activity
     */
    public function setSeveralDay($severalDay)
    {
        $this->several_day = $severalDay;

        return $this;
    }

    /**
     * Get severalDay
     *
     * @return boolean
     */
    public function getSeveralDay()
    {
        return $this->several_day;
    }

    /**
     * Set pickUpSeveral
     *
     * @param string $pickUpSeveral
     *
     * @return Activity
     */
    public function setPickUpSeveral($pickUpSeveral)
    {
        $this->pick_up_several = $pickUpSeveral;

        return $this;
    }

    /**
     * Get pickUpSeveral
     *
     * @return string
     */
    public function getPickUpSeveral()
    {
        return $this->pick_up_several;
    }
}
