<?php
namespace MediaWiki\Tests\Storage;

/**
 * Tests RevisionStore against the pre-MCR DB schema.
 *
 * @covers MediaWiki\Storage\RevisionStore
 *
 * @group RevisionStore
 * @group Storage
 * @group Database
 * @group medium
 */
class PreMcrRevisionStoreDbTest extends RevisionStoreDbTestBase {

	use PreMcrSchemaOverride;

}
