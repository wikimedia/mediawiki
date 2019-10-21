<?php
/**
 * Object caching using PHP's APC accelerator.
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
 * This is a wrapper for APC's shared memory functions
 *
 * Use PHP serialization to avoid bugs and easily create CAS tokens.
 * APCu has a memory corruption bug when the serializer is set to 'default'.
 * See T120267, and upstream bug reports:
 *  - https://github.com/krakjoe/apcu/issues/38
 *  - https://github.com/krakjoe/apcu/issues/35
 *  - https://github.com/krakjoe/apcu/issues/111
 *
 * @ingroup Cache
 */
class APCBagOStuff extends MediumSpecificBagOStuff {
	/** @var bool Whether to trust the APC implementation to serialization */
	private $nativeSerialize;

	/**
	 * @var string String to append to each APC key. This may be changed
	 *  whenever the handling of values is changed, to prevent existing code
	 *  from encountering older values which it cannot handle.
	 */
	const KEY_SUFFIX = ':4';

	public function __construct( array $params = [] ) {
		$params['segmentationSize'] = $params['segmentationSize'] ?? INF;
		parent::__construct( $params );
		// The extension serializer is still buggy, unlike "php" and "igbinary"
		$this->nativeSerialize = ( ini_get( 'apc.serializer' ) !== 'default' );
	}

	protected function doGet( $key, $flags = 0, &$casToken = null ) {
		$casToken = null;

		$blob = apc_fetch( $key . self::KEY_SUFFIX );
		$value = $this->nativeSerialize ? $blob : $this->unserialize( $blob );
		if ( $value !== false ) {
			$casToken = $blob; // don't bother hashing this
		}

		return $value;
	}

	protected function doSet( $key, $value, $exptime = 0, $flags = 0 ) {
		apc_store(
			$key . self::KEY_SUFFIX,
			$this->nativeSerialize ? $value : $this->serialize( $value ),
			$exptime
		);

		return true;
	}

	protected function doAdd( $key, $value, $exptime = 0, $flags = 0 ) {
		return apc_add(
			$key . self::KEY_SUFFIX,
			$this->nativeSerialize ? $value : $this->serialize( $value ),
			$exptime
		);
	}

	protected function doDelete( $key, $flags = 0 ) {
		apc_delete( $key . self::KEY_SUFFIX );

		return true;
	}

	public function incr( $key, $value = 1, $flags = 0 ) {
		return apc_inc( $key . self::KEY_SUFFIX, $value );
	}

	public function decr( $key, $value = 1, $flags = 0 ) {
		return apc_dec( $key . self::KEY_SUFFIX, $value );
	}
}
