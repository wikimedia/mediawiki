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

	public function getDependencies() {
		return [ 'tables' ];
	}

	public function getProvidedNames() {
		return 'services';
	}

	public function execute(): Status {
		MediaWikiServices::resetGlobalInstance( null, 'reload' );
		$this->getContext()->provide( 'services', MediaWikiServices::getInstance() );
		return Status::newGood();
	}
}
