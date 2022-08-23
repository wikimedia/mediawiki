<?php
/**
 * Storage medium specific cache for storing items.
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

use Wikimedia\WaitConditionLoop;

/**
 * Storage medium specific cache for storing items (e.g. redis, memcached, ...)
 *
 * This should not be used for proxy classes that simply wrap other cache instances
 *
 * @ingroup Cache
 * @since 1.34
 */
abstract class MediumSpecificBagOStuff extends BagOStuff {
	/** @var array<string,array> Map of (key => (class, depth, expiry) */
	protected $locks = [];
	/** @var int Bytes; chunk size of segmented cache values */
	protected $segmentationSize;
	/** @var int Bytes; maximum total size of a segmented cache value */
	protected $segmentedValueMaxSize;

	/** @var array */
	private $duplicateKeyLookups = [];
	/** @var bool */
	private $reportDupes = false;
	/** @var bool */
	private $dupeTrackScheduled = false;

	/** @var array[] Map of (key => (PHP variable value, serialized value)) */
	protected $preparedValues = [];

	/** Component to use for key construction of blob segment keys */
	private const SEGMENT_COMPONENT = 'segment';

	/** Idiom for doGet() to return extra information by reference */
	protected const PASS_BY_REF = -1;

	protected const METRIC_OP_GET = 'get';
	protected const METRIC_OP_SET = 'set';
	protected const METRIC_OP_DELETE = 'delete';
	protected const METRIC_OP_CHANGE_TTL = 'change_ttl';
	protected const METRIC_OP_ADD = 'add';
	protected const METRIC_OP_INCR = 'incr';
	protected const METRIC_OP_DECR = 'decr';
	protected const METRIC_OP_CAS = 'cas';

	protected const LOCK_RCLASS = 0;
	protected const LOCK_DEPTH = 1;
	protected const LOCK_TIME = 2;
	protected const LOCK_EXPIRY = 3;

	/**
	 * @see BagOStuff::__construct()
	 * Additional $params options include:
	 *   - logger: Psr\Log\LoggerInterface instance
	 *   - reportDupes: Whether to emit warning log messages for all keys that were
	 *      requested more than once (requires an asyncHandler).
	 *   - segmentationSize: The chunk size, in bytes, of segmented values. The value should
	 *      not exceed the maximum size of values in the storage backend, as configured by
	 *      the site administrator.
	 *   - segmentedValueMaxSize: The maximum total size, in bytes, of segmented values.
	 *      This should be configured to a reasonable size give the site traffic and the
	 *      amount of I/O between application and cache servers that the network can handle.
	 * @param array $params
	 * @phpcs:ignore Generic.Files.LineLength
	 * @phan-param array{logger?:Psr\Log\LoggerInterface,asyncHandler?:callable,reportDupes?:bool,segmentationSize?:int|float,segmentedValueMaxSize?:int} $params
	 */
	public function __construct( array $params = [] ) {
		parent::__construct( $params );

		if ( !empty( $params['reportDupes'] ) && $this->asyncHandler ) {
			$this->reportDupes = true;
		}

		// Default to 8MiB if segmentationSize is not set
		$this->segmentationSize = $params['segmentationSize'] ?? 8388608;
		// Default to 64MiB if segmentedValueMaxSize is not set
		$this->segmentedValueMaxSize = $params['segmentedValueMaxSize'] ?? 67108864;
	}

	/**
	 * Get an item with the given key
	 *
	 * If the key includes a deterministic input hash (e.g. the key can only have
	 * the correct value) or complete staleness checks are handled by the caller
	 * (e.g. nothing relies on the TTL), then the READ_VERIFIED flag should be set.
	 * This lets tiered backends know they can safely upgrade a cached value to
	 * higher tiers using standard TTLs.
	 *
	 * @param string $key
	 * @param int $flags Bitfield of BagOStuff::READ_* constants [optional]
	 * @return mixed Returns false on failure or if the item does not exist
	 */
	public function get( $key, $flags = 0 ) {
		$this->trackDuplicateKeys( $key );

		return $this->resolveSegments( $key, $this->doGet( $key, $flags ) );
	}

	/**
	 * Track the number of times that a given key has been used.
	 * @param string $key
	 */
	private function trackDuplicateKeys( $key ) {
		if ( !$this->reportDupes ) {
			return;
		}

		if ( !isset( $this->duplicateKeyLookups[$key] ) ) {
			// Track that we have seen this key. This N-1 counting style allows
			// easy filtering with array_filter() later.
			$this->duplicateKeyLookups[$key] = 0;
		} else {
			$this->duplicateKeyLookups[$key] += 1;

			if ( $this->dupeTrackScheduled === false ) {
				$this->dupeTrackScheduled = true;
				// Schedule a callback that logs keys processed more than once by get().
				call_user_func( $this->asyncHandler, function () {
					$dups = array_filter( $this->duplicateKeyLookups );
					foreach ( $dups as $key => $count ) {
						$this->logger->warning(
							'Duplicate get(): "{key}" fetched {count} times',
							// Count is N-1 of the actual lookup count
							[ 'key' => $key, 'count' => $count + 1, ]
						);
					}
				} );
			}
		}
	}

