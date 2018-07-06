<?php
use MediaWiki\Tests\Storage\McrReadNewSchemaOverride;

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
class RevisionMcrReadNewDbTest extends RevisionDbTestBase {

	use McrReadNewSchemaOverride;

	protected function getContentHandlerUseDB() {
		return true;
	}

}
