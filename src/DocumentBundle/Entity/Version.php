<?php

namespace DocumentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Version
 *
 * @ORM\Table(name="t_version_ver")
 * @ORM\Entity(repositoryClass="DocumentBundle\Repository\VersionRepository")
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
     */
    private $nVersion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ver_diffuse_le", type="datetime")
     */
    private $diffuseLe;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ver_arret_le", type="datetime", nullable=true)
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
     */
    private $redacteurs;


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
}
