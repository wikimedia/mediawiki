<?php
/**
 * Database load monitoring interface
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
 * @ingroup Database
 */
use Psr\Log\LoggerAwareInterface;

/**
 * An interface for database load monitoring
 *
 * @ingroup Database
 */
interface ILoadMonitor extends LoggerAwareInterface {
	/**
	 * Construct a new LoadMonitor with a given LoadBalancer parent
	 *
	 * @param ILoadBalancer $lb LoadBalancer this instance serves
	 * @param BagOStuff $sCache Local server memory cache
	 * @param BagOStuff $cCache Local cluster memory cache
	 * @param array $options Options map
	 */
	public function __construct(
		ILoadBalancer $lb, BagOStuff $sCache, BagOStuff $cCache, array $options = []
	);

	/**
	 * Perform load ratio adjustment before deciding which server to use
	 *
	 * @param int[] &$weightByServer Map of (server index => float weight)
	 * @param string|bool $domain
	 */
	public function scaleLoads( array &$weightByServer, $domain );

	/**
	 * Get an estimate of replication lag (in seconds) for each server
	 *
	 * Values may be "false" if replication is too broken to estimate
	 *
	 * @param integer[] $serverIndexes
	 * @param string $domain
	 * @return array Map of (server index => float|int|bool)
	 */
	public function getLagTimes( array $serverIndexes, $domain );
}
