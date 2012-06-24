<?php
/**
 * @defgroup RDBStore RDBStore
 *
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
 * Class representing a relational DB storage system.
 * Callers access tables as if they were horizontally partitioned,
 * which may actually be the case for the external RDB stores.
 *
 * Partitioning is based on a single non-NULL table column.
 * Only the column value will be used to determine partitions, not the column name.
 *
 * Note that cross-DB transactions are not totally atomic. When COMMITs are issued,
 * It's possible for some DB COMMITs to fail and not others, though since they are done
 * in rapid sequence this scenario is very unlikely. If this is problematic, one can:
 *   - a) Use MVCC patterns when possible. For example, if a local DB row needs to be created,
 *        referencing several RDB store blobs, one can commit all the blobs first, using
 *        UUIDs as foreign keys, updating the local DB only if all the blobs were committed.
 *        If the row and blobs need to be updated, new blobs with different UUIDs can be used.
 *        Failures just leave unreferenced garbage in the worst case scenario.
 *   - b) Partition tables such that related rows of different tables map to the same DB.
 *        This is useful if, for example, a table is partitioned on page ID but a table that
 *        stores aggregate data for by page ID is needed. The later can simply be partitioned
 *        by page ID, ensuring that rows for either table for any page X will be on the same DB.
 *
 * Note that cross-DB queries are not supported in this class. Tables should be sharded such
 * that all queries only need to affect a single shard. If this is not possible, one can:
 *   - a) Have a canonical and a duplicate version of a table, each sharded in different ways.
 *        For example, the former being sharded on page ID and the later on user ID.
 *        Read queries for page X or user Y could thus be routed to a single shard.
 *        A drawback is that this essentially doubles the physical size of the data set.
 *   - b) Have a canonical and an index version of a table, each sharded in different ways.
 *        For example, the former being sharded on page ID and the later on user ID. The index
 *        table duplicates the main table, except only having columns used in WHERE clauses.
 *        Read queries for page X would hit a main table shard, while read queries for user Y
 *        would hit an index shard and then fetch the full rows from the main table shards.
 *        This takes up less space than (a) and can reduce the chance for cross-shard updates.
 *        A drawback is that user=Y queries require O(N) queries for result sets of size N.
 *        This should only be done if N is very small or if it is heavily cached (memcached).
 *   - c) Have a duplicate time-based rolling table stored in the local DB, if possible.
 *        For example, if a thread table is sharded on thread ID, one could also store the last
 *        few months of rows in a local DB table. Since the table is not sharded, anything that
 *        is properly indexed could be queried and JOINs could be done with local tables.
 *        Data inconsistencies are self-correcting as all rows are eventually rotated out.
 * Since cross-DB transactions are not totally atomic, these options have the potential for data
 * anamolies. Reads used for writes should always be done on the canonical table shards.
 * If anamolies are not tolerable or there are too many distinct access patterns to denormalize,
 * then it should be placed on its own cluster and should not be sharded (nor use RDB store).
 *
 * When callers use begin() and finish(), the data might not be committed until later if another
 * caller already called begin() and has yet to call finish(). Output to the user can be buffered
 * in the normal means (e.g. OutputPage) in a way that blindly assumes it has been committed. If
 * the commit fails, a DBError exception will be thrown and the buffered output will be tossed.
 * Things like emails or job insertion, which need to happen only after successful commit, can be
 * made to happen in a callback function. This callback can be passed to finish(), and will only
 * triggered after successful commit.
 *
 * Example usage from an extension:
 * @code
 *		$rdbs = RDBStoreGroup::singleton()->getForTable( 'ext-myextension' );
 *		$tp = $rdbs->getPartition( 'comments', 'thread', 501 );
 *		$res = $tp->select( DB_SLAVE, '*', array( 'thread' => 501, 'comment' => 11 ), __METHOD__ );
 * @endcode
 *
 * @code
 *		$rdbs = RDBStoreGroup::singleton()->getForTable( 'ext-myextension' );
 *		$rdbs->begin();
 *		$rdbs->getPartition( 'user_info', 'user', $row['user'] )->insert( $row, __METHOD__ );
 *		$rdbs->finish();
 * @endcode
 *
 * @code
 *		$rdbs = RDBStoreGroup::singleton()->getForTableGroup( 'ext-myextension' );
 *		$rdbs->begin();
 *		// This table is sharded by user and and duplicated to shard on event
 *		$places = $rdbs->columnValueMap( $row, array( 'user', 'event' ) );
 *		// Insert the subscription row into canonical and duplicate shards...
 *		foreach ( $rdbs->getEachPartition( 'subscriptions', $places ) as $tp ) {
 *			$tp->insert( $row, __METHOD__ );
 *		}
 *		$rdbs->finish();
 * @endcode
 *
 * @code
 *		$callbackCommit = function() {
 *			MyExtension::enqueueNotifyJobs( $row );
 *		};
 *		$rdbs = RDBStoreGroup::singleton()->getForTableGroup( 'ext-myextension' );
 *		$rdbs->begin();
 *		// Insert the comment row into the canonical shard...
 *		$rdbs->getPartition( 'comments', 'thread', $row['thread'] )->insert( $row, __METHOD__ );
 *		// Insert a row that references the comment row into the user index shard...
 *		$irow = $rdbs->columnValueMap( $row, array( 'user', 'thread', 'comment' ) );
 *		$rdbs->getPartition( 'comments', 'user', $row['user'] )->insert( $irow, __METHOD__ );
 *		// Update the stats for the thread...
 *		$tp = $rdbs->getPartition( 'thread_stats', 'thread', $row['thread'] );
 *		$tp->update( 'thread_stats', array(...), array( 'thread' => $row['thread'] ), __METHOD__ );
 *		$rdbs->finish( $callbackCommit );
 * @endcode
 *
 * @ingroup RDBStore
 * @since 1.20
 */
