<?php

/**
 * A wrapper class for the pure-PHP memcached client, exposing a BagOStuff interface.
 */
class MemcachedPhpBagOStuff extends BagOStuff {

	/**
	 * @var MemCachedClientforWiki
	 */
	protected $client;

	/**
	 * Constructor.
	 *
	 * Available parameters are:
	 *   - servers:             The list of IP:port combinations holding the memcached servers.
	 *   - debug:               Whether to set the debug flag in the underlying client.
	 *   - persistent:          Whether to use a persistent connection
	 *   - compress_threshold:  The minimum size an object must be before it is compressed
	 *   - timeout:             The read timeout in microseconds
	 *   - connect_timeout:     The connect timeout in seconds
	 *
	 * @param $params array
	 */
	function __construct( $params ) {
		if ( !isset( $params['servers'] ) ) {
			$params['servers'] = $GLOBALS['wgMemCachedServers'];
		}
		if ( !isset( $params['debug'] ) ) {
			$params['debug'] = $GLOBALS['wgMemCachedDebug'];
		}
		if ( !isset( $params['persistent'] ) ) {
			$params['persistent'] = $GLOBALS['wgMemCachedPersistent'];
		}
		if  ( !isset( $params['compress_threshold'] ) ) {
			$params['compress_threshold'] = 1500;
		}
		if ( !isset( $params['timeout'] ) ) {
			$params['timeout'] = $GLOBALS['wgMemCachedTimeout'];
		}
		if ( !isset( $params['connect_timeout'] ) ) {
			$params['connect_timeout'] = 0.1;
		}

		$this->client = new MemCachedClientforWiki( $params );
		$this->client->set_servers( $params['servers'] );
		$this->client->set_debug( $params['debug'] );
	}

	/**
	 * @param $debug bool
	 */
	public function setDebug( $debug ) {
		$this->client->set_debug( $debug );
	}

	/**
	 * @param $key string
	 * @return Mixed
	 */
	public function get( $key ) {
		return $this->client->get( $this->encodeKey( $key ) );
	}

	/**
	 * @param $key string
	 * @param $value
	 * @param $exptime int
	 * @return bool
	 */
	public function set( $key, $value, $exptime = 0 ) {
		return $this->client->set( $this->encodeKey( $key ), $value, $exptime );
	}

	/**
	 * @param $key string
	 * @param $time int
	 * @return bool
	 */
	public function delete( $key, $time = 0 ) {
		return $this->client->delete( $this->encodeKey( $key ), $time );
	}

	/**
	 * @param $key
	 * @param $timeout int
	 * @return
	 */
	public function lock( $key, $timeout = 0 ) {
		return $this->client->lock( $this->encodeKey( $key ), $timeout );
	}

	/**
	 * @param $key string
	 * @return Mixed
	 */
	public function unlock( $key ) {
		return $this->client->unlock( $this->encodeKey( $key ) );
	}

	/**
	 * @param $key string
	 * @param $value int
	 * @return Mixed
	 */
	public function add( $key, $value, $exptime = 0 ) {
		return $this->client->add( $this->encodeKey( $key ), $value, $exptime );
	}

	/**
	 * @param $key string
	 * @param $value int
	 * @param $exptime
	 * @return Mixed
	 */
	public function replace( $key, $value, $exptime = 0 ) {
		return $this->client->replace( $this->encodeKey( $key ), $value, $exptime );
	}

	/**
	 * @param $key string
	 * @param $value int
	 * @return Mixed
	 */
	public function incr( $key, $value = 1 ) {
		return $this->client->incr( $this->encodeKey( $key ), $value );
	}

	/**
	 * @param $key string
	 * @param $value int
	 * @return Mixed
	 */
	public function decr( $key, $value = 1 ) {
		return $this->client->decr( $this->encodeKey( $key ), $value );
	}

	/**
	 * Get the underlying client object. This is provided for debugging 
	 * purposes.
	 *
	 * @return MemCachedClientforWiki
	 */
	public function getClient() {
		return $this->client;
	}

	/**
	 * Encode a key for use on the wire inside the memcached protocol.
	 *
	 * We encode spaces and line breaks to avoid protocol errors. We encode 
	 * the other control characters for compatibility with libmemcached 
	 * verify_key. We leave other punctuation alone, to maximise backwards
	 * compatibility.
	 */
	public function encodeKey( $key ) {
		return preg_replace_callback( '/[\x00-\x20\x25\x7f]+/', 
			array( $this, 'encodeKeyCallback' ), $key );
	}

	protected function encodeKeyCallback( $m ) {
		return rawurlencode( $m[0] );
	}

	/**
	 * Decode a key encoded with encodeKey(). This is provided as a convenience 
	 * function for debugging.
	 *
	 * @param $key string
	 *
	 * @return string
	 */
	public function decodeKey( $key ) {
		return urldecode( $key );
	}
}