	/**
	 * Get an item
	 *
	 * The CAS token should be null if the key does not exist or the value is corrupt
	 *
	 * @param string $key
	 * @param int $flags Bitfield of BagOStuff::READ_* constants [optional]
	 * @param mixed &$casToken CAS token if MediumSpecificBagOStuff::PASS_BY_REF [returned]
	 * @return mixed Returns false on failure or if the item does not exist
	 */
	abstract protected function doGet( $key, $flags = 0, &$casToken = null );

	/**
	 * Set an item
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants
	 * @return bool Success
	 */
	public function set( $key, $value, $exptime = 0, $flags = 0 ) {
		$entry = $this->makeValueOrSegmentList( $key, $value, $exptime, $flags, $ok );
		// Only when all segments (if any) are stored should the main key be changed
		return $ok ? $this->doSet( $key, $entry, $exptime, $flags ) : false;
	}

	/**
	 * Set an item
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants
	 * @return bool Success
	 */
	abstract protected function doSet( $key, $value, $exptime = 0, $flags = 0 );

	/**
	 * Delete an item
	 *
	 * For large values written using WRITE_ALLOW_SEGMENTS, this only deletes the main
	 * segment list key unless WRITE_PRUNE_SEGMENTS is in the flags. While deleting the segment
	 * list key has the effect of functionally deleting the key, it leaves unused blobs in cache.
	 *
	 * @param string $key
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants
	 * @return bool True if the item was deleted or not found, false on failure
	 */
	public function delete( $key, $flags = 0 ) {
		if ( !$this->fieldHasFlags( $flags, self::WRITE_PRUNE_SEGMENTS ) ) {
			return $this->doDelete( $key, $flags );
		}

		$mainValue = $this->doGet( $key, self::READ_LATEST );
		if ( !$this->doDelete( $key, $flags ) ) {
			return false;
		}

		if ( !SerializedValueContainer::isSegmented( $mainValue ) ) {
			// no segments to delete
			return true;
		}

		$orderedKeys = array_map(
			function ( $segmentHash ) use ( $key ) {
				return $this->makeGlobalKey( self::SEGMENT_COMPONENT, $key, $segmentHash );
			},
			$mainValue->{SerializedValueContainer::SEGMENTED_HASHES}
		);

		return $this->deleteMulti( $orderedKeys, $flags & ~self::WRITE_PRUNE_SEGMENTS );
	}

	/**
	 * Delete an item
	 *
	 * @param string $key
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants
	 * @return bool True if the item was deleted or not found, false on failure
	 */
	abstract protected function doDelete( $key, $flags = 0 );

	public function add( $key, $value, $exptime = 0, $flags = 0 ) {
		$entry = $this->makeValueOrSegmentList( $key, $value, $exptime, $flags, $ok );
		// Only when all segments (if any) are stored should the main key be changed
		return $ok ? $this->doAdd( $key, $entry, $exptime, $flags ) : false;
	}

	/**
	 * Insert an item if it does not already exist
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants (since 1.33)
	 * @return bool Success
	 */
	abstract protected function doAdd( $key, $value, $exptime = 0, $flags = 0 );

	/**
	 * Merge changes into the existing cache value (possibly creating a new one)
	 *
	 * The callback function returns the new value given the current value
	 * (which will be false if not present), and takes the arguments:
	 * (this BagOStuff, cache key, current value, TTL).
	 * The TTL parameter is reference set to $exptime. It can be overridden in the callback.
	 * Nothing is stored nor deleted if the callback returns false.
	 *
	 * @param string $key
	 * @param callable $callback Callback method to be executed
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $attempts The amount of times to attempt a merge in case of failure
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants
	 * @return bool Success
	 */
	public function merge( $key, callable $callback, $exptime = 0, $attempts = 10, $flags = 0 ) {
		return $this->mergeViaCas( $key, $callback, $exptime, $attempts, $flags );
	}

	/**
	 * @param string $key
	 * @param callable $callback Callback method to be executed
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $attempts The amount of times to attempt a merge in case of failure
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants
	 * @return bool Success
	 * @see BagOStuff::merge()
	 */
	final protected function mergeViaCas( $key, callable $callback, $exptime, $attempts, $flags ) {
		$attemptsLeft = $attempts;
		do {
			$token = self::PASS_BY_REF;
			// Get the old value and CAS token from cache
			$watchPoint = $this->watchErrors();
			$currentValue = $this->resolveSegments(
				$key,
				$this->doGet( $key, $flags, $token )
			);
			if ( $this->getLastError( $watchPoint ) ) {
				// Don't spam slow retries due to network problems (retry only on races)
				$this->logger->warning(
					__METHOD__ . ' failed due to read I/O error on get() for {key}.',
					[ 'key' => $key ]
				);
				$success = false;
				break;
			}

			// Derive the new value from the old value
			$value = $callback( $this, $key, $currentValue, $exptime );
			$keyWasNonexistant = ( $currentValue === false );
			$valueMatchesOldValue = ( $value === $currentValue );
			// free RAM in case the value is large
			unset( $currentValue );

			$watchPoint = $this->watchErrors();
			if ( $value === false || $exptime < 0 ) {
				// do nothing
				$success = true;
			} elseif ( $valueMatchesOldValue && $attemptsLeft !== $attempts ) {
				// recently set by another thread to the same value
				$success = true;
			} elseif ( $keyWasNonexistant ) {
				// Try to create the key, failing if it gets created in the meantime
				$success = $this->add( $key, $value, $exptime, $flags );
			} else {
				// Try to update the key, failing if it gets changed in the meantime
				$success = $this->cas( $token, $key, $value, $exptime, $flags );
			}
			if ( $this->getLastError( $watchPoint ) ) {
				// Don't spam slow retries due to network problems (retry only on races)
				$this->logger->warning(
					__METHOD__ . ' failed due to write I/O error for {key}.',
					[ 'key' => $key ]
				);
				$success = false;
				break;
			}

		} while ( !$success && --$attemptsLeft );

		return $success;
	}

