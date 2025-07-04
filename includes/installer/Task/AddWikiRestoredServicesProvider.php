<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\MediaWikiServices;
use MediaWiki\Status\Status;

/**
 * Scheduled provider for restored services to be used in maintenance/installPreConfigured.php.
 *
 * It's simpler than RestoredServicesProvider because it just needs to reverse
 * disableStorage(). It needs to use the original LBFactory, not a single
 * injected connection, so that the configured external clusters are accessible.
 *
 * @internal
 */
class AddWikiRestoredServicesProvider extends Task {
	public function getName(): string {
		return 'restore-services';
	}

	/** @inheritDoc */
	public function getDependencies() {
		return [ 'tables' ];
	}

	/** @inheritDoc */
	public function getProvidedNames() {
		return 'services';
	}

	public function execute(): Status {
		MediaWikiServices::resetGlobalInstance( null, 'reload' );
		$services = MediaWikiServices::getInstance();
		$this->getContext()->provide( 'services', $services );

		// Wait for replication, so that the new database will exist in replica servers
		$connProvider = $services->getConnectionProvider();
		$ticket = $connProvider->getEmptyTransactionTicket( __METHOD__ );
		$connProvider->commitAndWaitForReplication( __METHOD__, $ticket );

		return Status::newGood();
	}
}