abstract class RDBStore {
	protected $trxDepth = 0; // integer
	protected $pcCallbacks = array(); // list of post-commit callbacks

	/**
	 * @param $options array
	 */
	public function __construct( array $options ) {}

	/**
	 * @return string Name of this store
	 */
	abstract public function getName();

	/**
	 * @return bool Whether this store is the primary DB of a wiki
	 */
	abstract public function isInternal();

	/**
	 * @return bool Tables are actually partitioned in the store
	 */
	abstract public function isPartitioned();

	/**
	 * Increment the transaction counter and begin a transaction if the counter was zero
	 *
	 * @return bool Success
	 */
	final public function begin() {
		// Possibly BEGIN for the outermost transaction
		$ok = ( $this->trxDepth == 0 ) ? $this->beginOutermost() : true;
		if ( $ok ) {
			++$this->trxDepth; // increment the transaction counter in any case
			wfDebug( __METHOD__ . ": transaction nesting level now {$this->trxDepth}.\n" );
		}
		return $ok;
	}

	/**
	 * @see RDBStore::begin()
	 * @return bool Success
	 */
	abstract protected function beginOutermost();

	/**
	 * Decrement the transaction counter and commit the transaction if the counter is zero.
	 *
	 * Callers can register post-commit callback functions (including closures) here.
	 * When the transaction is committed, any post-commit callback functions are called.
	 *
	 * @param $callback Callback Optional function to call after database COMMIT
	 * @return bool Success
	 */
	final public function finish( $callback = null ) {
		if ( $this->trxDepth <= 0 ) {
			throw new DBUnexpectedError( "Detected unmatched finish() call." );
		}
		// Register any post-commit callback given by the caller
		if ( $callback !== null && is_callable( $callback ) ) {
			$this->pcCallbacks[] = $callback;
		}
		// Possibly COMMIT for the outermost transaction
		$ok = ( $this->trxDepth == 1 ) ? $this->finishOutermost() : true;
		if ( $ok ) {
			--$this->trxDepth; // decrement the transaction counter in any case
			wfDebug( __METHOD__ . ": transaction nesting level now {$this->trxDepth}.\n" );
			// Once the transaction level is 0, anything must have been committed.
			// Trigger any post-commit callbacks (which assume the data is committed).
			if ( $this->trxDepth <= 0 ) {
				$e = null;
				foreach ( $this->pcCallbacks as $callback ) {
					try {
						call_user_func( $callback );
					} catch ( Exception $e ) {}
				}
				$this->pcCallbacks = array(); // done
				if ( $e instanceof Exception ) {
					throw $e; // throw back the last exception
				}
			}
		}
		return $ok;
	}

