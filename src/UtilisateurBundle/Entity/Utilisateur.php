<?php

namespace UtilisateurBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Utilisateur
 *
 * @ORM\Table(name="ts_utilisateur_uti")
 * @ORM\Entity(repositoryClass="UtilisateurBundle\Repository\UtilisateurRepository")
 */
class Utilisateur
{
    /**
     * @var int
     *
     * @ORM\Column(name="uti_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
     * @ORM\Column(name="uti_initiale", type="string", length=8, unique=true)
     */
    private $initiale;
    
    /**
     * @var RHBundle\Entity\Formation
     * 
     * @ORM\ManyToMany(targetEntity="RHBundle\Entity\Formation")
     * @ORM\JoinTable(
     *  name="tj_utilisateurformation_ufa",
     *  joinColumns={@ORM\JoinColumn(name="ufa_uti_id", referencedColumnName="uti_id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="ufa_fat_id", referencedColumnName="fat_id")}
     * )
     */
    private $formations;
    
    /**
     * @var UtilisateurBundle\Entity\Statut
     * 
     * @ORM\ManyToOne(targetEntity="UtilisateurBundle\Entity\Statut")
     * @ORM\JoinColumn(name="uti_sta_id", nullable=false, referencedColumnName="sta_id")
     */
    private $statut;
    
    //
    // ManyToMany qui joue le rôle d'un OneToMany !
    // 
    // Cf. http://doctrine-orm.readthedocs.io/projects/doctrine-orm/en/latest/reference/association-mapping.html#one-to-many-unidirectional-with-join-table
    //
    /**
     * @var RHBundle\Entity\Stage
     * 
     * @ORM\ManyToMany(targetEntity="RHBundle\Entity\Stage")
     * @ORM\JoinTable(
     *  name="tj_utilisateurstage_ust",
     *  joinColumns={@ORM\JoinColumn(name="ust_uti_id", referencedColumnName="uti_id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="ust_stg_id", referencedColumnName="stg_id", unique=true)}
     * )
     */
    private $stages;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->formations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->stages = new \Doctrine\Common\Collections\ArrayCollection();
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
