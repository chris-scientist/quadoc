<?php
/* Copyright 2016 C. Thubert */

namespace DechetEquipementBundle\Repository;

/**
 * CategorieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategorieRepository extends \Doctrine\ORM\EntityRepository
{
    /*
     * Trier les catégories selon leur nom, utilisé pour les formulaires.
     */
    public function trierParNom()
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.nom', 'ASC') ;
    }
}
