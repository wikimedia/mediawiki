<?php

namespace Liuggio\StatsdClient;

use Liuggio\StatsdClient\Sender\SenderInterface;
use Liuggio\StatsdClient\Entity\StatsdDataInterface;
use Liuggio\StatsdClient\Exception\InvalidArgumentException;

Interface StatsdClientInterface
{
    const MAX_UDP_SIZE_STR = 512;

    /*
     * Send the metrics over UDP
     *
     * @abstract
     * @param array|string|StatsdDataInterface  $data message(s) to sent
     * @param int $sampleRate Tells StatsD that this counter is being sent sampled every Xth of the time.
     *
     * @return integer the data sent in bytes
     */
    function send($data, $sampleRate = 1);
}
