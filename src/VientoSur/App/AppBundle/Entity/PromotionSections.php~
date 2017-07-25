<?php

namespace VientoSur\App\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use VientoSur\App\AppBundle\Entity\Traits\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass="VientoSur\App\AppBundle\Repository\PromotionSectionsRepository")
 * @ORM\Table(name="promotion_sections")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class PromotionSections
{
    use TimestampableTrait;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(name="subtitle", type="string", length=255, nullable=true)
     */
    protected $subtitle;
    
    /**
     * @ORM\ManyToOne(targetEntity="Status")
     * @ORM\JoinColumn(name="status", referencedColumnName="id")
     */
    protected $status;

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
     * Set title
     *
     * @param string $title
     * @return PromotionSections
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set subtitle
     *
     * @param string $subtitle
     * @return PromotionSections
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string 
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set status
     *
     * @param \VientoSur\App\AppBundle\Entity\Status $status
     * @return PromotionSections
     */
    public function setStatus(\VientoSur\App\AppBundle\Entity\Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \VientoSur\App\AppBundle\Entity\Status 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set created_by
     *
     * @param \VientoSur\App\AppBundle\Entity\User $createdBy
     * @return PromotionSections
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
        return $this->title;
    }
}
