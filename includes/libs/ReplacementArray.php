<?php
/**
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
 * Replacement array for FSS with fallback to strtr()
 * Supports lazy initialisation of FSS resource
 */
class ReplacementArray {
	private $data = false;
	private $fss = false;

	/**
	 * Create an object with the specified replacement array
	 * The array should have the same form as the replacement array for strtr()
	 * @param array $data
	 */
	public function __construct( $data = array() ) {
		$this->data = $data;
	}

	/**
	 * @return array
	 */
	public function __sleep() {
		return array( 'data' );
	}

	public function __wakeup() {
		$this->fss = false;
	}

	/**
	 * Set the whole replacement array at once
	 * @param array $data
	 */
	public function setArray( $data ) {
		$this->data = $data;
		$this->fss = false;
	}

	/**
	 * @return array|bool
	 */
	public function getArray() {
		return $this->data;
	}

	/**
	 * Set an element of the replacement array
	 * @param string $from
	 * @param string $to
	 */
	public function setPair( $from, $to ) {
		$this->data[$from] = $to;
		$this->fss = false;
	}

	/**
	 * @param array $data
	 */
	public function mergeArray( $data ) {
		$this->data = $data + $this->data;
		$this->fss = false;
	}

	/**
	 * @param ReplacementArray $other
	 */
	public function merge( ReplacementArray $other ) {
		$this->data = $other->data + $this->data;
		$this->fss = false;
	}

	/**
	 * @param string $from
	 */
	public function removePair( $from ) {
		unset( $this->data[$from] );
		$this->fss = false;
	}

	/**
	 * @param array $data
	 */
	public function removeArray( $data ) {
		foreach ( $data as $from => $to ) {
			$this->removePair( $from );
		}
		$this->fss = false;
	}

	/**
	 * @param string $subject
	 * @return string
	 */
	public function replace( $subject ) {
		if (
			function_exists( 'fss_prep_replace' )  &&
			version_compare( PHP_VERSION, '5.5.0' ) < 0
		) {
			if ( $this->fss === false ) {
				$this->fss = fss_prep_replace( $this->data );
			}
			$result = fss_exec_replace( $this->fss, $subject );
		} else {
			$result = strtr( $subject, $this->data );
		}

		return $result;
	}
}
