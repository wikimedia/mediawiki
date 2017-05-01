<?php
/**
 * Object caching using PHP's APCU accelerator.
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
 * @ingroup Cache
 */

/**
 * This is a wrapper for APCU's shared memory functions
 *
 * @ingroup Cache
 */
class APCUBagOStuff extends APCBagOStuff {
	/**
	 * Constructor
	 *
	 * Available parameters are:
	 *   - nativeSerialize:     If true, pass objects to apcu_store(), and trust it
	 *                          to serialize them correctly. If false, serialize
	 *                          all values in PHP.
	 *
	 * @param array $params
	 */
	public function __construct( array $params = [] ) {
		parent::__construct( $params );
	}

	protected function doGet( $key, $flags = 0 ) {
		return $this->getUnserialize(
			apcu_fetch( $key . self::KEY_SUFFIX )
		);
	}

	public function set( $key, $value, $exptime = 0, $flags = 0 ) {
		apcu_store(
			$key . self::KEY_SUFFIX,
			$this->setSerialize( $value ),
			$exptime
		);

		return true;
	}

	public function delete( $key ) {
		apcu_delete( $key . self::KEY_SUFFIX );

		return true;
	}

	public function incr( $key, $value = 1 ) {
		/**
		 * @todo When we only support php 7 or higher remove this hack
		 *
		 * https://github.com/krakjoe/apcu/issues/166
		 */
		if ( apcu_exists( $key . self::KEY_SUFFIX ) ) {
			return apcu_inc( $key . self::KEY_SUFFIX, $value );
		} else {
			return apcu_set( $key . self::KEY_SUFFIX, $value );
		}
	}

	public function decr( $key, $value = 1 ) {
		/**
		 * @todo When we only support php 7 or higher remove this hack
		 *
		 * https://github.com/krakjoe/apcu/issues/166
		 */
		if ( apcu_exists( $key . self::KEY_SUFFIX ) ) {
			return apcu_dec( $key . self::KEY_SUFFIX, $value );
		} else {
			return apcu_set( $key . self::KEY_SUFFIX, -$value );
		}
	}
}
