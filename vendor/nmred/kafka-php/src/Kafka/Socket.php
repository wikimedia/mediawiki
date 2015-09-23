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

class Socket
{
    // {{{ consts

    const READ_MAX_LEN = 5242880; // read socket max length 5MB

    // }}}
    // {{{ members

    /**
     * Send timeout in seconds.
     *
     * @var float
     * @access private
     */
    private $sendTimeoutSec = 0;

    /**
     * Send timeout in microseconds.
     *
     * @var float
     * @access private
     */
    private $sendTimeoutUsec = 100000;

    /**
     * Recv timeout in seconds
     *
     * @var float
     * @access private
     */
    private $recvTimeoutSec = 0;

    /**
     * Recv timeout in microseconds
     *
     * @var float
     * @access private
     */
    private $recvTimeoutUsec = 750000;

    /**
     * Stream resource
     *
     * @var mixed
     * @access private
     */
    private $stream = null;

    /**
     * Socket host
     *
     * @var mixed
     * @access private
     */
    private $host = null;

    /**
     * Socket port
     *
     * @var mixed
     * @access private
     */
    private $port = -1;

    // }}}
    // {{{ functions
    // {{{ public function __construct()

    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct($host, $port, $recvTimeoutSec = 0, $recvTimeoutUsec = 750000, $sendTimeoutSec = 0, $sendTimeoutUsec = 100000)
    {
        $this->host = $host;
        $this->port = $port;
        $this->setRecvTimeoutSec($recvTimeoutSec);
        $this->setRecvTimeoutUsec($recvTimeoutUsec);
        $this->setSendTimeoutSec($sendTimeoutSec);
        $this->setSendTimeoutUsec($sendTimeoutUsec);
    }

    /**
     * @param float $sendTimeoutSec
     */
    public function setSendTimeoutSec($sendTimeoutSec)
    {
        $this->sendTimeoutSec = $sendTimeoutSec;
    }

    /**
     * @param float $sendTimeoutUsec
     */
    public function setSendTimeoutUsec($sendTimeoutUsec)
    {
        $this->sendTimeoutUsec = $sendTimeoutUsec;
    }

    /**
     * @param float $recvTimeoutSec
     */
    public function setRecvTimeoutSec($recvTimeoutSec)
    {
        $this->recvTimeoutSec = $recvTimeoutSec;
    }

    /**
     * @param float $recvTimeoutUsec
     */
    public function setRecvTimeoutUsec($recvTimeoutUsec)
    {
        $this->recvTimeoutUsec = $recvTimeoutUsec;
    }



    // }}}
    // {{{ public static function createFromStream()

    /**
     * Optional method to set the internal stream handle
     *
     * @static
     * @access public
     * @return void
     */
    public static function createFromStream($stream)
    {
        $socket = new self('localhost', 0);
        $socket->setStream($stream);
        return $socket;
    }

    // }}}
    // {{{ public function setStream()

    /**
     * Optional method to set the internal stream handle
     *
     * @param mixed $stream
     * @access public
     * @return void
     */
    public function setStream($stream)
    {
        $this->stream = $stream;
    }

    // }}}
    // {{{ public function connect()

    /**
     * Connects the socket
     *
     * @access public
     * @return void
     */
    public function connect()
    {
        if (is_resource($this->stream)) {
            return false;
        }

        if (empty($this->host)) {
            throw new \Kafka\Exception('Cannot open null host.');
        }
        if ($this->port <= 0) {
            throw new \Kafka\Exception('Cannot open without port.');
        }

        $this->stream = @fsockopen(
            $this->host,
            $this->port,
            $errno,
            $errstr,
            $this->sendTimeoutSec + ($this->sendTimeoutUsec / 1000000)
        );

        if ($this->stream == false) {
            $error = 'Could not connect to '
                    . $this->host . ':' . $this->port
                    . ' ('.$errstr.' ['.$errno.'])';
            throw new \Kafka\Exception\SocketConnect($error);
        }

        stream_set_blocking($this->stream, 0);
    }

    // }}}
    // {{{ public function close()

    /**
     * close the socket
     *
     * @access public
     * @return void
     */
    public function close()
    {
        if (is_resource($this->stream)) {
            fclose($this->stream);
        }
    }

    // }}}
    // {{{ public function read()

