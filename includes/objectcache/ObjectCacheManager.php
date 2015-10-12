<?php
/**
 * Factory for object cache services.
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
 * @ingroup Cache
 */

use MediaWiki\Logger\LoggerFactory;
use Wikimedia\Assert\Assert;

/**
 * Factory for object cache services.
 *
 * The word "cache" has two main dictionary meanings, and both
 * are used in this factory class. They are:
 *
 *   - a) Cache (the computer science definition).
 *        A place to store copies or computations on existing data for
 *        higher access speeds.
 *   - b) Storage.
 *        A place to store lightweight data that is not canonically
 *        stored anywhere else (e.g. a "hoard" of objects).
 *
 * The former should always use strongly consistent stores, so callers don't
 * have to deal with stale reads. The later may be eventually consistent, but
 * callers can use BagOStuff:READ_LATEST to see the latest available data.
 *
 * Primary entry points:
 *
 * - ObjectCacheManager::getInstance( $cacheType )
 *   Get a specific cache type by key in $objectCaches.
 *
 * - ObjectCacheManager::getInstance( $cacheType )
 *   Get a specific cache type by key in $wanObjectCaches.
 *
 * All the above cache instances (BagOStuff and WANObjectCache) have their makeKey()
 * method scoped to the wiki ID given to the constructor. Use makeGlobalKey() to avoid
 * this scoping when using keys that need to be shared amongst wikis.
 *
 * @since 1.27
 *
 * @ingroup Cache
 */
class ObjectCacheManager {

	/**
	 * @var string
	 */
	private $keyPrefix;

	/**
	 * @var array[]
	 */
	private $objectCaches;

	/**
	 * @var array[]
	 */
	private $wanObjectCaches;

	/**
	 * @var \MediaWiki\Logger\Spi
	 */
	private $loggerFactory;

	/**
	 * @var int|string
	 */
	private $mainCache = CACHE_NONE;

	/**
	 * @var int|string|boolean
	 */
	private $mainWANCache = false;

	/**
	 * @var int|string
	 */
	private $mainStash = CACHE_DB;

	/**
	 * @var string[]
	 */
	private $anythingCandidates = array();

	/** @var BagOStuff[] Map of (id => BagOStuff) */
	public $instances = array();

	/** @var WANObjectCache[] Map of (id => WANObjectCache) */
	public $wanInstances = array();

	/**
	 * ObjectCacheManager constructor.
	 *
	 * The $objectCaches and $wanObjectCaches contain specs for creating
	 * cache objects. Each entry in these arrays should have 'factory' or
	 * 'class' property.
	 *  - factory: Callback passed $params that returns BagOStuff.
	 *  - class: BagOStuff subclass constructed with $params.
	 *  - loggroup: Alias to set 'logger' key with LoggerFactory group.
	 *  - .. Other parameters passed to factory or class.
	 *
	 * @param string $keyPrefix
	 * @param array[] $objectCaches
	 * @param array[] $wanObjectCaches
	 * @param \MediaWiki\Logger\Spi $loggerFactory
	 */
	public function __construct( //FIXME: TESTME!
		$keyPrefix,
		array $objectCaches,
		array $wanObjectCaches,
		MediaWiki\Logger\Spi $loggerFactory
	) {
		Assert::parameterType( 'string', $keyPrefix, '$keyPrefix' );
		Assert::parameterElementType( 'array', $objectCaches, '$objectCaches' );
		Assert::parameterElementType( 'array', $wanObjectCaches, '$wanObjectCaches' );

		$this->keyPrefix = $keyPrefix;
		$this->objectCaches = $objectCaches;
		$this->wanObjectCaches = $wanObjectCaches;
		$this->loggerFactory = $loggerFactory;
	}

	/**
	 * Sets the cache IDs to be tried by newAnything().
	 *
	 * @param string[] $anythingCandidates
	 */
	public function setAnythingCandidates( array $anythingCandidates ) {
		Assert::parameterElementType( 'string|integer|boolean', $anythingCandidates, '$anythingCandidates' );
		$this->anythingCandidates = $anythingCandidates;
	}

	/**
	 * @param int|string $mainCache A key in the $objectCaches array provided to the constructor.
	 */
	public function setLocalClusterCache( $mainCache ) {
		$this->mainCache = $mainCache;
	}

	/**
	 * @param int|string|boolean $mainWANCache A key in the $wanObjectCaches array provided to the
	 * constructor, or false to use getLocalClusterInstance() for the WAN cache.
	 */
	public function setMainWANCache( $mainWANCache ) {
		$this->mainWANCache = $mainWANCache;
	}

