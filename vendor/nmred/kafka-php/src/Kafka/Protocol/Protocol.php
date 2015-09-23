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

namespace Kafka\Protocol;

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

abstract class Protocol
{
    // {{{ consts

    /**
     *  Kafka server protocol version
     */
    const API_VERSION = 0;

    /**
     * use encode message, This is a version id used to allow backwards
     * compatible evolution of the message binary format.
     */
    const MESSAGE_MAGIC = 0;

    /**
     * message no compression
     */
    const COMPRESSION_NONE = 0;

    /**
     * Message using gzip compression
     */
    const COMPRESSION_GZIP = 1;

    /**
     * Message using Snappy compression
     */
    const COMPRESSION_SNAPPY = 2;

    /**
     *  pack int32 type
     */
    const PACK_INT32 = 0;

    /**
     * pack int16 type
     */
    const PACK_INT16 = 1;

    /**
     * protocol request code
     */
    const PRODUCE_REQUEST = 0;
    const FETCH_REQUEST   = 1;
    const OFFSET_REQUEST  = 2;
    const METADATA_REQUEST      = 3;
    const OFFSET_COMMIT_REQUEST = 8;
    const OFFSET_FETCH_REQUEST  = 9;
    const CONSUMER_METADATA_REQUEST = 10;

    // unpack/pack bit
    const BIT_B64 = 'N2';
    const BIT_B32 = 'N';
    const BIT_B16 = 'n';
    const BIT_B8  = 'C';

    // }}}
    // {{{ members

    /**
     * stream
     *
     * @var mixed
     * @access protected
     */
    protected $stream = null;

    // }}}
    // {{{ functions
    // {{{ public function __construct()

    /**
     * __construct
     *
     * @param \Kafka\Socket $stream
     * @access public
     * @return void
     */
    public function __construct(\Kafka\Socket $stream)
    {
        $this->stream = $stream;
    }

    // }}}
    // {{{ public static function Khex2bin()

    /**
     * hex to bin
     *
     * @param string $string
     * @static
     * @access protected
     * @return string (raw)
     */
    public static function Khex2bin($string)
    {
        if (function_exists('\hex2bin')) {
            return \hex2bin($string);
        } else {
            $bin = '';
            $len = strlen($string);
            for ($i = 0; $i < $len; $i += 2) {
                $bin .= pack('H*', substr($string, $i, 2));
            }

            return $bin;
        }
    }

    // }}}
    // {{{ public static function unpack()

    /**
     * Unpack a bit integer as big endian long
     *
     * @static
     * @access public
     * @return integer
     */
    public static function unpack($type, $bytes)
    {
        self::checkLen($type, $bytes);
        if ($type == self::BIT_B64) {
            $set = unpack($type, $bytes);
            $original = ($set[1] & 0xFFFFFFFF) << 32 | ($set[2] & 0xFFFFFFFF);
            return $original;
        } else {
            return unpack($type, $bytes);
        }
    }

    // }}}
    // {{{ public static function pack()

    /**
     * pack a bit integer as big endian long
     *
     * @static
     * @access public
     * @return integer
     */
    public static function pack($type, $data)
    {
        if ($type == self::BIT_B64) {
            if ($data == -1) { // -1L
                $data = self::Khex2bin('ffffffffffffffff');
            } elseif ($data == -2) { // -2L
                $data = self::Khex2bin('fffffffffffffffe');
            } else {
                $left  = 0xffffffff00000000;
                $right = 0x00000000ffffffff;

                $l = ($data & $left) >> 32;
                $r = $data & $right;
                $data = pack($type, $l, $r);
            }
        } else {
            $data = pack($type, $data);
        }

        return $data;
    }

    // }}}
    // {{{ protected static function checkLen()

    /**
     * check unpack bit is valid
     *
     * @param string $type
     * @param string(raw) $bytes
     * @static
     * @access protected
     * @return void
     */
    protected static function checkLen($type, $bytes)
    {
        $len = 0;
        switch($type) {
            case self::BIT_B64:
                $len = 8;
                break;
            case self::BIT_B32:
                $len = 4;
                break;
            case self::BIT_B16:
                $len = 2;
                break;
            case self::BIT_B8:
                $len = 1;
                break;
        }

        if (strlen($bytes) != $len) {
            throw new \Kafka\Exception\Protocol('unpack failed. string(raw) length is ' . strlen($bytes) . ' , TO ' . $type);
        }
    }

    // }}}
    // }}}
}
