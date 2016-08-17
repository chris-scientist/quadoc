<?php
/* Copyright 2016 C. Thubert */

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use DechetEquipementBundle\Entity\Dechet;
use AdminBundle\Form\DechetType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class DechetController extends Controller
{
    /**
     * @Route("/dechet/index", name="admin_dechet_index")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        $repDec = $em->getRepository("DechetEquipementBundle:Dechet") ;
        
        $dechets = $repDec->findAll() ;
        
        return $this->render('admin/dechet/index.html.twig', array(
            'dechets' => $dechets
        ));
    }
    /**
     * @Route("/dechet/add", name="admin_dechet_add")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addAction(Request $request)
    {
        $dechet = new Dechet() ;
        $form = $this->createForm(DechetType::class, $dechet) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($dechet) ;
            $em->flush() ;
            
            return $this->redirectToRoute("admin_dechet_index") ;
        }
        
        return $this->render('admin/dechet/add.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/dechet/remove/{id}",
     *  name="admin_dechet_remove",
     *  requirements={ "id": "\d+" }
     * )
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function removeAction(Dechet $dechet, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $em->remove($dechet) ;
        $em->flush() ;
        
        return $this->redirectToRoute("admin_dechet_index") ;
    }
}
