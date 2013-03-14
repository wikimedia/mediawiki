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
 * @ingroup JobQueue
 * @ingroup Redis
 * @since 1.21
 */
class JobQueueAggregatorRedis extends JobQueueAggregator {
	/** @var RedisConnectionPool */
	protected $redisPool;

	/**
	 * @params include:
	 *   - redisConfig : An array of parameters to RedisConnectionPool::__construct().
	 *   - redisServer : A hostname/port combination or the absolute path of a UNIX socket.
	 *                   If a hostname is specified but no port, the standard port number
	 *                   6379 will be used. Required.
	 * @param array $params
	 */
	protected function __construct( array $params ) {
		parent::__construct( $params );
		$this->server = $params['redisServer'];
		$this->redisPool = RedisConnectionPool::singleton( $params['redisConfig'] );
	}

	/**
	 * @see JobQueueAggregator::doNotifyQueueEmpty()
	 */
	protected function doNotifyQueueEmpty( $wiki, $type ) {
		$conn = $this->getConnection();
		if ( !$conn ) {
			return false;
		}
		try {
			$conn->hDel( $this->getReadyQueueKey(), $this->encQueueName( $type, $wiki ) );
			return true;
		} catch ( RedisException $e ) {
			$this->handleException( $conn, $e );
			return false;
		}
	}

	/**
	 * @see JobQueueAggregator::doNotifyQueueNonEmpty()
	 */
	protected function doNotifyQueueNonEmpty( $wiki, $type ) {
		$conn = $this->getConnection();
		if ( !$conn ) {
			return false;
		}
		try {
			$conn->hSet( $this->getReadyQueueKey(), $this->encQueueName( $type, $wiki ), time() );
			return true;
		} catch ( RedisException $e ) {
			$this->handleException( $conn, $e );
			return false;
		}
	}

	/**
	 * @see JobQueueAggregator::doAllGetReadyWikiQueues()
	 */
	protected function doGetAllReadyWikiQueues() {
		$conn = $this->getConnection();
		if ( !$conn ) {
			return array();
		}
		try {
			$conn->multi( Redis::PIPELINE );
			$conn->exists( $this->getReadyQueueKey() );
			$conn->hGetAll( $this->getReadyQueueKey() );
			list( $exists, $map ) = $conn->exec();

			if ( $exists ) { // cache hit
				$pendingDBs = array(); // (type => list of wikis)
				foreach ( $map as $key => $time ) {
					list( $type, $wiki ) = $this->dencQueueName( $key );
					$pendingDBs[$type][] = $wiki;
				}
			} else { // cache miss
				// Avoid duplicated effort
				$conn->multi( Redis::MULTI );
				$conn->setnx( $this->getReadyQueueKey() . ":lock", 1 );
				$conn->expire( $this->getReadyQueueKey() . ":lock", 3600 );
				if ( $conn->exec() !== array( true, true ) ) { // lock
					return array(); // already in progress
				}

				$pendingDBs = $this->findPendingWikiQueues(); // (type => list of wikis)

				$conn->delete( $this->getReadyQueueKey() . ":lock" ); // unlock

				$now = time();
				$map = array();
				foreach ( $pendingDBs as $type => $wikis ) {
					foreach ( $wikis as $wiki ) {
						$map[$this->encQueueName( $type, $wiki )] = $now;
					}
				}
				$conn->hMSet( $this->getReadyQueueKey(), $map );
			}

			return $pendingDBs;
		} catch ( RedisException $e ) {
			$this->handleException( $conn, $e );
			return array();
		}
	}

	/**
	 * @see JobQueueAggregator::doPurge()
	 */
	protected function doPurge() {
		$conn = $this->getConnection();
		if ( !$conn ) {
			return false;
		}
		try {
			$conn->delete( $this->getReadyQueueKey() );
		} catch ( RedisException $e ) {
			$this->handleException( $conn, $e );
			return false;
		}
		return true;
	}

	/**
	 * Get a connection to the server that handles all sub-queues for this queue
	 *
	 * @return Array (server name, Redis instance)
	 * @throws MWException
	 */
	protected function getConnection() {
		return $this->redisPool->getConnection( $this->server );
	}

	/**
	 * @param RedisConnRef $conn
	 * @param RedisException $e
	 * @return void
	 */
	protected function handleException( RedisConnRef $conn, $e ) {
		$this->redisPool->handleException( $this->server, $conn, $e );
	}

	/**
	 * @return string
	 */
	private function getReadyQueueKey() {
		return "jobqueue:aggregator:h-ready-queues:v1"; // global
	}

	/**
	 * @param string $type
	 * @param string $wiki
	 * @return string
	 */
	private function encQueueName( $type, $wiki ) {
		return rawurlencode( $type ) . '/' . rawurlencode( $wiki );
	}

	/**
	 * @param string $name
	 * @return string
	 */
	private function dencQueueName( $name ) {
		list( $type, $wiki ) = explode( '/', $name, 2 );
		return array( rawurldecode( $type ), rawurldecode( $wiki ) );
	}
}
