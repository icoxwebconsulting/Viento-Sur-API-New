<?php

namespace VientoSur\App\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use VientoSur\App\AppBundle\Entity\Traits\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Translatable\Translatable;

/**
 * @ORM\Entity(repositoryClass="VientoSur\App\AppBundle\Repository\GeneralInformationRepository")
 * @ORM\Table(name="general_information")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class GeneralInformation
{
    use TimestampableTrait;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;
    
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    protected $created_by;
    
    /**
     * @ORM\ManyToMany(targetEntity="Activity")
     * @ORM\JoinTable(name="general_information_activity",
     *     joinColumns={@ORM\JoinColumn(name="activity_id", referencedColumnName="id", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="general_information_id", referencedColumnName="id")}
     * )
     */
    protected $activity;
    
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
     * @return GeneralInformation
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
     * Set createdBy
     *
     * @param \VientoSur\App\AppBundle\Entity\User $createdBy
     *
     * @return GeneralInformation
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
    
    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->activity = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add activity
     *
     * @param \VientoSur\App\AppBundle\Entity\Activity $activity
     *
     * @return GeneralInformation
     */
    public function addActivity(\VientoSur\App\AppBundle\Entity\Activity $activity)
    {
        $this->activity[] = $activity;

        return $this;
    }

    /**
     * Remove activity
     *
     * @param \VientoSur\App\AppBundle\Entity\Activity $activity
     */
    public function removeActivity(\VientoSur\App\AppBundle\Entity\Activity $activity)
    {
        $this->activity->removeElement($activity);
    }

    /**
     * Get activity
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivity()
    {
        return $this->activity;
    }
}
