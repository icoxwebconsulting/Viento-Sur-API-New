<?php

namespace VientoSur\App\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use VientoSur\App\AppBundle\Entity\Traits\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Translatable\Translatable;

/**
 * @ORM\Entity(repositoryClass="VientoSur\App\AppBundle\Repository\PromotionsRepository")
 * @ORM\Table(name="promotions")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @Vich\Uploadable
 */
class Promotions
{
    use TimestampableTrait;
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @Gedmo\Translatable
     * @Assert\NotBlank()
     * @ORM\Column(name="description", type="text")
     */
    protected $description;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(name="link", type="string", length=255)
     */
    protected $link;

    /**
     * @Assert\File(
     *     maxSize="10000k",
     *     mimeTypes={"image/png", "image/jpeg", "image/gif", "image/jpg"},
     *     mimeTypesMessage = "El tipo de archivo ({{ type }}) no es válido. Los tipos de archivos permitidos son {{ types }}"
     * )
     *
     * @Vich\UploadableField(mapping="image_promotion", fileNameProperty="imageName")
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageName;

    /**
     * @ORM\ManyToOne(targetEntity="PromotionSections")
     * @ORM\JoinColumn(name="section", referencedColumnName="id")
     */
    protected $section;

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
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

     /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;
    
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
     * @return Promotions
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
     * @return Promotions
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
     * Set link
     *
     * @param string $link
     * @return Promotions
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set section
     *
     * @param \VientoSur\App\AppBundle\Entity\PromotionSections $section
     * @return Promotions
     */
    public function setSection(\VientoSur\App\AppBundle\Entity\PromotionSections $section = null)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * Get section
     *
     * @return \VientoSur\App\AppBundle\Entity\PromotionSections 
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * Set status
     *
     * @param \VientoSur\App\AppBundle\Entity\Status $status
     * @return Promotions
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
     * @return Promotions
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
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return Media
     */
    public function setImage(File $file = null)
    {
        $this->image = $file;

        if ($file instanceof UploadedFile) {
            $this->setUpdatedAt(new \DateTime());
        }
    }

    /**
     * @return File
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $fileName
     *
     * @return User
     */
    public function setImageName($fileName)
    {
        $this->imageName = $fileName;

        return $this;
    }

    /**
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Promotions
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
