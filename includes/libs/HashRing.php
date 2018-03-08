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
 * This deterministically maps "keys" to a set of "locations" while avoiding clumping
 *
 * Each "location" is assigned 1 to MAX_WEIGHT "sectors" based on the provided weights.
 * The "sectors" are named "<sector number>@<location name>" and are spread around the ring.
 * Each "sector" includes a "starting point" on the ring, based on its name alone. They also
 * have a "stopping point", which is the next "starting point" of a "sector", clockwise. Each
 * "sector" is responsible for all "keys" that fall in its half-closed [start,stop) range.
 *
 * A location that is temporarily "ejected" is said to be absent from the "live" ring.
 * If no location ejections are active, then the main ring and live ring are identical.
 *
 * @since 1.22
 */
class HashRing implements Serializable {
	/** @var string Hashing algorithm for hash() */
	protected $algo = 'sha1';
	/** @var int[] Non-empty (location => number of sectors) */
	protected $sectorsByLocation;
	/** @var int[] Map of (location => UNIX timestamp) */
	protected $ejectExpiryByLocation;

	/** @var array[] Non-empty list of (integer, sector name, location name) */
	protected $ring;
	/** @var HashRing|null Cached version of the ring sans ejected locations */
	protected $liveRing;

	/** Number of points on the ring */
	const RING_SIZE = 268435456; // 2^28

	/** Minimum number of sectors for location */
	const MIN_WEIGHT = 1;
	/** Maxmimum number of sectors for location */
	const MAX_WEIGHT = 100;

	const KEY_BEGIN = 0;
	const KEY_LOCATION = 1;
	const KEY_SECTOR = 2;

	/**
	 * @param int[] $map Map of (location => weight); weight range is [MIN_WEIGHT, MAX_WEIGHT]
	 * @param string $algo Hashing algorithm (sha1, md5, sha256) [optional]
	 * @param int[] $ejections Map of (location => integer UNIX timestamp) [optional]
	 */
	public function __construct( array $map, $algo = 'sha1', array $ejections = [] ) {
		$this->initialize( $map, $algo, $ejections );
	}

	/**
	 * @param int[] $map Map of (location => integer weight)
	 * @param string $algo Hashing algorithm
	 * @param int[] $ejections Map of (location => integer UNIX timestamp)
	 */
	protected function initialize( array $map, $algo, array $ejections ) {
		if ( !in_array( $algo, hash_algos(), true ) ) {
			throw new RuntimeException( __METHOD__ . ": unsupported '$algo' hash algorithm." );
		}

		$sectorsByLocation = [];
		foreach ( $map as $location => $weight ) {
			$sectors = (int)$weight;
			if ( $sectors >= self::MIN_WEIGHT ) {
				$sectorsByLocation[$location] = min( self::MAX_WEIGHT, $sectors );
			}
		}
		if ( $sectorsByLocation === [] ) {
			throw new UnexpectedValueException( "Ring is empty or all weights are zero." );
		}

		$this->algo = $algo;
		$this->sectorsByLocation = $sectorsByLocation;
		$this->ejectExpiryByLocation = $ejections; // import temporary location bans
		$this->ring = self::makeConsistentRing( $this->sectorsByLocation, $this->algo );
	}

	/**
	 * Make a consistent hash ring given a set of locations and their weight values
	 *
	 * Weight values can be anything from HashRing::MIN_WEIGHT to HashRing::MAX_WEIGHT.
	 * For small sets of locations (e.g. < ~32), avoid low weight values (e.g < 10) in
	 * order to greatly reduce the odds of having an "unlucky" lumpy distribution. For
	 * large locations sets, modest weights like ~3-10 lower some hash ring overhead.
	 *
	 * @param int[] $map Map of (location => weight); weight range is [MIN_WEIGHT, MAX_WEIGHT]
	 * @param string $algo Hashing algorithm (sha1, md5, sha256) [optional]
	 * @param int[] $ejections Map of (location => integer UNIX timestamp)
	 * @return HashRing
	 * @since 1.31
	 */
	public static function newConsistent( array $map, $algo = 'sha1', array $ejections = [] ) {
		return new self( $map, $algo, $ejections );
	}

	/**
	 * Get the location of an item on the ring
	 *
	 * @param string $item
	 * @return string Location
	 */
	final public function getLocation( $item ) {
		return $this->getLocations( $item, 1 )[0];
	}

