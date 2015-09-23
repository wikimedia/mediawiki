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

class FreeStream extends HelperAbstract
{
    // {{{ members

    /**
     * streams
     *
     * @var array
     * @access protected
     */
    protected $streams = array();

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
    // {{{ public function setStreams()

    /**
     * set streams
     *
     * @access public
     * @return void
     */
    public function setStreams($streams)
    {
        $this->streams = $streams;
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
        if (isset($this->streams[$streamKey])) {
            $this->client->freeStream($streamKey);
        }
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
    }

    // }}}
    // }}}
}
