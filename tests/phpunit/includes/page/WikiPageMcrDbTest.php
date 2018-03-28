<?php

use MediaWiki\Tests\Storage\McrSchemaOverride;

/**
 * Tests WikiPage against the MCR DB schema after schema migration.
 *
 * @covers WikiPage
 *
 * @group WikiPage
 * @group Storage
 * @group ContentHandler
 * @group Database
 * @group medium
 */
class WikiPageMcrDbTest extends WikiPageDbTestBase {

	use McrSchemaOverride;

	public function setUp() {
		parent::setUp();

		// FIXME! Remove this before merging!
		$this->markTestSkipped( 'MIGRATION_NEW mode is work in progress!' );
	}

	protected function getContentHandlerUseDB() {
		return true;
	}

}
