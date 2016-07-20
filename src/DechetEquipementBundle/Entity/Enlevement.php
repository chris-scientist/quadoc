<?php
/* Copyright 2016 C. Thubert */

namespace DechetEquipementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Enlevement
 *
 * @ORM\Table(name="t_enlevement_enl")
 * @ORM\Entity(repositoryClass="DechetEquipementBundle\Repository\EnlevementRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Enlevement
{
    /**
     * @var int
     *
     * @ORM\Column(name="enl_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="enl_enleve_le", type="datetime")
     * @Assert\Date()
     */
    private $enleveLe;

    /**
     * @var string
     *
     * @ORM\Column(name="enl_quantite", type="string", length=32)
     * @Assert\Length(max=32)
     * @Assert\NotBlank()
     */
    private $quantite;

    /**
     * @var string
     *
     * @ORM\Column(name="enl_commentaire", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $commentaire;
    
    /**
     * @var UtilisateurBundle\Entity\Utilisateur
     * 
     * @ORM\ManyToOne(targetEntity="UtilisateurBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(name="enl_uti_id", referencedColumnName="uti_id")
     * @Assert\NotNull()
     */
    private $intervenant;
    
    /**
     * @var DechetEquipementBundle\Entity\Dechet
     * 
     * @ORM\ManyToOne(targetEntity="DechetEquipementBundle\Entity\Dechet", inversedBy="historique")
     * @ORM\JoinColumn(name="enl_dec_id", referencedColumnName="dec_id")
     * @Assert\NotNull()
     */
    private $dechet;
    
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
                ->setCommentaire(null) ;
    }
    
    public function getUploadDir()
    {
        $absolutePath = __DIR__ . '/../../../web/upload/' ;
        $uploadDir = $absolutePath . 'documents/enlevements/' ;
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
     * Set enleveLe
     *
     * @param \DateTime $enleveLe
     *
     * @return Enlevement
     */
    public function setEnleveLe($enleveLe)
    {
        $this->enleveLe = $enleveLe;

        return $this;
    }

    /**
     * Get enleveLe
     *
     * @return \DateTime
     */
    public function getEnleveLe()
    {
        return $this->enleveLe;
    }

    /**
     * Set quantite
     *
     * @param string $quantite
     *
     * @return Enlevement
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return string
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Enlevement
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
     * Set intervenant
     *
     * @param \UtilisateurBundle\Entity\Utilisateur $intervenant
     *
     * @return Enlevement
     */
    public function setIntervenant(\UtilisateurBundle\Entity\Utilisateur $intervenant = null)
    {
        $this->intervenant = $intervenant;

        return $this;
    }

    /**
     * Get intervenant
     *
     * @return \UtilisateurBundle\Entity\Utilisateur
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }

    /**
     * Set dechet
     *
     * @param \DechetEquipementBundle\Entity\Dechet $dechet
     *
     * @return Enlevement
     */
    public function setDechet(\DechetEquipementBundle\Entity\Dechet $dechet = null)
    {
        $this->dechet = $dechet;

        return $this;
    }

    /**
     * Get dechet
     *
     * @return \DechetEquipementBundle\Entity\Dechet
     */
    public function getDechet()
    {
        return $this->dechet;
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
}
