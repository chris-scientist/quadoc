<?php
/* Copyright 2016 C. Thubert */

namespace DechetEquipementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use UtilisateurBundle\Repository\UtilisateurRepository;
use DechetEquipementBundle\Repository\FournisseurRepository;
use DechetEquipementBundle\Repository\MarqueRepository;
use DechetEquipementBundle\Repository\CategorieRepository;
use DechetEquipementBundle\Repository\ParcRepository;
use UtilisateurBundle\Repository\EquipeRepository;
use AppBundle\Form\FilterType;
use AppBundle\Form\SearchTextType;
use AppBundle\Form\SearchDateType;

class FiltreEquipementType extends FilterType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options) ;
        
        $builder
            ->add('nom', SearchTextType::class)
            ->add('parc', EntityType::class,
                    array(
                        'class' => 'DechetEquipementBundle\Entity\Parc',
                        'label' => 'Parc(s)',
                        'multiple' => true,
                        'query_builder' => function (ParcRepository $rep) {
                            return $rep->trierParNom() ;
                        },
                        'required' => false
                    ))
            ->add('emplacement', SearchTextType::class)
            ->add('categorie', EntityType::class,
                    array(
                        'class' => 'DechetEquipementBundle\Entity\Categorie',
                        'label' => 'Catégorie(s)',
                        'multiple' => true,
                        'query_builder' => function (CategorieRepository $rep) {
                            return $rep->trierParNom() ;
                        },
                        'required' => false
                    ))
            ->add('achete_le', SearchDateType::class,
                    array(
                        'label' => 'Acheté le',
                        'required' => false
                    ))
            ->add('equipe', EntityType::class,
                    array(
                        'class' => 'UtilisateurBundle\Entity\Equipe',
                        'label' => 'Equipe(s)',
                        'multiple' => true,
                        'query_builder' => function (EquipeRepository $rep) {
                            return $rep->trierParNom() ;
                        },
                        'required' => false
                    ))
            ->add('fingarantie_le', SearchDateType::class,
                    array(
                        'label' => 'Fin garantie le',
                        'required' => false
                    ))
            ->add('marque', EntityType::class,
                    array(
                        'class' => 'DechetEquipementBundle\Entity\Marque',
                        'label' => 'Marque(s)',
                        'multiple' => true,
                        'query_builder' => function (MarqueRepository $rep) {
                            return $rep->trierParNom() ;
                        },
                        'required' => false
                    ))
            ->add('miseenservice_le', SearchDateType::class,
                    array(
                        'label' => 'Mise en service le',
                        'required' => false
                    ))
            ->add('fournisseur', EntityType::class,
                    array(
                        'class' => 'DechetEquipementBundle\Entity\Fournisseur',
                        'label' => 'Fournisseur(s)',
                        'multiple' => true,
                        'query_builder' => function (FournisseurRepository $rep) {
                            return $rep->trierParNom() ;
                        },
                        'required' => false
                    ))
            ->add('reforme_le', SearchDateType::class,
                    array(
                        'label' => 'Réformé le',
                        'required' => false
                    ));
    }
}
