<?php
/* Copyright 2016 C. Thubert */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
//use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

//
// Partie du formulaire " filtre " qui est générique.
//
abstract class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('combination', ChoiceType::class,
                array(
                    'choices' => array(
                        'Et' => true,
                        'Ou' => false
                    )/*,
                    'label' => 'Combinaison des filtres'*/
                ))
            ->add('toexport', HiddenType::class,
                    array(
                        'data' => "off"
                    )
                )
            ->add('export', ButtonType::class)
//            ->add('help', ButtonType::class)
//            ->add('reset', ResetType::class)
            ->add('save', SubmitType::class) ;
    }
}
