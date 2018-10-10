<?php

use MediaWiki\Tests\Revision\PreMcrSchemaOverride;

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

}
