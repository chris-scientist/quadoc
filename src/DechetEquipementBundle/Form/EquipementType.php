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
use UtilisateurBundle\Repository\EquipeRepository;

class EquipementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', TextType::class) ;
        $builder->add('modele', TextType::class,
                array(
                    'label' => 'Modèle'
                )) ;
        $builder->add('n_serie', TextType::class,
                array(
                    'label' => 'Numéro de série'
                )) ;
        $builder->add('miseenservice_le', DateType::class,
                array(
                    'label' => 'Mise en service le',
                    'format' => 'ddMMMyyyy'
                )) ;
        $builder->add('fingarantie_le', DateType::class,
                array(
                    'label' => 'Fin garantie',
                    'format' => 'ddMMMyyyy'
                )) ;
        $builder->add('emplacement', TextType::class) ;
        $builder->add('achete_le', DateType::class,
                array(
                    'label' => 'Acheté le',
                    'format' => 'ddMMMyyyy'
                )) ;
        $builder->add('n_immobilisation', TextType::class,
                array(
                    'label' => 'Numéro d\'immobilisation',
                    'required' => false
                )) ;
        $builder->add('responsable', EntityType::class,
                array(
                    'class' => 'UtilisateurBundle\Entity\Utilisateur',
                    'label' => 'Responsable',
                    'query_builder' => function (UtilisateurRepository $rep) {
                        return $rep->trierParNom() ;
                    }
                )) ;
        $builder->add('equipe', EntityType::class,
                array(
                    'class' => 'UtilisateurBundle\Entity\Equipe',
                    'label' => 'Equipe',
                    'query_builder' => function (EquipeRepository $rep) {
                        return $rep->trierParNom() ;
                    }
                )) ;
        $builder->add('fournisseur', EntityType::class,
                array(
                    'class' => 'DechetEquipementBundle\Entity\Fournisseur',
                    'label' => 'Fournisseur',
                    'query_builder' => function (FournisseurRepository $rep) {
                        return $rep->trierParNom() ;
                    }
                )) ;
        $builder->add('marque', EntityType::class,
                array(
                    'class' => 'DechetEquipementBundle\Entity\Marque',
                    'label' => 'Marque',
                    'query_builder' => function (MarqueRepository $rep) {
                        return $rep->trierParNom() ;
                    }
                )) ;
        $builder->add('categorie', EntityType::class,
                array(
                    'class' => 'DechetEquipementBundle\Entity\Categorie',
                    'label' => 'Catégorie',
                    'query_builder' => function (CategorieRepository $rep) {
                        return $rep->trierParNom() ;
                    }
                )) ;
        $builder->add('caracteristiques', TextareaType::class,
                array(
                    'label' => 'Caractéristiques',
                    'required' => false
                )) ;
        $builder->add('fichier', FileType::class,
                array(
                    'required' => false
                )) ;
        $builder->add('photo', FileType::class,
                array(
                    'required' => false
                )) ;
        $builder->add('reset', ResetType::class) ;
        $builder->add('save', SubmitType::class) ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( array( 'data_class' => 'DechetEquipementBundle\Entity\Equipement') ) ;
    }
}
