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
 * Class representing a single partition of a virtual DB table
 *
 * @ingroup RDBStore
 * @since 1.20
 */
class ExternalRDBStoreTablePartition extends RDBStoreTablePartition {
	/** @var LoadBalancer */
	protected $lb;

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
		$this->vTable   = (string)$table;
		$this->pTable   = "{$table}__{$rdbStore->formatShardIndex( $index )}__{$key}";
		$this->key      = (string)$key;
		$this->value    = (string)$value;
		$this->wiki     = (string)$wiki;
	}

	/**
	 * @see RDBStoreTablePartition
	 * @return DatabaseBase
	 */
	public function getSlaveDB() {
		$conn = $this->lb->getConnection( DB_SLAVE, array(), $this->wiki );
		if ( $this->rdbStore->hasTransaction() ) {
			$conn->setFlag( DBO_TRX ); // wrap queries in one transaction
			$this->rdbStore->registerConnTrxInternal( $conn );
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
			if ( $this->rdbStore->getTrxIdXAInternal() ) { // XA transaction
				$this->rdbStore->connStartXAInternal( $conn );
			} else { // non-XA transaction
				$conn->setFlag( DBO_TRX ); // wrap queries in one transaction
				$this->rdbStore->registerConnTrxInternal( $conn );
			}
		} else {
			$conn->clearFlag( DBO_TRX ); // auto-commit by default
		}
		return $conn;
	}
}
