<?php
/* Copyright 2016 C. Thubert */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig');
    }
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        return $this->render('default/login.html.twig');
    }
    /**
     * @Route("/mentions-legales", name="rights")
     */
    public function creditsrightsAction(Request $request)
    {
        return $this->render('default/credits_rights.html.twig') ;
    }
}
