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

namespace Kafka\Protocol\Fetch\Helper;

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

class CommitOffset extends HelperAbstract
{
    // {{{ members

    /**
     * consumer group
     *
     * @var string
     * @access protected
     */
    protected $group = '';

    // }}}
    // {{{ functions
    // {{{ public function __construct()

    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    // }}}
    // {{{ public function setGroup()

    /**
     * set consumer group
     *
     * @access public
     * @return void
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    // }}}
    // {{{ public function onStreamEof()

    /**
     * on stream eof call
     *
     * @param string $streamKey
     * @access public
     * @return void
     */
    public function onStreamEof($streamKey)
    {
    }

    // }}}
    // {{{ public function onTopicEof()

    /**
     * on topic eof call
     *
     * @param string $topicName
     * @access public
     * @return void
     */
    public function onTopicEof($topicName)
    {
    }

    // }}}
    // {{{ public function onPartitionEof()

    /**
     * on partition eof call
     *
     * @param \Kafka\Protocol\Fetch\Partition $partition
     * @access public
     * @return void
     */
    public function onPartitionEof($partition)
    {
        $partitionId = $partition->key();
        $topicName = $partition->getTopicName();
        $offset    = $partition->getMessageOffset();
        $offsetObject = new  \Kafka\Offset($this->client, $this->group, $topicName, $partitionId);
        $offsetObject->setOffset($offset);
    }

    // }}}
    // }}}
}
