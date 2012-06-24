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
	/** @var ExternalRDBStoreTrxJournal */
	protected $transactionJournal;

	/** @var Array */
	protected $connsWithTrx = array(); // (server host/ip => DatabaseBase)

	protected $name; // string
	protected $clusterCount; // integer
	protected $clusterPrefix; // string

	const SHARD_COUNT = 256; // integer; consistent partitions per (wiki,table) (power of 2)

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

		$this->transactionJournal = new ExternalRDBStoreTrxJournal( $this );
		$this->transactionJournal->periodicLogCheck(); // fix failed transactions
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
	 * @see RDBStore::isPartitioned()
	 * @return bool
	 */
	public function isPartitioned() {
		return true;
	}

	/**
	 * @see RDBStore::beginOutermost()
	 * @see DatabaseBase::begin()
	 * @return void
	 */
	protected function beginOutermost() {
		// ExternalRDBStoreTablePartition will make sure that DB write queries are
		// wrapped in a transaction, which this class will commit in finishOutermost().
		$this->transactionJournal->onPrePropose(); // remember affected rows
	}

	/**
	 * @see RDBStore::finishOutermost()
	 * @see DatabaseBase::commit()
	 * @return void
	 */
	protected function finishOutermost() {
		$this->transactionJournal->onPreCommit(); // affected rows are changing
		if ( count( $this->connsWithTrx ) > 1 ) { // avoid network partitions on COMMIT
			foreach ( $this->connsWithTrx as $server => $conn ) {
				if ( $conn->trxLevel() ) { // queries made in transaction
					$conn->query( "SELECT 1 /*pre-commit ping*/", __METHOD__ );
				}
			}
		}
		foreach ( $this->connsWithTrx as $server => $conn ) {
			if ( $conn->trxLevel() ) {
				$conn->commit(); // commit open transactions
			}
			unset( $this->connsWithTrx[$server] );
		}
		$this->transactionJournal->onPostCommit(); // affected rows have changed
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
		$index = (int)base_convert( $hash, 16, 10 ) % self::SHARD_COUNT; // [0,1023]
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
	 * Get a transaction journal to use for the life of this object.
	 * Outside callers should generally not need this and should avoid using it.
	 *
	 * @return ExternalRDBStoreTrxJournal
	 */
	public function getTransactionJournal() {
		return $this->transactionJournal;
	}

	/**
	 * Outside callers should generally not need this and should avoid using it
	 *
	 * @return Array List of cluster names for this store.
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
		for ( $index = 0; $index < self::SHARD_COUNT; $index++ ) {
			$map[$this->getClusterForIndex( $index )][] = $index;
		}
		return $map;
	}

	/**
	 * Format a shard number by padding out the digits as needed.
	 * Outside callers should generally not need this and should avoid using it.
	 *
	 * @param $index integer
	 * @return string
	 */
	public function formatShardIndex( $index ) {
		$decimals = strlen( self::SHARD_COUNT - 1 );
		return sprintf( "%0{$decimals}d", $index ); // e.g "033"
	}

	/**
	 * Outside callers should generally not need this and should avoid using it
	 *
	 * @param $index integer Partition index
	 * @return string Name of DB cluster reponsible for this shard index
	 * @throws MWException
	 */
	public function getClusterForIndex( $index ) {
		if ( $index < 0 || $index >= self::SHARD_COUNT ) {
			throw new MWException( "Index $index is not a valid partition index." );
		}
		// This mapping MUST always remain consistent (immutable)!
		return $this->clusterPrefix . ( ( $index % $this->clusterCount ) + 1 );
	}

	/**
	 * Outside callers should generally not need this and should avoid using it
	 *
	 * @param $index integer Partition index
	 * @return LoadBalancer For the cluster the partition index is on
	 */
	public function getClusterLBForIndex( $index ) {
		return wfGetLBFactory()->getExternalLB( $this->getClusterForIndex( $index ) );
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
		for ( $index = 0; $index < self::SHARD_COUNT; $index++ ) {
			$shard = $this->formatShardIndex( $index ); // e.g "0033"
			$pTables[] = "{$table}__{$shard}__{$column}";
		}
		return $pTables;
	}

	/**
	 * This is used by ExternalRDBStoreTablePartition when getting master connections.
	 * Do not call this function from places outside ExternalRDBStoreTablePartition.
	 *
	 * @param $conn DatabaseBase
	 * @return void
	 */
	public function registerConnTrxInternal( DatabaseBase $conn ) {
		$this->connsWithTrx[$conn->getServer()] = $conn;
	}
}
