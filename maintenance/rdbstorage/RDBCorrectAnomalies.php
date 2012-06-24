<?php
/**
 * Tool to help with consistency fixes for denormalized, sharded, tables.
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
 * @author Aaron Schulz
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/../Maintenance.php' );

/**
 * Maintenance script do consistency fixes for denormalized, sharded, tables
 *
 * @ingroup Maintenance
 */
class RDBCorrectAnamalies extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( "store", "RDB store name", true, true );
		$this->addOption( "cluster", "Name of the DB cluster", false, true );
		$this->addOption( "maxage", "Scan journal for entries this or less days old", false, true );
		$this->mDescription = "Fix row anamolies in RDB storage";
		$this->setBatchSize( 10 );
	}

	public function execute() {
		$rdbStore = RDBStoreGroup::singleton()->getExternal( $this->getOption( 'store' ) );

		$days = $this->hasOption( 'maxage' ) ? $this->getOption( 'maxage' ) : 30;
		if ( $this->hasOption( 'cluster' ) ) {
			$clusters = array( $this->getOption( 'cluster' ) );
		} else {
			$clusters = $rdbStore->getClusters();
		}

		$this->output( "Scanning for anamolies in the last $days day(s).\n\n" );
		foreach ( $clusters as $cluster ) {
			$this->output( "Correcting anomalies for cluster '$cluster'...\n" );
			$trxJournal = new ExternalRDBStoreTrxJournal( $rdbStore, $cluster );
			do {
				$fixed = $trxJournal->correctRowSyncAnomalies( $this->mBatchSize, $days );
				wfWaitForSlaves( 5 );
			} while ( $fixed > 0 );
			$this->output( "Done, corrected $fixed row(s) for cluster '$cluster'...\n\n" );
		}
	}
}

$maintClass = "RDBCorrectAnamalies";
require_once( RUN_MAINTENANCE_IF_MAIN );
