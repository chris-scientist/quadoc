<?php

namespace DocumentBundle\Entity;

abstract class Document
{
    //
    // NOTE concernant les attributs (de cette classe)
    // Ils sont stockés en base de données, mais annotés dans :
    // Documentmanagement et Documentqualite !
    // 
    
    protected $titre;

    protected $archiveLe;
    
    protected $versions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->versions = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Document
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set archiveLe
     *
     * @param \DateTime $archiveLe
     *
     * @return Document
     */
    public function setArchiveLe($archiveLe)
    {
        $this->archiveLe = $archiveLe;

        return $this;
    }

    /**
     * Get archiveLe
     *
     * @return \DateTime
     */
    public function getArchiveLe()
    {
        return $this->archiveLe;
    }

    /**
     * Add version
     *
     * @param \DocumentBundle\Entity\Version $version
     *
     * @return Document
     */
    public function addVersion(\DocumentBundle\Entity\Version $version)
    {
        $this->versions[] = $version;

        return $this;
    }

    /**
     * Remove version
     *
     * @param \DocumentBundle\Entity\Version $version
     */
    public function removeVersion(\DocumentBundle\Entity\Version $version)
    {
        $this->versions->removeElement($version);
    }

    /**
     * Get versions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVersions()
    {
        return $this->versions;
    }
}

