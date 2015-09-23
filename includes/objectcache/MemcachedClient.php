<?php
// @codingStandardsIgnoreFile It's an external lib and it isn't. Let's not bother.
/**
 * Memcached client for PHP.
 *
 * +---------------------------------------------------------------------------+
 * | memcached client, PHP                                                     |
 * +---------------------------------------------------------------------------+
 * | Copyright (c) 2003 Ryan T. Dean <rtdean@cytherianage.net>                 |
 * | All rights reserved.                                                      |
 * |                                                                           |
 * | Redistribution and use in source and binary forms, with or without        |
 * | modification, are permitted provided that the following conditions        |
 * | are met:                                                                  |
 * |                                                                           |
 * | 1. Redistributions of source code must retain the above copyright         |
 * |    notice, this list of conditions and the following disclaimer.          |
 * | 2. Redistributions in binary form must reproduce the above copyright      |
 * |    notice, this list of conditions and the following disclaimer in the    |
 * |    documentation and/or other materials provided with the distribution.   |
 * |                                                                           |
 * | THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR      |
 * | IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES |
 * | OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.   |
 * | IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,          |
 * | INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT  |
 * | NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, |
 * | DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY     |
 * | THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT       |
 * | (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF  |
 * | THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.         |
 * +---------------------------------------------------------------------------+
 * | Author: Ryan T. Dean <rtdean@cytherianage.net>                            |
 * | Heavily influenced by the Perl memcached client by Brad Fitzpatrick.      |
 * |   Permission granted by Brad Fitzpatrick for relicense of ported Perl     |
 * |   client logic under 2-clause BSD license.                                |
 * +---------------------------------------------------------------------------+
 *
 * @file
 * $TCAnet$
 */

/**
 * This is the PHP client for memcached - a distributed memory cache daemon.
 * More information is available at http://www.danga.com/memcached/
 *
 * Usage example:
 *
 * require_once 'memcached.php';
 *
 * $mc = new MWMemcached(array(
 *              'servers' => array('127.0.0.1:10000',
 *                                 array('192.0.0.1:10010', 2),
 *                                 '127.0.0.1:10020'),
 *              'debug'   => false,
 *              'compress_threshold' => 10240,
 *              'persistent' => true));
 *
 * $mc->add( 'key', array( 'some', 'array' ) );
 * $mc->replace( 'key', 'some random string' );
 * $val = $mc->get( 'key' );
 *
 * @author  Ryan T. Dean <rtdean@cytherianage.net>
 * @version 0.1.2
 */

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

// {{{ requirements
// }}}

// {{{ class MWMemcached
/**
 * memcached client class implemented using (p)fsockopen()
 *
 * @author  Ryan T. Dean <rtdean@cytherianage.net>
 * @ingroup Cache
 */
class MWMemcached {
	// {{{ properties
	// {{{ public

	// {{{ constants
	// {{{ flags

	/**
	 * Flag: indicates data is serialized
	 */
	const SERIALIZED = 1;

	/**
	 * Flag: indicates data is compressed
	 */
	const COMPRESSED = 2;

	/**
	 * Flag: indicates data is an integer
	 */
	const INTVAL = 4;

	// }}}

	/**
	 * Minimum savings to store data compressed
	 */
	const COMPRESSION_SAVINGS = 0.20;

	// }}}

	/**
	 * Command statistics
	 *
	 * @var array
	 * @access public
	 */
	public $stats;

	// }}}
	// {{{ private

	/**
	 * Cached Sockets that are connected
	 *
	 * @var array
	 * @access private
	 */
	public $_cache_sock;

	/**
	 * Current debug status; 0 - none to 9 - profiling
	 *
	 * @var bool
	 * @access private
	 */
	public $_debug;

	/**
	 * Dead hosts, assoc array, 'host'=>'unixtime when ok to check again'
	 *
	 * @var array
	 * @access private
	 */
	public $_host_dead;

	/**
	 * Is compression available?
	 *
	 * @var bool
	 * @access private
	 */
	public $_have_zlib;

	/**
	 * Do we want to use compression?
	 *
	 * @var bool
	 * @access private
	 */
	public $_compress_enable;

	/**
	 * At how many bytes should we compress?
	 *
	 * @var int
	 * @access private
	 */
	public $_compress_threshold;

