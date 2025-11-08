<?php

use MediaWiki\WikiMap\WikiMap;
use Wikimedia\FileBackend\MemoryFileBackend;

/**
 * @group FileBackend
 * @covers \Wikimedia\FileBackend\MemoryFileBackend
 * @covers \Wikimedia\FileBackend\FileBackendStore
 * @covers \NullLockManager
 */
class MemoryFileBackendIntegrationTest extends FileBackendIntegrationTestBase {
	protected function getBackend() {
		return new MemoryFileBackend( [
			'name' => 'localtesting',
			'wikiId' => WikiMap::getCurrentWikiId(),
		] );
	}
}
