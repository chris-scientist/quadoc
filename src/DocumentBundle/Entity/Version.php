<?php
/* Copyright 2016 C. Thubert */

namespace DocumentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Version
 *
 * @ORM\Table(name="t_version_ver")
 * @ORM\Entity(repositoryClass="DocumentBundle\Repository\VersionRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Version
{
    /**
     * @var int
     *
     * @ORM\Column(name="ver_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ver_n_version", type="string", length=32)
     * @Assert\Length(max=32)
     * @Assert\NotBlank()
     */
    private $nVersion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ver_diffuse_le", type="datetime")
     * @Assert\Date()
     */
    private $diffuseLe;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ver_arret_le", type="datetime", nullable=true)
     * @Assert\Date()
     */
    private $arretLe;
    
    /**
     * @var UtilisateurBundle\Entity\Utilisateur
     * 
     * @ORM\ManyToMany(targetEntity="UtilisateurBundle\Entity\Utilisateur")
     * @ORM\JoinTable(
     *  name="tj_versionutilisateur_vut",
     *  joinColumns={@ORM\JoinColumn(name="vut_ver_id", referencedColumnName="ver_id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="vut_uti_id", referencedColumnName="uti_id")}
     * )
     * @Assert\NotNull()
     */
    private $redacteurs;
    
    /**
     * @Assert\File(
     *      mimeTypes = {"application/pdf"},
     *      mimeTypesMessage = "app.err.form.pdfonly"
     * )
     */
    private $fichier;
    
    private $uploadDir ;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->redacteurs = new \Doctrine\Common\Collections\ArrayCollection();
        $this
                ->setArretLe(null) ;
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
    
    public function getUploadDir()
    {
        return $this->uploadDir ;
    }
    
    public function setUploadDir($dir)
    {
        $this->uploadDir = $dir ;
    }
//    
//    /**
//     * @Assert\IsTrue(
//     *      message="app.err.form.datesversion"
//     * )
//     */
    /**
     * @Assert\IsTrue()
     */
    public function isVersionValid()
    {
        $out = false ;
        if( ! is_null($this->arretLe) )
        {
            $interval = $this->diffuseLe->diff( $this->arretLe ) ;
            if( $interval->d >= 0 )
            {
                $out = true ;
            }
        }
        return $out ;
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
     * Set nVersion
     *
     * @param string $nVersion
     *
     * @return Version
     */
    public function setNVersion($nVersion)
    {
        $this->nVersion = $nVersion;

        return $this;
    }

    /**
     * Get nVersion
     *
     * @return string
     */
    public function getNVersion()
    {
        return $this->nVersion;
    }

    /**
     * Set diffuseLe
     *
     * @param \DateTime $diffuseLe
     *
     * @return Version
     */
    public function setDiffuseLe($diffuseLe)
    {
        $this->diffuseLe = $diffuseLe;

        return $this;
    }

    /**
     * Get diffuseLe
     *
     * @return \DateTime
     */
    public function getDiffuseLe()
    {
        return $this->diffuseLe;
    }

    /**
     * Set arretLe
     *
     * @param \DateTime $arretLe
     *
     * @return Version
     */
    public function setArretLe($arretLe)
    {
        $this->arretLe = $arretLe;

        return $this;
    }

    /**
     * Get arretLe
     *
     * @return \DateTime
     */
    public function getArretLe()
    {
        return $this->arretLe;
    }

    /**
     * Add redacteur
     *
     * @param \UtilisateurBundle\Entity\Utilisateur $redacteur
     *
     * @return Version
     */
    public function addRedacteur(\UtilisateurBundle\Entity\Utilisateur $redacteur)
    {
        $this->redacteurs[] = $redacteur;

        return $this;
    }

    /**
     * Remove redacteur
     *
     * @param \UtilisateurBundle\Entity\Utilisateur $redacteur
     */
    public function removeRedacteur(\UtilisateurBundle\Entity\Utilisateur $redacteur)
    {
        $this->redacteurs->removeElement($redacteur);
    }

    /**
     * Get redacteurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRedacteurs()
    {
        return $this->redacteurs;
    }
    
    public function setFichier(UploadedFile $fichier)
    {
        $this->fichier = $fichier ;
    }
    
    public function getFichier()
    {
        return $this->fichier ;
    }
}
