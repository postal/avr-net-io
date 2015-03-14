<?php
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 28.11.14
 * Time: 07:35
 */

namespace Ron\RaspberryPiBundle\Lib;


use Symfony\Component\Process\Process;

class AtClient
{

    protected $process;
    protected $error;
    protected $output;

    /**
     * @param Process $process
     */
    public function setProcess($process)
    {
        $this->process = $process;
    }

    /**
     * @return Process
     */
    public function getProcess()
    {
        return $this->process;
    }


    /**
     * @param mixed $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param mixed $output
     */
    public function setOutput($output)
    {
        $this->output = $output;
    }

    /**
     * @return mixed
     */
    public function getOutput()
    {
        return $this->output;
    }


    /**
     * @param Command $command
     * @return Process
     */
    public function process(Command $command)
    {
        $this->process = new Process($command->buildCommand());

        $output = $this->output;
        $this->process->run(
            function ($type, $buffer) use (&$error, &$output) {
                if (Process::ERR === $type) {
                    $error .= 'ERR > ' . $buffer;
                } else {
                    $output .= 'OUT > ' . $buffer;
                }
            }
        );

        $this->error = $error;
        $this->output = $output;

        return $this->process;
    }

    /**
     * @return At
     */
    public function createAt($params = array())
    {
        return new At($params);
    }


    public function createBatch()
    {
        return null;
    }
} 