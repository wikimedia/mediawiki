<?php

namespace MediaWiki\Settings\Cache;

use APCuIterator;
use DateInterval;
use DateTime;
use MediaWiki\Settings\SettingsBuilderException;
use Psr\SimpleCache\CacheInterface;
use Traversable;

/**
 * A PSR-16 compliant APCu based shared memory settings cache.
 *
 * @since 1.38
 */
class SharedMemoryCache implements CacheInterface {
	private const VERSION = '0';

	/** @var string */
	private $namespace;

	/** @var int */
	private $ttl;

	/**
	 * Checks whether APCu is available.
	 *
	 * @return bool
	 */
	public static function isSupported() {
		return function_exists( 'apcu_fetch' )
			&& filter_var( ini_get( 'apc.enabled' ), FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * @param string $namespace APCu namespace.
	 * @param int $ttl Cached settings TTL in seconds.
	 *
	 * @throws SettingsBuilderException
	 */
	public function __construct(
		string $namespace = 'mediawiki.settings',
		int $ttl = 60 * 60 * 24
	) {
		$this->namespace = $namespace;
		$this->ttl = $ttl;

		if ( !static::isSupported() ) {
			throw new SettingsBuilderException( 'APCu is not enabled' );
		}
	}

	/**
	 * Clear all items from this cache.
	 *
	 * @return bool True on success, false on failure.
	 */
	public function clear(): bool {
		return apcu_delete(
			new APCuIterator(
				sprintf( '/^%s/', preg_quote( $this->qualifyKey( '' ) ) ),
				APC_ITER_KEY
			)
		);
	}

	/**
	 * Delete an item from the cache.
	 *
	 * @param string $key
	 *
	 * @return bool True if removed, false if not.
	 *
	 * @throws CacheArgumentException
	 */
	public function delete( $key ): bool {
		return $this->deleteMultiple( [ $key ] );
	}

	/**
	 * Deletes multiple cache items.
	 *
	 * @param iterable $keys
	 *
	 * @return bool True if all were removed, false if not.
	 *
	 * @throws CacheArgumentException
	 */
	public function deleteMultiple( $keys ): bool {
		if ( !( is_array( $keys ) || $keys instanceof Traversable ) ) {
			throw new CacheArgumentException(
				'given cache $keys is not an iterable'
			);
		}

		$apcuKeys = [];

		foreach ( $keys as $key ) {
			$this->assertValidKey( $key );
			$apcuKeys[] = $this->qualifyKey( $key );
		}

		$deleted = apcu_delete( $apcuKeys );

		if ( is_array( $deleted ) ) {
			return count( $deleted ) === count( $keys );
		}

		return $deleted;
	}

	/**
	 * Fetches a value from the cache.
	 *
	 * @param string $key
	 * @param mixed $default
	 *
	 * @return mixed
	 *
	 * @throws CacheArgumentException
	 */
	public function get( $key, $default = null ) {
		$result = $default;

		foreach ( $this->getMultiple( [ $key ], $default ) as $value ) {
			$result = $value;
		}

		return $result;
	}

	/**
	 * Fetches values from the cache.
	 *
	 * @param iterable $keys
	 * @param mixed $default
	 *
	 * @return iterable
	 *
	 * @throws CacheArgumentException
	 */
	public function getMultiple( $keys, $default = null ) {
		if ( !( is_array( $keys ) || $keys instanceof Traversable ) ) {
			throw new CacheArgumentException(
				'given cache $keys is not an iterable'
			);
		}

		$results = [];
		$keyMap = [];

		foreach ( $keys as $key ) {
			$this->assertValidKey( $key );
			$keyMap[ $this->qualifyKey( $key ) ] = $key;
			$results[$key] = $default;
		}

		$apcuResults = apcu_fetch( array_keys( $keyMap ), $ok ) ?: [];

		if ( $ok ) {
			foreach ( $apcuResults as $qualifiedKey => $value ) {
				$results[ $keyMap[ $qualifiedKey ] ] = $value;
			}
		}

		return $results;
	}

	/**
	 * Checks for the presence of a given item in the cache.
	 *
	 * @param string $key
	 *
	 * @return bool
	 *
	 * @throws CacheArgumentException
	 */
	public function has( $key ): bool {
		$this->assertValidKey( $key );

		return apcu_exists( $this->qualifyKey( $key ) );
	}

	/**
	 * Store a value in the cache.
	 *
	 * Note that while object values are possible given the underlying APCU
	 * serializer, caching of objects directly should be avoided due to
	 * incompatibilities in serialized representation from PHP version to PHP
	 * version and when simple differences in member scope are made (private
	 * to protected, etc.). Wherever possible, convert objects to arrays
	 * before storing.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param null|int|DateInterval $ttl Optional. By default, the $ttl
	 * argument passed to the constructor will be used.
	 *
	 * @return bool True on success, false on failure.
	 *
	 * @throws CacheArgumentException
	 */
	public function set( $key, $value, $ttl = null ): bool {
		return $this->setMultiple( [ $key => $value ], $ttl );
	}

	/**
	 * Store multiple values in the cache.
	 *
	 * @param iterable $values An iterable of key => value pairs.
	 * @param null|int|DateInterval $ttl Optional. By default, the $ttl
	 * argument passed to the constructor will be used.
	 *
	 * @return bool True on success, false on failure.
	 *
	 * @throws CacheArgumentException
	 */
	public function setMultiple( $values, $ttl = null ): bool {
		if ( $ttl === null ) {
			$ttl = $this->ttl;
		}

		if ( !( is_array( $values ) || $values instanceof Traversable ) ) {
			throw new CacheArgumentException(
				'given cache $values is not an iterable'
			);
		}

		// To be compliant with the PSR-16 interface, we must accept a
		// DateInterval TTL. Convert it to an int.
		if ( $ttl instanceof DateInterval ) {
			$ttl = ( new DateTime( '@0' ) )->add( $ttl )->getTimestamp();
		}

		$apcuValues = [];

		foreach ( $values as $key => $value ) {
			$this->assertValidKey( $key );
			$apcuValues[ $this->qualifyKey( $key ) ] = $value;
		}

		$failures = apcu_store( $apcuValues, null, $ttl );

		return empty( $failures );
	}

	/**
	 * Fully qualifies a relative key, prefixing it with the internal
	 * namespace and cache implementation version.
	 *
	 * @param string $key Relative key value
	 *
	 * @return string
	 */
	private function qualifyKey( string $key ): string {
		return $this->namespace . '@' . self::VERSION . ':' . $key;
	}

	/**
	 * @param string $key
	 *
	 * @throws CacheArgumentException
	 */
	private function assertValidKey( $key ) {
		if ( !is_string( $key ) ) {
			throw new CacheArgumentException( 'key must be a string' );
		}

		if ( empty( $key ) ) {
			throw new CacheArgumentException( 'key cannot be an empty string' );
		}
	}
}
