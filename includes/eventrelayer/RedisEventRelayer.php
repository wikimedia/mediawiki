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

class RedisEventRelayer extends EventRelayer {
	/** @var RedisConnectionPool Used for PUB/SUB broadcasters */
	protected $redisPool;
	/** @var array List of redis servers Used for PUB/SUB broadcasters */
	protected $redisSrvs;

	/** @var integer How long to store events (seconds) */
	protected $eventTTL = 86400;

	public function __construct( array $params ) {
		parent::__construct( $params );

		$redisConf = $params['redisConfig'];
		$redisConf['serializer'] = 'none'; // manage that in this class

		$this->redisPool = RedisConnectionPool::singleton( $redisConf );
		$this->redisSrvs = $params['redisServers'];
	}

	protected function doNotify( $channel, array $msgs ) {
		if ( !count( $msgs ) ) {
			return true;
		}

		$ok = false;

		static $script =
<<<LUA
		local kEvents = unpack(KEYS)
		local curTime = 1*ARGV[1]
		local purgeTTL = 1*ARGV[2]
		local channel = ARGV[3]
		-- Append the events (the remaining arguments) to the set
		local pushed = 0
		for i = 4,#ARGV,1 do
			local event = ARGV[i]
			-- Prepend the timestamp to allow duplicate events
			redis.call('zAdd',kEvents,curTime,curTime .. ':' .. event)
			-- Also publish the event for the fast common case
			redis.call('publish',channel,event)
			pushed = pushed + 1
		end
		-- Prune out any stale events
		local cutoffTime = curTime - purgeTTL
		redis.call('zRemRangeByScore',kEvents,0,cutoffTime)
		return pushed
LUA;

		$keys = array( "z-stream:$channel" );
		$args = array( microtime( true ), $this->eventTTL, $channel );
		$params = array_merge( $keys, $args, $msgs );

		shuffle( $this->redisSrvs );
		foreach ( $this->redisSrvs as $server ) {
			$conn = $this->redisPool->getConnection( $server );
			if ( !$conn ) {
				continue; // fail-over to the next server
			}
			try {
				$n = $conn->luaEval( $script, $params, count( $keys ) );
				$ok = ( $n > 0 );
				break; // success
			} catch ( RedisException $e ) {
				$this->redisPool->handleError( $conn, $e );
			}
		}

		return $ok;
	}
}