	/**
	 * @see RDBStore::finish()
	 * @return bool Success
	 */
	abstract protected function finishOutermost();

	/**
	 * Check if the store is currently in a DB transaction.
	 * Outside callers should generally not need this and should avoid using it.
	 *
	 * @return bool
	 */
	final public function hasTransaction() {
		return ( $this->trxDepth > 0 );
	}

	/**
	 * Get an object representing a shard of a virtual DB table.
	 * If this store is not partitioned, this returns an object for the entire table.
	 *
	 * Each table is canonically sharded on one column key, and may possibly be
	 * denormalized and sharded on additional column keys (e.g. thread ID, user ID).
	 *
	 * @param $table string Virtual table name
	 * @param $column string Shard key column name
	 * @param $value string Shard key column value
	 * @param $wiki string Wiki ID; defaults to the current wiki
	 * @return RDBStoreTablePartition
	 * @throws DBUnexpectedError
	 */
	final public function getPartition( $table, $column, $value, $wiki = false ) {
		$wiki = ( $wiki === false ) ? wfWikiID() : $wiki;
		if ( !isset( $column ) || !isset( $value ) ) {
			throw new DBUnexpectedError( "Missing table shard column or value." );
		}
		return $this->doGetPartition( $table, $column, $value, $wiki );
	}

	/**
	 * @see RDBStore::getPartition()
	 * @return RDBStoreTablePartition
	 */
	abstract protected function doGetPartition( $table, $column, $value, $wiki );

	/**
	 * Get a list of objects representing all shards of a virtual DB table.
	 * If this store is not partitioned, the list has one object for the entire table.
	 *
	 * Each table is canonically sharded on one column key, and may possibly be
	 * denormalized and sharded on additional column keys (e.g. thread ID, user ID).
	 *
	 * @param $table string Virtual table name
	 * @param $column string Shard key column name
	 * @param $wiki string Wiki ID; defaults to the current wiki
	 * @return Array List of RDBStoreTablePartition objects
	 * @throws DBUnexpectedError
	 */
	final public function getAllPartitions( $table, $column, $wiki = false ) {
		$wiki = ( $wiki === false ) ? wfWikiID() : $wiki;
		if ( !isset( $column ) ) {
			throw new DBUnexpectedError( "Missing table shard column." );
		}
		return $this->doGetAllPartitions( $table, $column, $wiki );
	}

	/**
	 * @see RDBStore::getAllPartitions()
	 * @return Array List of RDBStoreTablePartition objects
	 */
	abstract protected function doGetAllPartitions( $table, $column, $wiki );

	/**
	 * Get the column to value map for a row for a given list of columns.
	 * All these columns must be present in the row or an error will be thrown.
	 *
	 * @param $row Row|array
	 * @param $columns array
	 * @return Array
	 * @throws DBUnexpectedError
	 */
	final public function columnValueMap( $row, array $columns ) {
		$map  = array_intersect_key( (array)$row, $columns );
		$diff = array_diff( $columns, array_keys( $map ) );
		if ( count( $diff ) ) {
			throw new DBUnexpectedError( "Row is missing column(s) " . implode( ', ', $diff ) );
		}
		return $map;
	}

	/**
	 * Get a list of objects representing shards of a virtual DB table.
	 * A shard is returned for each (shard column, column value) tuple given if the
	 * store is partitioned, and for the first tuple if the store is not partitioned.
	 *
	 * This is for tables that are canonically sharded on one column key, and also
	 * denormalized and sharded on additional column keys (e.g. thread ID, user ID).
	 *
	 * When writing to multiple shards, callers should always do so in a certain order.
	 * For example, if a table is sharded on thread ID and denormalized to shard on user ID,
	 * updates should first write to the thread shard and then to the user shard. This reduces
	 * the chance for deadlocks arising from transaction locks on rows. If P1 locks rows on
	 * thread partition A, and blocks on P2 for rows of user partition B, it can't be the case
	 * that P2 is also blocked on P1 for rows of thread partition A (due to the write order).
	 *
	 * @param $table string Virtual table name
	 * @param $map array Map of shard column names to values from RDBStore::columnValueMap()
	 * @param $wiki string Wiki ID; defaults to the current wiki
	 * @return Array Map of column names to RDBStoreTablePartition objects (same order as $map)
	 * @throws DBUnexpectedError
	 */
	final public function getEachPartition( $table, array $map, $wiki = false ) {
		$wiki = ( $wiki === false ) ? wfWikiID() : $wiki;
		$partitions = array();
		foreach ( $map as $column => $value ) {
			if ( !is_string( $column ) ) {
				throw new DBUnexpectedError( "Invalid column name." );
			} elseif ( !is_scalar( $value ) ) {
				throw new DBUnexpectedError( "Invalid value for column '$column'." );
			}
			$partitions[$column] = $this->getPartition( $table, $column, $value, $wiki );
			if ( !$this->isPartitioned() ) {
				break; // the data is all one one table
			}
		}
		return $partitions;
	}
}

