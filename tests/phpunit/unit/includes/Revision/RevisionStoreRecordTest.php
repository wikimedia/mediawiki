<?php

namespace MediaWiki\Tests\Unit\Revision;

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionSlots;
use MediaWiki\Revision\RevisionStoreRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\Mocks\Content\DummyContentForTesting;
use MediaWiki\User\UserIdentityValue;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Revision\RevisionStoreRecord
 * @covers \MediaWiki\Revision\RevisionRecord
 */
class RevisionStoreRecordTest extends MediaWikiUnitTestCase {
	use RevisionRecordTests;

	protected static function expectedDefaultFieldVisibility( int $field ): bool {
		return true;
	}

	/**
	 * @param array $rowOverrides
	 *
	 * @return RevisionStoreRecord
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
			'rev_id' => '7',
			'rev_page' => '17',
			'rev_timestamp' => '20200101000000',
			'rev_deleted' => 0,
			'rev_minor_edit' => 0,
			'rev_parent_id' => '5',
			'rev_len' => $slots->computeSize(),
			'rev_sha1' => $slots->computeSha1(),
			'page_latest' => '18',
		];

		$row = array_merge( $row, $rowOverrides );

		return new RevisionStoreRecord( $title, $user, $comment, (object)$row, $slots, $wikiId );
	}

	public static function provideIsCurrent() {
		yield [
			[
				'rev_id' => 11,
				'page_latest' => 11,
			],
			true,
		];
		yield [
			[
				'rev_id' => 11,
				'page_latest' => 10,
			],
			false,
		];
	}

	/**
	 * @dataProvider provideIsCurrent
	 */
	public function testIsCurrent( $row, $current ) {
		$rev = $this->newRevision( $row );

		$this->assertSame( $current, $rev->isCurrent(), 'isCurrent()' );
	}

	public static function provideGetSlot_audience_latest() {
		return self::provideAudienceCheckData( RevisionRecord::DELETED_TEXT );
	}

	/**
	 * @dataProvider provideGetSlot_audience_latest
	 */
	public function testGetSlot_audience_latest( $visibility, $permissions, $userCan, $publicCan ) {
		$performer = $this->mockRegisteredAuthorityWithPermissions( $permissions );
		$rev = $this->newRevision(
			[
				'rev_deleted' => $visibility,
				'rev_id' => 11,
				'page_latest' => 11, // revision is current
			]
		);

		// NOTE: slot meta-data is never suppressed, just the content is!
		$this->assertNotNull( $rev->getSlot( SlotRecord::MAIN, RevisionRecord::RAW ), 'raw can' );
		$this->assertNotNull( $rev->getSlot( SlotRecord::MAIN, RevisionRecord::FOR_PUBLIC ),
			'public can' );

		$this->assertNotNull(
			$rev->getSlot( SlotRecord::MAIN, RevisionRecord::FOR_THIS_USER, $performer ),
			'user can'
		);

		$rev->getSlot( SlotRecord::MAIN, RevisionRecord::RAW )->getContent();
		// NOTE: the content of the current revision is never suppressed!
		// Check that getContent() doesn't throw SuppressedDataException
		$rev->getSlot( SlotRecord::MAIN, RevisionRecord::FOR_PUBLIC )->getContent();
		$rev->getSlot( SlotRecord::MAIN, RevisionRecord::FOR_THIS_USER, $performer )->getContent();
	}

}
