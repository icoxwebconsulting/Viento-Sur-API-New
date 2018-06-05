<?php

namespace VientoSur\App\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Picture
 *
 * @ORM\Table(name="pictures")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Picture
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\File(
     *     maxSize="10000k",
     *     mimeTypes={"image/png", "image/jpeg", "image/gif", "image/jpg"},
     *     mimeTypesMessage = "El tipo de archivo ({{ type }}) no es válido. Los tipos de archivos permitidos son {{ types }}"
     * )
     *
     * @Vich\UploadableField(mapping="gallery_image", fileNameProperty="imageName")
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageName;

    /**
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(name="main_picture", type="boolean", nullable=true)
     */
    private $mainPicture;
    
    /**
     * @ORM\ManyToOne(targetEntity="Hotel", inversedBy="pictures")
     * @ORM\JoinColumn(nullable=true)
     */
    private $hotel;

    /**
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="pictures")
     * @ORM\JoinColumn(nullable=true)
     */
    private $room;
    
    /**
     * @ORM\ManyToOne(targetEntity="Activity", inversedBy="pictures")
     * @ORM\JoinColumn(nullable=true)
     */
    private $activity;
    
    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

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
     * Set hotel
     *
     * @param \VientoSur\App\AppBundle\Entity\Hotel $hotel
     * @return Picture
     */
    public function setHotel(\VientoSur\App\AppBundle\Entity\Hotel $hotel = null)
    {
        $this->hotel = $hotel;

        return $this;
    }

    /**
     * Get hotel
     *
     * @return \VientoSur\App\AppBundle\Entity\Hotel 
     */
    public function getHotel()
    {
        return $this->hotel;
    }

    /**
     * Set room
     *
     * @param \VientoSur\App\AppBundle\Entity\Room $room
     * @return Picture
     */
    public function setRoom(\VientoSur\App\AppBundle\Entity\Room $room = null)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * Get room
     *
     * @return \VientoSur\App\AppBundle\Entity\Room 
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * Set imageName
     *
     * @param string $imageName
     * @return Picture
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get imageName
     *
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return Image
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
     * Set position
     *
     * @param integer $position
     * @return Picture
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set mainPicture
     *
     * @param boolean $mainPicture
     * @return Picture
     */
    public function setMainPicture($mainPicture)
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }

    /**
     * Get mainPicture
     *
     * @return boolean 
     */
    public function getMainPicture()
    {
        return $this->mainPicture;
    }

    /**
     * Set activity
     *
     * @param \VientoSur\App\AppBundle\Entity\Activity $activity
     *
     * @return Picture
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Picture
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
