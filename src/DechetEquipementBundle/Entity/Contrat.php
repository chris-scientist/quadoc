<?php

namespace DechetEquipementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contrat
 *
 * @ORM\Table(name="t_contrat_cnt")
 * @ORM\Entity(repositoryClass="DechetEquipementBundle\Repository\ContratRepository")
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
     */
    private $debut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cnt_fin", type="datetime", nullable=true)
     */
    private $fin;

    /**
     * @var string
     *
     * @ORM\Column(name="cnt_numero", type="string", length=32, nullable=true)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="cnt_cout", type="string", length=32, nullable=true)
     */
    private $cout;

    /**
     * @var string
     *
     * @ORM\Column(name="cnt_commentaire", type="string", length=255, nullable=true)
     */
    private $commentaire;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="cnt_b_fichier", type="boolean")
     */
    private $bFichier;
    
    /**
     * @var DechetEquipementBundle\Entity\Prestataire
     * 
     * @ORM\ManyToOne(targetEntity="DechetEquipementBundle\Entity\Prestataire")
     * @ORM\JoinColumn(name="cnt_pre_id", nullable=false, referencedColumnName="pre_id")
     * 
     */
    private $prestataire;
    
    /**
     * @var DechetEquipementBundle\Entity\Typecontrat
     * 
     * @ORM\ManyToOne(targetEntity="DechetEquipementBundle\Entity\Typecontrat")
     * @ORM\JoinColumn(name="cnt_tcn_id", nullable=false, referencedColumnName="tcn_id")
     */
    private $typecontrat;


    public function __construct()
    {
        $this
                ->setFin(null)
                ->setNumero(null)
                ->setCout(null)
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

    /**
     * Set typecontrat
     *
     * @param \DechetEquipementBundle\Entity\Typecontrat $typecontrat
     *
     * @return Contrat
     */
    public function setTypecontrat(\DechetEquipementBundle\Entity\Typecontrat $typecontrat)
    {
        $this->typecontrat = $typecontrat;

        return $this;
    }

    /**
     * Get typecontrat
     *
     * @return \DechetEquipementBundle\Entity\Typecontrat
     */
    public function getTypecontrat()
    {
        return $this->typecontrat;
    }

    /**
     * Set bFichier
     *
     * @param boolean $bFichier
     *
     * @return Contrat
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
