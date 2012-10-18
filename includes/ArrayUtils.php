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
 */
class ArrayUtils {
	/**
	 * Do a binary search, and return the index of the largest item that sorts
	 * less than or equal to the target value.
	 *
	 * @param $valueCallback array A function to call to get the value with
	 *     a given array index.
	 * @param $valueCount int The number of items accessible via $valueCallback,
	 *     indexed from 0 to $valueCount - 1
	 * @param $comparisonCallback array A callback to compare two values, returning
	 *     -1, 0 or 1 in the style of strcmp().
	 * @param $target string The target value to find.
	 *
	 * @return int|bool The item index of the lower bound, or false if the target value
	 *     sorts before all items.
	 */
	static function findLowerBound( $valueCallback, $valueCount, $comparisonCallback, $target ) {
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
	 * @param $array1 array The array to compare from
	 * @param $array2 array An array to compare against
	 * @param ... array More arrays to compare against
	 * @return array An array containing all the values from array1
	 *               that are not present in any of the other arrays.
	 */
	static function arrayDiffAssocRecursive( $array1 ) {
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
