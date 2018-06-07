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

use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;

/**
 * A foreign repository with an accessible MediaWiki database
 *
 * @ingroup FileRepo
 */
class ForeignDBRepo extends LocalRepo {
	/** @var string */
	protected $dbType;

	/** @var string */
	protected $dbServer;

	/** @var string */
	protected $dbUser;

	/** @var string */
	protected $dbPassword;

	/** @var string */
	protected $dbName;

	/** @var string */
	protected $dbFlags;

	/** @var string */
	protected $tablePrefix;

	/** @var bool */
	protected $hasSharedCache;

	/** @var IDatabase */
	protected $dbConn;

	/** @var callable */
	protected $fileFactory = [ ForeignDBFile::class, 'newFromTitle' ];
	/** @var callable */
	protected $fileFromRowFactory = [ ForeignDBFile::class, 'newFromRow' ];

	/**
	 * @param array|null $info
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
	 * @return IDatabase
	 */
	function getMasterDB() {
		if ( !isset( $this->dbConn ) ) {
			$func = $this->getDBFactory();
			$this->dbConn = $func( DB_MASTER );
		}

		return $this->dbConn;
	}

	/**
	 * @return IDatabase
	 */
	function getReplicaDB() {
		return $this->getMasterDB();
	}

	/**
	 * @return Closure
	 */
	protected function getDBFactory() {
		$type = $this->dbType;
		$params = [
			'host' => $this->dbServer,
			'user' => $this->dbUser,
			'password' => $this->dbPassword,
			'dbname' => $this->dbName,
			'flags' => $this->dbFlags,
			'tablePrefix' => $this->tablePrefix,
			'foreign' => true,
		];

		return function ( $index ) use ( $type, $params ) {
			return Database::factory( $type, $params );
		};
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

			return wfForeignMemcKey( $this->dbName, $this->tablePrefix, ...$args );
		} else {
			return false;
		}
	}

	protected function assertWritableRepo() {
		throw new MWException( static::class . ': write operations are not supported.' );
	}

	/**
	 * Return information about the repository.
	 *
	 * @return array
	 * @since 1.22
	 */
	function getInfo() {
		return FileRepo::getInfo();
	}
}
