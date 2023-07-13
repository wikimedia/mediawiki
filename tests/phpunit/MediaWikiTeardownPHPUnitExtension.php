<?php

use PHPUnit\Runner\AfterLastTestHook;

/**
 * PHPUnit extension used in integration tests to clean up the environment after the last test. This will:
 *  - clear the temporary job queue.
 *  - allow extensions to delete any temporary tables they created.
 *  - restore ability to connect to the real database.
 */
class MediaWikiTeardownPHPUnitExtension implements AfterLastTestHook {
	public function executeAfterLastTest(): void {
		MediaWikiIntegrationTestCase::teardownTestDB();
	}
}
