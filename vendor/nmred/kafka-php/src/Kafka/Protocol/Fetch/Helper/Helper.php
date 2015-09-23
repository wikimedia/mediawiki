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

class Helper
{
    // {{{ members

    /**
     * helper object
     */
    private static $helpers = array();

    // }}}
    // {{{ functions
    // {{{ public staitc function registerHelper()

    /**
     * register helper
     *
     * @param string $key
     * @param \Kafka\Protocol\Fetch\Helper\HelperAbstract $helper
     * @static
     * @access public
     * @return void
     */
    public static function registerHelper($key, $helper = null)
    {
        if (is_null($helper)) {
            $className = '\\Kafka\\Protocol\\Fetch\\Helper\\' . $key;
            if (!class_exists($className)) {
                throw new \Kafka\Exception('helper is not exists.');
            }
            $helper = new $className();
        }

        if ($helper instanceof \Kafka\Protocol\Fetch\Helper\HelperAbstract) {
            self::$helpers[$key] = $helper;
        } else {
            throw new \Kafka\Exception('this helper not instance of `\Kafka\Protocol\Fetch\Helper\HelperAbstract`');
        }
    }

    // }}}
    // {{{ public staitc function unRegisterHelper()

    /**
     * unregister helper
     *
     * @param string $key
     * @static
     * @access public
     * @return void
     */
    public static function unRegisterHelper($key)
    {
        if (isset(self::$helpers[$key])) {
            unset(self::$helpers[$key]);
        }
    }

    // }}}
    // {{{ public static function onStreamEof()

    /**
     * on stream eof call
     *
     * @param string $streamKey
     * @static
     * @access public
     * @return void
     */
    public static function onStreamEof($streamKey)
    {
        if (empty(self::$helpers)) {
            return false;
        }

        foreach (self::$helpers as $key => $helper) {
            if (method_exists($helper, 'onStreamEof')) {
                $helper->onStreamEof($streamKey);
            }
        }
    }

    // }}}
    // {{{ public static function onTopicEof()

    /**
     * on topic eof call
     *
     * @param string $topicName
     * @static
     * @access public
     * @return void
     */
    public static function onTopicEof($topicName)
    {
        if (empty(self::$helpers)) {
            return false;
        }

        foreach (self::$helpers as $key => $helper) {
            if (method_exists($helper, 'onTopicEof')) {
                $helper->onStreamEof($topicName);
            }
        }
    }

    // }}}
    // {{{ public static function onPartitionEof()

    /**
     * on partition eof call
     *
     * @param \Kafka\Protocol\Fetch\Partition $partition
     * @static
     * @access public
     * @return void
     */
    public static function onPartitionEof($partition)
    {
        if (empty(self::$helpers)) {
            return false;
        }

        foreach (self::$helpers as $key => $helper) {
            if (method_exists($helper, 'onPartitionEof')) {
                $helper->onPartitionEof($partition);
            }
        }
    }

    // }}}
    // }}}
}
