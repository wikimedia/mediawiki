<?php

namespace MediaWiki\Tests\Unit\Revision;

use CommentStoreComment;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Revision\RevisionArchiveRecord;
use MediaWiki\Revision\RevisionSlots;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;
use TextContent;

/**
 * @covers \MediaWiki\Revision\RevisionArchiveRecord
 * @covers \MediaWiki\Revision\RevisionRecord
 */
class RevisionArchiveRecordTest extends MediaWikiUnitTestCase {
	use RevisionRecordTests;

	/**
	 * @param array $rowOverrides
	 *
	 * @return RevisionArchiveRecord
	 */
	protected function newRevision( array $rowOverrides = [] ) {
		$title = new PageIdentityValue( 17, NS_MAIN, 'Dummy', PageIdentity::LOCAL );

		$user = new UserIdentityValue( 11, 'Tester', 0 );
		$comment = CommentStoreComment::newUnsavedComment( 'Hello World' );

		$main = SlotRecord::newUnsaved( SlotRecord::MAIN, new TextContent( 'Lorem Ipsum' ) );
		$aux = SlotRecord::newUnsaved( 'aux', new TextContent( 'Frumious Bandersnatch' ) );
		$slots = new RevisionSlots( [ $main, $aux ] );

		$row = [
			'ar_id' => '5',
			'ar_rev_id' => '7',
			'ar_page_id' => '17',
			'ar_timestamp' => '20200101000000',
			'ar_deleted' => 0,
			'ar_minor_edit' => 0,
			'ar_parent_id' => '5',
			'ar_len' => $slots->computeSize(),
			'ar_sha1' => $slots->computeSha1(),
		];

		foreach ( $rowOverrides as $field => $value ) {
			$field = preg_replace( '/^rev_/', 'ar_', $field );
			$row[$field] = $value;
		}

		return new RevisionArchiveRecord( $title, $user, $comment, (object)$row, $slots );
	}

	/**
	 * @covers \MediaWiki\Revision\RevisionRecord::isCurrent
	 */
	public function testIsCurrent() {
		$rev = $this->newRevision();
		$this->assertFalse( $rev->isCurrent(),
			RevisionArchiveRecord::class . ' cannot be stored current revision' );
	}
}