/**
 * Class representing a single partition of a virtual DB table.
 * If a shard column value is provided, queries are restricted
 * to those that apply to that value; otherwise, queries can be
 * made on the entire table partition.
 *
 * @ingroup RDBStore
 * @since 1.20
 */
abstract class RDBStoreTablePartition {
	protected $wiki; // string; wiki ID
	protected $vTable; // string; virtual table name
	protected $sTable; // string; partition table name
	protected $key; // string; column name
	protected $value; // string; column value

	/**
	 * @return string Wiki ID
	 */
	final public function getWiki() {
		return $this->wiki;
	}

	/**
	 * @return string Table name (e.g. "flaggedtemplates")
	 */
	final public function getVirtualTable() {
		return $this->vTable;
	}

	/**
	 * @return string Table name (e.g. "flaggedtemplates__030__ft_rev_id")
	 */
	final public function getPartitionTable() {
		return $this->sTable;
	}

	/**
	 * @return string Name of the column used to shard on (e.g. "ft_rev_id")
	 */
	final public function getPartitionKey() {
		return $this->key;
	}

	/**
	 * @return string|null Value of the shard column or NULL
	 */
	final public function getPartitionKeyValue() {
		return $this->value;
	}

	/**
	 * Use this function with usort() to order a list of partitions.
	 * This should be done for access patterns where several shards with the same shard
	 * column need to be written to. Doing writes in order reduces the possibility of deadlocks.
	 *
	 * @param RDBStoreTablePartition $a
	 * @param RDBStoreTablePartition $b
	 * @return integer (negative if a < b, 0 if a = b, positive if a > b)
	 */
	final public function compare( RDBStoreTablePartition $a, RDBStoreTablePartition $b ) {
		return strcmp( $a->getPartitionTable(), $b->getPartitionTable() );
	}

	/**
	 * Similiar to DatabaseBase::select() except the first argument is DB_SLAVE/DB_MASTER
	 *
	 * @see DatabaseBase::select()
	 * @param $index integer
	 * @param $vars Array|string
	 * @param $conds Array
	 * @param $fname string
	 * @param $options Array
	 * @return ResultWrapper
	 */
	final public function select( $index, $vars, array $conds, $fname, $options = array() ) {
		$this->assertKeyCond( $conds ); // sanity
		if ( $index == DB_SLAVE ) {
			$db = $this->getSlaveDB();
		} elseif ( $index == DB_MASTER ) {
			$db = $this->getMasterDB();
		} else {
			throw new DBUnexpectedError( "First argument must be DB_SLAVE or DB_MASTER." );
		}
		return $db->select( $this->sTable, $vars, $conds, $fname, $options );
	}

	/**
	 * Similiar to DatabaseBase::selectRow() except the first argument is DB_SLAVE/DB_MASTER
	 *
	 * @see DatabaseBase::selectRow()
	 * @param $index integer
	 * @param $vars Array|string
	 * @param $conds Array
	 * @param $fname string
	 * @param $options Array
	 * @return ResultWrapper
	 */
	final public function selectRow( $index, $vars, array $conds, $fname, $options = array() ) {
		$this->assertKeyCond( $conds ); // sanity
		if ( $index == DB_SLAVE ) {
			$db = $this->getSlaveDB();
		} elseif ( $index == DB_MASTER ) {
			$db = $this->getMasterDB();
		} else {
			throw new DBUnexpectedError( "First argument must be DB_SLAVE or DB_MASTER." );
		}
		return $db->selectRow( $this->sTable, $vars, $conds, $fname, $options );
	}

