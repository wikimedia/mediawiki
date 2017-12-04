<?php
/**
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
 * @ingroup Database
 */

namespace Wikimedia\Rdbms;

/**
 * Database connection manager.
 *
 * This manages access to master and replica databases. It also manages state that indicates whether
 * the replica databases are possibly outdated after a write operation, and thus the master database
 * should be used for subsequent read operations.
 *
 * @note: Services that access overlapping sets of database tables, or interact with logically
 * related sets of data in the database, should share a SessionConsistentConnectionManager.
 * Services accessing unrelated sets of information may prefer to not share a
 * SessionConsistentConnectionManager, so they can still perform read operations against replica
 * databases after a (unrelated, per the assumption) write operation to the master database.
 * Generally, sharing a SessionConsistentConnectionManager improves consistency (by avoiding race
 * conditions due to replication lag), but can reduce performance (by directing more read
 * operations to the master database server).
 *
 * @since 1.29
 *
 * @author Daniel Kinzler
 * @author Addshore
 */
class SessionConsistentConnectionManager extends ConnectionManager {

	/**
	 * @var bool
	 */
	private $forceWriteConnection = false;

	/**
	 * Forces all future calls to getReadConnection() to return a write connection.
	 * Use this before performing read operations that are critical for a future update.
	 *
	 * @since 1.29
	 */
	public function prepareForUpdates() {
		$this->forceWriteConnection = true;
	}

	/**
	 * @since 1.29
	 *
	 * @param string[]|null $groups
	 *
	 * @return Database
	 */
	public function getReadConnection( array $groups = null ) {
		if ( $this->forceWriteConnection ) {
			return parent::getWriteConnection();
		}

		return parent::getReadConnection( $groups );
	}

	/**
	 * @since 1.29
	 *
	 * @return Database
	 */
	public function getWriteConnection() {
		$this->prepareForUpdates();
		return parent::getWriteConnection();
	}

	/**
	 * @since 1.29
	 *
	 * @param string[]|null $groups
	 *
	 * @return DBConnRef
	 */
	public function getReadConnectionRef( array $groups = null ) {
		if ( $this->forceWriteConnection ) {
			return parent::getWriteConnectionRef();
		}

		return parent::getReadConnectionRef( $groups );
	}

	/**
	 * @since 1.29
	 *
	 * @return DBConnRef
	 */
	public function getWriteConnectionRef() {
		$this->prepareForUpdates();
		return parent::getWriteConnectionRef();
	}

}
