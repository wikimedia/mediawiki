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

class Log
{
    // {{{ consts
    // }}}
    // {{{ members

    /**
     * log
     *
     * @var mixed
     * @access private
     */
    private static $log = null;

    // }}}
    // {{{ functions
    // {{{ public static function setLog()

    /**
     * setLog
     *
     * @access public
     * @return void
     */
    public static function setLog($log)
    {
        if ($log) {
            self::$log = $log;
        }
    }

    // }}}
    // {{{ public static function log()

    /**
     * log
     *
     * @access public
     * @return void
     */
    public static function log($message, $level = LOG_DEBUG)
    {
        if (self::$log && method_exists(self::$log, 'log')) {
            self::$log->log($message, $level);
        }
    }

    // }}}
    // }}}
}
