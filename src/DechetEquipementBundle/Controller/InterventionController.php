<?php
/* Copyright 2016 C. Thubert */

namespace DechetEquipementBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use DechetEquipementBundle\Entity\Intervention;
use DechetEquipementBundle\Form\InterventionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use DechetEquipementBundle\Repository\EquipementRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class InterventionController extends Controller
{
    /**
     * @Route("/intervention/add", name="int_add")
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ANIM_QUALITE') or has_role('ROLE_ANIM_PREVENTION') or has_role('ROLE_ANIM_CHARTESANITAIRE') or has_role('ROLE_ANIM_SME') or has_role('ROLE_RESPONSABLE') or has_role('ROLE_UTILISATEUR')")
     */
    public function addAction(Request $request)
    {
        $intervention = new Intervention() ;
        $form = $this->createForm(InterventionType::class, $intervention) ;
        $form->add('equipement', EntityType::class,
                array(
                    'class' => 'DechetEquipementBundle\Entity\Equipement',
                    'label' => 'Equipement',
                    'query_builder' => function (EquipementRepository $rep) {
                        return $rep->contrainteEquipementActif( $rep->trierParNom() ) ;
                    }
                )) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            if( ! is_null($intervention->getFichier()) )
            {
                $intervention->setBFichier(true) ;
            }
            
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($intervention) ;
            $em->flush() ;
            
            return $this->redirectToRoute("eqt_index") ;
        }
        
        return $this->render('intervention/add.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
}