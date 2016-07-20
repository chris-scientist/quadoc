<?php
/* Copyright 2016 C. Thubert */

namespace DechetEquipementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Intervention
 *
 * @ORM\Table(name="t_intervention_int")
 * @ORM\Entity(repositoryClass="DechetEquipementBundle\Repository\InterventionRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Intervention
{
    /**
     * @var int
     *
     * @ORM\Column(name="int_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="int_operation", type="string", length=32)
     * @Assert\Length(max=32)
     * @Assert\NotBlank()
     */
    private $operation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="int_afaire_le", type="datetime", nullable=true)
     * @Assert\Date()
     */
    private $afaireLe;

    /**
     * @var string
     *
     * @ORM\Column(name="int_commentaire", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $commentaire;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="int_effectue_le", type="datetime", nullable=true)
     * @Assert\Date()
     */
    private $effectueLe;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="int_b_fichier", type="boolean")
     */
    private $bFichier;
    
    /**
     * @var UtilisateurBundle\Entity\Utilisateur
     * 
     * @ORM\ManyToOne(targetEntity="UtilisateurBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(name="int_uti_id", referencedColumnName="uti_id")
     * @Assert\NotNull()
     */
    private $operateur;
    
    /**
     * @var DechetEquipementBundle\Entity\Equipement
     * 
     * @ORM\ManyToOne(targetEntity="DechetEquipementBundle\Entity\Equipement", inversedBy="historique")
     * @ORM\JoinColumn(name="int_eqt_id", referencedColumnName="eqt_id")
     * @Assert\NotNull()
     */
    private $equipement;
    
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
                ->setAfaireLe(null)
                ->setCommentaire(null)
                ->setEffectueLe(null)
                ->setBFichier(false) ;
    }
    
    public function getUploadDir()
    {
        $absolutePath = __DIR__ . '/../../../web/upload/' ;
        $uploadDir = $absolutePath . 'documents/interventions/' ;
        return $uploadDir ;
    }
    
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if( ! is_null($this->fichier) )
        {
            $nomFichier = $this->getId() . '.pdf' ;
            $this->fichier->move(
                $this->getUploadDir(),
                $nomFichier
            ) ;
        }
    }
    
    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        if( $this->getBFichier() )
        {
            $this->fichier = $this->getUploadDir() . $this->getId() . '.pdf' ;
        }
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
     * Set operation
     *
     * @param string $operation
     *
     * @return Intervention
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * Get operation
     *
     * @return string
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * Set afaireLe
     *
     * @param \DateTime $afaireLe
     *
     * @return Intervention
     */
    public function setAfaireLe($afaireLe)
    {
        $this->afaireLe = $afaireLe;

        return $this;
    }

    /**
     * Get afaireLe
     *
     * @return \DateTime
     */
    public function getAfaireLe()
    {
        return $this->afaireLe;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Intervention
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set effectueLe
     *
     * @param \DateTime $effectueLe
     *
     * @return Intervention
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
     * Set operateur
     *
     * @param \UtilisateurBundle\Entity\Utilisateur $operateur
     *
     * @return Intervention
     */
    public function setOperateur(\UtilisateurBundle\Entity\Utilisateur $operateur = null)
    {
        $this->operateur = $operateur;

        return $this;
    }

    /**
     * Get operateur
     *
     * @return \UtilisateurBundle\Entity\Utilisateur
     */
    public function getOperateur()
    {
        return $this->operateur;
    }

    /**
     * Set bFichier
     *
     * @param boolean $bFichier
     *
     * @return Intervention
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
        
        return $this ;
    }
    
    public function getFichier()
    {
        return $this->fichier ;
    }

    /**
     * Set equipement
     *
     * @param \DechetEquipementBundle\Entity\Equipement $equipement
     *
     * @return Intervention
     */
    public function setEquipement(\DechetEquipementBundle\Entity\Equipement $equipement = null)
    {
        $this->equipement = $equipement;

        return $this;
    }

    /**
     * Get equipement
     *
     * @return \DechetEquipementBundle\Entity\Equipement
     */
    public function getEquipement()
    {
        return $this->equipement;
    }
}
