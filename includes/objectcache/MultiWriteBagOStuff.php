<?php

/**
 * A cache class that replicates all writes to multiple child caches. Reads 
 * are implemented by reading from the caches in the order they are given in 
 * the configuration until a cache gives a positive result.
 */
class MultiWriteBagOStuff extends BagOStuff {
	var $caches;

	/**
	 * Constructor. Parameters are:
	 *
	 *   - caches:   This should have a numbered array of cache parameter 
	 *               structures, in the style required by $wgObjectCaches. See
	 *               the documentation of $wgObjectCaches for more detail.
	 */
	public function __construct( $params ) {
		if ( !isset( $params['caches'] ) ) {
			throw new MWException( __METHOD__.': the caches parameter is required' );
		}

		$this->caches = array();
		foreach ( $params['caches'] as $cacheInfo ) {
			$this->caches[] = ObjectCache::newFromParams( $cacheInfo );
		}
	}

	public function setDebug( $debug ) {
		$this->doWrite( 'setDebug', $debug );
	}

	public function get( $key ) {
		foreach ( $this->caches as $cache ) {
			$value = $cache->get( $key );
			if ( $value !== false ) {
				return $value;
			}
		}
		return false;
	}

	public function set( $key, $value, $exptime = 0 ) {
		return $this->doWrite( 'set', $key, $value, $exptime );
	}

	public function delete( $key, $time = 0 ) {
		return $this->doWrite( 'delete', $key, $time );
	}

	public function add( $key, $value, $exptime = 0 ) {
		return $this->doWrite( 'add', $key, $value, $exptime );
	}

	public function replace( $key, $value, $exptime = 0 ) {
		return $this->doWrite( 'replace', $key, $value, $exptime );
	}

	public function incr( $key, $value = 1 ) {
		return $this->doWrite( 'incr', $key, $value );
	}

	public function decr( $key, $value = 1 ) {
		return $this->doWrite( 'decr', $key, $value );
	}	

	public function lock( $key, $timeout = 0 ) {
		// Lock only the first cache, to avoid deadlocks
		if ( isset( $this->caches[0] ) ) {
			return $this->caches[0]->lock( $key, $timeout );
		} else {
			return true;
		}
	}

	public function unlock( $key ) {
		if ( isset( $this->caches[0] ) ) {
			return $this->caches[0]->unlock( $key );
		} else {
			return true;
		}
	}

	protected function doWrite( $method /*, ... */ ) {
		$ret = true;
		$args = func_get_args();
		array_shift( $args );

		foreach ( $this->caches as $cache ) {
			$ret = $ret && call_user_func_array( array( $cache, $method ), $args );
		}
		return $ret;
	}

}
