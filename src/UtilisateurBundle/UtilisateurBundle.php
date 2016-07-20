<?php
/* Copyright 2016 C. Thubert */

namespace UtilisateurBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class UtilisateurBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle' ;
    }
}
