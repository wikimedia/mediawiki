<?php

// phpcs:disable MediaWiki.Commenting.PhpunitAnnotations.NotClass

namespace MediaWiki\Tests\Revision;

use CommentStoreComment;
use LogicException;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionSlots;
use MediaWiki\Revision\RevisionStoreRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SuppressedDataException;
use MediaWiki\User\UserIdentityValue;
use TextContent;
use Title;

/**
 * @covers \MediaWiki\Revision\RevisionRecord
 *
 * @note Expects to be used in classes that extend MediaWikiIntegrationTestCase.
 */
trait RevisionRecordTests {

	/**
	 * @param array $rowOverrides
	 *
	 * @return RevisionRecord
	 */
	abstract protected function newRevision( array $rowOverrides = [] );

	private function provideAudienceCheckData( $field ) {
		yield 'field accessible for oversighter (ALL)' => [
			RevisionRecord::SUPPRESSED_ALL,
			[ 'oversight' ],
			true,
			false
		];

		yield 'field accessible for oversighter' => [
			RevisionRecord::DELETED_RESTRICTED | $field,
			[ 'oversight' ],
			true,
			false
		];

		yield 'field not accessible for sysops (ALL)' => [
			RevisionRecord::SUPPRESSED_ALL,
			[ 'sysop' ],
			false,
			false
		];

		yield 'field not accessible for sysops' => [
			RevisionRecord::DELETED_RESTRICTED | $field,
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
			$field === RevisionRecord::DELETED_COMMENT
				? RevisionRecord::DELETED_USER
				: RevisionRecord::DELETED_COMMENT,
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
		$this->expectException( LogicException::class );
		$rev = $this->newRevision();
		serialize( $rev );
	}

	public function provideGetComment_audience() {
		return $this->provideAudienceCheckData( RevisionRecord::DELETED_COMMENT );
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

		$this->assertNotNull( $rev->getComment( RevisionRecord::RAW ), 'raw can' );

		$this->assertSame(
			$publicCan,
			$rev->getComment( RevisionRecord::FOR_PUBLIC ) !== null,
			'public can'
		);
		$this->assertSame(
			$userCan,
			$rev->getComment( RevisionRecord::FOR_THIS_USER, $user ) !== null,
			'user can'
		);
	}

	public function provideGetUser_audience() {
		return $this->provideAudienceCheckData( RevisionRecord::DELETED_USER );
	}

	/**
	 * @dataProvider provideGetUser_audience
	 */
	public function testGetUser_audience( $visibility, $groups, $userCan, $publicCan ) {
		$this->forceStandardPermissions();

		$user = $this->getTestUser( $groups )->getUser();
		$rev = $this->newRevision( [ 'rev_deleted' => $visibility ] );

		$this->assertNotNull( $rev->getUser( RevisionRecord::RAW ), 'raw can' );

		$this->assertSame(
			$publicCan,
			$rev->getUser( RevisionRecord::FOR_PUBLIC ) !== null,
			'public can'
		);
		$this->assertSame(
			$userCan,
			$rev->getUser( RevisionRecord::FOR_THIS_USER, $user ) !== null,
			'user can'
		);
	}

	public function provideGetSlot_audience() {
		return $this->provideAudienceCheckData( RevisionRecord::DELETED_TEXT );
	}

	/**
	 * @dataProvider provideGetSlot_audience
	 */
	public function testGetSlot_audience( $visibility, $groups, $userCan, $publicCan ) {
		$this->forceStandardPermissions();

		$user = $this->getTestUser( $groups )->getUser();
		$rev = $this->newRevision( [ 'rev_deleted' => $visibility ] );

		// NOTE: slot meta-data is never suppressed, just the content is!
		$this->assertTrue( $rev->hasSlot( SlotRecord::MAIN ), 'hasSlot is never suppressed' );
		$this->assertNotNull( $rev->getSlot( SlotRecord::MAIN, RevisionRecord::RAW ), 'raw meta' );
		$this->assertNotNull( $rev->getSlot( SlotRecord::MAIN, RevisionRecord::FOR_PUBLIC ),
			'public meta' );

		$this->assertNotNull(
			$rev->getSlot( SlotRecord::MAIN, RevisionRecord::FOR_THIS_USER, $user ),
			'user can'
		);

		try {
			$rev->getSlot( SlotRecord::MAIN, RevisionRecord::FOR_PUBLIC )->getContent();
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
			$rev->getSlot( SlotRecord::MAIN, RevisionRecord::FOR_THIS_USER, $user )->getContent();
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

	/**
	 * @dataProvider provideGetSlot_audience
	 */
	public function testGetContent_audience( $visibility, $groups, $userCan, $publicCan ) {
		$this->forceStandardPermissions();

		$user = $this->getTestUser( $groups )->getUser();
		$rev = $this->newRevision( [ 'rev_deleted' => $visibility ] );

		$this->assertNotNull( $rev->getContent( SlotRecord::MAIN, RevisionRecord::RAW ), 'raw can' );

		$this->assertSame(
			$publicCan,
			$rev->getContent( SlotRecord::MAIN, RevisionRecord::FOR_PUBLIC ) !== null,
			'public can'
		);
		$this->assertSame(
			$userCan,
			$rev->getContent( SlotRecord::MAIN, RevisionRecord::FOR_THIS_USER, $user ) !== null,
			'user can'
		);
	}

	public function testGetSlot() {
		$rev = $this->newRevision();

		$slot = $rev->getSlot( SlotRecord::MAIN );
		$this->assertNotNull( $slot, 'getSlot()' );
		$this->assertSame( 'main', $slot->getRole(), 'getRole()' );
	}

	public function testHasSlot() {
		$rev = $this->newRevision();

		$this->assertTrue( $rev->hasSlot( SlotRecord::MAIN ) );
		$this->assertFalse( $rev->hasSlot( 'xyz' ) );
	}

	public function testGetContent() {
		$rev = $this->newRevision();

		$content = $rev->getSlot( SlotRecord::MAIN );
		$this->assertNotNull( $content, 'getContent()' );
		$this->assertSame( CONTENT_MODEL_TEXT, $content->getModel(), 'getModel()' );
	}

	public function provideUserCanBitfield() {
		yield [ 0, 0, [], null, true ];
		// Bitfields match, user has no permissions
		yield [
			RevisionRecord::DELETED_TEXT,
			RevisionRecord::DELETED_TEXT,
			[],
			null,
			false
		];
		yield [
			RevisionRecord::DELETED_COMMENT,
			RevisionRecord::DELETED_COMMENT,
			[],
			null,
			false,
		];
		yield [
			RevisionRecord::DELETED_USER,
			RevisionRecord::DELETED_USER,
			[],
			null,
			false
		];
		yield [
			RevisionRecord::DELETED_RESTRICTED,
			RevisionRecord::DELETED_RESTRICTED,
			[],
			null,
			false,
		];
		// Bitfields match, user (admin) does have permissions
		yield [
			RevisionRecord::DELETED_TEXT,
			RevisionRecord::DELETED_TEXT,
			[ 'sysop' ],
			null,
			true,
		];
		yield [
			RevisionRecord::DELETED_COMMENT,
			RevisionRecord::DELETED_COMMENT,
			[ 'sysop' ],
			null,
			true,
		];
		yield [
			RevisionRecord::DELETED_USER,
			RevisionRecord::DELETED_USER,
			[ 'sysop' ],
			null,
			true,
		];
		// Bitfields match, user (admin) does not have permissions
		yield [
			RevisionRecord::DELETED_RESTRICTED,
			RevisionRecord::DELETED_RESTRICTED,
			[ 'sysop' ],
			null,
			false,
		];
		// Bitfields match, user (oversight) does have permissions
		yield [
			RevisionRecord::DELETED_RESTRICTED,
			RevisionRecord::DELETED_RESTRICTED,
			[ 'oversight' ],
			null,
			true,
		];
		// Check permissions using the title
		yield [
			RevisionRecord::DELETED_TEXT,
			RevisionRecord::DELETED_TEXT,
			[ 'sysop' ],
			__METHOD__,
			true,
		];
		yield [
			RevisionRecord::DELETED_TEXT,
			RevisionRecord::DELETED_TEXT,
			[],
			__METHOD__,
			false,
		];
	}

	/**
	 * @dataProvider provideUserCanBitfield
	 * @covers \MediaWiki\Revision\RevisionRecord::userCanBitfield
	 */
	public function testUserCanBitfield( $bitField, $field, $userGroups, $title, $expected ) {
		if ( is_string( $title ) ) {
			// NOTE: Data providers cannot instantiate Title objects! See T202641.
			$title = Title::newFromText( $title );
		}

		$this->forceStandardPermissions();

		$user = $this->getTestUser( $userGroups )->getUser();

		$this->assertSame(
			$expected,
			RevisionRecord::userCanBitfield( $bitField, $field, $user, $title )
		);
	}

	public function provideHasSameContent() {
		// Create some slots with content
		$mainA = SlotRecord::newUnsaved( SlotRecord::MAIN, new TextContent( 'A' ) );
		$mainB = SlotRecord::newUnsaved( SlotRecord::MAIN, new TextContent( 'B' ) );
		$auxA = SlotRecord::newUnsaved( 'aux', new TextContent( 'A' ) );
		$auxB = SlotRecord::newUnsaved( 'aux', new TextContent( 'A' ) );

		$initialRecordSpec = [ [ $mainA ], 12 ];

		return [
			'same record object' => [
				true,
				$initialRecordSpec,
				$initialRecordSpec,
			],
			'same record content, different object' => [
				true,
				[ [ $mainA ], 12 ],
				[ [ $mainA ], 13 ],
			],
			'same record content, aux slot, different object' => [
				true,
				[ [ $auxA ], 12 ],
				[ [ $auxB ], 13 ],
			],
			'different content' => [
				false,
				[ [ $mainA ], 12 ],
				[ [ $mainB ], 13 ],
			],
			'different content and number of slots' => [
				false,
				[ [ $mainA ], 12 ],
				[ [ $mainA, $mainB ], 13 ],
			],
		];
	}

	/**
	 * @note Do not call directly from a data provider! Data providers cannot instantiate
	 * Title objects! See T202641.
	 *
	 * @param SlotRecord[] $slots
	 * @param int $revId
	 * @return RevisionStoreRecord
	 */
	private function makeHasSameContentTestRecord( array $slots, $revId ) {
		$title = Title::newFromText( 'provideHasSameContent' );
		$title->resetArticleID( 19 );
		$slots = new RevisionSlots( $slots );

		return new RevisionStoreRecord(
			$title,
			new UserIdentityValue( 11, __METHOD__, 0 ),
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
	}

	/**
	 * @dataProvider provideHasSameContent
	 * @covers \MediaWiki\Revision\RevisionRecord::hasSameContent
	 * @group Database
	 */
	public function testHasSameContent(
		$expected,
		$recordSpec1,
		$recordSpec2
	) {
		$record1 = $this->makeHasSameContentTestRecord( ...$recordSpec1 );
		$record2 = $this->makeHasSameContentTestRecord( ...$recordSpec2 );

		$this->assertSame(
			$expected,
			$record1->hasSameContent( $record2 )
		);
	}

	public function provideIsDeleted() {
		yield 'no deletion' => [
			0,
			[
				RevisionRecord::DELETED_TEXT => false,
				RevisionRecord::DELETED_COMMENT => false,
				RevisionRecord::DELETED_USER => false,
				RevisionRecord::DELETED_RESTRICTED => false,
			]
		];
		yield 'text deleted' => [
			RevisionRecord::DELETED_TEXT,
			[
				RevisionRecord::DELETED_TEXT => true,
				RevisionRecord::DELETED_COMMENT => false,
				RevisionRecord::DELETED_USER => false,
				RevisionRecord::DELETED_RESTRICTED => false,
			]
		];
		yield 'text and comment deleted' => [
			RevisionRecord::DELETED_TEXT + RevisionRecord::DELETED_COMMENT,
			[
				RevisionRecord::DELETED_TEXT => true,
				RevisionRecord::DELETED_COMMENT => true,
				RevisionRecord::DELETED_USER => false,
				RevisionRecord::DELETED_RESTRICTED => false,
			]
		];
		yield 'all 4 deleted' => [
			RevisionRecord::DELETED_TEXT +
			RevisionRecord::DELETED_COMMENT +
			RevisionRecord::DELETED_RESTRICTED +
			RevisionRecord::DELETED_USER,
			[
				RevisionRecord::DELETED_TEXT => true,
				RevisionRecord::DELETED_COMMENT => true,
				RevisionRecord::DELETED_USER => true,
				RevisionRecord::DELETED_RESTRICTED => true,
			]
		];
	}

	/**
	 * @dataProvider provideIsDeleted
	 * @covers \MediaWiki\Revision\RevisionRecord::isDeleted
	 */
	public function testIsDeleted( $revDeleted, $assertionMap ) {
		$rev = $this->newRevision( [ 'rev_deleted' => $revDeleted ] );
		foreach ( $assertionMap as $deletionLevel => $expected ) {
			$this->assertSame( $expected, $rev->isDeleted( $deletionLevel ) );
		}
	}

	public function testIsReadyForInsertion() {
		$rev = $this->newRevision();
		$this->assertTrue( $rev->isReadyForInsertion() );
	}

}
