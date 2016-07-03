<?php
/**
 * Job queue aggregator code that uses PhpRedis.
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
 * Class to handle tracking information about all queues using PhpRedis
 *
 * The mediawiki/services/jobrunner background service must be set up and running.
 *
 * @ingroup JobQueue
 * @ingroup Redis
 * @since 1.21
 */
class JobQueueAggregatorRedis extends JobQueueAggregator {
	/** @var RedisConnectionPool */
	protected $redisPool;
	/** @var array List of Redis server addresses */
	protected $servers;

	/**
	 * @param array $params Possible keys:
	 *   - redisConfig  : An array of parameters to RedisConnectionPool::__construct().
	 *   - redisServers : Array of server entries, the first being the primary and the
	 *                    others being fallback servers. Each entry is either a hostname/port
	 *                    combination or the absolute path of a UNIX socket.
	 *                    If a hostname is specified but no port, the standard port number
	 *                    6379 will be used. Required.
	 */
	public function __construct( array $params ) {
		parent::__construct( $params );
		$this->servers = isset( $params['redisServers'] )
			? $params['redisServers']
			: [ $params['redisServer'] ]; // b/c
		$params['redisConfig']['serializer'] = 'none';
		$this->redisPool = RedisConnectionPool::singleton( $params['redisConfig'] );
	}

	protected function doNotifyQueueEmpty( $wiki, $type ) {
		return true; // managed by the service
	}

	protected function doNotifyQueueNonEmpty( $wiki, $type ) {
		return true; // managed by the service
	}

	protected function doGetAllReadyWikiQueues() {
		$conn = $this->getConnection();
		if ( !$conn ) {
			return [];
		}
		try {
			$map = $conn->hGetAll( $this->getReadyQueueKey() );

			if ( is_array( $map ) && isset( $map['_epoch'] ) ) {
				unset( $map['_epoch'] ); // ignore
				$pendingDBs = []; // (type => list of wikis)
				foreach ( $map as $key => $time ) {
					list( $type, $wiki ) = $this->decodeQueueName( $key );
					$pendingDBs[$type][] = $wiki;
				}
			} else {
				throw new UnexpectedValueException(
					"No queue listing found; make sure redisJobChronService is running."
				);
			}

			return $pendingDBs;
		} catch ( RedisException $e ) {
			$this->redisPool->handleError( $conn, $e );

			return [];
		}
	}

	protected function doPurge() {
		return true; // fully and only refreshed by the service
	}

	/**
	 * Get a connection to the server that handles all sub-queues for this queue
	 *
	 * @return RedisConnRef|bool Returns false on failure
	 * @throws MWException
	 */
	protected function getConnection() {
		$conn = false;
		foreach ( $this->servers as $server ) {
			$conn = $this->redisPool->getConnection( $server );
			if ( $conn ) {
				break;
			}
		}

		return $conn;
	}

	/**
	 * @return string
	 */
	private function getReadyQueueKey() {
		return "jobqueue:aggregator:h-ready-queues:v2"; // global
	}

	/**
	 * @param string $name
	 * @return string
	 */
	private function decodeQueueName( $name ) {
		list( $type, $wiki ) = explode( '/', $name, 2 );

		return [ rawurldecode( $type ), rawurldecode( $wiki ) ];
	}
}
