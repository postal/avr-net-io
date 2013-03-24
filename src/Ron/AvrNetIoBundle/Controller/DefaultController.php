<?php

namespace Ron\AvrNetIoBundle\Controller;

use \Symfony\Bundle\FrameworkBundle\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \Ron\AvrNetIoBundle\Avr\AvrNetIo;

class DefaultController extends Controller
{
    protected $periods = array(
        'hour' => 'Stunde',
        'day' => 'Tag',
        'week' => 'Woche',
        'month' => 'Monat',
        'year' => 'Jahr',
    );


    /**
     * @return array
     */
    public function getPeriods()
    {
        return $this->periods;
    }


    /**
     * Liefert den Namen zu einer PeriodenId
     * @param string $periodId
     * @return string|false
     */
    public function getPeriodName($periodId)
    {
        if (isset($this->periods[$periodId])) {
            return $this->periods[$periodId];
        }
        return false;
    }

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

    /**
     * @param string $periodId
     * @return Response
     */
    public function avrOutputTemperatureAction($periodId)
    {
        if (!in_array($periodId, keys($this->getPeriods()))) {
            $periodId = 'day';
        }

        $filename = $periodId . '.png';
        if (file_exists($this->container->get('temperature.image.path')) . '/' . $filename) {
            $imagePath = $this->container->get('temperature.image.path') . '/' . $filename;
        }

        $params = array(
#            'avr' => $avr,
            'image_path' => $imagePath,
            'period_title' => $this->getPeriodName($periodId),
        );

        $response = $this->render('AvrNetIoBundle:Default:output_temperature.html.twig', $params);

        return $response;
    }

    public function outputTemperatureImageAction($periodId)
    {
        if (!in_array($periodId, keys($this->getPeriods()))) {
            $periodId = 'day';
        }

        $filename = $periodId . '.png';
        $headers = array(
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'inline; filename="image.png"'
        );

        if (file_exists($this->container->get('temperature.image.path')) . '/' . $filename) {
            $imagePath = $this->container->get('temperature.image.path') . '/' . $filename;
        }
        $data = file_get_contents($imagePath);

        return new Response($data, 200, $headers);


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
