<?php

namespace DocumentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cahierlaboratoire
 *
 * @ORM\Table(name="t_cahierlaboratoire_cla")
 * @ORM\Entity(repositoryClass="DocumentBundle\Repository\CahierlaboratoireRepository")
 */
class Cahierlaboratoire
{
    /**
     * @var int
     *
     * @ORM\Column(name="cla_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="cla_n_interne", type="string", length=32)
     */
    private $nInterne;

    /**
     * @var string
     *
     * @ORM\Column(name="cla_n_ministere", type="string", length=32)
     */
    private $nMinistere;

    /**
     * @var string
     *
     * @ORM\Column(name="cla_intitule", type="string", length=32)
     */
    private $intitule;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cla_ouvert_le", type="datetime")
     */
    private $ouvertLe;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cla_ferme_le", type="datetime", nullable=true)
     */
    private $fermeLe;

    /**
     * @var string
     *
     * @ORM\Column(name="cla_localisation", type="string", length=32)
     */
    private $localisation;
    
    /**
     * @var UtilisateurBundle\Entity\Utilisateur
     * 
     * @ORM\ManyToMany(targetEntity="UtilisateurBundle\Entity\Utilisateur")
     * @ORM\JoinTable(
     *  name="tj_cahierlaboratoireutilisateur_cut",
     *  joinColumns={@ORM\JoinColumn(name="cut_cla_id", referencedColumnName="cla_id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="cut_uti_id", referencedColumnName="uti_id")}
     * )
     */
    private $utilisateurs;
    
    /**
     * @var DocumentBundle\Entity\Support
     * 
     * @ORM\ManyToOne(targetEntity="DocumentBundle\Entity\Support")
     * @ORM\JoinColumn(name="cla_sup_id", nullable=false, referencedColumnName="sup_id")
     */
    private $support;
    
    /**
     * @var UtilisateurBundle\Entity\Equipe
     * 
     * @ORM\ManyToOne(targetEntity="UtilisateurBundle\Entity\Equipe")
     * @ORM\JoinColumn(name="cla_eqp_id", nullable=false, referencedColumnName="eqp_id")
     */
    private $equipe;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->utilisateurs = new \Doctrine\Common\Collections\ArrayCollection();
        $this
                ->setFermeLe(null) ;
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
     * Set nInterne
     *
     * @param string $nInterne
     *
     * @return Cahierlaboratoire
     */
    public function setNInterne($nInterne)
    {
        $this->nInterne = $nInterne;

        return $this;
    }

    /**
     * Get nInterne
     *
     * @return string
     */
    public function getNInterne()
    {
        return $this->nInterne;
    }

    /**
     * Set nMinistere
     *
     * @param string $nMinistere
     *
     * @return Cahierlaboratoire
     */
    public function setNMinistere($nMinistere)
    {
        $this->nMinistere = $nMinistere;

        return $this;
    }

    /**
     * Get nMinistere
     *
     * @return string
     */
    public function getNMinistere()
    {
        return $this->nMinistere;
    }

    /**
     * Set intitule
     *
     * @param string $intitule
     *
     * @return Cahierlaboratoire
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
     * Set ouvertLe
     *
     * @param \DateTime $ouvertLe
     *
     * @return Cahierlaboratoire
     */
    public function setOuvertLe($ouvertLe)
    {
        $this->ouvertLe = $ouvertLe;

        return $this;
    }

    /**
     * Get ouvertLe
     *
     * @return \DateTime
     */
    public function getOuvertLe()
    {
        return $this->ouvertLe;
    }

    /**
     * Set fermeLe
     *
     * @param \DateTime $fermeLe
     *
     * @return Cahierlaboratoire
     */
    public function setFermeLe($fermeLe)
    {
        $this->fermeLe = $fermeLe;

        return $this;
    }

    /**
     * Get fermeLe
     *
     * @return \DateTime
     */
    public function getFermeLe()
    {
        return $this->fermeLe;
    }

    /**
     * Set localisation
     *
     * @param string $localisation
     *
     * @return Cahierlaboratoire
     */
    public function setLocalisation($localisation)
    {
        $this->localisation = $localisation;

        return $this;
    }

    /**
     * Get localisation
     *
     * @return string
     */
    public function getLocalisation()
    {
        return $this->localisation;
    }

    /**
     * Add utilisateur
     *
     * @param \UtilisateurBundle\Entity\Utilisateur $utilisateur
     *
     * @return Cahierlaboratoire
     */
    public function addUtilisateur(\UtilisateurBundle\Entity\Utilisateur $utilisateur)
    {
        $this->utilisateurs[] = $utilisateur;

        return $this;
    }

    /**
     * Remove utilisateur
     *
     * @param \UtilisateurBundle\Entity\Utilisateur $utilisateur
     */
    public function removeUtilisateur(\UtilisateurBundle\Entity\Utilisateur $utilisateur)
    {
        $this->utilisateurs->removeElement($utilisateur);
    }

    /**
     * Get utilisateurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUtilisateurs()
    {
        return $this->utilisateurs;
    }

    /**
     * Set support
     *
     * @param \DocumentBundle\Entity\Support $support
     *
     * @return Cahierlaboratoire
     */
    public function setSupport(\DocumentBundle\Entity\Support $support)
    {
        $this->support = $support;

        return $this;
    }

    /**
     * Get support
     *
     * @return \DocumentBundle\Entity\Support
     */
    public function getSupport()
    {
        return $this->support;
    }

    /**
     * Set equipe
     *
     * @param \UtilisateurBundle\Entity\Equipe $equipe
     *
     * @return Cahierlaboratoire
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
}
