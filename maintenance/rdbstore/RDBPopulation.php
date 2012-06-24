<?php
/**
 * Tool to help copy rows from a local DB table into sharded DB storage.
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
 * Maintenance script to copy a table to a sharded DB store
 *
 * @ingroup Maintenance
 */
class RDBPopulation extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( "table", "Local DB table name", true, true );
		$this->addOption( "indexColumn", "Indexed integer column", true, true );
		$this->addOption( "shardColumn", "Shard column", true, true );
		$this->addOption( "store", "The external RDB store name", true, true );
		$this->mDescription = "Copy a local table into sharded DB storage";
		$this->setBatchSize( 500 );
	}

	public function execute() {
		$table    = $this->getOption( 'table' );
		$indexCol = $this->getOption( 'indexColumn' );
		$shardCol = $this->getOption( 'shardColumn' );

		$dbw   = $this->getDB( DB_MASTER );
		$start = $dbw->selectField( $table, "MIN($indexCol)", false, __METHOD__ );
		$end   = $dbw->selectField( $table, "MAX($indexCol)", false, __METHOD__ );
		if ( !$start || !$end ) {
			$this->output( "...$table table seems to be empty.\n" );
			return;
		} elseif ( !$dbw->fieldExists( $table, $shardCol ) ) { // sanity
			$this->error( "Table '$table' missing column '$shardCol'.", 1 ); // die
		}
		$rdbStore = RDBStoreGroup::singleton()->getExternal( $this->getOption( 'store' ) );

		$first = true;
		$count = 0;
		// $indexCol may be a BIGINT or a UID, which are too large for PHP ints.
		// Also UIDs are sparse, rather than incrementing by units of 1 each time.
		// Lastly, $indexCol might be only the first column of a unique index.
		$blockStart = $start;
		do {
			$inequality = $first ? ">=" : ">"; // inclusive for the first block
			$res = $dbw->select( $table, '*',
				array(
					"$indexCol $inequality {$dbw->addQuotes( $blockStart )}",
					"$indexCol <= {$dbw->addQuotes( $end )}"
				),
				__METHOD__,
				array( 'ORDER BY' => $indexCol, 'LIMIT' => $this->mBatchSize )
			);
			$n = $dbw->numRows( $res );

			if ( $n ) {
				$res->seek( $n - 1 );
				$blockEnd = $dbw->fetchObject( $res )->$indexCol;
				$this->output( "...doing $indexCol from $blockStart to $blockEnd, $n row(s)\n" );
			}

			foreach ( $res as $row ) {
				$pTable = $rdbStore->getPartition( $table, $shardCol, $row->$shardCol );
				$count += $pTable->insert( (array)$row, __METHOD__, array( 'IGNORE' ) );
			}

			$first = false;
			$blockStart = $blockEnd; // highest ID done so far
			wfWaitForSlaves( 5 );
		} while ( $n > 0 );

		$this->output( "Done. Inserted $count rows from '$table' table.\n" );
	}
}

$maintClass = "RDBPopulation";
require_once( RUN_MAINTENANCE_IF_MAIN );
