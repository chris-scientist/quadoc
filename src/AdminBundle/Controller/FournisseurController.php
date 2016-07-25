<?php
/* Copyright 2016 C. Thubert */

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use DechetEquipementBundle\Entity\Fournisseur;
use AdminBundle\Form\FournisseurType;

class FournisseurController extends Controller
{
    /**
     * @Route("/fournisseur/index", name="admin_fournisseur_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        $repFou = $em->getRepository("DechetEquipementBundle:Fournisseur") ;
        
        $fournisseurs = $repFou->findAll() ;
        
        return $this->render('admin/fournisseur/index.html.twig', array(
            'fournisseurs' => $fournisseurs
        ));
    }
    /**
     * @Route("/fournisseur/add", name="admin_fournisseur_add")
     */
    public function addAction(Request $request)
    {
        $fournisseur = new Fournisseur() ;
        $form = $this->createForm(FournisseurType::class, $fournisseur) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($fournisseur) ;
            $em->flush() ;
            
            return $this->redirectToRoute("admin_fournisseur_index") ;
        }
        
        return $this->render('admin/fournisseur/add.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/fournisseur/remove/{id}",
     *  name="admin_fournisseur_remove",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function removeAction(Fournisseur $fournisseur, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $em->remove($fournisseur) ;
        $em->flush() ;
        
        return $this->redirectToRoute("admin_fournisseur_index") ;
    }
}
