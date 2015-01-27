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
 * Multi-datacenter aware memcached caching interface
 *
 * Since values are internally stored in arrays, which will always be
 * serialized in storage, raw string values can have special meanings.
 * They can also be easily set by purging daemons, even in other languages.
 * Daemons can set flags=0, as both PHP and PECL memcached handle that.
 *
 * @ingroup Cache
 * @since 1.25
 */
class WANObjectCacheMemc extends WANObjectCache {
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

		if ( ! $this->cache instanceof MemcachedBagOStuff ) {
			throw Exception( __CLASS__ . ' requires a MemcachedBagOStuff.' );
		}

		$this->pool = $params['pool'];
		$this->relayer = $params['relayer'];
	}

	public function purge( $key, $ttl = self::HOLDOFF_TTL ) {
		return $this->relayer->notify(
			"{$this->pool}:purge",
			json_encode( array(
				'cmd' => 'set',
				'key' => $key,
				'val' => 'PURGED:$UNIXTIME$',
				'flg' => 0,
				'ttl' => max( $ttl, 1 ),
				'sbt' => true // interpolate $UNIXTIME$
			) )
		);
	}
}
