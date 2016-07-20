<?php
/* Copyright 2016 C. Thubert */

namespace UtilisateurBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use UtilisateurBundle\Form\StageType;
use UtilisateurBundle\Form\FormationType;
use UtilisateurBundle\Repository\UtilisateurRepository;
use RHBundle\Entity\Stage;
use RHBundle\Entity\Formation;
use UtilisateurBundle\Entity\Utilisateur;

class UtilisateurController extends Controller
{
    /**
     * @Route("/utilisateur/initiales/", name="uti_initiales_index")
     */
    public function showInitialesAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        $repUti = $em->getRepository("UtilisateurBundle:Utilisateur") ;
        
        // Récupérer les utilisateurs actifs.
        $utilisateurs = $repUti->findBy(array('enabled' => true)) ;
        
        return $this->render('utilisateur/show_initiales.html.twig', array(
            'utilisateurs' => $utilisateurs
        ) ) ;
    }
    /**
     * @Route("/utilisateur/stage/", name="uti_stage_index")
     */
    public function showStageAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        $repStg = $em->getRepository("RHBundle:Stage") ;
        
        $stages = $repStg->findAll() ;
        
        return $this->render('utilisateur/show_stage.html.twig', array(
            'stages' => $stages
        ) ) ;
    }
    /**
     * @Route("/utilisateur/livretcompetences/", name="uti_livretcompetences_index")
     */
    public function showLivretcompetencesAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        $repUti = $em->getRepository("UtilisateurBundle:Utilisateur") ;
        
        // Récupérer les utilisateurs actifs.
        $utilisateurs = $repUti->findBy(array('enabled' => true)) ;
        
        return $this->render('utilisateur/show_livretcompetences.html.twig', array(
            'utilisateurs' => $utilisateurs
        ) ) ;
    }
    /**
     * @Route("/utilisateur/initiales/add", name="uti_initiales_add")
     */
    public function addInitialesAction(Request $request)
    {
        $formBuilder = $this->createFormBuilder() ;
        $formBuilder
                ->add("utilisateur", EntityType::class,
                        array(
                            'class' => 'UtilisateurBundle:Utilisateur',
                            'choice_label' => function ($uti) {
                                return $uti->getNom() . ' ' . $uti->getPrenom() ;
                            },
                            'query_builder' => function (UtilisateurRepository $rep) {
                                return $rep->trierParNom()
                                        ->where("u.initiale IS NULL") ;
                            }
                        ))
                ->add("initiales", TextType::class)
                ->add("reset", ResetType::class)
                ->add("save", SubmitType::class) ;
        
        $form = $formBuilder->getForm() ;
        $form->handleRequest($request) ;
        
        if( $form->isValid() )
        {
            $idUti = $form["utilisateur"]->getData()->getId() ;
            $initiale = $form["initiales"]->getData() ;
            
            $em = $this->getDoctrine()->getManager() ;
            $repUti = $em->getRepository("UtilisateurBundle:Utilisateur") ;
            $utilisateur = $repUti->findOneBy(array('id' => $idUti)) ;
            $utilisateur->setInitiale($initiale) ;
            $em->flush() ;
            
            return $this->redirectToRoute("uti_initiales_index") ;
        }
        
        return $this->render('utilisateur/add_initiales.html.twig', array(
            'form' => $form->createView()
        )) ;
    }
    /**
     * @Route("/utilisateur/stage/add", name="uti_stage_add")
     */
    public function addStageAction(Request $request)
    {
        $stage = new Stage() ;
        $form = $this->createForm(StageType::class, $stage) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($stage) ;
            $em->flush() ;
            
            return $this->redirectToRoute("uti_stage_index") ;
        }
        
        return $this->render('utilisateur/add_stage.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    // Création du livret de compétences.
    /**
     * @Route("/utilisateur/livretcompetences/add", name="uti_livretcompetences_add")
     */
    public function addLivretcompetencesAction(Request $request)
    {
        $formation = new Formation() ;
        $form = $this->createForm(FormationType::class, $formation) ;
        $form->add('utilisateur', EntityType::class,
                array(
                    'class' => 'UtilisateurBundle\Entity\Utilisateur',
                    'label' => 'Utilisateur',
                    'query_builder' => function (UtilisateurRepository $rep) {
                        return $rep->trierParNom() ;
                    }
                )) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            if( ! is_null($formation->getFichier()) )
            {
                $formation->setBFichier( true ) ;
            }
            
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($formation) ;
            $em->flush() ;
            
            return $this->redirectToRoute("uti_livretcompetences_index") ;
        }
        
        return $this->render('utilisateur/add_livretcompetences.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    // Ajouter une formation au livret de compétences.
    /**
     * @Route(
     *  "/utilisateur/formation/add/{id}", 
     *  name="uti_formation_add",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function addFormationAction(Utilisateur $uti, Request $request)
    {
        $formation = new Formation() ;
        $formation->setUtilisateur($uti) ;
        $form = $this->createForm(FormationType::class, $formation) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            if( ! is_null($formation->getFichier()) )
            {
                $formation->setBFichier( true ) ;
            }
            
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($formation) ;
            $em->flush() ;
            
            return $this->redirectToRoute("uti_livretcompetences_index") ;
        }
        
        return $this->render('utilisateur/add_formation.html.twig',
                array(
                    'utilisateur' => $uti,
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/formation/download/{id}",
     *  name="uti_dl_formation",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function downloadFormationAction(Formation $fat, Request $request)
    {
        $path = $fat->getUploadDir() ;
        $filename = $fat->getId() . '.pdf' ;
        
        $response = new Response() ;
        $response->setContent(file_get_contents($path . $filename)) ;
        $response->headers->set('Content-Type', 'application/force-download') ;
        $response->headers->set('Content-disposition', 'filename=' . $filename) ;
        
        return $response ;
    }
    /**
     * @Route(
     *  "/formation/info/{id}",
     *  name="fat_info",
     *  requirements={ "id": "\d+" }
     * )
     * @Method({"GET"})
     */
    public function infoAction(Utilisateur $uti, Request $request)
    {
        $formationsArray = array() ;
        $formations = $uti->getFormations() ;
        foreach( $formations as $fat )
        {
            $description = $fat->getDescription() ;
            $formationArray = array() ;
            $formationArray[ "id" ] = $fat->getId() ;
            $formationArray[ "intitule" ] = $fat->getIntitule() ;
            $formationArray[ "modeacquisition" ] = $fat->getModeacquisition()->getNom() ;
            $formationArray[ "effectue" ] = $fat->getEffectueLe()->format('d/m/Y') ;
            $formationArray[ "duree" ] = $fat->getDuree() ;
            $formationArray[ "bfichier" ] = $fat->getBFichier() ;
            $formationArray[ "description" ] = ( is_null($description) ? "" : $description ) ;
            $formationsArray[] = $formationArray ;
        }
        
        $utilisateurArray = array() ;
        $utilisateurArray[ "id" ] = $uti->getId() ;
        $utilisateurArray[ "nom" ] = $uti->getNom() ;
        $utilisateurArray[ "prenom" ] = $uti->getPrenom() ;
        $utilisateurArray[ "formations" ] = $formationsArray ;
        
        $response = new Response(json_encode($utilisateurArray)) ;
        $response->headers->set('Content-Type', 'application/json') ;
        return $response ;
    }
}
