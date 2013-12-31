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
 * @author Aaron Schulz
 */

/**
 * Convenience class for weighted consistent hash rings
 *
 * @since 1.22
 */
class HashRing {
	/** @var Array (location => weight) */
	protected $sourceMap = array();
	/** @var Array (location => (start, end)) */
	protected $ring = array();

	const RING_SIZE = 268435456; // 2^28

	/**
	 * @param array $map (location => weight)
	 */
	public function __construct( array $map ) {
		$map = array_filter( $map, function ( $w ) {
			return $w > 0;
		} );
		if ( !count( $map ) ) {
			throw new UnexpectedValueException( "Ring is empty or all weights are zero." );
		}
		$this->sourceMap = $map;
		// Sort the locations based on the hash of their names
		$hashes = array();
		foreach ( $map as $location => $weight ) {
			$hashes[$location] = sha1( $location );
		}
		uksort( $map, function ( $a, $b ) use ( $hashes ) {
			return strcmp( $hashes[$a], $hashes[$b] );
		} );
		// Fit the map to weight-proportionate one with a space of size RING_SIZE
		$sum = array_sum( $map );
		$standardMap = array();
		foreach ( $map as $location => $weight ) {
			$standardMap[$location] = (int)floor( $weight / $sum * self::RING_SIZE );
		}
		// Build a ring of RING_SIZE spots, with each location at a spot in location hash order
		$index = 0;
		foreach ( $standardMap as $location => $weight ) {
			// Location covers half-closed interval [$index,$index + $weight)
			$this->ring[$location] = array( $index, $index + $weight );
			$index += $weight;
		}
		// Make sure the last location covers what is left
		end( $this->ring );
		$this->ring[key( $this->ring )][1] = self::RING_SIZE;
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
	 * Get the location of an item on the ring, as well as the next clockwise locations
	 *
	 * @param string $item
	 * @param integer $limit Maximum number of locations to return
	 * @return array List of locations
	 */
	public function getLocations( $item, $limit ) {
		$locations = array();
		$primaryLocation = null;
		$spot = hexdec( substr( sha1( $item ), 0, 7 ) ); // first 28 bits
		foreach ( $this->ring as $location => $range ) {
			if ( count( $locations ) >= $limit ) {
				break;
			}
			// The $primaryLocation is the location the item spot is in.
			// After that is reached, keep appending the next locations.
			if ( ( $range[0] <= $spot && $spot < $range[1] ) || $primaryLocation !== null ) {
				if ( $primaryLocation === null ) {
					$primaryLocation = $location;
				}
				$locations[] = $location;
			}
		}
		// If more locations are requested, wrap-around and keep adding them
		reset( $this->ring );
		while ( count( $locations ) < $limit ) {
			list( $location, ) = each( $this->ring );
			if ( $location === $primaryLocation ) {
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
		if ( count( $map ) ) {
			return new self( $map );
		}

		return false;
	}
}
