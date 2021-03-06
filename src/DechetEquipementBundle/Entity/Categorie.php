<?php
/* Copyright 2016 C. Thubert */

namespace DechetEquipementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Categorie
 *
 * @ORM\Table(name="tr_categorie_cat")
 * @ORM\Entity(repositoryClass="DechetEquipementBundle\Repository\CategorieRepository")
 */
class Categorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="cat_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="cat_nom", type="string", length=32, unique=true)
     * @Assert\Length(max=32)
     * @Assert\NotBlank()
     */
    private $nom;
    
    /**
     * @var DechetEquipementBundle\Entity\Parc
     * 
     * @ORM\ManyToOne(targetEntity="DechetEquipementBundle\Entity\Parc")
     * @ORM\JoinColumn(name="cat_par_id", referencedColumnName="par_id")
     * @Assert\NotNull()
     */
    private $parc;


    public function __toString()
    {
        return $this->getNom() ;
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
     * @return Categorie
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
     * Set parc
     *
     * @param \DechetEquipementBundle\Entity\Parc $parc
     *
     * @return Categorie
     */
    public function setParc(\DechetEquipementBundle\Entity\Parc $parc = null)
    {
        $this->parc = $parc;

        return $this;
    }

    /**
     * Get parc
     *
     * @return \DechetEquipementBundle\Entity\Parc
     */
    public function getParc()
    {
        return $this->parc;
    }
}
