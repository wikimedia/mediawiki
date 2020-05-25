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
use Wikimedia\Rdbms\DatabaseDomain;
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

	/** @var string */
	private $dbDomain;

	/**
	 * @param array|null $info
	 */
	public function __construct( $info ) {
		parent::__construct( $info );
		'@phan-var array $info';
		$this->dbType = $info['dbType'];
		$this->dbServer = $info['dbServer'];
		$this->dbUser = $info['dbUser'];
		$this->dbPassword = $info['dbPassword'];
		$this->dbName = $info['dbName'];
		$this->dbFlags = $info['dbFlags'];
		$this->tablePrefix = $info['tablePrefix'];
		$this->hasSharedCache = $info['hasSharedCache'];

		$dbDomain = new DatabaseDomain( $this->dbName, null, $this->tablePrefix );
		$this->dbDomain = $dbDomain->getId();
	}

	public function getMasterDB() {
		if ( !isset( $this->dbConn ) ) {
			$func = $this->getDBFactory();
			$this->dbConn = $func( DB_MASTER );
		}

		return $this->dbConn;
	}

	public function getReplicaDB() {
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
			'tablePrefix' => $this->tablePrefix
		];

		return function ( $index ) use ( $type, $params ) {
			return Database::factory( $type, $params );
		};
	}

	/**
	 * @return bool
	 */
	private function hasSharedCache() {
		return $this->hasSharedCache;
	}

	public function getSharedCacheKey( ...$args ) {
		if ( $this->hasSharedCache() ) {
			return $this->wanCache->makeGlobalKey( $this->dbDomain, ...$args );
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
	public function getInfo() {
		return FileRepo::getInfo();
	}
}
