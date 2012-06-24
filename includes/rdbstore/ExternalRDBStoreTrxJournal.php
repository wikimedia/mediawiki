<?php
/**
 * This file deals with sharded RDBMs stores.
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
 * @ingroup RDBStore
 * @author Aaron Schulz
 */

/**
 * Class that helps with cross-shard commits and anomaly correction in external RDB stores.
 * This can correct missing, stale, or extreneous rows due to failed cross-shard transactions
 * with respect to rows that are duplicated and stored in multiple places (due to access patterns).
 *
 * If queries change a canonical shard of a denormalized table, this can durably write
 * the row IDs changed to a special journalling file before committing the main changes.
 * When journaling is used, commits are done essentially in three phases:
 *   - a) Changes are proposed on the relevant DBs, starting local transactions
 *   - b) Concensus is reached and journals are updated for the canonical shard DBs
 *   - c) Changes are committed on the relevant DBs, ending local transactions
 *
 * These log files are deleted when everything is committed successfully. Old log files are
 * assumed to correspond to failed transactions and are used to repair the affected DB rows.
 * This only fixes errors where duplicate or "index" shard tables fall out of sync with the
 * canonical table shard. It does fix all generic cross-shard transaction failures.
 *
 * @ingroup RDBStore
 * @since 1.20
 */
class ExternalRDBStoreTrxJournal {
	/** @var ExternalRDBStore */
	protected $rdbStore;
	/** @var BagOStuff */
	protected $srvCache;
	/** @var Array */
	protected $rowRefs = array(); // list of field/value maps for each row

	protected $directory; // string; path
	protected $trxId; // string; timestamped UUID
	protected $phase = self::PHASE_READY; // integer

	// Possible states
	const PHASE_READY      = 1; // not in a transaction
	const PHASE_PROPOSING  = 2; // in a cross-DB transaction, proposing changes
	const PHASE_COMMITTING = 3; // agreement on all changes, ready for COMMITs

	// Array keys of table row references stored in .tlog files
	const IDX_WIKI      = 0;
	const IDX_TABLE     = 1;
	const IDX_SHARD_COL = 2;
	const IDX_SHARD_VAL = 3;
	const IDX_UID_COL   = 4;
	const IDX_UID_VAL   = 5;
	const IDX_REFS      = 6;

	const STALE_SEC    = 300; // how old a .tlog must be to be "stale"
	const CHECK_SEC    = 60; // how long to go before checking for stale .tlog files
	const MAX_LOGS_FIX = 10; // max stale .tlog files to fix in one run

	/**
	 * @param $rdbStore ExternalRDBStore
	 */
	public function __construct( ExternalRDBStore $rdbStore ) {
		$this->rdbStore  = $rdbStore;
		$this->directory = wfTempDir() . '/rdbstore-tlogs/' . rawurlencode( $rdbStore->getName() );
		try {
			$cache = ObjectCache::newAccelerator( array() ); // local server cache
		} catch ( MWException $e ) {
			$cache = new EmptyBagOStuff();
		}
		$this->srvCache = $cache;
	}

	/**
	 * Function to be called right after transaction BEGIN
	 *
	 * @return void
	 */
	public function onPrePropose() {
		if ( $this->phase !== self::PHASE_READY ) {
			throw new DBUnexpectedError( "Transaction already in progress." );
		}
		$this->trxId   = null;
		$this->rowRefs = array();
		$this->phase   = self::PHASE_PROPOSING;
	}