	/**
	 * Are we using persistent links?
	 *
	 * @var bool
	 * @access private
	 */
	public $_persistent;

	/**
	 * If only using one server; contains ip:port to connect to
	 *
	 * @var string
	 * @access private
	 */
	public $_single_sock;

	/**
	 * Array containing ip:port or array(ip:port, weight)
	 *
	 * @var array
	 * @access private
	 */
	public $_servers;

	/**
	 * Our bit buckets
	 *
	 * @var array
	 * @access private
	 */
	public $_buckets;

	/**
	 * Total # of bit buckets we have
	 *
	 * @var int
	 * @access private
	 */
	public $_bucketcount;

	/**
	 * # of total servers we have
	 *
	 * @var int
	 * @access private
	 */
	public $_active;

	/**
	 * Stream timeout in seconds. Applies for example to fread()
	 *
	 * @var int
	 * @access private
	 */
	public $_timeout_seconds;

	/**
	 * Stream timeout in microseconds
	 *
	 * @var int
	 * @access private
	 */
	public $_timeout_microseconds;

	/**
	 * Connect timeout in seconds
	 */
	public $_connect_timeout;

	/**
	 * Number of connection attempts for each server
	 */
	public $_connect_attempts;

	/**
	 * @var LoggerInterface
	 */
	private $_logger;

	// }}}
	// }}}
	// {{{ methods
	// {{{ public functions
	// {{{ memcached()

	/**
	 * Memcache initializer
	 *
	 * @param array $args Associative array of settings
	 *
	 * @return mixed
	 */
	public function __construct( $args ) {
		$this->set_servers( isset( $args['servers'] ) ? $args['servers'] : array() );
		$this->_debug = isset( $args['debug'] ) ? $args['debug'] : false;
		$this->stats = array();
		$this->_compress_threshold = isset( $args['compress_threshold'] ) ? $args['compress_threshold'] : 0;
		$this->_persistent = isset( $args['persistent'] ) ? $args['persistent'] : false;
		$this->_compress_enable = true;
		$this->_have_zlib = function_exists( 'gzcompress' );

		$this->_cache_sock = array();
		$this->_host_dead = array();

		$this->_timeout_seconds = 0;
		$this->_timeout_microseconds = isset( $args['timeout'] ) ? $args['timeout'] : 500000;

		$this->_connect_timeout = isset( $args['connect_timeout'] ) ? $args['connect_timeout'] : 0.1;
		$this->_connect_attempts = 2;

		$this->_logger = isset( $args['logger'] ) ? $args['logger'] : new NullLogger();
	}

	// }}}
	// {{{ add()

	/**
	 * Adds a key/value to the memcache server if one isn't already set with
	 * that key
	 *
	 * @param string $key Key to set with data
	 * @param mixed $val Value to store
	 * @param int $exp (optional) Expiration time. This can be a number of seconds
	 * to cache for (up to 30 days inclusive).  Any timespans of 30 days + 1 second or
	 * longer must be the timestamp of the time at which the mapping should expire. It
	 * is safe to use timestamps in all cases, regardless of expiration
	 * eg: strtotime("+3 hour")
	 *
	 * @return bool
	 */
	public function add( $key, $val, $exp = 0 ) {
		return $this->_set( 'add', $key, $val, $exp );
	}

	// }}}
	// {{{ decr()

	/**
	 * Decrease a value stored on the memcache server
	 *
	 * @param string $key Key to decrease
	 * @param int $amt (optional) amount to decrease
	 *
	 * @return mixed False on failure, value on success
	 */
	public function decr( $key, $amt = 1 ) {
		return $this->_incrdecr( 'decr', $key, $amt );
	}

	// }}}
	// {{{ delete()

	/**
	 * Deletes a key from the server, optionally after $time
	 *
	 * @param string $key Key to delete
	 * @param int $time (optional) how long to wait before deleting
	 *
	 * @return bool True on success, false on failure
	 */
	public function delete( $key, $time = 0 ) {
		if ( !$this->_active ) {
			return false;
		}

		$sock = $this->get_sock( $key );
		if ( !is_resource( $sock ) ) {
			return false;
		}

		$key = is_array( $key ) ? $key[1] : $key;

		if ( isset( $this->stats['delete'] ) ) {
			$this->stats['delete']++;
		} else {
			$this->stats['delete'] = 1;
		}
		$cmd = "delete $key $time\r\n";
		if ( !$this->_fwrite( $sock, $cmd ) ) {
			return false;
		}
		$res = $this->_fgets( $sock );

		if ( $this->_debug ) {
			$this->_debugprint( sprintf( "MemCache: delete %s (%s)\n", $key, $res ) );
		}

		if ( $res == "DELETED" || $res == "NOT_FOUND" ) {
			return true;
		}

		return false;
	}

