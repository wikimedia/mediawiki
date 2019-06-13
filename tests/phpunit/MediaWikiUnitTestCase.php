<?php

use MediaWiki\MediaWikiServices;
use PHPUnit\Framework\TestCase;

abstract class MediaWikiUnitTestCase extends TestCase {
	use MediaWikiCoversValidator;
	use PHPUnit4And6Compat;

	/** @var MediaWikiServices $mwServicesBackup */
	private $mwServicesBackup;

	/**
	 * Replace global MediaWiki service locator with a clone that has the given overrides applied
	 * @param callable[] $overrides map of service names to instantiators
	 * @throws MWException
	 */
	protected function overrideMwServices( array $overrides ) {
		$services = clone MediaWikiServices::getInstance();

		foreach ( $overrides as $serviceName => $factory ) {
			$services->disableService( $serviceName );
			$services->redefineService( $serviceName, $factory );
		}

		$this->mwServicesBackup = MediaWikiServices::forceGlobalInstance( $services );
	}

	protected function tearDown() {
		parent::tearDown();

		if ( $this->mwServicesBackup ) {
			MediaWikiServices::forceGlobalInstance( $this->mwServicesBackup );
		}
	}
}
