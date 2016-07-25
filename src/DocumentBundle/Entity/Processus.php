<?php
/* Copyright 2016 C. Thubert */

namespace DocumentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Processus
 *
 * @ORM\Table(name="tr_processus_pro")
 * @ORM\Entity(repositoryClass="DocumentBundle\Repository\ProcessusRepository")
 */
class Processus
{
    /**
     * @var int
     *
     * @ORM\Column(name="pro_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="pro_nom", type="string", length=32, unique=true)
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
     * @return Processus
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

