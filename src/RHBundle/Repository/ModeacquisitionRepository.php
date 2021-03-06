<?php
/* Copyright 2016 C. Thubert */

namespace RHBundle\Repository;

/**
 * ModeacquisitionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ModeacquisitionRepository extends \Doctrine\ORM\EntityRepository
{
    /*
     * Trier les modes d'acquisition selon leur nom, utilisé pour les formulaires.
     */
    public function trierParNom()
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.nom', 'ASC') ;
    }
}
