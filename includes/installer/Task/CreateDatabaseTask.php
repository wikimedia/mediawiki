<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\MainConfigNames;
use MediaWiki\Status\Status;

/**
 * Create the MySQL/MariaDB or PostgreSQL database
 *
 * @internal For use by the installer
 */
class CreateDatabaseTask extends Task {
	/** @inheritDoc */
	public function getName() {
		return 'database';
	}

	/** @inheritDoc */
	public function getAliases() {
		return 'schema';
	}

	public function execute(): Status {
		$creator = $this->getDatabaseCreator();
		$dbName = $this->getConfigVar( MainConfigNames::DBname );
		if ( !$creator->existsLocally( $dbName ) ) {
			return $creator->createLocally( $dbName );
		}
		return Status::newGood();
	}
}
