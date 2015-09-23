<?php

namespace Liuggio\StatsdClient\Service;

use Liuggio\StatsdClient\Entity\StatsdDataInterface;
use Liuggio\StatsdClient\Factory\StatsdDataFactory;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use Liuggio\StatsdClient\StatsdClient;
use Liuggio\StatsdClient\Entity\StatsdData;

/**
 * Simplifies access to StatsD client and factory, buffers all data.
 */
class StatsdService implements StatsdDataFactoryInterface
{
    /**
     * @var \Liuggio\StatsdClient\StatsdClient
     */
    protected $client;

    /**
     * @var \Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface
     */
    protected $factory;

    /**
     * @var StatsdData[]
     */
    protected $buffer = array();

    /**
     * @var float
     */
    protected $samplingRate;

    private $samplingFunction;

    /**
     * @param StatsdClient               $client
     * @param StatsdDataFactoryInterface $factory
     * @param float                      $samplingRate see setSamplingRate
     */
    public function __construct(
        StatsdClient $client,
        StatsdDataFactoryInterface $factory = null,
        $samplingRate = 1
    ) {
        $this->client = $client;
        if (is_null($factory)) {
            $factory = new StatsdDataFactory();
        }
        $this->factory = $factory;
        $this->setSamplingRate($samplingRate);
    }

    /**
     * Actually defines the sampling rate used by the service.
     * If set to 0.1, the service will automatically discard 10%
     * of the incoming metrics. It will also automatically flag these
     * as sampled data to statsd.
     *
     * @param float $samplingRate
     */
    public function setSamplingRate($samplingRate)
    {
        if ($samplingRate <= 0.0 || 1.0 < $samplingRate) {
            throw new \LogicException('Sampling rate shall be within ]0, 1]');
        }
        $this->samplingRate = $samplingRate;
        $this->samplingFunction = function($min, $max){
            return rand($min, $max);
        };
    }

    /**
     * @return float
     */
    public function getSamplingRate()
    {
        return $this->samplingRate;
    }

    /**
     * {@inheritdoc}
     */
    public function timing($key, $time)
    {
        $this->appendToBuffer(
            $this->factory->timing($key, $time)
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function gauge($key, $value)
    {
        $this->appendToBuffer(
            $this->factory->gauge($key, $value)
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $this->appendToBuffer(
            $this->factory->set($key, $value)
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function increment($key)
    {
        $this->appendToBuffer(
            $this->factory->increment($key)
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function decrement($key)
    {
        $this->appendToBuffer(
            $this->factory->decrement($key)
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function updateCount($key, $delta)
    {
        $this->appendToBuffer(
            $this->factory->updateCount($key, $delta)
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function produceStatsdData($key, $value = 1, $metric = StatsdDataInterface::STATSD_METRIC_COUNT)
    {
        throw new \BadFunctionCallException('produceStatsdData is not implemented');
    }

    /**
     * @param callable $samplingFunction rand() function by default.
     */
    public function setSamplingFunction(\Closure $samplingFunction)
    {
        $this->samplingFunction = $samplingFunction;
    }

    private function appendToBuffer(StatsdData $data)
    {
        if($this->samplingRate < 1){
            $data->setSampleRate($this->samplingRate);
            $result = call_user_func($this->samplingFunction, 0 , floor(1 / $this->samplingRate));
            if ($result == 0) {
                array_push($this->buffer, $data);
            };
        } else {
            array_push($this->buffer, $data);
        }
    }

    /**
     * Send all buffered data to statsd.
     *
     * @return $this
     */
    public function flush()
    {
        $this->client->send($this->buffer);
        $this->buffer = array();

        return $this;
    }
}
