<?php
/* Copyright 2016 C. Thubert */

namespace DocumentBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DocumentBundle\Form\DocumentqualiteType;
use DocumentBundle\Form\VersionType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use DocumentBundle\Entity\Documentqualite;
use DocumentBundle\Entity\Version;

class DocumentqualiteController extends Controller
{
    /**
     * @Route("/basedocumentaire/index", name="basedoc_index")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $repDoc = $em->getRepository("DocumentBundle:Documentqualite") ;
        
        $docs = $repDoc->findBy(array('archiveLe' => null)) ;
        
        return $this->render('documentqualite/index.html.twig', array(
            'docs' => $docs
        ) ) ;
    }
    /**
     * @Route("/basedocumentaire/add", name="basedoc_add")
     */
    public function addAction(Request $request)
    {
        $version = new Version() ;
        $version->setDiffuseLe(new \DateTime()) ;
        $version->setArretLe(new \DateTime()) ;
        $documentqualite = new Documentqualite() ;
        $version->setUploadDir( $documentqualite->getUploadDir() ) ;
        $documentqualite->addVersion($version) ;
        $form = $this->createForm(DocumentqualiteType::class, $documentqualite) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($documentqualite) ;
            $em->persist($version) ;
            $em->flush() ;
            
            return $this->redirectToRoute("basedoc_index") ;
        }
        
