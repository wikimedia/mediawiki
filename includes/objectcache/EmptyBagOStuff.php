<?php

/**
 * A BagOStuff object with no objects in it. Used to provide a no-op object to calling code.
 *
 * @ingroup Cache
 */
class EmptyBagOStuff extends BagOStuff {
	function get( $key ) {
		return false;
	}

	function set( $key, $value, $exp = 0 ) {
		return true;
	}

	function delete( $key, $time = 0 ) {
		return true;
	}
}

/**
 * Backwards compatibility alias for EmptyBagOStuff
 * @deprecated since 1.18
 */
class FakeMemCachedClient extends EmptyBagOStuff {
}
