<?php

use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\Revision\McrSchemaOverride;

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

	public function provideGetTextId() {
		yield [ [], null ];

		$slot = new SlotRecord( (object)[
			'slot_revision_id' => 42,
			'slot_content_id' => 1,
			'content_address' => 'tt:789',
			'model_name' => CONTENT_MODEL_WIKITEXT,
			'role_name' => SlotRecord::MAIN,
			'slot_origin' => 1,
		], new WikitextContent( 'Test' ) );

		$rec = new MutableRevisionRecord( $this->getMockTitle() );
		$rec->setId( 42 );
		$rec->setSlot( $slot );

		yield [ $rec, 789 ];
	}

}
