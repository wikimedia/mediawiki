<?php
/**
 * Copyright 2017
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
 * @author Aaron Schulz
 */

use ActiveCollab\Etcd\Client;
use ActiveCollab\Etcd\Exception\EtcdException;

/**
 * Interface for configuration instances
 *
 * @since 1.29
 */
class EtcdConfig implements MutableConfig {
	/** @var Client */
	private $client;
	/** @var BagOStuff */
	private $cache;

	/** @var string */
	private $directory;
	/** @var string */
	private $encoding;
	/** @var integer */
	private $baseCacheTTL;
	/** @var integer */
	private $skewCacheTTL;
	/** @var string */
	private $directoryHash;

	/**
	 * @param array $params Parameter map:
	 *   - host: the host address and port
	 *   - directory: the etc "directory" were MediaWiki specific variables are located
	 *   - encoding: one of ("JSON", "YAML")
	 *   - cache: BagOStuff instance or ObjectFactory spec thereof for a server cache.
	 *            The cache will also be used as a fallback if etcd is down.
	 *   - cacheTTL: logical cache TTL in seconds
	 *   - skewTTL: maximum seconds to randomly lower the assigned TTL on cache save
	 */
	public function __construct( array $params ) {
		$params += [ 'encoding' => 'JSON', 'cacheTTL' => 10, 'skewTTL' => 1 ];

		$this->client = new Client( $params['host'] );
		$this->directory = rtrim( $params['directory'], '/' );
		$this->directoryHash = sha1( $this->directory );
		$this->encoding = $params['encoding'];
		$this->skewCacheTTL = $params['skewTTL'];
		$this->baseCacheTTL = max( $params['cacheTTL'] - $this->skewCacheTTL, 0 );

		if ( !isset( $params['cache'] ) ) {
			$this->cache = new HashBagOStuff( [] );
		} elseif ( $params['cache'] instanceof BagOStuff ) {
			$this->cache = $params['cache'];
		} else {
			$this->cache = ObjectFactory::getObjectFromSpec( $params['cache'] );
		}
	}

	public function get( $name ) {
		$queryable = true;
		$now = microtime( true );
		$key = $this->cacheKey( $name );

		$data = $this->cache->get( $key );
		if ( !is_array( $data ) || $now > $data['exp'] ) {
			// If there is no cached value or it is expired, try to rebuild the cache
			if ( $this->retrieveAndCacheAll() ) {
				// If this succeeded, try to re-fetch the new cached value
				$data = $this->cache->get( $key );
			} else {
				$queryable = false; // lock is probably busy
			}
		}

		if ( is_array( $data ) && $now < $data['exp'] ) {
			$variable = $data['var']; // use cached value
		} elseif ( $queryable ) {
			try {
				$variable = $this->retrieve( $name );

				// Avoid having all servers expire cache keys at the same time
				$skew = mt_rand( 0, 1e6 ) / 1e6 * $this->skewCacheTTL;
				$expiry = microtime( true ) + $this->baseCacheTTL + $skew;

				$this->cache->set(
					$key,
					[ 'var' => $variable, 'exp' => $expiry ],
					BagOStuff::TTL_INDEFINITE // logical TTL only; useful for fallback
				);
				$data = $this->cache->get( $key );
			} catch ( EtcdException $e ) {
				if ( $data !== false ) {
					// Fallback to the cached value
					$variable = $data['var'];
				} else {
					// No cached value available; throw an error
					throw new ConfigException( "Got " . get_class( $e ) . ": {$e->getMessage()}" );
				}
			}
		} elseif ( $data !== false ) {
			// Fallback to the cached value if the lock is likely busy
			$variable = $data['var'];
		} else {
			throw new ConfigException( "No cached entry for '$name' and etcd is not queryable." );
		}

		return $variable;
	}

	public function set( $name, $value ) {
		$encoded = $this->serialize( self::wrap( $value, true ) );
		if ( !strlen( $encoded ) ) {
			throw new ConfigException( "Failed to encode value for '$name'." );
		}

		try {
			$this->client->set( "{$this->directory}/{$name}", $encoded );
		} catch ( EtcdException $e ) {
			throw new ConfigException( "Got " . get_class( $e ) . ": {$e->getMessage()}" );
		}
	}

	/**
	 * @param string $name
	 * @return mixed
	 * @throws ConfigException
	 * @throws EtcdException
	 */
	private function retrieve( $name ) {
		$value = $this->client->get( "{$this->directory}/{$name}" );

		$map = $this->unserialize( $value );
		if ( !is_array( $map ) ) {
			throw new ConfigException( "Value for '$name' failed to decode." );
		}

		$this->assertHasKeys( $map, [ 'value', 'type' ] );

		return self::unwrap( $map['value'], $map['type'] );
	}

