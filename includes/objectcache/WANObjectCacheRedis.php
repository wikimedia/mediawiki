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
 * @author Aaron Schulz
 */

/**
 * @ingroup Cache
 * @since 1.25
 */
class WANObjectCacheRedis extends WANObjectCache {
	/** @var string Cache pool name */
	protected $pool;
	/** @var EventRelayer */
	protected $relayer;

	/**
	 * @param array $params Additional params include:
	 *   - pool    : pool name
	 *   - relayer : EventRelayer class
	 */
	public function __construct( array $params ) {
		parent::__construct( $params );

		if ( ! $this->cache instanceof RedisBagOStuff ) {
			throw new Exception( __CLASS__ . ' requires a RedisBagOStuff.' );
		}

		$this->pool = $params['pool'];
		$this->relayer = $params['relayer'];
	}

	public function relayPurge( $key, $ttl = self::HOLDOFF_TTL ) {
		return $this->relayer->notify(
			"{$this->pool}:purge",
			array(
				'cmd' => 'set',
				'key' => $key,
				'val' => serialize( 'PURGED:$UNIXTIME$' ),
				'ttl' => max( $ttl, 1 ),
				'sbt' => true, // substitute $UNIXTIME$
			)
		);
	}
}
