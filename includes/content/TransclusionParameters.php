<?php
/**
 * Interface representing parameters for transclusion.
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
 * @since 1.25
 *
 * @file
 * @ingroup Content
 *
 * @author Daniel Kinzler
 */
abstract class TransclusionParameters {

	/**
	 * Returns the parameters, as an associative array of strings.
	 * Positional parameters will have consecutive integers as keys,
	 * starting with 1. Named parameters have their names as keys.
	 * The order of the entries in the array is undefined.
	 *
	 * @note Classes implementing this interface are encouraged to provide
	 * alternative methods for accessing the parameters in a more meaningful
	 * form, instead of strings, e.g. as JSONeque array structures.
	 *
	 * @return string[]
	 */
	public abstract function getParameters();

	/**
	 * Convenience function to get positional parameters as an in-order array.
	 * Returns parameters with integer keys from the array getParameters() returns.
	 * The resulting array is sorted by key.
	 *
	 * @return array
	 */
	public function getPositionalParameters() {
		$parameters = array_filter( $this->getParameters(), 'is_int' );
		ksort( $parameters );
		return $parameters;
	}

}
 