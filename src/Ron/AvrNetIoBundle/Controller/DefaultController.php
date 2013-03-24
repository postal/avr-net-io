<?php

namespace Ron\AvrNetIoBundle\Controller;

use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Response;
use \Ron\AvrNetIoBundle\Avr\AvrNetIo;

/**
 *
 */
class DefaultController extends Controller
{
    /**
     * @var array
     */
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

    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->forward('AvrNetIoBundle:Default:avrOutput');
    }

    /**
     * @return Response
     */
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
     * @param string $period
     * @return Response
     */
    public function avrOutputTemperatureAction($period)
    {
        if (!in_array($period, array_keys($this->getPeriods()))) {
            $periodId = 'day';
        }

        $params = array(
            'period_title' => $this->getPeriodName($period),
            'period' => $period,
        );

        $response = $this->render('AvrNetIoBundle:Default:output_temperature.html.twig', $params);

        return $response;
    }

    /**
     * @param $period
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function outputTemperatureImageAction($period)
    {
        if (!in_array($period, array_keys($this->getPeriods()))) {
            $period = 'day';
        }

        $filename = $period . '.png';
        $headers = array(
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'inline; filename="image.png"'
        );

        if (file_exists($this->container->getParameter('temperature.image.path')) . '/' . $filename) {
            $imagePath = $this->container->getParameter('temperature.image.path') . '/' . $filename;
        }
        $data = file_get_contents($imagePath);

        return new Response($data, 200, $headers);


    }

    /**
     * @param $port
     * @param $value
     * @return Response
     */
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

    /**
     *
     */
    public function switchFloorLightAction()
    {
        $this->getAvr();
    }

    /**
     *
     */
    public function loginAction()
    {

    }

    /**
     * @return bool
     */
    protected function getAvr()
    {
        $avr = $this->container->get('avr');

        if (false === $avr->connect()) {
            return false;
        }

        return $avr;
    }

    /**
     * @return Response
     */
    public function avrInfoAction()
    {
        $avr = $this->getAvr();

        $params = array(
            'avr' => $avr,
        );

        $response = $this->render('AvrNetIoBundle:Default:info.html.twig', $params);

        return $response;

    }

    /**
     * @return Response
     */
    public function avrInputAction()
    {
        $avr = $this->getAvr();

        $params = array(
            'avr' => $avr,
        );

        $response = $this->render('AvrNetIoBundle:Default:input.html.twig', $params);

        return $response;

    }

    /**
     * @return mixed
     */
    public function connectionFailAction()
    {
        $response = $this->render('AvrNetIoBundle:Default:connection_fail.html.twig', array());

        return $response;
    }

}
