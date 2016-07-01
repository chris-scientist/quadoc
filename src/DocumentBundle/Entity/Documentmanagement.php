<?php

namespace DocumentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Documentmanagement
 *
 * @ORM\Table(name="t_documentmanagement_dma")
 * @ORM\Entity(repositoryClass="DocumentBundle\Repository\DocumentmanagementRepository")
 */
class Documentmanagement extends Document
{
    /**
     * @var int
     *
     * @ORM\Column(name="dma_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var DocumentBundle\Entity\Mission
     * 
     * @ORM\ManyToOne(targetEntity="DocumentBundle\Entity\Mission")
     * @ORM\JoinColumn(name="dma_mis_id", nullable=false, referencedColumnName="mis_id")
     */
    private $mission;
    
    /**
     * @var DocumentBundle\Entity\Typedocument
     * 
     * @ORM\ManyToOne(targetEntity="DocumentBundle\Entity\Typedocument")
     * @ORM\JoinColumn(name="dma_tdo_id", nullable=false, referencedColumnName="tdo_id")
     */
    private $typedocument;
    
    // -------------------------------------------------------------------------
    // Attributs (ci-dessous) herités de Document
    //
    
    /**
     * @var string
     * 
     * @ORM\Column(name="dma_titre", type="string", length=32)
     */
    protected $titre; 
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="dma_archive_le", type="datetime")
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
     *  name="tj_documentmanagementversion_dmv",
     *  joinColumns={@ORM\JoinColumn(name="dmv_dma_id", referencedColumnName="dma_id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="dmv_ver_id", referencedColumnName="ver_id", unique=true)}
     * )
     */
    protected $versions;
    
    //
    // Attributs (ci-dessus) herités de Document
    // -------------------------------------------------------------------------


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
     * Set mission
     *
     * @param \DocumentBundle\Entity\Mission $mission
     *
     * @return Documentmanagement
     */
    public function setMission(\DocumentBundle\Entity\Mission $mission)
    {
        $this->mission = $mission;

        return $this;
    }

    /**
     * Get mission
     *
     * @return \DocumentBundle\Entity\Mission
     */
    public function getMission()
    {
        return $this->mission;
    }

    /**
     * Set typedocument
     *
     * @param \DocumentBundle\Entity\Typedocument $typedocument
     *
     * @return Documentmanagement
     */
    public function setTypedocument(\DocumentBundle\Entity\Typedocument $typedocument)
    {
        $this->typedocument = $typedocument;

        return $this;
    }

    /**
     * Get typedocument
     *
     * @return \DocumentBundle\Entity\Typedocument
     */
    public function getTypedocument()
    {
        return $this->typedocument;
    }
}