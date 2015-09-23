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

class Produce
{
    // {{{ consts
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
     * send message options cache
     *
     * @var array
     * @access private
     */
    private $payload = array();

    /**
     * default the server will not send any response
     *
     * @var float
     * @access private
     */
    private $requiredAck = 0;

    /**
     * default timeout is 100ms
     *
     * @var float
     * @access private
     */
    private $timeout = 100;

    /**
     * produce instance
     *
     * @var \Kafka\Produce
     * @access private
     */
    private static $instance = null;

    /**
     * broker host list
     *
     * @var array
     * @access private
     */
    private $hostList = array();

    /**
     * save broker connection
     *
     * @var array
     * @access private
     */
    private $stream = array();

    // }}}
    // {{{ functions
    // {{{ public function static getInstance()

    /**
     * set send messages
     *
     * @access public
     * @return void
     */
    public static function getInstance($hostList, $timeout, $kafkaHostList = null)
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($hostList, $timeout, $kafkaHostList);
        }

        return self::$instance;
    }

    // }}}
    // {{{ public function __construct()

    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct($hostList, $timeout = null, $kafkaHostList = null)
    {
        if ($hostList instanceof \Kafka\ClusterMetaData) {
            $metadata = $hostList;
        } elseif ( $kafkaHostList !== null ) {
            $metadata = new \Kafka\MetaDataFromKafka($kafkaHostList);
        } else {
            $metadata = new \Kafka\ZooKeeper($hostList, $timeout);
        }
        $this->client = new \Kafka\Client($metadata);
    }

    // }}}
    // {{{ public function setMessages()

    /**
     * set send messages
     *
     * @access public
     * @return void
     */
    public function setMessages($topicName, $partitionId = 0, $messages = array())
    {
        if (isset($this->payload[$topicName][$partitionId])) {
            $this->payload[$topicName][$partitionId] =
                    array_merge($this->payload[$topicName][$partitionId], $messages);
        } else {
            $this->payload[$topicName][$partitionId] = $messages;
        }

        return $this;
    }

    // }}}
    // {{{ public function setRequireAck()

    /**
     * set request mode
     * This field indicates how many acknowledgements the servers should receive
     * before responding to the request. If it is 0 the server will not send any
     * response (this is the only case where the server will not reply to a
     * request). If it is 1, the server will wait the data is written to the
     * local log before sending a response. If it is -1 the server will block
     * until the message is committed by all in sync replicas before sending a
     * response. For any number > 1 the server will block waiting for this
     * number of acknowledgements to occur (but the server will never wait for
     * more acknowledgements than there are in-sync replicas).
     *
     * @param int $ack
     * @access public
     * @return void
     */
    public function setRequireAck($ack = 0)
    {
        if ($ack >= -1) {
            $this->requiredAck = (int) $ack;
        }

        return $this;
    }

    // }}}
    // {{{ public function setTimeOut()

    /**
     * set request timeout
     *
     * @param int $timeout
     * @access public
     * @return void
     */
    public function setTimeOut($timeout = 100)
    {
        if ((int) $timeout) {
            $this->timeout = (int) $timeout;
        }
        return $this;
    }

    // }}}
    // {{{ public function send()

    /**
     * send message to broker
     *
     * @access public
     * @return void
     */
    public function send()
    {
        $data = $this->_formatPayload();
        if (empty($data)) {
            return false;
        }

        $responseData = array();
        foreach ($data as $host => $requestData) {
            $stream = $this->client->getStream($host);
            $conn   = $stream['stream'];
            $encoder = new \Kafka\Protocol\Encoder($conn);
            $encoder->produceRequest($requestData);
            if ((int) $this->requiredAck !== 0) { // get broker response
                $decoder = new \Kafka\Protocol\Decoder($conn);
                $response = $decoder->produceResponse();
                foreach ($response as $topicName => $info) {
                    if (!isset($responseData[$topicName])) {
                        $responseData[$topicName] = $info;
                    } else {
                        $responseData[$topicName] = array_merge($info, $responseData[$topicName]);
                    }
                }
            }

            $this->client->freeStream($stream['key']);
        }

        $this->payload = array();
        return $responseData;
    }

    // }}}
    // {{{ public function getClient()

    /**
     * get client object
     *
     * @access public
     * @return void
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * passthru method to client for setting stream options
     *
     * @access public
     * @param array $options
     */
    public function setStreamOptions($options = array())
    {
        $this->client->setStreamOptions($options);
    }

    // }}}
    // {{{ public function getAvailablePartitions()

    /**
     * get available partition
     *
     * @access public
     * @return array
     */
    public function getAvailablePartitions($topicName)
    {
        $topicDetail = $this->client->getTopicDetail($topicName);
        if (is_array($topicDetail) && isset($topicDetail['partitions'])) {
            $topicPartitiions = array_keys($topicDetail['partitions']);
        } else {
            $topicPartitiions = array();
        }

        return $topicPartitiions;
    }

    // }}}
    // {{{ private function _formatPayload()

    /**
     * format payload array
     *
     * @access private
     * @return array
     */
    private function _formatPayload()
    {
        if (empty($this->payload)) {
            return array();
        }

        $data = array();
        foreach ($this->payload as $topicName => $partitions) {
            foreach ($partitions as $partitionId => $messages) {
                $host = $this->client->getHostByPartition($topicName, $partitionId);
                $data[$host][$topicName][$partitionId] = $messages;
            }
        }

        $requestData = array();
        foreach ($data as $host => $info) {
            $topicData = array();
            foreach ($info as $topicName => $partitions) {
                $partitionData = array();
                foreach ($partitions as $partitionId => $messages) {
                    $partitionData[] = array(
                        'partition_id' => $partitionId,
                        'messages'     => $messages,
                    );
                }
                $topicData[] = array(
                    'topic_name' => $topicName,
                    'partitions' => $partitionData,
                );
            }

            $requestData[$host] = array(
                'required_ack' => $this->requiredAck,
                'timeout'      => $this->timeout,
                'data' => $topicData,
            );
        }

       return $requestData;
    }

    // }}}
    // }}}
}
