<?php
/* Copyright 2016 C. Thubert */

namespace DechetEquipementBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DechetEquipementBundle\Entity\Enlevement;
use DechetEquipementBundle\Form\EnlevementType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use DechetEquipementBundle\Repository\DechetRepository;

class EnlevementController extends Controller
{
    /**
     * @Route("/enlevement/add", name="enl_add")
     */
    public function addAction(Request $request)
    {
        $enlevement = new Enlevement() ;
        $form = $this->createForm(EnlevementType::class, $enlevement) ;
        $form->add('dechet', EntityType::class,
                array(
                    'class' => 'DechetEquipementBundle\Entity\Dechet',
                    'label' => 'Déchet',
                    'query_builder' => function (DechetRepository $rep) {
                        return $rep->trierParNom() ;
                    }
                )) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($enlevement) ;
            $em->flush() ;
            
            return $this->redirectToRoute("dec_index") ;
        }
        
        return $this->render('enlevement/add.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
}