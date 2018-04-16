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

	protected function getContentHandlerUseDB() {
		return true;
	}

}
