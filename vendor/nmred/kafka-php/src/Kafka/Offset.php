<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
// +---------------------------------------------------------------------------
// | SWAN [ $_SWANBR_SLOGAN_$ ]
// +---------------------------------------------------------------------------
// | Copyright $_SWANBR_COPYRIGHT_$
// +---------------------------------------------------------------------------
// | Version  $_SWANBR_VERSION_$
// +---------------------------------------------------------------------------
// | Licensed ( $_SWANBR_LICENSED_URL_$ )
// +---------------------------------------------------------------------------
// | $_SWANBR_WEB_DOMAIN_$
// +---------------------------------------------------------------------------

namespace Kafka;

use \Kafka\Log;

/**
+------------------------------------------------------------------------------
* Kafka protocol since Kafka v0.8
+------------------------------------------------------------------------------
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright Copyleft
* @author $_SWANBR_AUTHOR_$
+------------------------------------------------------------------------------
*/

class Offset
{
    // {{{ consts

    /**
     * receive the latest offset
     */
    const LAST_OFFSET = -1;

    /**
     *   receive the earliest available offset.
     */
    const EARLIEST_OFFSET = -2;

    /**
     * function getOffset if read invalid value use latest offset instead of
     */
    const DEFAULT_LAST  = -2;

    /**
     * function getOffset if read invalid value use earliest offset instead of
     */
    const DEFAULT_EARLY = -1;

    // }}}
    // {{{ members

    /**
     * client
     *
     * @var mixed
     * @access private
     */
    private $client = null;

    /**
     * consumer group
     *
     * @var string
     * @access private
     */
    private $groupId = '';

    /**
     * topic name
     *
     * @var string
     * @access private
     */
    private $topicName = '';

    /**
     * topic partition id, default 0
     *
     * @var float
     * @access private
     */
    private $partitionId = 0;

    /**
     * encoder
     *
     * @var mixed
     * @access private
     */
    private $encoder = null;

    /**
     * decoder
     *
     * @var mixed
     * @access private
     */
    private $decoder = null;

    /**
     * streamKey
     *
     * @var string
     * @access private
     */
    private $streamKey = '';

    // }}}
    // {{{ functions
    // {{{ public function __construct()

    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct($client, $groupId, $topicName, $partitionId = 0)
    {
        $this->client      = $client;
        $this->groupId     = $groupId;
        $this->topicName   = $topicName;
        $this->partitionId = $partitionId;

        $host   = $this->client->getHostByPartition($topicName, $partitionId);
        $stream = $this->client->getStream($host);
        $conn   = $stream['stream'];
        $this->streamKey = $stream['key'];
        $this->encoder = new \Kafka\Protocol\Encoder($conn);
        $this->decoder = new \Kafka\Protocol\Decoder($conn);
    }

    // }}}
    // {{{ public function setOffset()

    /**
     * set consumer offset
     *
     * @param integer $offset
     * @access public
     * @return void
     */
    public function setOffset($offset)
    {
        $maxOffset = $this->getProduceOffset();
        if ($offset > $maxOffset) {
            throw new \Kafka\Exception('this offset is invalid. must less than max offset:' . $maxOffset);
        }

        $data = array(
            'group_id' => $this->groupId,
            'data' => array(
                array(
                    'topic_name' => $this->topicName,
                    'partitions' => array(
                        array(
                            'partition_id' => $this->partitionId,
                            'offset' => $offset,
                            ),
                        ),
                ),
            ),
        );

        $topicName = $this->topicName;
        $partitionId = $this->partitionId;

        $this->encoder->commitOffsetRequest($data);
        $result = $this->decoder->commitOffsetResponse();
        $this->client->freeStream($this->streamKey);
        if (!isset($result[$topicName][$partitionId]['errCode'])) {
            throw new \Kafka\Exception('commit topic offset failed.');
        }
        if ($result[$topicName][$partitionId]['errCode'] != 0) {
            throw new \Kafka\Exception(\Kafka\Protocol\Decoder::getError($result[$topicName][$partitionId]['errCode']));
        }
    }