	/**
	 * Set an item if the current CAS token matches the provided CAS token
	 *
	 * @param mixed $casToken Only set the item if it still has this CAS token
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants
	 * @return bool Success
	 */
	protected function cas( $casToken, $key, $value, $exptime = 0, $flags = 0 ) {
		if ( $casToken === null ) {
			$this->logger->warning(
				__METHOD__ . ' got empty CAS token for {key}.',
				[ 'key' => $key ]
			);

			// caller may have meant to use add()?
			return false;
		}

		$entry = $this->makeValueOrSegmentList( $key, $value, $exptime, $flags, $ok );
		// Only when all segments (if any) are stored should the main key be changed
		return $ok ? $this->doCas( $casToken, $key, $entry, $exptime, $flags ) : false;
	}

	/**
	 * Set an item if the current CAS token matches the provided CAS token
	 *
	 * @param mixed $casToken CAS token from an existing version of the key
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants
	 * @return bool Success
	 */
	protected function doCas( $casToken, $key, $value, $exptime = 0, $flags = 0 ) {
		// @TODO: the use of lock() assumes that all other relevant sets() use a lock
		if ( !$this->lock( $key, 0 ) ) {
			// non-blocking
			return false;
		}

		$curCasToken = self::PASS_BY_REF;
		$watchPoint = $this->watchErrors();
		$exists = ( $this->doGet( $key, self::READ_LATEST, $curCasToken ) !== false );
		if ( $this->getLastError( $watchPoint ) ) {
			// Fail if the old CAS token could not be read
			$success = false;
			$this->logger->warning(
				__METHOD__ . ' failed due to write I/O error for {key}.',
				[ 'key' => $key ]
			);
		} elseif ( $exists && $this->tokensMatch( $casToken, $curCasToken ) ) {
			$success = $this->doSet( $key, $value, $exptime, $flags );
		} else {
			// mismatched or failed
			$success = false;
			$this->logger->info(
				__METHOD__ . ' failed due to race condition for {key}.',
				[ 'key' => $key, 'key_exists' => $exists ]
			);
		}

		$this->unlock( $key );

		return $success;
	}

	/**
	 * @param mixed $value CAS token for an existing key
	 * @param mixed $otherValue CAS token for an existing key
	 * @return bool Whether the two tokens match
	 */
	final protected function tokensMatch( $value, $otherValue ) {
		$type = gettype( $value );
		// Ideally, tokens are counters, timestamps, hashes, or serialized PHP values.
		// However, some classes might use the PHP values themselves.
		if ( $type !== gettype( $otherValue ) ) {
			return false;
		}
		// Serialize both tokens to strictly compare objects or arrays (which might objects
		// nested inside). Note that this will not apply if integer/string CAS tokens are used.
		if ( $type === 'array' || $type === 'object' ) {
			return ( serialize( $value ) === serialize( $otherValue ) );
		}
		// For string/integer tokens, use a simple comparison
		return ( $value === $otherValue );
	}

	/**
	 * Change the expiration on a key if it exists
	 *
	 * If an expiry in the past is given then the key will immediately be expired
	 *
	 * For large values written using WRITE_ALLOW_SEGMENTS, this only changes the TTL of the
	 * main segment list key. While lowering the TTL of the segment list key has the effect of
	 * functionally lowering the TTL of the key, it might leave unused blobs in cache for longer.
	 * Raising the TTL of such keys is not effective, since the expiration of a single segment
	 * key effectively expires the entire value.
	 *
	 * @param string $key
	 * @param int $exptime TTL or UNIX timestamp
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants (since 1.33)
	 * @return bool Success Returns false on failure or if the item does not exist
	 * @since 1.28
	 */
	public function changeTTL( $key, $exptime = 0, $flags = 0 ) {
		return $this->doChangeTTL( $key, $exptime, $flags );
	}

