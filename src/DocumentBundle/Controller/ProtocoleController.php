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
use DocumentBundle\Entity\Protocole;
use DocumentBundle\Form\ProtocoleType;
use DocumentBundle\Form\VersionType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProtocoleController extends Controller
{
    /**
     * @Route("/protocole/index", name="protocole_index")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $repPto = $em->getRepository("DocumentBundle:Protocole") ;
        
        $protocoles = $repPto->findAll() ;
        
        return $this->render('protocole/index.html.twig', array(
            'protocoles' => $protocoles
        ) ) ;
    }
    /**
     * @Route("/protocole/add", name="protocole_add")
     */
    public function addAction(Request $request)
    {
        $version = new Version() ;
        $version->setDiffuseLe(new \DateTime()) ;
        $version->setArretLe(new \DateTime()) ;
        $protocole = new Protocole() ;
        $version->setUploadDir( $protocole->getUploadDir() ) ;
        $protocole->addVersion($version) ;
        $form = $this->createForm(ProtocoleType::class, $protocole) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($protocole) ;
            $em->persist($version) ;
            $em->flush() ;
            
            return $this->redirectToRoute("protocole_index") ;
        }
        
        return $this->render('protocole/add.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/protocole/version/{id}",
     *  name="protocole_version",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function addVersion(Protocole $pto, Request $request)
    {
        $lastVersion = $pto->getLastVersion() ;
        $version = new Version() ;
        $version->setDiffuseLe(new \DateTime()) ;
        $version->setArretLe(new \DateTime()) ;
        $version->setUploadDir( $pto->getUploadDir() ) ;
        $pto->addVersion($version) ;
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
            
            return $this->redirectToRoute("protocole_index") ;
        }
        
        return $this->render('protocole/add_version.html.twig',
                array(
                    'protocole' => $pto,
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/protocole/download/{id}",
     *  name="protocole_download",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function downloadLast(Protocole $pto, Request $request)
    {
        $path = $pto->getUploadDir() ;
        $version = $pto->getLastVersion() ;
        $filename = $version->getId() . '.pdf' ;
        
        $response = new Response() ;
        $response->setContent(file_get_contents($path . $filename)) ;
        $response->headers->set('Content-Type', 'application/force-download') ;
        $response->headers->set('Content-disposition', 'filename=' . $filename) ;
        
        return $response ;
    }
    /**
     * @Route(
     *  "/protocole/version/download/{id_pto}/{id_ver}",
     *  name="protocole_version_download",
     *  requirements={
     *      "id_pto": "\d+",
     *      "id_ver": "\d+"
     *  }
     * )
     * @ParamConverter("pto", options={"mapping": {"id_pto": "id"}})
     * @ParamConverter("version", options={"mapping": {"id_ver": "id"}})
     */
    public function downloadVersion(Protocole $pto, Version $version, Request $request)
    {
        $path = $pto->getUploadDir() ;
        $filename = $version->getId() . '.pdf' ;
        
        $response = new Response() ;
        $response->setContent(file_get_contents($path . $filename)) ;
        $response->headers->set('Content-Type', 'application/force-download') ;
        $response->headers->set('Content-disposition', 'filename=' . $filename) ;
        
        return $response ;
    }
    /**
     * @Route(
     *  "/protocole/info/{id}",
     *  name="protocole_info",
     *  requirements={ "id": "\d+" }
     * )
     * @Method({"GET"})
     */
    public function infoAction (Protocole $pto, Request $request)
    {
        $versionsArray = array() ;      // L'ensemble des versions relatives au protocole.
        $versions = $pto->getVersions() ;
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
        
        $responsable = $pto->getResponsable() ;
        $protocoleArray = array() ;
        $protocoleArray[ "id" ] = $pto->getId() ;
        $protocoleArray[ "titre" ] = $pto->getTitre() ;
        $protocoleArray[ "numero" ] = $pto->getNumero() ;
        $protocoleArray[ "responsable" ] = $responsable->getNom() . ' ' . $responsable->getPrenom() ;
        $protocoleArray[ "equipe" ] = $pto->getEquipe()->getNom() ;
        $protocoleArray[ "debut" ] = $pto->getDebut()->format('d/m/Y') ;
        $protocoleArray[ "versions" ] = $versionsArray ;
        
        $response = new Response(json_encode($protocoleArray)) ;
        $response->headers->set('Content-Type', 'application/json') ;
        return $response ;
    }
}