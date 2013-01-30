<?php

class ArrayUtils {
	/**
	 * Sort the given array in a pseudo-random order which depends only on the
	 * given key and each element value. This is typically used for load
	 * balancing between servers each with a local cache.
	 *
	 * Keys are preserved. The input array is modified in place.
	 *
	 * Note: Benchmarking on PHP 5.3 and 5.4 indicates that for small
	 * strings, md5() is only 10% slower than hash('joaat',...) etc.,
	 * since the function call overhead dominates. So there's not much
	 * justification for breaking compatibility with installations
	 * compiled with ./configure --disable-hash.
	 * 
	 * @param $array The array to sort
	 * @param $key The string key
	 * @param $separator A separator used to delimit the array elements and the
	 *     key. This can be chosen to provide backwards compatibility with 
	 *     various consistent hash implementations that existed before this
	 *     function was introduced.
	 */
	static function consistentHashSort( &$array, $key, $separator = "\000" ) {
		$hashes = array();
		foreach ( $array as $elt ) {
			$hashes[$elt] = md5( $elt . $separator . $key );
		}
		uasort( $array, function ( $a, $b ) use ( $hashes ) {
			return strcmp( $hashes[$a], $hashes[$b] );
		} );
	}
}
