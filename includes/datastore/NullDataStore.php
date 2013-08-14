<?php

/**
 * Store that stores nothing. Fastest NoSQL in the world.
 */
class NullDataStore extends DataStore {

	/**
	 * Returns value for a given key or null if not found
	 *
	 * @param string $key : Data key
	 * @param bool $latest : Whether a replicated or distributed store should ensure that the data returned is latest
	 *
	 * @return mixed
	 */
	public function get( $key, $latest = false ) {
		return null;
	}

	/**
	 * Sets value for a given key
	 *
	 * @param $key
	 * @param $value
	 */
	public function set( $key, $value ) {
	}

	/**
	 * Returns all values whose keys start with a given string
	 *
	 * @param string $prefix
	 * @param callable $callback : Function that will receive data. Example: function( $key, $value )
	 * @param bool $latest : Whether a replicated or distributed store should ensure that the data returned is latest
	 */
	public function getByPrefix( $prefix, $callback, $latest = false ) {
	}

	/**
	 * @param $key
	 */
	public function delete( $key ) {
	}

	/**
	 * @param $prefix
	 */
	protected function deleteByPrefixInternal( $prefix ) {
	}
}
