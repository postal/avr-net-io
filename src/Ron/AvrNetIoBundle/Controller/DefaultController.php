<?php

namespace Ron\AvrNetIoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ron\AvrNetIoBundle\Avr\AvrNetIo;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $avr = new AvrNetIo('192.168.178.178');
        if ($avr->connect()) {
            echo "IP: ".$avr->getIp()."<br />\r\n";
            echo "Gateway: ".$avr->getGw()."<br />\r\n";
            echo "Netmask: ".$avr->getMask()."<br />\r\n";
            var_dump($avr->getVersion());
            echo "<br />";
            echo "Port 1:".$avr->getPort(1)."<br />\r\n";
            echo "ADC1 1:".$avr->getAdc(1)."<br />\r\n";

            var_dump($avr->getData());
            echo "<br />\r\n";
            var_dump($avr->getStatus(AvrNetIo::STATUS_RAW));
            echo "\r\n";

            for ($i=1; $i<5; $i++) {
                echo "ADC #$i: ".$avr->getAdc($i)."<br />\r\n";
            }

            $avr->disconnect();

        } else {
            die("Verbindung nicht möglich!");
        }

        return $this->render('AvrNetIoBundle:Default:index.html.twig', array('name' => $name));
    }
}
