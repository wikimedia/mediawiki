<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\DependencyStore;

use InvalidArgumentException;
use MediaWiki\Deferred\DeferredUpdates;
use MediaWiki\MediaWikiServices;
use Wikimedia\ObjectCache\BagOStuff;

/**
 * Track per-module dependency file paths that are expensive to mass compute
 *
 * @internal For use by ResourceLoader\Module only
 */
class DependencyStore {
	protected const KEY_PATHS = 'paths';
	protected const KEY_AS_OF = 'asOf';

	/** @var BagOStuff */
	private $stash;

	/** @var int How long to preserve indirect dependency metadata in our backend store. */
	private const RL_MODULE_DEP_TTL = BagOStuff::TTL_YEAR;

	/** @var array Map of (module-variant => buffered DependencyStore updates) */
	public $updateBuffer = [];

	/**
	 * @param BagOStuff $stash Storage backend
	 */
	public function __construct( BagOStuff $stash ) {
		$this->stash = $stash;
	}

	/**
	 * @param string[] $paths List of dependency paths
	 * @param int|null $asOf UNIX timestamp or null
	 * @return array
	 */
	public function newEntityDependencies( array $paths = [], $asOf = null ) {
		return [ self::KEY_PATHS => $paths, self::KEY_AS_OF => $asOf ];
	}

	/**
	 * Get the currently tracked dependencies for an entity
	 *
	 * The "paths" field contains a sorted list of unique paths
	 *
	 * The "asOf" field reflects the last-modified timestamp of the dependency data itself.
	 * It will be null if there is no tracking data available. Note that if empty path lists
	 * are never stored (as an optimisation) then it will not be possible to discern whether
	 * the result is up-to-date.
	 *
	 * @param string $entity Entity name
	 * @return array Map of (paths: paths, asOf: UNIX timestamp or null)
	 */
	final public function retrieve( $entity ) {
		return $this->retrieveMulti( [ $entity ] )[$entity];
	}

	/**
	 * Get the currently tracked dependencies for a set of entities
	 *
	 * @param string[] $entities Entity names
	 * @return array[] Map of (entity => (paths: paths, asOf: UNIX timestamp or null))
	 */
	public function retrieveMulti( array $entities ) {
		$entitiesByKey = [];
		foreach ( $entities as $entity ) {
			$entitiesByKey[$this->getStoreKey( $entity )] = $entity;
		}

		$blobsByKey = $this->stash->getMulti( array_keys( $entitiesByKey ) );

		$results = [];
		foreach ( $entitiesByKey as $key => $entity ) {
			$blob = $blobsByKey[$key] ?? null;
			$data = is_string( $blob ) ? json_decode( $blob, true ) : null;
			$results[$entity] = $this->newEntityDependencies(
				$data[self::KEY_PATHS] ?? [],
				$data[self::KEY_AS_OF] ?? null
			);
		}

		return $results;
	}

	public function storeMulti( array $pathByEntity ): void {
		$hasPendingUpdate = (bool)$this->updateBuffer;

		foreach ( $pathByEntity as $entity => $paths ) {
			$this->updateBuffer[$entity] = $paths ? $this->newEntityDependencies( $paths, time() ) : null;
		}

		// If paths were unchanged, leave the dependency store unchanged also.
		// The entry will eventually expire, after which we will briefly issue an incomplete
		// version hash for a 5-min startup window, the module then recomputes and rediscovers
		// the paths and arrive at the same module version hash once again. It will churn
		// part of the browser cache once, for clients connecting during that window.

		if ( !$hasPendingUpdate ) {
			DeferredUpdates::addCallableUpdate( function () {
				$updatesByEntity = $this->updateBuffer;
				$this->updateBuffer = [];
				$scopeLocks = [];
				$depsByEntity = [];
				$entitiesUnreg = [];
				$blobsByKey = [];
				$cache = MediaWikiServices::getInstance()
				->getObjectCacheFactory()->getLocalClusterInstance();
				foreach ( $updatesByEntity as $entity => $update ) {
					$lockKey = $cache->makeKey( 'rl-deps', $entity );
					$scopeLocks[$entity] = $cache->getScopedLock( $lockKey, 0 );
					if ( !$scopeLocks[$entity] ) {
						// avoid duplicate write request slams (T124649)
						// the lock must be specific to the current wiki (T247028)
						continue;
					}
					if ( !$update ) {
						$entitiesUnreg[] = $entity;
					} else {
						$depsByEntity[$entity] = $update;
					}
				}

				foreach ( $depsByEntity as $entity => $data ) {
					if ( !is_array( $data[self::KEY_PATHS] ) || !is_int( $data[self::KEY_AS_OF] ) ) {
						throw new InvalidArgumentException( "Invalid entry for '$entity'" );
					}

					// Normalize the list by removing duplicates and sorting
					$data[self::KEY_PATHS] = array_values( array_unique( $data[self::KEY_PATHS] ) );
					sort( $data[self::KEY_PATHS], SORT_STRING );

					$blob = json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

					$blobsByKey[$this->getStoreKey( $entity )] = $blob;
				}

				if ( $blobsByKey ) {
					$this->stash->setMulti( $blobsByKey, self::RL_MODULE_DEP_TTL, BagOStuff::WRITE_BACKGROUND );
				}

				$this->remove( $entitiesUnreg );
			} );
		}
	}

	/**
	 * Delete the currently tracked dependencies for an entity or set of entities
	 *
	 * @param string|string[] $entities Entity name(s)
	 */
	public function remove( $entities ) {
		$keys = [];
		foreach ( (array)$entities as $entity ) {
			$keys[] = $this->getStoreKey( $entity );
		}

		if ( $keys ) {
			$this->stash->deleteMulti( $keys, BagOStuff::WRITE_BACKGROUND );
		}
	}

	/**
	 * @param string $entity
	 * @return string
	 */
	private function getStoreKey( $entity ) {
		return $this->stash->makeKey( "ResourceLoaderModule-dependencies", $entity );
	}
}
