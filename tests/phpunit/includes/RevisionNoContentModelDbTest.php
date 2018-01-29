<?php
use MediaWiki\Tests\Storage\PreMcrSchemaOverride;

/**
 * Tests Revision against the pre-MCR, pre ContentHandler DB schema.
 *
 * @covers Revision
 *
 * @group Revision
 * @group Storage
 * @group ContentHandler
 * @group Database
 * @group medium
 */
class RevisionNoContentModelDbTest extends RevisionDbTestBase {

	use PreMcrSchemaOverride;

	protected function getContentHandlerUseDB() {
		return false;
	}

}
