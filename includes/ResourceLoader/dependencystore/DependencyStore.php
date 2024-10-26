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

namespace Wikimedia\DependencyStore;

use InvalidArgumentException;
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
	 * @param string $type Entity type
	 * @param string $entity Entity name
	 * @return array Map of (paths: paths, asOf: UNIX timestamp or null)
	 */
	final public function retrieve( $type, $entity ) {
		return $this->retrieveMulti( $type, [ $entity ] )[$entity];
	}

	/**
	 * Get the currently tracked dependencies for a set of entities
	 *
	 * @param string $type Entity type
	 * @param string[] $entities Entity names
	 * @return array[] Map of (entity => (paths: paths, asOf: UNIX timestamp or null))
	 */
	public function retrieveMulti( $type, array $entities ) {
		$entitiesByKey = [];
		foreach ( $entities as $entity ) {
			$entitiesByKey[$this->getStoreKey( $type, $entity )] = $entity;
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

	/**
	 * Set the currently tracked dependencies for an entity
	 *
	 * @param string $type Entity type
	 * @param string $entity Entity name
	 * @param array $data Map of (paths: paths, asOf: UNIX timestamp or null)
	 * @param int $ttl New time-to-live in seconds
	 */
	final public function store( $type, $entity, array $data, $ttl ) {
		$this->storeMulti( $type, [ $entity => $data ], $ttl );
	}

	/**
	 * Set the currently tracked dependencies for a set of entities
	 *
	 * @param string $type Entity type
	 * @param array[] $dataByEntity Map of (entity => (paths: paths, asOf: UNIX timestamp or null))
	 * @param int $ttl New time-to-live in seconds
	 *
	 */
	public function storeMulti( $type, array $dataByEntity, $ttl ) {
		$blobsByKey = [];
		foreach ( $dataByEntity as $entity => $data ) {
			if ( !is_array( $data[self::KEY_PATHS] ) || !is_int( $data[self::KEY_AS_OF] ) ) {
				throw new InvalidArgumentException( "Invalid entry for '$entity'" );
			}

			// Normalize the list by removing duplicates and sorting
			$data[self::KEY_PATHS] = array_values( array_unique( $data[self::KEY_PATHS] ) );
			sort( $data[self::KEY_PATHS], SORT_STRING );

			$blob = json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
			$blobsByKey[$this->getStoreKey( $type, $entity )] = $blob;
		}

		if ( $blobsByKey ) {
			$this->stash->setMulti( $blobsByKey, $ttl, BagOStuff::WRITE_BACKGROUND );
		}
	}

	/**
	 * Delete the currently tracked dependencies for an entity or set of entities
	 *
	 * @param string $type Entity type
	 * @param string|string[] $entities Entity name(s)
	 */
	public function remove( $type, $entities ) {
		$keys = [];
		foreach ( (array)$entities as $entity ) {
			$keys[] = $this->getStoreKey( $type, $entity );
		}

		if ( $keys ) {
			$this->stash->deleteMulti( $keys, BagOStuff::WRITE_BACKGROUND );
		}
	}

	/**
	 * @param string $type
	 * @param string $entity
	 * @return string
	 */
	private function getStoreKey( $type, $entity ) {
		return $this->stash->makeKey( "{$type}-dependencies", $entity );
	}
}
