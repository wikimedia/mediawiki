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

class Message
{
    // {{{ members

    /**
     * init read bytes
     *
     * @var float
     * @access private
     */
    private $initOffset = 0;

    /**
     * validByteCount
     *
     * @var float
     * @access private
     */
    private $validByteCount = 0;

    /**
     * crc32 code
     *
     * @var float
     * @access private
     */
    private $crc = 0;

    /**
     * This is a version id used to allow backwards compatible evolution of the
     * message binary format.
     *
     * @var float
     * @access private
     */
    private $magic = 0;

    /**
     * The lowest 2 bits contain the compression codec used for the message. The
     * other bits should be set to 0.
     *
     * @var float
     * @access private
     */
    private $attribute = 0;

    /**
     * message key
     *
     * @var string
     * @access private
     */
    private $key = '';

    /**
     * message value
     *
     * @var string
     * @access private
     */
    private $value = '';

    // }}}
    // {{{ functions
    // {{{ public function __construct()

    /**
     * __construct
     *
     * @param string(raw) $msg
     * @access public
     * @return void
     */
    public function __construct($msg)
    {
        $offset = 0;
        $crc = Decoder::unpack(Decoder::BIT_B32, substr($msg, $offset, 4));
        $offset += 4;
        $this->crc = array_shift($crc);
        $magic = Decoder::unpack(Decoder::BIT_B8, substr($msg, $offset, 1));
        $this->magic = array_shift($magic);
        $offset += 1;
        $attr  = Decoder::unpack(Decoder::BIT_B8, substr($msg, $offset, 1));
        $this->attribute = array_shift($attr);
        $offset += 1;
        $keyLen = Decoder::unpack(Decoder::BIT_B32, substr($msg, $offset, 4));
        $keyLen = array_shift($keyLen);
        $offset += 4;
        if ($keyLen > 0 && $keyLen != 0xFFFFFFFF) {
            $this->key = substr($msg, $offset, $keyLen);
            $offset += $keyLen;
        }
        $messageSize = Decoder::unpack(Decoder::BIT_B32, substr($msg, $offset, 4));
        $messageSize = array_shift($messageSize);
        $offset += 4;
        if ($messageSize) {
            $this->value = substr($msg, $offset, $messageSize);
        }
    }

    // }}}
    // {{{ public function getMessage()

    /**
     * get message data
     *
     * @access public
     * @return string (raw)
     */
    public function getMessage()
    {
        return $this->value;
    }

    // }}}
    // {{{ public function getMessageKey()

    /**
     * get message key
     *
     * @access public
     * @return string (raw)
     */
    public function getMessageKey()
    {
        return $this->key;
    }

    // }}}
    // {{{ public function __toString()

    /**
     * __toString
     *
     * @access public
     * @return void
     */
    public function __toString()
    {
        return $this->value;
    }

    // }}}
    // }}}
}
