<?php
use MediaWiki\Tests\Storage\McrSchemaOverride;

/**
 * Tests Revision against the MCR DB schema after schema migration.
 *
 * @covers Revision
 *
 * @group Revision
 * @group Storage
 * @group ContentHandler
 * @group Database
 * @group medium
 */
class RevisionMcrDbTest extends RevisionDbTestBase {

	use McrSchemaOverride;

	public function setUp() {
		parent::setUp();
	}

	protected function getContentHandlerUseDB() {
		return true;
	}

}
