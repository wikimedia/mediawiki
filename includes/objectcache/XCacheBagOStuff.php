<?php

/**
 * Wrapper for XCache object caching functions; identical interface
 * to the APC wrapper
 *
 * @ingroup Cache
 */
class XCacheBagOStuff extends BagOStuff {
	/**
	 * Are we operating in CLI mode? Since xcache doesn't work then and they 
	 * don't want to change that
	 * @see bug 28752
	 * @var bool
	 */
	private $isCli = false;

	public function __construct() {
		$this->isCli = php_sapi_name() == 'cli';
	}

	/**
	 * Get a value from the XCache object cache
	 *
	 * @param $key String: cache key
	 * @return mixed
	 */
	public function get( $key ) {
		if( $this->isCli ) {
			return false;
		}
		$val = xcache_get( $key );

		if ( is_string( $val ) ) {
			$val = unserialize( $val );
		}

		return $val;
	}

	/**
	 * Store a value in the XCache object cache
	 *
	 * @param $key String: cache key
	 * @param $value Mixed: object to store
	 * @param $expire Int: expiration time
	 * @return bool
	 */
	public function set( $key, $value, $expire = 0 ) {
		if( !$this->isCli ) {
			xcache_set( $key, serialize( $value ), $expire );
		}
		return true;
	}

	/**
	 * Remove a value from the XCache object cache
	 *
	 * @param $key String: cache key
	 * @param $time Int: not used in this implementation
	 * @return bool
	 */
	public function delete( $key, $time = 0 ) {
		if( !$this->isCli ) {
			xcache_unset( $key );
		}
		return true;
	}
}

