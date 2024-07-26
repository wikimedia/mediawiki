<?php

namespace MediaWiki\Tests\Maintenance;

use PageExists;

/**
 * @covers \PageExists
 * @group Database
 * @author Dreamy Jazz
 */
class PageExistsTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return PageExists::class;
	}

	public function testExecuteForNonExistingPage() {
		$nonExistentTestPage = $this->getNonexistingTestPage();
		$this->maintenance->setArg( 'title', $nonExistentTestPage );
		$this->maintenance->execute();
		$this->expectOutputString(
			"$nonExistentTestPage doesn't exist.\n"
		);
	}

	public function testExecuteForExistingPage() {
		$existingTestPage = $this->getExistingTestPage();
		$this->maintenance->setArg( 'title', $existingTestPage );
		$this->maintenance->execute();
		$this->expectOutputString(
			"$existingTestPage exists.\n"
		);
	}
}
