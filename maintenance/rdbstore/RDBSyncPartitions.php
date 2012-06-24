<?php
/**
 * Tool to sync denormalized shards in a sharded DB storage.
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
 * Maintenance script to fix denormalized shards in a sharded DB store
 *
 * @ingroup Maintenance
 */
class RDBSyncPartitions extends Maintenance {
	protected $sTimestamp; // integer; UNIX timestamp
	protected $eTimestamp; // integer; UNIX timestamp

	const TRX_LIMBO_SEC = 86400; // integer; prepared transactions this old are stale

	public function __construct() {
		parent::__construct();
		$this->addOption( "store", "The external RDB store name", true, true );
		$this->addOption( "cluster", "The name of a DB cluster within the store", true, true );
		$this->addOption( "posdir", "Directory to store last-scan timestamp", true, true );
		$this->addOption( "fixlimbo", "Resolve all day old limbo transactions (for 2PC)" );
		$this->mDescription = "Sync denormalized table shards within sharded DB storage";
	}

	public function execute() {
		global $wgRDBStoredTables;

		$store    = $this->getOption( 'store' );
		$cluster  = $this->getOption( 'cluster' );
		$posdir   = $this->getOption( 'posdir' );
		$fixlimbo = $this->hasOption( 'fixlimbo' );
		$wiki     = wfWikiID();

		$rdbs    = RDBStoreGroup::singleton()->getExternal( $this->getOption( 'store' ) );
		$mapping = $rdbs->getClusterMapping(); // (cluster => indexes)
		if ( !isset( $mapping[$cluster] ) ) {
			$this->error( "Cluster '$cluster' does not exist in store '$store'.\n", 1 ); // die
		}
		$indexes = $mapping[$cluster]; // indexes handled by this cluster

		wfMkdirParents( "{$posdir}/{$cluster}" );
		wfSuppressWarnings();
		$startTsUnix = (int)file_get_contents( "{$posdir}/{$cluster}/{$wiki}" );
		wfRestoreWarnings();
		$startTsUnix = $startTsUnix ? $startTsUnix : 1; /* default to ~epoch */

		$lb  = wfGetLBFactory()->getExternalLB( $cluster );
		$dbw = $lb->getConnection( DB_MASTER );

		$endTsUnix = time(); // do not scan rows added after this point
		if ( $rdbs->is2PCEnabled() ) {
			$this->output( "Checking for limbo transactions in RDB store '$store'...\n" );
			foreach ( $rdbs->connGetAllPreparedXAInternal( $dbw ) as $trxId ) {
				if ( preg_match( '/^(\d{14})-([0-9a-f]{32})$/', $trxId, $matches ) ) {
					list( /* all */, $ts, /* uuid */ ) = $matches;
					$tsUnix = wfTimestamp( TS_UNIX, $ts );
					if ( $tsUnix < ( time() - self::TRX_LIMBO_SEC ) ) {
						if ( $fixlimbo ) { // resolve limbo transaction
							$rdbs->connRollbackXAInternal( $dbw, $trxId );
							$this->output( "Rolled back limbo transaction '$trxId'.\n" );
						} else {
							$this->output( "Detected limbo transaction '$trxId'.\n" );
							$endTsUnix = min( $tsUnix, $endTsUnix );
						}
					} else {
						$endTsUnix = min( $tsUnix, $endTsUnix );
					}
				}
			}
		}
		$this->output( "Done.\n" );

		$this->sTimestamp = $startTsUnix;
		$this->eTimestamp = $endTsUnix - 900; // good measure (skew factor)

		$this->output( "Row timestamp scan range is [" .
			wfTimestamp( TS_MW, $this->sTimestamp ) . "," .
			wfTimestamp( TS_MW, $this->eTimestamp ) . "]\n"
		);

		$this->output( "Syncronizing denormalized data in RDB store '$store'...\n" );
		if ( $rdbs->isPartitioned() && isset( $wgRDBStoredTables[$wiki] ) ) {
			foreach ( $wgRDBStoredTables[$wiki] as $table => $tstore ) { // each table
				$syncInfo = RDBStoreGroup::singleton()->getTableSyncInfo( $table );
				if ( $tstore === $store && is_array( $syncInfo ) ) { // syncable
					$this->output( "Syncing table $table...\n" );
					$count = $this->syncPush( $rdbs, $table, $syncInfo, $indexes );
					$this->output( "Pushed $count row(s) missing rows.\n" );
					$count = $this->syncPull( $rdbs, $table, $syncInfo, $indexes );
					$this->output( "Removed $count row(s) bogus rows.\n" );
				}
			}
		}
		$this->output( "Done.\n" );

		if ( file_put_contents( "{$posdir}/{$cluster}/{$wiki}", $this->eTimestamp ) !== false ) {
			$this->output( "Updated UNIX timestamp position file to '{$this->eTimestamp}'.\n" );
		}
	}

