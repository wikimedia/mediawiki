<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\FileRepo;

use Closure;
use LogicException;
use MediaWiki\FileRepo\File\ForeignDBFile;
use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * A foreign repository with a MediaWiki database accessible via the configured LBFactory.
 *
 * @ingroup FileRepo
 */
class ForeignDBViaLBRepo extends LocalRepo implements IForeignRepoWithDB {
	/** @var array */
	protected $fileFactory = [ ForeignDBFile::class, 'newFromTitle' ];

	/** @var array */
	protected $fileFromRowFactory = [ ForeignDBFile::class, 'newFromRow' ];

	/**
	 * @param array|null $info
	 */
	public function __construct( $info ) {
		parent::__construct( $info );
		'@phan-var array $info';
		$this->dbDomain = $info['wiki'];
		$this->hasAccessibleSharedCache = $info['hasSharedCache'];
	}

	/** @inheritDoc */
	public function getPrimaryDB() {
		return $this->getDbProvider()->getPrimaryDatabase( $this->dbDomain );
	}

	/** @inheritDoc */
	public function getReplicaDB() {
		return $this->getDbProvider()->getReplicaDatabase( $this->dbDomain );
	}

	/**
	 * @return Closure
	 */
	protected function getDBFactory() {
		return function ( $index ) {
			if ( $index == DB_PRIMARY ) {
				return $this->getDbProvider()->getPrimaryDatabase( $this->dbDomain );
			} else {
				return $this->getDbProvider()->getReplicaDatabase( $this->dbDomain );
			}
		};
	}

	protected function getDbProvider(): IConnectionProvider {
		return MediaWikiServices::getInstance()->getConnectionProvider();
	}

	protected function assertWritableRepo(): never {
		throw new LogicException( static::class . ': write operations are not supported.' );
	}

	/** @inheritDoc */
	public function getInfo() {
		return FileRepo::getInfo();
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ForeignDBViaLBRepo::class, 'ForeignDBViaLBRepo' );
