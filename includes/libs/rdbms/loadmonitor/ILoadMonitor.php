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
 */
namespace Wikimedia\Rdbms;

use Psr\Log\LoggerAwareInterface;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Stats\StatsdAwareInterface;

/**
 * Database load monitoring interface
 *
 * @internal This class should not be called outside of LoadBalancer
 * @ingroup Database
 */
interface ILoadMonitor extends LoggerAwareInterface, StatsdAwareInterface {
	public const STATE_UP = 'up';
	public const STATE_CONN_COUNT = 'conn_count';
	public const STATE_AS_OF = 'time';

	/**
	 * Construct a new LoadMonitor with a given LoadBalancer parent
	 *
	 * @param ILoadBalancer $lb LoadBalancer this instance serves
	 * @param BagOStuff $sCache Local server memory cache
	 * @param WANObjectCache $wCache Local cluster memory cache
	 * @param array $options Additional parameters include:
	 *   - maxConnCount: maximum number of connections before circuit breaking to kick in [default: infinity]
	 */
	public function __construct( ILoadBalancer $lb, BagOStuff $sCache, WANObjectCache $wCache, $options );

	/**
	 * Perform load ratio adjustment before deciding which server to use
	 *
	 * @param array<int,int|float> &$weightByServer Map of (server index => weight)
	 */
	public function scaleLoads( array &$weightByServer );
}
