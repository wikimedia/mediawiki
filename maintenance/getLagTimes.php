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

use MediaWiki\MediaWikiServices;

/**
 * Maintenance script that displays replication lag times.
 *
 * @ingroup Maintenance
 */
class GetLagTimes extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Dump replication lag times' );
		$this->addOption( 'report', "Report the lag values to StatsD" );
	}

	public function execute() {
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$stats = MediaWikiServices::getInstance()->getStatsdDataFactory();

		$lbs = $lbFactory->getAllMainLBs() + $lbFactory->getAllExternalLBs();
		foreach ( $lbs as $cluster => $lb ) {
			if ( $lb->getServerCount() <= 1 ) {
				continue;
			}
			$lags = $lb->getLagTimes();
			foreach ( $lags as $serverIndex => $lag ) {
				$host = $lb->getServerName( $serverIndex );
				if ( IP::isValid( $host ) ) {
					$ip = $host;
					$host = gethostbyaddr( $host );
				} else {
					$ip = gethostbyname( $host );
				}

				$starLen = min( intval( $lag ), 40 );
				$stars = str_repeat( '*', $starLen );
				$this->output( sprintf( "%10s %20s %3d %s\n", $ip, $host, $lag, $stars ) );

				if ( $this->hasOption( 'report' ) ) {
					$stats->gauge( "loadbalancer.lag.$cluster.$host", $lag );
				}
			}
		}
	}
}

$maintClass = "GetLagTimes";
require_once RUN_MAINTENANCE_IF_MAIN;