    /**
     * Read from the socket at most $len bytes.
     *
     * This method will not wait for all the requested data, it will return as
     * soon as any data is received.
     *
     * @param integer $len               Maximum number of bytes to read.
     * @param boolean $verifyExactLength Throw an exception if the number of read bytes is less than $len
     *
     * @return string Binary data
     * @throws Kafka_Exception_Socket
     */
    public function read($len, $verifyExactLength = false)
    {
        if ($len > self::READ_MAX_LEN) {
            throw new \Kafka\Exception\SocketEOF('Could not read '.$len.' bytes from stream, length too longer.');
        }

        $null = null;
        $read = array($this->stream);
        $readable = @stream_select($read, $null, $null, $this->recvTimeoutSec, $this->recvTimeoutUsec);
        if ($readable > 0) {
            $remainingBytes = $len;
            $data = $chunk = '';
            while ($remainingBytes > 0) {
                $chunk = fread($this->stream, $remainingBytes);
                if ($chunk === false) {
                    $this->close();
                    throw new \Kafka\Exception\SocketEOF('Could not read '.$len.' bytes from stream (no data)');
                }
                if (strlen($chunk) === 0) {
                    // Zero bytes because of EOF?
                    if (feof($this->stream)) {
                        $this->close();
                        throw new \Kafka\Exception\SocketEOF('Unexpected EOF while reading '.$len.' bytes from stream (no data)');
                    }
                    // Otherwise wait for bytes
                    $readable = @stream_select($read, $null, $null, $this->recvTimeoutSec, $this->recvTimeoutUsec);
                    if ($readable !== 1) {
                        throw new \Kafka\Exception\SocketTimeout('Timed out reading socket while reading ' . $len . ' bytes with ' . $remainingBytes . ' bytes to go');
                    }
                    continue; // attempt another read
                }
                $data .= $chunk;
                $remainingBytes -= strlen($chunk);
            }
            if ($len === $remainingBytes || ($verifyExactLength && $len !== strlen($data))) {
                // couldn't read anything at all OR reached EOF sooner than expected
                $this->close();
                throw new \Kafka\Exception\SocketEOF('Read ' . strlen($data) . ' bytes instead of the requested ' . $len . ' bytes');
            }

            return $data;
        }
        if (false !== $readable) {
            $res = stream_get_meta_data($this->stream);
            if (!empty($res['timed_out'])) {
                $this->close();
                throw new \Kafka\Exception\SocketTimeout('Timed out reading '.$len.' bytes from stream');
            }
        }
        $this->close();
        throw new \Kafka\Exception\SocketEOF('Could not read '.$len.' bytes from stream (not readable)');

    }

    // }}}
    // {{{ public function write()

    /**
     * Write to the socket.
     *
     * @param string $buf The data to write
     *
     * @return integer
     * @throws Kafka_Exception_Socket
     */
    public function write($buf)
    {
        $null = null;
        $write = array($this->stream);

        // fwrite to a socket may be partial, so loop until we
        // are done with the entire buffer
        $written = 0;
        $buflen = strlen($buf);
        while ( $written < $buflen ) {
            // wait for stream to become available for writing
            $writable = stream_select($null, $write, $null, $this->sendTimeoutSec, $this->sendTimeoutUsec);
            if ($writable > 0) {
                // write remaining buffer bytes to stream
                $wrote = fwrite($this->stream, substr($buf, $written));
                if ($wrote === -1 || $wrote === false) {
                    throw new \Kafka\Exception\Socket('Could not write ' . strlen($buf) . ' bytes to stream, completed writing only ' . $written . ' bytes');
                }
                $written += $wrote;
                continue;
            }
            if (false !== $writable) {
                $res = stream_get_meta_data($this->stream);
                if (!empty($res['timed_out'])) {
                    throw new \Kafka\Exception\SocketTimeout('Timed out writing ' . strlen($buf) . ' bytes to stream after writing ' . $written . ' bytes');
                }
            }
            throw new \Kafka\Exception\Socket('Could not write ' . strlen($buf) . ' bytes to stream');
        }
        return $written;
    }

    // }}}
    // {{{ public function rewind()

    /**
     * Rewind the stream
     *
     * @return void
     */
    public function rewind()
    {
        if (is_resource($this->stream)) {
            rewind($this->stream);
        }
    }

    // }}}
    // }}}
}
