<?php

namespace Ron\RaspberryPiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('RonRaspberryPiBundle:Default:index.html.twig', array('name' => $name));
    }
}