	/**
	 * @param string $key
	 * @param int $timeout
	 * @return bool
	 */
	public function lock( $key, $timeout = 0 ) {
		/* stub */
		return true;
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function unlock( $key ) {
		/* stub */
		return true;
	}

	// }}}
	// {{{ disconnect_all()

	/**
	 * Disconnects all connected sockets
	 */
	public function disconnect_all() {
		foreach ( $this->_cache_sock as $sock ) {
			fclose( $sock );
		}

		$this->_cache_sock = array();
	}

	// }}}
	// {{{ enable_compress()

	/**
	 * Enable / Disable compression
	 *
	 * @param bool $enable True to enable, false to disable
	 */
	public function enable_compress( $enable ) {
		$this->_compress_enable = $enable;
	}

	// }}}
	// {{{ forget_dead_hosts()

	/**
	 * Forget about all of the dead hosts
	 */
	public function forget_dead_hosts() {
		$this->_host_dead = array();
	}

	// }}}
	// {{{ get()

	/**
	 * Retrieves the value associated with the key from the memcache server
	 *
	 * @param array|string $key key to retrieve
	 * @param float $casToken [optional]
	 *
	 * @return mixed
	 */
	public function get( $key, &$casToken = null ) {

		if ( $this->_debug ) {
			$this->_debugprint( "get($key)\n" );
		}

		if ( !is_array( $key ) && strval( $key ) === '' ) {
			$this->_debugprint( "Skipping key which equals to an empty string" );
			return false;
		}

		if ( !$this->_active ) {
			return false;
		}

		$sock = $this->get_sock( $key );

		if ( !is_resource( $sock ) ) {
			return false;
		}

		$key = is_array( $key ) ? $key[1] : $key;
		if ( isset( $this->stats['get'] ) ) {
			$this->stats['get']++;
		} else {
			$this->stats['get'] = 1;
		}

		$cmd = "gets $key\r\n";
		if ( !$this->_fwrite( $sock, $cmd ) ) {
			return false;
		}

		$val = array();
		$this->_load_items( $sock, $val, $casToken );

		if ( $this->_debug ) {
			foreach ( $val as $k => $v ) {
				$this->_debugprint( sprintf( "MemCache: sock %s got %s\n", serialize( $sock ), $k ) );
			}
		}

		$value = false;
		if ( isset( $val[$key] ) ) {
			$value = $val[$key];
		}
		return $value;
	}

	// }}}
	// {{{ get_multi()

	/**
	 * Get multiple keys from the server(s)
	 *
	 * @param array $keys Keys to retrieve
	 *
	 * @return array
	 */
	public function get_multi( $keys ) {
		if ( !$this->_active ) {
			return false;
		}

		if ( isset( $this->stats['get_multi'] ) ) {
			$this->stats['get_multi']++;
		} else {
			$this->stats['get_multi'] = 1;
		}
		$sock_keys = array();
		$socks = array();
		foreach ( $keys as $key ) {
			$sock = $this->get_sock( $key );
			if ( !is_resource( $sock ) ) {
				continue;
			}
			$key = is_array( $key ) ? $key[1] : $key;
			if ( !isset( $sock_keys[$sock] ) ) {
				$sock_keys[intval( $sock )] = array();
				$socks[] = $sock;
			}
			$sock_keys[intval( $sock )][] = $key;
		}

		$gather = array();
		// Send out the requests
		foreach ( $socks as $sock ) {
			$cmd = 'gets';
			foreach ( $sock_keys[intval( $sock )] as $key ) {
				$cmd .= ' ' . $key;
			}
			$cmd .= "\r\n";

			if ( $this->_fwrite( $sock, $cmd ) ) {
				$gather[] = $sock;
			}
		}

		// Parse responses
		$val = array();
		foreach ( $gather as $sock ) {
			$this->_load_items( $sock, $val, $casToken );
		}

		if ( $this->_debug ) {
			foreach ( $val as $k => $v ) {
				$this->_debugprint( sprintf( "MemCache: got %s\n", $k ) );
			}
		}

		return $val;
	}

	// }}}
	// {{{ incr()

	/**
	 * Increments $key (optionally) by $amt
	 *
	 * @param string $key Key to increment
	 * @param int $amt (optional) amount to increment
	 *
	 * @return int|null Null if the key does not exist yet (this does NOT
	 * create new mappings if the key does not exist). If the key does
	 * exist, this returns the new value for that key.
	 */
	public function incr( $key, $amt = 1 ) {
		return $this->_incrdecr( 'incr', $key, $amt );
	}

	// }}}
	// {{{ replace()

	/**
	 * Overwrites an existing value for key; only works if key is already set
	 *
	 * @param string $key Key to set value as
	 * @param mixed $value Value to store
	 * @param int $exp (optional) Expiration time. This can be a number of seconds
	 * to cache for (up to 30 days inclusive).  Any timespans of 30 days + 1 second or
	 * longer must be the timestamp of the time at which the mapping should expire. It
	 * is safe to use timestamps in all cases, regardless of exipration
	 * eg: strtotime("+3 hour")
	 *
	 * @return bool
	 */
	public function replace( $key, $value, $exp = 0 ) {
		return $this->_set( 'replace', $key, $value, $exp );
	}

	// }}}
	// {{{ run_command()

	/**
	 * Passes through $cmd to the memcache server connected by $sock; returns
	 * output as an array (null array if no output)
	 *
	 * @param Resource $sock Socket to send command on
	 * @param string $cmd Command to run
	 *
	 * @return array Output array
	 */
	public function run_command( $sock, $cmd ) {
		if ( !is_resource( $sock ) ) {
			return array();
		}

		if ( !$this->_fwrite( $sock, $cmd ) ) {
			return array();
		}

		$ret = array();
		while ( true ) {
			$res = $this->_fgets( $sock );
			$ret[] = $res;
			if ( preg_match( '/^END/', $res ) ) {
				break;
			}
			if ( strlen( $res ) == 0 ) {
				break;
			}
		}
		return $ret;
	}

	// }}}
	// {{{ set()

	/**
	 * Unconditionally sets a key to a given value in the memcache.  Returns true
	 * if set successfully.
	 *
	 * @param string $key Key to set value as
	 * @param mixed $value Value to set
	 * @param int $exp (optional) Expiration time. This can be a number of seconds
	 * to cache for (up to 30 days inclusive).  Any timespans of 30 days + 1 second or
	 * longer must be the timestamp of the time at which the mapping should expire. It
	 * is safe to use timestamps in all cases, regardless of exipration
	 * eg: strtotime("+3 hour")
	 *
	 * @return bool True on success
	 */
	public function set( $key, $value, $exp = 0 ) {
		return $this->_set( 'set', $key, $value, $exp );
	}

	// }}}
	// {{{ cas()

	/**
	 * Sets a key to a given value in the memcache if the current value still corresponds
	 * to a known, given value.  Returns true if set successfully.
	 *
	 * @param float $casToken Current known value
	 * @param string $key Key to set value as
	 * @param mixed $value Value to set
	 * @param int $exp (optional) Expiration time. This can be a number of seconds
	 * to cache for (up to 30 days inclusive).  Any timespans of 30 days + 1 second or
	 * longer must be the timestamp of the time at which the mapping should expire. It
	 * is safe to use timestamps in all cases, regardless of exipration
	 * eg: strtotime("+3 hour")
	 *
	 * @return bool True on success
	 */
	public function cas( $casToken, $key, $value, $exp = 0 ) {
		return $this->_set( 'cas', $key, $value, $exp, $casToken );
	}

	// }}}
	// {{{ set_compress_threshold()

	/**
	 * Sets the compression threshold
	 *
	 * @param int $thresh Threshold to compress if larger than
	 */
	public function set_compress_threshold( $thresh ) {
		$this->_compress_threshold = $thresh;
	}

	// }}}
	// {{{ set_debug()

	/**
	 * Sets the debug flag
	 *
	 * @param bool $dbg True for debugging, false otherwise
	 *
	 * @see MWMemcached::__construct
	 */
	public function set_debug( $dbg ) {
		$this->_debug = $dbg;
	}

	// }}}
	// {{{ set_servers()

	/**
	 * Sets the server list to distribute key gets and puts between
	 *
	 * @param array $list Array of servers to connect to
	 *
	 * @see MWMemcached::__construct()
	 */
	public function set_servers( $list ) {
		$this->_servers = $list;
		$this->_active = count( $list );
		$this->_buckets = null;
		$this->_bucketcount = 0;

		$this->_single_sock = null;
		if ( $this->_active == 1 ) {
			$this->_single_sock = $this->_servers[0];
		}
	}

	/**
	 * Sets the timeout for new connections
	 *
	 * @param int $seconds Number of seconds
	 * @param int $microseconds Number of microseconds
	 */
	public function set_timeout( $seconds, $microseconds ) {
		$this->_timeout_seconds = $seconds;
		$this->_timeout_microseconds = $microseconds;
	}

	// }}}
	// }}}
	// {{{ private methods
	// {{{ _close_sock()

	/**
	 * Close the specified socket
	 *
	 * @param string $sock Socket to close
	 *
	 * @access private
	 */
	function _close_sock( $sock ) {
		$host = array_search( $sock, $this->_cache_sock );
		fclose( $this->_cache_sock[$host] );
		unset( $this->_cache_sock[$host] );
	}

	// }}}
	// {{{ _connect_sock()

	/**
	 * Connects $sock to $host, timing out after $timeout
	 *
	 * @param int $sock Socket to connect
	 * @param string $host Host:IP to connect to
	 *
	 * @return bool
	 * @access private
	 */
	function _connect_sock( &$sock, $host ) {
		list( $ip, $port ) = preg_split( '/:(?=\d)/', $host );
		$sock = false;
		$timeout = $this->_connect_timeout;
		$errno = $errstr = null;
		for ( $i = 0; !$sock && $i < $this->_connect_attempts; $i++ ) {
			MediaWiki\suppressWarnings();
			if ( $this->_persistent == 1 ) {
				$sock = pfsockopen( $ip, $port, $errno, $errstr, $timeout );
			} else {
				$sock = fsockopen( $ip, $port, $errno, $errstr, $timeout );
			}
			MediaWiki\restoreWarnings();
		}
		if ( !$sock ) {
			$this->_error_log( "Error connecting to $host: $errstr\n" );
			$this->_dead_host( $host );
			return false;
		}

		// Initialise timeout
		stream_set_timeout( $sock, $this->_timeout_seconds, $this->_timeout_microseconds );

		// If the connection was persistent, flush the read buffer in case there
		// was a previous incomplete request on this connection
		if ( $this->_persistent ) {
			$this->_flush_read_buffer( $sock );
		}
		return true;
	}

	// }}}
	// {{{ _dead_sock()

	/**
	 * Marks a host as dead until 30-40 seconds in the future
	 *
	 * @param string $sock Socket to mark as dead
	 *
	 * @access private
	 */
	function _dead_sock( $sock ) {
		$host = array_search( $sock, $this->_cache_sock );
		$this->_dead_host( $host );
	}

	/**
	 * @param string $host
	 */
	function _dead_host( $host ) {
		$parts = explode( ':', $host );
		$ip = $parts[0];
		$this->_host_dead[$ip] = time() + 30 + intval( rand( 0, 10 ) );
		$this->_host_dead[$host] = $this->_host_dead[$ip];
		unset( $this->_cache_sock[$host] );
	}

	// }}}
	// {{{ get_sock()

	/**
	 * get_sock
	 *
	 * @param string $key Key to retrieve value for;
	 *
	 * @return Resource|bool Resource on success, false on failure
	 * @access private
	 */
	function get_sock( $key ) {
		if ( !$this->_active ) {
			return false;
		}

		if ( $this->_single_sock !== null ) {
			return $this->sock_to_host( $this->_single_sock );
		}

		$hv = is_array( $key ) ? intval( $key[0] ) : $this->_hashfunc( $key );
		if ( $this->_buckets === null ) {
			$bu = array();
			foreach ( $this->_servers as $v ) {
				if ( is_array( $v ) ) {
					for ( $i = 0; $i < $v[1]; $i++ ) {
						$bu[] = $v[0];
					}
				} else {
					$bu[] = $v;
				}
			}
			$this->_buckets = $bu;
			$this->_bucketcount = count( $bu );
		}

		$realkey = is_array( $key ) ? $key[1] : $key;
		for ( $tries = 0; $tries < 20; $tries++ ) {
			$host = $this->_buckets[$hv % $this->_bucketcount];
			$sock = $this->sock_to_host( $host );
			if ( is_resource( $sock ) ) {
				return $sock;
			}
			$hv = $this->_hashfunc( $hv . $realkey );
		}

		return false;
	}

	// }}}
	// {{{ _hashfunc()

	/**
	 * Creates a hash integer based on the $key
	 *
	 * @param string $key Key to hash
	 *
	 * @return int Hash value
	 * @access private
	 */
	function _hashfunc( $key ) {
		# Hash function must be in [0,0x7ffffff]
		# We take the first 31 bits of the MD5 hash, which unlike the hash
		# function used in a previous version of this client, works
		return hexdec( substr( md5( $key ), 0, 8 ) ) & 0x7fffffff;
	}

	// }}}
	// {{{ _incrdecr()

	/**
	 * Perform increment/decriment on $key
	 *
	 * @param string $cmd Command to perform
	 * @param string|array $key Key to perform it on
	 * @param int $amt Amount to adjust
	 *
	 * @return int New value of $key
	 * @access private
	 */
	function _incrdecr( $cmd, $key, $amt = 1 ) {
		if ( !$this->_active ) {
			return null;
		}

		$sock = $this->get_sock( $key );
		if ( !is_resource( $sock ) ) {
			return null;
		}

		$key = is_array( $key ) ? $key[1] : $key;
		if ( isset( $this->stats[$cmd] ) ) {
			$this->stats[$cmd]++;
		} else {
			$this->stats[$cmd] = 1;
		}
		if ( !$this->_fwrite( $sock, "$cmd $key $amt\r\n" ) ) {
			return null;
		}

		$line = $this->_fgets( $sock );
		$match = array();
		if ( !preg_match( '/^(\d+)/', $line, $match ) ) {
			return null;
		}
		return $match[1];
	}

	// }}}
	// {{{ _load_items()

	/**
	 * Load items into $ret from $sock
	 *
	 * @param Resource $sock Socket to read from
	 * @param array $ret returned values
	 * @param float $casToken [optional]
	 * @return bool True for success, false for failure
	 *
	 * @access private
	 */
	function _load_items( $sock, &$ret, &$casToken = null ) {
		$results = array();

		while ( 1 ) {
			$decl = $this->_fgets( $sock );

			if ( $decl === false ) {
				/*
				 * If nothing can be read, something is wrong because we know exactly when
				 * to stop reading (right after "END") and we return right after that.
				 */
				return false;
			} elseif ( preg_match( '/^VALUE (\S+) (\d+) (\d+) (\d+)$/', $decl, $match ) ) {
				/*
				 * Read all data returned. This can be either one or multiple values.
				 * Save all that data (in an array) to be processed later: we'll first
				 * want to continue reading until "END" before doing anything else,
				 * to make sure that we don't leave our client in a state where it's
				 * output is not yet fully read.
				 */
				$results[] = array(
					$match[1], // rkey
					$match[2], // flags
					$match[3], // len
					$match[4], // casToken
					$this->_fread( $sock, $match[3] + 2 ), // data
				);
			} elseif ( $decl == "END" ) {
				if ( count( $results ) == 0 ) {
					return false;
				}

				/**
				 * All data has been read, time to process the data and build
				 * meaningful return values.
				 */
				foreach ( $results as $vars ) {
					list( $rkey, $flags, $len, $casToken, $data ) = $vars;

					if ( $data === false || substr( $data, -2 ) !== "\r\n" ) {
						$this->_handle_error( $sock,
							'line ending missing from data block from $1' );
						return false;
					}
					$data = substr( $data, 0, -2 );
					$ret[$rkey] = $data;

					if ( $this->_have_zlib && $flags & self::COMPRESSED ) {
						$ret[$rkey] = gzuncompress( $ret[$rkey] );
					}

					/*
					 * This unserialize is the exact reason that we only want to
					 * process data after having read until "END" (instead of doing
					 * this right away): "unserialize" can trigger outside code:
					 * in the event that $ret[$rkey] is a serialized object,
					 * unserializing it will trigger __wakeup() if present. If that
					 * function attempted to read from memcached (while we did not
					 * yet read "END"), these 2 calls would collide.
					 */
					if ( $flags & self::SERIALIZED ) {
						$ret[$rkey] = unserialize( $ret[$rkey] );
					} elseif ( $flags & self::INTVAL ) {
						$ret[$rkey] = intval( $ret[$rkey] );
					}
				}

				return true;
			} else {
				$this->_handle_error( $sock, 'Error parsing response from $1' );
				return false;
			}
		}
	}

	// }}}
	// {{{ _set()

	/**
	 * Performs the requested storage operation to the memcache server
	 *
	 * @param string $cmd Command to perform
	 * @param string $key Key to act on
	 * @param mixed $val What we need to store
	 * @param int $exp (optional) Expiration time. This can be a number of seconds
	 * to cache for (up to 30 days inclusive).  Any timespans of 30 days + 1 second or
	 * longer must be the timestamp of the time at which the mapping should expire. It
	 * is safe to use timestamps in all cases, regardless of exipration
	 * eg: strtotime("+3 hour")
	 * @param float $casToken [optional]
	 *
	 * @return bool
	 * @access private
	 */
	function _set( $cmd, $key, $val, $exp, $casToken = null ) {
		if ( !$this->_active ) {
			return false;
		}

		$sock = $this->get_sock( $key );
		if ( !is_resource( $sock ) ) {
			return false;
		}

		if ( isset( $this->stats[$cmd] ) ) {
			$this->stats[$cmd]++;
		} else {
			$this->stats[$cmd] = 1;
		}

		$flags = 0;

		if ( is_int( $val ) ) {
			$flags |= self::INTVAL;
		} elseif ( !is_scalar( $val ) ) {
			$val = serialize( $val );
			$flags |= self::SERIALIZED;
			if ( $this->_debug ) {
				$this->_debugprint( sprintf( "client: serializing data as it is not scalar\n" ) );
			}
		}

		$len = strlen( $val );

		if ( $this->_have_zlib && $this->_compress_enable
			&& $this->_compress_threshold && $len >= $this->_compress_threshold
		) {
			$c_val = gzcompress( $val, 9 );
			$c_len = strlen( $c_val );

			if ( $c_len < $len * ( 1 - self::COMPRESSION_SAVINGS ) ) {
				if ( $this->_debug ) {
					$this->_debugprint( sprintf( "client: compressing data; was %d bytes is now %d bytes\n", $len, $c_len ) );
				}
				$val = $c_val;
				$len = $c_len;
				$flags |= self::COMPRESSED;
			}
		}

		$command = "$cmd $key $flags $exp $len";
		if ( $casToken ) {
			$command .= " $casToken";
		}

		if ( !$this->_fwrite( $sock, "$command\r\n$val\r\n" ) ) {
			return false;
		}

		$line = $this->_fgets( $sock );

		if ( $this->_debug ) {
			$this->_debugprint( sprintf( "%s %s (%s)\n", $cmd, $key, $line ) );
		}
		if ( $line == "STORED" ) {
			return true;
		}
		return false;
	}

	// }}}
	// {{{ sock_to_host()

	/**
	 * Returns the socket for the host
	 *
	 * @param string $host Host:IP to get socket for
	 *
	 * @return Resource|bool IO Stream or false
	 * @access private
	 */
	function sock_to_host( $host ) {
		if ( isset( $this->_cache_sock[$host] ) ) {
			return $this->_cache_sock[$host];
		}

		$sock = null;
		$now = time();
		list( $ip, /* $port */) = explode( ':', $host );
		if ( isset( $this->_host_dead[$host] ) && $this->_host_dead[$host] > $now ||
			isset( $this->_host_dead[$ip] ) && $this->_host_dead[$ip] > $now
		) {
			return null;
		}

		if ( !$this->_connect_sock( $sock, $host ) ) {
			return null;
		}

		// Do not buffer writes
		stream_set_write_buffer( $sock, 0 );

		$this->_cache_sock[$host] = $sock;

		return $this->_cache_sock[$host];
	}

	/**
	 * @param string $text
	 */
	function _debugprint( $text ) {
		$this->_logger->debug( $text );
	}

	/**
	 * @param string $text
	 */
	function _error_log( $text ) {
		$this->_logger->error( "Memcached error: $text" );
	}

	/**
	 * Write to a stream. If there is an error, mark the socket dead.
	 *
	 * @param Resource $sock The socket
	 * @param string $buf The string to write
	 * @return bool True on success, false on failure
	 */
	function _fwrite( $sock, $buf ) {
		$bytesWritten = 0;
		$bufSize = strlen( $buf );
		while ( $bytesWritten < $bufSize ) {
			$result = fwrite( $sock, $buf );
			$data = stream_get_meta_data( $sock );
			if ( $data['timed_out'] ) {
				$this->_handle_error( $sock, 'timeout writing to $1' );
				return false;
			}
			// Contrary to the documentation, fwrite() returns zero on error in PHP 5.3.
			if ( $result === false || $result === 0 ) {
				$this->_handle_error( $sock, 'error writing to $1' );
				return false;
			}
			$bytesWritten += $result;
		}

		return true;
	}

	/**
	 * Handle an I/O error. Mark the socket dead and log an error.
	 *
	 * @param Resource $sock
	 * @param string $msg
	 */
	function _handle_error( $sock, $msg ) {
		$peer = stream_socket_get_name( $sock, true /** remote **/ );
		if ( strval( $peer ) === '' ) {
			$peer = array_search( $sock, $this->_cache_sock );
			if ( $peer === false ) {
				$peer = '[unknown host]';
			}
		}
		$msg = str_replace( '$1', $peer, $msg );
		$this->_error_log( "$msg\n" );
		$this->_dead_sock( $sock );
	}

	/**
	 * Read the specified number of bytes from a stream. If there is an error,
	 * mark the socket dead.
	 *
	 * @param Resource $sock The socket
	 * @param int $len The number of bytes to read
	 * @return string|bool The string on success, false on failure.
	 */
	function _fread( $sock, $len ) {
		$buf = '';
		while ( $len > 0 ) {
			$result = fread( $sock, $len );
			$data = stream_get_meta_data( $sock );
			if ( $data['timed_out'] ) {
				$this->_handle_error( $sock, 'timeout reading from $1' );
				return false;
			}
			if ( $result === false ) {
				$this->_handle_error( $sock, 'error reading buffer from $1' );
				return false;
			}
			if ( $result === '' ) {
				// This will happen if the remote end of the socket is shut down
				$this->_handle_error( $sock, 'unexpected end of file reading from $1' );
				return false;
			}
			$len -= strlen( $result );
			$buf .= $result;
		}
		return $buf;
	}

	/**
	 * Read a line from a stream. If there is an error, mark the socket dead.
	 * The \r\n line ending is stripped from the response.
	 *
	 * @param Resource $sock The socket
	 * @return string|bool The string on success, false on failure
	 */
	function _fgets( $sock ) {
		$result = fgets( $sock );
		// fgets() may return a partial line if there is a select timeout after
		// a successful recv(), so we have to check for a timeout even if we
		// got a string response.
		$data = stream_get_meta_data( $sock );
		if ( $data['timed_out'] ) {
			$this->_handle_error( $sock, 'timeout reading line from $1' );
			return false;
		}
		if ( $result === false ) {
			$this->_handle_error( $sock, 'error reading line from $1' );
			return false;
		}
		if ( substr( $result, -2 ) === "\r\n" ) {
			$result = substr( $result, 0, -2 );
		} elseif ( substr( $result, -1 ) === "\n" ) {
			$result = substr( $result, 0, -1 );
		} else {
			$this->_handle_error( $sock, 'line ending missing in response from $1' );
			return false;
		}
		return $result;
	}

	/**
	 * Flush the read buffer of a stream
	 * @param Resource $f
	 */
	function _flush_read_buffer( $f ) {
		if ( !is_resource( $f ) ) {
			return;
		}
		$r = array( $f );
		$w = null;
		$e = null;
		$n = stream_select( $r, $w, $e, 0, 0 );
		while ( $n == 1 && !feof( $f ) ) {
			fread( $f, 1024 );
			$r = array( $f );
			$w = null;
			$e = null;
			$n = stream_select( $r, $w, $e, 0, 0 );
		}
	}

	// }}}
	// }}}
	// }}}
}

// }}}

class MemCachedClientforWiki extends MWMemcached {
}
