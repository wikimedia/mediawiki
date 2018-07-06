<?php
use MediaWiki\Tests\Storage\McrReadNewSchemaOverride;

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
class WikiPageMcrReadNewDbTest extends WikiPageDbTestBase {

	use McrReadNewSchemaOverride;

	protected function getContentHandlerUseDB() {
		return true;
	}

}
