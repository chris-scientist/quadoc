<?php

namespace DechetEquipementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DechetEquipementBundle:Default:index.html.twig');
    }
}
