<?php
/* Copyright 2016 C. Thubert */

namespace DocumentBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use DocumentBundle\Entity\Version;
use DocumentBundle\Entity\Documentmanagement;
use DocumentBundle\Form\DocumentmanagementType;
use DocumentBundle\Form\VersionType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DocumentmanagementController extends Controller
{
    /**
     * @Route("/management/index", name="management_index")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $repDoc = $em->getRepository("DocumentBundle:Documentmanagement") ;
        
        $docs = $repDoc->findBy(array('archiveLe' => null)) ;
        
        return $this->render('documentmanagement/index.html.twig', array(
            'docs' => $docs
        ) ) ;
    }
    /**
     * @Route("/management/add", name="management_add")
     */
    public function addAction(Request $request)
    {
        $version = new Version() ;
        $version->setDiffuseLe(new \DateTime()) ;
        $version->setArretLe(new \DateTime()) ;
        $docmanagement = new Documentmanagement() ;
        $version->setUploadDir( $docmanagement->getUploadDir() ) ;
        $docmanagement->addVersion($version) ;
        $form = $this->createForm(DocumentmanagementType::class, $docmanagement) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($docmanagement) ;
            $em->persist($version) ;
            $em->flush() ;
            
            return $this->redirectToRoute("management_index") ;
        }
        
        return $this->render('documentmanagement/add.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/management/version/{id}",
     *  name="management_version",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function addVersion(Documentmanagement $doc, Request $request)
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
            
            return $this->redirectToRoute("management_index") ;
        }
        
        return $this->render('documentmanagement/add_version.html.twig',
                array(
                    'doc' => $doc,
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/management/remove/{id}",
     *  name="management_remove",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function remove(Documentmanagement $doc, Request $request)
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
        
        return $this->redirectToRoute("management_index") ;
    }
    /**
     * @Route(
     *  "/management/download/{id}",
     *  name="management_download",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function downloadLast(Documentmanagement $doc, Request $request)
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
     *  "/management/version/download/{id_doc}/{id_ver}",
     *  name="management_version_download",
     *  requirements={
     *      "id_doc": "\d+",
     *      "id_ver": "\d+"
     *  }
     * )
     * @ParamConverter("doc", options={"mapping": {"id_doc": "id"}})
     * @ParamConverter("version", options={"mapping": {"id_ver": "id"}})
     */
    public function downloadVersion(Documentmanagement $doc, Version $version, Request $request)
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
     * @Route(
     *  "/management/info/{id}",
     *  name="management_info",
     *  requirements={ "id": "\d+" }
     * )
     * @Method({"GET"})
     */
    public function infoAction (Documentmanagement $doc, Request $request)
    {
        $dateArchive = $doc->getArchiveLe() ;
        
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
        if( ! is_null($dateArchive) )
        {
            $docArray[ "archive_le" ] = $dateArchive->format('d/m/Y') ;
        }
        $docArray[ "typedoc" ] = $doc->getTypedocument()->getNom() ;
        $docArray[ "mission" ] = $doc->getMission()->getNom() ;
        $docArray[ "versions" ] = $versionsArray ;
        
        $response = new Response(json_encode($docArray)) ;
        $response->headers->set('Content-Type', 'application/json') ;
        return $response ;
    }
    /**
     * @Route(
     *  "/management/archiver/{id}",
     *  name="management_archiver",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function archiverAction(Documentmanagement $doc, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        
        $doc->setArchiveLe( new \DateTime() ) ;
        $em->flush() ;
        
        return $this->redirectToRoute("archives_management") ;
    }
    /**
     * @Route("/archives/management", name="archives_management")
     */
    public function archiveAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        
        // Requête DQL pour filtrer les documents qualité qui sont archivés.
        $dql = "SELECT d FROM DocumentBundle:Documentmanagement d " .
               "WHERE d.archiveLe IS NOT NULL" ;
        $docs = $em->createQuery($dql)->getResult() ;
        
        return $this->render('documentmanagement/index.html.twig', array(
            'docs' => $docs
        ) ) ;
    }
}