	/**
	 * @param string $key
	 * @param int $exptime
	 * @param int $flags
	 * @return bool
	 */
	protected function doChangeTTL( $key, $exptime, $flags ) {
		// @TODO: the use of lock() assumes that all other relevant sets() use a lock
		if ( !$this->lock( $key, 0 ) ) {
			return false;
		}

		$expiry = $this->getExpirationAsTimestamp( $exptime );
		$delete = ( $expiry != self::TTL_INDEFINITE && $expiry < $this->getCurrentTime() );

		// Use doGet() to avoid having to trigger resolveSegments()
		$blob = $this->doGet( $key, self::READ_LATEST );
		if ( $blob ) {
			if ( $delete ) {
				$ok = $this->doDelete( $key, $flags );
			} else {
				$ok = $this->doSet( $key, $blob, $exptime, $flags );
			}
		} else {
			$ok = false;
		}

		$this->unlock( $key );

		return $ok;
	}

	public function incrWithInit( $key, $exptime, $step = 1, $init = null, $flags = 0 ) {
		$step = (int)$step;
		$init = is_int( $init ) ? $init : $step;

		return $this->doIncrWithInit( $key, $exptime, $step, $init, $flags );
	}

	/**
	 * @param string $key
	 * @param int $exptime
	 * @param int $step
	 * @param int $init
	 * @param int $flags
	 * @return int|bool New value or false on failure
	 */
	abstract protected function doIncrWithInit( $key, $exptime, $step, $init, $flags );

	/**
	 * @param string $key
	 * @param int $timeout
	 * @param int $exptime
	 * @param string $rclass
	 * @return bool
	 */
	public function lock( $key, $timeout = 6, $exptime = 6, $rclass = '' ) {
		$exptime = min( $exptime ?: INF, self::TTL_DAY );

		$acquired = false;

		if ( isset( $this->locks[$key] ) ) {
			// Already locked; avoid deadlocks and allow lock reentry if specified
			if ( $rclass != '' && $this->locks[$key][self::LOCK_RCLASS] === $rclass ) {
				++$this->locks[$key][self::LOCK_DEPTH];
				$acquired = true;
			}
		} else {
			// Not already locked; acquire a lock on the backend
			$lockTsUnix = $this->doLock( $key, $timeout, $exptime );
			if ( $lockTsUnix !== null ) {
				$this->locks[$key] = [
					self::LOCK_RCLASS => $rclass,
					self::LOCK_DEPTH  => 1,
					self::LOCK_TIME   => $lockTsUnix,
					self::LOCK_EXPIRY => $lockTsUnix + $exptime
				];
				$acquired = true;
			}
		}

		return $acquired;
	}

	/**
	 * @see MediumSpecificBagOStuff::lock()
	 *
	 * @param string $key
	 * @param int $timeout Lock wait timeout; 0 for non-blocking [optional]
	 * @param int $exptime Lock time-to-live 1 day maximum [optional]
	 * @return float|null UNIX timestamp of acquisition; null on failure
	 */
	protected function doLock( $key, $timeout, $exptime ) {
		$lockTsUnix = null;

		$fname = __METHOD__;
		$loop = new WaitConditionLoop(
			function () use ( $key, $exptime, $fname, &$lockTsUnix ) {
				$watchPoint = $this->watchErrors();
				if ( $this->add( $this->makeLockKey( $key ), 1, $exptime ) ) {
					$lockTsUnix = microtime( true );

					// locked!
					return WaitConditionLoop::CONDITION_REACHED;
				} elseif ( $this->getLastError( $watchPoint ) ) {
					$this->logger->warning(
						"$fname failed due to I/O error for {key}.",
						[ 'key' => $key ]
					);

					// network partition?
					return WaitConditionLoop::CONDITION_ABORTED;
				}

				return WaitConditionLoop::CONDITION_CONTINUE;
			},
			$timeout
		);
		$code = $loop->invoke();

		if ( $code === $loop::CONDITION_TIMED_OUT ) {
			$this->logger->warning(
				"$fname failed due to timeout for {key}.",
				[ 'key' => $key, 'timeout' => $timeout ]
			);
		}

		return $lockTsUnix;
	}

	/**
	 * Release an advisory lock on a key string
	 *
	 * @param string $key
	 * @return bool Success
	 */
	public function unlock( $key ) {
		$released = false;

		if ( isset( $this->locks[$key] ) ) {
			if ( --$this->locks[$key][self::LOCK_DEPTH] > 0 ) {
				$released = true;
			} else {
				$released = $this->doUnlock( $key );
				unset( $this->locks[$key] );
				if ( !$released ) {
					$this->logger->warning(
						__METHOD__ . ' failed to release lock for {key}.',
						[ 'key' => $key ]
					);
				}
			}
		} else {
			$this->logger->warning(
				__METHOD__ . ' no lock to release for {key}.',
				[ 'key' => $key ]
			);
		}

		return $released;
	}

	/**
	 * @see MediumSpecificBagOStuff::unlock()
	 *
	 * @param string $key
	 * @return bool Success
	 */
	protected function doUnlock( $key ) {
		// Estimate the remaining TTL of the lock key
		$curTTL = $this->locks[$key][self::LOCK_EXPIRY] - $this->getCurrentTime();
		// Maximum expected one-way-delay for a query to reach the backend
		$maxOWD = 0.050;

		$released = false;

		if ( ( $curTTL - $maxOWD ) > 0 ) {
			// The lock key is extremely unlikely to expire before a deletion operation
			// sent from this method arrives on the relevant backend server
			$released = $this->doDelete( $this->makeLockKey( $key ) );
		} else {
			// It is unsafe for this method to delete the lock key due to the risk of it
			// expiring and being claimed by another thread before the deletion operation
			// arrives on the backend server
			$this->logger->warning(
				"Lock for {key} held too long ({age} sec).",
				[ 'key' => $key, 'curTTL' => $curTTL ]
			);
		}

		return $released;
	}

