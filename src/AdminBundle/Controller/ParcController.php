<?php
/* Copyright 2016 C. Thubert */

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use DechetEquipementBundle\Entity\Parc;
use AdminBundle\Form\ParcType;

class ParcController extends Controller
{
    /**
     * @Route("/parc/index", name="admin_parc_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        $repPar = $em->getRepository("DechetEquipementBundle:Parc") ;
        
        $parcs = $repPar->findAll() ;
        
        return $this->render('admin/parc/index.html.twig', array(
            'parcs' => $parcs
        ));
    }
    /**
     * @Route("/parc/add", name="admin_parc_add")
     */
    public function addAction(Request $request)
    {
        $parc = new Parc() ;
        $form = $this->createForm(ParcType::class, $parc) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($parc) ;
            $em->flush() ;
            
            return $this->redirectToRoute("admin_parc_index") ;
        }
        
        return $this->render('admin/parc/add.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/parc/remove/{id}",
     *  name="admin_parc_remove",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function removeAction(Parc $parc, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $em->remove($parc) ;
        $em->flush() ;
        
        return $this->redirectToRoute("admin_parc_index") ;
    }
}
