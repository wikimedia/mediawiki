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

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Http\Telemetry;
use MediaWiki\Logger\Spi;
use MediaWiki\MainConfigNames;
use Wikimedia\Stats\StatsFactory;

/**
 * Factory for cache objects as configured in the ObjectCaches setting.
 *
 * The word "cache" has two main dictionary meanings, and both
 * are used in this factory class. They are:
 *
 *    - a) Cache (the computer science definition).
 *         A place to store copies or computations on existing data for
 *         higher access speeds.
 *    - b) Storage.
 *         A place to store lightweight data that is not canonically
 *         stored anywhere else (e.g. a "hoard" of objects).
 *
 *  Primary entry points:
 *
 *  - ObjectCacheFactory::getLocalServerInstance( $fallbackType )
 *    Purpose: Memory cache for very hot keys.
 *    Stored only on the individual web server (typically APC or APCu for web requests,
 *    and EmptyBagOStuff in CLI mode).
 *    Not replicated to the other servers.
 *
 *  - ObjectCacheFactory::getInstance( $cacheType )
 *    Purpose: Special cases (like tiered memory/disk caches).
 *    Get a specific cache type by key in $wgObjectCaches.
 *
 *  All the above BagOStuff cache instances have their makeKey()
 *  method scoped to the *current* wiki ID. Use makeGlobalKey() to avoid this scoping
 *  when using keys that need to be shared amongst wikis.
 *
 * @ingroup Cache
 * @since 1.42
 */
class ObjectCacheFactory {
	/**
	 * @internal For use by ServiceWiring.php
	 * @var array
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::SQLiteDataDir,
		MainConfigNames::UpdateRowsPerQuery,
		MainConfigNames::MemCachedServers,
		MainConfigNames::MemCachedPersistent,
		MainConfigNames::MemCachedTimeout,
		MainConfigNames::CachePrefix,
		MainConfigNames::ObjectCaches,
		MainConfigNames::MainCacheType,
	];

	private ServiceOptions $options;
	private StatsFactory $stats;
	private Spi $logger;
	/** @var BagOStuff[] */
	private $instances = [];
	private string $domainId;
	/** @var callable */
	private $dbLoadBalancerFactory;

	public function __construct(
		ServiceOptions $options,
		StatsFactory $stats,
		Spi $loggerSpi,
		callable $dbLoadBalancerFactory,
		string $domainId
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->stats = $stats;
		$this->logger = $loggerSpi;
		$this->dbLoadBalancerFactory = $dbLoadBalancerFactory;
		$this->domainId = $domainId;
	}

	/**
	 * Get the default keyspace for this wiki.
	 *
	 * This is either the value of the MainConfigNames::CachePrefix setting
	 * or (if the former is unset) the MainConfigNames::DBname setting, with
	 * MainConfigNames::DBprefix (if defined).
	 *
	 * @return string
	 */
	private function getDefaultKeyspace(): string {
		$cachePrefix = $this->options->get( MainConfigNames::CachePrefix );
		if ( is_string( $cachePrefix ) && $cachePrefix !== '' ) {
			return $cachePrefix;
		}

		return $this->domainId;
	}

	/**
	 * Create a new cache object of the specified type.
	 *
	 * @param string|int $id A key in $wgObjectCaches.
	 * @return BagOStuff
	 */
	private function newFromId( $id ): BagOStuff {
		if ( $id === CACHE_ANYTHING ) {
			$id = ObjectCache::getAnythingId();
		}

		if ( !isset( $this->options->get( MainConfigNames::ObjectCaches )[$id] ) ) {
			// Always recognize these
			if ( $id === CACHE_NONE ) {
				return new EmptyBagOStuff();
			} elseif ( $id === CACHE_HASH ) {
				return new HashBagOStuff();
			} elseif ( $id === CACHE_ACCEL ) {
				return ObjectCache::makeLocalServerCache( $this->getDefaultKeyspace() );
			}

			throw new InvalidArgumentException( "Invalid object cache type \"$id\" requested. " .
				"It is not present in \$wgObjectCaches." );
		}

		return $this->newFromParams( $this->options->get( MainConfigNames::ObjectCaches )[$id] );
	}

	/**
	 * Get a cached instance of the specified type of cache object.
	 *
	 * @param string|int $id A key in $wgObjectCaches.
	 * @return BagOStuff
	 */
	public function getInstance( $id ): BagOStuff {
		if ( !isset( $this->instances[$id] ) ) {
			$this->instances[$id] = $this->newFromId( $id );
		}

		return $this->instances[$id];
	}

