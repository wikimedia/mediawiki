<?php

namespace MediaWiki\Installer;

use MediaWiki\Installer\Task\ITaskContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Status\Status;
use Wikimedia\Rdbms\DatabaseSqlite;
use Wikimedia\Rdbms\ILoadBalancer;

class SqliteDatabaseCreator extends DatabaseCreator {
	/** @var string */
	private $dataDir;

	protected function __construct( ITaskContext $context ) {
		parent::__construct( $context );
		$this->dataDir = $context->getConfigVar( MainConfigNames::SQLiteDataDir );
	}

	/** @inheritDoc */
	public function existsLocally( $database ) {
		$file = DatabaseSqlite::generateFileName( $this->dataDir, $database );
		return file_exists( $file );
	}

	/** @inheritDoc */
	public function existsInLoadBalancer( ILoadBalancer $loadBalancer, $database ) {
		return $this->existsLocally( $database );
	}

	/** @inheritDoc */
	public function createLocally( $database ): Status {
		return $this->makeStubDBFile( $database );
	}

	/** @inheritDoc */
	public function createInLoadBalancer( ILoadBalancer $loadBalancer, $database ): Status {
		return $this->createLocally( $database );
	}

	private function makeStubDBFile( string $db ): Status {
		$file = DatabaseSqlite::generateFileName( $this->dataDir, $db );

		if ( file_exists( $file ) ) {
			if ( !is_writable( $file ) ) {
				return Status::newFatal( 'config-sqlite-readonly', $file );
			}
			return Status::newGood();
		}

		$oldMask = umask( 0177 );
		if ( file_put_contents( $file, '' ) === false ) {
			umask( $oldMask );
			return Status::newFatal( 'config-sqlite-cant-create-db', $file );
		}
		umask( $oldMask );

		return Status::newGood();
	}

}
