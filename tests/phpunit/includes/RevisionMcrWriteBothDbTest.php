<?php

use MediaWiki\Tests\Revision\McrWriteBothSchemaOverride;

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

	public function provideGetTextId() {
		yield [ [], null ];

		$row = (object)[
			'rev_id' => 7,
			'rev_page' => 1, // should match actual page id
			'rev_text_id' => 789,
			'rev_timestamp' => '20180101000000',
			'rev_len' => 7,
			'rev_minor_edit' => 0,
			'rev_deleted' => 0,
			'rev_parent_id' => 0,
			'rev_sha1' => 'deadbeef',
			'rev_comment' => 'some comment',
			'rev_comment_text' => 'some comment',
			'rev_comment_data' => '{}',
			'rev_user' => 17,
			'rev_user_text' => 'some user',
		];

		yield [ $row, 789 ];
	}

	public function provideGetRevisionText() {
		yield [
			[ 'text' ]
		];
	}
}
