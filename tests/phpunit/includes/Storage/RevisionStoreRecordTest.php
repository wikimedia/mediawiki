<?php

namespace MediaWiki\Tests\Storage;

use CommentStoreComment;
use InvalidArgumentException;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\RevisionSlots;
use MediaWiki\Storage\RevisionStoreRecord;
use MediaWiki\Storage\SlotRecord;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWikiTestCase;
use TextContent;
use Title;

/**
 * @covers \MediaWiki\Storage\RevisionStoreRecord
 * @covers \MediaWiki\Storage\RevisionRecord
 */
class RevisionStoreRecordTest extends MediaWikiTestCase {

	use RevisionRecordTests;

	/**
	 * @param array $rowOverrides
	 *
	 * @return RevisionStoreRecord
	 */
	protected function newRevision( array $rowOverrides = [] ) {
		$title = Title::newFromText( 'Dummy' );
		$title->resetArticleID( 17 );

		$user = new UserIdentityValue( 11, 'Tester', 0 );
		$comment = CommentStoreComment::newUnsavedComment( 'Hello World' );

		$main = SlotRecord::newUnsaved( 'main', new TextContent( 'Lorem Ipsum' ) );
		$aux = SlotRecord::newUnsaved( 'aux', new TextContent( 'Frumious Bandersnatch' ) );
		$slots = new RevisionSlots( [ $main, $aux ] );

		$row = [
			'rev_id' => '7',
			'rev_page' => strval( $title->getArticleID() ),
			'rev_timestamp' => '20200101000000',
			'rev_deleted' => 0,
			'rev_minor_edit' => 0,
			'rev_parent_id' => '5',
			'rev_len' => $slots->computeSize(),
			'rev_sha1' => $slots->computeSha1(),
			'page_latest' => '18',
		];

		$row = array_merge( $row, $rowOverrides );

		return new RevisionStoreRecord( $title, $user, $comment, (object)$row, $slots );
	}

	public function provideConstructor() {
		$title = Title::newFromText( 'Dummy' );
		$title->resetArticleID( 17 );

		$user = new UserIdentityValue( 11, 'Tester', 0 );
		$comment = CommentStoreComment::newUnsavedComment( 'Hello World' );

		$main = SlotRecord::newUnsaved( 'main', new TextContent( 'Lorem Ipsum' ) );
		$aux = SlotRecord::newUnsaved( 'aux', new TextContent( 'Frumious Bandersnatch' ) );
		$slots = new RevisionSlots( [ $main, $aux ] );

		$protoRow = [
			'rev_id' => '7',
			'rev_page' => strval( $title->getArticleID() ),
			'rev_timestamp' => '20200101000000',
			'rev_deleted' => 0,
			'rev_minor_edit' => 0,
			'rev_parent_id' => '5',
			'rev_len' => $slots->computeSize(),
			'rev_sha1' => $slots->computeSha1(),
			'page_latest' => '18',
		];

		$row = $protoRow;
		yield 'all info' => [
			$title,
			$user,
			$comment,
			(object)$row,
			$slots,
			'acmewiki'
		];

		$row = $protoRow;
		$row['rev_minor_edit'] = '1';
		$row['rev_deleted'] = strval( RevisionRecord::DELETED_USER );

		yield 'minor deleted' => [
			$title,
			$user,
			$comment,
			(object)$row,
			$slots
		];

		$row = $protoRow;
		$row['page_latest'] = $row['rev_id'];

		yield 'latest' => [
			$title,
			$user,
			$comment,
			(object)$row,
			$slots
		];

		$row = $protoRow;
		unset( $row['rev_parent'] );

		yield 'no parent' => [
			$title,
			$user,
			$comment,
			(object)$row,
			$slots
		];

		$row = $protoRow;
		$row['rev_len'] = null;
		$row['rev_sha1'] = '';

		yield 'rev_len is null, rev_sha1 is ""' => [
			$title,
			$user,
			$comment,
			(object)$row,
			$slots
		];

		$row = $protoRow;
		yield 'no length, no hash' => [
			Title::newFromText( 'DummyDoesNotExist' ),
			$user,
			$comment,
			(object)$row,
			$slots
		];
	}