	/**
	 * @see DatabaseBase::insert()
	 * @see DatabaseBase::affectedRows()
	 * @param $rows Array
	 * @param $fname String
	 * @param $options Array
	 * @return integer Number of affected rows
	 */
	final public function insert( array $rows, $fname, $options = array() ) {
		$rows = ( isset( $rows[0] ) && is_array( $rows[0] ) ) ? $rows : array( $rows );
		array_map( array( $this, 'assertKeyCond' ), $rows ); // sanity

		$this->logInsert( $this, $rows );

		$dbw = $this->getMasterDB();
		$dbw->insert( $this->sTable, $rows, $fname, $options );
		return $dbw->affectedRows();
	}

	/**
	 * Used by stores with transaction journals
	 *
	 * @param $partition RDBStoreTablePartition
	 * @param $rows array DB row objects changed
	 * @return void
	 */
	protected function logInsert( RDBStoreTablePartition $partition, array $rows ) {}

	/**
	 * @see DatabaseBase::update()
	 * @see DatabaseBase::affectedRows()
	 * @param $values Array
	 * @param $conds Array
	 * @param $fname String
	 * @return integer Number of affected rows
	 */
	final public function update( $values, array $conds, $fname ) {
		$this->assertKeyCond( $conds ); // sanity
		$this->assertKeyNotSet( $values ); // sanity

		$this->logUpdate( $this, $conds );

		$dbw = $this->getMasterDB();
		$dbw->update( $this->sTable, $values, $conds, $fname );
		return $dbw->affectedRows();
	}

	/**
	 * Used by stores with transaction journals
	 *
	 * @param $partition RDBStoreTablePartition
	 * @param $conds array Query conditions
	 * @return void
	 */
	protected function logUpdate( RDBStoreTablePartition $partition, array $conds ) {}

	/**
	 * @see DatabaseBase::delete()
	 * @see DatabaseBase::affectedRows()
	 * @param $conds Array
	 * @param $fname String
	 * @return integer Number of affected rows
	 */
	final public function delete( array $conds, $fname ) {
		$this->assertKeyCond( $conds ); // sanity

		$this->logDelete( $this, $conds );

		$dbw = $this->getMasterDB();
		$dbw->delete( $this->sTable, $conds, $fname );
		return $dbw->affectedRows();
	}

	/**
	 * Used by stores with transaction journals
	 *
	 * @param $partition RDBStoreTablePartition
	 * @param $conds array Query conditions
	 * @return void
	 */
	protected function logDelete( RDBStoreTablePartition $partition, array $conds ) {}

	/**
	 * Get a direct slave DB connection.
	 * Queries should always be done use the provided wrappers.
	 * This can be used to call functions like DatabaseBase::timestamp().
	 *
	 * @return DatabaseBase
	 */
	abstract public function getSlaveDB();

	/**
	 * Get a direct master DB connection.
	 * Queries should always be done use the provided wrappers.
	 * This can be used to call functions like DatabaseBase::timestamp().
	 *
	 * @return DatabaseBase
	 */
	abstract public function getMasterDB();

	/**
	 * Do a (partition key => value) assertion on a WHERE or insertion row array.
	 * This sanity checks that the column actually exists and protects against callers
	 * forgetting to add the condition or saving rows to the wrong table shard.
	 *
	 * @param $conds array
	 */
	final protected function assertKeyCond( array $conds ) {
		if ( !isset( $conds[$this->key] ) ) {
			throw new DBUnexpectedError( "Shard column '{$this->key}' value not provided." );
		} elseif ( $this->value !== null && $conds[$this->key] !== $this->value ) {
			throw new DBUnexpectedError( "Shard column '{$this->key}' value is mismatched." );
		}
	}

	/**
	 * Do a (partition key => value) assertion on a SET clause for an UPDATE statement.
	 * This sanity checks that the shard column value is not getting changed, which would
	 * make the row inaccessible since it would probably then be placed on the wrong shard.
	 *
	 * @param $set array
	 */
	final protected function assertKeyNotSet( array $set ) {
		if ( isset( $set[$this->key] ) ) {
			throw new DBUnexpectedError( "Shard column '{$this->key}' given in SET clause." );
		}
	}
}
