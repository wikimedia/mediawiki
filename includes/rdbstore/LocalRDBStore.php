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
 * Class representing a simple, non-external, DB storage system.
 * Tables are not sharded, and only the wiki cluster is used.
 *
 * @ingroup RDBStore
 * @since 1.20
 */
class LocalRDBStore extends RDBStore {
	/** @var LoadBalancer */
	protected $lb;

	protected $wiki; // string

	/**
	 * @param $options array
	 */
	public function __construct( array $options ) {
		$this->wiki = ( $options['wiki'] === false ) ? wfWikiID() : $options['wiki'];
		// Use a separate connection so this handles transactions like external RDB stores
		$this->lb   = wfGetLBFactory()->newMainLB( $this->wiki );
	}

	/**
	 * @see RDBStore::getName()
	 * @return string
	 */
	public function getName() {
		return "local-{$this->wiki}";
	}

	/**
	 * @see RDBStore::isInternal()
	 * @return bool
	 */
	public function isInternal() {
		return true;
	}

	/**
	 * @see RDBStore::isPartitioned()
	 * @return bool
	 */
	public function isPartitioned() {
		return false;
	}

	/**
	 * @see RDBStore::beginOutermost()
	 * @see DatabaseBase::begin()
	 * @return bool
	 */
	protected function beginOutermost() {
		$this->getMasterDB()->begin();
		return true; // use main transaction
	}

	/**
	 * @see RDBStore::finishOutermost()
	 * @see DatabaseBase::commit()
	 * @return bool
	 */
	protected function finishOutermost() {
		$this->getMasterDB()->commit();
		return true; // use main transaction
	}

	/**
	 * @see RDBStore::doGetPartition()
	 * @return LocalRDBStoreTablePartition
	 */
	protected function doGetPartition( $table, $column, $value, $wiki ) {
		if ( $wiki !== $this->wiki ) {
			throw new DBUnexpectedError( "Wiki ID '$wiki' does not match '{$this->wiki}'." );
		}
		return new LocalRDBStoreTablePartition( $this, $table, $column, $value, $wiki );
	}

	/**
	 * @see RDBStore::doGetAllPartitions()
	 * @return Array List of LocalRDBStoreTablePartition objects
	 */
	protected function doGetAllPartitions( $table, $column, $wiki ) {
		if ( $wiki !== $this->wiki ) {
			throw new DBUnexpectedError( "Wiki ID '$wiki' does not match '{$this->wiki}'." );
		}
		return array( new LocalRDBStoreTablePartition( $this, $table, $column, null, $wiki ) );
	}

	/**
	 * Outside callers should generally not need this and should avoid using it
	 *
	 * @return DatabaseBase
	 */
	public function getSlaveDB() {
		return $this->lb->getConnection( DB_SLAVE, array(), $this->wiki );
	}

	/**
	 * Outside callers should generally not need this and should avoid using it
	 *
	 * @return DatabaseBase
	 */
	public function getMasterDB() {
		return $this->lb->getConnection( DB_MASTER, array(), $this->wiki );
	}
}

/**
 * Class representing a single partition of a virtual DB table.
 * This is just a regular table on the non-external main DB.
 *
 * @ingroup RDBStore
 * @since 1.20
 */
class LocalRDBStoreTablePartition extends RDBStoreTablePartition {
	/**
	 * @param $dbStore LocalRDBStore
	 * @param $table string Table name
	 * @param $key string Shard key column name
	 * @param $value Array Shard key column value
	 * @param $wiki string Wiki ID
	 */
	public function __construct( LocalRDBStore $dbStore, $table, $key, $value, $wiki ) {
		$this->dbStore = $dbStore;
		$this->vTable  = $table;
		$this->sTable  = $table;
		$this->key     = $key;
		$this->value   = $value;
		$this->wiki    = $wiki;
	}

	/**
	 * @see RDBStoreTablePartition::getSlaveDB()
	 * @return DatabaseBase
	 */
	public function getSlaveDB() {
		return $this->dbStore->getSlaveDB();
	}

	/**
	 * @see RDBStoreTablePartition::getMasterDB()
	 * @return DatabaseBase
	 */
	public function getMasterDB() {
		return $this->dbStore->getMasterDB();
	}
}
