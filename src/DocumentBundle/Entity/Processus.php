<?php

namespace DocumentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     */
    private $nom;


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