	/**
	 * Function to be called right before transaction COMMIT
	 *
	 * @return void
	 */
	public function onPreCommit() {
		if ( $this->phase !== self::PHASE_PROPOSING ) {
			throw new DBUnexpectedError( "No transaction in progress." );
		}
		wfProfileIn( __METHOD__ );
		if ( count( $this->rowRefs ) ) {
			$this->trxId = wfTimestamp( TS_MW ) . '-' . wfRandomString( 18 ); // UUID
			// Durably log the affected rows to a file in /tmp space
			$data = serialize( $this->rowRefs );
			wfMkdirParents( $this->directory ); // create dir if needed
			$bytes = file_put_contents( "{$this->directory}/{$this->trxId}.tlog", $data );
			if ( $bytes !== strlen( $data ) ) {
				wfProfileOut( __METHOD__ );
				throw new DBUnexpectedError( "Could not write to '{$this->trxId}.tlog' file." );
			}
			if ( !wfIsWindows() ) { // *nix
				wfShellExec( 'sync' ); // XXX: PHP has no fsync()
			}
			$this->rowRefs = array(); // rows now journaled
		}
		$this->phase = self::PHASE_COMMITTING;
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Function to be called right after transaction commit
	 *
	 * @return void
	 */
	public function onPostCommit() {
		if ( $this->phase !== self::PHASE_COMMITTING ) {
			throw new DBUnexpectedError( "Transaction is not ready to commit." );
		}
		wfProfileIn( __METHOD__ );
		// Since this succeeded, we can throw away the log entry
		if ( $this->trxId !== null ) {
			unlink( "{$this->directory}/{$this->trxId}.tlog" );
			$this->trxId = null;
		}
		$this->phase = self::PHASE_READY;
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Note rows as changing in a cross-shard transaction if $key is the cannonical shard key
	 *
	 * @param $tp ExternalRDBStoreTablePartition
	 * @param $rows array DB row objects changed
	 * @return void
	 */
	public function includeFromRowList( ExternalRDBStoreTablePartition $tp, array $rows ) {
		if ( $this->phase !== self::PHASE_PROPOSING ) {
			throw new DBUnexpectedError( "Transaction is not in progress." );
		}
		$tinfo = RDBStoreGroup::singleton()->getDenormalizedTableInfo( $tp->getVirtualTable() );
		if ( is_array( $tinfo ) && $tinfo['canonicalShardKey'] === $tp->getPartitionKey() ) {
			$this->includeRowsFromList( $tinfo, $tp, $rows );
		}
	}

	/**
	 * Note rows as changing in a cross-shard transaction if $key is the cannonical shard key
	 *
	 * @param $tp ExternalRDBStoreTablePartition
	 * @param $conds array Query conditions
	 * @return void
	 */
	public function includeFromRowConds( ExternalRDBStoreTablePartition $tp, array $conds ) {
		if ( $this->phase !== self::PHASE_PROPOSING ) {
			throw new DBUnexpectedError( "Transaction is not in progress." );
		}
		$tinfo = RDBStoreGroup::singleton()->getDenormalizedTableInfo( $tp->getVirtualTable() );
		if ( is_array( $tinfo ) && $tinfo['canonicalShardKey'] === $tp->getPartitionKey() ) {
			$rows = array();
			$res = $tp->select( DB_MASTER, '*', $conds, __METHOD__, array( 'FOR UPDATE' ) );
			foreach ( $res as $row ) {
				$rows[] = (array)$row;
			}
			$this->includeRowsFromList( $tinfo, $tp, $rows );
		}
	}

	/**
	 * @param $tinfo array Table schema information
	 * @param $tp ExternalRDBStoreTablePartition
	 * @param $rows array DB row objects changed
	 * @return void
	 */
	protected function includeRowsFromList(
		array $tinfo, ExternalRDBStoreTablePartition $tp, array $rows
	) {
		foreach ( $rows as $row ) {
			// Store a reference to the row on the canonical shard...
			$rowRef = array(
				self::IDX_WIKI      => $tp->getWiki(), // wiki ID
				self::IDX_TABLE     => $tp->getVirtualTable(), // table name
				self::IDX_SHARD_COL => $tinfo['canonicalShardKey'], // shard column name
				self::IDX_SHARD_VAL => $row[$tinfo['canonicalShardKey']], // shard column value
				self::IDX_UID_COL   => $tinfo['rowIdColumn'], // ID column name
				self::IDX_UID_VAL   => $row[$tinfo['rowIdColumn']], // ID column value
				self::IDX_REFS      => array() // refs to rows on other shards
			);
			// Nest in references to the row on the denormalized shards...
			foreach ( $tinfo['secondaryShardKeys'] as $shardKey ) {
				$rowRef[self::IDX_REFS][] = array(
					self::IDX_SHARD_COL => $shardKey, // shard column name
					self::IDX_SHARD_VAL => $row[$shardKey], // shard column value
				);
			}
			$this->rowRefs[] = $rowRef;
		}
	}

	/**
	 * @return integer Number of .tlog files consumed
	 */
	public function periodicLogCheck() {
		$key  = "rdbstore-collect-{$this->rdbStore->getName()}";
		$time = $this->srvCache->get( $key );
		if ( !is_int( $time ) ) { // cache miss
			wfSuppressWarnings();
			$time = filemtime( "{$this->directory}.collect" ); // false if file doesn't exist
			wfRestoreWarnings();
			$this->srvCache->set( $key, (int)$time, 86400 );
		}
		if ( $time < ( time() - self::CHECK_SEC ) ) {
			$this->srvCache->set( $key, time(), 86400 );
			wfDebug( __METHOD__ . ": Checking for stale tlog files.\n" );
			$count = $this->fixDBFromLogs();
		} else {
			wfDebug( __METHOD__ . ": Skipping check for stale tlog files.\n" );
			$count = 0;
		}
		return $count;
	}

	/**
	 * Find stale .tlog files lying around in /tmp and fix the DB rows.
	 * This deals with inconsistent denormalized rows resulting from prior incomplete
	 * transactions on this server. The .tlog files are removed once the DB is synced.
	 *
	 * @return integer Number of .tlog files consumed
	 */
	public function fixDBFromLogs() {
		if ( $this->rdbStore->hasTransaction() ) { // sanity check
			throw new DBUnexpectedError( 'RDB store is already in a transaction.' );
		}
		wfProfileIn( __METHOD__ );

		$cutoff = time() - self::STALE_SEC;
		// Make sure only one process on this server is doing this...
		wfMkdirParents( $this->directory ); // create dir if needed
		$lockFileHandle = fopen( "{$this->directory}.collect", 'w' ); // open & touch
		if ( !$lockFileHandle ) {
			wfProfileOut( __METHOD__ );
			return false; // bail out
		} elseif ( !flock( $lockFileHandle, LOCK_EX | LOCK_NB ) ) {
			fclose( $lockFileHandle );
			wfProfileOut( __METHOD__ );
			return false; // someone else probably beat us
		}

		$e = null; // exception
		try {
			$tblobs = array(); // (tlog basename => contents of file)
			// Get all of the old .tlog files lying around...
			$dirHandle = opendir( $this->directory );
			if ( $dirHandle ) {
				while ( $file = readdir( $dirHandle ) ) {
					if ( FileBackend::extensionFromPath( $file ) !== 'tlog' ) {
						continue; // sanity
					} elseif ( filemtime( "{$this->directory}/{$file}" ) > $cutoff ) {
						continue; // wait; transaction may still be in progress
					}
					$data = file_get_contents( "{$this->directory}/{$file}" );
					if ( $data === false ) {
						continue; // sanity
					}
					$tblobs[$file] = $data; // delete this afterwards
					if ( count( $tblobs ) >= self::MAX_LOGS_FIX ) {
						break; // only handle so many tlog files
					}
				}
				closedir( $dirHandle );
			}
			wfDebugLog( 'RDBStore', "Detected {count($tblobs)} prior failed transactions.\n" );
			// Run through the failed transaction log and re-sync any duplicate
			// rows on non-canonical table shards that are found to be out of sync.
			foreach ( $tblobs as $file => $blob ) {
				$rowRefs = unserialize( $blob );
				if ( is_array( $rowRefs ) ) { // sanity
					foreach ( $rowRefs as $rowRef ) {
						$this->correctRowSyncAnomalies( $rowRef );
					}
				}
				unlink( "{$this->directory}/{$file}" ); // done with this tlog file
			}
			wfDebugLog( 'RDBStore', "Repaired {count($tblobs)} prior failed transactions.\n" );
		} catch ( Exception $e ) {}

		// Release locks so other processes can poll these files...
		flock( $lockFileHandle, LOCK_UN );
		fclose( $lockFileHandle );

		wfProfileOut( __METHOD__ );
		if ( $e instanceof Exception ) {
			throw $e; // throw back any exception
		}

		return count( $tblobs );
	}

	/**
	 * Actually re-sync the rows referenced in $rowRef
	 *
	 * @param $rowRef array Reference to a row in a canonical shard
	 * @return void
	 */
	protected function correctRowSyncAnomalies( array $rowRef ) {
		$this->rdbStore->begin();
		// Get the canonical partition...
		$ctpartition = $this->rdbStore->getPartition(
			$rowRef[self::IDX_TABLE],
			$rowRef[self::IDX_SHARD_COL],
			$rowRef[self::IDX_SHARD_VAL],
			$rowRef[self::IDX_WIKI]
		);
		// Get the row on the canonical partition and lock it
		$row = (array)$ctpartition->selectRow( DB_MASTER, '*',
			array( $rowRef[self::IDX_UID_COL] => $rowRef[self::IDX_UID_VAL] ),
			__METHOD__,
			array( 'FOR UPDATE' ) // lock
		);
		// Check if the denormalized rows match this row...
		foreach ( $rowRef[self::IDX_REFS] as $dupRowRef ) {
			// Get the duplicate partition...
			$dtpartition = $this->rdbStore->getPartition(
				$rowRef[self::IDX_TABLE],
				$dupRowRef[self::IDX_SHARD_COL],
				$dupRowRef[self::IDX_SHARD_VAL],
				$rowRef[self::IDX_WIKI]
			);
			// Get the row on the denormalized partition and lock it
			$dupRow = (array)$dtpartition->selectRow( DB_MASTER, '*',
				array( $rowRef[self::IDX_UID_COL] => $rowRef[self::IDX_UID_VAL] ),
				__METHOD__,
				array( 'FOR UPDATE' ) // lock
			);
			// Check if the rows are identical, and sync them if needed...
			// (the duplicate row may be from an "index table", not having all columns)
			if ( array_intersect_key( $row, $dupRow ) !== $dupRow ) { // not synced
				if ( $row === array() ) { // remove row from denormalized shard
					$dtpartition->getMasterDB()->delete(
						$dtpartition->getPartitionTable(),
						array( $rowRef[self::IDX_UID_COL] => $rowRef[self::IDX_UID_VAL] ),
						__METHOD__
					);
				} elseif ( $dupRow === array() ) { // insert row to denormalized shard
					$dtpartition->getMasterDB()->insert(
						$dtpartition->getPartitionTable(),
						array_intersect_key( $row, $dupRow ),
						__METHOD__,
						array( 'IGNORE' ) // ignore races
					);
				} else { // update row on denormalized shard
					$dtpartition->getMasterDB()->update(
						$dtpartition->getPartitionTable(),
						array_intersect_key( $row, $dupRow ),
						array( $rowRef[self::IDX_UID_COL] => $rowRef[self::IDX_UID_VAL] ),
						__METHOD__
					);
				}
			}
		}
		$this->rdbStore->finish();
	}
}