	/**
	 * @param string $name
	 * @return bool Success
	 * @throws ConfigException
	 */
	private function retrieveAndCacheAll() {
		// Avoid re-cache stampedes to etcd. Let the anti-stampede lock key expire on its
		// own to handle the case when a caller keeps trying to get a non-existant variable.
		$lockKey = $this->cache->makeKey( 'recache-lock', $this->directoryHash );
		if ( !$this->cache->add( $lockKey, 1, $this->baseCacheTTL ) ) {
			return false;
		}

		try {
			// Retrieve all the values under the MediaWiki config directory
			$values = $this->client->getKeyValueMap( "{$this->directory}/", false );
		} catch ( EtcdException $e ) {
			return false;
		}

		// Avoid having all servers expire cache keys at the same time
		$expiry = microtime( true ) + $this->baseCacheTTL;
		$expiry += mt_rand( 0, 1e6 ) / 1e6 * $this->skewCacheTTL;

		foreach ( $values as $key => $value ) {
			$name = basename( $key );

			$map = $this->unserialize( $value );
			if ( !is_array( $map ) ) {
				throw new ConfigException( "Value for '$name' failed to decode." );
			}

			$this->assertHasKeys( $map, [ 'value', 'type' ] );
			$variable = self::unwrap( $map['value'], $map['type'] );

			$this->cache->set(
				$this->cacheKey( $name ),
				[ 'var' => $variable, 'exp' => $expiry ],
				BagOStuff::TTL_INDEFINITE // logical TTL only; useful for fallback
			);
		}

		return true;
	}

	/**
	 * @param string $name
	 * @return string
	 */
	private function cacheKey( $name ) {
		return $this->cache->makeKey( 'variable', $this->directoryHash, $name );
	}

	/**
	 * @param mixed $object
	 * @return string|bool
	 */
	private function serialize( $object ) {
		if ( $this->encoding === 'YAML' ) {
			return yaml_emit( $object );
		} else {
			return json_encode( $object );
		}
	}

	/**
	 * @param string $string
	 * @return mixed
	 */
	private function unserialize( $string ) {
		if ( $this->encoding === 'YAML' ) {
			return yaml_parse( $string );
		} else {
			return json_decode( $string, true );
		}
	}

	/**
	 * @param mixed $value
	 * @param bool $forceArray
	 * @return string
	 */
	private function wrap( $value, $forceArray = false ) {
		if ( self::isOrderedMap( $value ) ) {
			// JSON/YAML don't have ordered maps, so use a list with [key,value] entries
			$result = [ 'value' => [], 'type' => 'omap' ];
			foreach ( $value as $key => $element ) {
				$entry = [ 'key' => $key ] + self::wrap( $element, true );
				$result['value'][] = $entry;
			}
		} elseif ( is_array( $value ) ) {
			$result = [ 'value' => [], 'type' => 'native' ];
			foreach ( $value as $element ) {
				$result['value'][] = self::wrap( $element );
			}
		} elseif ( $forceArray ) {
			// The outermost layer of a variable is forced as an array
			$result = [ 'value' => $value, 'type' => 'native' ];
		} else {
			$result = $value;
		}

		return $result;
	}

	/**
	 * @param mixed $value
	 * @param string $type
	 * @return mixed
	 * @throws ConfigException
	 */
	private function unwrap( $value, $type ) {
		if ( $type === 'omap' ) {
			if ( !is_array( $value ) || self::isOrderedMap( $value ) ) {
				throw new ConfigException( "Expected 0-indexed array for 'omap' value." );
			}

			$result = [];
			foreach ( $value as $element ) {
				$this->assertHasKeys( $element, [ 'key', 'value', 'type' ] );

				$key = $element['key'];
				if ( !is_string( $key ) && !is_int( $key ) ) {
					throw new ConfigException( "Expected integer or string key." );
				}

				$result[$key] = self::unwrap( $element['value'], $element['type'] );
			}
		} elseif ( $type === 'native' ) {
			if ( is_array( $value ) ) {
				$result = [];
				foreach ( $value as $element ) {
					if ( is_array( $element ) ) {
						$this->assertHasKeys( $element, [ 'value', 'type' ] );

						$result[] = self::unwrap( $element['value'], $element['type'] );
					} else {
						$result[] = $element;
					}
				}
			} else {
				$result = $value;
			}
		} else {
			throw new ConfigException( "Unexpected type '$type'." );
		}

		return $result;
	}

	/**
	 * @param mixed $var
	 * @return bool
	 */
	private function isOrderedMap( $var ) {
		if ( !is_array( $var ) ) {
			return false;
		}

		$i = 0;
		foreach ( $var as $key => $unused ) {
			if ( $key !== $i ) {
				return true;
			}
			++$i;
		}

		return false;
	}

	/**
	 * @param array $value
	 * @param array $keys
	 * @throws ConfigException
	 */
	private function assertHasKeys( array $value, array $keys ) {
		foreach ( $keys as $key ) {
			if ( !array_key_exists( $key, $value ) ) {
				throw new ConfigException( "Expected key '$key' inside array." );
			}
		}
	}
}
