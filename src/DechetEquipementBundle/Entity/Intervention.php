<?php

namespace DechetEquipementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Intervention
 *
 * @ORM\Table(name="t_intervention_int")
 * @ORM\Entity(repositoryClass="DechetEquipementBundle\Repository\InterventionRepository")
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
     */
    private $operation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="int_afaire_le", type="datetime", nullable=true)
     */
    private $afaireLe;

    /**
     * @var string
     *
     * @ORM\Column(name="int_commentaire", type="string", length=255, nullable=true)
     */
    private $commentaire;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="int_effectue_le", type="datetime", nullable=true)
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
     */
    private $operateur;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this
                ->setAfaireLe(null)
                ->setCommentaire(null)
                ->setEffectueLe(null) ;
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
}
