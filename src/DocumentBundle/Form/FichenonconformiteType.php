<?php
/* Copyright 2016 C. Thubert */

namespace DocumentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FichenonconformiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('processus', EntityType::class,
                array(
                    'class' => 'DocumentBundle\Entity\Processus',
                    'label' => 'Processus'
                )) ;
        $builder->add('description', TextareaType::class) ;
        $builder->add('ouvert_le', DateType::class,
                array(
                    'label' => 'Ouvert le',
                    'format' => 'ddMMMyyyy'
                )) ;
        $builder->add('ferme_le', DateType::class,
                array(
                    'label' => 'Fermé le',
                    'format' => 'ddMMMyyyy'
                )) ;
        $builder->add('utilisateurs', EntityType::class,
                array(
                    'class' => 'UtilisateurBundle\Entity\Utilisateur',
                    'label' => 'Utilisateur(s)',
                    'multiple' => true
                )) ;
        $builder->add('fichier', FileType::class) ;
        $builder->add('reset', ResetType::class) ;
        $builder->add('save', SubmitType::class) ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( array( 'data_class' => 'DocumentBundle\Entity\Fichenonconformite') ) ;
    }
}