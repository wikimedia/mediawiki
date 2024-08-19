<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\MainConfigNames;
use UpdateMediaWiki;

// UpdateMediaWiki is not autoloaded, and therefore we need to load the file here. Using require_once should
// be enough to achieve this.
require_once MW_INSTALL_PATH . '/maintenance/update.php';

/**
 * @covers \UpdateMediaWiki
 * @group Database
 * @author Dreamy Jazz
 */
class UpdateMediaWikiTest extends MaintenanceBaseTestCase {
	public function getMaintenanceClass() {
		return UpdateMediaWiki::class;
	}

	public function testExecuteWhenAllowSchemaUpdatesSetToFalse() {
		$this->overrideConfigValue( MainConfigNames::AllowSchemaUpdates, false );
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Do not run update\.php on this wiki/' );
		$this->maintenance->execute();
	}

	public function testExecuteWhenUsingInvalidConfig() {
		// Set wgAutoCreateTempUser to true to simulate that an invalid config has been set in LocalSettings.php.
		// wgAutoCreateTempUser should normally be an array and a boolean value is invalid for this configuration.
		$this->overrideConfigValue( MainConfigNames::AutoCreateTempUser, true );
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Some of your configuration settings caused a warning[\s\S]*AutoCreateTempUser/' );
		$this->maintenance->execute();
	}
}
