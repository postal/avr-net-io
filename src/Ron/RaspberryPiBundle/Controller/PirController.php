<?php
/**
 * @author Ronny Seiler
 */

namespace Ron\RaspberryPiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PirController extends Controller
{
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
     * @var array
     */
    protected $periods = array(
        'hour' => 'Stunde',
        'day' => 'Tag',
        'week' => 'Woche',
        'month' => 'Monat',
    );

    /**
     * @param $period
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function outputPirImageAction($period)
    {
        if (!in_array($period, array_keys($this->getPeriods()))) {
            $period = 'day';
        }

        $filename = $period . '.svg';
        $headers = array(
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'inline; filename="image.png"'
        );

        if (file_exists($this->container->getParameter('pir.image.path')) . '/' . $filename) {
            $imagePath = $this->container->getParameter('pir.image.path') . '/' . $filename;
        }
        $data = file_get_contents($imagePath);

        return new Response($data, 200, $headers);

    }


    /**
     * @param string $period
     * @return Response
     */
    public function outputPirAction($period = null)
    {
        if (!in_array($period, array_keys($this->getPeriods()))) {
            $period = 'day';
        }

        $params = array(
            'period_title' => $this->getPeriodName($period),
            'period' => $period,
        );

        $response = $this->render('RonRaspberryPiBundle:Pir:output_pir.html.twig', $params);

        return $response;
    }

    /**
     * @return Response
     */
    public function pirsAction()
    {
        $params = array(
            'period' => $this->getPeriods(),
        );

        $response = $this->render('RonRaspberryPiBundle:Pir:output_pirs.html.twig', $params);

        return $response;
    }
}