<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\Installer\DatabaseUpdater;
use MediaWiki\Status\Status;

/**
 * Insert the initial updatelog table rows
 *
 * @internal For use by the installer
 */
class InsertUpdateKeysTask extends Task {
	/** @inheritDoc */
	public function getName() {
		return 'updates';
	}

	/** @inheritDoc */
	public function getDependencies() {
		return 'tables';
	}

	public function execute(): Status {
		$updater = DatabaseUpdater::newForDB(
			$this->definitelyGetConnection( ITaskContext::CONN_CREATE_TABLES ) );
		$updater->insertInitialUpdateKeys();
		return Status::newGood();
	}
}
