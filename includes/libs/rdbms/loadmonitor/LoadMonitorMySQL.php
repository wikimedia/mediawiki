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

/**
 * Basic MySQL load monitor with no external dependencies
 * Uses memcached to cache the replication lag for a short time
 *
 * @ingroup Database
 */
class LoadMonitorMySQL extends LoadMonitor {
	protected function getWeightScale( $index, IDatabase $conn = null, $lastWeight ) {
		if ( !$conn ) {
			return .9 * $lastWeight; // lower weight some
		}

		$weight = 1.0;
		$res = $conn->query( 'SHOW STATUS', false );
		$s = $res ? $conn->fetchObject( $res ) : false;
		if ( $s === false ) {
			$host = $this->parent->getServerName( $index );
			$this->replLogger->error( __METHOD__ . ": host $host is not queryable" );
		} else {
			// http://dev.mysql.com/doc/refman/5.7/en/server-status-variables.html
			if ( $s->Innodb_buffer_pool_pages_total > 0 ) {
				$weight *= $s->Innodb_buffer_pool_pages_data / $s->Innodb_buffer_pool_pages_total;
			} elseif ( $s->Qcache_total_blocks > 0 ) {
				$weight *= 1 - $s->Qcache_free_blocks / $s->Qcache_total_blocks;
			}
		}

		return $weight;
	}
}
