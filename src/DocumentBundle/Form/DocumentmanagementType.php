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
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class DocumentmanagementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre', TextType::class) ;
        $builder->add('typedocument', EntityType::class,
                array(
                    'class' => 'DocumentBundle\Entity\Typedocument',
                    'label' => 'Type document'
                )) ;
        $builder->add('mission', EntityType::class,
                array(
                    'class' => 'DocumentBundle\Entity\Mission',
                    'label' => 'Mission'
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
        $resolver->setDefaults( array( 'data_class' => 'DocumentBundle\Entity\Documentmanagement') ) ;
    }
}