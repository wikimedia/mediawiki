<?php
/**
 * Database load monitoring.
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

/**
 * An interface for database load monitoring
 *
 * @ingroup Database
 */
interface LoadMonitor {
	/**
	 * Construct a new LoadMonitor with a given LoadBalancer parent
	 *
	 * @param LoadBalancer $parent
	 */
	public function __construct( $parent );

	/**
	 * Perform pre-connection load ratio adjustment.
	 * @param array $loads
	 * @param string|bool $group The selected query group. Default: false
	 * @param string|bool $wiki Default: false
	 */
	public function scaleLoads( &$loads, $group = false, $wiki = false );

	/**
	 * Return an estimate of replication lag for each server
	 *
	 * @param array $serverIndexes
	 * @param string $wiki
	 *
	 * @return array Map of (server index => seconds)
	 */
	public function getLagTimes( $serverIndexes, $wiki );
}

class LoadMonitorNull implements LoadMonitor {
	public function __construct( $parent ) {
	}

	public function scaleLoads( &$loads, $group = false, $wiki = false ) {
	}

	public function getLagTimes( $serverIndexes, $wiki ) {
		return array_fill_keys( $serverIndexes, 0 );
	}
}
