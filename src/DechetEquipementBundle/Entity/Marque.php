<?php
/* Copyright 2016 C. Thubert */

namespace DechetEquipementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Marque
 *
 * @ORM\Table(name="tr_marque_mar")
 * @ORM\Entity(repositoryClass="DechetEquipementBundle\Repository\MarqueRepository")
 */
class Marque
{
    /**
     * @var int
     *
     * @ORM\Column(name="mar_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="mar_nom", type="string", length=32, unique=true)
     * @Assert\Length(max=32)
     * @Assert\NotBlank()
     */
    private $nom;


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
     * @return Marque
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
}