	/**
	 * @param string $key
	 * @return string
	 */
	protected function makeLockKey( $key ) {
		return "$key:lock";
	}

	public function deleteObjectsExpiringBefore(
		$timestamp,
		callable $progress = null,
		$limit = INF,
		string $tag = null
	) {
		return false;
	}

	/**
	 * Get an associative array containing the item for each of the keys that have items.
	 * @param string[] $keys List of keys; can be a map of (unused => key) for convenience
	 * @param int $flags Bitfield; supports READ_LATEST [optional]
	 * @return mixed[] Map of (key => value) for existing keys; preserves the order of $keys
	 */
	public function getMulti( array $keys, $flags = 0 ) {
		$foundByKey = $this->doGetMulti( $keys, $flags );

		$res = [];
		foreach ( $keys as $key ) {
			// Resolve one blob at a time (avoids too much I/O at once)
			if ( array_key_exists( $key, $foundByKey ) ) {
				// A value should not appear in the key if a segment is missing
				$value = $this->resolveSegments( $key, $foundByKey[$key] );
				if ( $value !== false ) {
					$res[$key] = $value;
				}
			}
		}

		return $res;
	}

	/**
	 * Get an associative array containing the item for each of the keys that have items.
	 * @param string[] $keys List of keys
	 * @param int $flags Bitfield; supports READ_LATEST [optional]
	 * @return array Map of (key => value) for existing keys; preserves the order of $keys
	 */
	protected function doGetMulti( array $keys, $flags = 0 ) {
		$res = [];
		foreach ( $keys as $key ) {
			$val = $this->doGet( $key, $flags );
			if ( $val !== false ) {
				$res[$key] = $val;
			}
		}

		return $res;
	}

	/**
	 * Batch insertion/replace
	 *
	 * This does not support WRITE_ALLOW_SEGMENTS to avoid excessive read I/O
	 *
	 * @param mixed[] $valueByKey Map of (key => value)
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants (since 1.33)
	 * @return bool Success
	 * @since 1.24
	 */
	public function setMulti( array $valueByKey, $exptime = 0, $flags = 0 ) {
		if ( $this->fieldHasFlags( $flags, self::WRITE_ALLOW_SEGMENTS ) ) {
			throw new InvalidArgumentException( __METHOD__ . ' got WRITE_ALLOW_SEGMENTS' );
		}

		return $this->doSetMulti( $valueByKey, $exptime, $flags );
	}

	/**
	 * @param mixed[] $data Map of (key => value)
	 * @param int $exptime Either an interval in seconds or a unix timestamp for expiry
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants
	 * @return bool Success
	 */
	protected function doSetMulti( array $data, $exptime = 0, $flags = 0 ) {
		$res = true;
		foreach ( $data as $key => $value ) {
			$res = $this->doSet( $key, $value, $exptime, $flags ) && $res;
		}

		return $res;
	}

	/**
	 * Batch deletion
	 *
	 * This does not support WRITE_ALLOW_SEGMENTS to avoid excessive read I/O
	 *
	 * @param string[] $keys List of keys
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants
	 * @return bool Success
	 * @since 1.33
	 */
	public function deleteMulti( array $keys, $flags = 0 ) {
		if ( $this->fieldHasFlags( $flags, self::WRITE_PRUNE_SEGMENTS ) ) {
			throw new InvalidArgumentException( __METHOD__ . ' got WRITE_PRUNE_SEGMENTS' );
		}

		return $this->doDeleteMulti( $keys, $flags );
	}

	/**
	 * @param string[] $keys List of keys
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants
	 * @return bool Success
	 */
	protected function doDeleteMulti( array $keys, $flags = 0 ) {
		$res = true;
		foreach ( $keys as $key ) {
			$res = $this->doDelete( $key, $flags ) && $res;
		}
		return $res;
	}

	/**
	 * Change the expiration of multiple keys that exist
	 *
	 * @param string[] $keys List of keys
	 * @param int $exptime TTL or UNIX timestamp
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants (since 1.33)
	 * @return bool Success
	 *
	 * @since 1.34
	 */
	public function changeTTLMulti( array $keys, $exptime, $flags = 0 ) {
		return $this->doChangeTTLMulti( $keys, $exptime, $flags );
	}

	/**
	 * @param string[] $keys List of keys
	 * @param int $exptime TTL or UNIX timestamp
	 * @param int $flags Bitfield of BagOStuff::WRITE_* constants
	 * @return bool Success
	 */
	protected function doChangeTTLMulti( array $keys, $exptime, $flags = 0 ) {
		$res = true;
		foreach ( $keys as $key ) {
			$res = $this->doChangeTTL( $key, $exptime, $flags ) && $res;
		}

		return $res;
	}

