<?php

namespace Liuggio\StatsdClient\Factory;

use Liuggio\StatsdClient\Entity\StatsdDataInterface;

class StatsdDataFactory implements StatsdDataFactoryInterface
{
    /**
     * @var StatsdDataInterface
     */
    private $entityClass;

    public function __construct($entity_class = '\Liuggio\StatsdClient\Entity\StatsdData')
    {
        $this->setEntityClass($entity_class);
    }

    /**
     * {@inheritDoc}
     **/
    public function timing($key, $time)
    {
        return $this->produceStatsdData($key, $time, StatsdDataInterface::STATSD_METRIC_TIMING);
    }

    /**
     * {@inheritDoc}
     **/
    public function gauge($key, $value)
    {
        return $this->produceStatsdData($key, $value, StatsdDataInterface::STATSD_METRIC_GAUGE);
    }

    /**
     * {@inheritDoc}
     **/
    public function set($key, $value)
    {
        return $this->produceStatsdData($key, $value, StatsdDataInterface::STATSD_METRIC_SET);
    }

    /**
     * {@inheritDoc}
     **/
    public function increment($key)
    {
        return $this->produceStatsdData($key, 1, StatsdDataInterface::STATSD_METRIC_COUNT);
    }

    /**
     * {@inheritDoc}
     **/
    public function decrement($key)
    {
        return $this->produceStatsdData($key, -1, StatsdDataInterface::STATSD_METRIC_COUNT);
    }

    /**
     * {@inheritDoc}
     **/
    public function updateCount($key, $delta)
    {
        return $this->produceStatsdData($key, $delta, StatsdDataInterface::STATSD_METRIC_COUNT);
    }

    /**
     * {@inheritDoc}
     **/
    public function produceStatsdData($key, $value = 1, $metric = StatsdDataInterface::STATSD_METRIC_COUNT)
    {
        $statsdData = $this->produceStatsdDataEntity();

        if (null !== $key) {
            $statsdData->setKey($key);
        }

        if (null !== $value) {
            $statsdData->setValue($value);
        }

        if (null !== $metric) {
            $statsdData->setMetric($metric);
        }

        return $statsdData;
    }

    /**
     * {@inheritDoc}
     **/
    public function produceStatsdDataEntity()
    {
        $statsdData = $this->getEntityClass();

        return new $statsdData();
    }

    /**
     * {@inheritDoc}
     **/
    public function setFailSilently($failSilently)
    {
        $this->failSilently = $failSilently;
    }

    /**
     * {@inheritDoc}
     **/
    public function getFailSilently()
    {
        return $this->failSilently;
    }

    /**
     * {@inheritDoc}
     **/
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
    }

    /**
     * {@inheritDoc}
     **/
    public function getEntityClass()
    {
        return $this->entityClass;
    }
}
