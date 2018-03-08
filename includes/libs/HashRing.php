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
 * Each location is represented by a number of nodes on a ring proportionate to the ratio
 * of its weight compared to the total location weight. Note positions are deterministically
 * derived from the hash of the location name. Nodes are responsible for the portion of the
 * ring, clounter-clockwise, up until the next node. Locations are responsible for all portions
 * of the ring that the location's nodes are responsible for.
 *
 * A location that is temporarily "ejected" is said to be absent from the "live" ring.
 * If no location ejections are active, then the base ring and live ring are identical.
 *
 * @since 1.22
 */
class HashRing implements Serializable {
	/** @var string Hashing algorithm for hash() */
	protected $algo;
	/** @var int[] Non-empty (location => integer weight) */
	protected $weightByLocation;
	/** @var int[] Map of (location => UNIX timestamp) */
	protected $ejectExpiryByLocation;

	/** @var array[] Non-empty list of (float, node name, location name) */
	protected $baseRing;
	/** @var array[] Non-empty list of (float, node name, location name) */
	protected $liveRing;

	/** @var int|null Number of nodes scanned to place an item last time */
	private $lastNodeScanSize;

	/** @var float Number of positions on the ring */
	const RING_SIZE = 4294967296.0; // 2^32
	/** @var integer Overall number of node groups per server */
	const HASHES_PER_LOCATION = 40;
	/** @var integer Number of nodes in a node group */
	const SECTORS_PER_HASH = 4;

	const KEY_POS = 0;
	const KEY_LOCATION = 1;

	/** @var int Consider all locations */
	const RING_ALL = 0;
	/** @var int Only consider "live" locations */
	const RING_LIVE = 1;

	/**
	 * Make a consistent hash ring given a set of locations and their weight values
	 *
	 * @param int[] $map Map of (location => weight)
	 * @param string $algo Hashing algorithm listed in hash_algos() [optional]
	 * @param int[] $ejections Map of (location => UNIX timestamp) for ejection expiries
	 * @since 1.31
	 */
	public function __construct( array $map, $algo = 'sha1', array $ejections = [] ) {
		$this->init( $map, $algo, $ejections );
	}

	/**
	 * @param int[] $map Map of (location => integer)
	 * @param string $algo Hashing algorithm
	 * @param int[] $ejections Map of (location => UNIX timestamp) for ejection expires
	 */
	protected function init( array $map, $algo, array $ejections ) {
		if ( !in_array( $algo, hash_algos(), true ) ) {
			throw new RuntimeException( __METHOD__ . ": unsupported '$algo' hash algorithm." );
		}

		$weightByLocation = array_filter( $map );
		if ( $weightByLocation === [] ) {
			throw new UnexpectedValueException( "No locations with non-zero weight." );
		} elseif ( min( $map ) < 0 ) {
			throw new InvalidArgumentException( "Location weight cannot be negative." );
		}

		$this->algo = $algo;
		$this->weightByLocation = $weightByLocation;
		$this->ejectExpiryByLocation = $ejections;
		$this->baseRing = $this->buildLocationRing( $this->weightByLocation, $this->algo );
	}

	/**
	 * Get the location of an item on the ring
	 *
	 * @param string $item
	 * @return string Location
	 * @throws UnexpectedValueException
	 */
	final public function getLocation( $item ) {
		return $this->getLocations( $item, 1 )[0];
	}

