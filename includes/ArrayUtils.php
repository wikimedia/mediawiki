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
	public static function consistentHashSort( &$array, $key, $separator = "\000" ) {
		$hashes = array();
		foreach ( $array as $elt ) {
			$hashes[$elt] = md5( $elt . $separator . $key );
		}
		uasort( $array, function ( $a, $b ) use ( $hashes ) {
			return strcmp( $hashes[$a], $hashes[$b] );
		} );
	}

	/**
	 * Given an array of non-normalised probabilities, this function will select
	 * an element and return the appropriate key
	 *
	 * @param $weights array
	 *
	 * @return bool|int|string
	 */
	public static function pickRandom( $weights ) {
		if ( !is_array( $weights ) || count( $weights ) == 0 ) {
			return false;
		}

		$sum = array_sum( $weights );
		if ( $sum == 0 ) {
			# No loads on any of them
			# In previous versions, this triggered an unweighted random selection,
			# but this feature has been removed as of April 2006 to allow for strict
			# separation of query groups.
			return false;
		}
		$max = mt_getrandmax();
		$rand = mt_rand( 0, $max ) / $max * $sum;

		$sum = 0;
		foreach ( $weights as $i => $w ) {
			$sum += $w;
			# Do not return keys if they have 0 weight.
			# Note that the "all 0 weight" case is handed above
			if ( $w > 0 && $sum >= $rand ) {
				break;
			}
		}
		return $i;
	}
}