	/**
	 * Get and reassemble the chunks of blob at the given key
	 *
	 * @param string $key
	 * @param mixed $mainValue
	 * @return string|null|bool The combined string, false if missing, null on error
	 */
	final protected function resolveSegments( $key, $mainValue ) {
		if ( SerializedValueContainer::isUnified( $mainValue ) ) {
			return $this->unserialize( $mainValue->{SerializedValueContainer::UNIFIED_DATA} );
		}

		if ( SerializedValueContainer::isSegmented( $mainValue ) ) {
			$orderedKeys = array_map(
				function ( $segmentHash ) use ( $key ) {
					return $this->makeGlobalKey( self::SEGMENT_COMPONENT, $key, $segmentHash );
				},
				$mainValue->{SerializedValueContainer::SEGMENTED_HASHES}
			);

			$segmentsByKey = $this->doGetMulti( $orderedKeys );

			$parts = [];
			foreach ( $orderedKeys as $segmentKey ) {
				if ( isset( $segmentsByKey[$segmentKey] ) ) {
					$parts[] = $segmentsByKey[$segmentKey];
				} else {
					// missing segment
					return false;
				}
			}

			return $this->unserialize( implode( '', $parts ) );
		}

		return $mainValue;
	}

	final public function addBusyCallback( callable $workCallback ) {
		wfDeprecated( __METHOD__, '1.39' );
	}

	/**
	 * Check if a value should use a segmentation wrapper due to its size
	 *
	 * In order to avoid extra serialization and/or twice-serialized wrappers, just check if
	 * the value is a large string. Support cache wrappers (e.g. WANObjectCache) that use 2D
	 * arrays to wrap values. This does not recurse in order to avoid overhead from complex
	 * structures and the risk of infinite loops (due to references).
	 *
	 * @param mixed $value
	 * @param int $flags
	 * @return bool
	 */
	private function useSegmentationWrapper( $value, $flags ) {
		if (
			$this->segmentationSize === INF ||
			!$this->fieldHasFlags( $flags, self::WRITE_ALLOW_SEGMENTS )
		) {
			return false;
		}

		if ( is_string( $value ) ) {
			return ( strlen( $value ) >= $this->segmentationSize );
		}

		if ( is_array( $value ) ) {
			// Expect that the contained value will be one of the first array entries
			foreach ( array_slice( $value, 0, 4 ) as $v ) {
				if ( is_string( $v ) && strlen( $v ) >= $this->segmentationSize ) {
					return true;
				}
			}
		}

		// Avoid breaking functions for incrementing/decrementing integer key values
		return false;
	}

	/**
	 * Make the entry to store at a key (inline or segment list), storing any segments
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param int $exptime
	 * @param int $flags
	 * @param mixed|null &$ok Whether the entry is usable (e.g. no missing segments) [returned]
	 * @return mixed The entry (inline value, wrapped inline value, or wrapped segment list)
	 * @since 1.34
	 */
	final protected function makeValueOrSegmentList( $key, $value, $exptime, $flags, &$ok ) {
		$entry = $value;
		$ok = true;

		if ( $this->useSegmentationWrapper( $value, $flags ) ) {
			$segmentSize = $this->segmentationSize;
			$maxTotalSize = $this->segmentedValueMaxSize;
			$serialized = $this->getSerialized( $value, $key );
			$size = strlen( $serialized );
			if ( $size > $maxTotalSize ) {
				$this->logger->warning(
					"Value for {key} exceeds $maxTotalSize bytes; cannot segment.",
					[ 'key' => $key ]
				);
			} else {
				// Split the serialized value into chunks and store them at different keys
				$chunksByKey = [];
				$segmentHashes = [];
				$count = intdiv( $size, $segmentSize ) + ( ( $size % $segmentSize ) ? 1 : 0 );
				for ( $i = 0; $i < $count; ++$i ) {
					$segment = substr( $serialized, $i * $segmentSize, $segmentSize );
					$hash = sha1( $segment );
					$chunkKey = $this->makeGlobalKey( self::SEGMENT_COMPONENT, $key, $hash );
					$chunksByKey[$chunkKey] = $segment;
					$segmentHashes[] = $hash;
				}
				$flags &= ~self::WRITE_ALLOW_SEGMENTS;
				$ok = $this->setMulti( $chunksByKey, $exptime, $flags );
				$entry = SerializedValueContainer::newSegmented( $segmentHashes );
			}
		}

		return $entry;
	}

	/**
	 * @param int|float $exptime
	 * @return bool Whether the expiry is non-infinite, and, negative or not a UNIX timestamp
	 * @since 1.34
	 */
	final protected function isRelativeExpiration( $exptime ) {
		return ( $exptime !== self::TTL_INDEFINITE && $exptime < ( 10 * self::TTL_YEAR ) );
	}

