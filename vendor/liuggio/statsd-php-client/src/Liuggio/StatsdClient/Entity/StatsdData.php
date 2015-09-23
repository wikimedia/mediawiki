<?php

namespace Liuggio\StatsdClient\Entity;

use Liuggio\StatsdClient\Entity\StatsdDataInterface;

class StatsdData implements StatsdDataInterface
{

    private $key;
    private $value;
    private $metric;
    private $sampleRate = 1;

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param int $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }


    public function setMetric($metric)
    {
        $this->metric = $metric;
    }

    public function getMetric()
    {
        return $this->metric;
    }

    /**
     * @param float $sampleRate
     */
    public function setSampleRate($sampleRate)
    {
        $this->sampleRate = $sampleRate;
    }

    /**
     * @return float
     */
    public function getSampleRate()
    {
        return $this->sampleRate;
    }

    /**
     * @param bool $withMetric
     *
     * @return string
     */
    public function getMessage($withMetric = true)
    {
        if (!$withMetric) {
            $result = sprintf('%s:%s', $this->getKey(), $this->getValue());
        } else {
            $result = sprintf('%s:%s|%s', $this->getKey(), $this->getValue(), $this->getMetric());
        }

        $sampleRate = $this->getSampleRate();
        if($sampleRate < 1){
            $result.= "|@$sampleRate";
        }

        return $result;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getMessage();
    }
}