	/**
	 * Get the location of an item on the ring, as well as the next locations
	 *
	 * @param string $item
	 * @param int $limit Maximum number of locations to return
	 * @param int $from One of the RING_* class constants
	 * @return string[] List of locations
	 * @throws UnexpectedValueException
	 */
	public function getLocations( $item, $limit, $from = self::RING_ALL ) {
		if ( $from === self::RING_ALL ) {
			$ring = $this->baseRing;
		} elseif ( $from === self::RING_LIVE ) {
			$ring = $this->getLiveRing();
		} else {
			throw new InvalidArgumentException( "Invalid ring source specified." );
		}

		// Locate this item's position on the hash ring
		$position = $this->getItemPosition( $item );

		// Guess a nearby node based on the node list being ordered and the probabilistic
		// expected size of nodes being equal, varying less when with higher node counts
		$guessIndex = $this->guessNodeIndexForPosition( $position, $ring );

		// Find the index of the node within which this item resides
		$itemNodeIndex = $this->findNodeIndexForPosition( $position, $guessIndex, $ring );
		if ( $itemNodeIndex === null ) {
			throw new RuntimeException( __METHOD__ . ": no place for '$item' ($position)" );
		}

		$locations = [];
		$currentIndex = $itemNodeIndex;
		while ( count( $locations ) < $limit ) {
			$nodeLocation = $ring[$currentIndex][self::KEY_LOCATION];
			if ( !in_array( $nodeLocation, $locations, true ) ) {
				// Ignore other nodes for the same locations already added
				$locations[] = $nodeLocation;
			}
			$currentIndex = $this->getNextClockwiseNodeIndex( $currentIndex, $ring );
			if ( $currentIndex === $itemNodeIndex ) {
				break; // all nodes visited
			}
		}

		return $locations;
	}

	/**
	 * Get the map of locations to weight (does not include zero weight items)
	 *
	 * @return int[]
	 */
	public function getLocationWeights() {
		return $this->weightByLocation;
	}

	/**
	 * Remove a location from the "live" hash ring
	 *
	 * @param string $location
	 * @param int $ttl Seconds
	 * @return bool Whether some non-ejected locations are left
	 * @throws UnexpectedValueException
	 */
	public function ejectFromLiveRing( $location, $ttl ) {
		if ( !isset( $this->weightByLocation[$location] ) ) {
			throw new UnexpectedValueException( "No location '$location' in the ring." );
		}

		$expiry = $this->getCurrentTime() + $ttl;
		$this->ejectExpiryByLocation[$location] = $expiry;

		$this->liveRing = null; // invalidate ring cache

		return ( count( $this->ejectExpiryByLocation ) < count( $this->weightByLocation ) );
	}

	/**
	 * Get the location of an item on the "live" ring
	 *
	 * @param string $item
	 * @return string Location
	 * @throws UnexpectedValueException
	 */
	final public function getLiveLocation( $item ) {
		return $this->getLocations( $item, 1, self::RING_LIVE )[0];
	}

	/**
	 * Get the location of an item on the "live" ring, as well as the next locations
	 *
	 * @param string $item
	 * @param int $limit Maximum number of locations to return
	 * @return string[] List of locations
	 * @throws UnexpectedValueException
	 */
	final public function getLiveLocations( $item, $limit ) {
		return $this->getLocations( $item, $limit, self::RING_LIVE );
	}

	/**
	 * Get the map of "live" locations to weight (does not include zero weight items)
	 *
	 * @return int[]
	 * @throws UnexpectedValueException
	 */
	public function getLiveLocationWeights() {
		$now = $this->getCurrentTime();

		return array_diff_key(
			$this->weightByLocation,
			array_filter(
				$this->ejectExpiryByLocation,
				function ( $expiry ) use ( $now ) {
					return ( $expiry > $now );
				}
			)
		);
	}

	/**
	 * @param float $position
	 * @param array[] $ring Either the base or live ring
	 * @return int
	 */
	private function guessNodeIndexForPosition( $position, $ring ) {
		$arcRatio = $position / self::RING_SIZE; // range is [0.0, 1.0]
		$maxIndex = count( $ring ) - 1;
		$guessIndex = intval( $maxIndex * $arcRatio );

		$displacement = $ring[$guessIndex][self::KEY_POS] - $position;
		$aveSize = self::RING_SIZE / count( $ring );
		$shift = intval( $displacement / $aveSize );

		$guessIndex -= $shift;
		if ( $guessIndex < 0 ) {
			$guessIndex = max( $maxIndex + $guessIndex, 0 ); // roll-over
		} elseif ( $guessIndex > $maxIndex ) {
			$guessIndex = min( $guessIndex - $maxIndex, 0 ); // roll-over
		}

		return $guessIndex;
	}

