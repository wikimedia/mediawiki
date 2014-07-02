<?php
/**
 * Class for fetching backlink lists, approximate backlink counts and
 * partitions.
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

class RedisBloomCache extends BloomCache {
	/** @var RedisConnectionPool */
	protected $redisPool;
	/** @var array List of server names */
	protected $servers;

	public function __construct( array $config ) {
		parent::__construct( $config );

		$redisConf = array( 'serializer' => 'none' ); // manage that in this class
		foreach ( array( 'connectTimeout', 'persistent', 'password' ) as $opt ) {
			if ( isset( $config[$opt] ) ) {
				$redisConf[$opt] = $config[$opt];
			}
		}
		$this->redisPool = RedisConnectionPool::singleton( $redisConf );
		$this->servers = $config['servers'];
	}

	public function init( $key, $len ) {
		$conn = $this->getConnection( $key );

		$conn->luaEval( $script,
			array_merge(
				array(
					$this->getQueueKey( 'l-unclaimed' ), # KEYS[1]
					$this->getQueueKey( 'h-sha1ById' ), # KEYS[2]
					$this->getQueueKey( 'h-idBySha1' ), # KEYS[3]
					$this->getQueueKey( 'z-delayed' ), # KEYS[4]
					$this->getQueueKey( 'h-data' ), # KEYS[5]
				),
				$args
			),
			5 # number of first argument(s) that are keys
		);

		return true;
	}

	public function delete( $key ) {
		return true;
	}

	public function add( $key, $member ) {
		return true;
	}

	public function isPositive( $key, $member ) {
		return true;
	}

	public function getFalsePositiveRate( $key ) {
		return 1.0;
	}

	/**
	 * Get a Redis object with a connection suitable for fetching the specified key
	 * @return RedisConnRef|bool Returns false on failure
	 */
	protected function getConnection( $key ) {
		// TODO: redo this to make the # of filters = the number of servers

		if ( count( $this->servers ) === 1 ) {
			$candidates = $this->servers;
		} else {
			$candidates = $this->servers;
			ArrayUtils::consistentHashSort( $candidates, $key, '/' );
			if ( !$this->automaticFailover ) {
				$candidates = array_slice( $candidates, 0, 1 );
			}
		}

		foreach ( $candidates as $server ) {
			$conn = $this->redisPool->getConnection( $server );
			if ( $conn ) {
				return $conn;
			}
		}
		$this->setLastError( BagOStuff::ERR_UNREACHABLE );
		return false;
	}

	/**
	 * The redis extension throws an exception in response to various read, write
	 * and protocol errors. Sometimes it also closes the connection, sometimes
	 * not. The safest response for us is to explicitly destroy the connection
	 * object and let it be reopened during the next request.
	 * @param RedisConnRef $conn
	 * @param Exception $e
	 */
	protected function handleException( RedisConnRef $conn, $e ) {
		$this->redisPool->handleError( $conn, $e );
	}
}
