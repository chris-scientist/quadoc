<?php
/* Copyright 2016 C. Thubert */

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use RHBundle\Entity\Modeacquisition;
use AdminBundle\Form\ModeacquisitionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ModeacquisitionController extends Controller
{
    /**
     * @Route("/modeacquisition/index", name="admin_modeacquisition_index")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        $repMod = $em->getRepository("RHBundle:Modeacquisition") ;
        
        $modesacquisition = $repMod->findAll() ;
        
        return $this->render('admin/modeacquisition/index.html.twig', array(
            'modesacquisition' => $modesacquisition
        ));
    }
    /**
     * @Route("/modeacquisition/add", name="admin_modeacquisition_add")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addAction(Request $request)
    {
        $modeacquisition = new Modeacquisition() ;
        $form = $this->createForm(ModeacquisitionType::class, $modeacquisition) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($modeacquisition) ;
            $em->flush() ;
            
            return $this->redirectToRoute("admin_modeacquisition_index") ;
        }
        
        return $this->render('admin/modeacquisition/add.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/modeacquisition/remove/{id}",
     *  name="admin_modeacquisition_remove",
     *  requirements={ "id": "\d+" }
     * )
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function removeAction(Modeacquisition $modeacquisition, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $em->remove($modeacquisition) ;
        $em->flush() ;
        
        return $this->redirectToRoute("admin_modeacquisition_index") ;
    }
}
