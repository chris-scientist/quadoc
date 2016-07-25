<?php
/* Copyright 2016 C. Thubert */

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use DocumentBundle\Entity\Mission;
use AdminBundle\Form\MissionType;

class MissionController extends Controller
{
    /**
     * @Route("/mission/index", name="admin_mission_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager() ;
        $repMis = $em->getRepository("DocumentBundle:Mission") ;
        
        $missions = $repMis->findAll() ;
        
        return $this->render('admin/mission/index.html.twig', array(
            'missions' => $missions
        ));
    }
    /**
     * @Route("/mission/add", name="admin_mission_add")
     */
    public function addAction(Request $request)
    {
        $mission = new Mission() ;
        $form = $this->createForm(MissionType::class, $mission) ;
        
        $form->handleRequest($request) ;
        
        if($form->isValid())
        {
            $em = $this->getDoctrine()->getManager() ;
            $em->persist($mission) ;
            $em->flush() ;
            
            return $this->redirectToRoute("admin_mission_index") ;
        }
        
        return $this->render('admin/mission/add.html.twig',
                array(
                    'form' => $form->createView()
                )) ;
    }
    /**
     * @Route(
     *  "/mission/remove/{id}",
     *  name="admin_mission_remove",
     *  requirements={ "id": "\d+" }
     * )
     */
    public function removeAction(Mission $mission, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $em->remove($mission) ;
        $em->flush() ;
        
        return $this->redirectToRoute("admin_mission_index") ;
    }
}
