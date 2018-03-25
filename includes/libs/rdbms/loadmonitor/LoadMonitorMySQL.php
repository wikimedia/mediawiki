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
 * @ingroup Database
 */

namespace Wikimedia\Rdbms;

use BagOStuff;
use WANObjectCache;

/**
 * Basic MySQL load monitor with no external dependencies
 * Uses memcached to cache the replication lag for a short time
 *
 * @ingroup Database
 */
class LoadMonitorMySQL extends LoadMonitor {
	/** @var float What buffer pool use ratio counts as "warm" (e.g. 0.5 for 50% usage) */
	private $warmCacheRatio;

	public function __construct(
		ILoadBalancer $lb, BagOStuff $srvCache, WANObjectCache $wCache, array $options = []
	) {
		parent::__construct( $lb, $srvCache, $wCache, $options );

		$this->warmCacheRatio = isset( $options['warmCacheRatio'] )
			? $options['warmCacheRatio']
			: 0.0;
	}

	protected function getWeightScale( $index, IDatabase $conn = null ) {
		if ( !$conn ) {
			return parent::getWeightScale( $index, $conn );
		}

		$weight = 1.0;
		if ( $this->warmCacheRatio > 0 ) {
			$res = $conn->query( 'SHOW STATUS', __METHOD__ );
			$s = $res ? $conn->fetchObject( $res ) : false;
			if ( $s === false ) {
				$host = $this->parent->getServerName( $index );
				$this->replLogger->error( __METHOD__ . ": could not get status for $host" );
			} else {
				// https://dev.mysql.com/doc/refman/5.7/en/server-status-variables.html
				if ( $s->Innodb_buffer_pool_pages_total > 0 ) {
					$ratio = $s->Innodb_buffer_pool_pages_data / $s->Innodb_buffer_pool_pages_total;
				} else {
					$ratio = 1.0;
				}
				// Stop caring once $ratio >= $this->warmCacheRatio
				$weight *= min( $ratio / $this->warmCacheRatio, 1.0 );
			}
		}

		return $weight;
	}
}
