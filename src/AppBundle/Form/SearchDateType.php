<?php
/* Copyright 2016 C. Thubert */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;

//
// Recherche donnée temporelle (autrement dit "dates").
//
class SearchDateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('begin_date', DateType::class,
                array(
                    'format' => 'dd-MM-yyyy',
                    'widget' => 'single_text',
                    'attr' => array(
                        'class' => 'form-control input-inline datepicker',
                        'data-provide' => 'datepicker',
                        'data-date-format' => 'dd-mm-yyyy'
                    )
                ))
            ->add('end_date', DateType::class,
                array(
                    'format' => 'dd-MM-yyyy',
                    'widget' => 'single_text',
                    'attr' => array(
                        'class' => 'form-control input-inline datepicker',
                        'data-provide' => 'datepicker',
                        'data-date-format' => 'dd-mm-yyyy'
                    )
                )) ;
    }
}
