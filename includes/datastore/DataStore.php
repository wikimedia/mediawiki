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
 * Base class for generic key-value store
 */
abstract class DataStore {
	/**
	 * Maximum key length. Must be enforced in all implementations to guarantee data portability
	 */
	const MAX_KEY_LENGTH = 255;

	private static $stores = array();

	/**
	 * Default constructor, sets class properties from config array
	 * @param array $config: Associative array of store configuration
	 */
	public function __construct( array $config ) {
		foreach ( $config as $name => $value ) {
			$this->$name = $value;
		}
	}

	/**
	 * Creates a store with a given name
	 * The store must be configured in $wgDataStores
	 *
	 * @param string $name
	 * @return DataStore
	 */
	public static function getStore( $name = 'default' ) {
		if ( isset( self::$stores[$name] ) ) {
			return self::$stores[$name];
		}

		global $wgDataStores;
		wfProfileIn( __METHOD__ );
		if ( !isset( $wgDataStores[$name] ) ) {
			throw new MWException( "Unrecognised data store '$name'" );
		}
		$config = $wgDataStores[$name];
		$store = new $config['class']( $config );
		self::$stores[$name] = $store;
		wfProfileOut( __METHOD__ );
		return $store;
	}

	/**
	 * Returns value for a given key or null if not found
	 *
	 * @param string|array $key: Data key if stirng or array of keys if the function should return multiple values.
	 *                           In latter case, an associative array( key => value ) will be returned.
	 * @param bool $latest: Whether a replicated or distributed store should ensure that the data returned is latest
	 * @return mixed
	 */
	public abstract function get( $key, $latest = false );

	/**
	 * Sets value for a given key
	 *
	 * @param $key
	 * @param mixed $value
	 */
	public abstract function set( $key, $value );

	/**
	 * Returns all values whose keys start with a given string
	 *
	 * @param string $prefix
	 * @param callable $callback: Function that will receive data. Example: function( $key, $value )
	 * @param bool $latest: Whether a replicated or distributed store should ensure that the data returned is latest
	 */
	public abstract function getByPrefix( $prefix, $callback, $latest = false );

	/**
	 * @param $key
	 */
	public abstract function delete( $key );

	/**
	 * @param $prefix
	 */
	public final function deleteByPrefix( $prefix ) {
		if ( $prefix === '' ) {
			throw new MWException( 'Fool-proof against deletion of everything' );
		}
		$this->deleteByPrefixInternal( $prefix );
	}

	/**
	 * @param $prefix
	 */
	protected abstract function deleteByPrefixInternal( $prefix );

	/**
	 * @return string
	 */
	public function key( /*...*/ ) {
		$keys = func_get_args();
		$key = implode( ':', $keys );
		if ( strlen( $key ) > self::MAX_KEY_LENGTH ) {
			throw new MWException( "Key '$key' is too long" );
		}
		return $key;
	}

	/**
	 * Encodes data to be stored in a format suitable for current store.
	 * Default implementation attempts to store primitive types in a non-serialized format.
	 *
	 * @param mixed $data: Data to encode
	 * @return mixed
	 */
	protected function encodeValue( $data ) {
		if ( is_array( $data ) || is_object( $data ) ) {
			return serialize( $data );
		}
		return $data;
	}
};
