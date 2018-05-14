<?php
use MediaWiki\Tests\Storage\PreMcrSchemaOverride;

/**
 * Tests WikiPage against the pre-MCR, pre ContentHandler DB schema.
 *
 * @covers WikiPage
 *
 * @group WikiPage
 * @group Storage
 * @group ContentHandler
 * @group Database
 * @group medium
 */
class WikiPageNoContentModelDbTest extends WikiPageDbTestBase {

	use PreMcrSchemaOverride;

	protected function getContentHandlerUseDB() {
		return false;
	}

}
