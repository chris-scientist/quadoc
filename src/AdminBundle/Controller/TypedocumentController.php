<?php
/* Copyright 2016 C. Thubert */

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use DocumentBundle\Entity\Typedocument;
use AdminBundle\Form\TypedocumentType;

class TypedocumentController extends Controller
{
    /**
     * @Route("/typedocument/index", name="admin_typedocument_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        $repTdo = $em->getRepository("DocumentBundle:Typedocument") ;
        
        $typesdocument = $repTdo->findAll() ;
        
        return $this->render('admin/typedocument/index.html.twig', array(
            'typesdocument' => $typesdocument
        ));
    }
    /**
     * @Route("/typedocument/add", name="admin_typedocument_add")
     */
    public function addAction(Request $request)
    {
        $typedocument = new Typedocument() ;
        $form = $this->createForm(TypedocumentType::class, $typedocument) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($typedocument) ;
            $em->flush() ;
            
            return $this->redirectToRoute("admin_typedocument_index") ;
        }
        
        return $this->render('admin/typedocument/add.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/typedocument/remove/{id}",
     *  name="admin_typedocument_remove",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function removeAction(Typedocument $typedocument, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $em->remove($typedocument) ;
        $em->flush() ;
        
        return $this->redirectToRoute("admin_typedocument_index") ;
    }
}
