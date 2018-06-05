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
 * DatesDisableActivity
 *
 * @ORM\Table(name="dates_disable_activity")
 * @ORM\Entity(repositoryClass="VientoSur\App\AppBundle\Repository\DatesDisableActivityRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class DatesDisableActivity
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
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $disableAt;
    
    /**
     * @ORM\ManyToOne(targetEntity="Activity", inversedBy="pictures")
     * @ORM\JoinColumn(nullable=true)
     */
    private $activity;
    
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    protected $created_by;

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
     * Set disableAt
     *
     * @param \DateTime $disableAt
     *
     * @return DatesDisableActivity
     */
    public function setDisableAt($disableAt)
    {
        $this->disableAt = $disableAt;

        return $this;
    }

    /**
     * Get disableAt
     *
     * @return \DateTime
     */
    public function getDisableAt()
    {
        return $this->disableAt;
    }

    /**
     * Set activity
     *
     * @param \VientoSur\App\AppBundle\Entity\Activity $activity
     *
     * @return DatesDisableActivity
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
     * Set createdBy
     *
     * @param \VientoSur\App\AppBundle\Entity\User $createdBy
     *
     * @return DatesDisableActivity
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
}