	/**
	 * @internal Using this method directly outside of MediaWiki core
	 *   is discouraged. Use getInstance() instead and supply the ID
	 *   of the cache instance to be looked up.
	 *
	 * Create a new cache object from parameters specification supplied.
	 *
	 * @param array $params Must have 'factory' or 'class' property.
	 *  - factory: Callback passed $params that returns BagOStuff.
	 *  - class: BagOStuff subclass constructed with $params.
	 *  - loggroup: Alias to set 'logger' key with LoggerFactory group.
	 *  - .. Other parameters passed to factory or class.
	 *
	 * @return BagOStuff
	 */
	public function newFromParams( array $params ): BagOStuff {
		$logger = $this->logger->getLogger( $params['loggroup'] ?? 'objectcache' );
		// Apply default parameters and resolve the logger instance
		$params += [
			'logger' => $logger,
			'keyspace' => $this->getDefaultKeyspace(),
			'asyncHandler' => [ DeferredUpdates::class, 'addCallableUpdate' ],
			'reportDupes' => true,
			'stats' => $this->stats,
		];

		if ( isset( $params['factory'] ) ) {
			$args = $params['args'] ?? [ $params ];

			return call_user_func( $params['factory'], ...$args );
		}

		if ( !isset( $params['class'] ) ) {
			throw new InvalidArgumentException(
				'No "factory" nor "class" provided; got "' . print_r( $params, true ) . '"'
			);
		}

		$class = $params['class'];

		// Normalization and DI for SqlBagOStuff
		if ( is_a( $class, SqlBagOStuff::class, true ) ) {
			$this->prepareSqlBagOStuffFromParams( $params );
		}

		// Normalization and DI for MemcachedBagOStuff
		if ( is_subclass_of( $class, MemcachedBagOStuff::class ) ) {
			$this->prepareMemcachedBagOStuffFromParams( $params );
		}

		// Normalization and DI for MultiWriteBagOStuff
		if ( is_a( $class, MultiWriteBagOStuff::class, true ) ) {
			$this->prepareMultiWriteBagOStuffFromParams( $params );
		}
		if ( is_a( $class, RESTBagOStuff::class, true ) ) {
			$this->prepareRESTBagOStuffFromParams( $params );
		}

		return new $class( $params );
	}

	private function prepareSqlBagOStuffFromParams( array &$params ): void {
		if ( isset( $params['globalKeyLB'] ) ) {
			throw new InvalidArgumentException(
				'globalKeyLB in $wgObjectCaches is no longer supported' );
		}
		if ( isset( $params['server'] ) && !isset( $params['servers'] ) ) {
			$params['servers'] = [ $params['server'] ];
			unset( $params['server'] );
		}
		if ( isset( $params['servers'] ) ) {
			// In the past it was not required to set 'dbDirectory' in $wgObjectCaches
			foreach ( $params['servers'] as &$server ) {
				if ( $server['type'] === 'sqlite' && !isset( $server['dbDirectory'] ) ) {
					$server['dbDirectory'] = $this->options->get( MainConfigNames::SQLiteDataDir );
				}
			}
		} elseif ( isset( $params['cluster'] ) ) {
			$cluster = $params['cluster'];
			$dbLbFactory = $this->dbLoadBalancerFactory;
			$params['loadBalancerCallback'] = static function () use ( $cluster, $dbLbFactory ) {
				return $dbLbFactory()->getExternalLB( $cluster );
			};
			$params += [ 'dbDomain' => false ];
		} else {
			$dbLbFactory = $this->dbLoadBalancerFactory;
			$params['loadBalancerCallback'] = static function () use ( $dbLbFactory ) {
				return $dbLbFactory()->getMainLb();
			};
			$params += [ 'dbDomain' => false ];
		}
		$params += [ 'writeBatchSize' => $this->options->get( MainConfigNames::UpdateRowsPerQuery ) ];
	}

	private function prepareMemcachedBagOStuffFromParams( array &$params ): void {
		$params += [
			'servers' => $this->options->get( MainConfigNames::MemCachedServers ),
			'persistent' => $this->options->get( MainConfigNames::MemCachedPersistent ),
			'timeout' => $this->options->get( MainConfigNames::MemCachedTimeout ),
		];
	}

	private function prepareMultiWriteBagOStuffFromParams( array &$params ): void {
		// Phan warns about foreach with non-array because it
		// thinks any key can be Closure|IBufferingStatsdDataFactory
		'@phan-var array{caches:array[]} $params';
		foreach ( $params['caches'] ?? [] as $i => $cacheInfo ) {
			// Ensure logger, keyspace, asyncHandler, etc are injected just as if
			// one of these was configured without MultiWriteBagOStuff.
			$params['caches'][$i] = $this->newFromParams( $cacheInfo );
		}
	}

	private function prepareRESTBagOStuffFromParams( array &$params ): void {
		$params['telemetry'] = Telemetry::getInstance();
	}

	/**
	 * Factory function for CACHE_ACCEL (referenced from configuration)
	 *
	 * This will look for any APC or APCu style server-local cache.
	 * A fallback cache can be specified if none is found.
	 *
	 *     // Direct calls
	 *     ObjectCache::getLocalServerInstance( $fallbackType );
	 *
	 *     // From $wgObjectCaches via newFromParams()
	 *     ObjectCache::getLocalServerInstance( [ 'fallback' => $fallbackType ] );
	 *
	 * @param int|string|array $fallback Fallback cache or parameter map with 'fallback'
	 * @return BagOStuff
	 * @throws InvalidArgumentException
	 */
	public function getLocalServerInstance( $fallback = CACHE_NONE ): BagOStuff {
		$cache = $this->getInstance( CACHE_ACCEL );
		if ( $cache instanceof EmptyBagOStuff ) {
			if ( is_array( $fallback ) ) {
				$fallback = $fallback['fallback'] ?? CACHE_NONE;
			}
			$cache = $this->getInstance( $fallback );
		}

		return $cache;
	}

	/**
	 * Clear all the cached instances.
	 */
	public function clear(): void {
		$this->instances = [];
	}

	/**
	 * @internal For tests ONLY.
	 *
	 * @param string|int $cacheId
	 * @param BagOStuff $cache
	 * @return void
	 */
	public function setInstanceForTesting( $cacheId, BagOStuff $cache ): void {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new LogicException( __METHOD__ . ' can not be called outside of tests' );
		}
		$this->instances[$cacheId] = $cache;
	}
}
