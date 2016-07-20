<?php
/* Copyright 2016 C. Thubert */

namespace DechetEquipementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Contrat
 *
 * @ORM\Table(name="t_contrat_cnt")
 * @ORM\Entity(repositoryClass="DechetEquipementBundle\Repository\ContratRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Contrat
{
    /**
     * @var int
     *
     * @ORM\Column(name="cnt_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cnt_debut", type="datetime")
     * @Assert\Date()
     */
    private $debut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cnt_fin", type="datetime", nullable=true)
     * @Assert\Date()
     */
    private $fin;

    /**
     * @var string
     *
     * @ORM\Column(name="cnt_numero", type="string", length=32, nullable=true)
     * @Assert\Length(max=32)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="cnt_cout", type="string", length=32, nullable=true)
     * @Assert\Length(max=32)
     */
    private $cout;

    /**
     * @var string
     *
     * @ORM\Column(name="cnt_commentaire", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $commentaire;
    
    /**
     * @var DechetEquipementBundle\Entity\Prestataire
     * 
     * @ORM\ManyToOne(targetEntity="DechetEquipementBundle\Entity\Prestataire")
     * @ORM\JoinColumn(name="cnt_pre_id", nullable=false, referencedColumnName="pre_id")
     * @Assert\NotNull()
     * 
     */
    private $prestataire;

    // Vrai si c'est un contrat déchet, sinon faux si c'est un contrat équipement.
    /**
     * @var bool
     *
     * @ORM\Column(name="cnt_contratdechet", type="boolean")
     */
    private $contratdechet;
    
    /**
     * @var DechetEquipementBundle\Entity\Equipement
     * 
     * @ORM\ManyToMany(
     *  targetEntity="DechetEquipementBundle\Entity\Equipement", 
     *  mappedBy="contratequipement"
     * )
     */
    private $equipements ;
    
    /**
     * @var DechetEquipementBundle\Entity\Dechet
     * 
     * @ORM\ManyToMany(
     *  targetEntity="DechetEquipementBundle\Entity\Dechet", 
     *  mappedBy="contratdechet"
     * )
     */
    private $dechets ;
    
    /**
     * @Assert\File(
     *      mimeTypes = {"application/pdf"},
     *      mimeTypesMessage = "app.err.form.pdfonly"
     * )
     */
    private $fichier;

    public function __construct()
    {
        $this
                ->setFin(null)
                ->setNumero(null)
                ->setCout(null)
                ->setCommentaire(null) ;
        
        $this->setContratdechet(true) ;
        $this->equipements = new \Doctrine\Common\Collections\ArrayCollection() ;
        $this->dechets = new \Doctrine\Common\Collections\ArrayCollection() ;
    }
    
    public function getUploadDir()
    {
        $absolutePath = __DIR__ . '/../../../web/upload/' ;
        $uploadDir = $absolutePath . 'documents/contrats/' ;
        if( $this->getContratdechet() ) {
            $uploadDir .= 'dechets/' ;
        } else {
            $uploadDir .= 'equipements/' ;
        }
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
     * Set debut
     *
     * @param \DateTime $debut
     *
     * @return Contrat
     */
    public function setDebut($debut)
    {
        $this->debut = $debut;

        return $this;
    }

    /**
     * Get debut
     *
     * @return \DateTime
     */
    public function getDebut()
    {
        return $this->debut;
    }

    /**
     * Set fin
     *
     * @param \DateTime $fin
     *
     * @return Contrat
     */
    public function setFin($fin)
    {
        $this->fin = $fin;

        return $this;
    }

    /**
     * Get fin
     *
     * @return \DateTime
     */
    public function getFin()
    {
        return $this->fin;
    }

    /**
     * Set numero
     *
     * @param string $numero
     *
     * @return Contrat
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set cout
     *
     * @param string $cout
     *
     * @return Contrat
     */
    public function setCout($cout)
    {
        $this->cout = $cout;

        return $this;
    }

    /**
     * Get cout
     *
     * @return string
     */
    public function getCout()
    {
        return $this->cout;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Contrat
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
     * Set prestataire
     *
     * @param \DechetEquipementBundle\Entity\Prestataire $prestataire
     *
     * @return Contrat
     */
    public function setPrestataire(\DechetEquipementBundle\Entity\Prestataire $prestataire)
    {
        $this->prestataire = $prestataire;

        return $this;
    }

    /**
     * Get prestataire
     *
     * @return \DechetEquipementBundle\Entity\Prestataire
     */
    public function getPrestataire()
    {
        return $this->prestataire;
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
     * Set contratdechet
     *
     * @param boolean $contratdechet
     *
     * @return Contrat
     */
    public function setContratdechet($contratdechet)
    {
        $this->contratdechet = $contratdechet;

        return $this;
    }

    /**
     * Get contratdechet
     *
     * @return boolean
     */
    public function getContratdechet()
    {
        return $this->contratdechet;
    }

    /**
     * Add equipement
     *
     * @param \DechetEquipementBundle\Entity\Equipement $equipement
     *
     * @return Contrat
     */
    public function addEquipement(\DechetEquipementBundle\Entity\Equipement $equipement)
    {
        $this->equipements[] = $equipement;

        return $this;
    }

    /**
     * Remove equipement
     *
     * @param \DechetEquipementBundle\Entity\Equipement $equipement
     */
    public function removeEquipement(\DechetEquipementBundle\Entity\Equipement $equipement)
    {
        $this->equipements->removeElement($equipement);
    }

    /**
     * Get equipements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEquipements()
    {
        return $this->equipements;
    }

    /**
     * Add dechet
     *
     * @param \DechetEquipementBundle\Entity\Dechet $dechet
     *
     * @return Contrat
     */
    public function addDechet(\DechetEquipementBundle\Entity\Dechet $dechet)
    {
        $this->dechets[] = $dechet;

        return $this;
    }

    /**
     * Remove dechet
     *
     * @param \DechetEquipementBundle\Entity\Dechet $dechet
     */
    public function removeDechet(\DechetEquipementBundle\Entity\Dechet $dechet)
    {
        $this->dechets->removeElement($dechet);
    }

    /**
     * Get dechets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDechets()
    {
        return $this->dechets;
    }
}
