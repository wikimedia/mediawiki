<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\Installer\DatabaseUpdater;
use MediaWiki\Status\Status;

/**
 * Run extension updates in order to install extension tables
 *
 * @internal For use by the installer
 */
class ExtensionTablesTask extends Task {
	/** @inheritDoc */
	public function getName() {
		return 'extension-tables';
	}

	/** @inheritDoc */
	public function getDependencies() {
		return [ 'services', 'HookContainer', 'tables' ];
	}

	public function isSkipped(): bool {
		return !$this->getOption( 'Extensions' );
	}

	public function execute(): Status {
		$status = $this->getConnection( ITaskContext::CONN_CREATE_TABLES );
		if ( !$status->isOK() ) {
			return $status;
		}

		// Now run updates to create tables for old extensions
		$updater = DatabaseUpdater::newForDB(
			$status->getDB(),
			(bool)$this->getOption( 'Shared' )
		);
		$updater->setAutoExtensionHookContainer( $this->getHookContainer() );
		$updater->doUpdates( [ 'extensions' ] );

		return $status;
	}
}
