<?php
/* Copyright 2016 C. Thubert */

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use DocumentBundle\Entity\Support;
use AdminBundle\Form\SupportType;

class SupportController extends Controller
{
    /**
     * @Route("/support/index", name="admin_support_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        $repSup = $em->getRepository("DocumentBundle:Support") ;
        
        $supports = $repSup->findAll() ;
        
        return $this->render('admin/support/index.html.twig', array(
            'supports' => $supports
        ));
    }
    /**
     * @Route("/support/add", name="admin_support_add")
     */
    public function addAction(Request $request)
    {
        $support = new Support() ;
        $form = $this->createForm(SupportType::class, $support) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($support) ;
            $em->flush() ;
            
            return $this->redirectToRoute("admin_support_index") ;
        }
        
        return $this->render('admin/support/add.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/support/remove/{id}",
     *  name="admin_support_remove",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function removeAction(Support $support, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $em->remove($support) ;
        $em->flush() ;
        
        return $this->redirectToRoute("admin_support_index") ;
    }
}
