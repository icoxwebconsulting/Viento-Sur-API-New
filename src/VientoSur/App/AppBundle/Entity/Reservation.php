<?php


namespace VientoSur\App\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="VientoSur\App\AppBundle\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="hotel_id", type="integer", nullable=true)
     */
    private $hotelId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="collection_id", type="integer", nullable=true)
     */
    private $collectionId;

    /**
     * @ORM\Column(name="reservation_id", type="string", nullable=true)
     */
    private $reservationId;
    
    /**
     * @ORM\Column(name="external_reference", type="string", nullable=true)
     */
    private $externalReference;
    
    /**
     * @ORM\Column(name="payment_type", type="string", nullable=true)
     */
    private $paymentType;
    
    /**
     * @ORM\Column(name="can_adul", type="integer", nullable=true)
     */
    private $canAdul;
    
    /**
     * @ORM\Column(name="can_chil", type="integer", nullable=true)
     */
    private $canChil;
    
    /**
     * @ORM\Column(name="schedule", type="string", nullable=true)
     */
    private $schedule;

    /**
     * @var
     * @ORM\Column(name="total_price", type="float", nullable=true)
     */
    private $totalPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="card_type", type="string", length=100, nullable=true)
     */
    private $cardType;

    /**
     * @var string
     *
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
     * @ORM\Column(name="comments", type="text", nullable=true)
     */
    private $comments;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @ORM\Column(name="checkin", type="datetime", nullable=true)
     */
    private $checkin;

    /**
     * @ORM\Column(name="checkout", type="datetime", nullable=true)
     */
    private $checkout;

    /**
     * @ORM\Column(name="extra_data", type="text", nullable=true)
     */
    private $extra_data;

    /**
     * @ORM\Column(name="origin", type="string", length=255)
     */
    private $origin;

    /**
     * @ORM\Column(name="refundable", type="boolean")
     */
    private $refundable;

    /**
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(name="currencies_id", type="string", length=255, nullable=true)
     */
    private $currenciesId;
    
    /**
     * @ORM\Column(name="contact_type", type="string", length=255, nullable=true)
     */
    private $contactType;
    
    /**
     * @ORM\Column(name="document_number", type="string", length=255, nullable=true)
     */
    private $documentNumber;
    
    /**
     * @ORM\ManyToOne(targetEntity="ActivityAgency")
     * @ORM\JoinColumn(name="activity_agency", referencedColumnName="id", nullable=true)
     */
    protected $activity_agency;
    
    /**
     * @ORM\ManyToOne(targetEntity="ActivityAgency")
     * @ORM\JoinColumn(name="activity_agency_partner", referencedColumnName="id", nullable=true)
     */
    protected $activity_agency_partner;
    
    /**
     * @ORM\ManyToOne(targetEntity="Activity")
     * @ORM\JoinColumn(name="activity", referencedColumnName="id", nullable=true)
     */
    protected $activity;
    
    public function __construct()
    {
        $this->created = new \DateTime();
        $this->origin = 'despegar';
        $this->status = 'ok';
    }

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
     * Set cardType
     *
     * @param string $cardType
     * @return Reservation
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
     * @return Reservation
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
     * @return Reservation
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
     * @return Reservation
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
     * Set comments
     *
     * @param string $comments
     * @return Reservation
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set hotelId
     *
     * @param integer $hotelId
     * @return Reservation
     */
    public function setHotelId($hotelId)
    {
        $this->hotelId = $hotelId;

        return $this;
    }

    /**
     * Get hotelId
     *
     * @return integer 
     */
    public function getHotelId()
    {
        return $this->hotelId;
    }

    /**
     * Set contactName
     *
     * @param string $contactName
     * @return Reservation
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;

        return $this;
    }

    /**
     * Get contactName
     *
     * @return string 
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * Set reservationId
     *
     * @param integer $reservationId
     * @return Reservation
     */
    public function setReservationId($reservationId)
    {
        $this->reservationId = $reservationId;

        return $this;
    }

    /**
     * Get reservationId
     *
     * @return integer 
     */
    public function getReservationId()
    {
        return $this->reservationId;
    }

    /**
     * Set totalPrice
     *
     * @param float $totalPrice
     * @return Reservation
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
     * Set created
     *
     * @param \DateTime $created
     * @return Reservation
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
     * Set checkin
     *
     * @param \DateTime $checkin
     * @return Reservation
     */
    public function setCheckin($checkin)
    {
        $this->checkin = $checkin;

        return $this;
    }

    /**
     * Get checkin
     *
     * @return \DateTime 
     */
    public function getCheckin()
    {
        return $this->checkin;
    }

    /**
     * Set checkout
     *
     * @param \DateTime $checkout
     * @return Reservation
     */
    public function setCheckout($checkout)
    {
        $this->checkout = $checkout;

        return $this;
    }

    /**
     * Get checkout
     *
     * @return \DateTime 
     */
    public function getCheckout()
    {
        return $this->checkout;
    }

    /**
     * Set extra_data
     *
     * @param string $extraData
     * @return Reservation
     */
    public function setExtraData($extraData)
    {
        $this->extra_data = $extraData;

        return $this;
    }

    /**
     * Get extra_data
     *
     * @return string 
     */
    public function getExtraData()
    {
        return $this->extra_data;
    }

    /**
     * Set origin
     *
     * @param string $origin
     * @return Reservation
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
     * Set refundable
     *
     * @param boolean $refundable
     * @return Reservation
     */
    public function setRefundable($refundable)
    {
        $this->refundable = $refundable;

        return $this;
    }

    /**
     * Get refundable
     *
     * @return boolean 
     */
    public function getRefundable()
    {
        return $this->refundable;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Reservation
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set collectionId
     *
     * @param integer $collectionId
     *
     * @return Reservation
     */
    public function setCollectionId($collectionId)
    {
        $this->collectionId = $collectionId;

        return $this;
    }

    /**
     * Get collectionId
     *
     * @return integer
     */
    public function getCollectionId()
    {
        return $this->collectionId;
    }

    /**
     * Set externalReference
     *
     * @param string $externalReference
     *
     * @return Reservation
     */
    public function setExternalReference($externalReference)
    {
        $this->externalReference = $externalReference;

        return $this;
    }

    /**
     * Get externalReference
     *
     * @return string
     */
    public function getExternalReference()
    {
        return $this->externalReference;
    }

    /**
     * Set paymentType
     *
     * @param string $paymentType
     *
     * @return Reservation
     */
    public function setPaymentType($paymentType)
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    /**
     * Get paymentType
     *
     * @return string
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * Set canAdul
     *
     * @param integer $canAdul
     *
     * @return Reservation
     */
    public function setCanAdul($canAdul)
    {
        $this->canAdul = $canAdul;

        return $this;
    }

    /**
     * Get canAdul
     *
     * @return integer
     */
    public function getCanAdul()
    {
        return $this->canAdul;
    }

    /**
     * Set canChil
     *
     * @param integer $canChil
     *
     * @return Reservation
     */
    public function setCanChil($canChil)
    {
        $this->canChil = $canChil;

        return $this;
    }

    /**
     * Get canChil
     *
     * @return integer
     */
    public function getCanChil()
    {
        return $this->canChil;
    }

    /**
     * Set schedule
     *
     * @param string $schedule
     *
     * @return Reservation
     */
    public function setSchedule($schedule)
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * Get schedule
     *
     * @return string
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * Set currenciesId
     *
     * @param string $currenciesId
     *
     * @return Reservation
     */
    public function setCurrenciesId($currenciesId)
    {
        $this->currenciesId = $currenciesId;

        return $this;
    }

    /**
     * Get currenciesId
     *
     * @return string
     */
    public function getCurrenciesId()
    {
        return $this->currenciesId;
    }

    /**
     * Set activityAgency
     *
     * @param \VientoSur\App\AppBundle\Entity\ActivityAgency $activityAgency
     *
     * @return Reservation
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
     * Set activity
     *
     * @param \VientoSur\App\AppBundle\Entity\Activity $activity
     *
     * @return Reservation
     */
    public function setActivity(\VientoSur\App\AppBundle\Entity\Activity $activity = null)
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * Get activity
     *
     * @return \VientoSur\App\AppBundle\Entity\Activity
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Set contactType
     *
     * @param string $contactType
     *
     * @return Reservation
     */
    public function setContactType($contactType)
    {
        $this->contactType = $contactType;

        return $this;
    }

    /**
     * Get contactType
     *
     * @return string
     */
    public function getContactType()
    {
        return $this->contactType;
    }

    /**
     * Set documentNumber
     *
     * @param string $documentNumber
     *
     * @return Reservation
     */
    public function setDocumentNumber($documentNumber)
    {
        $this->documentNumber = $documentNumber;

        return $this;
    }

    /**
     * Get documentNumber
     *
     * @return string
     */
    public function getDocumentNumber()
    {
        return $this->documentNumber;
    }

    /**
     * Set activityAgencyPartner
     *
     * @param \VientoSur\App\AppBundle\Entity\ActivityAgency $activityAgencyPartner
     *
     * @return Reservation
     */
    public function setActivityAgencyPartner(\VientoSur\App\AppBundle\Entity\ActivityAgency $activityAgencyPartner = null)
    {
        $this->activity_agency_partner = $activityAgencyPartner;

        return $this;
    }

    /**
     * Get activityAgencyPartner
     *
     * @return \VientoSur\App\AppBundle\Entity\ActivityAgency
     */
    public function getActivityAgencyPartner()
    {
        return $this->activity_agency_partner;
    }
}
