<?php

namespace MediaWiki\Tests\Storage;

use CommentStoreComment;
use InvalidArgumentException;
use LogicException;
use MediaWiki\Storage\RevisionRecordBase;
use MediaWiki\Storage\RevisionSlots;
use MediaWiki\Storage\RevisionStoreRecordBase;
use MediaWiki\Storage\SlotRecord;
use MediaWiki\Storage\SuppressedDataException;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWikiTestCase;
use TextContent;
use Title;

/**
 * @covers \MediaWiki\Storage\RevisionStoreRecordBase
 */
class RevisionStoreRecordTest extends MediaWikiTestCase {

	/**
	 * @param array $rowOverrides
	 *
	 * @return RevisionStoreRecordBase
	 */
	public function newRevision( array $rowOverrides = [] ) {
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

		$row = array_merge( $row, $rowOverrides );

		return new RevisionStoreRecordBase( $title, $user, $comment, (object)$row, $slots );
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
		$row['rev_deleted'] = strval( RevisionRecordBase::DELETED_USER );

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
		$rec = new RevisionStoreRecordBase( $title, $user, $comment, $row, $slots, $wikiId );

		$this->assertSame( $title, $rec->getPageAsLinkTarget(), 'getPageAsLinkTarget' );
		$this->assertSame( $user, $rec->getUser( RevisionRecordBase::RAW ), 'getUser' );
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
		new RevisionStoreRecordBase( $title, $user, $comment, $row, $slots, $wikiId );
	}

	private function provideAudienceCheckData( $field ) {
		yield 'field accessible for oversighter (ALL)' => [
			RevisionRecordBase::SUPPRESSED_ALL,
			[ 'oversight' ],
			true,
			false
		];

		yield 'field accessible for oversighter' => [
			RevisionRecordBase::DELETED_RESTRICTED | $field,
			[ 'oversight' ],
			true,
			false
		];

		yield 'field not accessible for sysops (ALL)' => [
			RevisionRecordBase::SUPPRESSED_ALL,
			[ 'sysop' ],
			false,
			false
		];

		yield 'field not accessible for sysops' => [
			RevisionRecordBase::DELETED_RESTRICTED | $field,
			[ 'sysop' ],
			false,
			false
		];

		yield 'field accessible for sysops' => [
			$field,
			[ 'sysop' ],
			true,
			false
		];

		yield 'field suppressed for logged in users' => [
			$field,
			[ 'user' ],
			false,
			false
		];

		yield 'unrelated field suppressed' => [
			$field === RevisionRecordBase::DELETED_COMMENT
				? RevisionRecordBase::DELETED_USER
				: RevisionRecordBase::DELETED_COMMENT,
			[ 'user' ],
			true,
			true
		];

		yield 'nothing suppressed' => [
			0,
			[ 'user' ],
			true,
			true
		];
	}

	public function testSerialization_fails() {
		$this->setExpectedException( LogicException::class );
		$rev = $this->newRevision();
		serialize( $rev );
	}

	public function provideGetComment_audience() {
		return $this->provideAudienceCheckData( RevisionRecordBase::DELETED_COMMENT );
	}

	private function forceStandardPermissions() {
		$this->setMwGlobals(
			'wgGroupPermissions',
			[
				'user' => [
					'viewsuppressed' => false,
					'suppressrevision' => false,
					'deletedtext' => false,
					'deletedhistory' => false,
				],
				'sysop' => [
					'viewsuppressed' => false,
					'suppressrevision' => false,
					'deletedtext' => true,
					'deletedhistory' => true,
				],
				'oversight' => [
					'deletedtext' => true,
					'deletedhistory' => true,
					'viewsuppressed' => true,
					'suppressrevision' => true,
				],
			]
		);
	}

	/**
	 * @dataProvider provideGetComment_audience
	 */
	public function testGetComment_audience( $visibility, $groups, $userCan, $publicCan ) {
		$this->forceStandardPermissions();

		$user = $this->getTestUser( $groups )->getUser();
		$rev = $this->newRevision( [ 'rev_deleted' => $visibility ] );

		$this->assertNotNull( $rev->getComment( RevisionRecordBase::RAW ), 'raw can' );

		$this->assertSame(
			$publicCan,
			$rev->getComment( RevisionRecordBase::FOR_PUBLIC ) !== null,
			'public can'
		);
		$this->assertSame(
			$userCan,
			$rev->getComment( RevisionRecordBase::FOR_THIS_USER, $user ) !== null,
			'user can'
		);
	}

