<?php
/**
 * Shows database lag
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
 * Maintenance script to show database lag.
 *
 * @ingroup Maintenance
 */
class DatabaseLag extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Shows database lag";
		$this->addOption( 'r', "Don't exit immediately, but show the lag every 5 seconds" );
	}

	public function execute() {
		if ( $this->hasOption( 'r' ) ) {
			$lb = wfGetLB();
			echo 'time     ';
			for ( $i = 1; $i < $lb->getServerCount(); $i++ ) {
				$hostname = $lb->getServerName( $i );
				printf( "%-12s ", $hostname );
			}
			echo "\n";

			while ( 1 ) {
				$lb->clearLagTimeCache();
				$lags = $lb->getLagTimes();
				unset( $lags[0] );
				echo gmdate( 'H:i:s' ) . ' ';
				foreach ( $lags as $lag ) {
					printf( "%-12s ", $lag === false ? 'false' : $lag );
				}
				echo "\n";
				sleep( 5 );
			}
		} else {
			$lb = wfGetLB();
			$lags = $lb->getLagTimes();
			foreach ( $lags as $i => $lag ) {
				$name = $lb->getServerName( $i );
				$this->output( sprintf( "%-20s %s\n", $name, $lag === false ? 'false' : $lag ) );
			}
		}
	}
}

$maintClass = "DatabaseLag";
require_once RUN_MAINTENANCE_IF_MAIN;
