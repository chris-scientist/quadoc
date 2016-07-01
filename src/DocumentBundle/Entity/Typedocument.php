<?php

namespace DocumentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Typedocument
 *
 * @ORM\Table(name="tr_typedocument_tdo")
 * @ORM\Entity(repositoryClass="DocumentBundle\Repository\TypedocumentRepository")
 */
class Typedocument
{
    /**
     * @var int
     *
     * @ORM\Column(name="tdo_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="tdo_nom", type="string", length=32, unique=true)
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
     * @return Typedocument
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

