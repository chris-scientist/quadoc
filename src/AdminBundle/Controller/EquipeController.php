<?php
/* Copyright 2016 C. Thubert */

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use UtilisateurBundle\Entity\Equipe;
use AdminBundle\Form\EquipeType;

class EquipeController extends Controller
{
    /**
     * @Route("/equipe/index", name="admin_equipe_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        $repEqp = $em->getRepository("UtilisateurBundle:Equipe") ;
        
        $equipes = $repEqp->findAll() ;
        
        return $this->render('admin/equipe/index.html.twig', array(
            'equipes' => $equipes
        ));
    }
    /**
     * @Route("/equipe/add", name="admin_equipe_add")
     */
    public function addAction(Request $request)
    {
        $equipe = new Equipe() ;
        $form = $this->createForm(EquipeType::class, $equipe) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($equipe) ;
            $em->flush() ;
            
            return $this->redirectToRoute("admin_equipe_index") ;
        }
        
        return $this->render('admin/equipe/add.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/equipe/remove/{id}",
     *  name="admin_equipe_remove",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function removeAction(Equipe $equipe, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $em->remove($equipe) ;
        $em->flush() ;
        
        return $this->redirectToRoute("admin_equipe_index") ;
    }
}
