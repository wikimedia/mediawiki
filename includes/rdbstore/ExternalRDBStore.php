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
 * Class representing an external DB storage system.
 * Tables are sharded vertically based on the wiki ID and
 * horizontally based on a table column used as a "shard key".
 *
 * The shard key determines what cluster a table partition maps to.
 * We use cluster = (integerhash(column value) mod (# of clusters)) + 1.
 * The 1 is added to the remainder so the cluster names start at "1".
 *
 * The number of clusters must be a power of 2. This makes the re-balancing required
 * with the addition of new clusters fairly straightforward and avoids downtime.
 * For example, say we have four clusters:
 *   cluster1 [hash has remainder 0]
 *   cluster2 [hash has remainder 1]
 *   cluster3 [hash has remainder 2]
 *   cluster4 [hash has remainder 3]
 * We can add four new clusters, resulting in the following:
 *   cluster1 [remainder (0 now, 0 before)]
 *   cluster2 [remainder (1 now, 1 before)]
 *   cluster3 [remainder (2 now, 2 before)]
 *   cluster4 [remainder (3 now, 3 before)]
 *   cluster5 [start as replica of cluster1] [remainder (4 now, 0 before)]
 *   cluster6 [start as replica of cluster2] [remainder (5 now, 1 before)]
 *   cluster7 [start as replica of cluster3] [remainder (6 now, 2 before)]
 *   cluster8 [start as replica of cluster4] [remainder (7 now, 3 before)]
 * What was in cluster1 is now split between cluster1 and cluster5.
 * Since cluster5 started as a full clone of cluster1 (via MySQL replication),
 * the only disruption will be a brief read-only period where cluster5 is
 * caught up and the ExternalRDBStore cluster config is updated on the servers.
 * After the change is done, there will be outdated, duplicate, partition tables
 * that are on the wrong shard and no longer used. These can be dropped as needed.
 *
 * The same trick can be used to keep doubling the amount of storage.
 *
 * @ingroup RDBStore
 * @since 1.20
 */
class ExternalRDBStore extends RDBStore {
	/** @var Array */
	protected $clusters = array(); // list of cluster names
	/** @var Array */
	protected $trxJournals = array(); // (cluster name => ExternalRDBStoreTrxJournal)

	protected $name; // string
	protected $clusterCount; // integer
	protected $clusterPrefix; // string

	const SHARDS = 256; // integer; consistent partitions per (wiki,table) (power of 2)

	/**
	 * @param $options array
	 * @throws MWException
	 */
	public function __construct( array $options ) {
		parent::__construct( $options );

		$this->name = $options['name'];
		if ( !strlen( $options['clusterPrefix'] ) ) {
			throw new MWException( "Option 'clusterPrefix' is not valid." );
		}
		$this->clusterPrefix = $options['clusterPrefix'];

		$logB2 = log( $options['clusterCount'], 2 ); // float
		if ( $logB2 != floor( $logB2 ) ) {
			throw new MWException( "Option 'clusterCount' must be a power of 2." );
		}
		$this->clusterCount = $options['clusterCount'];

		for ( $i = 1; $i <= $options['clusterCount']; $i++ ) {
			$this->clusters[] = $options['clusterPrefix'] . $i;
		}
	}

	/**
	 * @see RDBStore::getName()
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @see RDBStore::isInternal()
	 * @return bool
	 */
	public function isInternal() {
		return false;
	}

	/**
	 * Check if tables are actually partitioned in the store
	 *
	 * @return bool
	 */
	public function isPartitioned() {
		return true;
	}

	/**
	 * @see RDBStore::beginOutermost()
	 * @see DatabaseBase::begin()
	 * @return bool
	 */
	public function beginOutermost() {
		return true;
	}

	/**
	 * @see RDBStore::finishOutermost()
	 * @see DatabaseBase::commit()
	 * @return bool
	 */
	public function finishOutermost() {
		$funcCommit = function( DatabaseBase $conn ) {
			if ( $conn->trxLevel() ) {
				$conn->commit(); // finish transaction
			}
		};

		foreach ( $this->trxJournals as $tj ) { // each journal used
			if ( $tj->getPhase() === ExternalRDBStoreTrxJournal::PHASE_PROPOSING ) {
				$tj->onPreCommit(); // affected rows as changing
			}
		}
		foreach ( $this->clusters as $cluster ) { // for existing connections
			$this->getClusterLB( $cluster )->forEachOpenConnection( $funcCommit );
		}
		foreach ( $this->trxJournals as $tj ) { // each journal used
			if ( $tj->getPhase() === ExternalRDBStoreTrxJournal::PHASE_COMMITTING ) {
				$tj->onPostCommit(); // affected rows no longer changing
			}
		}

		return true;
	}

	/**
	 * Get an object representing a shard of a virtual DB table.
	 * Each table is sharded on at least one column key, and possibly
	 * denormalized and sharded on muliple column keys (e.g. rev ID, page ID, user ID).
	 *
	 * @see RDBStore::doGetPartition()
	 * @return ExternalRDBStoreTablePartition
	 */
	protected function doGetPartition( $table, $column, $value, $wiki ) {
		// Map this row to a consistent table shard, which only depends on $value.
		// This mapping MUST always remain consistent (immutable)!
		$hash  = substr( sha1( $value ), 0, 4 ); // 65535 possible values
		$index = (int)base_convert( $hash, 16, 10 ) % self::SHARDS; // [0,1023]
		return new ExternalRDBStoreTablePartition( $this, $table, $index, $column, $value, $wiki );
	}

	/**
	 * @see RDBStore::doGetAllPartitions()
	 * @return Array List of ExternalRDBStoreTablePartition objects
	 */
	protected function doGetAllPartitions( $table, $column, $wiki ) {
		$partitions = array();
		foreach ( $this->getClusterMapping() as $cluster => $indexes ) {
			foreach ( $indexes as $index ) {
				$partitions[] = new ExternalRDBStoreTablePartition(
					$this, $table, $index, $column, null, $wiki );
			}
		}
		return $partitions;
	}

	/**
	 * @return Array List of cluster names for this store
	 */
	public function getClusters() {
		return $this->clusters;
	}

	/**
	 * Get a map of DB cluster names to shard indexes they serve.
	 * Outside callers should generally not need this and should avoid using it.
	 *
	 * @return Array
	 */
	public function getClusterMapping() {
		$map = array();
		for ( $index = 0; $index < self::SHARDS; $index++ ) {
			$map[$this->getClusterForIndex( $index )][] = $index;
		}
		return $map;
	}

	/**
	 * @param $index integer Partition index
	 * @return string
	 * @throws MWException
	 */
	public function getClusterForIndex( $index ) {
		if ( $index < 0 || $index >= self::SHARDS ) {
			throw new MWException( "Index $index is not a valid partition index." );
		}
		// This mapping MUST always remain consistent (immutable)!
		return $this->clusterPrefix . (( $index % $this->clusterCount ) + 1);
	}

	/**
	 * @param $index integer Partition index
	 * @return LoadBalancer For the cluster the partition index is on
	 */
	public function getClusterLBForIndex( $index ) {
		return $this->getClusterLB( $this->getClusterForIndex( $index ) );
	}

	/**
	 * @param $index integer Partition index
	 * @return ExternalRDBStoreTrxJournal
	 */
	public function getClusterTJForIndex( $index ) {
		$cluster = $this->getClusterForIndex( $index );
		if ( !isset( $this->trxJournals[$cluster] ) ) {
			$this->trxJournals[$cluster] = new ExternalRDBStoreTrxJournal( $this, $cluster );
		}
		return $this->trxJournals[$cluster];
	}

	/**
	 * Return all table partitions that exist for a virtual table.
	 * This can be used to verify schema change completion for a table.
	 *
	 * @see DatabaseBase::tableExists()
	 * @param $table string Virtual table name
	 * @param $column string Shard key column name
	 * @param $wiki string Wiki ID (default to current wiki)
	 * @return Array List of partition table names
	 */
	public function partitionsWhereTableExists( $table, $column, $wiki = false ) {
		$ctpartitions = array();
		$wiki = ( $wiki === false ) ? wfWikiID() : $wiki;
		foreach ( $this->doGetAllPartitions( $table, $column, $wiki ) as $tp ) {
			if ( $tp->getMasterDB()->tableExists( $tp->getPartitionTable() ) ) {
				$ctpartitions[] = $tp->getPartitionTable();
			}
		}
		return $ctpartitions;
	}

	/**
	 * Return all table partitions that exist for a virtual table.
	 * This can be used to verify schema change completion for a table.
	 *
	 * @see DatabaseBase::tableExists()
	 * @param $table string Virtual table name
	 * @param $column string Shard key column name
	 * @param $wiki string Wiki ID (default to current wiki)
	 * @return Array List of partition table names
	 */
	public function partitionsWhereTableNotExists( $table, $column, $wiki = false ) {
		return array_diff(
			$this->getPartitionTableNames( $table, $column ),
			$this->partitionsWhereTableExists( $table, $column, $wiki )
		);
	}

	/**
	 * Return all table partitions that have a specified column.
	 * This can be used to verify schema change completion for a table.
	 *
	 * @see DatabaseBase::fieldExists()
	 * @param $table string Virtual table name
	 * @param $column string Shard key column name
	 * @param $field string Column name to check
	 * @param $wiki string Wiki ID (default to current wiki)
	 * @return Array List of partition table names
	 */
	public function partitionsWhereFieldExists( $table, $column, $field, $wiki = false ) {
		$ctpartitions = array();
		$wiki = ( $wiki === false ) ? wfWikiID() : $wiki;
		foreach ( $this->doGetAllPartitions( $table, $column, $wiki ) as $tp ) {
			if ( $tp->getMasterDB()->fieldExists( $tp->getPartitionTable(), $field ) ) {
				$ctpartitions[] = $tp->getPartitionTable();
			}
		}
		return $ctpartitions;
	}

	/**
	 * Return all table partitions that do not have a specified column.
	 * This can be used to verify schema change completion for a table.
	 *
	 * @see DatabaseBase::fieldExists()
	 * @param $table string Virtual table name
	 * @param $column string Shard key column name
	 * @param $field string Column name to check
	 * @param $wiki string Wiki ID (default to current wiki)
	 * @return Array List of partition table names
	 */
	public function partitionsWhereFieldNotExists( $table, $column, $field, $wiki = false ) {
		return array_diff(
			$this->getPartitionTableNames( $table, $column ),
			$this->partitionsWhereFieldExists( $table, $column, $field, $wiki )
		);
	}

	/**
	 * Return all table partitions that have a specified index.
	 * This can be used to verify schema change completion for a table.
	 *
	 * @see DatabaseBase::indexExists()
	 * @param $table string Virtual table name
	 * @param $column string Shard key column name
	 * @param $index string Index name to check
	 * @param $wiki string Wiki ID (default to current wiki)
	 * @return Array List of partition table names
	 */
	public function partitionsWhereIndexExists( $table, $column, $index, $wiki = false ) {
		$ctpartitions = array();
		$wiki = ( $wiki === false ) ? wfWikiID() : $wiki;
		foreach ( $this->doGetAllPartitions( $table, $column, $wiki ) as $tp ) {
			if ( $tp->getMasterDB()->indexExists( $tp->getPartitionTable(), $index ) ) {
				$ctpartitions[] = $tp->getPartitionTable();
			}
		}
		return $ctpartitions;
	}

	/**
	 * Return all table partitions that do not have a specified index.
	 * This can be used to verify schema change completion for a table.
	 *
	 * @see DatabaseBase::indexExists()
	 * @param $table string Virtual table name
	 * @param $column string Shard key column name
	 * @param $index string Index name to check
	 * @param $wiki string Wiki ID (default to current wiki)
	 * @return Array List of partition table names
	 */
	public function partitionsWhereIndexNotExists( $table, $column, $index, $wiki = false ) {
		return array_diff(
			$this->getPartitionTableNames( $table, $column ),
			$this->partitionsWhereIndexExists( $table, $column, $index, $wiki )
		);
	}

	/**
	 * Format a shard number by padding out the digits as needed.
	 * Outside callers should generally not need this and should avoid using it.
	 *
	 * @param $index integer
	 * @return string
	 */
	public function formatShardIndex( $index ) {
		$decimals = strlen( self::SHARDS - 1 );
		return sprintf( "%0{$decimals}d", $index ); // e.g "033"
	}

	/**
	 * @param $cluster string DB cluster name
	 * @return LoadBalancer
	 */
	protected function getClusterLB( $cluster ) {
		return wfGetLBFactory()->getExternalLB( $cluster );
	}

	/**
	 * Get a list of the partition tables for a given virtual DB table and shard column.
	 * Outside callers should generally not need this and should avoid using it.
	 *
	 * @param $table string Virtual DB table
	 * @param $column string Column the table is sharded on
	 * @return Array List of partition table names
	 */
	protected function getPartitionTableNames( $table, $column ) {
		if ( !is_string( $table ) || !is_string( $column ) ) {
			throw new DBUnexpectedError( "Missing table or column name." );
		}
		$pTables = array();
		for ( $index = 0; $index < self::SHARDS; $index++ ) {
			$shard = $this->formatShardIndex( $index ); // e.g "0033"
			$pTables[] = "{$table}__{$shard}__{$column}";
		}
		return $pTables;
	}
}

/**
 * Class representing a single partition of a virtual DB table
 *
 * @ingroup RDBStore
 * @since 1.20
 */
class ExternalRDBStoreTablePartition extends RDBStoreTablePartition {
	/** @var ExternalRDBStore */
	protected $rdbStore;
	/** @var LoadBalancer */
	protected $lb;
	/** @var ExternalRDBStoreTrxJournal */
	protected $tj;

	/**
	 * @param $rdbStore ExternalRDBStore
	 * @param $table string Virtual table name
	 * @param $index integer Partition index
	 * @param $key string Shard key column name
	 * @param $value array Shard key column value
	 * @param $wiki string Wiki ID
	 */
	public function __construct(
		ExternalRDBStore $rdbStore, $table, $index, $key, $value, $wiki
	) {
		$this->rdbStore = $rdbStore;
		$this->lb       = $rdbStore->getClusterLBForIndex( $index );
		$this->tj       = $rdbStore->getClusterTJForIndex( $index );
		$this->vTable   = $table;
		$this->sTable   = "{$table}__{$this->rdbStore->formatShardIndex( $index )}__{$key}";
		$this->key      = $key;
		$this->value    = $value;
		$this->wiki     = $wiki;
	}

	/**
	 * @see RDBStore::logInsert()
	 * @return void
	 */
	protected function logInsert( ExternalRDBStoreTablePartition $p, array $rows ) {
		if ( $this->rdbStore->hasTransaction() ) {
			if ( $this->tj->getPhase() === ExternalRDBStoreTrxJournal::PHASE_READY ) {
				$this->tj->onPrePropose(); // remember affected rows
			}
			$this->tj->bufferFromRowList( $p, $rows );
		}
	}

	/**
	 * @see RDBStore::logUpdate()
	 * @return void
	 */
	protected function logUpdate( ExternalRDBStoreTablePartition $p, array $conds ) {
		if ( $this->rdbStore->hasTransaction() ) {
			if ( $this->tj->getPhase() === ExternalRDBStoreTrxJournal::PHASE_READY ) {
				$this->tj->onPrePropose(); // remember affected rows
			}
			$this->tj->bufferFromRowConds( $p, $conds );
		}
	}

	/**
	 * @see RDBStore::logDelete()
	 * @return void
	 */
	protected function logDelete( ExternalRDBStoreTablePartition $p, array $conds ) {
		if ( $this->rdbStore->hasTransaction() ) {
			if ( $this->tj->getPhase() === ExternalRDBStoreTrxJournal::PHASE_READY ) {
				$this->tj->onPrePropose(); // remember affected rows
			}
			$this->tj->bufferFromRowConds( $p, $conds );
		}
	}

	/**
	 * @see RDBStoreTablePartition
	 * @return DatabaseBase
	 */
	public function getSlaveDB() {
		$conn = $this->lb->getConnection( DB_SLAVE, array(), $this->wiki );
		if ( $this->rdbStore->hasTransaction() ) {
			$conn->setFlag( DBO_TRX ); // wrap in transaction by default
		} else {
			$conn->clearFlag( DBO_TRX ); // auto-commit by default
		}
		return $conn;
	}

	/**
	 * @see RDBStoreTablePartition
	 * @return DatabaseBase
	 */
	public function getMasterDB() {
		$conn = $this->lb->getConnection( DB_MASTER, array(), $this->wiki );
		if ( $this->rdbStore->hasTransaction() ) {
			$conn->setFlag( DBO_TRX ); // wrap in transaction by default
		} else {
			$conn->clearFlag( DBO_TRX ); // auto-commit by default
		}
		return $conn;
	}
}

/**
 * Class that helps with cross-shard commits and anomaly correction in external RDB stores.
 * This can clean up after half-failed cross-shard transactions, by correcting missing, stale,
 * or extreneous rows. This assures stability and consistency, kind of like [[Agent Smith]].
 *
 * If write queries change a canonical shard of a denormalized table, this can durably write
 * the row IDs changed to a special journalling table before committing the main changes.
 * When journaling is used, commits are done essentially in three phases:
 *   - a) Changes are proposed on the relevant DBs, starting local transactions
 *   - b) Concensus is reached and journals are updated for the canonical shard DBs
 *   - c) Changes are committed on the relevant DBs, ending local transactions
 *
 * Journals entries are staged locally and only those for failed transactions get propagated
 * to one of the DB journal tables on a random cluster in the store. A background script can use
 * this journal to confirm and fix cross-shard changes. This only fixes errors where denormalized
 * duplicate or "index" shard tables fall out of sync with the canonical table shard. It cannot
 * fix all generic cross-shard transaction failures.
 *
 * @ingroup RDBStore
 * @since 1.20
 */
class ExternalRDBStoreTrxJournal {
	/** @var ExternalRDBStore */
	protected $dbStore;
	/** @var LoadBalancer */
	protected $lb;
	/** @var Array */
	protected $rowRefs = array(); // list of field/value maps for each row

	protected $dir; // string; path
	protected $trxId; // string; UUID
	protected $phase = self::PHASE_READY; // integer

	// Possible states
	const PHASE_READY      = 1; // not in a transaction
	const PHASE_PROPOSING  = 2; // in a cross-DB transaction, proposing changes
	const PHASE_COMMITTING = 3; // agreement on all changes, ready for COMMITs
	const PHASE_CANCELLING = 4; // disagreement on all changes, ready for ROLLBACKs

	// Array keys of table row references stored in rtj_blob and .tlog files
	const IDX_WIKI      = 0;
	const IDX_TABLE     = 1;
	const IDX_SHARD_COL = 2;
	const IDX_SHARD_VAL = 3;
	const IDX_UID_COL   = 4;
	const IDX_UID_VAL   = 5;
	const IDX_REFS      = 6;

	// Status values for rtj_state
	const LOG_ATTEMPTED = 0;
	const LOG_CONFIRMED = 1;

	const STALE_SEC = 300; // how old a .tlog must be to be "stale"
	const CHECK_SEC = 60; // how long to go before checking for stale .tlog files

	/**
	 * @param $dbStore ExternalRDBStore
	 * @param $cluster string Name of DB cluster
	 */
	public function __construct( ExternalRDBStore $dbStore, $cluster ) {
		if ( !in_array( $cluster, $dbStore->getClusters() ) ) {
			throw new MWException( "Cluster '$cluster' does not belong to this RDB store." );
		}
		$this->dbStore = $dbStore;
		$this->lb      = wfGetLBFactory()->getExternalLB( $cluster );
		// Get a staging dir for saving updates. On failure, we may dump these into the DB.
		$this->dir     = wfTempDir() . '/ext-rdbstore/' . rawurlencode( $dbStore->getName() );
	}

	/**
	 * @return integer ExternalRDBStoreTrxJournal::PHASE_* constant
	 */
	public function getPhase() {
		return $this->phase;
	}

	/**
	 * Function to be called right after transaction BEGIN
	 *
	 * @return void
	 */
	public function onPrePropose() {
		if ( $this->phase !== self::PHASE_READY ) {
			throw new MWException( "Transaction already in progress." );
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
			throw new MWException( "No transaction in progress." );
		}
		if ( count( $this->rowRefs ) ) {
			$this->trxId = wfRandomString( 32 ); // UUID
			// Durably log the affected rows to a file in /tmp space
			$data = serialize( $this->rowRefs );
			wfMkdirParents( $this->dir ); // create dir if needed
			$bytes = file_put_contents( "{$this->dir}/{$this->trxId}.tlog", $data );
			if ( $bytes !== strlen( $data ) ) {
				throw new DBUnexpectedError( "Could not write to '{$this->trxId}.tlog' file." );
			}
			// XXX: where is the fsync()? :D
			$this->rowRefs = array(); // rows now journaled
		}
		$this->phase = self::PHASE_COMMITTING;
	}

	/**
	 * Function to be called right after transaction commit
	 *
	 * @return void
	 */
	public function onPostCommit() {
		if ( $this->phase !== self::PHASE_COMMITTING ) {
			throw new MWException( "Transaction is not ready to commit." );
		}
		// Since this succeeded, we can throw away the log entry
		if ( $this->trxId ) {
			unlink( "{$this->dir}/{$this->trxId}.tlog" );
			$this->trxId = null;
		}
		$this->phase = self::PHASE_READY;
		$this->maybeCollectForDBJournal();
	}

	/**
	 * Function to be called right before transaction rollback
	 *
	 * @return void
	 */
	public function onPreRollback() {
		if ( $this->phase !== self::PHASE_PROPOSING ) {
			throw new MWException( "No transaction in progress." );
		}
		$this->rowRefs = array();
		$this->phase   = self::PHASE_CANCELLING;
	}

	/**
	 * Function to be called right after transaction rollback
	 *
	 * @return void
	 */
	public function onPostRollback() {
		if ( $this->phase !== self::PHASE_CANCELLING ) {
			throw new MWException( "Transaction is not ready to rollback." );
		}
		$this->phase = self::PHASE_READY;
	}

	/**
	 * Note rows as changing in a cross-shard transaction if $key is the cannonical shard key
	 *
	 * @param $tp ExternalRDBStoreTablePartition
	 * @param $rows array DB row objects changed
	 * @return void
	 * @throws DBError
	 */
	public function bufferFromRowList( ExternalRDBStoreTablePartition $tp, array $rows ) {
		if ( $this->phase !== self::PHASE_PROPOSING ) {
			throw new MWException( "Transaction is not in progress." );
		}
		$table = $tp->getVirtualTable();
		$tinfo = $this->getDenormalizedTableInfo( $table );
		if ( is_array( $tinfo ) && $tinfo['canonicalShardKey'] === $tp->getPartitionKey() ) {
			$this->addRowsFromList( $tinfo, $tp, $rows );
		}
	}

	/**
	 * Note rows as changing in a cross-shard transaction if $key is the cannonical shard key
	 *
	 * @param $tp ExternalRDBStoreTablePartition
	 * @param $conds array Query conditions
	 * @return void
	 */
	public function bufferFromRowConds( ExternalRDBStoreTablePartition $tp, array $conds ) {
		if ( $this->phase !== self::PHASE_PROPOSING ) {
			throw new MWException( "Transaction is not in progress." );
		}
		$table = $tp->getVirtualTable();
		$tinfo = $this->getDenormalizedTableInfo( $table );
		if ( is_array( $tinfo ) && $tinfo['canonicalShardKey'] === $tp->getPartitionKey() ) {
			$rows = array();
			$res = $tp->select( DB_MASTER, '*', $conds, __METHOD__, array( 'FOR UPDATE' ) );
			foreach ( $res as $row ) {
				$rows[] = (array)$row;
			}
			$this->addRowsFromList( $tinfo, $tp, $rows );
		}
	}

	/**
	 * @param $tinfo array Table schema information
	 * @param $tp ExternalRDBStoreTablePartition
	 * @param $rows array DB row objects changed
	 * @return void
	 */
	protected function addRowsFromList(
		array $tinfo, ExternalRDBStoreTablePartition $tp, array $rows
	) {
		foreach ( $rows as $row ) {
			// Store a reference to the row on the canonical shard...
			$rowRef = array(
				self::IDX_WIKI      => $ip->getWiki(), // wiki ID
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
	 * Log any potentially failed transactions to the DB failure journal.
	 * This deals with DB errors, crashes, power failures, and other things that
	 * may have interrupted previous transactions on this server.
	 */
	protected function maybeCollectForDBJournal() {
		if ( !file_exists( "{$this->dir}.collect" )
			|| filemtime( "{$this->dir}.collect" ) < ( time() - self::CHECK_SEC )
		) {
			wfDebug( __METHOD__ . ": Checking for tlog files to move to the DB journal.\n" );
			$this->collectForDBJournal();
		} else {
			wfDebug( __METHOD__ . ": Skipping check for tlog files to move to the DB journal.\n" );
		}
	}

	/**
	 * Move the .tlog files lying around in /tmp to the DB failure journal.
	 * The failure journal should be polled, and the rows references fixed.
	 *
	 * @return bool
	 */
	protected function collectForDBJournal() {
		wfProfileIn( __METHOD__ );

		wfMkdirParents( $this->dir ); // create dir if needed
		// Make sure only one process on this server is doing this...
		$lockFileHandle = fopen( "{$this->dir}.collect", 'w' ); // open & touch
		if ( !$lockFileHandle ) {
			wfProfileOut( __METHOD__ );
			return false; // bail out
		} elseif ( !flock( $lockFileHandle, LOCK_EX | LOCK_NB ) ) {
			fclose( $lockFileHandle );
			wfProfileOut( __METHOD__ );
			return false; // someone else probably beat us
		}

		try {
			$time        = time();
			$cutoff      = $time - self::STALE_SEC;
			$rows        = array();
			$filesCopied = array();

			// Get all of the old .tlog files lying around...
			$dirHandle = opendir( $this->dir );
			if ( $dirHandle ) {
				while ( $file = readdir( $dirHandle ) ) {
					if ( FileBackend::extensionFromPath( $file ) !== 'tlog' ) {
						continue; // sanity
					} elseif ( filemtime( "{$this->dir}/{$file}" ) > $cutoff ) {
						continue; // wait; transaction may still be in progress
					}
					$data = file_get_contents( "{$this->dir}/{$file}" );
					if ( $data === false ) {
						continue; // sanity
					}
					$rows[] = array( 'rtj_blob' => $data, 'rtj_time' => $time );
					$filesCopied[] = $file; // delete this afterwards
				}
				closedir( $dirHandle );
			}

			// Copy these into the .tlog blobs into the DB failure journal...
			if ( count( $rows ) ) {
				$trxDB = $this->lb->getConnection( DB_MASTER, array(), 'rdb_metadata' );
				$trxDB->clearFlag( DBO_TRX ); // use auto-commit mode
				if ( $trxDB->trxLevel() ) { // sanity check
					throw new DBUnexpectedError( "rdb_metadata DB connection in a transaction." );
				}
				$trxDB->insert( 'rdb_trx_journal', $rows, __METHOD__ );
			}
			wfDebug( __METHOD__ . " :Moved " . count( $rows ) . " tlog file(s) to DB journal.\n" );

			// Delete all of the copied .tlog files...
			foreach ( $filesCopied as $fileCopied ) {
				unlink( "{$this->dir}/{$fileCopied}" );
			}
		} catch ( Exception $e ) {
			flock( $lockFileHandle, LOCK_UN );
			fclose( $lockFileHandle );
			wfProfileOut( __METHOD__ );
			throw $e;
		}

		// Release locks so other processes can poll these files...
		flock( $lockFileHandle, LOCK_UN );
		fclose( $lockFileHandle );

		wfProfileOut( __METHOD__ );
		return true;
	}

	/**
	 * Run through the failed transaction log and re-sync any duplicate
	 * rows on non-canonical table shards that are found to be out of sync.
	 *
	 * @param $limit integer Maximum number of transactions to correct
	 * @param $daysAgo integer Only scan entries up to this many days back
	 * @return integer Number of transactions corrected
	 */
	public function correctRowSyncAnomalies( $limit = 50, $daysAgo = 30 ) {
		$trxDB = $this->lb->getConnection( DB_MASTER, array(), 'rdb_metadata' );
		if ( $trxDB->trxLevel() ) { // sanity check
			throw new DBUnexpectedError( "rdb_metadata DB connection in a transaction." );
		}
		$count = 0;
		$res = $trxDB->select( 'rdb_trx_journal',
			array( 'rtj_id', 'rtj_blob' ),
			array( 'rtj_state' => self::LOG_ATTEMPTED,
				'rtj_time > ' . $trxDB->addQuotes( time() - 86400*$daysAgo )
			),
			__METHOD__,
			array( 'LIMIT' => $limit, 'ORDER BY' => 'rtj_id ASC' )
		);
		foreach ( $res as $row ) {
			$this->dbStore->begin();
			$this->doCorrectRowSyncAnomalies( unserialize( $row->rtj_blob ) );
			$this->dbStore->commit();
			$trxDB->update( 'rdb_trx_journal',
				array( 'rtj_state' => self::LOG_CONFIRMED ),
				array( 'rtj_id'     => $row->rtj_id ),
				__METHOD__
			);
			$count++;
		}
		return $count;
	}

	/**
	 * Actually re-sync the rows referenced in $rowRefs
	 *
	 * @see ExternalRDBStoreTrxJournal::correctRowSyncAnomalies()
	 *
	 * @param $rowRefs array
	 * @return void
	 */
	protected function doCorrectRowSyncAnomalies( array $rowRefs ) {
		foreach ( $rowRefs as $rowRef ) {
			// Get the canonical partition...
			$ctpartition = $this->dbStore->getPartition(
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
				$dtpartition = $this->dbStore->getPartition(
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
		}
	}

	/**
	 * @param $table string
	 * @return Array|null Returns null if the table is not denormalized
	 */
	protected function getDenormalizedTableInfo( $table ) {
		$tinfo = RDBStoreGroup::singleton()->getDenormalizedTableInfo( $table );
		if ( is_array( $tinfo ) ) { // table is denormalized
			if ( !is_string( $tinfo['rowIdColumn'] ) ) {
				throw new DBUnexpectedError( "Table '$table' has no registered ID column." );
			} elseif ( !is_string( $tinfo['canonicalShardKey'] ) ) {
				throw new DBUnexpectedError( "Table '$table' has no registered shard column." );
			} elseif ( !is_array( $tinfo['secondaryShardKeys'] ) ) {
				throw new DBUnexpectedError( "Table '$table' missing registered shard columns." );
			}
		}
		return $tinfo;
	}
}
