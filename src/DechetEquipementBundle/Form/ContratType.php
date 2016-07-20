<?php
/* Copyright 2016 C. Thubert */

namespace DechetEquipementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use DechetEquipementBundle\Repository\PrestataireRepository;

class ContratType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('debut', DateType::class,
                array(
                    'label' => 'Début',
                    'format' => 'ddMMMyyyy'
                )) ;
        $builder->add('fin', DateType::class,
                array(
                    'label' => 'Fin',
                    'format' => 'ddMMMyyyy'
                )) ;
        $builder->add('numero', TextType::class,
                array(
                    'label' => 'Numéro',
                    'required' => false
                )) ;
        $builder->add('cout', TextType::class,
                array(
                    'label' => 'Coût',
                    'required' => false
                )) ;
        $builder->add('prestataire', EntityType::class,
                array(
                    'class' => 'DechetEquipementBundle\Entity\Prestataire',
                    'label' => 'Prestataire',
                    'query_builder' => function (PrestataireRepository $rep) {
                        return $rep->trierParNom() ;
                    }
                )) ;
        $builder->add('commentaire', TextareaType::class,
                array(
                    'required' => false
                )) ;
        $builder->add('fichier', FileType::class) ;
        $builder->add('reset', ResetType::class) ;
        $builder->add('save', SubmitType::class) ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( array( 'data_class' => 'DechetEquipementBundle\Entity\Contrat') ) ;
    }
}
