<?php
/* Copyright 2016 C. Thubert */

namespace DocumentBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DocumentBundle\Entity\Fichenonconformite;
use DocumentBundle\Form\FichenonconformiteType;

class FichenonconformiteController extends Controller
{
    /**
     * @Route("/nonconformite/index", name="fnc_index")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $repFnc = $em->getRepository("DocumentBundle:Fichenonconformite") ;
        
        $fiches = $repFnc->findAll() ;
        
        return $this->render('fichenonconformite/index.html.twig', array(
            'fiches' => $fiches
        ) ) ;
    }
    /**
     * @Route("/nonconformite/add", name="fnc_add")
     */
    public function addAction(Request $request)
    {
        $fiche = new Fichenonconformite() ;
        $form = $this->createForm(FichenonconformiteType::class, $fiche) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($fiche) ;
            $em->flush() ;
            
            return $this->redirectToRoute("fnc_index") ;
        }
        
        return $this->render('fichenonconformite/add.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/nonconformite/remove/{id}",
     *  name="fnc_remove",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function remove(Fichenonconformite $fnc, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $em->remove($fnc) ;
        $em->flush() ;
        
        return $this->redirectToRoute("fnc_index") ;
    }
    /**
     * @Route(
     *  "/nonconformite/download/{id}",
     *  name="fnc_download",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function downloadAction(Fichenonconformite $fnc, Request $request)
    {
        $path = $fnc->getUploadDir() ;
        $filename = $fnc->getId() . '.pdf' ;
        
        $response = new Response() ;
        $response->setContent(file_get_contents($path . $filename)) ;
        $response->headers->set('Content-Type', 'application/force-download') ;
        $response->headers->set('Content-disposition', 'filename=' . $filename) ;
        
        return $response ;
    }
}