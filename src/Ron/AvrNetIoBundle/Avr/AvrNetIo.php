<?php
namespace Ron\AvrNetIoBundle\Avr;


/**
 *
 * Nach Vorlage von:
 * AvrNetIo PHP Class von Sascha Kimmel steht unter einer Creative Commons
 * Namensnennung-Weitergabe unter gleichen Bedingungen 3.0 Deutschland Lizenz.
 * http://creativecommons.org/licenses/by-sa/3.0/de/
 *
 * Anleitung und Infos:
 * http://www.sascha-kimmel.de/2010/02/avr-net-io-mit-php-ansteuern/
 *
 * @author Ronny Seiler
 */
class AvrNetIo
{

    /**
     * @var string
     */
    protected $connIp;
    /**
     * @var int
     */
    protected $connPort = 50290;

    /**
     * @var
     */
    protected $conn;
    /**
     * @var int
     */
    protected $timeout = 5;

    /**
     * @var
     */
    protected $lcdInitialized;

    /**
     *
     */
    const STATUS_RAW = 1;
    /**
     *
     */
    const STATUS_ARRAY_BOOL = 2;
    /**
     *
     */
    const STATUS_ARRAY_STRING = 3;
    /**
     *
     */
    const PORT_ON = 1;
    /**
     *
     */
    const PORT_OFF = 0;

    /**
     * @param $ip
     */
    public function __construct($ip, $port = null)
    {
        $this->setconnIp($ip);

        if (null != $port && is_int($port)) {
            $this->setConnPort($port);
        }
    }

    /**
     * @param string $connIp
     */
    public function setConnIp($connIp)
    {
        $this->connIp = $connIp;
    }

    /**
     * @return string
     */
    public function getConnIp()
    {
        return $this->connIp;
    }

    /**
     * @param int $connPort
     */
    public function setConnPort($connPort)
    {
        $this->connPort = $connPort;
    }

    /**
     * @return int
     */
    public function getConnPort()
    {
        return $this->connPort;
    }

    /**
     * @return bool
     */
    public function connect()
    {
        $this->conn = @fsockopen($this->getConnIp(), $this->getConnPort(), $errno, $errstr, $this->timeout);

        return (bool)$this->conn;
    }

    /**
     * @return bool
     */
    public function disconnect()
    {
        return fclose($this->conn);
    }

    /**
     * @param $cmd
     * @param $lines
     * @return array
     */
    protected function read($cmd, $lines)
    {
        fputs($this->conn, trim($cmd) . "\r\n");
        $results = array();
        for ($i = 0; $i < $lines; $i++) {
            $results[] = trim(fgets($this->conn, 65535));
        }
        return $results;
    }

    /**
     * @return array
     */
    public function getVersion()
    {
        $info = $this->read("VERSION", 3);
        $data = array();
        foreach ($info as $l) {
            list($n, $v) = explode(":", $l);
            $v = trim($v);
            $data[strtolower($n)] = $v;

        }
        return $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $data = array();
        $boardInfo = $this->getVersion();

        $data['ip'] = $this->getIp();
        $data['gateway'] = $this->getGw();
        $data['netmask'] = $this->getMask();
        $data['controller'] = $boardInfo['uc'];
        $data['firmware'] = $boardInfo['ver'];
        $data['nic'] = $boardInfo['nic'];

        return $data;
    }

    /**
     * @param int $returnType
     * @return array
     */
    public function getStatus($returnType = self::STATUS_RAW)
    {
        $r = $this->read("GETSTATUS", 1);
        $data = $r[0];

        if ($returnType == self::STATUS_RAW) {
            return $r[0];
        } else {
            $array = array();
            for ($i = 1; $i < strlen($data); $i++) {
                $char = substr($data, $i, 1);
                if ($returnType == self::STATUS_ARRAY_BOOL) {
                    $array[] = (bool)$char;
                }
                if ($returnType == self::STATUS_ARRAY_STRING) {
                    $array[] = (int)$char;
                }
            }
        }
        return $array;
    }

    /**
     * @param $number
     * @return int
     */
    public function getInput($number)
    {
        return $this->getPort($number);
    }

    /**
     * @param $number
     * @return bool
     */
    public function getOutput($number)
    {
        $status = $this->getStatus(self::STATUS_ARRAY_STRING);
        $status = array_reverse($status);
        if (array_key_exists($number - 1, $status)) {
            return $status[$number - 1];
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        $r = $this->read("GETIP", 1);
        return $r[0];
    }

    /**
     * @param $value
     * @return bool
     */
    public function setIp($value)
    {
        $r = $this->read("SETIP " . ($value), 1);
        return $this->resultToBool($r[0]);
    }

    /**
     * @return mixed
     */
    public function getMask()
    {
        $r = $this->read("GETMASK", 1);
        return $r[0];
    }

    /**
     * @param $value
     * @return bool
     */
    public function setMask($value)
    {
        $r = $this->read("SETMASK " . ($value), 1);
        return $this->resultToBool($r[0]);
    }

    /**
     * @return mixed
     */
    public function getGw()
    {
        $r = $this->read("GETGW", 1);
        return $r[0];
    }

    /**
     * @param $value
     * @return bool
     */
    public function setGw($value)
    {
        $r = $this->read("SETGW " . ($value), 1);
        return $this->resultToBool($r[0]);
    }

    /**
     * @param $number
     * @return int
     */
    public function getPort($number)
    {
        $r = $this->read("GETPORT " . (int)$number, 1);
        return (int)$r[0];
    }

    /**
     * @param $number
     * @param $value
     * @return bool
     */
    public function setPort($number, $value)
    {
        if ($value) {
            $value = self::PORT_ON;
        } else {
            $value = self::PORT_OFF;
        }
        $r = $this->read("SETPORT " . (int)$number . "." . (int)$value, 1);
        return $this->resultToBool($r[0]);
    }

    /**
     * @param $number
     * @return int
     */
    public function getAdc($number)
    {
        $r = $this->read("GETADC " . (int)$number, 1);
        return (int)$r[0];
    }

    /**
     * @return bool
     */
    public function initLcd()
    {
        if ($this->lcdInitialized) {
            return true; // already initialized
        }
        $r = $this->read("INITLCD", 1);
        $res = $this->resultToBool($r[0]);
        if ($res) {
            $this->lcdInitialized = true;
        }
        return $res;
    }

    /**
     * @param $line
     * @param $text
     * @return bool
     */
    public function writeLcd($line, $text)
    {
        $this->initLcd();
        $r = $this->read("WRITELCD " . (int)$line . "." . $text, 1);
        return $this->resultToBool($r[0]);
    }

    /**
     * @return bool
     */
    public function clearLcd()
    {
        $this->initLcd();
        $r = $this->read("CLEARLCD", 1);
        return $this->resultToBool($r[0]);
    }

    /**
     * @param $result
     * @return bool
     */
    protected function resultToBool($result)
    {
        return ($result == 'ACK');
    }


}