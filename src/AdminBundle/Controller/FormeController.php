<?php
/* Copyright 2016 C. Thubert */

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use DocumentBundle\Entity\Forme;
use AdminBundle\Form\FormeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class FormeController extends Controller
{
    /**
     * @Route("/forme/index", name="admin_forme_index")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        $repFor = $em->getRepository("DocumentBundle:Forme") ;
        
        $formes = $repFor->findAll() ;
        
        return $this->render('admin/forme/index.html.twig', array(
            'formes' => $formes
        ));
    }
    /**
     * @Route("/forme/add", name="admin_forme_add")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addAction(Request $request)
    {
        $forme = new Forme() ;
        $form = $this->createForm(FormeType::class, $forme) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($forme) ;
            $em->flush() ;
            
            return $this->redirectToRoute("admin_forme_index") ;
        }
        
        return $this->render('admin/forme/add.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/forme/remove/{id}",
     *  name="admin_forme_remove",
     *  requirements={ "id": "\d+" }
     * )
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function removeAction(Forme $forme, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $em->remove($forme) ;
        $em->flush() ;
        
        return $this->redirectToRoute("admin_forme_index") ;
    }
}
