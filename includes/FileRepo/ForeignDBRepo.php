<?php
/**
 * A foreign repository with an accessible MediaWiki database.
 *
 * @license GPL-2.0-or-later
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

	/** @inheritDoc */
	public function getPrimaryDB() {
		if ( !$this->dbConn ) {
			$func = $this->getDBFactory();
			$this->dbConn = $func( DB_PRIMARY );
		}

		return $this->dbConn;
	}

	/** @inheritDoc */
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
