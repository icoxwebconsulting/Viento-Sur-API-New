<?php

namespace VientoSur\App\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bed
 *
 * @ORM\Table(name="beds")
 * @ORM\Entity
 */
class Bed
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
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="size", type="string", length=255)
     */
    private $size;

    /**
     * @ORM\ManyToOne(targetEntity="Room", inversedBy="beds")
     * @ORM\JoinColumn(name="room", referencedColumnName="id")
     */
    protected $room;

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
     * @return Bed
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
     * Set size
     *
     * @param string $size
     * @return Bed
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string 
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set room
     *
     * @param \VientoSur\App\AppBundle\Entity\Room $room
     * @return Bed
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
}
