<?php
/* Copyright 2016 C. Thubert */

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use DocumentBundle\Entity\Domaine;
use AdminBundle\Form\DomaineType;

class DomaineController extends Controller
{
    /**
     * @Route("/domaine/index", name="admin_domaine_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        $repDom = $em->getRepository("DocumentBundle:Domaine") ;
        
        $domaines = $repDom->findAll() ;
        
        return $this->render('admin/domaine/index.html.twig', array(
            'domaines' => $domaines
        ));
    }
    /**
     * @Route("/domaine/add", name="admin_domaine_add")
     */
    public function addAction(Request $request)
    {
        $domaine = new Domaine() ;
        $form = $this->createForm(DomaineType::class, $domaine) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($domaine) ;
            $em->flush() ;
            
            return $this->redirectToRoute("admin_domaine_index") ;
        }
        
        return $this->render('admin/domaine/add.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/domaine/remove/{id}",
     *  name="admin_domaine_remove",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function removeAction(Domaine $domaine, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $em->remove($domaine) ;
        $em->flush() ;
        
        return $this->redirectToRoute("admin_domaine_index") ;
    }
}
