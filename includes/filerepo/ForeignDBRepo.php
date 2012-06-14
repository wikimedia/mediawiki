<?php
/**
 * A foreign repository with an accessible MediaWiki database.
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
 * @ingroup FileRepo
 */

/**
 * A foreign repository with an accessible MediaWiki database
 *
 * @ingroup FileRepo
 */
class ForeignDBRepo extends LocalRepo {
	# Settings
	var $dbType, $dbServer, $dbUser, $dbPassword, $dbName, $dbFlags,
		$tablePrefix, $hasSharedCache;

	# Other stuff
	var $dbConn;
	var $fileFactory = array( 'ForeignDBFile', 'newFromTitle' );
	var $fileFromRowFactory = array( 'ForeignDBFile', 'newFromRow' );

	/**
	 * @param $info array|null
	 */
	function __construct( $info ) {
		parent::__construct( $info );
		$this->dbType = $info['dbType'];
		$this->dbServer = $info['dbServer'];
		$this->dbUser = $info['dbUser'];
		$this->dbPassword = $info['dbPassword'];
		$this->dbName = $info['dbName'];
		$this->dbFlags = $info['dbFlags'];
		$this->tablePrefix = $info['tablePrefix'];
		$this->hasSharedCache = $info['hasSharedCache'];
	}

	/**
	 * @return DatabaseBase
	 */
	function getMasterDB() {
		if ( !isset( $this->dbConn ) ) {
			$this->dbConn = DatabaseBase::factory( $this->dbType,
				array(
					'host' => $this->dbServer,
					'user'   => $this->dbUser,
					'password' => $this->dbPassword,
					'dbname' => $this->dbName,
					'flags' => $this->dbFlags,
					'tablePrefix' => $this->tablePrefix
				)
			);
		}
		return $this->dbConn;
	}

	/**
	 * @return DatabaseBase
	 */
	function getSlaveDB() {
		return $this->getMasterDB();
	}

	/**
	 * @return bool
	 */
	function hasSharedCache() {
		return $this->hasSharedCache;
	}

	/**
	 * Get a key on the primary cache for this repository.
	 * Returns false if the repository's cache is not accessible at this site. 
	 * The parameters are the parts of the key, as for wfMemcKey().
	 * @return bool|mixed
	 */
	function getSharedCacheKey( /*...*/ ) {
		if ( $this->hasSharedCache() ) {
			$args = func_get_args();
			array_unshift( $args, $this->dbName, $this->tablePrefix );
			return call_user_func_array( 'wfForeignMemcKey', $args );
		} else {
			return false;
		}
	}

	protected function assertWritableRepo() {
		throw new MWException( get_class( $this ) . ': write operations are not supported.' );
	}
}
