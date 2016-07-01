<?php

namespace DechetEquipementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equipement
 *
 * @ORM\Table(name="t_equipement_eqt")
 * @ORM\Entity(repositoryClass="DechetEquipementBundle\Repository\EquipementRepository")
 */
class Equipement
{
    /**
     * @var int
     *
     * @ORM\Column(name="eqt_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="eqt_descriptif", type="string", length=32)
     */
    private $descriptif;

    /**
     * @var string
     *
     * @ORM\Column(name="eqt_modele", type="string", length=32)
     */
    private $modele;

    /**
     * @var string
     *
     * @ORM\Column(name="eqt_n_serie", type="string", length=32)
     */
    private $nSerie;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="eqt_miseenservice_le", type="datetime")
     */
    private $miseenserviceLe;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="eqt_reforme_le", type="datetime", nullable=true)
     */
    private $reformeLe;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="eqt_fingarentie_le", type="datetime", nullable=true)
     */
    private $fingarentieLe;

    /**
     * @var string
     *
     * @ORM\Column(name="eqt_emplacement", type="string", length=32)
     */
    private $emplacement;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="eqt_achete_le", type="datetime")
     */
    private $acheteLe;

    /**
     * @var string
     *
     * @ORM\Column(name="eqt_n_immobilisation", type="string", length=32, nullable=true, unique=true)
     */
    private $nImmobilisation;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="eqt_b_fichier", type="boolean")
     */
    private $bFichier;
    
    /**
     * @var UtilisateurBundle\Entity\Utilisateur
     * 
     * @ORM\ManyToOne(targetEntity="UtilisateurBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(name="eqt_uti_id", referencedColumnName="uti_id")
     */
    private $responsable;
    
    /**
     * @var DechetEquipementBundle\Entity\Contrat
     * 
     * @ORM\ManyToMany(targetEntity="DechetEquipementBundle\Entity\Contrat")
     * @ORM\JoinTable(
     *  name="tj_equipementcontrat_ecn",
     *  joinColumns={@ORM\JoinColumn(name="ecn_eqt_id", referencedColumnName="eqt_id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="ecn_cnt_id", referencedColumnName="cnt_id")}
     * )
     */
    private $contratequipement;
    
    /**
     * @var DechetEquipementBundle\Entity\Categorie
     * 
     * @ORM\ManyToOne(targetEntity="DechetEquipementBundle\Entity\Categorie")
     * @ORM\JoinColumn(name="eqt_cat_id", referencedColumnName="cat_id")
     */
    private $categorie;
    
    /**
     * @var DechetEquipementBundle\Entity\Marque
     * 
     * @ORM\ManyToOne(targetEntity="DechetEquipementBundle\Entity\Marque")
     * @ORM\JoinColumn(name="eqt_mar_id", referencedColumnName="mar_id")
     */
    private $marque;
    
    /**
     * @var DechetEquipementBundle\Entity\Fournisseur
     * 
     * @ORM\ManyToOne(targetEntity="DechetEquipementBundle\Entity\Fournisseur")
     * @ORM\JoinColumn(name="eqt_fou_id", referencedColumnName="fou_id")
     */
    private $fournisseur;
    
    /**
     * @var UtilisateurBundle\Entity\Equipe
     * 
     * @ORM\ManyToOne(targetEntity="UtilisateurBundle\Entity\Equipe")
     * @ORM\JoinColumn(name="eqt_eqp_id", referencedColumnName="eqp_id")
     */
    private $equipe;
    
    //
    // ManyToMany qui joue le rôle d'un OneToMany !
    // 
    // Cf. http://doctrine-orm.readthedocs.io/projects/doctrine-orm/en/latest/reference/association-mapping.html#one-to-many-unidirectional-with-join-table
    //
    /**
     * @var DechetEquipementBundle\Entity\Intervention
     * 
     * @ORM\ManyToMany(targetEntity="DechetEquipementBundle\Entity\Intervention")
     * @ORM\JoinTable(
     *  name="tj_equipementintervention_ein",
     *  joinColumns={@ORM\JoinColumn(name="ein_eqt_id", referencedColumnName="eqt_id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="ein_int_id", referencedColumnName="int_id", unique=true)}
     * )
     */
    private $historique;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratequipement = new \Doctrine\Common\Collections\ArrayCollection();
        $this->historique = new \Doctrine\Common\Collections\ArrayCollection();
        $this
                ->setReformeLe(null)
                ->setFingarentieLe(null)
                ->setNImmobilisation(null) ;
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
     * Set descriptif
     *
     * @param string $descriptif
     *
     * @return Equipement
     */
    public function setDescriptif($descriptif)
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    /**
     * Get descriptif
     *
     * @return string
     */
    public function getDescriptif()
    {
        return $this->descriptif;
    }

    /**
     * Set modele
     *
     * @param string $modele
     *
     * @return Equipement
     */
    public function setModele($modele)
    {
        $this->modele = $modele;

        return $this;
    }

    /**
     * Get modele
     *
     * @return string
     */
    public function getModele()
    {
        return $this->modele;
    }

    /**
     * Set nSerie
     *
     * @param string $nSerie
     *
     * @return Equipement
     */
    public function setNSerie($nSerie)
    {
        $this->nSerie = $nSerie;

        return $this;
    }

    /**
     * Get nSerie
     *
     * @return string
     */
    public function getNSerie()
    {
        return $this->nSerie;
    }

    /**
     * Set miseenserviceLe
     *
     * @param \DateTime $miseenserviceLe
     *
     * @return Equipement
     */
    public function setMiseenserviceLe($miseenserviceLe)
    {
        $this->miseenserviceLe = $miseenserviceLe;

        return $this;
    }

    /**
     * Get miseenserviceLe
     *
     * @return \DateTime
     */
    public function getMiseenserviceLe()
    {
        return $this->miseenserviceLe;
    }

    /**
     * Set reformeLe
     *
     * @param \DateTime $reformeLe
     *
     * @return Equipement
     */
    public function setReformeLe($reformeLe)
    {
        $this->reformeLe = $reformeLe;

        return $this;
    }

    /**
     * Get reformeLe
     *
     * @return \DateTime
     */
    public function getReformeLe()
    {
        return $this->reformeLe;
    }

    /**
     * Set fingarentieLe
     *
     * @param \DateTime $fingarentieLe
     *
     * @return Equipement
     */
    public function setFingarentieLe($fingarentieLe)
    {
        $this->fingarentieLe = $fingarentieLe;

        return $this;
    }

    /**
     * Get fingarentieLe
     *
     * @return \DateTime
     */
    public function getFingarentieLe()
    {
        return $this->fingarentieLe;
    }

    /**
     * Set emplacement
     *
     * @param string $emplacement
     *
     * @return Equipement
     */
    public function setEmplacement($emplacement)
    {
        $this->emplacement = $emplacement;

        return $this;
    }

    /**
     * Get emplacement
     *
     * @return string
     */
    public function getEmplacement()
    {
        return $this->emplacement;
    }

    /**
     * Set acheteLe
     *
     * @param \DateTime $acheteLe
     *
     * @return Equipement
     */
    public function setAcheteLe($acheteLe)
    {
        $this->acheteLe = $acheteLe;

        return $this;
    }

    /**
     * Get acheteLe
     *
     * @return \DateTime
     */
    public function getAcheteLe()
    {
        return $this->acheteLe;
    }

    /**
     * Set nImmobilisation
     *
     * @param string $nImmobilisation
     *
     * @return Equipement
     */
    public function setNImmobilisation($nImmobilisation)
    {
        $this->nImmobilisation = $nImmobilisation;

        return $this;
    }

    /**
     * Get nImmobilisation
     *
     * @return string
     */
    public function getNImmobilisation()
    {
        return $this->nImmobilisation;
    }

    /**
     * Set responsable
     *
     * @param \UtilisateurBundle\Entity\Utilisateur $responsable
     *
     * @return Equipement
     */
    public function setResponsable(\UtilisateurBundle\Entity\Utilisateur $responsable = null)
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return \UtilisateurBundle\Entity\Utilisateur
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Add contratequipement
     *
     * @param \DechetEquipementBundle\Entity\Contrat $contratequipement
     *
     * @return Equipement
     */
    public function addContratequipement(\DechetEquipementBundle\Entity\Contrat $contratequipement)
    {
        $this->contratequipement[] = $contratequipement;

        return $this;
    }

    /**
     * Remove contratequipement
     *
     * @param \DechetEquipementBundle\Entity\Contrat $contratequipement
     */
    public function removeContratequipement(\DechetEquipementBundle\Entity\Contrat $contratequipement)
    {
        $this->contratequipement->removeElement($contratequipement);
    }

    /**
     * Get contratequipement
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratequipement()
    {
        return $this->contratequipement;
    }

    /**
     * Set categorie
     *
     * @param \DechetEquipementBundle\Entity\Categorie $categorie
     *
     * @return Equipement
     */
    public function setCategorie(\DechetEquipementBundle\Entity\Categorie $categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \DechetEquipementBundle\Entity\Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set marque
     *
     * @param \DechetEquipementBundle\Entity\Marque $marque
     *
     * @return Equipement
     */
    public function setMarque(\DechetEquipementBundle\Entity\Marque $marque = null)
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * Get marque
     *
     * @return \DechetEquipementBundle\Entity\Marque
     */
    public function getMarque()
    {
        return $this->marque;
    }

    /**
     * Set fournisseur
     *
     * @param \DechetEquipementBundle\Entity\Fournisseur $fournisseur
     *
     * @return Equipement
     */
    public function setFournisseur(\DechetEquipementBundle\Entity\Fournisseur $fournisseur = null)
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    /**
     * Get fournisseur
     *
     * @return \DechetEquipementBundle\Entity\Fournisseur
     */
    public function getFournisseur()
    {
        return $this->fournisseur;
    }

    /**
     * Set equipe
     *
     * @param \UtilisateurBundle\Entity\Equipe $equipe
     *
     * @return Equipement
     */
    public function setEquipe(\UtilisateurBundle\Entity\Equipe $equipe = null)
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
     * Add historique
     *
     * @param \DechetEquipementBundle\Entity\Intervention $historique
     *
     * @return Equipement
     */
    public function addHistorique(\DechetEquipementBundle\Entity\Intervention $historique)
    {
        $this->historique[] = $historique;

        return $this;
    }

    /**
     * Remove historique
     *
     * @param \DechetEquipementBundle\Entity\Intervention $historique
     */
    public function removeHistorique(\DechetEquipementBundle\Entity\Intervention $historique)
    {
        $this->historique->removeElement($historique);
    }

    /**
     * Get historique
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHistorique()
    {
        return $this->historique;
    }

    /**
     * Set bFichier
     *
     * @param boolean $bFichier
     *
     * @return Equipement
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