	/**
	 * @param float $position
	 * @param int $guessIndex Node index to start scanning
	 * @param array[] $ring Either the base or live ring
	 * @return int|null
	 */
	private function findNodeIndexForPosition( $position, $guessIndex, $ring ) {
		$mainNodeIndex = null; // first matching node index

		$this->lastNodeScanSize = 0;

		if ( $ring[$guessIndex][self::KEY_POS] >= $position ) {
			// Walk the nodes counter-clockwise until reaching a node at/before $position
			do {
				$priorIndex = $guessIndex;
				$guessIndex = $this->getPrevClockwiseNodeIndex( $guessIndex, $ring );
				$nodePosition = $ring[$guessIndex][self::KEY_POS];
				if ( $nodePosition < $position || $guessIndex > $priorIndex ) {
					$mainNodeIndex = $priorIndex; // includes roll-over case
				} elseif ( $nodePosition === $position ) {
					$mainNodeIndex = $guessIndex;
				}
				++$this->lastNodeScanSize;
			} while ( $mainNodeIndex === null );
		} else {
			// Walk the nodes clockwise until reaching a node at/after $position
			do {
				$priorIndex = $guessIndex;
				$guessIndex = $this->getNextClockwiseNodeIndex( $guessIndex, $ring );
				$nodePosition = $ring[$guessIndex][self::KEY_POS];
				if ( $nodePosition >= $position || $guessIndex < $priorIndex ) {
					$mainNodeIndex = $guessIndex; // includes roll-over case
				}
				++$this->lastNodeScanSize;
			} while ( $mainNodeIndex === null );
		}

		return $mainNodeIndex;
	}

	/**
	 * @param int[] $weightByLocation
	 * @param string $algo Hashing algorithm
	 * @return array[]
	 */
	private function buildLocationRing( array $weightByLocation, $algo ) {
		$locationCount = count( $weightByLocation );
		$totalWeight = array_sum( $weightByLocation );

		$ring = [];
		// Assign nodes to all locations based on location weight
		$claimed = []; // (position as string => node)
		foreach ( $weightByLocation as $location => $weight ) {
			$ratio = $weight / $totalWeight;
			// There $locationCount * (HASHES_PER_LOCATION * 4) nodes available;
			// assign a few groups of nodes to this location based on its weight.
			$nodesQuartets = intval( $ratio * self::HASHES_PER_LOCATION * $locationCount );
			for ( $qi = 0; $qi < $nodesQuartets; ++$qi ) {
				// For efficiency, get 4 points per hash call and 4X node count.
				// If $algo is MD5, then this matches that of with libketama.
				// See https://github.com/RJ/ketama/blob/master/libketama/ketama.c
				$positions = $this->getNodePositionQuartet( "{$location}-{$qi}" );
				foreach ( $positions as $gi => $position ) {
					$node = ( $qi * self::SECTORS_PER_HASH + $gi ) . "@$location";
					if ( isset( $claimed["$position"] ) && $claimed["$position"] > $node ) {
						continue; // disallow duplicates for sanity (name decides precedence)
					}
					$ring[] = [
						self::KEY_POS => $position,
						self::KEY_LOCATION => $location
					];
					$claimed["$position"] = $node;
				}
			}
		}
		// Sort the locations into clockwise order based on the hash ring position
		usort( $ring, function ( $a, $b ) {
			if ( $a[self::KEY_POS] === $b[self::KEY_POS] ) {
				throw new UnexpectedValueException( 'Duplicate node positions.' );
			}

			return ( $a[self::KEY_POS] < $b[self::KEY_POS] ? -1 : 1 );
		} );

		return $ring;
	}

	/**
	 * @param string $item Key
	 * @return float Ring position; integral number in [0, self::RING_SIZE - 1]
	 */
	private function getItemPosition( $item ) {
		// If $algo is MD5, then this matches that of with libketama.
		// See https://github.com/RJ/ketama/blob/master/libketama/ketama.c
		$octets = substr( hash( $this->algo, (string)$item, true ), 0, 4 );
		if ( strlen( $octets ) != 4 ) {
			throw new UnexpectedValueException( __METHOD__ . ": {$this->algo} is < 32 bits." );
		}

		return (float)sprintf( '%u', unpack( 'V', $octets )[1] );
	}

