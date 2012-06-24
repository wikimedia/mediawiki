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
 * Class representing a single partition of a virtual DB table.
 * If a shard column value is provided, queries are restricted
 * to those that apply to that value; otherwise, queries can be
 * made on the entire table partition.
 *
 * @ingroup RDBStore
 * @since 1.20
 */
abstract class RDBStoreTablePartition {
	/** @var RDBStore */
	protected $rdbStore;

	protected $wiki; // string; wiki ID
	protected $vTable; // string; virtual table name
	protected $pTable; // string; partition table name
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
		return $this->pTable;
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
		return $db->select( $this->pTable, $vars, $conds, $fname, $options );
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
		return $db->selectRow( $this->pTable, $vars, $conds, $fname, $options );
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

		if ( $this->rdbStore->hasTransaction() ) {
			$this->proposedInsert( $this, $rows );
		}

		$dbw = $this->getMasterDB();
		$dbw->insert( $this->pTable, $rows, $fname, $options );
		return $dbw->affectedRows();
	}

	/**
	 * Used by stores with transaction journals
	 *
	 * @param $partition RDBStoreTablePartition
	 * @param $rows array DB row objects changed
	 * @return void
	 */
	protected function proposedInsert( RDBStoreTablePartition $partition, array $rows ) {}

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

		if ( $this->rdbStore->hasTransaction() ) {
			$this->proposedUpdate( $this, $conds );
		}

		$dbw = $this->getMasterDB();
		$dbw->update( $this->pTable, $values, $conds, $fname );
		return $dbw->affectedRows();
	}

	/**
	 * Used by stores with transaction journals
	 *
	 * @param $partition RDBStoreTablePartition
	 * @param $conds array Query conditions
	 * @return void
	 */
	protected function proposedUpdate( RDBStoreTablePartition $partition, array $conds ) {}

	/**
	 * @see DatabaseBase::delete()
	 * @see DatabaseBase::affectedRows()
	 * @param $conds Array
	 * @param $fname String
	 * @return integer Number of affected rows
	 */
	final public function delete( array $conds, $fname ) {
		$this->assertKeyCond( $conds ); // sanity

		if ( $this->rdbStore->hasTransaction() ) {
			$this->proposedDelete( $this, $conds );
		}

		$dbw = $this->getMasterDB();
		$dbw->delete( $this->pTable, $conds, $fname );
		return $dbw->affectedRows();
	}

	/**
	 * Used by stores with transaction journals
	 *
	 * @param $partition RDBStoreTablePartition
	 * @param $conds array Query conditions
	 * @return void
	 */
	protected function proposedDelete( RDBStoreTablePartition $partition, array $conds ) {}

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
		} elseif ( $this->value !== null && strval( $conds[$this->key] ) !== $this->value ) {
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
