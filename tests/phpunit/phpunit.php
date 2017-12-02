#!/usr/bin/env php
<?php

define( 'MW_PHPUNIT_TEST', true );
require_once dirname( dirname( __DIR__ ) ) . "/maintenance/Maintenance.php";

class PHPUnitMaintClass extends Maintenance {
	public function finalSetup() {
		self::requireTestsAutoloader();
		TestSetup::applyInitialConfig();
		echo "\n\n"; echo json_encode(1457521464.3814);echo "\n\n";
	}

	public function execute() {
	}
}

$maintClass = 'PHPUnitMaintClass';
require RUN_MAINTENANCE_IF_MAIN;
