<?php
/* Copyright 2016 C. Thubert */

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use DocumentBundle\Entity\Processus;
use AdminBundle\Form\ProcessusType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ProcessusController extends Controller
{
    /**
     * @Route("/processus/index", name="admin_processus_index")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        $repPro = $em->getRepository("DocumentBundle:Processus") ;
        
        $processus = $repPro->findAll() ;
        
        return $this->render('admin/processus/index.html.twig', array(
            'processus' => $processus
        ));
    }
    /**
     * @Route("/processus/add", name="admin_processus_add")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addAction(Request $request)
    {
        $processus = new Processus() ;
        $form = $this->createForm(ProcessusType::class, $processus) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($processus) ;
            $em->flush() ;
            
            return $this->redirectToRoute("admin_processus_index") ;
        }
        
        return $this->render('admin/processus/add.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/processus/remove/{id}",
     *  name="admin_processus_remove",
     *  requirements={ "id": "\d+" }
     * )
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function removeAction(Processus $processus, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $em->remove($processus) ;
        $em->flush() ;
        
        return $this->redirectToRoute("admin_processus_index") ;
    }
}
