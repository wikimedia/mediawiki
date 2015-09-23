<?php

namespace Liuggio\StatsdClient\Entity;

interface StatsdDataInterface
{
    CONST STATSD_METRIC_TIMING = 'ms';
    CONST STATSD_METRIC_GAUGE  = 'g';
    CONST STATSD_METRIC_SET    = 's';
    CONST STATSD_METRIC_COUNT  = 'c';

    /**
     * @abstract
     * @return string
     */
    function getKey();

    /**
     * @abstract
     * @return mixed
     */
    function getValue();

    /**
     * @abstract
     * @return string
     */
    function getMetric();

    /**
     * @abstract
     * @return string
     */
    function getMessage();

    /**
     * @abstract
     * @return float
     */
    function getSampleRate();

    /**
     * @abstract
     * @return string
     */
    function __toString();
}
