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
 * @covers MediaWiki\Storage\RevisionStoreRecord
 */
class RevisionStoreRecordTest extends MediaWikiTestCase {

	/**
	 * @param array $overrides
	 * @return RevisionStoreRecord
	 */
	public function newRevision( array $overrides = [] ) {
		$title = Title::newFromText( 'Dummy' );
		$title->resetArticleID( 17 );

		$user = new UserIdentityValue( 11, 'Tester' );
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

		$row = array_merge( $row, $overrides );

		return new RevisionStoreRecord( $title, $user, $comment, (object)$row, $slots );
	}

	public function provideConstructor() {
		$title = Title::newFromText( 'Dummy' );
		$title->resetArticleID( 17 );

		$user = new UserIdentityValue( 11, 'Tester' );
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
		unset( $row['rev_len'] );
		unset( $row['rev_sha1'] );

		yield 'no length, no hash' => [
			$title,
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

		if ( isset( $row->rev_sha1 ) ) {
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

		$user = new UserIdentityValue( 11, 'Tester' );

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

	public function provideGetComment_privilegedAudience() {
		yield [
			Revisionrecord::SUPPRESSED_ALL,
			[ 'sysop' ],
			false,
			false
		];

		yield [
			Revisionrecord::DELETED_RESTRICTED,
			[ 'sysop' ],
			false,
			false
		];

		yield [
			Revisionrecord::DELETED_COMMENT,
			[ 'sysop' ],
			false,
			true
		];

		yield [
			Revisionrecord::DELETED_COMMENT,
			[ 'user' ],
			false,
			false
		];

		yield [
			Revisionrecord::DELETED_TEXT,
			[ 'user' ],
			true,
			true
		];

		yield [
			0,
			[ 'user' ],
			true,
			true
		];

	}

	/**
	 * @dataProvider provideGetComment_privilegedAudience
	 */
	public function testGetComment_privilegedAudience( $visibility, $groups, $userCan, $publicCan ) {
		$this->setMwGlobals(
			'wgGroupPermissions',
			[
				'sysop' => [
					'deletedtext' => true,
					'deletedhistory' => true,
				],
				'oversight' => [
					'viewsuppressed' => true,
					'suppressrevision' => true,
				],
			]
		);

		$user = $this->getTestUser( $groups )->getUser();
		$rev = $this->newRevision( [ 'deleted' => $visibility ] );

		$this->assertNotNull( $rev->getComment( RevisionRecord::RAW ) );

		$this->assertSame( $publicCan, $rev->getComment( RevisionRecord::FOR_PUBLIC ) !== null, 'public can' );
		$this->assertSame( $userCan, $rev->getComment( RevisionRecord::FOR_THIS_USER, $user ) !== null, 'user can' );
	}

	public function testGetUser_audience() {
	}

	public function testGetSlot() {
	}

	public function testGetSlot_audience() {
	}

	public function testGetContent_audience() {
	}

	public function testGetContent() {
	}

	public function testHasSameContent() {
	}

	public function testSerialization_fails() {
	}

	public function testIsDeleted() {
	}

	public function provideUserCanBitfield() {
		yield [ 0, 0, [], null, true ];
		// Bitfields match, user has no permissions
		yield [ RevisionRecord::DELETED_TEXT, RevisionRecord::DELETED_TEXT, [], null, false ];
		yield [ RevisionRecord::DELETED_COMMENT, RevisionRecord::DELETED_COMMENT, [], null, false ];
		yield [ RevisionRecord::DELETED_USER, RevisionRecord::DELETED_USER, [], null, false ];
		yield [ RevisionRecord::DELETED_RESTRICTED, RevisionRecord::DELETED_RESTRICTED, [], null, false ];
		// Bitfields match, user (admin) does have permissions
		yield [ RevisionRecord::DELETED_TEXT, RevisionRecord::DELETED_TEXT, [ 'sysop' ], null, true ];
		yield [ RevisionRecord::DELETED_COMMENT, RevisionRecord::DELETED_COMMENT, [ 'sysop' ], null, true ];
		yield [ RevisionRecord::DELETED_USER, RevisionRecord::DELETED_USER, [ 'sysop' ], null, true ];
		// Bitfields match, user (admin) does not have permissions
		yield [ RevisionRecord::DELETED_RESTRICTED, RevisionRecord::DELETED_RESTRICTED, [ 'sysop' ], null, false ];
		// Bitfields match, user (oversight) does have permissions
		yield [ RevisionRecord::DELETED_RESTRICTED, RevisionRecord::DELETED_RESTRICTED, [ 'oversight' ], null, true ];
		// Check permissions using the title
		yield [
			RevisionRecord::DELETED_TEXT,
			RevisionRecord::DELETED_TEXT,
			[ 'sysop' ],
			Title::newFromText( __METHOD__ ),
			true,
		];
		yield [
			RevisionRecord::DELETED_TEXT,
			RevisionRecord::DELETED_TEXT,
			[],
			Title::newFromText( __METHOD__ ),
			false,
		];
	}

	/**
	 * @dataProvider provideUserCanBitfield
	 * @covers RevisionRecord::userCanBitfield
	 */
	public function testUserCanBitfield( $bitField, $field, $userGroups, $title, $expected ) {
		$this->setMwGlobals(
			'wgGroupPermissions',
			[
				'sysop' => [
					'deletedtext' => true,
					'deletedhistory' => true,
				],
				'oversight' => [
					'viewsuppressed' => true,
					'suppressrevision' => true,
				],
			]
		);
		$user = $this->getTestUser( $userGroups )->getUser();

		$this->assertSame(
			$expected,
			RevisionRecord::userCanBitfield( $bitField, $field, $user, $title )
		);
	}
	
}