	/**
	 * Convert an optionally relative timestamp to an absolute time
	 *
	 * The input value will be cast to an integer and interpreted as follows:
	 *   - zero: no expiry; return zero (e.g. TTL_INDEFINITE)
	 *   - negative: relative TTL; return UNIX timestamp offset by this value
	 *   - positive (< 10 years): relative TTL; return UNIX timestamp offset by this value
	 *   - positive (>= 10 years): absolute UNIX timestamp; return this value
	 *
	 * @param int $exptime
	 * @return int Expiration timestamp or TTL_INDEFINITE for indefinite
	 * @since 1.34
	 */
	final protected function getExpirationAsTimestamp( $exptime ) {
		if ( $exptime == self::TTL_INDEFINITE ) {
			return $exptime;
		}

		return $this->isRelativeExpiration( $exptime )
			? intval( $this->getCurrentTime() + $exptime )
			: $exptime;
	}

	/**
	 * Convert an optionally absolute expiry time to a relative time. If an
	 * absolute time is specified which is in the past, use a short expiry time.
	 *
	 * The input value will be cast to an integer and interpreted as follows:
	 *   - zero: no expiry; return zero (e.g. TTL_INDEFINITE)
	 *   - negative: relative TTL; return a short expiry time (1 second)
	 *   - positive (< 10 years): relative TTL; return this value
	 *   - positive (>= 10 years): absolute UNIX timestamp; return offset to current time
	 *
	 * @param int $exptime
	 * @return int Relative TTL or TTL_INDEFINITE for indefinite
	 * @since 1.34
	 */
	final protected function getExpirationAsTTL( $exptime ) {
		if ( $exptime == self::TTL_INDEFINITE ) {
			return $exptime;
		}

		return $this->isRelativeExpiration( $exptime )
			? $exptime
			: (int)max( $exptime - $this->getCurrentTime(), 1 );
	}

	/**
	 * Check if a value is an integer
	 *
	 * @param mixed $value
	 * @return bool
	 */
	final protected function isInteger( $value ) {
		if ( is_int( $value ) ) {
			return true;
		} elseif ( !is_string( $value ) ) {
			return false;
		}

		$integer = (int)$value;

		return ( $value === (string)$integer );
	}

	public function makeGlobalKey( $collection, ...$components ) {
		return $this->makeKeyInternal( self::GLOBAL_KEYSPACE, func_get_args() );
	}

	public function makeKey( $collection, ...$components ) {
		return $this->makeKeyInternal( $this->keyspace, func_get_args() );
	}

	protected function convertGenericKey( $key ) {
		$components = $this->componentsFromGenericKey( $key );
		if ( count( $components ) < 2 ) {
			// Legacy key not from makeKey()/makeGlobalKey(); keep it as-is
			return $key;
		}

		$keyspace = array_shift( $components );

		return $this->makeKeyInternal( $keyspace, $components );
	}

	public function getQoS( $flag ) {
		return $this->attrMap[$flag] ?? self::QOS_UNKNOWN;
	}

	public function getSegmentationSize() {
		return $this->segmentationSize;
	}

	public function getSegmentedValueMaxSize() {
		return $this->segmentedValueMaxSize;
	}

	public function setNewPreparedValues( array $valueByKey ) {
		$this->preparedValues = [];

		$sizes = [];
		foreach ( $valueByKey as $key => $value ) {
			if ( $value === false ) {
				// not storable, don't bother
				$sizes[] = null;
				continue;
			}

			$serialized = $this->serialize( $value );
			$sizes[] = ( $serialized !== false ) ? strlen( $serialized ) : null;

			$this->preparedValues[$key] = [ $value, $serialized ];
		}

		return $sizes;
	}

	/**
	 * Get the serialized form a value, using any applicable prepared value
	 *
	 * @see BagOStuff::setNewPreparedValues()
	 *
	 * @param mixed $value
	 * @param string $key
	 * @return string|int String/integer representation
	 * @since 1.35
	 */
	protected function getSerialized( $value, $key ) {
		// Reuse any available prepared (serialized) value
		if ( array_key_exists( $key, $this->preparedValues ) ) {
			list( $prepValue, $prepSerialized ) = $this->preparedValues[$key];
			// Normally, this comparison should only take a few microseconds to confirm a match.
			// Using "===" on variables of different types is always fast. It is also fast for
			// variables of matching type int, float, bool, null, and object. Lastly, it is fast
			// for comparing arrays/strings if they are copy-on-write references, which should be
			// the case at this point, assuming prepareValues() was called correctly.
			if ( $prepValue === $value ) {
				unset( $this->preparedValues[$key] );

				return $prepSerialized;
			}
		}

		$this->checkValueSerializability( $value, $key );

		return $this->serialize( $value );
	}

	/**
	 * Estimate the size of a variable once serialized
	 *
	 * @param mixed $value
	 * @param int $depth Current stack depth
	 * @param int &$loops Number of iterable nodes visited
	 * @return int|null Size in bytes; null for unsupported variable types
	 * @since 1.35
	 */
	protected function guessSerialValueSize( $value, $depth = 0, &$loops = 0 ) {
		if ( is_string( $value ) ) {
			// E.g. "<type><delim1><quote><value><quote><delim2>"
			return strlen( $value ) + 5;
		} else {
			return strlen( serialize( $value ) );
		}
	}

