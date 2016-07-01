<?php

namespace DocumentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Protocole
 *
 * @ORM\Table(name="tr_protocole_pto")
 * @ORM\Entity(repositoryClass="DocumentBundle\Repository\ProtocoleRepository")
 */
class Protocole
{
    /**
     * @var int
     *
     * @ORM\Column(name="pto_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="pto_titre", type="string", length=32)
     */
    private $titre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="pto_debut", type="datetime")
     */
    private $debut;
    
    /**
     * @var string
     *
     * @ORM\Column(name="pto_numero", type="string", length=32, unique=true)
     */
    private $numero;
    
    //
    // ManyToMany qui joue le rôle d'un OneToMany !
    // 
    // Cf. http://doctrine-orm.readthedocs.io/projects/doctrine-orm/en/latest/reference/association-mapping.html#one-to-many-unidirectional-with-join-table
    //
    /**
     * @var DocumentBundle\Entity\Version
     * 
     * @ORM\ManyToMany(targetEntity="DocumentBundle\Entity\Version")
     * @ORM\JoinTable(
     *  name="tj_protocoleversion_pve",
     *  joinColumns={@ORM\JoinColumn(name="pve_pto_id", referencedColumnName="pto_id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="pve_ver_id", referencedColumnName="ver_id", unique=true)}
     * )
     */
    private $versions;
    
    /**
     * @var UtilisateurBundle\Entity\Equipe
     * 
     * @ORM\ManyToOne(targetEntity="UtilisateurBundle\Entity\Equipe")
     * @ORM\JoinColumn(name="pto_eqp_id", nullable=false, referencedColumnName="eqp_id")
     */
    private $equipe;
    
    /**
     * @var UtilisateurBundle\Entity\Utilisateur
     * 
     * @ORM\ManyToOne(targetEntity="UtilisateurBundle\Entity\Utilisateur")
     * @ORM\JoinColumn(name="pto_uti_id", nullable=false, referencedColumnName="uti_id")
     */
    private $responsable;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->versions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set titre
     *
     * @param string $titre
     *
     * @return Protocole
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set debut
     *
     * @param \DateTime $debut
     *
     * @return Protocole
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
     * Add version
     *
     * @param \DocumentBundle\Entity\Version $version
     *
     * @return Protocole
     */
    public function addVersion(\DocumentBundle\Entity\Version $version)
    {
        $this->versions[] = $version;

        return $this;
    }

    /**
     * Remove version
     *
     * @param \DocumentBundle\Entity\Version $version
     */
    public function removeVersion(\DocumentBundle\Entity\Version $version)
    {
        $this->versions->removeElement($version);
    }

    /**
     * Get versions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVersions()
    {
        return $this->versions;
    }

    /**
     * Set equipe
     *
     * @param \UtilisateurBundle\Entity\Equipe $equipe
     *
     * @return Protocole
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
     * Set responsable
     *
     * @param \UtilisateurBundle\Entity\Utilisateur $responsable
     *
     * @return Protocole
     */
    public function setResponsable(\UtilisateurBundle\Entity\Utilisateur $responsable)
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
     * Set numero
     *
     * @param string $numero
     *
     * @return Protocole
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
}
