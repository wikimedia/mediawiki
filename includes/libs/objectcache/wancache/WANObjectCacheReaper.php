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
 * @ingroup Cache
 */

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\ScopedCallback;

/**
 * Class for scanning through chronological, log-structured data or change logs
 * and locally purging cache keys related to entities that appear in this data.
 *
 * This is useful for repairing cache when purges are missed by using a reliable
 * stream, such as Kafka or a replicated MySQL table. Purge loss between datacenters
 * is expected to be more common than within them.
 *
 * @since 1.28
 */
class WANObjectCacheReaper implements LoggerAwareInterface {
	/** @var WANObjectCache */
	protected $cache;
	/** @var BagOStuff */
	protected $store;
	/** @var callable */
	protected $logChunkCallback;
	/** @var callable */
	protected $keyListCallback;
	/** @var LoggerInterface */
	protected $logger;

	/** @var string */
	protected $channel;
	/** @var int */
	protected $initialStartWindow;

	/**
	 * @param WANObjectCache $cache Cache to reap bad keys from
	 * @param BagOStuff $store Cache to store positions use for locking
	 * @param callable $logCallback Callback taking arguments:
	 *          - The starting position as a UNIX timestamp
	 *          - The starting unique ID used for breaking timestamp collisions or null
	 *          - The ending position as a UNIX timestamp
	 *          - The maximum number of results to return
	 *        It returns a list of maps of (key: cache key, pos: UNIX timestamp, id: unique ID)
	 *        for each key affected, with the corrosponding event timestamp/ID information.
	 *        The events should be in ascending order, by (timestamp,id).
	 * @param callable $keyCallback Callback taking arguments:
	 *          - The WANObjectCache instance
	 *          - An object from the event log
	 *        It should return a list of WAN cache keys.
	 *        The callback must fully duck-type test the object, since can be any model class.
	 * @param array $params Additional options:
	 *          - channel: the name of the update event stream.
	 *          - initialStartWindow: seconds back in time to start if the position is lost.
	 *            Default: 1 hour.
	 *          - logger: an SPL monolog instance [optional]
	 */
	public function __construct(
		WANObjectCache $cache,
		BagOStuff $store,
		callable $logCallback,
		callable $keyCallback,
		array $params
	) {
		$this->cache = $cache;
		$this->store = $store;

		$this->logChunkCallback = $logCallback;
		$this->keyListCallback = $keyCallback;
		if ( isset( $params['channel'] ) ) {
			$this->channel = $params['channel'];
		} else {
			throw new UnexpectedValueException( "No channel specified." );
		}

		$this->initialStartWindow = $params['initialStartWindow'] ?? 3600;
		$this->logger = $params['logger'] ?? new NullLogger();
	}

	public function setLogger( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Check and reap stale keys based on a chunk of events
	 *
	 * @param int $n Number of events
	 * @return int Number of keys checked
	 */
	final public function invoke( $n = 100 ) {
		$posKey = $this->store->makeGlobalKey( 'WANCache', 'reaper', $this->channel );
		$scopeLock = $this->store->getScopedLock( "$posKey:busy", 0 );
		if ( !$scopeLock ) {
			return 0;
		}

		$now = time();
		$status = $this->store->get( $posKey );
		if ( !$status ) {
			$status = [ 'pos' => $now - $this->initialStartWindow, 'id' => null ];
		}

		// Get events for entities who's keys tombstones/hold-off should have expired by now
		$events = call_user_func_array(
			$this->logChunkCallback,
			[ $status['pos'], $status['id'], $now - WANObjectCache::HOLDOFF_TTL - 1, $n ]
		);

		$event = null;
		$keyEvents = [];
		foreach ( $events as $event ) {
			$keys = call_user_func_array(
				$this->keyListCallback,
				[ $this->cache, $event['item'] ]
			);
			foreach ( $keys as $key ) {
				unset( $keyEvents[$key] ); // use only the latest per key
				$keyEvents[$key] = [
					'pos' => $event['pos'],
					'id' => $event['id']
				];
			}
		}

		$purgeCount = 0;
		$lastOkEvent = null;
		foreach ( $keyEvents as $key => $keyEvent ) {
			if ( !$this->cache->reap( $key, $keyEvent['pos'] ) ) {
				break;
			}
			++$purgeCount;
			$lastOkEvent = $event;
		}

		if ( $lastOkEvent ) {
			$ok = $this->store->merge(
				$posKey,
				function ( $bag, $key, $curValue ) use ( $lastOkEvent ) {
					if ( !$curValue ) {
						// Use new position
					} else {
						$curCoord = [ $curValue['pos'], $curValue['id'] ];
						$newCoord = [ $lastOkEvent['pos'], $lastOkEvent['id'] ];
						if ( $newCoord < $curCoord ) {
							// Keep prior position instead of rolling it back
							return $curValue;
						}
					}

					return [
						'pos' => $lastOkEvent['pos'],
						'id' => $lastOkEvent['id'],
						'ctime' => $curValue ? $curValue['ctime'] : date( 'c' )
					];
				},
				IExpiringStore::TTL_INDEFINITE
			);

			$pos = $lastOkEvent['pos'];
			$id = $lastOkEvent['id'];
			if ( $ok ) {
				$this->logger->info( "Updated cache reap position ($pos, $id)." );
			} else {
				$this->logger->error( "Could not update cache reap position ($pos, $id)." );
			}
		}

		ScopedCallback::consume( $scopeLock );

		return $purgeCount;
	}

	/**
	 * @return array|bool Returns (pos, id) map or false if not set
	 */
	public function getState() {
		$posKey = $this->store->makeGlobalKey( 'WANCache', 'reaper', $this->channel );

		return $this->store->get( $posKey );
	}
}
