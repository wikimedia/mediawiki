<?php
use MediaWiki\Tests\Storage\McrWriteBothSchemaOverride;

/**
 * Tests Revision against the intermediate MCR DB schema for use during schema migration.
 *
 * @covers Revision
 *
 * @group Revision
 * @group Storage
 * @group ContentHandler
 * @group Database
 * @group medium
 */
class RevisionMcrWriteBothDbTest extends RevisionDbTestBase {

	use McrWriteBothSchemaOverride;

	protected function getContentHandlerUseDB() {
		return true;
	}

}
