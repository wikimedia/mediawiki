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
 * @author Aaron Schulz
 */

class RedisPubSubPublisher extends PubSubPublisher {
	/** @var RedisConnectionPool Used for PUB/SUB broadcasters */
	protected $redisPool;
	/** @var array List of redis servers Used for PUB/SUB broadcasters */
	protected $redisSrvs;

	public function __construct( array $params ) {
		parent::__construct( $params );

		$redisConf = array( 'serializer' => 'none' ); // manage that in this class
		foreach ( array( 'connectTimeout', 'persistent', 'password' ) as $opt ) {
			if ( isset( $params[$opt] ) ) {
				$redisConf[$opt] = $params[$opt];
			}
		}
		$this->redisPool = RedisConnectionPool::singleton( $redisConf );
		$this->redisSrvs = $params['servers'];
	}

	public function publish( $channel, $msg ) {
		$ok = false;

		shuffle( $this->redisSrvs );
		foreach ( $this->redisSrvs as $server ) {
			$conn = $this->redisPool->getConnection( $server );
			if ( !$conn ) {
				continue; // fail-over to the next server
			}
			try {
				if ( $conn->publish( $channel, $msg ) ) {
					$ok = true;
					break; // success
				}
			} catch ( RedisException $e ) {
				$this->redisPool->handleException( $conn, $e );
			}
		}

		if ( !$ok ) {
			$srvs = implode( ', ', $this->redisSrvs );
			throw new PubSubException( "Could not write to any of $srvs." );
		}
	}
}

class PubSubException extends Exception {}