	/**
	 * Get the location of an item on the ring, as well as the next locations
	 *
	 * @param string $item
	 * @param int $limit Maximum number of locations to return
	 * @return string[] List of locations
	 */
	public function getLocations( $item, $limit ) {
		if ( $this->ring === [] ) {
			throw new UnexpectedValueException( __METHOD__ . ': no locations on the ring.' );
		}

		// Locate this item's position on the hash ring
		$itemPos = self::getRingPosition( $item, $this->algo );

		// The ring index is ordered; guess a nearby sector (assuming roughly uniform weights)
		$arcRatio = $itemPos / self::RING_SIZE; // range is [0.0, 1.0]
		$maxIndex = count( $this->ring ) - 1;
		$guessIndex = intval( $maxIndex * $arcRatio );

		$mainSectorIndex = null; // first matching sector index

		if ( $itemPos === $this->ring[$guessIndex][self::KEY_BEGIN] ) {
			// Perfect match on the sector edge
			$mainSectorIndex = $guessIndex;
		} elseif ( $itemPos < $this->ring[$guessIndex][self::KEY_BEGIN] ) {
			// Walk counter-clockwise and stop at the first sector where $itemPos >= position
			do {
				$priorIndex = $guessIndex;
				$guessIndex = $this->getPrevSectorIndex( $guessIndex );
				if (
					$itemPos >= $this->ring[$guessIndex][self::KEY_BEGIN] ||
					$guessIndex > $priorIndex // warped pass 0'clock
				) {
					$mainSectorIndex = $guessIndex;
				}
			} while ( $mainSectorIndex === null );
		} else {
			// Walk clockwise and stop at the sector prior to the one where $itemPos < position
			do {
				$priorIndex = $guessIndex;
				$guessIndex = $this->getNextSectorIndex( $guessIndex );
				if (
					$itemPos < $this->ring[$guessIndex][self::KEY_BEGIN] ||
					$guessIndex < $priorIndex // warped pass 0'clock
				) {
					$mainSectorIndex = $priorIndex;
				}
			} while ( $mainSectorIndex === null );
		}

		if ( $mainSectorIndex === null ) {
			throw new RuntimeException( __METHOD__ . ": no place for '$item' ($itemPos)" );
		}

		$locations = [];
		$currentIndex = $mainSectorIndex;
		while ( count( $locations ) < $limit ) {
			$sectorLocation = $this->ring[$currentIndex][self::KEY_LOCATION];
			if ( !in_array( $sectorLocation, $locations, true ) ) {
				// Ignore other sectors for the same locations already added
				$locations[] = $sectorLocation;
			}
			$currentIndex = $this->getNextSectorIndex( $currentIndex );
			if ( $currentIndex === $mainSectorIndex ) {
				break; // all sectors visited
			}
		}

		return $locations;
	}

	/**
	 * @param int[] $map
	 * @param string $algo Hashing algorithm
	 * @return array[]
	 */
	private static function makeConsistentRing( array $map, $algo ) {
		if ( $map === [] ) {
			throw new InvalidArgumentException( __METHOD__ . ': got empty map.' );
		}

		$ring = [];
		// Assign sectors to all locations based on location weight
		$claimed = []; // (position => sector)
		foreach ( $map as $location => $weight ) {
			// Weight is fitted to a discrete scale
			$sectorCount = max( self::MIN_WEIGHT, min( self::MAX_WEIGHT, $weight ) );
			for ( $i = 1; $i <= $sectorCount; ++$i ) {
				$sector = "$i@$location";
				$position = self::getRingPosition( $sector, $algo );
				if ( isset( $claimed[$position] ) && $claimed[$position] > $sector ) {
					continue; // disallow duplicates for sanity
				}

				$ring[] = [
					self::KEY_BEGIN => $position,
					self::KEY_SECTOR => $sector,
					self::KEY_LOCATION => $location
				];
				$claimed[$position] = $sector;
			}
		}
		// Sort the locations based on the hash ring position
		usort( $ring, function ( $a, $b ) {
			if ( $a[self::KEY_BEGIN] === $b[self::KEY_BEGIN] ) {
				throw new UnexpectedValueException( 'Duplicate sector positions.' );
			}

			return ( $a[self::KEY_BEGIN] < $b[self::KEY_BEGIN] ? -1 : 1 );
		} );

		return $ring;
	}

	/**
	 * @param string $item Key or location name
	 * @param string $algo Hashing algorithm (28 bits or higher)
	 * @return int Ring position on [0, self::RING_SIZE]
	 */
	private static function getRingPosition( $item, $algo ) {
		$hex28bit = substr( hash( $algo, $item, false ), 0, 7 );
		if ( strlen( $hex28bit ) != 7 ) {
			throw new UnexpectedValueException( __METHOD__ . ": digest for '$algo' too small." );
		}

		return (int)hexdec( $hex28bit );
	}

