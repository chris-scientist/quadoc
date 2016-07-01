<?php

namespace DocumentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fichenonconformite
 *
 * @ORM\Table(name="t_fichenonconformite_fnc")
 * @ORM\Entity(repositoryClass="DocumentBundle\Repository\FichenonconformiteRepository")
 */
class Fichenonconformite
{
    /**
     * @var int
     *
     * @ORM\Column(name="fnc_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fnc_ouvert_le", type="datetime")
     */
    private $ouvertLe;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fnc_ferme_le", type="datetime", nullable=true)
     */
    private $fermeLe;

    /**
     * @var string
     *
     * @ORM\Column(name="fnc_description", type="string", length=255)
     */
    private $description;
    
    /**
     * @var DocumentBundle\Entity\Processus
     * 
     * @ORM\ManyToOne(targetEntity="DocumentBundle\Entity\Processus")
     * @ORM\JoinColumn(name="fnc_pro_id", nullable=false, referencedColumnName="pro_id")
     */
    private $processus;
    
    /**
     * @var UtilisateurBundle\Entity\Utilisateur
     * 
     * @ORM\ManyToMany(targetEntity="UtilisateurBundle\Entity\Utilisateur")
     * @ORM\JoinTable(
     *  name="tj_fichenonconformiteutilisateur_fut",
     *  joinColumns={@ORM\JoinColumn(name="fut_fnc_id", referencedColumnName="fnc_id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="fut_uti_id", referencedColumnName="uti_id")}
     * )
     */
    private $utilisateurs;


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
     * Set ouvertLe
     *
     * @param \DateTime $ouvertLe
     *
     * @return Fichenonconformite
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
     * @return Fichenonconformite
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
     * Set description
     *
     * @param string $description
     *
     * @return Fichenonconformite
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set processus
     *
     * @param \DocumentBundle\Entity\Processus $processus
     *
     * @return Fichenonconformite
     */
    public function setProcessus(\DocumentBundle\Entity\Processus $processus)
    {
        $this->processus = $processus;

        return $this;
    }

    /**
     * Get processus
     *
     * @return \DocumentBundle\Entity\Processus
     */
    public function getProcessus()
    {
        return $this->processus;
    }

    /**
     * Add utilisateur
     *
     * @param \UtilisateurBundle\Entity\Utilisateur $utilisateur
     *
     * @return Fichenonconformite
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
}
