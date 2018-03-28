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

		// FIXME! Remove this before merging!
		$this->markTestSkipped( 'MIGRATION_NEW mode is work in progress!' );
	}

	protected function getContentHandlerUseDB() {
		return true;
	}

}