	/**
	 * @param int $i Valid index for a sector in the ring
	 * @return int Valid index for a sector in the ring
	 */
	private function getNextSectorIndex( $i ) {
		if ( !isset( $this->ring[$i] ) ) {
			throw new InvalidArgumentException( __METHOD__ . ": reference index is invalid." );
		}

		$next = $i + 1;

		return ( $next < count( $this->ring ) ) ? $next : 0;
	}

	/**
	 * @param int $i Valid index for a sector in the ring
	 * @return int Valid index for a sector in the ring
	 */
	private function getPrevSectorIndex( $i ) {
		if ( !isset( $this->ring[$i] ) ) {
			throw new InvalidArgumentException( __METHOD__ . ": reference index is invalid." );
		}

		$prev = $i - 1;

		return ( $prev >= 0 ) ? $prev : count( $this->ring ) - 1;
	}

	/**
	 * Get the map of locations to weight (does not include zero weight items)
	 *
	 * @return int[]
	 */
	public function getLocationWeights() {
		return $this->sectorsByLocation;
	}

	/**
	 * Remove a location from the "live" hash ring
	 *
	 * @param string $location
	 * @param int $ttl Seconds
	 * @return bool Whether some non-ejected locations are left
	 */
	public function ejectFromLiveRing( $location, $ttl ) {
		if ( !isset( $this->sectorsByLocation[$location] ) ) {
			throw new UnexpectedValueException( "No location '$location' in the ring." );
		}

		$expiry = $this->getCurrentTime() + $ttl;
		$this->ejectExpiryByLocation[$location] = $expiry;

		$this->liveRing = null; // invalidate ring cache

		return ( count( $this->ejectExpiryByLocation ) < count( $this->sectorsByLocation ) );
	}

	/**
	 * Get the "live" hash ring (which does not include ejected locations)
	 *
	 * @return HashRing
	 * @throws UnexpectedValueException
	 */
	protected function getLiveRing() {
		$liveRingExpiry = $this->ejectExpiryByLocation
			? min( $this->ejectExpiryByLocation )
			: INF;

		$now = $this->getCurrentTime();

		if ( $this->liveRing === null || $liveRingExpiry <= $now ) {
			// Live ring needs to be regerenated...
			$this->ejectExpiryByLocation = array_filter(
				$this->ejectExpiryByLocation,
				function ( $expiry ) use ( $now ) {
					return ( $expiry > $now );
				}
			);
			if ( count( $this->ejectExpiryByLocation ) ) {
				// Complex case: some locations are still ejected from the ring
				$map = array_diff_key( $this->sectorsByLocation, $this->ejectExpiryByLocation );
				// Cache negative result of nothing being available
				$this->liveRing = count( $map ) ? new self( $map, $this->algo ) : false;
			} else {
				// Common case: avoid recalculating ring
				$this->liveRing = clone $this;
				$this->liveRing->ejectExpiryByLocation = [];
				$this->liveRing->liveRing = null;
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
	final public function getLiveLocation( $item ) {
		return $this->getLiveLocations( $item, 1 )[0];
	}

	/**
	 * Get the location of an item on the "live" ring, as well as the next locations
	 *
	 * @param string $item
	 * @param int $limit Maximum number of locations to return
	 * @return string[] List of locations
	 * @throws UnexpectedValueException
	 */
	public function getLiveLocations( $item, $limit ) {
		return $this->getLiveRing()->getLocations( $item, $limit );
	}

	/**
	 * Get the map of "live" locations to weight (does not include zero weight items)
	 *
	 * @return int[]
	 * @throws UnexpectedValueException
	 */
	public function getLiveLocationWeights() {
		return $this->getLiveRing()->getLocationWeights();
	}

	/**
	 * @return int UNIX timestamp
	 */
	protected function getCurrentTime() {
		return time();
	}

	public function serialize() {
		return serialize( [
			'algorithm' => $this->algo,
			'locations' => $this->sectorsByLocation,
			'ejections' => $this->ejectExpiryByLocation
		] );
	}

	public function unserialize( $serialized ) {
		$data = unserialize( $serialized );
		if ( is_array( $data ) ) {
			$this->initialize( $data['locations'], $data['algorithm'], $data['ejections'] );
		} else {
			throw new UnexpectedValueException( __METHOD__ . ": unable to decode JSON." );
		}
	}
}
