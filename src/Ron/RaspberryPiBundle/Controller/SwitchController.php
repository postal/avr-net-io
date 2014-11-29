<?php
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 06.03.14
 * Time: 18:03
 */

namespace Ron\RaspberryPiBundle\Controller;


use Ron\RaspberryPiBundle\Form\SwitchesType;
use Ron\RaspberryPiBundle\Form\TimersType;
use Ron\RaspberryPiBundle\Lib\AtClient;
use Ron\RaspberryPiBundle\SwitchEntity;
use Ron\RaspberryPiBundle\TimerEntity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SwitchController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function indexAction(Request $request)
    {
        if (false === $this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new AccessDeniedException();
        }

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
                } elseif (null !== $result) {
                    $this->get('session')->getFlashBag()->add(
                        'error',
                        $data->getName() . ' konnte nicht ' . $status . ' werden.' . "<br />" . $result
                    );
                }
            }

            return $this->redirect($this->generateUrl('ron_raspberry_pi_switch'));
        }

        $formTimers = $this->createForm(
            new TimersType(),
            array('timers' => $this->buildTimers())
        #      array(
        #          'action' => $this->generateUrl('ron_raspberry_pi_timer_start')
        #      )
        );

        $formTimers->handleRequest($request);

        if ($formTimers->isValid()) {
            foreach ($formTimers['timers'] as $key => $timer) {
                /**
                 * @var $timer TimersType
                 */
                if ($timer->get('submitTimer' . $key)->isClicked()) {
                    $resultTimer = $this->startTimer($timer->getData(), $timer->getData()->getTime()[$key]);

                }

            }
        }

        $viewData = array(
            'form' => $form->createView(),
            'formTimers' => $formTimers->createView(),
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


    public function startTimerAction(Request $request)
    {


        $formTimers = $this->createForm(
            new TimersType(),
            array('timers' => $this->buildTimers())
        );

        $formTimers->handleRequest($request);

        if ($formTimers->isValid()) {

            $data = $formTimers->getData();
            foreach ($formTimers['timers'] as $key => $timer) {
                /**
                 * @var $timer TimersType
                 */
                $timer->get('submitTimer' . $key)->isClicked();
            }

        }

        var_dump($formTimers->getErrors(false, false));
        exit;

        # return $this->forward('RonRaspberryPiBundle:Switch:index');
        #  return $this->redirect($this->generateUrl('ron_raspberry_pi_switch'));
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

    /**
     * @return array
     */
    private function buildTimers()
    {
        $timers = array();
        foreach ($this->container->getParameter('raspi_timers_times') as $timer) {
            $timers[] = new TimerEntity(
                $timer['name'],
                $timer['groupCode'],
                $timer['trigger_code'],
                $timer['times'],
                $timer['timeUnit']
            );
        }


        return $timers;
    }

    /**
     * @param \Ron\RaspberryPiBundle\TimerEntity $timerEntity
     * @param $time
     * @return bool|string
     */
    private function startTimer(TimerEntity $timerEntity, $time)
    {

        $client = new AtClient();
        $atCommand = $client->createAt();
        $atCommand->setTime($time);
        $atCommand->setTimeUnit($timerEntity->getTimeUnit());
        $atCommand->setCommand('/usr/bin/send ' . $timerEntity->getGroupCode().' '.$timerEntity->getCode().' 1');

        $client->process($atCommand);

        if ($client->getProcess()->isSuccessful()) {
            $this->get('session')->getFlashBag()->add('info', $timerEntity->getName() . ' wurde gestartet.');
        } else {
            $this->get('session')->getFlashBag()->add('error', $client->getError() . ' wurde gestartet.');
        }

        return $client;
    }
} 