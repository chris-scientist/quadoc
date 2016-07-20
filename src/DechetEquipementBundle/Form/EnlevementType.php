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
use UtilisateurBundle\Repository\UtilisateurRepository;

class EnlevementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('enleve_le', DateType::class,
                array(
                    'label' => 'Enlevé le',
                    'format' => 'ddMMMyyyy'
                )) ;
        $builder->add('intervenant', EntityType::class,
                array(
                    'class' => 'UtilisateurBundle\Entity\Utilisateur',
                    'label' => 'Intervenant',
                    'query_builder' => function (UtilisateurRepository $rep) {
                        return $rep->trierParNom() ;
                    }
                )) ;
        $builder->add('quantite', TextType::class,
                array(
                    'label' => 'Quantité'
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
        $resolver->setDefaults( array( 'data_class' => 'DechetEquipementBundle\Entity\Enlevement') ) ;
    }
}