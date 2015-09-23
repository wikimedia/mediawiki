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

namespace Kafka\Protocol\Fetch;

use \Kafka\Protocol\Decoder;

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

class Topic implements \Iterator, \Countable
{
    // {{{ members

    /**
     * kafka socket object
     *
     * @var array
     * @access private
     */
    private $streams = array();

    /**
     * each topic count
     *
     * @var array
     * @access private
     */
    private $topicCounts = array();

    /**
     * current iterator stream
     *
     * @var mixed
     * @access private
     */
    private $currentStreamKey = 0;

    /**
     * current lock key
     *
     * @var string
     * @access private
     */
    private $currentStreamLockKey = '';

    /**
     * currentStreamCount
     *
     * @var float
     * @access private
     */
    private $currentStreamCount = 0;

    /**
     * validCount
     *
     * @var float
     * @access private
     */
    private $validCount = 0;

    /**
     * topic count
     *
     * @var float
     * @access private
     */
    private $topicCount = false;

    /**
     * current topic
     *
     * @var mixed
     * @access private
     */
    private $current = null;

    /**
     * current iterator key
     * topic name
     *
     * @var string
     * @access private
     */
    private $key = null;

    /**
     * valid
     *
     * @var mixed
     * @access private
     */
    private $valid = false;

    /**
     * request fetch context
     *
     * @var array
     */
    private $context = array();

    // }}}
    // {{{ functions
    // {{{ public function __construct()

    /**
     * __construct
     *
     * @param \Kafka\Socket $stream
     * @param int $initOffset
     * @access public
     * @return void
     */
    public function __construct($streams, $context = array())
    {
        if (!is_array($streams)) {
            $streams = array($streams);
        }
        $this->streams = $streams;
        $topicInfos = array();
        foreach ($context as $values) {
            if (!isset($values['data'])) {
                continue;
            }

            foreach ($values['data'] as $value) {
                if (!isset($value['topic_name']) || !isset($value['partitions'])) {
                    continue;
                }

                $topicName = $value['topic_name'];
                foreach ($value['partitions'] as $part) {
                    $topicInfos[$topicName][$part['partition_id']] = array(
                        'offset' => $part['offset'],
                    );
                }
            }
        }
        $this->context = $topicInfos;
        $this->topicCount = $this->getTopicCount();
    }

    // }}}
    // {{{ public function current()

    /**
     * current
     *
     * @access public
     * @return void
     */
    public function current()
    {
        return $this->current;
    }

    // }}}
    // {{{ public function key()

    /**
     * key
     *
     * @access public
     * @return void
     */
    public function key()
    {
        return $this->key;
    }

    // }}}
    // {{{ public function rewind()

    /**
     * implements Iterator function
     *
     * @access public
     * @return integer
     */
    public function rewind()
    {
        $this->valid = $this->loadNextTopic();
    }

    // }}}
    // {{{ public function valid()

    /**
     * implements Iterator function
     *
     * @access public
     * @return integer
     */
    public function valid()
    {
        return $this->valid;
    }

    // }}}
    // {{{ public function next()

    /**
     * implements Iterator function
     *
     * @access public
     * @return integer
     */
    public function next()
    {
        $this->valid = $this->loadNextTopic();
    }

    // }}}
    // {{{ public function count()

    /**
     * implements Countable function
     *
     * @access public
     * @return integer
     */
    public function count()
    {
        return $this->topicCount;
    }

    // }}}
    // {{{ protected function getTopicCount()

    /**
     * get message size
     * only use to object init
     *
     * @access protected
     * @return integer
     */
    protected function getTopicCount()
    {
        $count = 0;
        foreach (array_values($this->streams) as $key => $stream) {
            // read topic count
            $stream->read(8, true);
            $data = $stream->read(4, true);
            $data = Decoder::unpack(Decoder::BIT_B32, $data);
            $topicCount = array_shift($data);
            $count += $topicCount;
            $this->topicCounts[$key] = $topicCount;
            if ($count <= 0) {
                throw new \Kafka\Exception\OutOfRange($count . ' is not a valid topic count');
            }
        }

        return $count;
    }

    // }}}
    // {{{ public function loadNextTopic()

    /**
     * load next topic
     *
     * @access public
     * @return void
     */
    public function loadNextTopic()
    {
        if ($this->validCount >= $this->topicCount) {
            \Kafka\Protocol\Fetch\Helper\Helper::onStreamEof($this->currentStreamLockKey);
            return false;
        }

        if ($this->currentStreamCount >= $this->topicCounts[$this->currentStreamKey]) {
            \Kafka\Protocol\Fetch\Helper\Helper::onStreamEof($this->currentStreamLockKey);
            $this->currentStreamKey++;
        }

        $lockKeys = array_keys($this->streams);
        $streams  = array_values($this->streams);
        if (!isset($streams[$this->currentStreamKey])) {
            return false;
        }

        $stream = $streams[$this->currentStreamKey];
        $this->currentStreamLockKey = $lockKeys[$this->currentStreamKey];

        try {
            $topicLen = $stream->read(2, true);
            $topicLen = Decoder::unpack(Decoder::BIT_B16, $topicLen);
            $topicLen = array_shift($topicLen);
            if ($topicLen <= 0) {
                return false;
            }

            // topic name
            $this->key = $stream->read($topicLen, true);
            $this->current = new Partition($this, $this->context);
        } catch (\Kafka\Exception $e) {
            return false;
        }

        $this->validCount++;
        $this->currentStreamCount++;

        return true;
    }

    // }}}
    // {{{ public function getStream()

    /**
     * get current stream
     *
     * @access public
     * @return \Kafka\Socket
     */
    public function getStream()
    {
        $streams = array_values($this->streams);
        return $streams[$this->currentStreamKey];
    }

    // }}}
    // }}}
}
