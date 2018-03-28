<?php
use MediaWiki\Tests\Storage\PreMcrSchemaOverride;

/**
 * Tests Revision against the pre-MCR DB schema.
 *
 * @covers Revision
 *
 * @group Revision
 * @group Storage
 * @group ContentHandler
 * @group Database
 * @group medium
 */
class RevisionPreMcrDbTest extends RevisionDbTestBase {

	use PreMcrSchemaOverride;

	protected function getContentHandlerUseDB() {
		return true;
	}

}
