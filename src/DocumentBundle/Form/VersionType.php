<?php
/* Copyright 2016 C. Thubert */

namespace DocumentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use UtilisateurBundle\Repository\UtilisateurRepository;

class VersionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('diffuse_le', DateType::class,
                array(
                    'label' => 'Diffusé le',
                    'format' => 'ddMMMyyyy'
                )) ;
        $builder->add('arret_le', DateType::class,
                array(
                    'label' => 'Arrêté le',
                    'format' => 'ddMMMyyyy'
                )) ;
        $builder->add('n_version', TextType::class,
                array(
                    'label' => 'Numéro de version'
                )) ;
        $builder->add('redacteurs', EntityType::class,
                array(
                    'class' => 'UtilisateurBundle\Entity\Utilisateur',
                    'label' => 'Rédacteur(s)',
                    'multiple' => true,
                    'expanded' => false,
                    'query_builder' => function (UtilisateurRepository $rep) {
                        return $rep->trierParNom() ;
                    }
                )) ;
        $builder->add('fichier', FileType::class) ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( array( 'data_class' => 'DocumentBundle\Entity\Version') ) ;
    }
}
