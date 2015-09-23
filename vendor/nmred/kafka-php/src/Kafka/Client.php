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

class Client
{
    // {{{ consts
    // }}}
    // {{{ members

    /**
     * cluster metadata
     *
     * @var \Kafka\ClusterMetaData
     * @access private
     */
    private $metadata = null;

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
    private static $stream = array();

    /**
     * default stream options
     *
     * @var array
     * @access private
     */
    private $streamOptions = array(
        'RecvTimeoutSec' => 0,
        'RecvTimeoutUsec' => 750000,
        'SendTimeoutSec' => 0,
        'SendTimeoutUsec' => 100000,
    );

    // }}}
    // {{{ functions
    // {{{ public function __construct()

    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct(ClusterMetaData $metadata)
    {
        $this->metadata = $metadata;
        if (method_exists($metadata, 'setClient')) {
            $this->metadata->setClient($this);
        }
    }

    /**
     * update stream options
     *
     * @param array $options
     */
    public function setStreamOptions($options = array())
    {
        // Merge the arrays
        $this->streamOptions = array_merge($this->streamOptions, $options);
        $this->updateStreamOptions();
    }

    /**
     * @access public
     * @param $name - name of stream option
     * @param $value - value for option
     */
    public function setStreamOption($name, $value)
    {
        $this->streamOptions[$name] = $value;
        $this->updateStreamOptions();
    }

    /**
     * @access public
     * @param $name - name of option
     * @return mixed
     */
    public function getStreamOption($name)
    {
        if (array_key_exists($name, $this->streamOptions)) {
            return $this->streamOptions[$name];
        }
        return null;
    }

    /**
     * @access private
     */
    private function updateStreamOptions()
    {
        // Loop thru each stream
        foreach (self::$stream as $host => $streams) {
            foreach ($streams as $key => $info) {
                // Update options
                if (isset($info['stream'])) {
                    /** @var \Kafka\Socket $stream */
                    $stream = $info['stream'];
                    $stream->setRecvTimeoutSec($this->streamOptions['RecvTimeoutSec']);
                    $stream->setRecvTimeoutUsec($this->streamOptions['SendTimeoutUsec']);
                    $stream->setSendTimeoutSec($this->streamOptions['SendTimeoutSec']);
                    $stream->setSendTimeoutUsec($this->streamOptions['SendTimeoutUsec']);
                }
            }
        }
    }

    // }}}
    // {{{ public function getBrokers()

    /**
     * get broker server
     *
     * @access public
     * @return void
     */
    public function getBrokers()
    {
        if (empty($this->hostList)) {
            $brokerList = $this->metadata->listBrokers();
            foreach ($brokerList as $brokerId => $info) {
                if (!isset($info['host']) || !isset($info['port'])) {
                    continue;
                }
                $this->hostList[$brokerId] = $info['host'] . ':' . $info['port'];
            }
        }

        return $this->hostList;
    }

    // }}}
    // {{{ public function getHostByPartition()

    /**
     * get broker host by topic partition
     *
     * @param string $topicName
     * @param int $partitionId
     * @access public
     * @return string
     */
    public function getHostByPartition($topicName, $partitionId = 0)
    {
        $partitionInfo = $this->metadata->getPartitionState($topicName, $partitionId);
        if (!$partitionInfo) {
            throw new \Kafka\Exception('topic:' . $topicName . ', partition id: ' . $partitionId . ' is not exists.');
        }

        $hostList = $this->getBrokers();
        if (isset($partitionInfo['leader']) && isset($hostList[$partitionInfo['leader']])) {
            return $hostList[$partitionInfo['leader']];
        } else {
            throw new \Kafka\Exception('can\'t find broker host.');
        }
    }

    // }}}
    // {{{ public function getZooKeeper()

    /**
     * get kafka zookeeper object
     *
     * @access public
     * @return \Kafka\ZooKeeper
     */
    public function getZooKeeper()
    {
        if ($this->metadata instanceof \Kafka\ZooKeeper) {
                return $this->metadata;
        } else {
                throw new \Kafka\Exception( 'ZooKeeper was not provided' );
        }
    }

    // }}}
    // {{{ public function getStream()

    /**
     * get broker broker connect
     *
     * @param string $host
     * @access private
     * @return void
     */
    public function getStream($host, $lockKey = null)
    {
        if (!$lockKey) {
            $lockKey = uniqid($host);
        }

        list($hostname, $port) = explode(':', $host);
        // find unlock stream
        if (isset(self::$stream[$host])) {
            foreach (self::$stream[$host] as $key => $info) {
                if ($info['locked']) {
                    continue;
                } else {
                    self::$stream[$host][$key]['locked'] = true;
                    $info['stream']->connect();
                    return array('key' => $key, 'stream' => $info['stream']);
                }
            }
        }

        // no idle stream
        $stream = new \Kafka\Socket($hostname, $port, $this->getStreamOption('RecvTimeoutSec'), $this->getStreamOption('RecvTimeoutUsec'), $this->getStreamOption('SendTimeoutSec'), $this->getStreamOption('SendTimeoutUsec'));
        $stream->connect();
        self::$stream[$host][$lockKey] = array(
            'locked' => true,
            'stream' => $stream,
        );
        return array('key' => $lockKey, 'stream' => $stream);
    }

    // }}}
    // {{{ public function freeStream()

    /**
     * free stream pool
     *
     * @param string $key
     * @access public
     * @return void
     */
    public function freeStream($key)
    {
        foreach (self::$stream as $host => $values) {
            if (isset($values[$key])) {
                self::$stream[$host][$key]['locked'] = false;
            }
        }
    }

    // }}}
    // {{{ public function getTopicDetail()

    /**
     * get topic detail info
     *
     * @param  string $topicName
     * @return array
     */
    public function getTopicDetail($topicName)
    {
        return $this->metadata->getTopicDetail($topicName);
    }

    // }}}
    // }}}
}
