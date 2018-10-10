<?php
use MediaWiki\Tests\Revision\McrWriteBothSchemaOverride;

/**
 * Tests WikiPage against the intermediate MCR DB schema for use during schema migration.
 *
 * @covers WikiPage
 *
 * @group WikiPage
 * @group Storage
 * @group ContentHandler
 * @group Database
 * @group medium
 */
class WikiPageMcrWriteBothDbTest extends WikiPageDbTestBase {

	use McrWriteBothSchemaOverride;

	protected function getContentHandlerUseDB() {
		return true;
	}

}
