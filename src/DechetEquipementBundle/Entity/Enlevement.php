<?php

namespace DechetEquipementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Enlevement
 *
 * @ORM\Table(name="t_enlevement_enl")
 * @ORM\Entity(repositoryClass="DechetEquipementBundle\Repository\EnlevementRepository")
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
     */
    private $enleveLe;

    /**
     * @var string
     *
     * @ORM\Column(name="enl_quantite", type="string", length=32)
     */
    private $quantite;

    /**
     * @var string
     *
     * @ORM\Column(name="enl_commentaire", type="string", length=255, nullable=true)
     */
    private $commentaire;
    
    /**
     * @var UtilisateurBundle\Entity\Utilisateur
     * 
     * @ORM\ManyToOne(targetEntity="UtilisateurBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(name="enl_uti_id", referencedColumnName="uti_id")
     */
    private $intervenant;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this
                ->setCommentaire(null) ;
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
}
