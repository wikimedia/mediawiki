<?php

namespace MediaWiki\Tests\Unit\Revision;

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Revision\RevisionArchiveRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionSlots;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\Mocks\Content\DummyContentForTesting;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Revision\RevisionArchiveRecord
 * @covers \MediaWiki\Revision\RevisionRecord
 */
class RevisionArchiveRecordTest extends MediaWikiUnitTestCase {
	use RevisionRecordTests;

	protected static function expectedDefaultFieldVisibility( int $field ): bool {
		return match ( $field ) {
			RevisionRecord::DELETED_TEXT => false,
			RevisionRecord::DELETED_COMMENT => false,
			RevisionRecord::DELETED_USER => true,
		};
	}

	/**
	 * @param array $rowOverrides
	 * @return RevisionArchiveRecord
	 */
	protected function newRevision( array $rowOverrides = [] ) {
		$wikiId = $rowOverrides['wikiId'] ?? RevisionRecord::LOCAL;

		$title = new PageIdentityValue( 17, NS_MAIN, 'Dummy', $wikiId );

		$user = new UserIdentityValue( 11, 'Tester' );
		$comment = CommentStoreComment::newUnsavedComment( 'Hello World' );

		$main = SlotRecord::newUnsaved( SlotRecord::MAIN, new DummyContentForTesting( 'Lorem Ipsum' ) );
		$aux = SlotRecord::newUnsaved( 'aux', new DummyContentForTesting( 'Frumious Bandersnatch' ) );
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
			if ( $field === 'rev_id' ) {
				$field = 'ar_rev_id';
			} else {
				$field = preg_replace( '/^rev_/', 'ar_', $field );
			}
			$row[$field] = $value;
		}

		return new RevisionArchiveRecord( $title, $user, $comment, (object)$row, $slots, $wikiId );
	}

	public function testIsCurrent() {
		$rev = $this->newRevision();
		$this->assertFalse( $rev->isCurrent(),
			RevisionArchiveRecord::class . ' cannot be stored current revision' );
	}
}
