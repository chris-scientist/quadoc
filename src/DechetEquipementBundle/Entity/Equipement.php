<?php
/* Copyright 2016 C. Thubert */

namespace DechetEquipementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Equipement
 *
 * @ORM\Table(name="t_equipement_eqt")
 * @ORM\Entity(repositoryClass="DechetEquipementBundle\Repository\EquipementRepository")
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\Column(name="eqt_nom", type="string", length=32)
     * @Assert\Length(max=32)
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="eqt_modele", type="string", length=32)
     * @Assert\Length(max=32)
     * @Assert\NotBlank()
     */
    private $modele;

    /**
     * @var string
     *
     * @ORM\Column(name="eqt_n_serie", type="string", length=32)
     * @Assert\Length(max=32)
     * @Assert\NotBlank()
     */
    private $nSerie;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="eqt_miseenservice_le", type="datetime")
     * @Assert\Date()
     */
    private $miseenserviceLe;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="eqt_reforme_le", type="datetime", nullable=true)
     * @Assert\Date()
     */
    private $reformeLe;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="eqt_fingarantie_le", type="datetime", nullable=true)
     * @Assert\Date()
     */
    private $fingarantieLe;

    /**
     * @var string
     *
     * @ORM\Column(name="eqt_emplacement", type="string", length=32)
     * @Assert\Length(max=32)
     * @Assert\NotBlank()
     */
    private $emplacement;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="eqt_achete_le", type="datetime")
     * @Assert\Date()
     */
    private $acheteLe;

    /**
     * @var string
     *
     * @ORM\Column(name="eqt_n_immobilisation", type="string", length=32, nullable=true, unique=true)
     * @Assert\Length(max=32)
     */
    private $nImmobilisation;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="eqt_b_fichier", type="boolean")
     */
    private $bFichier;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="eqt_b_photo", type="boolean")
     */
    private $bPhoto;
    
    /**
     * @var UtilisateurBundle\Entity\Utilisateur
     * 
     * @ORM\ManyToOne(targetEntity="UtilisateurBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(name="eqt_uti_id", referencedColumnName="uti_id")
     * @Assert\NotNull()
     * 
     */
    private $responsable;
    
    /**
     * @var DechetEquipementBundle\Entity\Contrat
     * 
     * @ORM\ManyToMany(targetEntity="DechetEquipementBundle\Entity\Contrat", inversedBy="equipements")
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
     * @Assert\NotNull()
     */
    private $categorie;
    
    /**
     * @var DechetEquipementBundle\Entity\Marque
     * 
     * @ORM\ManyToOne(targetEntity="DechetEquipementBundle\Entity\Marque")
     * @ORM\JoinColumn(name="eqt_mar_id", referencedColumnName="mar_id")
     * @Assert\NotNull()
     */
    private $marque;
    
    /**
     * @var DechetEquipementBundle\Entity\Fournisseur
     * 
     * @ORM\ManyToOne(targetEntity="DechetEquipementBundle\Entity\Fournisseur")
     * @ORM\JoinColumn(name="eqt_fou_id", referencedColumnName="fou_id")
     * @Assert\NotNull()
     */
    private $fournisseur;
    
    /**
     * @var UtilisateurBundle\Entity\Equipe
     * 
     * @ORM\ManyToOne(targetEntity="UtilisateurBundle\Entity\Equipe")
     * @ORM\JoinColumn(name="eqt_eqp_id", referencedColumnName="eqp_id")
     * @Assert\NotNull()
     */
    private $equipe;
    
    /**
     * @var DechetEquipementBundle\Entity\Intervention
     * 
     * @ORM\OneToMany(targetEntity="DechetEquipementBundle\Entity\Intervention", mappedBy="equipement")
     */
    private $historique;
    
    /**
     * @var string
     *
     * @ORM\Column(name="eqt_caracteristiques", type="string", length=255, nullable=true)
     */
    private $caracteristiques;
    
    /**
     * @Assert\File(
     *      mimeTypes = {"application/pdf"},
     *      mimeTypesMessage = "app.err.form.pdfonly"
     * )
     */
    private $fichier ;
    
    /**
     * @Assert\Image(
     *      mimeTypes = {"image/jpeg"}
     * )
     */
    private $photo ;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contratequipement = new \Doctrine\Common\Collections\ArrayCollection();
        $this->historique = new \Doctrine\Common\Collections\ArrayCollection();
        $this
                ->setReformeLe(null)
                ->setFingarantieLe(null)
                ->setNImmobilisation(null)
                ->setCaracteristiques(null)
                ->setBFichier(false)
                ->setBPhoto(false) ;
    }
    
    public function __toString()
    {
        return $this->getNom() ;
    }
    
    static public function getLabels()
    {
        $labelArray = array(
            'nom',
            'équipe',
            'responsable',
            'n série',
            'acheté le',
            'fin garantie le',
            'fournisseur',
            'marque',
            'parc',
            'catégorie',
            'modèle',
            'mise en service le',
            'reformé le',
            'emplacement',
            'n immobilisation',
            'caractéristiques'
        ) ;
        return $labelArray ;
    }
    
    public function toArray()
    {
        $outArray = array() ;
        
        $outArray[] = $this->nom ;
        $outArray[] = $this->equipe->getNom() ;
        $outArray[] = $this->responsable->getNom() . ' ' . $this->responsable->getPrenom() ;
        $outArray[] = $this->nSerie ;
        $outArray[] = $this->acheteLe->format('d/m/Y') ;
        $outArray[] = $this->fingarantieLe->format('d/m/Y') ;
        $outArray[] = $this->fournisseur->getNom() ;
        $outArray[] = $this->marque->getNom() ;
        $outArray[] = $this->categorie->getParc()->getNom() ;
        $outArray[] = $this->categorie->getNom() ;
        $outArray[] = $this->modele ;
        $outArray[] = $this->miseenserviceLe->format('d/m/Y') ;
        $reformeLe = $this->reformeLe ;
        if( ! is_null($reformeLe) ) {
            $outArray[] = $reformeLe->format('d/m/Y') ;
        } else {
            $outArray[] = '' ;
        }
        $outArray[] = $this->emplacement ;
        $outArray[] = $this->nImmobilisation ;
        $outArray[] = $this->caracteristiques ;
        
        return $outArray ;
    }
    
    public function getUploadFichierDir()
    {
        $absolutePath = __DIR__ . '/../../../web/upload/' ;
        $uploadFichierDir = $absolutePath . "documents/equipements/" ;
        return $uploadFichierDir ;
    }
    
    public function getUploadPhotoDir()
    {
        $absolutePath = __DIR__ . '/../../../web/upload/' ;
        $uploadPhotoDir = $absolutePath . "images/equipements/" ;
        return $uploadPhotoDir ;
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
                $this->getUploadFichierDir(),
                $nomFichier
            ) ;
        }
        
        if( ! is_null($this->photo) )
        {
            $nomPhoto = $this->getId() . '.jpeg' ;
            $this->photo->move(
                $this->getUploadPhotoDir(),
                $nomPhoto
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
            $this->fichier = $this->getUploadFichierDir() . $this->getId() . '.pdf' ;
        }
        
        if( $this->getBPhoto() )
        {
            $this->photo = $this->getUploadPhotoDir() . $this->getId() . '.jpeg' ;
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
        
        $photo = $this->getPhoto() ;
        if( file_exists($photo) )
        {
            unlink($photo) ;
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Equipement
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
     * Set fingarantieLe
     *
     * @param \DateTime $fingarantieLe
     *
     * @return Equipement
     */
    public function setFingarantieLe($fingarantieLe)
    {
        $this->fingarantieLe = $fingarantieLe;

        return $this;
    }

    /**
     * Get fingarantieLe
     *
     * @return \DateTime
     */
    public function getFingarantieLe()
    {
        return $this->fingarantieLe;
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
        $historique->setEquipement( $this ) ;
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

    /**
     * Set bPhoto
     *
     * @param boolean $bPhoto
     *
     * @return Equipement
     */
    public function setBPhoto($bPhoto)
    {
        $this->bPhoto = $bPhoto;

        return $this;
    }

    /**
     * Get bPhoto
     *
     * @return boolean
     */
    public function getBPhoto()
    {
        return $this->bPhoto;
    }
    
    public function setFichier(UploadedFile $fichier)
    {
        $this->fichier = $fichier ;
    }
    
    public function getFichier()
    {
        return $this->fichier ;
    }
    
    public function setPhoto(UploadedFile $photo)
    {
        $this->photo = $photo ;
    }
    
    public function getPhoto()
    {
        return $this->photo ;
    }

    /**
     * Set caracteristiques
     *
     * @param string $caracteristiques
     *
     * @return Equipement
     */
    public function setCaracteristiques($caracteristiques)
    {
        $this->caracteristiques = $caracteristiques;

        return $this;
    }

    /**
     * Get caracteristiques
     *
     * @return string
     */
    public function getCaracteristiques()
    {
        return $this->caracteristiques;
    }
}