	/**
	 * Estimate the size of a each variable once serialized
	 *
	 * @param array $values List/map with PHP variable values to serialize
	 * @return int[]|null[] Corresponding list of size estimates (null for invalid values)
	 * @since 1.39
	 */
	protected function guessSerialSizeOfValues( array $values ) {
		$sizes = [];
		foreach ( $values as $value ) {
			$sizes[] = $this->guessSerialValueSize( $value );
		}

		return $sizes;
	}

	/**
	 * Log if a new cache value does not appear suitable for serialization at a quick glance
	 *
	 * This aids migration of values to JSON-like structures and the debugging of exceptions
	 * due to serialization failure.
	 *
	 * This does not recurse more than one level into container structures.
	 *
	 * A proper cache key value is one of the following:
	 *  - null
	 *  - a scalar
	 *  - an array with scalar/null values
	 *  - an array tree with scalar/null "leaf" values
	 *  - an stdClass instance with scalar/null field values
	 *  - an stdClass instance tree with scalar/null "leaf" values
	 *  - an instance of a class that implements JsonSerializable
	 *
	 * @param mixed $value Result of the value generation callback for the key
	 * @param string $key Cache key
	 */
	private function checkValueSerializability( $value, $key ) {
		if ( is_array( $value ) ) {
			$this->checkIterableMapSerializability( $value, $key );
		} elseif ( is_object( $value ) ) {
			// Note that Closure instances count as objects
			if ( $value instanceof stdClass ) {
				$this->checkIterableMapSerializability( $value, $key );
			} elseif ( !( $value instanceof JsonSerializable ) ) {
				$this->logger->warning(
					"{class} value for '{cachekey}'; serialization is suspect.",
					[ 'cachekey' => $key, 'class' => get_class( $value ) ]
				);
			}
		}
	}

	/**
	 * @param array|stdClass $value Result of the value generation callback for the key
	 * @param string $key Cache key
	 */
	private function checkIterableMapSerializability( $value, $key ) {
		foreach ( $value as $index => $entry ) {
			if ( is_object( $entry ) ) {
				// Note that Closure instances count as objects
				if (
					!( $entry instanceof stdClass ) &&
					!( $entry instanceof JsonSerializable )
				) {
					$this->logger->warning(
						"{class} value for '{cachekey}' at '$index'; serialization is suspect.",
						[ 'cachekey' => $key, 'class' => get_class( $entry ) ]
					);

					return;
				}
			}
		}
	}

	/**
	 * @param mixed $value
	 * @return string|int|false String/integer representation
	 * @note Special handling is usually needed for integers so incr()/decr() work
	 */
	protected function serialize( $value ) {
		return is_int( $value ) ? $value : serialize( $value );
	}

	/**
	 * @param string|int|false $value
	 * @return mixed Original value or false on error
	 * @note Special handling is usually needed for integers so incr()/decr() work
	 */
	protected function unserialize( $value ) {
		return $this->isInteger( $value ) ? (int)$value : unserialize( $value );
	}

	/**
	 * @param string $text
	 */
	protected function debug( $text ) {
		$this->logger->debug( "{class} debug: $text", [ 'class' => static::class ] );
	}

	/**
	 * @param string $op Operation name as a MediumSpecificBagOStuff::METRIC_OP_* constant
	 * @param array<int,string>|array<string,int[]> $keyInfo Key list, if payload sizes are not
	 *  applicable, otherwise, map of (key => (send payload size, receive payload size)); send
	 *  and receive sizes are 0 where not applicable and receive sizes are "false" for keys
	 *  that were not found during read operations
	 */
	protected function updateOpStats( string $op, array $keyInfo ) {
		$deltasByMetric = [];

		foreach ( $keyInfo as $indexOrKey => $keyOrSizes ) {
			if ( is_array( $keyOrSizes ) ) {
				$key = $indexOrKey;
				list( $sPayloadSize, $rPayloadSize ) = $keyOrSizes;
			} else {
				$key = $keyOrSizes;
				$sPayloadSize = 0;
				$rPayloadSize = 0;
			}

			// Metric prefix for the cache wrapper and key collection name
			$prefix = $this->determineKeyPrefixForStats( $key );

			if ( $op === self::METRIC_OP_GET ) {
				// This operation was either a "hit" or "miss" for this key
				$name = "{$prefix}.{$op}_" . ( $rPayloadSize === false ? 'miss_rate' : 'hit_rate' );
			} else {
				// There is no concept of "hit" or "miss" for this operation
				$name = "{$prefix}.{$op}_call_rate";
			}
			$deltasByMetric[$name] = ( $deltasByMetric[$name] ?? 0 ) + 1;

			if ( $sPayloadSize > 0 ) {
				$name = "{$prefix}.{$op}_bytes_sent";
				$deltasByMetric[$name] = ( $deltasByMetric[$name] ?? 0 ) + $sPayloadSize;
			}

			if ( $rPayloadSize > 0 ) {
				$name = "{$prefix}.{$op}_bytes_read";
				$deltasByMetric[$name] = ( $deltasByMetric[$name] ?? 0 ) + $rPayloadSize;
			}
		}

		foreach ( $deltasByMetric as $name => $delta ) {
			$this->stats->updateCount( $name, $delta );
		}
	}
}
