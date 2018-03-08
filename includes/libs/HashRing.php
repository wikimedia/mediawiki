<?php
/**
 * Convenience class for weighted consistent hash rings.
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
 */

/**
 * Convenience class for weighted consistent hash rings
 *
 * @since 1.22
 */
class HashRing {
	/** @var array (location => weight) */
	protected $sourceMap = [];
	/** @var array (sector => (start, end, location)) */
	protected $ring = [];
	/** @var int */
	protected $type;

	/** @var HashRing|null */
	protected $liveRing;
	/** @var array (location => UNIX timestamp) */
	protected $ejectionExpiries = [];
	/** @var int UNIX timestamp */
	protected $ejectionNextExpiry = INF;

	/** @var int Allow arbitrary weights; removal of locations causes heavy re-mapping */
	const TYPE_LEGACY = 0;
	/** @var int Weights are on a 0-10 scale; removal of locations has minimal re-mapping */
	const TYPE_CONSISTENT = 1;

	const RING_SIZE = 268435456; // 2^28
	const MAX_WEIGHT = 10;

	/**
	 * @param array $map (location => weight)
	 * @param int $type TYPE_* class constant
	 */
	public function __construct( array $map, $type = self::TYPE_LEGACY ) {
		$map = array_filter( $map, function ( $w ) {
			return $w > 0;
		} );
		if ( !count( $map ) ) {
			throw new UnexpectedValueException( "Ring is empty or all weights are zero." );
		}
		$this->sourceMap = $map;
		if ( $type === self::TYPE_CONSISTENT ) {
			$this->ring = $this->makeConsistentRing( $map );
		} else {
			$this->ring = $this->makeLegacyRing( $map );
		}
	}

	/**
	 * @param array $map (location => weight)
	 * @return HashRing
	 */
	public static function newConsistent( array $map ) {
		return new self( $map, self::TYPE_CONSISTENT );
	}

	/**
	 * @param int[] $map
	 * @return int[]
	 */
	private function makeConsistentRing( $map ) {
		$locationSectors = [];
		// Map the number sectors pointing to a location based on weight
		foreach ( $map as $location => $weight ) {
			// Weight is fitted to a 1-10 scale
			$weight = max( 1, min( self::MAX_WEIGHT, $weight ) );
			for ( $i = 1; $i <= $weight; ++$i ) {
				$sector = "$i@$location";
				$spot = hexdec( substr( sha1( $sector ), 0, 7 ) ); // first 28 bits
				$locationSectors[] = [ $spot, $sector, $location ];
			}
		}
		// Sort the locations based on the hash ring position
		usort( $locationSectors, function ( $a, $b ) {
			if ( $a[0] === $b[0] ) {
				return 0;
			}

			return ( $a[0] < $b[0] ? -1 : 1 );
		} );
		// Build a ring of RING_SIZE spots, with each location at a spot in location hash order
		$ring = [];
		foreach ( $locationSectors as $i => $info ) {
			list( $spot, $sector, $location ) = $info;
			// Location covers half-closed interval [$index,$index + $weight)
			$nextSpot = isset( $locationSectors[$i + 1] )
				? $locationSectors[$i + 1][0]
				: self::RING_SIZE + $locationSectors[0][0];
			$ring[$sector] = [ $spot, $nextSpot, $location ];
		}

		return $ring;
	}

	/**
	 * @param int[] $map
	 * @return int[]
	 */
	private function makeLegacyRing( $map ) {
		// Sort the locations based on the hash of their names
		$hashes = [];
		foreach ( $map as $location => $weight ) {
			$hashes[$location] = sha1( $location );
		}
		uksort( $map, function ( $a, $b ) use ( $hashes ) {
			return strcmp( $hashes[$a], $hashes[$b] );
		} );
		// Fit the map to weight-proportionate one with a space of size RING_SIZE
		$sum = array_sum( $map );
		$standardMap = [];
		foreach ( $map as $location => $weight ) {
			$standardMap[$location] = (int)floor( $weight / $sum * self::RING_SIZE );
		}
		// Build a ring of RING_SIZE spots, with each location at a spot in location hash order
		$ring = [];
		$index = 0;
		foreach ( $standardMap as $location => $weight ) {
			// Location covers half-closed interval [$index,$index + $weight)
			$ring[$location] = [ $index, $index + $weight, $location ];
			$index += $weight;
		}
		// Make sure the last location covers what is left
		end( $ring );
		$ring[key( $ring )][1] = self::RING_SIZE;

		return $ring;
	}

	/**
	 * Get the location of an item on the ring
	 *
	 * @param string $item
	 * @return string Location
	 */
	public function getLocation( $item ) {
		$locations = $this->getLocations( $item, 1 );

		return $locations[0];
	}

