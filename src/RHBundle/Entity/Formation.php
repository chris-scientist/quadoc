<?php

namespace RHBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Formation
 *
 * @ORM\Table(name="t_formation_fat")
 * @ORM\Entity(repositoryClass="RHBundle\Repository\FormationRepository")
 */
class Formation
{
    /**
     * @var int
     *
     * @ORM\Column(name="fat_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="fat_intitule", type="string", length=32, unique=true)
     */
    private $intitule;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fat_effectue_le", type="datetime")
     */
    private $effectueLe;

    /**
     * @var int
     *
     * @ORM\Column(name="fat_duree", type="integer")
     */
    private $duree;

    /**
     * @var string
     *
     * @ORM\Column(name="fat_description", type="string", nullable=true, length=255)
     */
    private $description;
    
    /**
     * @var RHBundle\Entity\Modeacquisition
     * 
     * @ORM\ManyToOne(targetEntity="RHBundle\Entity\Modeacquisition")
     * @ORM\JoinColumn(name="fat_mod_id", nullable=false, referencedColumnName="mod_id")
     */
    private $modeacquisition;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="fat_b_fichier", type="boolean")
     */
    private $bFichier;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this
                ->setDescription(null) ;
    }
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set intitule
     *
     * @param string $intitule
     *
     * @return Formation
     */
    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * Get intitule
     *
     * @return string
     */
    public function getIntitule()
    {
        return $this->intitule;
    }

    /**
     * Set effectueLe
     *
     * @param \DateTime $effectueLe
     *
     * @return Formation
     */
    public function setEffectueLe($effectueLe)
    {
        $this->effectueLe = $effectueLe;

        return $this;
    }

    /**
     * Get effectueLe
     *
     * @return \DateTime
     */
    public function getEffectueLe()
    {
        return $this->effectueLe;
    }

    /**
     * Set duree
     *
     * @param integer $duree
     *
     * @return Formation
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return int
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Formation
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
     * Set modeacquisition
     *
     * @param \RHBundle\Entity\Modeacquisition $modeacquisition
     *
     * @return Formation
     */
    public function setModeacquisition(\RHBundle\Entity\Modeacquisition $modeacquisition)
    {
        $this->modeacquisition = $modeacquisition;

        return $this;
    }

    /**
     * Get modeacquisition
     *
     * @return \RHBundle\Entity\Modeacquisition
     */
    public function getModeacquisition()
    {
        return $this->modeacquisition;
    }
}