	/**
	 * @dataProvider provideConstructor
	 *
	 * @param Title $title
	 * @param UserIdentity $user
	 * @param CommentStoreComment $comment
	 * @param object $row
	 * @param RevisionSlots $slots
	 * @param bool $wikiId
	 */
	public function testConstructorAndGetters(
		Title $title,
		UserIdentity $user,
		CommentStoreComment $comment,
		$row,
		RevisionSlots $slots,
		$wikiId = false
	) {
		$rec = new RevisionStoreRecord( $title, $user, $comment, $row, $slots, $wikiId );

		$this->assertSame( $title, $rec->getPageAsLinkTarget(), 'getPageAsLinkTarget' );
		$this->assertSame( $user, $rec->getUser( RevisionRecord::RAW ), 'getUser' );
		$this->assertSame( $comment, $rec->getComment(), 'getComment' );

		$this->assertSame( $slots->getSlotRoles(), $rec->getSlotRoles(), 'getSlotRoles' );
		$this->assertSame( $wikiId, $rec->getWikiId(), 'getWikiId' );

		$this->assertSame( (int)$row->rev_id, $rec->getId(), 'getId' );
		$this->assertSame( (int)$row->rev_page, $rec->getPageId(), 'getId' );
		$this->assertSame( $row->rev_timestamp, $rec->getTimestamp(), 'getTimestamp' );
		$this->assertSame( (int)$row->rev_deleted, $rec->getVisibility(), 'getVisibility' );
		$this->assertSame( (bool)$row->rev_minor_edit, $rec->isMinor(), 'getIsMinor' );

		if ( isset( $row->rev_parent_id ) ) {
			$this->assertSame( (int)$row->rev_parent_id, $rec->getParentId(), 'getParentId' );
		} else {
			$this->assertSame( 0, $rec->getParentId(), 'getParentId' );
		}

		if ( isset( $row->rev_len ) ) {
			$this->assertSame( (int)$row->rev_len, $rec->getSize(), 'getSize' );
		} else {
			$this->assertSame( $slots->computeSize(), $rec->getSize(), 'getSize' );
		}

		if ( !empty( $row->rev_sha1 ) ) {
			$this->assertSame( $row->rev_sha1, $rec->getSha1(), 'getSha1' );
		} else {
			$this->assertSame( $slots->computeSha1(), $rec->getSha1(), 'getSha1' );
		}

		if ( isset( $row->page_latest ) ) {
			$this->assertSame(
				(int)$row->rev_id === (int)$row->page_latest,
				$rec->isCurrent(),
				'isCurrent'
			);
		} else {
			$this->assertSame(
				false,
				$rec->isCurrent(),
				'isCurrent'
			);
		}
	}

	public function provideConstructorFailure() {
		$title = Title::newFromText( 'Dummy' );
		$title->resetArticleID( 17 );

		$user = new UserIdentityValue( 11, 'Tester', 0 );

		$comment = CommentStoreComment::newUnsavedComment( 'Hello World' );

		$main = SlotRecord::newUnsaved( 'main', new TextContent( 'Lorem Ipsum' ) );
		$aux = SlotRecord::newUnsaved( 'aux', new TextContent( 'Frumious Bandersnatch' ) );
		$slots = new RevisionSlots( [ $main, $aux ] );

		$protoRow = [
			'rev_id' => '7',
			'rev_page' => strval( $title->getArticleID() ),
			'rev_timestamp' => '20200101000000',
			'rev_deleted' => 0,
			'rev_minor_edit' => 0,
			'rev_parent_id' => '5',
			'rev_len' => $slots->computeSize(),
			'rev_sha1' => $slots->computeSha1(),
			'page_latest' => '18',
		];

		yield 'not a row' => [
			$title,
			$user,
			$comment,
			'not a row',
			$slots,
			'acmewiki'
		];

		$row = $protoRow;
		$row['rev_timestamp'] = 'kittens';

		yield 'bad timestamp' => [
			$title,
			$user,
			$comment,
			(object)$row,
			$slots
		];

		$row = $protoRow;
		$row['rev_page'] = 99;

		yield 'page ID mismatch' => [
			$title,
			$user,
			$comment,
			(object)$row,
			$slots
		];

		$row = $protoRow;

		yield 'bad wiki' => [
			$title,
			$user,
			$comment,
			(object)$row,
			$slots,
			12345
		];
	}

	/**
	 * @dataProvider provideConstructorFailure
	 *
	 * @param Title $title
	 * @param UserIdentity $user
	 * @param CommentStoreComment $comment
	 * @param object $row
	 * @param RevisionSlots $slots
	 * @param bool $wikiId
	 */
	public function testConstructorFailure(
		Title $title,
		UserIdentity $user,
		CommentStoreComment $comment,
		$row,
		RevisionSlots $slots,
		$wikiId = false
	) {
		$this->setExpectedException( InvalidArgumentException::class );
		new RevisionStoreRecord( $title, $user, $comment, $row, $slots, $wikiId );
	}

	public function provideIsCurrent() {
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

	public function provideGetSlot_audience_latest() {
		return $this->provideAudienceCheckData( RevisionRecord::DELETED_TEXT );
	}

	/**
	 * @dataProvider provideGetSlot_audience_latest
	 */
	public function testGetSlot_audience_latest( $visibility, $groups, $userCan, $publicCan ) {
		$this->forceStandardPermissions();

		$user = $this->getTestUser( $groups )->getUser();
		$rev = $this->newRevision(
			[
				'rev_deleted' => $visibility,
				'rev_id' => 11,
				'page_latest' => 11, // revision is current
			]
		);

		// NOTE: slot meta-data is never suppressed, just the content is!
		$this->assertNotNull( $rev->getSlot( 'main', RevisionRecord::RAW ), 'raw can' );
		$this->assertNotNull( $rev->getSlot( 'main', RevisionRecord::FOR_PUBLIC ), 'public can' );

		$this->assertNotNull(
			$rev->getSlot( 'main', RevisionRecord::FOR_THIS_USER, $user ),
			'user can'
		);

		$rev->getSlot( 'main', RevisionRecord::RAW )->getContent();
		// NOTE: the content of the current revision is never suppressed!
		// Check that getContent() doesn't throw SuppressedDataException
		$rev->getSlot( 'main', RevisionRecord::FOR_PUBLIC )->getContent();
		$rev->getSlot( 'main', RevisionRecord::FOR_THIS_USER, $user )->getContent();
	}

}