	public function provideGetUser_audience() {
		return $this->provideAudienceCheckData( RevisionRecordBase::DELETED_USER );
	}

	/**
	 * @dataProvider provideGetUser_audience
	 */
	public function testGetUser_audience( $visibility, $groups, $userCan, $publicCan ) {
		$this->forceStandardPermissions();

		$user = $this->getTestUser( $groups )->getUser();
		$rev = $this->newRevision( [ 'rev_deleted' => $visibility ] );

		$this->assertNotNull( $rev->getUser( RevisionRecordBase::RAW ), 'raw can' );

		$this->assertSame(
			$publicCan,
			$rev->getUser( RevisionRecordBase::FOR_PUBLIC ) !== null,
			'public can'
		);
		$this->assertSame(
			$userCan,
			$rev->getUser( RevisionRecordBase::FOR_THIS_USER, $user ) !== null,
			'user can'
		);
	}

	public function provideGetSlot_audience() {
		return $this->provideAudienceCheckData( RevisionRecordBase::DELETED_TEXT );
	}

	/**
	 * @dataProvider provideGetSlot_audience
	 */
	public function testGetSlot_audience( $visibility, $groups, $userCan, $publicCan ) {
		$this->forceStandardPermissions();

		$user = $this->getTestUser( $groups )->getUser();
		$rev = $this->newRevision( [ 'rev_deleted' => $visibility ] );

		// NOTE: slot meta-data is never suppressed, just the content is!
		$this->assertNotNull( $rev->getSlot( 'main', RevisionRecordBase::RAW ), 'raw can' );
		$this->assertNotNull( $rev->getSlot( 'main', RevisionRecordBase::FOR_PUBLIC ), 'public can' );

		$this->assertNotNull(
			$rev->getSlot( 'main', RevisionRecordBase::FOR_THIS_USER, $user ),
			'user can'
		);

		try {
			$rev->getSlot( 'main', RevisionRecordBase::FOR_PUBLIC )->getContent();
			$exception = null;
		} catch ( SuppressedDataException $ex ) {
			$exception = $ex;
		}

		$this->assertSame(
			$publicCan,
			$exception === null,
			'public can'
		);

		try {
			$rev->getSlot( 'main', RevisionRecordBase::FOR_THIS_USER, $user )->getContent();
			$exception = null;
		} catch ( SuppressedDataException $ex ) {
			$exception = $ex;
		}

		$this->assertSame(
			$userCan,
			$exception === null,
			'user can'
		);
	}

	public function provideGetSlot_audience_latest() {
		return $this->provideAudienceCheckData( RevisionRecordBase::DELETED_TEXT );
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

		// sanity check
		$this->assertTrue( $rev->isCurrent(), 'isCurrent()' );

		// NOTE: slot meta-data is never suppressed, just the content is!
		$this->assertNotNull( $rev->getSlot( 'main', RevisionRecordBase::RAW ), 'raw can' );
		$this->assertNotNull( $rev->getSlot( 'main', RevisionRecordBase::FOR_PUBLIC ), 'public can' );

		$this->assertNotNull(
			$rev->getSlot( 'main', RevisionRecordBase::FOR_THIS_USER, $user ),
			'user can'
		);

		// NOTE: the content of the current revision is never suppressed!
		// Check that getContent() doesn't throw SuppressedDataException
		$rev->getSlot( 'main', RevisionRecordBase::RAW )->getContent();
		$rev->getSlot( 'main', RevisionRecordBase::FOR_PUBLIC )->getContent();
		$rev->getSlot( 'main', RevisionRecordBase::FOR_THIS_USER, $user )->getContent();
	}

	/**
	 * @dataProvider provideGetSlot_audience
	 */
	public function testGetContent_audience( $visibility, $groups, $userCan, $publicCan ) {
		$this->forceStandardPermissions();

		$user = $this->getTestUser( $groups )->getUser();
		$rev = $this->newRevision( [ 'rev_deleted' => $visibility ] );

		$this->assertNotNull( $rev->getContent( 'main', RevisionRecordBase::RAW ), 'raw can' );

		$this->assertSame(
			$publicCan,
			$rev->getContent( 'main', RevisionRecordBase::FOR_PUBLIC ) !== null,
			'public can'
		);
		$this->assertSame(
			$userCan,
			$rev->getContent( 'main', RevisionRecordBase::FOR_THIS_USER, $user ) !== null,
			'user can'
		);
	}

