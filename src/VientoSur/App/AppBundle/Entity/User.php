<?php

namespace VientoSur\App\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use VientoSur\App\AppBundle\Entity\Traits\TimestampableTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @UniqueEntity(fields="email", message="Email is already in use")
 * @UniqueEntity(fields="username", message="Username is already in use")
 */
class User extends BaseUser
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $first_name
     * @Assert\NotBlank()
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    protected $first_name;

    /**
     * @var string $last_name
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    protected $last_name;

    /**
     * @ORM\ManyToMany(targetEntity="Hotel")
     * @ORM\JoinTable(name="user_hotels",
     *     joinColumns={@ORM\JoinColumn(name="hotel_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    protected $userHotels;
    
    /**
     * @ORM\ManyToMany(targetEntity="ActivityAgency")
     * @ORM\JoinTable(name="user_activity_agency",
     *     joinColumns={@ORM\JoinColumn(name="activity_agency_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    protected $userActivityAgency;
    
    /** Set first_name
    *
    * @param string $firstName
    * @return User
    */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get first_name
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set last_name
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get last_name
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }
    
    public function __construct()
    {
        parent::__construct();
        //$this->addRole("ROLE_HOTELIER");
    }

    /**
     * Add userHotels
     *
     * @param \VientoSur\App\AppBundle\Entity\Hotel $userHotels
     * @return User
     */
    public function addUserHotel(\VientoSur\App\AppBundle\Entity\Hotel $userHotels)
    {
        $this->userHotels[] = $userHotels;

        return $this;
    }

    /**
     * Remove userHotels
     *
     * @param \VientoSur\App\AppBundle\Entity\Hotel $userHotels
     */
    public function removeUserHotel(\VientoSur\App\AppBundle\Entity\Hotel $userHotels)
    {
        $this->userHotels->removeElement($userHotels);
    }

    /**
     * Get userHotels
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserHotels()
    {
        return $this->userHotels;
    }

    /**
     * Add userActivityAgency
     *
     * @param \VientoSur\App\AppBundle\Entity\ActivityAgency $userActivityAgency
     *
     * @return User
     */
    public function addUserActivityAgency(\VientoSur\App\AppBundle\Entity\ActivityAgency $userActivityAgency)
    {
        $this->userActivityAgency[] = $userActivityAgency;

        return $this;
    }

    /**
     * Remove userActivityAgency
     *
     * @param \VientoSur\App\AppBundle\Entity\ActivityAgency $userActivityAgency
     */
    public function removeUserActivityAgency(\VientoSur\App\AppBundle\Entity\ActivityAgency $userActivityAgency)
    {
        $this->userActivityAgency->removeElement($userActivityAgency);
    }

    /**
     * Get userActivityAgency
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserActivityAgency()
    {
        return $this->userActivityAgency;
    }
}
