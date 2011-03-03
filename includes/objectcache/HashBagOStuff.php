<?php

/**
 * This is a test of the interface, mainly. It stores things in an associative
 * array, which is not going to persist between program runs.
 *
 * @ingroup Cache
 */
class HashBagOStuff extends BagOStuff {
	var $bag;

	function __construct() {
		$this->bag = array();
	}

	protected function expire( $key ) {
		$et = $this->bag[$key][1];

		if ( ( $et == 0 ) || ( $et > time() ) ) {
			return false;
		}

		$this->delete( $key );

		return true;
	}

	function get( $key ) {
		if ( !isset( $this->bag[$key] ) ) {
			return false;
		}

		if ( $this->expire( $key ) ) {
			return false;
		}

		return $this->bag[$key][0];
	}

	function set( $key, $value, $exptime = 0 ) {
		$this->bag[$key] = array( $value, $this->convertExpiry( $exptime ) );
	}

	function delete( $key, $time = 0 ) {
		if ( !isset( $this->bag[$key] ) ) {
			return false;
		}

		unset( $this->bag[$key] );

		return true;
	}

	function keys() {
		return array_keys( $this->bag );
	}
}

