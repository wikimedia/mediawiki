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
		$this->setBatchSize( 1000 );
	}

	public function execute() {
		$table = $this->getOption( 'table' );
		$indexCol = $this->getOption( 'indexColumn' );
		$shardCol = $this->getOption( 'shardColumn' );

		$db = $this->getDB( DB_MASTER );
		$start = $db->selectField( $table, "MIN($indexCol)", false, __METHOD__ );
		$end = $db->selectField( $table, "MAX($indexCol)", false, __METHOD__ );
		if ( !$start || !$end ) {
			$this->output( "...$table table seems to be empty.\n" );
			return 0;
		}

		$rdbStore = RDBStoreGroup::singleton()->getExternal( $this->getOption( 'store' ) );

		# Do remaining chunk
		$end += $this->mBatchSize - 1;
		$blockStart = $start;
		$blockEnd = $start + $this->mBatchSize - 1;

		$count = 0;
		while ( $blockEnd <= $end ) {
			$this->output( "...doing $indexCol from $blockStart to $blockEnd\n" );
			$cond = "$indexCol BETWEEN $blockStart AND $blockEnd";
			$res = $db->select( $table, '*', $cond, __METHOD__ );

			foreach ( $res as $row ) {
				if ( !isset( $row->$shardCol ) ) {
					$this->error( "Table '$table' missing column '$shardCol'.", 1 );
				}
				$pTable = $rdbStore->getPartition( $table, $shardCol, $row->$shardCol );
				$pTable->insert( (array)$row, __METHOD__, array( 'IGNORE' ) );
				$count++;
			}

			$blockStart += $this->mBatchSize;
			$blockEnd += $this->mBatchSize;
			wfWaitForSlaves( 5 );
		}

		$this->output( "Done. Inserted $count rows from '$table' table.\n" );
	}
}

$maintClass = "RDBPopulation";
require_once( RUN_MAINTENANCE_IF_MAIN );
