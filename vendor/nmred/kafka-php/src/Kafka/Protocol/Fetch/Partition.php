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

class Partition implements \Iterator, \Countable
{
    // {{{ members

    /**
     * kafka socket object
     *
     * @var mixed
     * @access private
     */
    private $stream = null;

    /**
     * validCount
     *
     * @var float
     * @access private
     */
    private $validCount = 0;

    /**
     * partitions count
     *
     * @var float
     * @access private
     */
    private $partitionCount = false;

    /**
     * current topic
     *
     * @var mixed
     * @access private
     */
    private $current = null;

    /**
     * current iterator key
     * partition id
     *
     * @var string
     * @access private
     */
    private $key = null;

    /**
     * partition errCode
     *
     * @var float
     * @access private
     */
    private $errCode = 0;

    /**
     * partition offset
     *
     * @var float
     * @access private
     */
    private $offset = 0;

    /**
     * partition current fetch offset
     *
     * @var float
     * @access private
     */
    private $currentOffset = 0;

    /**
     * valid
     *
     * @var mixed
     * @access private
     */
    private $valid = false;

    /**
     * cuerrent topic name
     *
     * @var string
     * @access private
     */
    private $topicName = '';

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
     * @param \Kafka\Protocol\Fetch\Topic $topic
     * @param int $initOffset
     * @access public
     * @return void
     */
    public function __construct(\Kafka\Protocol\Fetch\Topic $topic, $context = array())
    {
        $this->stream    = $topic->getStream();
        $this->topicName = $topic->key();
        $this->context   = $context;
        $this->partitionCount = $this->getPartitionCount();
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
        $this->valid = $this->loadNextPartition();
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
        return $this->valid && $this->validCount <= $this->partitionCount;
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
        $this->valid = $this->loadNextPartition();
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
        return $this->partitionCount;
    }

    // }}}
    // {{{ public function getErrCode()

    /**
     * get partition errcode
     *
     * @access public
     * @return void
     */
    public function getErrCode()
    {
        return $this->errCode;
    }

    // }}}
    // {{{ public function getHighOffset()

    /**
     * get partition high offset
     *
     * @access public
     * @return void
     */
    public function getHighOffset()
    {
        return $this->offset;
    }

    // }}}
    // {{{ public function getTopicName()

    /**
     * get partition topic name
     *
     * @access public
     * @return void
     */
    public function getTopicName()
    {
        return $this->topicName;
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
        return $this->stream;
    }

    // }}}
    // {{{ protected function getPartitionCount()

    /**
     * get message size
     * only use to object init
     *
     * @access protected
     * @return integer
     */
    protected function getPartitionCount()
    {
        // read topic count
        $data = $this->stream->read(4, true);
        $data = Decoder::unpack(Decoder::BIT_B32, $data);
        $count = array_shift($data);
        if ($count <= 0) {
            throw new \Kafka\Exception\OutOfRange($size . ' is not a valid partition count');
        }

        return $count;
    }

    // }}}
    // {{{ public function loadNextPartition()

    /**
     * load next partition
     *
     * @access public
     * @return void
     */
    public function loadNextPartition()
    {
        if ($this->validCount >= $this->partitionCount) {
            return false;
        }

        try {
            $partitionId = $this->stream->read(4, true);
            $partitionId = Decoder::unpack(Decoder::BIT_B32, $partitionId);
            $partitionId = array_shift($partitionId);
            \Kafka\Log::log("kafka client:fetch partition:" . $partitionId, LOG_INFO);

            $errCode = $this->stream->read(2, true);
            $errCode = Decoder::unpack(Decoder::BIT_B16, $errCode);
            $this->errCode = array_shift($errCode);
            if ($this->errCode != 0) {
                throw new \Kafka\Exception(\Kafka\Protocol\Decoder::getError($this->errCode));
            }
            $offset = $this->stream->read(8, true);
            $this->offset  = \Kafka\Protocol\Decoder::unpack(Decoder::BIT_B64, $offset);

            $this->key = $partitionId;
            $this->current = new MessageSet($this, $this->context);
        } catch (\Kafka\Exception $e) {
            return false;
        }

        $this->validCount++;
        return true;
    }

    // }}}
    // {{{ public function setMessageOffset()

    /**
     * set messageSet fetch offset current
     *
     * @param  intger $offset
     * @return void
     */
    public function setMessageOffset($offset)
    {
        $this->currentOffset = $offset;
    }

    // }}}
    // {{{ public function getMessageOffset()

    /**
     * get messageSet fetch offset current
     *
     * @return int
     */
    public function getMessageOffset()
    {
        return $this->currentOffset;
    }

    // }}}
    // }}}
}
