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
class APCUBagOStuff extends BagOStuff {

	/**
	 * @var bool If true, trust the APCU implementation to serialize and
	 * deserialize objects correctly. If false, (de-)serialize in PHP.
	 */
	protected $nativeSerialize;

	/**
	 * @var string String to append to each APCU key. This may be changed
	 *  whenever the handling of values is changed, to prevent existing code
	 *  from encountering older values which it cannot handle.
	 */
	const KEY_SUFFIX = ':2';

	/**
	 * Constructor
	 *
	 * Available parameters are:
	 *   - nativeSerialize:     If true, pass objects to apcU_store(), and trust it
	 *                          to serialize them correctly. If false, serialize
	 *                          all values in PHP.
	 *
	 * @param array $params
	 */
	public function __construct( array $params = [] ) {
		parent::__construct( $params );

		if ( isset( $params['nativeSerialize'] ) ) {
			$this->nativeSerialize = $params['nativeSerialize'];
		} elseif ( extension_loaded( 'apcu' ) && ini_get( 'apcu.serializer' ) === 'default' ) {
			// APCu has a memory corruption bug when the serializer is set to 'default'.
			// See T120267, and upstream bug reports:
			//  - https://github.com/krakjoe/apcu/issues/38
			//  - https://github.com/krakjoe/apcu/issues/35
			//  - https://github.com/krakjoe/apcu/issues/111
			$this->logger->warning(
				'The APCu extension is loaded and the apc.serializer INI setting ' .
				'is set to "default". This can cause memory corruption! ' .
				'You should change apc.serializer to "php" instead. ' .
				'See <https://github.com/krakjoe/apcu/issues/38>.'
			);
			$this->nativeSerialize = false;
		} else {
			$this->nativeSerialize = true;
		}
	}

	protected function doGet( $key, $flags = 0 ) {
		$val = apcu_fetch( $key . self::KEY_SUFFIX );

		if ( is_string( $val ) && !$this->nativeSerialize ) {
			$val = $this->isInteger( $val )
				? intval( $val )
				: unserialize( $val );
		}

		return $val;
	}

	public function set( $key, $value, $exptime = 0, $flags = 0 ) {
		if ( !$this->nativeSerialize && !$this->isInteger( $value ) ) {
			$value = serialize( $value );
		}

		apcu_store( $key . self::KEY_SUFFIX, $value, $exptime );

		return true;
	}

	public function delete( $key ) {
		apcu_delete( $key . self::KEY_SUFFIX );

		return true;
	}

	public function incr( $key, $value = 1 ) {
		return apcu_inc( $key . self::KEY_SUFFIX, $value );
	}

	public function decr( $key, $value = 1 ) {
		return apcu_dec( $key . self::KEY_SUFFIX, $value );
	}
}
