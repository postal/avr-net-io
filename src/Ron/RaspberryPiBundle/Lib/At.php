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

    const COMMAND_NAME = 'at';

    /**
     * @var
     */
    protected $time;
    protected $timeUnit = 'minutes';
    protected $offsetDate = 'now';
    /**
     * @var
     */
    protected $command;

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
     * @param string $command
     */
    public function setCommand($command)
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
        $command .= 'echo "' . $this->getCommand() . '" ';
        $command .= ' | ';
        $command .= self::COMMAND_NAME .' '. $this->getOffsetDate().' +' . $this->getTime() . $this->getTimeUnit();

        return $command;
    }
} 