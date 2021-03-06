<?php

namespace Ron\RaspberryPiBundle\Controller;

use Cmfcmf\OpenWeatherMap;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

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
        return $this->forward('RonRaspberryPiBundle:Default:showInput');
    }

    /**
     * Show output informations
     *
     * @return Response
     */
    public function avrOutputAction()
    {
        $avr = $this->getAvr();

        # if (false === $avr) {
        #     return $this->redirect($this->generateUrl('_connection_fail'));
        # }

        $params = array(
            'avr' => $avr,
        );

        $response = $this->render('RonRaspberryPiBundle:Default:output.html.twig', $params);


        $this->disconnect($avr);

        return $response;
    }

    /**
     * @param string $period
     * @return Response
     */
    public function avrOutputTemperatureAction()
    {

        $params = array(
            'periods' => $this->getPeriods(),
        );

        $response = $this->render('RonRaspberryPiBundle:Default:output_temperatures.html.twig', $params);

        return $response;
    }

    /**
     * @param $period
     * @return \HttpResponse
     */
    public function outputTemperatureImageAction($period)
    {
        if (!in_array($period, array_keys($this->getPeriods()))) {
            $period = 'day';
        }

        $filename = $period . '.svg';
        $headers = array(
            'Content-Type' => 'image/svg+xml',
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
        $response = $this->render('RonRaspberryPiBundle:Default:output.html.twig', $params);

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
     * @return bool|object
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

        $response = $this->render('RonRaspberryPiBundle:Default:info.html.twig', $params);

        return $response;

    }

    /**
     * @return Response
     */
    public function showInputAction()
    {

#	$temp = exec('cat /sys/bus/w1/devices/10-0008025fd9a1/w1_slave | cut -d "="  -f2 |tail -n1');
#	$temp = round($temp / 1000, 3);
        if ($this->container->getParameter('avr.enabled')) {
            $avr = $this->getAvr();
        } else {
            $avr = false;
        }
        #       $temp = exec(
        #           'echo $(echo "scale=3; $(grep \'t=\' /sys/bus/w1/devices/w1_bus_master1/10-0008025fd9a1/w1_slave | awk
        #       -F \'t=\' \'{print $2}\') / 1000" | bc -l)'
        #      );
        $owm = new OpenWeatherMap();
    #    $weather = $owm->getWeather('Berlin', 'de', 'metric');

        try {
            $weather = $owm->getWeather('Berlin', 'metric', 'de');
        } catch(OpenWeatherMap\Exception $e) {
            echo 'OpenWeatherMap exception: ' . $e->getMessage() . ' (Code ' . $e->getCode() . ').';
            echo "<br />\n";
        } catch(\Exception $e) {
            echo 'General exception: ' . $e->getMessage() . ' (Code ' . $e->getCode() . ').';
            echo "<br />\n";
        }

        $tempOutside = $weather->temperature->getValue();
        $temp = exec($this->container->getParameter('ron.read_temperature'));
        $pressure = exec($this->container->getParameter('ron.read_pressure'));
        $motion = exec($this->container->getParameter('ron.read_motion'));
        $sun = $this->container->get('ron_sun_helper');
        $params = array(
            'weather' => $weather,
            'avr' => $avr,
            'temp' => $temp,
            'tempOutside' => $tempOutside,
            'motion' => $motion,
            'pressure' => $pressure,
            'sunrise' => $sun->getNextSunrise(),
            'sunset' => $sun->getNextSunset(),
        );

        $response = $this->render('RonRaspberryPiBundle:Default:input.html.twig', $params);

        return $response;

    }

    /**
     * @return Response
     */
    public function connectionFailAction()
    {
        $response = $this->render('RonRaspberryPiBundle:Default:connection_fail.html.twig', array());

        return $response;
    }

    /**
     * Disconnect the Avr
     *
     * @param $avr
     * @return bool
     */
    private function disconnect($avr)
    {
        if ($avr instanceof AvrNetIo) {
            return $avr->disconnect();
        }
    }

}
