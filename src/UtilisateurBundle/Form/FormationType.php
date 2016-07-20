<?php
/* Copyright 2016 C. Thubert */

namespace UtilisateurBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use RHBundle\Repository\ModeacquisitionRepository;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('intitule', TextType::class,
                array(
                    'required' => false
                )) ;
        $builder->add('modeacquisition', EntityType::class,
                array(
                    'class' => 'RHBundle\Entity\Modeacquisition',
                    'label' => 'Mode acquisition',
                    'query_builder' => function (ModeacquisitionRepository $rep) {
                        return $rep->trierParNom() ;
                    }
                )) ;
        $builder->add('effectue_le', DateType::class,
                array(
                    'label' => 'Effectué le',
                    'format' => 'ddMMMyyyy'
                )) ;
        $builder->add('duree', IntegerType::class,
                array(
                    'label' => 'Durée (en heure)'
                )) ;
        $builder->add('description', TextareaType::class,
                array(
                    'required' => false
                )) ;
        $builder->add('fichier', FileType::class,
                array(
                    'required' => false
                )) ;
        $builder->add('reset', ResetType::class) ;
        $builder->add('save', SubmitType::class) ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( array( 'data_class' => 'RHBundle\Entity\Formation') ) ;
    }
}