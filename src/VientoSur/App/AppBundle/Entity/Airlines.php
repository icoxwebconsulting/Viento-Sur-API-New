<?php

namespace VientoSur\App\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Airlines
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="VientoSur\App\AppBundle\Repository\AirlinesRepository")
 */
class Airlines
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="string")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * One Product has Many Features.
     * @ORM\OneToMany(targetEntity="AirlineAlliance", mappedBy="airline")
     */
    private $alliance;


    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Airlines
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
     * Constructor
     */
    public function __construct()
    {
        $this->alliance = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set id
     *
     * @param string $id
     * @return Airlines
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Add alliance
     *
     * @param \VientoSur\App\AppBundle\Entity\AirlineAlliance $alliance
     * @return Airlines
     */
    public function addAlliance(\VientoSur\App\AppBundle\Entity\AirlineAlliance $alliance)
    {
        $this->alliance[] = $alliance;

        return $this;
    }

    /**
     * Remove alliance
     *
     * @param \VientoSur\App\AppBundle\Entity\AirlineAlliance $alliance
     */
    public function removeAlliance(\VientoSur\App\AppBundle\Entity\AirlineAlliance $alliance)
    {
        $this->alliance->removeElement($alliance);
    }

    /**
     * Get alliance
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAlliance()
    {
        return $this->alliance;
    }
}
