<?php

namespace VientoSur\App\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use VientoSur\App\AppBundle\Entity\Traits\TimestampableTrait;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ReservationCancellation
 *
 * @ORM\Table(name="reservation_cancellations")
 * @ORM\Entity
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class ReservationCancellation
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
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    protected $created_by;

    /**
     * @ORM\ManyToOne(targetEntity="Reservation")
     * @ORM\JoinColumn(name="reservation", referencedColumnName="id")
     */
    protected $reservation;

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
     * Set description
     *
     * @param string $description
     * @return ReservationCancellation
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
     * Set code
     *
     * @param integer $code
     * @return ReservationCancellation
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return integer 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set created_by
     *
     * @param \VientoSur\App\AppBundle\Entity\User $createdBy
     * @return ReservationCancellation
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
     * Set reservation
     *
     * @param \VientoSur\App\AppBundle\Entity\Reservation $reservation
     * @return ReservationCancellation
     */
    public function setReservation(\VientoSur\App\AppBundle\Entity\Reservation $reservation = null)
    {
        $this->reservation = $reservation;

        return $this;
    }

    /**
     * Get reservation
     *
     * @return \VientoSur\App\AppBundle\Entity\Reservation 
     */
    public function getReservation()
    {
        return $this->reservation;
    }
}
