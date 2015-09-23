<?php

namespace Liuggio\StatsdClient\Factory;

use Liuggio\StatsdClient\Entity\StatsdDataInterface;

Interface StatsdDataFactoryInterface
{

    /**
     * This function creates a 'timing' StatsdData.
     *
     * @abstract
     *
     * @param string|array $key  The metric(s) to set.
     * @param float        $time The elapsed time (ms) to log
     **/
    function timing($key, $time);

    /**
     * This function creates a 'gauge' StatsdData.
     *
     * @abstract
     *
     * @param string|array $key   The metric(s) to set.
     * @param float        $value The value for the stats.
     **/
    function gauge($key, $value);

    /**
     * This function creates a 'set' StatsdData object
     * A "Set" is a count of unique events.
     * This data type acts like a counter, but supports counting
     * of unique occurrences of values between flushes. The backend
     * receives the number of unique events that happened since
     * the last flush.
     *
     * The reference use case involved tracking the number of active
     * and logged in users by sending the current userId of a user
     * with each request with a key of "uniques" (or similar).
     *
     * @abstract
     *
     * @param  string|array $key   The metric(s) to set.
     * @param  float        $value The value for the stats.
     *
     * @return array
     **/
    function set($key, $value);

    /**
     * This function creates a 'increment' StatsdData object.
     *
     * @abstract
     *
     * @param string|array $key        The metric(s) to increment.
     * @param float|1      $sampleRate The rate (0-1) for sampling.
     *
     * @return array
     **/
    function increment($key);

    /**
     * This function creates a 'decrement' StatsdData object.
     *
     * @abstract
     *
     * @param string|array $key        The metric(s) to decrement.
     * @param float|1      $sampleRate The rate (0-1) for sampling.
     *
     * @return mixed
     **/
    function decrement($key);

    /**
     * This function creates a 'updateCount' StatsdData object.
     *
     * @abstract
     *
     * @param string|array $key        The metric(s) to decrement.
     * @param integer      $delta      The delta to add to the each metric
     *
     * @return mixed
     **/
    function updateCount($key, $delta);

    /**
     * Produce a StatsdDataInterface Object.
     *
     * @abstract
     *
     * @param string $key    The key of the metric
     * @param int    $value  The amount to increment/decrement each metric by.
     * @param string $metric The metric type ("c" for count, "ms" for timing, "g" for gauge, "s" for set)
     *
     * @return StatsdDataInterface
     **/
    function produceStatsdData($key, $value = 1, $metric = StatsdDataInterface::STATSD_METRIC_COUNT);
}
