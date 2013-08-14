<?php

abstract class DataStore {
	const MAX_KEY_LENGTH = 255;
	public abstract function get( $key, $latest = false );
	public abstract function set( $key, $value );
	public abstract function getByPrefix( $prefix, $callback, $latest = false );

	public function key( /*...*/ ) {
		global $wgWikiID;
		$keys = func_get_args();
		array_unshift( $keys, $wgWikiID );
		return $this->makeKey( $keys );
	}

	public function sharedKey( /*...*/ ) {
		return $this->makeKey( func_get_args() );
	}

	protected function makeKey( $keys ) {
		$key = implode( ':', $keys );
		if ( strlen( $key ) > self::MAX_KEY_LENGTH ) {
			throw new MWException( "Key '$key' is too long" );
		}
		return $key;
	}
};
