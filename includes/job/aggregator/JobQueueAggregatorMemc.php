<?php
/**
 * Job queue aggregator code that uses BagOStuff.
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
 * Class to handle tracking information about all queues using BagOStuff
 *
 * @ingroup JobQueue
 * @since 1.21
 */
class JobQueueAggregatorMemc extends JobQueueAggregator {
	/** @var BagOStuff */
	protected $cache;

	protected $cacheTTL; // integer; seconds

	/**
	 * @params include:
	 *   - objectCache : Name of an object cache registered in $wgObjectCaches.
	 *                   This defaults to the one specified by $wgMainCacheType.
	 *   - cacheTTL    : Seconds to cache the aggregate data before regenerating.
	 * @param array $params
	 */
	protected function __construct( array $params ) {
		parent::__construct( $params );
		$this->cache = isset( $params['objectCache'] )
			? wfGetCache( $params['objectCache'] )
			: wfGetMainCache();
		$this->cacheTTL = isset( $params['cacheTTL'] ) ? $params['cacheTTL'] : 180; // 3 min
	}

	/**
	 * @see JobQueueAggregator::doNotifyQueueEmpty()
	 */
	protected function doNotifyQueueEmpty( $wiki, $type ) {
		$key = $this->getReadyQueueCacheKey();
		// Delist the queue from the "ready queue" list
		if ( $this->cache->add( "$key:lock", 1, 60 ) ) { // lock
			$curInfo = $this->cache->get( $key );
			if ( is_array( $curInfo ) && isset( $curInfo['pendingDBs'][$type] ) ) {
				if ( in_array( $wiki, $curInfo['pendingDBs'][$type] ) ) {
					$curInfo['pendingDBs'][$type] = array_diff(
						$curInfo['pendingDBs'][$type], array( $wiki ) );
					$this->cache->set( $key, $curInfo );
				}
			}
			$this->cache->delete( "$key:lock" ); // unlock
		}
		return true;
	}

	/**
	 * @see JobQueueAggregator::doNotifyQueueNonEmpty()
	 */
	protected function doNotifyQueueNonEmpty( $wiki, $type ) {
		return true; // updated periodically
	}

	/**
	 * @see JobQueueAggregator::doAllGetReadyWikiQueues()
	 */
	protected function doGetAllReadyWikiQueues() {
		$key = $this->getReadyQueueCacheKey();
		// If the cache entry wasn't present, is stale, or in .1% of cases otherwise,
		// regenerate the cache. Use any available stale cache if another process is
		// currently regenerating the pending DB information.
		$pendingDbInfo = $this->cache->get( $key );
		if ( !is_array( $pendingDbInfo )
			|| ( time() - $pendingDbInfo['timestamp'] ) > $this->cacheTTL
			|| mt_rand( 0, 999 ) == 0
		) {
			if ( $this->cache->add( "$key:rebuild", 1, 1800 ) ) { // lock
				$pendingDbInfo = array(
					'pendingDBs' => $this->findPendingWikiQueues(),
					'timestamp' => time()
				);
				for ( $attempts = 1; $attempts <= 25; ++$attempts ) {
					if ( $this->cache->add( "$key:lock", 1, 60 ) ) { // lock
						$this->cache->set( $key, $pendingDbInfo );
						$this->cache->delete( "$key:lock" ); // unlock
						break;
					}
				}
				$this->cache->delete( "$key:rebuild" ); // unlock
			}
		}
		return is_array( $pendingDbInfo )
			? $pendingDbInfo['pendingDBs']
			: array(); // cache is both empty and locked
	}

	/**
	 * @see JobQueueAggregator::doPurge()
	 */
	protected function doPurge() {
		return $this->cache->delete( $this->getReadyQueueCacheKey() );
	}

	/**
	 * @return string
	 */
	private function getReadyQueueCacheKey() {
		return "jobqueue:aggregator:ready-queues:v1"; // global
	}
}
