<?php

namespace DechetEquipementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dechet
 *
 * @ORM\Table(name="tr_dechet_dec")
 * @ORM\Entity(repositoryClass="DechetEquipementBundle\Repository\DechetRepository")
 */
class Dechet
{
    /**
     * @var int
     *
     * @ORM\Column(name="dec_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="dec_nom", type="string", length=32, unique=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="dec_description", type="string", length=255)
     */
    private $description;
    
    //
    // ManyToMany qui joue le rôle d'un OneToMany !
    // 
    // Cf. http://doctrine-orm.readthedocs.io/projects/doctrine-orm/en/latest/reference/association-mapping.html#one-to-many-unidirectional-with-join-table
    //
    /**
     * @var DechetEquipementBundle\Entity\Enlevement
     * 
     * @ORM\ManyToMany(targetEntity="DechetEquipementBundle\Entity\Enlevement")
     * @ORM\JoinTable(
     *  name="tj_dechetenlevement_den",
     *  joinColumns={@ORM\JoinColumn(name="den_dec_id", referencedColumnName="dec_id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="den_enl_id", referencedColumnName="enl_id", unique=true)}
     * )
     */
    private $historique;
    
    /**
     * @var DechetEquipementBundle\Entity\Contrat
     * 
     * @ORM\ManyToMany(targetEntity="DechetEquipementBundle\Entity\Contrat")
     * @ORM\JoinTable(
     *  name="tj_dechetcontrat_dcn",
     *  joinColumns={@ORM\JoinColumn(name="dcn_dec_id", referencedColumnName="dec_id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="dcn_cnt_id", referencedColumnName="cnt_id")}
     * )
     */
    private $contratdechet;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->historique = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Dechet
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
     * Set description
     *
     * @param string $description
     *
     * @return Dechet
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
     * Add historique
     *
     * @param \DechetEquipementBundle\Entity\Enlevement $historique
     *
     * @return Dechet
     */
    public function addHistorique(\DechetEquipementBundle\Entity\Enlevement $historique)
    {
        $this->historique[] = $historique;

        return $this;
    }

    /**
     * Remove historique
     *
     * @param \DechetEquipementBundle\Entity\Enlevement $historique
     */
    public function removeHistorique(\DechetEquipementBundle\Entity\Enlevement $historique)
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
     * Add contratdechet
     *
     * @param \DechetEquipementBundle\Entity\Contrat $contratdechet
     *
     * @return Dechet
     */
    public function addContratdechet(\DechetEquipementBundle\Entity\Contrat $contratdechet)
    {
        $this->contratdechet[] = $contratdechet;

        return $this;
    }

    /**
     * Remove contratdechet
     *
     * @param \DechetEquipementBundle\Entity\Contrat $contratdechet
     */
    public function removeContratdechet(\DechetEquipementBundle\Entity\Contrat $contratdechet)
    {
        $this->contratdechet->removeElement($contratdechet);
    }

    /**
     * Get contratdechet
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContratdechet()
    {
        return $this->contratdechet;
    }
}
