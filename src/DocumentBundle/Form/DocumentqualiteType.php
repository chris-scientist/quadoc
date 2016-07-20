<?php
/* Copyright 2016 C. Thubert */

namespace DocumentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class DocumentqualiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre', TextType::class) ;
        $builder->add('reference', TextType::class,
                array(
                    'label' => 'Référence'
                )) ;
        $builder->add('domaine', EntityType::class,
                array(
                    'class' => 'DocumentBundle\Entity\Domaine',
                    'label' => 'Domaine'
                )) ;
        $builder->add('forme', EntityType::class,
                array(
                    'class' => 'DocumentBundle\Entity\Forme',
                    'label' => 'Forme'
                )) ;
        $builder->add('equipes', EntityType::class,
                array(
                    'class' => 'UtilisateurBundle\Entity\Equipe',
                    'label' => 'Equipe(s)',
                    'multiple' => true,
                    'expanded' => true
                )) ;
        $builder->add('commentaire', TextareaType::class,
                array(
                    'required' => false
                )) ;
        $builder->add('interne', CheckboxType::class,
                array(
                    'required' => false
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
        $resolver->setDefaults( array( 'data_class' => 'DocumentBundle\Entity\Documentqualite') ) ;
    }
}