    // }}}
    // {{{ public function getOffset()

    /**
     * get consumer offset
     *
     * @param integer $defaultOffset
     *   if defaultOffset -1 instead of early offset
     *   if defaultOffset -2 instead of last offset
     * @access public
     * @return void
     */
    public function getOffset($defaultOffset = self::DEFAULT_LAST)
    {
        $maxOffset = $this->getProduceOffset(self::LAST_OFFSET);
        $minOffset = $this->getProduceOffset(self::EARLIEST_OFFSET);
        $data = array(
            'group_id' => $this->groupId,
            'data' => array(
                array(
                    'topic_name' => $this->topicName,
                    'partitions' => array(
                        array(
                            'partition_id' => $this->partitionId,
                        ),
                    ),
                ),
            ),
        );

        $this->encoder->fetchOffsetRequest($data);
        $result = $this->decoder->fetchOffsetResponse();
        $this->client->freeStream($this->streamKey);

        $topicName = $this->topicName;
        $partitionId = $this->partitionId;
        if (!isset($result[$topicName][$partitionId]['errCode'])) {
            throw new \Kafka\Exception('fetch topic offset failed.');
        }
        if ($result[$topicName][$partitionId]['errCode'] == 3) {
            switch ($defaultOffset) {
                case self::DEFAULT_LAST:
                    return $maxOffset;
                    Log::log("topic name: $topicName, partitionId: $partitionId, get offset value is default last.", LOG_INFO);
                case self::DEFAULT_EARLY:
                    Log::log("topic name: $topicName, partitionId: $partitionId, get offset value is default early.", LOG_INFO);
                    return $minOffset;
                default:
                    $this->setOffset($defaultOffset);
                    Log::log("topic name: $topicName, partitionId: $partitionId, get offset value is default $defaultOffset.", LOG_INFO);
                    return $defaultOffset;
            }
            if ($defaultOffset) {
                $this->setOffset($defaultOffset);
                return $defaultOffset;
            }
        } elseif ($result[$topicName][$partitionId]['errCode'] == 0) {
            $offset = $result[$topicName][$partitionId]['offset'];
            if ($offset > $maxOffset || $offset < $minOffset) {
                if ($defaultOffset == self::DEFAULT_EARLY) {
                    $offset = $minOffset;
                } else {
                    $offset = $maxOffset;
                }
            }
            Log::log("topic name: $topicName, partitionId: $partitionId, get offset value is $offset.", LOG_INFO);

            return $offset;
        } else {
            throw new \Kafka\Exception(\Kafka\Protocol\Decoder::getError($result[$topicName][$partitionId]['errCode']));
        }
    }

    // }}}
    // {{{ public function getProduceOffset()

    /**
     * get produce server offset
     *
     * @param string $topicName
     * @param integer $partitionId
     * @access public
     * @return int
     */
    public function getProduceOffset($timeLine = self::LAST_OFFSET)
    {
        $topicName = $this->topicName;
        $partitionId = $this->partitionId;

        $requestData = array(
            'data' => array(
                array(
                    'topic_name' => $this->topicName,
                    'partitions' => array(
                        array(
                            'partition_id' => $this->partitionId,
                            'time' => $timeLine,
                            'max_offset' => 1,
                        ),
                    ),
                ),
            ),
        );
        $this->encoder->offsetRequest($requestData);
        $result = $this->decoder->offsetResponse();
        $this->client->freeStream($this->streamKey);

        if (!isset($result[$topicName][$partitionId]['offset'])) {
            if (isset($result[$topicName][$partitionId]['errCode'])) {
                throw new \Kafka\Exception(\Kafka\Protocol\Decoder::getError($result[$topicName][$partitionId]['errCode']));
            } else {
                throw new \Kafka\Exception('get offset failed. topic name:' . $this->topicName . ' partitionId: ' . $this->partitionId);
            }
        }

        return array_shift($result[$topicName][$partitionId]['offset']);
    }

    // }}}
    // }}}
}
