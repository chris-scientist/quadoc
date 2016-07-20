<?php
/* Copyright 2016 C. Thubert */

namespace DocumentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use UtilisateurBundle\Repository\UtilisateurRepository;
use UtilisateurBundle\Repository\EquipeRepository;

class ProtocoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre', TextType::class) ;
        $builder->add('debut', DateType::class,
                array(
                    'label' => 'Début',
                    'format' => 'ddMMMyyyy'
                )) ;
        $builder->add('numero', TextType::class,
                array(
                    'label' => 'Numéro'
                )) ;
        $builder->add('equipe', EntityType::class,
                array(
                    'class' => 'UtilisateurBundle\Entity\Equipe',
                    'label' => 'Equipe',
                    'query_builder' => function (EquipeRepository $rep) {
                        return $rep->trierParNom() ;
                    }
                )) ;
        $builder->add('responsable', EntityType::class,
                array(
                    'class' => 'UtilisateurBundle\Entity\Utilisateur',
                    'label' => 'Utilisateur',
                    'query_builder' => function (UtilisateurRepository $rep) {
                        return $rep->trierParNom() ;
                    }
                )) ;
        $builder->add('versions', CollectionType::class,
                array(
                    'entry_type' => VersionType::class,
                    'allow_add' => true,
                    'label' => 'Version'
                )) ;
        $builder->add('reset', ResetType::class) ;
        $builder->add('save', SubmitType::class) ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( array( 'data_class' => 'DocumentBundle\Entity\Protocole') ) ;
    }
}