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
* Cluster metadata provided by kafka
+------------------------------------------------------------------------------
*
* @package
* @version $_SWANBR_VERSION_$
* @copyright Copyleft
* @author ebernhardson@wikimedia.org
+------------------------------------------------------------------------------
*/

class MetaDataFromKafka implements ClusterMetaData
{
    // {{{ consts
    // }}}
    // {{{ members

    /**
     * client
     *
     * @var \Kafka\Client
     * @access private
     */
    private $client;

    /**
     * list of kafka brokers to get metadata from
     *
     * @var array
     * @access private
     */
    private $hostList;

    /**
     * List of all kafka brokers
     *
     * @var array
     * @access private
     */
    private $brokers = array();

    /**
     * List of all loaded topic metadata
     *
     * @var array
     * @access private
     */
    private $topics = array();

    // }}}
    // {{{ functions
    // {{{ public function __construct()

    /**
     * @var string|array $hostList List of kafka brokers to get metadata from
     * @access public
     */
    public function __construct($hostList)
    {
        if (is_string($hostList)) { // support host list 127.0.0.1:9092,192.168.2.11:9092 form
            $this->hostList = explode(',', $hostList);
        } else {
            $this->hostList = (array)$hostList;
        }
        // randomize the order of servers we collect metadata from
        shuffle($this->hostList);
    }

    // }}}
    // {{{ public function setClient()

    /**
     * @var \Kafka\Client $client
     * @access public
     * @return void
     */
    public function setClient(\Kafka\Client $client)
    {
        $this->client = $client;
    }

    // }}}
    // {{{ public function listBrokers()

    /**
     * get broker list from kafka metadata
     *
     * @access public
     * @return array
     */
    public function listBrokers()
    {
        if ($this->brokers === null) {
            $this->loadBrokers();
        }
        return $this->brokers;
    }

    // }}}
    // {{{ public function getPartitionState()

    public function getPartitionState($topicName, $partitionId = 0)
    {
        if (!isset( $this->topics[$topicName] ) ) {
            $this->loadTopicDetail(array($topicName));
        }
        if ( isset( $this->topics[$topicName]['partitions'][$partitionId] ) ) {
            return $this->topics[$topicName]['partitions'][$partitionId];
        } else {
            return null;
        }
    }

    // }}}
    // {{{ public function getTopicDetail()

    /**
     *
     * @param string $topicName
     * @access public
     * @return array
     */
    public function getTopicDetail($topicName)
    {
        if (!isset( $this->topics[$topicName] ) ) {
            $this->loadTopicDetail(array($topicName));
        }
        if (isset( $this->topics[$topicName] ) ) {
            return $this->topics[$topicName];
        } else {
            return array();
        }
    }

    // }}}
    // {{{ private function loadBrokers()

    private function loadBrokers()
    {
        $this->brokers = array();
        // not sure how to ask for only the brokers without a topic...
        // just ask for a topic we don't care about
        $this->loadTopicDetail(array('test'));
    }

    // }}}
    // {{{ private function loadTopicDetail()

    private function loadTopicDetail(array $topics)
    {
        if ($this->client === null) {
            throw new \Kafka\Exception('client was not provided');
        }
        $response = null;
        foreach ($this->hostList as $host) {
            try {
                $response = null;
                $stream = $this->client->getStream($host);
                $conn = $stream['stream'];
                $encoder = new \Kafka\Protocol\Encoder($conn);
                $encoder->metadataRequest($topics);
                $decoder = new \Kafka\Protocol\Decoder($conn);
                $response = $decoder->metadataResponse();
                $this->client->freeStream($stream['key']);
                break;
            } catch (\Kafka\Exception $e) {
                // keep trying
            }
        }
        if ($response) {
            // Merge arrays using "+" operator to preserve key (which are broker IDs)
            // instead of array_merge (which reindex numeric keys)
            $this->brokers = $response['brokers'] + $this->brokers;
            $this->topics = array_merge($response['topics'], $this->topics);
        } else {
            throw new \Kafka\Exception('Could not connect to any kafka brokers');
        }
    }

    // }}}
    // }}}
}
