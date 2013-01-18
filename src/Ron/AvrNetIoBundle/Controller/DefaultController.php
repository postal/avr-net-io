<?php

namespace Ron\AvrNetIoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ron\AvrNetIoBundle\Avr\AvrNetIo;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $avr = new AvrNetIo('192.168.178.178');
        #$avr = new AvrNetIo('keller.servebeer.com');
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

    public function setPortAction($port, $value)
    {
        $avr = new AvrNetIo('192.168.178.178');
        if (!$avr->connect()) {
            die("Verbindung nicht möglich!");
        }
        $value = 1 == $value ? AvrNetIo::PORT_ON : AvrNetIo::PORT_OFF;
        $avr->setPort($port, $value);


        $params = array(
            'avr' => $avr,
            'version' => var_export($avr->getVersion(), true),
        );
        $response = $this->render('AvrNetIoBundle:Default:index.html.twig', $params);
        return $response;
    }
}
