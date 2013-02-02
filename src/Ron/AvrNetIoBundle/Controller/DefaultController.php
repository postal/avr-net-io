<?php

namespace Ron\AvrNetIoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ron\AvrNetIoBundle\Avr\AvrNetIo;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->forward('AvrNetIoBundle:Default:avrOutput');
    }

    public function avrOutputAction()
    {
        $avr = $this->getAvr();

        if (false === $avr) {
            return $this->redirect($this->generateUrl('_connection_fail'));
        }

        $params = array(
            'avr' => $avr,
        );

        $response = $this->render('AvrNetIoBundle:Default:output.html.twig', $params);

        $avr->disconnect();

        return $response;
    }

    public function setPortAction($port, $value)
    {
        if (!$avr = $this->getAvr()) {
            $this->redirect($this->generateUrl('_connection_fail'));
        }

        $value = 1 == $value ? AvrNetIo::PORT_ON : AvrNetIo::PORT_OFF;
        $avr->setPort($port, $value);

        $params = array(
            'avr' => $avr,
        );
        $response = $this->render('AvrNetIoBundle:Default:output.html.twig', $params);

        return $response;
    }

    public function switchFloorLightAction()
    {
        $this->getAvr();
    }

    public function loginAction()
    {

    }

    protected function getAvr()
    {
        $avr = $this->container->get('avr');

        if (false === $avr->connect()) {
            return false;
        }

        return $avr;
    }

    public function avrInfoAction()
    {
        if (!$avr = $this->getAvr()) {
            $this->redirect($this->generateUrl('_connection_fail'));
        }

        $params = array(
            'avr' => $avr,
        );

        $response = $this->render('AvrNetIoBundle:Default:info.html.twig', $params);

        return $response;

    }

    public function avrInputAction()
    {
        if (!$avr = $this->getAvr()) {
            $this->redirect($this->generateUrl('_connection_fail'));
        }

        $params = array(
            'avr' => $avr,
        );

        $response = $this->render('AvrNetIoBundle:Default:input.html.twig', $params);

        return $response;

    }

    public function connectionFailAction()
    {
        $response = $this->render('AvrNetIoBundle:Default:connection_fail.html.twig', array());

        return $response;
    }

}
