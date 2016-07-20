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
use UtilisateurBundle\Repository\UtilisateurRepository;

class InterventionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('operation', TextType::class,
                array(
                    'label' => 'Opération'
                )) ;
        $builder->add('afaire_le', DateType::class,
                array(
                    'label' => 'A faire le',
                    'format' => 'ddMMMyyyy'
                )) ;
        $builder->add('effectue_le', DateType::class,
                array(
                    'label' => 'Effectue le',
                    'format' => 'ddMMMyyyy'
                )) ;
        $builder->add('operateur', EntityType::class,
                array(
                    'class' => 'UtilisateurBundle\Entity\Utilisateur',
                    'label' => 'Opérateur',
                    'query_builder' => function (UtilisateurRepository $rep) {
                        return $rep->trierParNom() ;
                    }
                )) ;
        $builder->add('commentaire', TextareaType::class,
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
        $resolver->setDefaults( array( 'data_class' => 'DechetEquipementBundle\Entity\Intervention') ) ;
    }
}
