<?php

namespace MediaWiki\Settings\Cache;

use Psr\SimpleCache\CacheInterface;

/**
 * A PSR-16 compliant array based cache used for testing.
 *
 * @since 1.38
 */
class ArrayCache implements CacheInterface {
	/** @var array */
	private $store = [];

	public function clear(): bool {
		$this->store = [];
		return true;
	}

	public function delete( $key ): bool {
		unset( $this->store[$key] );
		return true;
	}

	public function deleteMultiple( $keys ): bool {
		foreach ( $keys as $key ) {
			$this->delete( $key );
		}
		return true;
	}

	public function get( $key, $default = null ) {
		if ( !array_key_exists( $key, $this->store ) ) {
			return $default;
		}
		return $this->store[$key];
	}

	public function getMultiple( $keys, $default = null ) {
		$results = [];

		foreach ( $keys as $key ) {
			$results[$key] = $this->get( $key, $default );
		}

		return $results;
	}

	public function has( $key ): bool {
		return array_key_exists( $key, $this->store );
	}

	public function set( $key, $value, $_ttl = null ): bool {
		$this->store[$key] = $value;
		return true;
	}

	public function setMultiple( $values, $ttl = null ): bool {
		foreach ( $values as $key => $value ) {
			$this->set( $key, $value, $ttl );
		}
		return true;
	}
}
