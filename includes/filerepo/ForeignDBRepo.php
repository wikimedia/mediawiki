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

namespace MediaWiki\FileRepo;

use Closure;
use LogicException;
use MediaWiki\FileRepo\File\ForeignDBFile;
use MediaWiki\MediaWikiServices;
use MediaWiki\Storage\BlobStore;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\IDatabase;

/**
 * A foreign repository with an accessible MediaWiki database
 *
 * @ingroup FileRepo
 */
class ForeignDBRepo extends LocalRepo implements IForeignRepoWithDB {
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

	/** @var IDatabase */
	protected $dbConn;

	/** @var callable */
	protected $fileFactory = [ ForeignDBFile::class, 'newFromTitle' ];
	/** @var callable */
	protected $fileFromRowFactory = [ ForeignDBFile::class, 'newFromRow' ];

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
		$this->hasAccessibleSharedCache = $info['hasSharedCache'];

		$dbDomain = new DatabaseDomain( $this->dbName, null, $this->tablePrefix );
		$this->dbDomain = $dbDomain->getId();
	}

	public function getPrimaryDB() {
		if ( !$this->dbConn ) {
			$func = $this->getDBFactory();
			$this->dbConn = $func( DB_PRIMARY );
		}

		return $this->dbConn;
	}

	public function getReplicaDB() {
		return $this->getPrimaryDB();
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

		return static function ( $index ) use ( $type, $params ) {
			$factory = MediaWikiServices::getInstance()->getDatabaseFactory();
			return $factory->create( $type, $params );
		};
	}

	protected function assertWritableRepo(): never {
		throw new LogicException( static::class . ': write operations are not supported.' );
	}

	public function getBlobStore(): ?BlobStore {
		return null;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ForeignDBRepo::class, 'ForeignDBRepo' );
