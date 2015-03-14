<?php
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 28.11.14
 * Time: 07:43
 */

namespace Ron\RaspberryPiBundle\Lib;


class At implements Command
{

    /**
     * @var
     */
    protected $time;
    /**
     * @var string
     */
    protected $timeUnit = 'minutes';
    /**
     * @var string
     */
    protected $offsetDate = 'now';
    /**
     * @var string
     */
    protected $command = 'at';
    /**
     * @var string
     */
    protected $options;

    /**
     * @param array $params
     */
    public function __construct(array $params = null)
    {
        if (isset($params['command'])) {
            $this->setCommand($params['command']);
        }
    }

    /**
     * @param string $offsetDate
     */
    public function setOffsetDate($offsetDate)
    {
        $this->offsetDate = $offsetDate;
    }

    /**
     * @return string
     */
    public function getOffsetDate()
    {
        return $this->offsetDate;
    }

    /**
     * @param mixed $timeunit
     */
    public function setTimeUnit($timeunit)
    {
        $this->timeUnit = $timeunit;
    }

    /**
     * @return mixed
     */
    public function getTimeUnit()
    {
        return $this->timeUnit;
    }

    /**
     * @return string
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @param string $command
     */
    protected function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param string $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Buildes the command string
     * @return string
     */
    public function buildCommand()
    {
        $command = '';
        $command .= 'echo "' . $this->getOptions() . '" ';
        $command .= ' | ';
        $command .= $this->getCommand() . ' ' . $this->getOffsetDate() . ' +' . $this->getTime() .
            $this->getTimeUnit();

        return $command;
    }
} 