<?php
/* Copyright 2016 C. Thubert */

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use DechetEquipementBundle\Entity\Marque;
use AdminBundle\Form\MarqueType;

class MarqueController extends Controller
{
    /**
     * @Route("/marque/index", name="admin_marque_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        $repMar = $em->getRepository("DechetEquipementBundle:Marque") ;
        
        $marques = $repMar->findAll() ;
        
        return $this->render('admin/marque/index.html.twig', array(
            'marques' => $marques
        ));
    }
    /**
     * @Route("/marque/add", name="admin_marque_add")
     */
    public function addAction(Request $request)
    {
        $marque = new Marque() ;
        $form = $this->createForm(MarqueType::class, $marque) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($marque) ;
            $em->flush() ;
            
            return $this->redirectToRoute("admin_marque_index") ;
        }
        
        return $this->render('admin/marque/add.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/marque/remove/{id}",
     *  name="admin_marque_remove",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function removeAction(Marque $marque, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $em->remove($marque) ;
        $em->flush() ;
        
        return $this->redirectToRoute("admin_marque_index") ;
    }
}