	public function testGetSlot() {
		$rev = $this->newRevision();

		$slot = $rev->getSlot( 'main' );
		$this->assertNotNull( $slot, 'getSlot()' );
		$this->assertSame( 'main', $slot->getRole(), 'getRole()' );
	}

	public function testGetContent() {
		$rev = $this->newRevision();

		$content = $rev->getSlot( 'main' );
		$this->assertNotNull( $content, 'getContent()' );
		$this->assertSame( CONTENT_MODEL_TEXT, $content->getModel(), 'getModel()' );
	}

	public function provideUserCanBitfield() {
		yield [ 0, 0, [], null, true ];
		// Bitfields match, user has no permissions
		yield [
			RevisionRecordBase::DELETED_TEXT,
			RevisionRecordBase::DELETED_TEXT,
			[],
			null,
			false
		];
		yield [
			RevisionRecordBase::DELETED_COMMENT,
			RevisionRecordBase::DELETED_COMMENT,
			[],
			null,
			false,
		];
		yield [
			RevisionRecordBase::DELETED_USER,
			RevisionRecordBase::DELETED_USER,
			[],
			null,
			false
		];
		yield [
			RevisionRecordBase::DELETED_RESTRICTED,
			RevisionRecordBase::DELETED_RESTRICTED,
			[],
			null,
			false,
		];
		// Bitfields match, user (admin) does have permissions
		yield [
			RevisionRecordBase::DELETED_TEXT,
			RevisionRecordBase::DELETED_TEXT,
			[ 'sysop' ],
			null,
			true,
		];
		yield [
			RevisionRecordBase::DELETED_COMMENT,
			RevisionRecordBase::DELETED_COMMENT,
			[ 'sysop' ],
			null,
			true,
		];
		yield [
			RevisionRecordBase::DELETED_USER,
			RevisionRecordBase::DELETED_USER,
			[ 'sysop' ],
			null,
			true,
		];
		// Bitfields match, user (admin) does not have permissions
		yield [
			RevisionRecordBase::DELETED_RESTRICTED,
			RevisionRecordBase::DELETED_RESTRICTED,
			[ 'sysop' ],
			null,
			false,
		];
		// Bitfields match, user (oversight) does have permissions
		yield [
			RevisionRecordBase::DELETED_RESTRICTED,
			RevisionRecordBase::DELETED_RESTRICTED,
			[ 'oversight' ],
			null,
			true,
		];
		// Check permissions using the title
		yield [
			RevisionRecordBase::DELETED_TEXT,
			RevisionRecordBase::DELETED_TEXT,
			[ 'sysop' ],
			Title::newFromText( __METHOD__ ),
			true,
		];
		yield [
			RevisionRecordBase::DELETED_TEXT,
			RevisionRecordBase::DELETED_TEXT,
			[],
			Title::newFromText( __METHOD__ ),
			false,
		];
	}

	/**
	 * @dataProvider provideUserCanBitfield
	 * @covers \MediaWiki\Storage\RevisionRecordBase::userCanBitfield
	 */
	public function testUserCanBitfield( $bitField, $field, $userGroups, $title, $expected ) {
		$this->forceStandardPermissions();

		$user = $this->getTestUser( $userGroups )->getUser();

		$this->assertSame(
			$expected,
			RevisionRecordBase::userCanBitfield( $bitField, $field, $user, $title )
		);
	}

	private function getSlotRecord( $role, $contentString ) {
		return SlotRecord::newUnsaved( $role, new TextContent( $contentString ) );
	}

