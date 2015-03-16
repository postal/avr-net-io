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
use Symfony\Component\Form\Form;
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
                /**
                 * @var $switch Form
                 */
                $result = $status = null;
                /** @var $data SwitchEntity */
                $data = $switch->getData();
                if ($switch->get('submitSwitchOn')->isClicked()) {
                    $command = $this->buildCommand($data->getCode(), $data->getGroupCode(), 1);
                    $result = $this->toggleSwitch($command);
                    $status = 'eingeschaltet';
                }

                if ($switch->get('submitSwitchOff')->isClicked()) {
                    $command = $this->buildCommand($data->getCode(), $data->getGroupCode(), 0);
                    $result = $this->toggleSwitch($command);
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
        );

        $formTimers->handleRequest($request);

        if ($formTimers->isValid()) {
            foreach ($formTimers['timers'] as $key => $timer) {
                /** @var $timer Form */
                foreach ($timer->getData()->getTimes() as $keyTime => $time) {
                    {
                        /**
                         * @var $timer Form
                         */
                        if ($timer->get('submitTimer' . $keyTime)->isClicked()) {
                            $resultTimer = $this->startTimer(
                                $timer->getData(),
                                $timer->getData()->getTimes()[$keyTime]
                            );
                        }
                    }
                }

            }

            return $this->redirect($this->generateUrl('ron_raspberry_pi_switch'));
        }

        $formBuilder = $this->createFormBuilder();
        $formBuilder->add('submitAllOn', 'submit', array('label' => 'An'));
        $formBuilder->add('submitAllOff', 'submit', array('label' => 'Aus'));
        $formAll = $formBuilder->getForm();

        $formAll->handleRequest($request);

        if ($formAll->get('submitAllOn')->isClicked()) {
            foreach ($switches as $switch) {
                $command = $this->buildCommand($switch->getCode(), $switch->getGroupCode(), 1);
                $result = $this->toggleSwitch($command);
            }

        }

        if ($formAll->get('submitAllOff')->isClicked()) {
            foreach ($switches as $switch) {
                $command = $this->buildCommand($switch->getCode(), $switch->getGroupCode(), 0);
                $result = $this->toggleSwitch($command);
            }

        }

        $viewData = array(
            'form' => $form->createView(),
            'formTimers' => $formTimers->createView(),
            'formAll' => $formAll->createView(),
        );

        $response = $this->render('RonRaspberryPiBundle:Switch:index.html.twig', $viewData);
        $response->setSharedMaxAge(3600);

        return $response;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showFlashMessagesAction()
    {

        return $this->render('RonRaspberryPiBundle:Switch:flash_messages.html.twig');

    }

    /**
     * @param $command
     * @return bool
     */
    protected function toggleSwitch($command)
    {

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
     * @return SwitchEntity[]
     */
    private function buildSwitches()
    {
        $switches = array();
        foreach ($this->container->getParameter('raspi_switch_switches') as $switch) {
            $switches[] = new SwitchEntity(
                $switch['name'],
                $switch['trigger_code'],
                $switch['group_code']
            );
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
                $timer['group_code'],
                $timer['trigger_code'],
                $timer['times'],
                $timer['timeUnit'],
                $timer['trigger_on'],
                $timer['trigger_off']
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
        if ($timerEntity->isOn()) {
            $options = $this->buildCommand($timerEntity->getCode(), $timerEntity->getGroupCode(), 1);
            $this->toggleSwitch($options);
        }

        $client = new AtClient();

        $options = null;
        if ($this->container->hasParameter('ron.command_at')) {
            $options = array('command' => $this->container->getParameter('ron.command_at'));
        }

        $atCommand = $client->createAt($options);
        $atCommand->setTime($time);
        $atCommand->setTimeUnit($timerEntity->getTimeUnit());
        $options = 'sudo /usr/bin/send ' . $timerEntity->getGroupCode() . ' ' . $timerEntity->getCode();
        if ($timerEntity->isOff()) {
            $options .= ' 0';
        }

        $atCommand->setOptions($options);

        $client->process($atCommand);

        if ($client->getProcess()->isSuccessful()) {
            $this->get('session')->getFlashBag()->add(
                'info',
                $timerEntity->getName() . ' wurde gestartet.<br />'
                . $client->getProcess()->getCommandLine()
            );
        } else {
            $this->get('session')->getFlashBag()->add('error', $client->getError() . ' wurde gestartet.');
        }

        return $client;
    }

    /**
     * @param $code
     * @param $groupCode
     * @param $status
     * @return string
     */
    protected function buildCommand($code, $groupCode, $status)
    {
        $command = '';
        $command .= $this->container->getParameter('raspi_switch_command');
        $command .= ' ' . $groupCode;
        $command .= ' ' . $code;
        $command .= ' ' . $status;

        return $command;
    }
} 