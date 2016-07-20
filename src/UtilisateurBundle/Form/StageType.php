<?php
/* Copyright 2016 C. Thubert */

namespace UtilisateurBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use UtilisateurBundle\Repository\UtilisateurRepository;
use UtilisateurBundle\Repository\EquipeRepository;

class StageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('encadrant', EntityType::class,
                array(
                    'class' => 'UtilisateurBundle\Entity\Utilisateur',
                    'label' => 'Encadrant',
                    'query_builder' => function (UtilisateurRepository $rep) {
                        return $rep->trierParNom() ;
                    }
                )) ;
        $builder->add('stagiaire', EntityType::class,
                array(
                    'class' => 'UtilisateurBundle\Entity\Utilisateur',
                    'label' => 'Stagiaire',
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
        $builder->add('sujet', TextType::class) ;
        $builder->add('etablissement', TextType::class,
                array(
                    'label' => 'Etablissement'
                )) ;
        $builder->add('diplome', TextType::class,
                array(
                    'label' => 'Diplôme'
                )) ;
        $builder->add('arrive_le', DateType::class,
                array(
                    'label' => 'Arrivé le',
                    'format' => 'ddMMMyyyy'
                )) ;
        $builder->add('duree', TextType::class,
                array(
                    'label' => 'Durée'
                )) ;
        $builder->add('reset', ResetType::class) ;
        $builder->add('save', SubmitType::class) ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( array( 'data_class' => 'RHBundle\Entity\Stage') ) ;
    }
}