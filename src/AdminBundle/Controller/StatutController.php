<?php
/* Copyright 2016 C. Thubert */

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use UtilisateurBundle\Entity\Statut;
use AdminBundle\Form\StatutType;

class StatutController extends Controller
{
    /**
     * @Route("/statut/index", name="admin_statut_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        $repSta = $em->getRepository("UtilisateurBundle:Statut") ;
        
        $statuts = $repSta->findAll() ;
        
        return $this->render('admin/statut/index.html.twig', array(
            'statuts' => $statuts
        ));
    }
    /**
     * @Route("/statut/add", name="admin_statut_add")
     */
    public function addAction(Request $request)
    {
        $statut = new Statut() ;
        $form = $this->createForm(StatutType::class, $statut) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($statut) ;
            $em->flush() ;
            
            return $this->redirectToRoute("admin_statut_index") ;
        }
        
        return $this->render('admin/statut/add.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/statut/remove/{id}",
     *  name="admin_statut_remove",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function removeAction(Statut $statut, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $em->remove($statut) ;
        $em->flush() ;
        
        return $this->redirectToRoute("admin_statut_index") ;
    }
}
