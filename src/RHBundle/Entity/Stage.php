<?php
/* Copyright 2016 C. Thubert */

namespace RHBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stage
 *
 * @ORM\Table(name="t_stage_stg")
 * @ORM\Entity(repositoryClass="RHBundle\Repository\StageRepository")
 */
class Stage
{
    /**
     * @var int
     *
     * @ORM\Column(name="stg_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="stg_sujet", type="string", length=255)
     */
    private $sujet;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="stg_arrive_le", type="datetime")
     */
    private $arriveLe;

    /**
     * @var string
     *
     * @ORM\Column(name="stg_duree", type="string", length=32)
     */
    private $duree;

    /**
     * @var string
     *
     * @ORM\Column(name="stg_etablissement", type="string", length=32)
     */
    private $etablissement;

    /**
     * @var string
     *
     * @ORM\Column(name="stg_diplome", type="string", length=32)
     */
    private $diplome;
    
    /**
     * @var UtilisateurBundle\Entity\Utilisateur
     * 
     * @ORM\ManyToOne(targetEntity="UtilisateurBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(name="stg_uti_id_encadrant", nullable=false, referencedColumnName="uti_id")
     */
    private $encadrant;
    
    /**
     * @var UtilisateurBundle\Entity\Equipe
     * 
     * @ORM\ManyToOne(targetEntity="UtilisateurBundle\Entity\Equipe")
     * @ORM\JoinColumn(name="stg_eqp_id", nullable=false, referencedColumnName="eqp_id")
     */
    private $equipe;
    
    /**
     * @var UtilisateurBundle\Entity\Utilisateur
     * 
     * @ORM\ManyToOne(targetEntity="UtilisateurBundle\Entity\Utilisateur", inversedBy="stages")
     * @ORM\JoinColumn(name="stg_uti_id_stagiaire", referencedColumnName="uti_id")
     */
    private $stagiaire;
    

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
     * Set sujet
     *
     * @param string $sujet
     *
     * @return Stage
     */
    public function setSujet($sujet)
    {
        $this->sujet = $sujet;

        return $this;
    }

    /**
     * Get sujet
     *
     * @return string
     */
    public function getSujet()
    {
        return $this->sujet;
    }

    /**
     * Set arriveLe
     *
     * @param \DateTime $arriveLe
     *
     * @return Stage
     */
    public function setArriveLe($arriveLe)
    {
        $this->arriveLe = $arriveLe;

        return $this;
    }

    /**
     * Get arriveLe
     *
     * @return \DateTime
     */
    public function getArriveLe()
    {
        return $this->arriveLe;
    }

    /**
     * Set duree
     *
     * @param string $duree
     *
     * @return Stage
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return string
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set etablissement
     *
     * @param string $etablissement
     *
     * @return Stage
     */
    public function setEtablissement($etablissement)
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    /**
     * Get etablissement
     *
     * @return string
     */
    public function getEtablissement()
    {
        return $this->etablissement;
    }

    /**
     * Set diplome
     *
     * @param string $diplome
     *
     * @return Stage
     */
    public function setDiplome($diplome)
    {
        $this->diplome = $diplome;

        return $this;
    }

    /**
     * Get diplome
     *
     * @return string
     */
    public function getDiplome()
    {
        return $this->diplome;
    }

    /**
     * Set encadrant
     *
     * @param \UtilisateurBundle\Entity\Utilisateur $encadrant
     *
     * @return Stage
     */
    public function setEncadrant(\UtilisateurBundle\Entity\Utilisateur $encadrant)
    {
        $this->encadrant = $encadrant;

        return $this;
    }

    /**
     * Get encadrant
     *
     * @return \UtilisateurBundle\Entity\Utilisateur
     */
    public function getEncadrant()
    {
        return $this->encadrant;
    }

    /**
     * Set equipe
     *
     * @param \UtilisateurBundle\Entity\Equipe $equipe
     *
     * @return Stage
     */
    public function setEquipe(\UtilisateurBundle\Entity\Equipe $equipe)
    {
        $this->equipe = $equipe;

        return $this;
    }

    /**
     * Get equipe
     *
     * @return \UtilisateurBundle\Entity\Equipe
     */
    public function getEquipe()
    {
        return $this->equipe;
    }

    /**
     * Set stagiaire
     *
     * @param \UtilisateurBundle\Entity\Utilisateur $stagiaire
     *
     * @return Stage
     */
    public function setStagiaire(\UtilisateurBundle\Entity\Utilisateur $stagiaire = null)
    {
        $this->stagiaire = $stagiaire;

        return $this;
    }

    /**
     * Get stagiaire
     *
     * @return \UtilisateurBundle\Entity\Utilisateur
     */
    public function getStagiaire()
    {
        return $this->stagiaire;
    }
}
