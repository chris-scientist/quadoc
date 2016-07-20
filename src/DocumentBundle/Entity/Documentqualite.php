<?php
/* Copyright 2016 C. Thubert */

namespace DocumentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Documentqualite
 *
 * @ORM\Table(name="t_documentqualite_dqu")
 * @ORM\Entity(repositoryClass="DocumentBundle\Repository\DocumentqualiteRepository")
 */
class Documentqualite extends Document
{
    /**
     * @var int
     *
     * @ORM\Column(name="dqu_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="dqu_commentaire", type="string", length=255, nullable=true)
     * @Assert\Length(max=255)
     */
    private $commentaire;

    /**
     * @var string
     *
     * @ORM\Column(name="dqu_reference", type="string", length=32, unique=true)
     * @Assert\Length(max=32)
     * @Assert\NotBlank()
     */
    private $reference;

    /**
     * @var bool
     *
     * @ORM\Column(name="dqu_interne", type="boolean")
     * @Assert\Type("bool")
     */
    private $interne;
    
    /**
     * @var DocumentBundle\Entity\Domaine
     * 
     * @ORM\ManyToOne(targetEntity="DocumentBundle\Entity\Domaine")
     * @ORM\JoinColumn(name="dqu_dom_id", nullable=false, referencedColumnName="dom_id")
     * @Assert\NotNull()
     */
    private $domaine;
    
    /**
     * @var DocumentBundle\Entity\Forme
     * 
     * @ORM\ManyToOne(targetEntity="DocumentBundle\Entity\Forme")
     * @ORM\JoinColumn(name="dqu_for_id", nullable=false, referencedColumnName="for_id")
     * @Assert\NotNull()
     */
    private $forme;
    
    /**
     * @var UtilisateurBundle\Entity\Equipe
     * 
     * @ORM\ManyToMany(targetEntity="UtilisateurBundle\Entity\Equipe")
     * @ORM\JoinTable(
     *  name="tj_documentqualiteequipe_dqe",
     *  joinColumns={@ORM\JoinColumn(name="dqe_dqu_id", referencedColumnName="dqu_id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="dqe_eqp_id", referencedColumnName="eqp_id")}
     * ) 
     * @Assert\NotNull()
     */
    private $equipes;
    
    // -------------------------------------------------------------------------
    // Attributs (ci-dessous) herités de Document
    //
    
    /**
     * @var string
     * 
     * @ORM\Column(name="dqu_titre", type="string", length=32)
     * @Assert\Length(max=32)
     * @Assert\NotBlank()
     */
    protected $titre; 
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="dqu_archive_le", type="datetime", nullable=true)
     * @Assert\Date()
     */
    protected $archiveLe;
    
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
     *  name="tj_documentqualiteversion_dqv",
     *  joinColumns={@ORM\JoinColumn(name="dqv_dqu_id", referencedColumnName="dqu_id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="dqv_ver_id", referencedColumnName="ver_id", unique=true)}
     * )
     * @ORM\OrderBy({"diffuseLe" = "ASC"})
     * @Assert\NotNull()
     * @Assert\Valid()
     */
    protected $versions;
    
    //
    // Attributs (ci-dessus) herités de Document
    // -------------------------------------------------------------------------


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct() ;
        
        $this->equipes = new \Doctrine\Common\Collections\ArrayCollection();
        
        $this
                ->setCommentaire(null)
                ->setInterne(true);
    }
    
    public function getUploadDir()
    {
        $dirPath = 'documents/qualite/' ;
        $dir = $this->getPathUpload() . $dirPath ;
        return $dir ;
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
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Documentqualite
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
     * Set reference
     *
     * @param string $reference
     *
     * @return Documentqualite
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set interne
     *
     * @param boolean $interne
     *
     * @return Documentqualite
     */
    public function setInterne($interne)
    {
        $this->interne = $interne;

        return $this;
    }

    /**
     * Get interne
     *
     * @return bool
     */
    public function getInterne()
    {
        return $this->interne;
    }

    /**
     * Set domaine
     *
     * @param \DocumentBundle\Entity\Domaine $domaine
     *
     * @return Documentqualite
     */
    public function setDomaine(\DocumentBundle\Entity\Domaine $domaine)
    {
        $this->domaine = $domaine;

        return $this;
    }

    /**
     * Get domaine
     *
     * @return \DocumentBundle\Entity\Domaine
     */
    public function getDomaine()
    {
        return $this->domaine;
    }

    /**
     * Set forme
     *
     * @param \DocumentBundle\Entity\Forme $forme
     *
     * @return Documentqualite
     */
    public function setForme(\DocumentBundle\Entity\Forme $forme)
    {
        $this->forme = $forme;

        return $this;
    }

    /**
     * Get forme
     *
     * @return \DocumentBundle\Entity\Forme
     */
    public function getForme()
    {
        return $this->forme;
    }

    /**
     * Add equipe
     *
     * @param \UtilisateurBundle\Entity\Equipe $equipe
     *
     * @return Documentqualite
     */
    public function addEquipe(\UtilisateurBundle\Entity\Equipe $equipe)
    {
        $this->equipes[] = $equipe;

        return $this;
    }

    /**
     * Remove equipe
     *
     * @param \UtilisateurBundle\Entity\Equipe $equipe
     */
    public function removeEquipe(\UtilisateurBundle\Entity\Equipe $equipe)
    {
        $this->equipes->removeElement($equipe);
    }

    /**
     * Get equipes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEquipes()
    {
        return $this->equipes;
    }
}
