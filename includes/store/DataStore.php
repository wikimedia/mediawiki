<?php

abstract class DataStore {
	const MAX_KEY_LENGTH = 255;

	private static $stores = array();
	public static function getStore( $name = 'default' ) {
		if ( isset( self::$stores[$name] ) ) {
			return self::$stores[$name];
		}

		global $wgDataStores;
		if ( !isset( $wgDataStores[$name] ) ) {
			throw new MWException( "Unrecognised data store '$name'" );
		}
		$config = $wgDataStores[$name];
		$store = new $config['class']( $config );
		self::$stores[$name] = $store;
		return $store;
	}

	public abstract function get( $key, $latest = false );
	public abstract function set( $key, $value );
	public abstract function getByPrefix( $prefix, $callback, $latest = false );
	public abstract function delete( $key );

	public final function deleteByPrefix( $prefix ) {
		if ( $prefix === '' ) {
			throw new MWException( 'Fool-proof against all data deletion' );
		}
		$this->deleteByPrefixInternal( $prefix );
	}

	protected abstract function deleteByPrefixInternal( $prefix );

	public function key( /*...*/ ) {
		$keys = func_get_args();
		$key = implode( ':', $keys );
		if ( strlen( $key ) > self::MAX_KEY_LENGTH ) {
			throw new MWException( "Key '$key' is too long" );
		}
		return $key;
	}

	protected function encodeValue( $data ) {
		if ( is_array( $data ) || is_object( $data ) ) {
			return serialize( $data );
		}
		return $data;
	}
};
