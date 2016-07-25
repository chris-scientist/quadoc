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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roles = array(
            "ROLE_ADMIN" => "ROLE_ADMIN",
            "ROLE_ANIMQUALITE" => "ROLE_ANIMQUALITE"
        ) ;
        
        $builder->add('nom', TextType::class) ;
        $builder->add('prenom', TextType::class,
                array(
                    'label' => 'Prénom'
                )) ;
        $builder->add('username', TextType::class,
                array(
                    'label' => 'Login'
                )) ;
        $builder->add('initiale', TextType::class,
                array(
                    'required' => false
                )) ;
        $builder->add('email', TextType::class,
                array(
                    'label' => 'Adresse mail'
                )) ;
        $builder->add('statut', EntityType::class,
                array(
                    'class' => 'UtilisateurBundle\Entity\Statut'
                )) ;
        $builder->add('roles', ChoiceType::class,
                array(
                    'label' => 'Rôle(s)',
                    'choices' => $roles,
                    'multiple' => true
                )) ;
        $builder->add('reset', ResetType::class) ;
        $builder->add('save', SubmitType::class) ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults( array( 'data_class' => 'UtilisateurBundle\Entity\Utilisateur') ) ;
    }
}