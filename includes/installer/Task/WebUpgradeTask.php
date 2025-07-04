<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\Installer\DatabaseUpdater;
use MediaWiki\Status\Status;

/**
 * TODO: remove this and run web upgrade with normal startup (T386661)
 */
class WebUpgradeTask extends Task {

	/** @inheritDoc */
	public function getName() {
		return 'upgrade';
	}

	/** @inheritDoc */
	public function getDependencies() {
		return [ 'HookContainer' ];
	}

	public function execute(): Status {
		$status = $this->getConnection( ITaskContext::CONN_CREATE_TABLES );
		if ( !$status->isOK() ) {
			return $status;
		}

		$updater = DatabaseUpdater::newForDB(
			$status->getDB(),
			(bool)$this->getOption( 'Shared' )
		);
		$updater->setAutoExtensionHookContainer( $this->getHookContainer() );
		$updater->doUpdates();
		$updater->purgeCache();
		return $status;
	}
}
