<?php

/**
 * An interface to support process-local caching of handler data associated
 * with a given file. Intended to replace the previous usage of custom
 * properties on the File object.
 *
 * @since 1.37
 */
interface MediaHandlerState {
	/**
	 * Get a value, or null if it does not exist.
	 *
	 * @param string $key
	 * @return mixed|null
	 */
	public function getHandlerState( string $key );

	/**
	 * Set a value
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function setHandlerState( string $key, $value );
}
