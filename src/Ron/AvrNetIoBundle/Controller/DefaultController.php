<?php

namespace Ron\AvrNetIoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ron\AvrNetIoBundle\Avr\AvrNetIo;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $avr = new AvrNetIo('192.168.178.178');
        if (!$avr->connect()) {
            die("Verbindung nicht möglich!");
        }

        $params = array(
            'avr' => $avr,
        );

        $response = $this->render('AvrNetIoBundle:Default:index.html.twig', $params);

        $avr->disconnect();

        return $response;
    }

}
