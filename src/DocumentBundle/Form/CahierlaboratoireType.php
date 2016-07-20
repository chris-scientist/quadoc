<?php
/* Copyright 2016 C. Thubert */

namespace DocumentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use UtilisateurBundle\Repository\UtilisateurRepository;
use UtilisateurBundle\Repository\EquipeRepository;
use DocumentBundle\Repository\SupportRepository;

class CahierlaboratoireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('intitule', TextType::class,
                array(
                    'label' => 'Intitulé'
                )) ;
        $builder->add('ninterne', TextType::class,
                array(
                    'label' => 'Numéro interne'
                )) ;
        $builder->add('nministere', TextType::class,
                array(
                    'label' => 'Numéro ministère'
                )) ;
        $builder->add('ouvert_le', DateType::class,
                array(
                    'label' => 'Ouvert le',
                    'format' => 'ddMMMyyyy'
                )) ;
        $builder->add('equipe', EntityType::class,
                array(
                    'class' => 'UtilisateurBundle\Entity\Equipe',
                    'label' => 'Equipe',
                    'query_builder' => function (EquipeRepository $rep) {
                        return $rep->trierParNom() ;
                    }
                )) ;
        $builder->add('utilisateurs', EntityType::class,
                array(
                    'class' => 'UtilisateurBundle\Entity\Utilisateur',
                    'label' => 'Utilisateur(s)',
                    'multiple' => true,
                    'query_builder' => function (UtilisateurRepository $rep) {
                        return $rep->trierParNom() ;
                    }
                )) ;
        $builder->add('support', EntityType::class,
                array(
                    'class' => 'DocumentBundle\Entity\Support',
                    'label' => 'Support',
                    'query_builder' => function (SupportRepository $rep) {
                        return $rep->trierParNom() ;
                    }
                )) ;
        $builder->add('localisation', TextType::class,
                array(
                    'label' => 'Localisation'
                )) ;
        $builder->add('commentaire', TextareaType::class,
                array(
                    'required' => false
                )) ;
        $builder->add('reset', ResetType::class) ;
        $builder->add('save', SubmitType::class) ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( array( 'data_class' => 'DocumentBundle\Entity\Cahierlaboratoire') ) ;
    }
}