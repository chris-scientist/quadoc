<?php
/* Copyright 2016 C. Thubert */

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use DechetEquipementBundle\Entity\Categorie;
use AdminBundle\Form\CategorieType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CategorieController extends Controller
{
    /**
     * @Route("/categorie/index", name="admin_categorie_index")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        $repCat = $em->getRepository("DechetEquipementBundle:Categorie") ;
        
        $categories = $repCat->findAll() ;
        
        return $this->render('admin/categorie/index.html.twig', array(
            'categories' => $categories
        ));
    }
    /**
     * @Route("/categorie/add", name="admin_categorie_add")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addAction(Request $request)
    {
        $categorie = new Categorie() ;
        $form = $this->createForm(CategorieType::class, $categorie) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($categorie) ;
            $em->flush() ;
            
            return $this->redirectToRoute("admin_categorie_index") ;
        }
        
        return $this->render('admin/categorie/add.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/categorie/remove/{id}",
     *  name="admin_categorie_remove",
     *  requirements={ "id": "\d+" }
     * )
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function removeAction(Categorie $categorie, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $em->remove($categorie) ;
        $em->flush() ;
        
        return $this->redirectToRoute("admin_categorie_index") ;
    }
}
