<?php
/* Copyright 2016 C. Thubert */

namespace DechetEquipementBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DechetEquipementBundle\Entity\Dechet;
use DechetEquipementBundle\Entity\Contrat;
use DechetEquipementBundle\Entity\Enlevement;
use DechetEquipementBundle\Form\ContratType;
use DechetEquipementBundle\Form\EnlevementType;

class DechetController extends Controller
{
    /**
     * @Route("/dechet/index", name="dec_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        $repDec = $em->getRepository("DechetEquipementBundle:Dechet") ;
        
        $dechets = $repDec->findAll() ;
        
        return $this->render('dechet/index.html.twig', array(
            'dechets' => $dechets
        ) ) ;
    }
    /**
     * @Route("/dechet/contrat/", name="dec_show_contrat")
     */
    public function showContratAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        
        // Récupérer les contrats pour les déchets
        // et seulement pour les déchets (pas les "contrats équipements").
        $dql = "SELECT c FROM DechetEquipementBundle:Contrat c " .
               "LEFT JOIN c.dechets d " .
               "WHERE c.contratdechet = TRUE" ;
        $contrats = $em->createQuery($dql)->getResult() ;
        
        return $this->render('dechet/show_contrat.html.twig', array(
            'contrats' => $contrats
        ) ) ;
    }
    /**
     * @Route("/enlevement/index", name="dec_show_enlevement")
     */
    public function showEnlevementAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        $repEnl = $em->getRepository("DechetEquipementBundle:Enlevement") ;
        
        // Récupérer les interventions pour les déchets.
        $enelevements = $repEnl->findAll() ;
        
        return $this->render('dechet/show_enlevement.html.twig', array(
            'enlevements' => $enelevements
        ) ) ;
    }
    /**
     * @Route(
     *  "/dechet/contrat/{id}",
     *  name="dec_contrat",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function addContratAction(Dechet $dec, Request $request)
    {
        $contrat = new Contrat() ;
        $contrat->setContratdechet(true) ;
        $dec->addContratdechet($contrat) ;
        $form = $this->createForm(ContratType::class, $contrat) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($contrat) ;
            $em->flush() ;
            
            return $this->redirectToRoute("dec_index") ;
        }
        
        return $this->render('dechet/add_contrat.html.twig',
                array(
                    'dec' => $dec,
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/dechet/enlevement/{id}",
     *  name="dec_enlevement",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function addEnlevementAction(Dechet $dec, Request $request)
    {
        $enlevement = new Enlevement() ;
        $dec->addHistorique($enlevement) ;
        $form = $this->createForm(EnlevementType::class, $enlevement) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($enlevement) ;
            $em->flush() ;
            
            return $this->redirectToRoute("dec_index") ;
        }
        
        return $this->render('dechet/add_enlevement.html.twig',
                array(
                    'dec' => $dec,
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/enlevement/download/{id}",
     *  name="dec_dl_enlevement",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function downloadEnlevementAction(Enlevement $enl, Request $request)
    {
        $path = $enl->getUploadDir() ;
        $filename = $enl->getId() . '.pdf' ;
        
        $response = new Response() ;
        $response->setContent(file_get_contents($path . $filename)) ;
        $response->headers->set('Content-Type', 'application/force-download') ;
        $response->headers->set('Content-disposition', 'filename=' . $filename) ;
        
        return $response ;
    }
    /**
     * Récupérer les informations relatives à un déchet.
     * 
     * @Route(
     *  "/dechet/info/{id}",
     *  name="dec_info",
     *  requirements={ "id": "\d+" }
     * )
     * @Method({"GET"})
     */
    public function infoAction(Dechet $dec, Request $request)
    {
        $contratsArray = array() ;
        $contrats = $dec->getContratdechet() ;
        foreach( $contrats as $cnt )
        {
            $numero = $cnt->getNumero() ;
            $cout = $cnt->getCout() ;
            $commentaire = $cnt->getCommentaire() ;
            $contratArray = array() ;
            $contratArray[ "id" ] = $cnt->getId() ;
            $contratArray[ "debut" ] = $cnt->getDebut()->format('d/m/Y') ;
            $contratArray[ "fin" ] = $cnt->getFin()->format('d/m/Y') ;
            $contratArray[ "numero" ] = ( is_null($numero) ? "" : $numero ) ;
            $contratArray[ "cout" ] = ( is_null($cout) ? "" : $cout ) ;
            $contratArray[ "commentaire" ] = ( is_null($commentaire) ? "" : $commentaire ) ;
            $contratsArray[] = $contratArray ;
        }
        
        $enlevementsArray = array() ;
        $enlevements = $dec->getHistorique() ;
        foreach( $enlevements as $enl )
        {
            $intervenant = $enl->getIntervenant() ;
            $commentaire = $enl->getCommentaire() ;
            $enlevementArray = array() ;
            $enlevementArray[ "id" ] = $enl->getId() ;
            $enlevementArray[ "enleve" ] = $enl->getEnleveLe()->format('d/m/Y') ;
            $enlevementArray[ "intervenant" ] = $intervenant->getNom() . ' ' . $intervenant->getPrenom() ;
            $enlevementArray[ "quantite" ] = $enl->getQuantite() ;
            $enlevementArray[ "commentaire" ] = ( is_null($commentaire) ? "" : $commentaire ) ;
            $enlevementsArray[] = $enlevementArray ;
        }
        
        $dechetArray = array() ;
        $dechetArray[ "id" ] = $dec->getId() ;
        $dechetArray[ "nom" ] = $dec->getNom() ;
        $dechetArray[ "desc" ] = $dec->getDescription() ;
        $dechetArray[ "contrats" ] = $contratsArray ;
        $dechetArray[ "enlevements" ] = $enlevementsArray ;
        
        $response = new Response(json_encode($dechetArray)) ;
        $response->headers->set('Content-Type', 'application/json') ;
        return $response ;
    }
}