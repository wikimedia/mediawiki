<?php
/**
 * Display replication lag times.
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
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that displays replication lag times.
 *
 * @ingroup Maintenance
 */
class GetLagTimes extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Dump replication lag times' );
	}

	public function execute() {
		$lb = wfGetLB();

		if ( $lb->getServerCount() == 1 ) {
			$this->error( "This script dumps replication lag times, but you don't seem to have\n"
				. "a multi-host db server configuration." );
		} else {
			$lags = $lb->getLagTimes();
			foreach ( $lags as $n => $lag ) {
				$host = $lb->getServerName( $n );
				if ( IP::isValid( $host ) ) {
					$ip = $host;
					$host = gethostbyaddr( $host );
				} else {
					$ip = gethostbyname( $host );
				}
				$starLen = min( intval( $lag ), 40 );
				$stars = str_repeat( '*', $starLen );
				$this->output( sprintf( "%10s %20s %3d %s\n", $ip, $host, $lag, $stars ) );
			}
		}
	}
}

$maintClass = "GetLagTimes";
require_once RUN_MAINTENANCE_IF_MAIN;
