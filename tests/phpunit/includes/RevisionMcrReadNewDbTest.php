<?php

use MediaWiki\Storage\MutableRevisionRecord;
use MediaWiki\Storage\SlotRecord;
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

	public function provideGetTextId() {
		yield [ [], null ];

		$slot = new SlotRecord( (object)[
			'slot_revision_id' => 42,
			'slot_content_id' => 1,
			'content_address' => 'tt:789',
			'model_name' => CONTENT_MODEL_WIKITEXT,
			'role_name' => 'main',
			'slot_origin' => 1,
		], new WikitextContent( 'Test' ) );

		$rec = new MutableRevisionRecord( $this->getMockTitle() );
		$rec->setId( 42 );
		$rec->setSlot( $slot );

		yield [ $rec, 789 ];
	}

}