	/**
	 * Get the location of an item on the ring, as well as the next locations
	 *
	 * @param string $item
	 * @param int $limit Maximum number of locations to return
	 * @return array List of locations
	 */
	public function getLocations( $item, $limit ) {
		$spot = hexdec( substr( sha1( $item ), 0, 7 ) ); // first 28 bits

		$locations = [];
		$startingSector = null;

		// Search for sector the item belongs to in sector order
		foreach ( $this->ring as $sector => $info ) {
			if ( count( $locations ) >= $limit ) {
				break;
			}
			list( $start, $end, $location ) = $info;
			// The $startingSector is the location the item spot is in.
			if ( $startingSector === null && ( $start <= $spot && $spot < $end ) ) {
				$startingSector = $sector;
				$locations[] = $location;
				// After that is reached, keep appending the next locations.
				// Skip sectors that map to the same location since these are for fail-over.
			} elseif ( $startingSector !== null && !in_array( $location, $locations ) ) {
				$locations[] = $location;
			}
		}
		// If the item was not found above, it must be where the last sector wraps-around
		if ( $startingSector === null ) {
			$info = end( $this->ring );
			list( , , $location ) = $info;
			$startingSector = key( $this->ring );
			$locations[] = $location;
		}
		// If more locations are requested, wrap-around and keep adding them
		foreach ( $this->ring as $sector => $info ) {
			if ( count( $locations ) >= $limit ) {
				break;
			}
			list( , , $location ) = $info;
			if ( $sector === $startingSector ) {
				break; // don't go in circles
			}
			$locations[] = $location;
		}

		return $locations;
	}

	/**
	 * Get the map of locations to weight (ignores 0-weight items)
	 *
	 * @return array
	 */
	public function getLocationWeights() {
		return $this->sourceMap;
	}

	/**
	 * Get a new hash ring with a location removed from the ring
	 *
	 * @param string $location
	 * @return HashRing|bool Returns false if no non-zero weighted spots are left
	 */
	public function newWithoutLocation( $location ) {
		$map = $this->sourceMap;
		unset( $map[$location] );

		return count( $map ) ? new self( $map, $this->type ) : false;
	}

	/**
	 * Remove a location from the "live" hash ring
	 *
	 * @param string $location
	 * @param int $ttl Seconds
	 * @return bool Whether some non-ejected locations are left
	 */
	public function ejectFromLiveRing( $location, $ttl ) {
		if ( !isset( $this->sourceMap[$location] ) ) {
			throw new UnexpectedValueException( "No location '$location' in the ring." );
		}
		$expiry = time() + $ttl;
		$this->liveRing = null; // stale
		$this->ejectionExpiries[$location] = $expiry;
		$this->ejectionNextExpiry = min( $expiry, $this->ejectionNextExpiry );

		return ( count( $this->ejectionExpiries ) < count( $this->sourceMap ) );
	}

	/**
	 * Get the "live" hash ring (which does not include ejected locations)
	 *
	 * @return HashRing
	 * @throws UnexpectedValueException
	 */
	public function getLiveRing() {
		$now = time();
		if ( $this->liveRing === null || $this->ejectionNextExpiry <= $now ) {
			$this->ejectionExpiries = array_filter(
				$this->ejectionExpiries,
				function ( $expiry ) use ( $now ) {
					return ( $expiry > $now );
				}
			);
			if ( count( $this->ejectionExpiries ) ) {
				$map = array_diff_key( $this->sourceMap, $this->ejectionExpiries );
				$this->liveRing = count( $map ) ? new self( $map, $this->type ) : false;

				$this->ejectionNextExpiry = min( $this->ejectionExpiries );
			} else { // common case; avoid recalculating ring
				$this->liveRing = clone $this;
				$this->liveRing->ejectionExpiries = [];
				$this->liveRing->ejectionNextExpiry = INF;
				$this->liveRing->liveRing = null;

				$this->ejectionNextExpiry = INF;
			}
		}
		if ( !$this->liveRing ) {
			throw new UnexpectedValueException( "The live ring is currently empty." );
		}

		return $this->liveRing;
	}

	/**
	 * Get the location of an item on the "live" ring
	 *
	 * @param string $item
	 * @return string Location
	 * @throws UnexpectedValueException
	 */
	public function getLiveLocation( $item ) {
		return $this->getLiveRing()->getLocation( $item );
	}

	/**
	 * Get the location of an item on the "live" ring, as well as the next locations
	 *
	 * @param string $item
	 * @param int $limit Maximum number of locations to return
	 * @return array List of locations
	 * @throws UnexpectedValueException
	 */
	public function getLiveLocations( $item, $limit ) {
		return $this->getLiveRing()->getLocations( $item, $limit );
	}

	/**
	 * Get the map of "live" locations to weight (ignores 0-weight items)
	 *
	 * @return array
	 * @throws UnexpectedValueException
	 */
	public function getLiveLocationWeights() {
		return $this->getLiveRing()->getLocationWeights();
	}
}
