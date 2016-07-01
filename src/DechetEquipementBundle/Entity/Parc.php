<?php

namespace DechetEquipementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parc
 *
 * @ORM\Table(name="tr_parc_par")
 * @ORM\Entity(repositoryClass="DechetEquipementBundle\Repository\ParcRepository")
 */
class Parc
{
    /**
     * @var int
     *
     * @ORM\Column(name="par_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="par_nom", type="string", length=32, unique=true)
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
     * @return Parc
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

