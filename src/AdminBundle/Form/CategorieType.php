<?php
/* Copyright 2016 C. Thubert */

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('parc', EntityType::class,
                array(
                    'class' => 'DechetEquipementBundle\Entity\Parc',
                    'label' => 'Parc'
                )) ;
        $builder->add('nom', TextType::class) ;
        $builder->add('reset', ResetType::class) ;
        $builder->add('save', SubmitType::class) ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( array( 'data_class' => 'DechetEquipementBundle\Entity\Categorie') ) ;
    }
}