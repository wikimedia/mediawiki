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
		local kEvents, kMeta = unpack(KEYS)
		local curTime = 1*ARGV[1]
		local purgeTTL = 1*ARGV[2]
		local newEpoch = ARGV[3]
		local channel = ARGV[4]
		-- Append the events (the remaining arguments) to the set
		local pushed = 0
		for i = 5,#ARGV,1 do
			local event = ARGV[i]
			-- The set scores come from an incrementing counter,
			-- which avoids the clock skew issues timestamps have
			local eventId = 1*redis.call('hIncrBy',kMeta,'maxId',1)
			-- Prepend the position to allow duplicate events
			redis.call('zAdd',kEvents,eventId,eventId .. ':' .. event)
			-- Also publish the event for the fast common case
			redis.call('publish',channel,event)
			pushed = pushed + 1
		end
		-- Get the current highest event ID
		local maxId = 1*redis.call('hGet',kMeta,'maxId')
		-- Reset the metadata if it was missing for any reason
		redis.call('hSetNx',kMeta,'epoch',newEpoch)
		redis.call('hSetNx',kMeta,'checkpointTime',0)
		redis.call('hSetNx',kMeta,'checkpointId',0)
		redis.call('hSetNx',kMeta,'deleteBeforeId',0)
		-- Maintain a checkpoint with the current time and event ID
		local cutoffTime = curTime - purgeTTL
		if 1*redis.call('hGet',kMeta,'checkpointTime') < cutoffTime then
			-- Allow deletions of items older than the prior checkpoint
			local id = 1*redis.call('hGet',kMeta,'checkpointId')
			redis.call('hSet',kMeta,'deleteBeforeId',id)
			-- Update the current checkpoint
			redis.call('hSet',kMeta,'checkpointTime',curTime)
			redis.call('hSet',kMeta,'checkpointId',maxId)
		end
		-- Prune out stale events by deleting manageable batches
		-- of items with event IDs less that "safe to delete" ID
		local fromId = maxId - 1*redis.call('zCard',kEvents) + 1
		local lastId = fromId + 99
		if lastId < 1*redis.call('hGet',kMeta,'deleteBeforeId') then
			redis.call('zRemRangeByScore',kEvents,fromId,lastId)
		end
		return pushed
LUA;

		$time = time();
		$epoch = $time . ':' . mt_rand();

		$keys = array( "z-stream:$channel", "h-stream-metadata:$channel" );
		$args = array( $time, $this->eventTTL, $epoch, $channel );
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
