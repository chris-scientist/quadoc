<?php
/* Copyright 2016 C. Thubert */

namespace UtilisateurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User;

/**
 * Utilisateur
 *
 * @ORM\Table(name="ts_utilisateur_uti")
 * @ORM\Entity(repositoryClass="UtilisateurBundle\Repository\UtilisateurRepository")
 */
class Utilisateur extends User
{
    /**
     * @var int
     *
     * @ORM\Column(name="uti_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="uti_nom", type="string", length=32)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="uti_prenom", type="string", length=32)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="uti_initiale", type="string", length=8, unique=true, nullable=true)
     */
    private $initiale;
    
    /**
     * @var RHBundle\Entity\Formation
     * 
     * @ORM\OneToMany(targetEntity="RHBundle\Entity\Formation", mappedBy="utilisateur")
     */
    private $formations;
    
    /**
     * @var UtilisateurBundle\Entity\Statut
     * 
     * @ORM\ManyToOne(targetEntity="UtilisateurBundle\Entity\Statut")
     * @ORM\JoinColumn(name="uti_sta_id", nullable=false, referencedColumnName="sta_id")
     */
    private $statut;
    
    /**
     * @var RHBundle\Entity\Stage
     * 
     * @ORM\OneToMany(targetEntity="RHBundle\Entity\Stage", mappedBy="stagiaire")
     */
    private $stages;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(); 
        
        $this->formations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->stages = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString()
    {
        return ( $this->getNom() . " " . $this->getPrenom() ) ;
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Utilisateur
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Utilisateur
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set initiale
     *
     * @param string $initiale
     *
     * @return Utilisateur
     */
    public function setInitiale($initiale)
    {
        $this->initiale = $initiale;

        return $this;
    }

    /**
     * Get initiale
     *
     * @return string
     */
    public function getInitiale()
    {
        return $this->initiale;
    }

    /**
     * Add formation
     *
     * @param \RHBundle\Entity\Formation $formation
     *
     * @return Utilisateur
     */
    public function addFormation(\RHBundle\Entity\Formation $formation)
    {
        $this->formations[] = $formation;

        return $this;
    }

    /**
     * Remove formation
     *
     * @param \RHBundle\Entity\Formation $formation
     */
    public function removeFormation(\RHBundle\Entity\Formation $formation)
    {
        $this->formations->removeElement($formation);
    }

    /**
     * Get formations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormations()
    {
        return $this->formations;
    }

    /**
     * Set statut
     *
     * @param \UtilisateurBundle\Entity\Statut $statut
     *
     * @return Utilisateur
     */
    public function setStatut(\UtilisateurBundle\Entity\Statut $statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return \UtilisateurBundle\Entity\Statut
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Add stage
     *
     * @param \RHBundle\Entity\Stage $stage
     *
     * @return Utilisateur
     */
    public function addStage(\RHBundle\Entity\Stage $stage)
    {
        $this->stages[] = $stage;

        return $this;
    }

    /**
     * Remove stage
     *
     * @param \RHBundle\Entity\Stage $stage
     */
    public function removeStage(\RHBundle\Entity\Stage $stage)
    {
        $this->stages->removeElement($stage);
    }

    /**
     * Get stages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStages()
    {
        return $this->stages;
    }
}