	/**
	 * @param int|string $mainStash A key in the $objectCaches array provided to the constructor.
	 */
	public function setMainStash( $mainStash ) {
		$this->mainStash = $mainStash;
	}

	/**
	 * Get a cached instance of the specified type of cache object.
	 *
	 * @param string $id A key in the $objectCaches array provided to the constructor.
	 * @return BagOStuff
	 */
	public function getInstance( $id ) {
		if ( !isset( $this->instances[$id] ) ) {
			$this->instances[$id] = $this->newFromId( $id );
		}

		return $this->instances[$id];
	}

	/**
	 * Get a cached instance of the specified type of WAN cache object.
	 *
	 * @param string $id A key in the $wanObjectCaches array provided to the constructor.
	 * @return WANObjectCache
	 */
	public function getWANInstance( $id ) {
		if ( !isset( $this->wanInstances[$id] ) ) {
			$this->wanInstances[$id] = $this->newWANCacheFromId( $id );
		}

		return $this->wanInstances[$id];
	}

	/**
	 * Create a new cache object of the specified type.
	 *
	 * @param string $id A key in the $objectCaches array provided to the constructor.
	 * @return BagOStuff
	 * @throws MWException
	 */
	private function newFromId( $id ) {
		if ( !isset( $this->objectCaches[$id] ) ) {
			throw new MWException( "Invalid object cache type \"$id\" requested. " .
				"It is not present in \$this->ObjectCaches." );
		}

		return $this->newFromParams( $this->objectCaches[$id] );
	}

	/**
	 * Get the default keyspace as provided to the constructor.
	 *
	 * @return string
	 */
	public function getDefaultKeyspace() {
		return $this->keyPrefix;
	}

	/**
	 * Create a new cache object from parameters.
	 *
	 * @param array $params Must have 'factory' or 'class' property.
	 *  - factory: Callback passed $params that returns BagOStuff.
	 *  - class: BagOStuff subclass constructed with $params.
	 *  - loggroup: Alias to set 'logger' key with LoggerFactory group.
	 *  - .. Other parameters passed to factory or class.
	 * @return BagOStuff
	 * @throws MWException
	 */
	private function newFromParams( $params ) {
		if ( isset( $params['loggroup'] ) ) {
			$params['logger'] = LoggerFactory::getInstance( $params['loggroup'] );
		} else {
			$params['logger'] = LoggerFactory::getInstance( 'objectcache' );
		}
		if ( !isset( $params['keyspace'] ) ) {
			$params['keyspace'] = $this->getDefaultKeyspace();
		}
		if ( isset( $params['factory'] ) ) {
			return call_user_func( $params['factory'], $params );
		} elseif ( isset( $params['class'] ) ) {
			$class = $params['class'];
			// Automatically set the 'async' update handler
			if ( $class === 'MultiWriteBagOStuff' ) {
				$params['asyncHandler'] = isset( $params['asyncHandler'] )
					? $params['asyncHandler']
					: 'DeferredUpdates::addCallableUpdate';
			}
			// Do b/c logic for MemcachedBagOStuff
			if ( is_subclass_of( $class, 'MemcachedBagOStuff' ) ) {
				if ( !isset( $params['servers'] ) ) {
					$params['servers'] = $GLOBALS['wgMemCachedServers'];
				}
				if ( !isset( $params['debug'] ) ) {
					$params['debug'] = $GLOBALS['wgMemCachedDebug'];
				}
				if ( !isset( $params['persistent'] ) ) {
					$params['persistent'] = $GLOBALS['wgMemCachedPersistent'];
				}
				if ( !isset( $params['timeout'] ) ) {
					$params['timeout'] = $GLOBALS['wgMemCachedTimeout'];
				}
			}
			return new $class( $params );
		} else {
			throw new MWException( "The definition of cache type \""
				. print_r( $params, true ) . "\" lacks both "
				. "factory and class parameters." );
		}
	}

	/**
	 * Factory function for CACHE_ANYTHING (referenced from DefaultSettings.php)
	 *
	 * CACHE_ANYTHING means that stuff has to be cached, not caching is not an option.
	 * If a caching method is configured for any of the main caches ($wgMainCacheType,
	 * $wgMessageCacheType, $wgParserCacheType), then CACHE_ANYTHING will effectively
	 * be an alias to the configured cache choice for that.
	 * If no cache choice is configured (by default $wgMainCacheType is CACHE_NONE),
	 * then CACHE_ANYTHING will forward to CACHE_DB.
	 *
	 * @param array $params
	 * @return BagOStuff
	 */
	public function newAnything( $params ) {
		foreach ( $this->anythingCandidates as $candidate ) {
			if ( $candidate !== CACHE_NONE && $candidate !== CACHE_ANYTHING ) {
				return $this->getInstance( $candidate );
			}
		}
		return $this->getInstance( CACHE_DB );
	}

