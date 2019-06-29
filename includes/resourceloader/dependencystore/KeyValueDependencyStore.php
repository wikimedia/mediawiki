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

use BagOStuff;
use InvalidArgumentException;

/**
 * Lightweight class for tracking path dependencies lists via an object cache instance
 *
 * This does not throw DependencyStoreException due to I/O errors since it is optimized for
 * speed and availability. Read methods return empty placeholders on failure. Write methods
 * might issue I/O in the background and return immediately. However, reads methods will at
 * least block on the resolution (success/failure) of any such pending writes.
 *
 * @since 1.35
 */
class KeyValueDependencyStore extends DependencyStore {
	/** @var BagOStuff */
	private $stash;

	/**
	 * @param BagOStuff $stash Storage backend
	 */
	public function __construct( BagOStuff $stash ) {
		$this->stash = $stash;
	}

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

	public function remove( $type, $entities ) {
		$keys = [];
		foreach ( (array)$entities as $entity ) {
			$keys[] = $this->getStoreKey( $type, $entity );
		}

		if ( $keys ) {
			$this->stash->deleteMulti( $keys, BagOStuff::WRITE_BACKGROUND );
		}
	}

	public function renew( $type, $entities, $ttl ) {
		$keys = [];
		foreach ( (array)$entities as $entity ) {
			$keys[] = $this->getStoreKey( $type, $entity );
		}

		if ( $keys ) {
			$this->stash->changeTTLMulti( $keys, $ttl, BagOStuff::WRITE_BACKGROUND );
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
