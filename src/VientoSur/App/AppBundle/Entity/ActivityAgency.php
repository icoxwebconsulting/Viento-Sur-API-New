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
 * ActivityAgency
 *
 * @ORM\Table(name="activity_agency")
 * @ORM\Entity(repositoryClass="VientoSur\App\AppBundle\Repository\ActivityAgencyRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @Vich\Uploadable
 */
class ActivityAgency
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
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="file_number", type="string", length=255)
     */
    private $file_number;
    
    /**
     * @var bool
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled;
    
    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;
    
    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @Assert\File(
     *     maxSize="10000k",
     *     mimeTypes={"image/png", "image/jpeg", "image/gif", "image/jpg"},
     *     mimeTypesMessage = "El tipo de archivo ({{ type }}) no es válido. Los tipos de archivos permitidos son {{ types }}"
     * )
     *
     * @Vich\UploadableField(mapping="image_activiti_agency", fileNameProperty="imageName")
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageName;
    
    /**
     * @Assert\File(
     *     maxSize="10000k",
     *     mimeTypes={"application/pdf"},
     *     mimeTypesMessage = "El tipo de archivo ({{ type }}) no es válido. Los tipos de archivos permitidos son {{ types }}"
     * )
     *
     * @Vich\UploadableField(mapping="file_activiti_agency", fileNameProperty="fileNamePdf")
     */
    private $file_pdf;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fileNamePdf;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", cascade={"persist"})
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    protected $user;
    
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
     * @var float
     *
     * @ORM\Column(name="percentage_vs", type="decimal", precision=5, scale=2)
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      minMessage = "Min % is 0",
     *      maxMessage = "Max % is 100"
     * )
     */
    private $percentageVs;
    
    /**
     * @var float
     *
     * @ORM\Column(name="percentage_others", type="decimal", precision=5, scale=2)
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      minMessage = "Min % is 0",
     *      maxMessage = "Max % is 100"
     * )
     */
    private $percentageOthers;
    
    /**
     * @var float
     *
     * @ORM\Column(name="commercial_discounts", type="decimal", precision=5, scale=2)
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      minMessage = "Min % is 0",
     *      maxMessage = "Max % is 100"
     * )
     */
    private $commercialDiscounts;
    
    public function __construct()
    {
        $this->setUpdatedAt(new \DateTime());
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
     * Set name
     *
     * @param string $name
     *
     * @return ActivityAgency
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
     * Set address
     *
     * @param string $address
     *
     * @return ActivityAgency
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
     * Set createdBy
     *
     * @param \VientoSur\App\AppBundle\Entity\User $createdBy
     *
     * @return ActivityAgency
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
     * Set phone
     *
     * @param string $phone
     *
     * @return ActivityAgency
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }
    
    /**
     * Set enable
     *
     * @param boolean $boolean
     *
     * @return ActivityAgency
     */
    public function setEnabled($boolean)
    {
        $this->enabled = (Boolean) $boolean;

        return $this;
    }
    
    /**
     * Is enable
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set user
     *
     * @param \VientoSur\App\AppBundle\Entity\User $user
     *
     * @return ActivityAgency
     */
    public function setUser(\VientoSur\App\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \VientoSur\App\AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
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

    /**
     * Set fileNumber
     *
     * @param string $fileNumber
     *
     * @return ActivityAgency
     */
    public function setFileNumber($fileNumber)
    {
        $this->file_number = $fileNumber;

        return $this;
    }

    /**
     * Get fileNumber
     *
     * @return string
     */
    public function getFileNumber()
    {
        return $this->file_number;
    }
    
    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return Media
     */
    public function setFilePdf(File $file = null)
    {
        $this->file_pdf = $file;

        if ($file instanceof UploadedFile) {
            $this->setUpdatedAt(new \DateTime());
        }
    }

    /**
     * @return File
     */
    public function getFilePdf()
    {
        return $this->file_pdf;
    }

    /**
     * Set fileName
     *
     * @param string $fileNamePdf
     *
     * @return ActivityAgency
     */
        public function setFileNamePdf($fileNamePdf)
    {
        $this->fileNamePdf = $fileNamePdf;

        return $this;
    }

    /**
     * Get fileNamePdf
     *
     * @return string
     */
    public function getFileNamePdf()
    {
        return $this->fileNamePdf;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return ActivityAgency
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

    /**
     * Set percentageVs
     *
     * @param string $percentageVs
     *
     * @return ActivityAgency
     */
    public function setPercentageVs($percentageVs)
    {
        $this->percentageVs = $percentageVs;

        return $this;
    }

    /**
     * Get percentageVs
     *
     * @return string
     */
    public function getPercentageVs()
    {
        return $this->percentageVs;
    }

    /**
     * Set percentageOthers
     *
     * @param string $percentageOthers
     *
     * @return ActivityAgency
     */
    public function setPercentageOthers($percentageOthers)
    {
        $this->percentageOthers = $percentageOthers;

        return $this;
    }

    /**
     * Get percentageOthers
     *
     * @return string
     */
    public function getPercentageOthers()
    {
        return $this->percentageOthers;
    }

    /**
     * Set commercialDiscounts
     *
     * @param string $commercialDiscounts
     *
     * @return ActivityAgency
     */
    public function setCommercialDiscounts($commercialDiscounts)
    {
        $this->commercialDiscounts = $commercialDiscounts;

        return $this;
    }

    /**
     * Get commercialDiscounts
     *
     * @return string
     */
    public function getCommercialDiscounts()
    {
        return $this->commercialDiscounts;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
    
    public function __toString() {
        return $this->name;
    }
}
