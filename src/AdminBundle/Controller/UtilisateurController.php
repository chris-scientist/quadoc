<?php
/* Copyright 2016 C. Thubert */

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use UtilisateurBundle\Entity\Utilisateur;
use AdminBundle\Form\UtilisateurType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UtilisateurController extends Controller
{
    /**
     * @Route("/utilisateur/index", name="admin_utilisateur_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        $repUti = $em->getRepository("UtilisateurBundle:Utilisateur") ;
        
        $utilisateurs = $repUti->findAll() ;
        
        return $this->render('admin/utilisateur/index.html.twig', array(
            'utilisateurs' => $utilisateurs
        ));
    }
    /**
     * @Route("/utilisateur/add", name="admin_utilisateur_add")
     */
    public function addAction(Request $request)
    {
        $utilisateur = new Utilisateur() ;
        $form = $this->createForm(UtilisateurType::class, $utilisateur) ;
        $form->add('plainPassword', RepeatedType::class,
                array(
                    'type' => PasswordType::class,
                    'first_options' => array('label' => 'Mot de passe'),
                    'second_options' => array('label' => 'Répété le mot de passe')
                )) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $password = $this->get('security.password_encoder')
                    ->encodePassword($utilisateur, $utilisateur->getPlainPassword()) ;
            $utilisateur->setPassword($password) ;
            $utilisateur->setEnabled(true) ;
            
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($utilisateur) ;
            $em->flush() ;
            
            return $this->redirectToRoute("admin_utilisateur_index") ;
        }
        
        return $this->render('admin/utilisateur/add.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/utilisateur/update/{id}", 
     *  name="admin_utilisateur_update",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function updateAction(Utilisateur $utilisateur, Request $request)
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->flush() ;
            
            return $this->redirectToRoute("admin_utilisateur_index") ;
        }
        
        return $this->render('admin/utilisateur/update.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/utilisateur/password/{id}", 
     *  name="admin_utilisateur_password",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function setPasswordAction(Utilisateur $utilisateur, Request $request)
    {
        $formBuilder = $this->createFormBuilder() ;
        $formBuilder
                ->add('plainPassword', RepeatedType::class,
                array(
                    'type' => PasswordType::class,
                    'first_options' => array('label' => 'Mot de passe'),
                    'second_options' => array('label' => 'Répété le mot de passe')
                ))
                ->add("reset", ResetType::class)
                ->add("save", SubmitType::class) ;
        
        $form = $formBuilder->getForm() ;
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $plainPassword = $form[ 'plainPassword' ]->getData() ;
            $password = $this->get('security.password_encoder')
                    ->encodePassword($utilisateur, $plainPassword) ;
            $utilisateur->setPassword($password) ;
            
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($utilisateur) ;
            $em->flush() ;
            
            return $this->redirectToRoute("admin_utilisateur_index") ;
        }
        
        return $this->render('admin/utilisateur/password.html.twig',
                array(
                    'utilisateur' => $utilisateur,
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/utilisateur/remove/{id}",
     *  name="admin_utilisateur_remove",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function removeAction(Utilisateur $utilisateur, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $em->remove($utilisateur) ;
        $em->flush() ;
        
        return $this->redirectToRoute("admin_utilisateur_index") ;
    }
    /**
     * @Route(
     *  "/utilisateur/lock/{id}",
     *  name="admin_utilisateur_lock",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function lockAction(Utilisateur $utilisateur, Request $request)
    {
        $utilisateur->setLocked( true ) ;
        $em = $this->getDoctrine()->getManager() ;
        $em->flush() ;
        
        return $this->redirectToRoute("admin_utilisateur_index") ;
    }
    /**
     * @Route(
     *  "/utilisateur/unlock/{id}",
     *  name="admin_utilisateur_unlock",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function unlockAction(Utilisateur $utilisateur, Request $request)
    {
        $utilisateur->setLocked( false ) ;
        $em = $this->getDoctrine()->getManager() ;
        $em->flush() ;
        
        return $this->redirectToRoute("admin_utilisateur_index") ;
    }
}
