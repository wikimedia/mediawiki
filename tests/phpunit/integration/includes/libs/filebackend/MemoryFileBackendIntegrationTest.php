<?php

use MediaWiki\WikiMap\WikiMap;

/**
 * @group FileBackend
 * @covers MemoryFileBackend
 * @covers FileBackendStore
 * @covers NullLockManager
 */
class MemoryFileBackendIntegrationTest extends FileBackendIntegrationTestBase {
	protected function getBackend() {
		return new MemoryFileBackend( [
			'name' => 'localtesting',
			'wikiId' => WikiMap::getCurrentWikiId(),
		] );
	}
}
