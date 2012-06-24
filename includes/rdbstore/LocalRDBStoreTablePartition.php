<?php
/**
 * This file deals with local RDBMs stores.
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
 * This is just a regular table on the non-external main DB.
 *
 * @ingroup RDBStore
 * @since 1.20
 */
class LocalRDBStoreTablePartition extends RDBStoreTablePartition {
	/**
	 * @param $rdbStore LocalRDBStore
	 * @param $table string Table name
	 * @param $key string Shard key column name
	 * @param $value Array Shard key column value
	 * @param $wiki string Wiki ID
	 */
	public function __construct( LocalRDBStore $rdbStore, $table, $key, $value, $wiki ) {
		$this->rdbStore = $rdbStore;
		$this->vTable  = (string)$table;
		$this->pTable  = (string)$table;
		$this->key     = (string)$key;
		$this->value   = (string)$value;
		$this->wiki    = (string)$wiki;
	}

	/**
	 * @see RDBStoreTablePartition::getSlaveDB()
	 * @return DatabaseBase
	 */
	public function getSlaveDB() {
		return $this->rdbStore->getSlaveDB();
	}

	/**
	 * @see RDBStoreTablePartition::getMasterDB()
	 * @return DatabaseBase
	 */
	public function getMasterDB() {
		return $this->rdbStore->getMasterDB();
	}
}
