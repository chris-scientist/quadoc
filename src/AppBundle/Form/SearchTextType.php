<?php
/* Copyright 2016 C. Thubert */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

//
// Recherche donnée textuelle (autrement dit "saisie libre").
//
class SearchTextType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('op_text', ChoiceType::class,
                array(
                    'choices' => array(
                        'égale à' => 'equal_to',
                        'contient' => 'like'
                    )
                )
            )
            ->add('searched_value', TextType::class,
                    array(
                        'required' => false
                    )) ;
    }
}
