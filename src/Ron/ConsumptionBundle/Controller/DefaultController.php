<?php

namespace Ron\ConsumptionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('RonConsumptionBundle:Default:index.html.twig', array('name' => $name));
    }
}