	/**
	 * Get the column => value pairs of $row with columns in the partition table
	 *
	 * @param $row array
	 * @param $tp RDBStoreTablePartition
	 */
	protected function getRowForTable( array $row, RDBStoreTablePartition $tp ) {
		$resRow = array();
		foreach ( $row as $field => $value ) {
			if ( $tp->getMasterDB()->fieldExists( $tp->getPartitionTable(), $field ) ) {
				$resRow[$field] = $value;
			}
		}
		return $resRow;
	}

	/**
	 * Push updates from the canonical source shards to the duplicate shards.
	 * We don't need to select FOR UPDATE as we should only sync immutable data.
	 *
	 * @return integer Number of rows fixed
	 */
	protected function syncPush( RDBStore $rdbs, $table, array $info, array $indexes ) {
		$cpartitions = array_map( $indexes, function( $index ) {
			return new ExternalRDBStoreTablePartition(
				$rdbs, $table, $index, $info['srcShardKey'], null, wfWikiID() );
		} );
		$count = 0;
		foreach ( $cpartitions as $cpartition ) { // for each canonical shard
			$dbw   = $cpartition->getMasterDB();
			$start = $dbw->addQuotes( $dbw->timestamp( $this->sTimestamp ) );
			$end   = $dbw->addQuotes( $dbw->timestamp( $this->eTimestamp ) );
			$res   = $cpartition->select( DB_MASTER, '*',
				array( "{$info['timeColumn']} BETWEEN $start AND $end" ),
				__METHOD__
			);
			foreach ( $res as $srcRow ) { // each canonical row
				$srcRow = (array)$srcRow;
				foreach ( $info['dupShardKeys'] as $shardKey ) { // each duplicate shard for row
					$dpartition = $rdbs->getPartition( $table, $shardKey, $srcRow[$shardKey] );
					$dupRow = $dpartition->selectRow( DB_MASTER, '*',
						array_intersect_key( $srcRow, $info['uniqueKey'] ),
						__METHOD__
					);
					if ( !$dupRow ) { // row missing in duplicate shard; create it
						$dupRow = $this->getRowForTable( $srcRow, $dpartition );
						$dpartition->insert( $dupRow, __METHOD__, array( 'IGNORE' ) );
						$count++;
					}
				}
			}
		}
		return $count;
	}

	/**
	 * Pull updates from the canonical source shards to the duplicate shards.
	 * We don't need to select FOR UPDATE as we should only sync immutable data.
	 *
	 * @return integer Number of rows fixed
	 */
	protected function syncPull( RDBStore $rdbs, $table, array $info, array $indexes ) {
		$count = 0;
		foreach ( $info['dupShardKeys'] as $shardKey ) { // each shard key column
			$dpartitions = array_map( $indexes, function( $index ) {
				return new ExternalRDBStoreTablePartition(
					$rdbs, $table, $index, $shardKey, null, wfWikiID() );
			} );
			foreach ( $dpartitions as $dpartition ) { // each duplicate shard
				$dbw   = $dpartition->getMasterDB();
				$start = $dbw->addQuotes( $dbw->timestamp( $this->sTimestamp ) );
				$end   = $dbw->addQuotes( $dbw->timestamp( $this->eTimestamp ) );
				$res   = $dpartition->select( DB_MASTER, '*',
					array( "{$info['timeColumn']} BETWEEN $start AND $end" ),
					__METHOD__
				);
				foreach ( $res as $dupRow ) { // each duplicate row
					$dupRow = (array)$dupRow;
					$cpartition = $rdbs->getPartition( $table, // canonical shard for row
						$info['srcShardKey'], $dupRow[$info['srcShardKey']] );
					$exists = $cpartition->selectField( DB_MASTER, '1',
						array_intersect_key( $dupRow, $info['uniqueKey'] ),
						__METHOD__
					);
					if ( !$exists ) { // row in duplicate shard is extraneous; delete it
						$dpartition->delete( $dupRow, __METHOD__ );
						$count++;
					}
				}
			}
		}
		return $count;
	}
}

$maintClass = "RDBSyncPartitions";
require_once( RUN_MAINTENANCE_IF_MAIN );
