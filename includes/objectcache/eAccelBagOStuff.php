<?php

/**
 * This is a wrapper for eAccelerator's shared memory functions.
 *
 * This is basically identical to the deceased Turck MMCache version,
 * mostly because eAccelerator is based on Turck MMCache.
 *
 * @ingroup Cache
 */
class eAccelBagOStuff extends BagOStuff {
	public function get( $key ) {
		$val = eaccelerator_get( $key );

		if ( is_string( $val ) ) {
			$val = unserialize( $val );
		}

		return $val;
	}

	public function set( $key, $value, $exptime = 0 ) {
		eaccelerator_put( $key, serialize( $value ), $exptime );

		return true;
	}

	public function delete( $key, $time = 0 ) {
		eaccelerator_rm( $key );

		return true;
	}

	public function lock( $key, $waitTimeout = 0 ) {
		eaccelerator_lock( $key );

		return true;
	}

	public function unlock( $key ) {
		eaccelerator_unlock( $key );

		return true;
	}
}