	/**
	 * @param string $nodeGroupName
	 * @return float[] Four ring positions on [0, self::RING_SIZE - 1]
	 */
	private function getNodePositionQuartet( $nodeGroupName ) {
		$octets = substr( hash( $this->algo, (string)$nodeGroupName, true ), 0, 16 );
		if ( strlen( $octets ) != 16 ) {
			throw new UnexpectedValueException( __METHOD__ . ": {$this->algo} is < 128 bits." );
		}

		$positions = [];
		foreach ( unpack( 'V4', $octets ) as $signed ) {
			$positions[] = (float)sprintf( '%u', $signed );
		}

		return $positions;
	}

	/**
	 * @param int $i Valid index for a node in the ring
	 * @param array[] $ring Either the base or live ring
	 * @return int Valid index for a node in the ring
	 */
	private function getNextClockwiseNodeIndex( $i, $ring ) {
		if ( !isset( $ring[$i] ) ) {
			throw new UnexpectedValueException( __METHOD__ . ": reference index is invalid." );
		}

		$next = $i + 1;

		return ( $next < count( $ring ) ) ? $next : 0;
	}

	/**
	 * @param int $i Valid index for a node in the ring
	 * @param array[] $ring Either the base or live ring
	 * @return int Valid index for a node in the ring
	 */
	private function getPrevClockwiseNodeIndex( $i, $ring ) {
		if ( !isset( $ring[$i] ) ) {
			throw new UnexpectedValueException( __METHOD__ . ": reference index is invalid." );
		}

		$prev = $i - 1;

		return ( $prev >= 0 ) ? $prev : count( $ring ) - 1;
	}

	/**
	 * Get the "live" hash ring (which does not include ejected locations)
	 *
	 * @return array[]
	 * @throws UnexpectedValueException
	 */
	protected function getLiveRing() {
		if ( !$this->ejectExpiryByLocation ) {
			return $this->baseRing; // nothing ejected
		}

		$now = $this->getCurrentTime();

		if ( $this->liveRing === null || min( $this->ejectExpiryByLocation ) <= $now ) {
			// Live ring needs to be regerenated...
			$this->ejectExpiryByLocation = array_filter(
				$this->ejectExpiryByLocation,
				function ( $expiry ) use ( $now ) {
					return ( $expiry > $now );
				}
			);

			if ( count( $this->ejectExpiryByLocation ) ) {
				// Some locations are still ejected from the ring
				$liveRing = [];
				foreach ( $this->baseRing as $i => $nodeInfo ) {
					$location = $nodeInfo[self::KEY_LOCATION];
					if ( !isset( $this->ejectExpiryByLocation[$location] ) ) {
						$liveRing[] = $nodeInfo;
					}
				}
			} else {
				$liveRing = $this->baseRing;
			}

			$this->liveRing = $liveRing;
		}

		if ( !$this->liveRing ) {
			throw new UnexpectedValueException( "The live ring is currently empty." );
		}

		return $this->liveRing;
	}

	/**
	 * @return int UNIX timestamp
	 */
	protected function getCurrentTime() {
		return time();
	}

	/**
	 * @return int|null
	 */
	public function getLastNodeScanSize() {
		return $this->lastNodeScanSize;
	}

	public function serialize() {
		return serialize( [
			'algorithm' => $this->algo,
			'locations' => $this->weightByLocation,
			'ejections' => $this->ejectExpiryByLocation
		] );
	}

	public function unserialize( $serialized ) {
		$data = unserialize( $serialized );
		if ( is_array( $data ) ) {
			$this->init( $data['locations'], $data['algorithm'], $data['ejections'] );
		} else {
			throw new UnexpectedValueException( __METHOD__ . ": unable to decode JSON." );
		}
	}
}
