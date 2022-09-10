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

/**
 * Track per-module dependency file paths that are expensive to mass compute
 *
 * @internal For use by ResourceLoader\Module only
 */
abstract class DependencyStore {
	/** @var string */
	protected const KEY_PATHS = 'paths';
	/** @var string */
	protected const KEY_AS_OF = 'asOf';

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
	 * @see KeyValueDependencyStore::retrieve()
	 * @param string $type Entity type
	 * @param string[] $entities Entity names
	 * @return array[] Map of (entity => (paths: paths, asOf: UNIX timestamp or null))
	 */
	abstract public function retrieveMulti( $type, array $entities );

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
	 * @see KeyValueDependencyStore::store()
	 * @param string $type Entity type
	 * @param array[] $dataByEntity Map of (entity => (paths: paths, asOf: UNIX timestamp or null))
	 * @param int $ttl New time-to-live in seconds
	 *
	 */
	abstract public function storeMulti( $type, array $dataByEntity, $ttl );

	/**
	 * Delete the currently tracked dependencies for an entity or set of entities
	 *
	 * @param string $type Entity type
	 * @param string|string[] $entities Entity name(s)
	 */
	abstract public function remove( $type, $entities );
}
