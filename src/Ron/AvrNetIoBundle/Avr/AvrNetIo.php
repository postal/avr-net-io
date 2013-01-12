<?php
namespace Ron\AvrNetIoBundle\Avr;
/*
 * AvrNetIo PHP Class von Sascha Kimmel steht unter einer Creative Commons
 * Namensnennung-Weitergabe unter gleichen Bedingungen 3.0 Deutschland Lizenz.
 * http://creativecommons.org/licenses/by-sa/3.0/de/
 *
 * Anleitung und Infos:
 * http://www.sascha-kimmel.de/2010/02/avr-net-io-mit-php-ansteuern/
 *
 */
class AvrNetIo
{

    protected $ip;
    protected $conn;
    protected $timeout = 5;

    protected $lcdInitialized;

    const STATUS_RAW = 1;
    const STATUS_ARRAY_BOOL = 2;
    const STATUS_ARRAY_STRING = 3;

    public function __construct($ip)
    {
        $this->ip = $ip;
    }

    public function connect()
    {
        $this->conn = fsockopen($this->ip, 50290, $errno, $errstr, $this->timeout);
        return (bool)$this->conn;
    }

    public function disconnect()
    {
        return fclose($this->conn);
    }

    protected function read($cmd, $lines)
    {
        fputs($this->conn, trim($cmd) . "\r\n");
        $results = array();
        for ($i = 0; $i < $lines; $i++) {
            $results[] = trim(fgets($this->conn, 65535));
        }
        return $results;
    }

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

    public function getStatus($returnType = self::STATUS_RAW)
    {
        $r = $this->read("GETSTATUS", 1);
        $data = $r[0];

        if ($returnType == self::STATUS_RAW) {
            return $r[0];
        } else {
            $array = array();
            if ($returnType == self::STATUS_ARRAY_BOOL) {
                for ($i = 1; $i < strlen($data); $i++) {
                    $char = substr($data, $i, 1);
                    $array[] = (bool)$char;
                }
            } else if ($returnType == self::STATUS_ARRAY_STRING) {
                for ($i = 1; $i < strlen($data); $i++) {
                    $char = substr($data, $i, 1);
                    $array[] = (int)$char;
                }
            }
        }
        return $array;
    }

    public function getIp()
    {
        $r = $this->read("GETIP", 1);
        return $r[0];
    }

    public function setIp($value)
    {
        $r = $this->read("SETIP " . ($value), 1);
        return $this->resultToBool($r[0]);
    }

    public function getMask()
    {
        $r = $this->read("GETMASK", 1);
        return $r[0];
    }

    public function setMask($value)
    {
        $r = $this->read("SETMASK " . ($value), 1);
        return $this->resultToBool($r[0]);
    }

    public function getGw()
    {
        $r = $this->read("GETGW", 1);
        return $r[0];
    }

    public function setGw($value)
    {
        $r = $this->read("SETGW " . ($value), 1);
        return $this->resultToBool($r[0]);
    }

    public function getPort($number)
    {
        $r = $this->read("GETPORT " . (int)$number, 1);
        return (int)$r[0];
    }

    public function setPort($number, $value)
    {
        if ($value) {
            $value = 1;
        } else {
            $value = 0;
        }
        $r = $this->read("SETPORT " . (int)$number . "." . (int)$value, 1);
        return $this->resultToBool($r[0]);
    }

    public function getAdc($number)
    {
        $r = $this->read("GETADC " . (int)$number, 1);
        return (int)$r[0];
    }

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

    public function writeLcd($line, $text)
    {
        $this->initLcd();
        $r = $this->read("WRITELCD " . (int)$line . "." . $text, 1);
        return $this->resultToBool($r[0]);
    }

    public function clearLcd()
    {
        $this->initLcd();
        $r = $this->read("CLEARLCD", 1);
        return $this->resultToBool($r[0]);
    }

    protected function resultToBool($result)
    {
        return ($result == 'ACK');
    }


}