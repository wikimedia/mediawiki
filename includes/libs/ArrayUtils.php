<?php
/**
 * Methods to play with arrays.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

/**
 * A collection of static methods to play with arrays.
 *
 * @since 1.21
 */
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
	 * @param array $array Array to sort
	 * @param string $key
	 * @param string $separator A separator used to delimit the array elements and the
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
	 * @param array $weights
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

	/**
	 * Do a binary search, and return the index of the largest item that sorts
	 * less than or equal to the target value.
	 *
	 * @since 1.23
	 *
	 * @param callable $valueCallback A function to call to get the value with
	 *     a given array index.
	 * @param int $valueCount The number of items accessible via $valueCallback,
	 *     indexed from 0 to $valueCount - 1
	 * @param callable $comparisonCallback A callback to compare two values, returning
	 *     -1, 0 or 1 in the style of strcmp().
	 * @param string $target The target value to find.
	 *
	 * @return int|bool The item index of the lower bound, or false if the target value
	 *     sorts before all items.
	 */
	public static function findLowerBound( $valueCallback, $valueCount,
		$comparisonCallback, $target
	) {
		if ( $valueCount === 0 ) {
			return false;
		}

		$min = 0;
		$max = $valueCount;
		do {
			$mid = $min + ( ( $max - $min ) >> 1 );
			$item = call_user_func( $valueCallback, $mid );
			$comparison = call_user_func( $comparisonCallback, $target, $item );
			if ( $comparison > 0 ) {
				$min = $mid;
			} elseif ( $comparison == 0 ) {
				$min = $mid;
				break;
			} else {
				$max = $mid;
			}
		} while ( $min < $max - 1 );

		if ( $min == 0 ) {
			$item = call_user_func( $valueCallback, $min );
			$comparison = call_user_func( $comparisonCallback, $target, $item );
			if ( $comparison < 0 ) {
				// Before the first item
				return false;
			}
		}
		return $min;
	}

	/**
	 * Do array_diff_assoc() on multi-dimensional arrays.
	 *
	 * Note: empty arrays are removed.
	 *
	 * @since 1.23
	 *
	 * @param array $array1 The array to compare from
	 * @param array $array2,... More arrays to compare against
	 * @return array An array containing all the values from array1
	 *               that are not present in any of the other arrays.
	 */
	public static function arrayDiffAssocRecursive( $array1 ) {
		$arrays = func_get_args();
		array_shift( $arrays );
		$ret = array();

		foreach ( $array1 as $key => $value ) {
			if ( is_array( $value ) ) {
				$args = array( $value );
				foreach ( $arrays as $array ) {
					if ( isset( $array[$key] ) ) {
						$args[] = $array[$key];
					}
				}
				$valueret = call_user_func_array( __METHOD__, $args );
				if ( count( $valueret ) ) {
					$ret[$key] = $valueret;
				}
			} else {
				foreach ( $arrays as $array ) {
					if ( isset( $array[$key] ) && $array[$key] === $value ) {
						continue 2;
					}
				}
				$ret[$key] = $value;
			}
		}

		return $ret;
	}
}
