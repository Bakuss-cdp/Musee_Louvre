<?php

namespace MUSEE\LouvreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MUSEELouvreBundle:Default:index.html.twig');
    }
}
