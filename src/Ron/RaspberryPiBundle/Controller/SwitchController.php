<?php
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 06.03.14
 * Time: 18:03
 */

namespace Ron\RaspberryPiBundle\Controller;


use Ron\RaspberryPiBundle\Form\SwitchesType;
use Ron\RaspberryPiBundle\SwitchEntity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Process\Process;

class SwitchController extends Controller
{

    public function indexAction(Request $request)
    {
        $switches = $this->buildSwitches();
        $form = $this->createForm(new SwitchesType(), array('switches' => $switches));
        $form->handleRequest($request);

        if ($form->isValid()) {
            foreach ($form['switches'] as $switch) {

         #       var_dump($switch->get('submitSwitchOn')->isClicked());
                $result = null;
                $data = $switch->getData();
                if ($switch->get('submitSwitchOn')->isClicked()) {
                    $result = $this->toggleSwitch($data, 1);
                    $status = 'eingeschaltet';
                }

                if ($switch->get('submitSwitchOff')->isClicked()) {
                    $result = $this->toggleSwitch($data, 0);
                    $status = 'ausgeschaltet';
                }


                if ($result == true) {
                    $this->get('session')->getFlashBag()->add('info', $data->getName() . ' wurde ' . $status . '.');
                } elseif(null !== $result) {
                    $this->get('session')->getFlashBag()->add(
                        'error',
                        $data->getName() . ' konnte nicht ' . $status . ' werden.' . "<br />" . $result
                    );
                }
            }
        }

        $viewData = array(
            'form' => $form->createView()
        );

        return $this->render('RonRaspberryPiBundle:Switch:index.html.twig', $viewData);
    }

    /**
     * @param $switch
     * @param $status
     * @return bool
     */
    protected function toggleSwitch($switch, $status)
    {
        $command = '';
        $command .= $this->container->getParameter('raspi_switch_command');
        $command .= ' ' . $this->container->getParameter('raspi_switch_code');
        $command .= ' ' . $switch->getCode();
        $command .= ' ' . $status;

        $process = new Process($command);
        $error = $output = '';
        $process->run(
            function ($type, $buffer) use (&$error, &$output) {
                if (Process::ERR === $type) {
                    $error .= 'ERR > ' . $buffer;
                } else {
                    $output .= 'OUT > ' . $buffer;
                }
            }
        );

        if (!$process->isSuccessful()) {
            return $error;
        }

        return true;
    }

    /**
     * @return array
     */
    private function buildSwitches()
    {
        $switches = array();
        foreach ($this->container->getParameter('raspi_switch_switches') as $switch) {
            $switches[] = new SwitchEntity($switch['name'], $switch['trigger_code']);
        }

        return $switches;
    }
} 