	/**
	 * Factory function for CACHE_ACCEL (referenced from DefaultSettings.php)
	 *
	 * This will look for any APC style server-local cache.
	 * A fallback cache can be specified if none is found.
	 *
	 *     // Direct calls
	 *     $this->getLocalServerInstance( $fallbackType );
	 *
	 *     // From $wgObjectCaches via newFromParams()
	 *     $this->getLocalServerInstance( array( 'fallback' => $fallbackType ) );
	 *
	 * @param int|string|array $fallback Fallback cache or parameter map with 'fallback'
	 * @return BagOStuff
	 * @throws MWException
	 */
	public function getLocalServerInstance( $fallback = CACHE_NONE ) {
		if ( function_exists( 'apc_fetch' ) ) {
			$id = 'apc';
		} elseif ( function_exists( 'xcache_get' ) && wfIniGetBool( 'xcache.var_size' ) ) {
			$id = 'xcache';
		} elseif ( function_exists( 'wincache_ucache_get' ) ) {
			$id = 'wincache';
		} else {
			if ( is_array( $fallback ) ) {
				$id = isset( $fallback['fallback'] ) ? $fallback['fallback'] : CACHE_NONE;
			} else {
				$id = $fallback;
			}
		}

		return $this->getInstance( $id );
	}

	/**
	 * Create a new cache object of the specified type.
	 *
	 * @param string $id A key in the $wanObjectCache array provided to the constructor.
	 * @return WANObjectCache
	 * @throws MWException
	 */
	private function newWANCacheFromId( $id ) {
		if ( !isset( $this->wanObjectCaches[$id] ) ) {
			throw new MWException( "Invalid object cache type \"$id\" requested. " .
				"It is not present in \$this->wanObjectCaches." );
		}

		$params = $this->wanObjectCaches[$id];
		$class = $params['relayerConfig']['class'];
		$params['relayer'] = new $class( $params['relayerConfig'] );
		$params['cache'] = $this->newFromId( $params['cacheId'] );
		if ( isset( $params['loggroup'] ) ) {
			$params['logger'] = $this->loggerFactory->getLogger( $params['loggroup'] );
		} else {
			$params['logger'] = $this->loggerFactory->getLogger( 'objectcache' );
		}
		$class = $params['class'];

		return new $class( $params );
	}

	/**
	 * Get the main cluster-local cache object.
	 * What cache will be used as the main local cluster cache is defined
	 * by calling setLocalClusterCache().
	 *
	 * @return BagOStuff
	 */
	public function getLocalClusterInstance() {
		return $this->getInstance( $this->mainCache );
	}

	/**
	 * Get the main WAN cache object.
	 * What cache will be used as the main WAN cache is defined by calling setMainWANCache().
	 *
	 * @since 1.26
	 * @return WANObjectCache
	 */
	public function getMainWANInstance() {
		if ( $this->mainWANCache === false ) {
			return $this->getLocalClusterInstance();
		} else {
			return $this->getWANInstance( $this->mainWANCache );
		}
	}

	/**
	 * Get the cache object for the main stash.
	 * What cache will be used as the main stash is defined by calling setMainStash().
	 *
	 * Stash objects are BagOStuff instances suitable for storing light
	 * weight data that is not canonically stored elsewhere (such as RDBMS).
	 * Stashes should be configured to propagate changes to all data-centers.
	 *
	 * Callers should be prepared for:
	 *   - a) Writes to be slower in non-"primary" (e.g. HTTP GET/HEAD only) DCs
	 *   - b) Reads to be eventually consistent, e.g. for get()/getMulti()
	 * In general, this means avoiding updates on idempotent HTTP requests and
	 * avoiding an assumption of perfect serializability (or accepting anomalies).
	 * Reads may be eventually consistent or data might rollback as nodes flap.
	 * Callers can use BagOStuff:READ_LATEST to see the latest available data.
	 *
	 * @return BagOStuff
	 * @since 1.26
	 */
	public function getMainStashInstance() {
		return $this->getInstance( $this->mainStash );
	}

	/**
	 * Clear all the cached instances.
	 */
	public function clear() {
		$this->instances = array();
		$this->wanInstances = array();
	}

}
