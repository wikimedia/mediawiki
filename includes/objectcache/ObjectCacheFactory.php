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
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\Logger\Spi;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use Wikimedia\ObjectCache\APCUBagOStuff;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\EmptyBagOStuff;
use Wikimedia\ObjectCache\HashBagOStuff;
use Wikimedia\ObjectCache\MemcachedBagOStuff;
use Wikimedia\ObjectCache\MultiWriteBagOStuff;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\Telemetry\TracerInterface;

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
 * - ObjectCacheFactory::getLocalClusterInstance()
 *    Purpose: Memory storage for per-cluster coordination and tracking.
 *    A typical use case would be a rate limit counter or cache regeneration mutex.
 *    Stored centrally within the local data-center. Not replicated to other DCs.
 *    Configured by $wgMainCacheType.
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
		MainConfigNames::MessageCacheType,
		MainConfigNames::ParserCacheType,
	];

	private ServiceOptions $options;
	private StatsFactory $stats;
	private Spi $logger;
	private TracerInterface $telemetry;
	/** @var BagOStuff[] */
	private $instances = [];
	private string $domainId;
	/** @var callable */
	private $dbLoadBalancerFactory;
	/**
	 * @internal ObjectCacheFactoryTest only
	 * @var class-string<BagOStuff>
	 */
	public static $localServerCacheClass;

	public function __construct(
		ServiceOptions $options,
		StatsFactory $stats,
		Spi $loggerSpi,
		callable $dbLoadBalancerFactory,
		string $domainId,
		TracerInterface $telemetry
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->stats = $stats;
		$this->logger = $loggerSpi;
		$this->dbLoadBalancerFactory = $dbLoadBalancerFactory;
		$this->domainId = $domainId;
		$this->telemetry = $telemetry;
	}

	/**
	 * Get the default keyspace for this wiki.
	 *
	 * This is either the value of the MainConfigNames::CachePrefix setting
	 * or (if the former is unset) the MainConfigNames::DBname setting, with
	 * MainConfigNames::DBprefix (if defined).
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
			$id = $this->getAnythingId();
		}

		if ( !isset( $this->options->get( MainConfigNames::ObjectCaches )[$id] ) ) {
			// Always recognize these
			if ( $id === CACHE_NONE ) {
				return new EmptyBagOStuff();
			} elseif ( $id === CACHE_HASH ) {
				return new HashBagOStuff();
			} elseif ( $id === CACHE_ACCEL ) {
				return self::makeLocalServerCache( $this->getDefaultKeyspace() );
			} elseif ( $id === 'wincache' ) {
				wfDeprecated( __METHOD__ . ' with cache ID "wincache"', '1.43' );
				return self::makeLocalServerCache( $this->getDefaultKeyspace() );
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
	 * Create a new cache object from parameters specification supplied.
	 *
	 * @internal Using this method directly outside of MediaWiki core
	 *   is discouraged. Use getInstance() instead and supply the ID
	 *   of the cache instance to be looked up.
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
			'telemetry' => $this->telemetry,
		];

		if ( isset( $params['factory'] ) ) {
			$args = $params['args'] ?? [ $params ];

			return $params['factory']( ...$args );
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
			// one of these was configured without MultiWriteBagOStuff (T318272)
			$params['caches'][$i] = $this->newFromParams( $cacheInfo );
		}
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
	 * Get the class which will be used for the local server cache
	 * @return class-string<BagOStuff>
	 */
	private static function getLocalServerCacheClass() {
		if ( self::$localServerCacheClass !== null ) {
			return self::$localServerCacheClass;
		}
		if ( function_exists( 'apcu_fetch' ) ) {
			// Make sure the APCu methods actually store anything
			if ( PHP_SAPI !== 'cli' || ini_get( 'apc.enable_cli' ) ) {
				return APCUBagOStuff::class;

			}
		}

		return EmptyBagOStuff::class;
	}

	/**
	 * Get the ID that will be used for CACHE_ANYTHING
	 *
	 * @internal
	 * @return string|int
	 */
	public function getAnythingId() {
		$candidates = [
			$this->options->get( MainConfigNames::MainCacheType ),
			$this->options->get( MainConfigNames::MessageCacheType ),
			$this->options->get( MainConfigNames::ParserCacheType )
		];
		foreach ( $candidates as $candidate ) {
			if ( $candidate === CACHE_ACCEL ) {
				// CACHE_ACCEL might default to nothing if no APCu
				// See includes/ServiceWiring.php
				$class = self::getLocalServerCacheClass();
				if ( $class !== EmptyBagOStuff::class ) {
					return $candidate;
				}
			} elseif ( $candidate !== CACHE_NONE && $candidate !== CACHE_ANYTHING ) {
				return $candidate;
			}
		}

		$services = MediaWikiServices::getInstance();

		if ( $services->isServiceDisabled( 'DBLoadBalancer' ) ) {
			// The DBLoadBalancer service is disabled, so we can't use the database!
			$candidate = CACHE_NONE;
		} elseif ( $services->isStorageDisabled() ) {
			// Storage services are disabled because MediaWikiServices::disableStorage()
			// was called. This is typically the case during installation.
			$candidate = CACHE_NONE;
		} else {
			$candidate = CACHE_DB;
		}
		return $candidate;
	}

	/**
	 * Create a new BagOStuff instance for local-server caching.
	 *
	 * Only use this if you explicitly require the creation of
	 * a fresh instance. Whenever possible, use or inject the object
	 * from MediaWikiServices::getLocalServerObjectCache() instead.
	 *
	 * NOTE: This method is called very early via Setup.php by ExtensionRegistry,
	 * and thus must remain fairly standalone so as to not cause initialization
	 * of the MediaWikiServices singleton.
	 *
	 * @internal For use by ServiceWiring and ExtensionRegistry. There are use
	 *    cases whereby we want to build up local server cache without service
	 *    wiring available.
	 * @since 1.43, previously on ObjectCache.php since 1.35
	 *
	 * @param string $keyspace
	 * @return BagOStuff
	 */
	public static function makeLocalServerCache( string $keyspace ) {
		$params = [
			'reportDupes' => false,
			// Even simple caches must use a keyspace (T247562)
			'keyspace' => $keyspace,
		];
		$class = self::getLocalServerCacheClass();
		return new $class( $params );
	}

	/**
	 * Determine whether a config ID would access the database
	 *
	 * @internal For use by ServiceWiring.php
	 * @param string|int $id A key in $wgObjectCaches
	 * @return bool
	 */
	public function isDatabaseId( $id ) {
		// NOTE: Sanity check if $id is set to CACHE_ANYTHING and
		// everything is going through service wiring. CACHE_ANYTHING
		// would default to CACHE_DB, let's handle that early for cases
		// where all cache configs are set to CACHE_ANYTHING (T362686).
		if ( $id === CACHE_ANYTHING ) {
			$id = $this->getAnythingId();
			return $this->isDatabaseId( $id );
		}

		if ( !isset( $this->options->get( MainConfigNames::ObjectCaches )[$id] ) ) {
			return false;
		}
		$cache = $this->options->get( MainConfigNames::ObjectCaches )[$id];
		if ( ( $cache['class'] ?? '' ) === SqlBagOStuff::class ) {
			return true;
		}

		return false;
	}

	/**
	 * Get the main cluster-local cache object.
	 *
	 * @since 1.43, previously on ObjectCache.php since 1.27
	 * @return BagOStuff
	 */
	public function getLocalClusterInstance() {
		return $this->getInstance(
			$this->options->get( MainConfigNames::MainCacheType )
		);
	}
}