        return $this->render('documentqualite/add.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/basedocumentaire/version/{id}",
     *  name="basedoc_version",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function addVersion(Documentqualite $doc, Request $request)
    {
        $lastVersion = $doc->getLastVersion() ;
        $version = new Version() ;
        $version->setDiffuseLe(new \DateTime()) ;
        $version->setArretLe(new \DateTime()) ;
        $version->setUploadDir( $doc->getUploadDir() ) ;
        $doc->addVersion($version) ;
        $form = $this->createForm(VersionType::class, $version) ;
        $form
                ->add('reset', ResetType::class)
                ->add('save', SubmitType::class) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $lastVersion->setArretLe( $version->getDiffuseLe() ) ;
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($version) ;
            $em->flush() ;
            
            return $this->redirectToRoute("basedoc_index") ;
        }
        
        return $this->render('documentqualite/add_version.html.twig',
                array(
                    'doc' => $doc,
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/basedocumentaire/remove/{id}",
     *  name="basedoc_remove",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function remove(Documentqualite $doc, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $em->remove($doc) ;
        $versions = $doc->getVersions() ;
        foreach($versions as $version)
        {
            $version->setUploadDir( $doc->getUploadDir() ) ;
            $em->remove($version) ;
        }
        $em->flush() ;
        
        return $this->redirectToRoute("basedoc_index") ;
    }
    /**
     * @Route(
     *  "/basedocumentaire/download/{id}",
     *  name="basedoc_download",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function downloadLast(Documentqualite $doc, Request $request)
    {
        $path = $doc->getUploadDir() ;
        $version = $doc->getLastVersion() ;
        $filename = $version->getId() . '.pdf' ;
        
        $response = new Response() ;
        $response->setContent(file_get_contents($path . $filename)) ;
        $response->headers->set('Content-Type', 'application/force-download') ;
        $response->headers->set('Content-disposition', 'filename=' . $filename) ;
        
        return $response ;
    }
    /**
     * @Route(
     *  "/basedocumentaire/version/download/{id_doc}/{id_ver}",
     *  name="basedoc_version_download",
     *  requirements={
     *      "id_doc": "\d+",
     *      "id_ver": "\d+"
     *  }
     * )
     * @ParamConverter("doc", options={"mapping": {"id_doc": "id"}})
     * @ParamConverter("version", options={"mapping": {"id_ver": "id"}})
     */
    public function downloadVersion(Documentqualite $doc, Version $version, Request $request)
    {
        $path = $doc->getUploadDir() ;
        $filename = $version->getId() . '.pdf' ;
        
        $response = new Response() ;
        $response->setContent(file_get_contents($path . $filename)) ;
        $response->headers->set('Content-Type', 'application/force-download') ;
        $response->headers->set('Content-disposition', 'filename=' . $filename) ;
        
        return $response ;
    }
    /**
     * Récupérer les informations relatives à un document.
     * @param Documentqualite $doc
     * @param Request $request
     * 
     * @Route(
     *  "/basedocumentaire/info/{id}",
     *  name="basedoc_info",
     *  requirements={ "id": "\d+" }
     * )
     * @Method({"GET"})
     */
    public function infoAction (Documentqualite $doc, Request $request)
    {
        $dateArchive = $doc->getArchiveLe() ;
        $equipes = $doc->getEquipes() ;
        $nbEquipes = count($equipes) ;
        $idEquipe = 0 ;
        $txtEquipes = "" ;
        foreach( $equipes as $equipe )
        {
            if( ($idEquipe > 0) && ($idEquipe < $nbEquipes) )
            {
                $txtEquipes .= ', ' ;
            }
            $txtEquipes .= $equipe->getNom() ;
            $idEquipe++ ;
        }
        $commentaire = $doc->getCommentaire() ;
        
        $versionsArray = array() ;      // L'ensemble des versions relatives au document.
        $versions = $doc->getVersions() ;
        foreach( $versions as $version )
        {
            $redacteurs = $version->getRedacteurs() ;
            $nbRedacteurs = count($redacteurs) ;
            $idRedacteur = 0 ;
            $txtRedacteurs = "" ;
            foreach ( $redacteurs as $redacteur )
            {
                if( ($idRedacteur > 0) && ($idRedacteur < $nbRedacteurs) )
                {
                    $txtRedacteurs .= ', ' ;
                }
                $txtRedacteurs .= $redacteur->getNom() . ' ' . $redacteur->getPrenom() ;
                $idRedacteur++ ;
            }
            
            $versionArray = array() ;   // Informations sur une version
            $versionArray[ "id" ] = $version->getId() ;
            $versionArray[ "numero" ] = $version->getNVersion() ;
            $versionArray[ "diffuse_le" ] = $version->getDiffuseLe()->format('d/m/Y') ;
            $versionArray[ "arret_le" ] = $version->getArretLe()->format('d/m/Y') ;
            $versionArray[ "redacteur" ] = $txtRedacteurs ;
            $versionsArray[] = $versionArray ;
        }
        
        $docArray = array() ;
        $docArray[ "id" ] = $doc->getId() ;
        $docArray[ "titre" ] = $doc->getTitre() ;
        $docArray[ "reference" ] = $doc->getReference() ;
        if( ! is_null($dateArchive) )
        {
            $docArray[ "archive_le" ] = $dateArchive->format('d/m/Y') ;
        }
        $docArray[ "interne" ] = ( $doc->getInterne() ? "Oui" : "Non" ) ;
        $docArray[ "domaine" ] = $doc->getDomaine()->getNom() ;
        $docArray[ "forme" ] = $doc->getForme()->getNom() ;
        $docArray[ "equipe" ] = $txtEquipes ;
        $docArray[ "commentaire" ] = ( is_null($commentaire) ? "" : $commentaire ) ;
        $docArray[ "versions" ] = $versionsArray ;
        
        $response = new Response(json_encode($docArray)) ;
        $response->headers->set('Content-Type', 'application/json') ;
        return $response ;
    }
    /**
     * @Route(
     *  "/basedocumentaire/archiver/{id}",
     *  name="basedoc_archiver",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function archiverAction(Documentqualite $doc, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        
        $doc->setArchiveLe( new \DateTime() ) ;
        $em->flush() ;
        
        return $this->redirectToRoute("archives_basedoc") ;
    }
    /**
     * @Route("/archives/basedocumentaire", name="archives_basedoc")
     */
    public function archiveAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        
        // Requête DQL pour filtrer les documents qualité qui sont archivés.
        $dql = "SELECT d FROM DocumentBundle:Documentqualite d " .
               "WHERE d.archiveLe IS NOT NULL" ;
        $docs = $em->createQuery($dql)->getResult() ;
        
        return $this->render('documentqualite/index.html.twig', array(
            'docs' => $docs
        ) ) ;
    }
}
