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

abstract class HelperAbstract
{
    // {{{ members
    // }}}
    // {{{ functions
    // {{{ abstract public function onStreamEof()

    /**
     * on stream eof
     *
     * @param string $streamKey
     * @access public
     * @return void
     */
    abstract public function onStreamEof($streamKey);

    // }}}
    // {{{ abstract public function onTopicEof()

    /**
     * on topic eof
     *
     * @param string $topicName
     * @access public
     * @return void
     */
    abstract public function onTopicEof($topicName);

    // }}}
    // {{{ abstract public function onPartitionEof()

    /**
     * on partition eof
     *
     * @param \Kafka\Protocol\Fetch\Partition $partition
     * @access public
     * @return void
     */
    abstract public function onPartitionEof($partition);

    // }}}
    // }}}
}
