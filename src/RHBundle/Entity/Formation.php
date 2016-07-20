<?php
/* Copyright 2016 C. Thubert */

namespace RHBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Formation
 *
 * @ORM\Table(name="t_formation_fat")
 * @ORM\Entity(repositoryClass="RHBundle\Repository\FormationRepository")
 * @ORM\HasLifecycleCallbacks
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
     * @Assert\Length(max=32)
     */
    private $intitule;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fat_effectue_le", type="datetime")
     * @Assert\Date()
     */
    private $effectueLe;

    /**
     * @var int
     *
     * @ORM\Column(name="fat_duree", type="integer")
     * @Assert\Range(
     *  min=1
     * )
     */
    private $duree;

    /**
     * @var string
     *
     * @ORM\Column(name="fat_description", type="string", nullable=true, length=255)
     * @Assert\Length(max=255)
     */
    private $description;
    
    /**
     * @var RHBundle\Entity\Modeacquisition
     * 
     * @ORM\ManyToOne(targetEntity="RHBundle\Entity\Modeacquisition")
     * @ORM\JoinColumn(name="fat_mod_id", nullable=false, referencedColumnName="mod_id")
     * @Assert\NotNull()
     */
    private $modeacquisition;
    
    /**
     * @var UtilisateurBundle\Entity\Utilisateur
     * 
     * @ORM\ManyToOne(targetEntity="UtilisateurBundle\Entity\Utilisateur", inversedBy="formations")
     * @ORM\JoinColumn(name="fat_uti_id", referencedColumnName="uti_id")
     * @Assert\NotNull()
     */
    private $utilisateur;

    /**
     * @var bool
     *
     * @ORM\Column(name="fat_b_fichier", type="boolean")
     */
    private $bFichier;
    
    /**
     * @Assert\File(
     *      mimeTypes = {"application/pdf"},
     *      mimeTypesMessage = "app.err.form.pdfonly"
     * )
     */
    private $fichier;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this
                ->setDescription(null) ;
        
        $this->setBFichier(false) ;
    }
    
    public function getUploadDir()
    {
        $absolutePath = __DIR__ . '/../../../web/upload/' ;
        $uploadDir = $absolutePath . "documents/formations/" ;
        return $uploadDir ;
    }
    
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if( is_null($this->fichier) ) {
            return ;
        }
        
        $filename = $this->getId() . '.pdf' ;
        $this->fichier->move(
            $this->getUploadDir(),
            $filename
        ) ;
    }
    
    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        $this->fichier = $this->getUploadDir() . $this->getId() . '.pdf' ;
    }
    
    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $fichier = $this->getFichier() ;
        if( file_exists($fichier) )
        {
            unlink($fichier) ;
        }
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

    /**
     * Set utilisateur
     *
     * @param \UtilisateurBundle\Entity\Utilisateur $utilisateur
     *
     * @return Formation
     */
    public function setUtilisateur(\UtilisateurBundle\Entity\Utilisateur $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \UtilisateurBundle\Entity\Utilisateur
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Set bFichier
     *
     * @param boolean $bFichier
     *
     * @return Formation
     */
    public function setBFichier($bFichier)
    {
        $this->bFichier = $bFichier;

        return $this;
    }

    /**
     * Get bFichier
     *
     * @return boolean
     */
    public function getBFichier()
    {
        return $this->bFichier;
    }
    
    public function setFichier(UploadedFile $fichier)
    {
        $this->fichier = $fichier ;
    }
    
    public function getFichier()
    {
        return $this->fichier ;
    }
}
