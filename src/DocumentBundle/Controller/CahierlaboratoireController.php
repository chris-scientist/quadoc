<?php
/* Copyright 2016 C. Thubert */

namespace DocumentBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DocumentBundle\Entity\Cahierlaboratoire;
use DocumentBundle\Form\CahierlaboratoireType;

class CahierlaboratoireController extends Controller
{
    /**
     * @Route("/cahierlaboratoire/index", name="cahierlabo_index")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $repCla = $em->getRepository("DocumentBundle:Cahierlaboratoire") ;
        
        $cahierlabo = $repCla->findBy(array('fermeLe' => null)) ;
        
        return $this->render('cahierlaboratoire/index.html.twig', array(
            'cahierlabo' => $cahierlabo
        ) ) ;
    }
    /**
     * @Route("/cahierlaboratoire/add", name="cahierlabo_add")
     */
    public function addAction(Request $request)
    {
        $cahierlabo = new Cahierlaboratoire() ;
        $form = $this->createForm(CahierlaboratoireType::class, $cahierlabo) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($cahierlabo) ;
            $em->flush() ;
            
            return $this->redirectToRoute("cahierlabo_index") ;
        }
        
        return $this->render('cahierlaboratoire/add.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/cahierlaboratoire/info/{id}",
     *  name="cahierlabo_info",
     *  requirements={ "id": "\d+" }
     * )
     * @Method({"GET"})
     */
    public function infoAction (Cahierlaboratoire $cla, Request $request)
    {
        $utilisateurs = $cla->getUtilisateurs() ;
        $idUtilisateur = 0 ;
        $txtUtilisateurs = "" ;
        foreach ( $utilisateurs as $utilisateur )
        {
            if( ($idUtilisateur > 0) && ($idUtilisateur < count($utilisateurs)) )
            {
                $txtUtilisateurs .= ', ' ;
            }
            $txtUtilisateurs .= $utilisateur->getNom() . ' ' . $utilisateur->getPrenom() ;
            $idUtilisateur++ ;
        }
        
        $ferme = $cla->getFermeLe() ;
        $commentaire = $cla->getCommentaire() ;
        $cahierlaboArray = array() ;
        $cahierlaboArray[ "id" ] = $cla->getId() ;
        $cahierlaboArray[ "intitule" ] = $cla->getIntitule() ;
        $cahierlaboArray[ "ninterne" ] = $cla->getNInterne() ;
        $cahierlaboArray[ "nministere" ] = $cla->getNMinistere() ;
        $cahierlaboArray[ "ouvert" ] = $cla->getOuvertLe()->format('d/m/Y') ;
        if( ! is_null($ferme) )
        {
            $cahierlaboArray[ "ferme" ] = $ferme->format('d/m/Y') ;
        }
        $cahierlaboArray[ "equipe" ] = $cla->getEquipe()->getNom() ;
        $cahierlaboArray[ "utilisateurs" ] = $txtUtilisateurs ;
        $cahierlaboArray[ "support" ] = $cla->getSupport()->getNom() ;
        $cahierlaboArray[ "localisation" ] = $cla->getLocalisation() ;
        $cahierlaboArray[ "commentaire" ] = ( is_null($commentaire) ? "" : $commentaire ) ;
        
        $response = new Response(json_encode($cahierlaboArray)) ;
        $response->headers->set('Content-Type', 'application/json') ;
        return $response ;
    }
    /**
     * @Route(
     *  "/cahierlaboratoire/archiver/{id}",
     *  name="cahierlabo_archiver",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function archiverAction(Cahierlaboratoire $cla, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        
        $cla->setFermeLe( new \DateTime() ) ;
        $em->flush() ;
        
        return $this->redirectToRoute("archives_cahierlabo") ;
    }
    /**
     * @Route("/archives/cahierlaboratoire", name="archives_cahierlabo")
     */
    public function archiveAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        
        // Requête DQL pour filtrer les cahiers de laboratoire qui sont archivés.
        $dql = "SELECT c FROM DocumentBundle:Cahierlaboratoire c " .
               "WHERE c.fermeLe IS NOT NULL" ;
        $cahierlabo = $em->createQuery($dql)->getResult() ;
        
        return $this->render('cahierlaboratoire/index.html.twig', array(
            'cahierlabo' => $cahierlabo
        ) ) ;
    }
}