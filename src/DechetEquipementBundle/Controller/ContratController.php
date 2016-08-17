<?php
/* Copyright 2016 C. Thubert */

namespace DechetEquipementBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use DechetEquipementBundle\Form\ContratType;
use DechetEquipementBundle\Repository\EquipementRepository;
use DechetEquipementBundle\Repository\DechetRepository;
use DechetEquipementBundle\Entity\Contrat;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ContratController extends Controller
{
    /**
     * @Route("/equipement/contrat/add", name="cnt_equipement_add")
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_ANIM_QUALITE') or has_role('ROLE_ANIM_PREVENTION') or has_role('ROLE_ANIM_CHARTESANITAIRE') or has_role('ROLE_ANIM_SME') or has_role('ROLE_RESPONSABLE') or has_role('ROLE_UTILISATEUR')")
     */
    public function addCntEquipementAction(Request $request)
    {
        $contrat = new Contrat() ;
        $contrat->setContratdechet(false) ;
        $form = $this->createForm(ContratType::class, $contrat) ;
        $form->add('equipements', EntityType::class,
                array(
                    'class' => 'DechetEquipementBundle\Entity\Equipement',
                    'label' => 'Equipement(s)',
                    'multiple' => true,
                    'query_builder' => function (EquipementRepository $rep) {
                        return $rep->contrainteEquipementActif( $rep->trierParNom() ) ;
                    }
                )) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($contrat) ;
            foreach( $contrat->getEquipements() as $equipement )
            {
                $equipement->addContratequipement($contrat) ;
            }
            $em->flush() ;
            
            return $this->redirectToRoute("eqt_index") ;
        }
        
        return $this->render('contrat/add_cnt_equipement.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route("/dechet/contrat/add", name="cnt_dechet_add")
     */
    public function addCntDechetAction(Request $request)
    {
        $contrat = new Contrat() ;
        $contrat->setContratdechet(true) ;
        $form = $this->createForm(ContratType::class, $contrat) ;
        $form->add('dechets', EntityType::class,
                array(
                    'class' => 'DechetEquipementBundle\Entity\Dechet',
                    'label' => 'Déchet(s)',
                    'multiple' => true,
                    'query_builder' => function (DechetRepository $rep) {
                        return $rep->trierParNom() ;
                    }
                )) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($contrat) ;
            foreach( $contrat->getDechets() as $dechet )
            {
                $dechet->addContratdechet($contrat) ;
            }
            $em->flush() ;
            
            return $this->redirectToRoute("dec_index") ;
        }
        
        return $this->render('contrat/add_cnt_dechet.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/contrat/download/{id}",
     *  name="cnt_dl",
     *  requirements={ "id": "\d+" }
     * )
     * @Security("has_role('ROLE_VISITEUR')")
     */
    public function downloadContratAction(Contrat $cnt, Request $request)
    {
        $path = $cnt->getUploadDir() ;
        $filename = $cnt->getId() . '.pdf' ;
        
        $response = new Response() ;
        $response->setContent(file_get_contents($path . $filename)) ;
        $response->headers->set('Content-Type', 'application/force-download') ;
        $response->headers->set('Content-disposition', 'filename=' . $filename) ;
        
        return $response ;
    }
}