	public function provideHasSameContent() {
		/**
		 * @param SlotRecord[] $slots
		 * @param int $revId
		 * @return RevisionStoreRecordBase
		 */
		$recordCreator = function ( array $slots, $revId ) {
			$title = Title::newFromText( 'provideHasSameContent' );
			$title->resetArticleID( 19 );
			$slots = new RevisionSlots( $slots );

			return new RevisionStoreRecordBase(
				$title,
				new UserIdentityValue( 11, __METHOD__ ),
				CommentStoreComment::newUnsavedComment( __METHOD__ ),
				(object)[
					'rev_id' => strval( $revId ),
					'rev_page' => strval( $title->getArticleID() ),
					'rev_timestamp' => '20200101000000',
					'rev_deleted' => 0,
					'rev_minor_edit' => 0,
					'rev_parent_id' => '5',
					'rev_len' => $slots->computeSize(),
					'rev_sha1' => $slots->computeSha1(),
					'page_latest' => '18',
				],
				$slots
			);
		};

		// Create some slots with content
		$mainA = SlotRecord::newUnsaved( 'main', new TextContent( 'A' ) );
		$mainB = SlotRecord::newUnsaved( 'main', new TextContent( 'B' ) );
		$auxA = SlotRecord::newUnsaved( 'aux', new TextContent( 'A' ) );
		$auxB = SlotRecord::newUnsaved( 'aux', new TextContent( 'A' ) );

		$initialRecord = $recordCreator( [ $mainA ], 12 );

		return [
			'same record object' => [
				true,
				$initialRecord,
				$initialRecord,
			],
			'same record content, different object' => [
				true,
				$recordCreator( [ $mainA ], 12 ),
				$recordCreator( [ $mainA ], 13 ),
			],
			'same record content, aux slot, different object' => [
				true,
				$recordCreator( [ $auxA ], 12 ),
				$recordCreator( [ $auxB ], 13 ),
			],
			'different content' => [
				false,
				$recordCreator( [ $mainA ], 12 ),
				$recordCreator( [ $mainB ], 13 ),
			],
			'different content and number of slots' => [
				false,
				$recordCreator( [ $mainA ], 12 ),
				$recordCreator( [ $mainA, $mainB ], 13 ),
			],
		];
	}

	/**
	 * @dataProvider provideHasSameContent
	 * @covers \MediaWiki\Storage\RevisionRecordBase::hasSameContent
	 * @group Database
	 */
	public function testHasSameContent(
		$expected,
		RevisionRecordBase $record1,
		RevisionRecordBase $record2
	) {
		$this->assertSame(
			$expected,
			$record1->hasSameContent( $record2 )
		);
	}

	public function provideIsDeleted() {
		yield 'no deletion' => [
			0,
			[
				RevisionRecordBase::DELETED_TEXT => false,
				RevisionRecordBase::DELETED_COMMENT => false,
				RevisionRecordBase::DELETED_USER => false,
				RevisionRecordBase::DELETED_RESTRICTED => false,
			]
		];
		yield 'text deleted' => [
			RevisionRecordBase::DELETED_TEXT,
			[
				RevisionRecordBase::DELETED_TEXT => true,
				RevisionRecordBase::DELETED_COMMENT => false,
				RevisionRecordBase::DELETED_USER => false,
				RevisionRecordBase::DELETED_RESTRICTED => false,
			]
		];
		yield 'text and comment deleted' => [
			RevisionRecordBase::DELETED_TEXT + RevisionRecordBase::DELETED_COMMENT,
			[
				RevisionRecordBase::DELETED_TEXT => true,
				RevisionRecordBase::DELETED_COMMENT => true,
				RevisionRecordBase::DELETED_USER => false,
				RevisionRecordBase::DELETED_RESTRICTED => false,
			]
		];
		yield 'all 4 deleted' => [
			RevisionRecordBase::DELETED_TEXT +
			RevisionRecordBase::DELETED_COMMENT +
			RevisionRecordBase::DELETED_RESTRICTED +
			RevisionRecordBase::DELETED_USER,
			[
				RevisionRecordBase::DELETED_TEXT => true,
				RevisionRecordBase::DELETED_COMMENT => true,
				RevisionRecordBase::DELETED_USER => true,
				RevisionRecordBase::DELETED_RESTRICTED => true,
			]
		];
	}

	/**
	 * @dataProvider provideIsDeleted
	 * @covers \MediaWiki\Storage\RevisionRecordBase::isDeleted
	 */
	public function testIsDeleted( $revDeleted, $assertionMap ) {
		$rev = $this->newRevision( [ 'rev_deleted' => $revDeleted ] );
		foreach ( $assertionMap as $deletionLevel => $expected ) {
			$this->assertSame( $expected, $rev->isDeleted( $deletionLevel ) );
		}
	}

}
