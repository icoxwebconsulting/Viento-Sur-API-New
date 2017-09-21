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
     * @ORM\Column(name="reservation_id", type="string", nullable=true)
     */
    private $reservationId;

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

    public function __construct()
    {
        $this->created = new \DateTime();
        $this->origin = 'despegar';
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
}
