<?php

// phpcs:disable MediaWiki.Commenting.PhpunitAnnotations.NotClass
// phpcs:disable MediaWiki.Commenting.FunctionComment.MissingParamTag -- Traits are not excluded

namespace MediaWiki\Tests\Unit\Revision;

use DummyContentForTesting;
use LogicException;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\Content;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionSlots;
use MediaWiki\Revision\RevisionStoreRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SuppressedDataException;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\User\UserIdentityValue;

/**
 * @covers \MediaWiki\Revision\RevisionRecord
 *
 * @note Expects to be used in classes that extend MediaWikiUnitTestCase.
 */
trait RevisionRecordTests {
	use MockAuthorityTrait;

	/**
	 * @param array $rowOverrides
	 * @return RevisionRecord
	 */
	abstract protected function newRevision( array $rowOverrides = [] );

	/**
	 * provided by MediaWikiTestCaseTrait
	 * @param string $regex
	 */
	abstract public function filterDeprecated( $regex );

	public function testGetIdSuccessful() {
		$revision = $this->newRevision( [ 'wikiId' => 'acmewiki', 'rev_id' => 5 ] );
		$this->assertEquals( 5, $revision->getId( 'acmewiki' ) );
	}

	public function testGetIdTriggerDeprecatedWarning() {
		$this->expectDeprecationAndContinue( '/Deprecated cross-wiki access/' );
		$revision = $this->newRevision( [ 'wikiId' => 'acmewiki', 'rev_id' => 5 ] );
		$revision->getId();
	}

	public function testGetIdDeprecated() {
		$revision = $this->newRevision( [ 'wikiId' => 'acmewiki', 'rev_id' => 5 ] );
		$this->filterDeprecated( '/Deprecated cross-wiki access.*/' );
		$this->assertEquals( 5, $revision->getId() );
	}

	public function testGetPageIdSuccessful() {
		$revision = $this->newRevision( [ 'wikiId' => 'acmewiki', 'rev_page_id' => 17 ] );
		$this->assertEquals( 17, $revision->getPageId( 'acmewiki' ) );
	}

	public function testGetPageIdTriggerDeprecatedWarning() {
		$this->expectDeprecationAndContinue( '/Deprecated cross-wiki access/' );
		$revision = $this->newRevision( [ 'wikiId' => 'acmewiki', 'rev_page_id' => 17 ] );
		$revision->getPageId();
	}

	public function testGetPageIdDeprecated() {
		$revision = $this->newRevision( [ 'wikiId' => 'acmewiki', 'rev_page_id' => 17 ] );
		$this->filterDeprecated( '/Deprecated cross-wiki access.*/' );
		$this->assertEquals( 17, $revision->getPageId() );
	}

	public function testGetParentIdSuccessful() {
		$revision = $this->newRevision( [ 'wikiId' => 'acmewiki', 'rev_parent_id' => 1 ] );
		$this->assertEquals( 1, $revision->getParentId( 'acmewiki' ) );
	}

	public function testGetParentIdTriggerDeprecatedWarning() {
		$this->expectDeprecationAndContinue( '/Deprecated cross-wiki access/' );
		$revision = $this->newRevision( [ 'wikiId' => 'acmewiki', 'rev_parent_id' => 1 ] );
		$revision->getParentId();
	}

	public function testGetParentIdDeprecated() {
		$revision = $this->newRevision( [ 'wikiId' => 'acmewiki', 'rev_parent_id' => 1 ] );
		$this->filterDeprecated( '/Deprecated cross-wiki access.*/' );
		$this->assertEquals( 1, $revision->getParentId() );
	}

	abstract protected static function expectedDefaultFieldVisibility( int $field ): bool;

	private static function provideAudienceCheckData( int $field ): iterable {
		yield 'field accessible for oversighter (ALL)' => [
			RevisionRecord::SUPPRESSED_ALL,
			[ 'deletedtext', 'deletedhistory', 'viewsuppressed', 'suppressrevision' ],
			true,
			false
		];

		yield 'field accessible for oversighter' => [
			RevisionRecord::DELETED_RESTRICTED | $field,
			[ 'deletedtext', 'deletedhistory', 'viewsuppressed', 'suppressrevision' ],
			true,
			false
		];

		yield 'field not accessible for sysops (ALL)' => [
			RevisionRecord::SUPPRESSED_ALL,
			[ 'deletedtext', 'deletedhistory' ],
			false,
			false
		];

		yield 'field not accessible for sysops' => [
			RevisionRecord::DELETED_RESTRICTED | $field,
			[ 'deletedtext', 'deletedhistory' ],
			false,
			false
		];

		yield 'field accessible for sysops' => [
			$field,
			[ 'deletedtext', 'deletedhistory' ],
			true,
			false
		];

		yield 'field suppressed for logged in users' => [
			$field,
			[],
			false,
			false
		];

		yield 'unrelated field suppressed' => [
			$field === RevisionRecord::DELETED_COMMENT
				? RevisionRecord::DELETED_USER
				: RevisionRecord::DELETED_COMMENT,
			[],
			self::expectedDefaultFieldVisibility( $field ),
			self::expectedDefaultFieldVisibility( $field )
		];

		yield 'nothing suppressed' => [
			0,
			[],
			self::expectedDefaultFieldVisibility( $field ),
			self::expectedDefaultFieldVisibility( $field )
		];
	}

	public function testSerialization_fails() {
		$this->expectException( LogicException::class );
		$rev = $this->newRevision();
		serialize( $rev );
	}

	public static function provideGetComment_audience() {
		return self::provideAudienceCheckData( RevisionRecord::DELETED_COMMENT );
	}

	/**
	 * @dataProvider provideGetComment_audience
	 */
	public function testGetComment_audience( $visibility, $permissions, $userCan, $publicCan ) {
		$performer = $this->mockRegisteredAuthorityWithPermissions( $permissions );
		$rev = $this->newRevision( [ 'rev_deleted' => $visibility ] );

		$this->assertNotNull( $rev->getComment( RevisionRecord::RAW ), 'raw can' );

		$this->assertSame(
			$publicCan,
			$rev->getComment( RevisionRecord::FOR_PUBLIC ) !== null,
			'public can'
		);
		$this->assertSame(
			$userCan,
			$rev->getComment( RevisionRecord::FOR_THIS_USER, $performer ) !== null,
			'user can'
		);
	}

	public static function provideGetUser_audience() {
		return self::provideAudienceCheckData( RevisionRecord::DELETED_USER );
	}

	/**
	 * @dataProvider provideGetUser_audience
	 */
	public function testGetUser_audience( $visibility, $permissions, $userCan, $publicCan ) {
		$performer = $this->mockRegisteredAuthorityWithPermissions( $permissions );
		$rev = $this->newRevision( [ 'rev_deleted' => $visibility ] );

		$this->assertNotNull( $rev->getUser( RevisionRecord::RAW ), 'raw can' );

		$this->assertSame(
			$publicCan,
			$rev->getUser( RevisionRecord::FOR_PUBLIC ) !== null,
			'public can'
		);
		$this->assertSame(
			$userCan,
			$rev->getUser( RevisionRecord::FOR_THIS_USER, $performer ) !== null,
			'user can'
		);
	}

	public static function provideGetSlot_audience() {
		return self::provideAudienceCheckData( RevisionRecord::DELETED_TEXT );
	}

	/**
	 * @dataProvider provideGetSlot_audience
	 */
	public function testGetSlot_audience( $visibility, $permissions, $userCan, $publicCan ) {
		$performer = $this->mockRegisteredAuthorityWithPermissions( $permissions );
		$rev = $this->newRevision( [ 'rev_deleted' => $visibility ] );

		// NOTE: slot meta-data is never suppressed, just the content is!
		$this->assertTrue( $rev->hasSlot( SlotRecord::MAIN ), 'hasSlot is never suppressed' );
		$this->assertNotNull( $rev->getSlot( SlotRecord::MAIN, RevisionRecord::RAW ), 'raw meta' );
		$this->assertNotNull( $rev->getSlot( SlotRecord::MAIN, RevisionRecord::FOR_PUBLIC ),
			'public meta' );

		$this->assertNotNull(
			$rev->getSlot( SlotRecord::MAIN, RevisionRecord::FOR_THIS_USER, $performer ),
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
			$rev->getSlot( SlotRecord::MAIN, RevisionRecord::FOR_THIS_USER, $performer )->getContent();
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
	public function testGetContent_audience( $visibility, $permissions, $userCan, $publicCan ) {
		$performer = $this->mockRegisteredAuthorityWithPermissions( $permissions );
		$rev = $this->newRevision( [ 'rev_deleted' => $visibility ] );

		$this->assertNotNull( $rev->getContent( SlotRecord::MAIN, RevisionRecord::RAW ), 'raw can' );
		$this->assertNotNull( $rev->getMainContentRaw(), 'raw can' );

		$this->assertSame(
			$publicCan,
			$rev->getContent( SlotRecord::MAIN, RevisionRecord::FOR_PUBLIC ) !== null,
			'public can'
		);
		$this->assertSame(
			$userCan,
			$rev->getContent( SlotRecord::MAIN, RevisionRecord::FOR_THIS_USER, $performer ) !== null,
			'user can'
		);
	}

	/**
	 * @dataProvider provideGetSlot_audience
	 */
	public function testGetContentOrThrow_audience(
		int $visibility,
		array $permissions,
		bool $userCan,
		bool $publicCan
	) {
		$rev = $this->newRevision( [ 'rev_deleted' => $visibility ] );

		$content = $rev->getContentOrThrow( SlotRecord::MAIN, RevisionRecord::RAW );
		$this->assertInstanceOf( Content::class, $content );

		if ( !$publicCan ) {
			$this->expectException( SuppressedDataException::class );
		}
		$content = $rev->getContentOrThrow( SlotRecord::MAIN );
		$this->assertInstanceOf( Content::class, $content );
	}

	/**
	 * @dataProvider provideGetSlot_audience
	 */
	public function testGetContentOrThrow_forThisUser(
		int $visibility,
		array $permissions,
		bool $userCan,
		bool $publicCan
	) {
		$performer = $this->mockRegisteredAuthorityWithPermissions( $permissions );
		$rev = $this->newRevision( [ 'rev_deleted' => $visibility ] );

		if ( !$userCan ) {
			$this->expectException( SuppressedDataException::class );
		}
		$content = $rev->getContentOrThrow( SlotRecord::MAIN,
			RevisionRecord::FOR_THIS_USER, $performer );
		$this->assertInstanceOf( Content::class, $content );
	}

	public function testGetSlot() {
		$rev = $this->newRevision();

		$slot = $rev->getSlot( SlotRecord::MAIN );
		$this->assertNotNull( $slot, 'getSlot()' );
		$this->assertSame( SlotRecord::MAIN, $slot->getRole(), 'getRole()' );
	}

	public function testHasSlot() {
		$rev = $this->newRevision();

		$this->assertTrue( $rev->hasSlot( SlotRecord::MAIN ) );
		$this->assertFalse( $rev->hasSlot( 'xyz' ) );
	}

	public function testGetContent() {
		$rev = $this->newRevision();

		$content = $rev->getContent( SlotRecord::MAIN, RevisionRecord::RAW );
		$this->assertNotNull( $content, 'getContent()' );
		$this->assertSame(
			DummyContentForTesting::MODEL_ID,
			$content->getModel(),
			'getModel()'
		);

		$this->assertTrue( $content->equals( $rev->getMainContentRaw() ) );
		$this->assertSame( DummyContentForTesting::MODEL_ID, $rev->getMainContentModel() );
	}

	public static function provideUserCanBitfield() {
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
			[ 'deletedtext', 'deletedhistory' ],
			null,
			true,
		];
		yield [
			RevisionRecord::DELETED_COMMENT,
			RevisionRecord::DELETED_COMMENT,
			[ 'deletedtext', 'deletedhistory' ],
			null,
			true,
		];
		yield [
			RevisionRecord::DELETED_USER,
			RevisionRecord::DELETED_USER,
			[ 'deletedtext', 'deletedhistory' ],
			null,
			true,
		];
		// Bitfields match, user (admin) does not have permissions
		yield [
			RevisionRecord::DELETED_RESTRICTED,
			RevisionRecord::DELETED_RESTRICTED,
			[ 'deletedtext', 'deletedhistory' ],
			null,
			false,
		];
		// Bitfields match, user (oversight) does have permissions
		yield [
			RevisionRecord::DELETED_RESTRICTED,
			RevisionRecord::DELETED_RESTRICTED,
			[ 'deletedtext', 'deletedhistory', 'viewsuppressed', 'suppressrevision' ],
			null,
			true,
		];
		// Check permissions using the title
		yield [
			RevisionRecord::DELETED_TEXT,
			RevisionRecord::DELETED_TEXT,
			[ 'deletedtext', 'deletedhistory' ],
			PageIdentityValue::localIdentity( 0, NS_MAIN, 'ProvideUserCanBitfield' ),
			true,
		];
		yield [
			RevisionRecord::DELETED_TEXT,
			RevisionRecord::DELETED_TEXT,
			[],
			PageIdentityValue::localIdentity( 0, NS_MAIN, 'ProvideUserCanBitfield' ),
			false,
		];
	}

	/**
	 * @dataProvider provideUserCanBitfield
	 */
	public function testUserCanBitfield( $bitField, $field, $permissions, ?PageIdentity $title, $expected ) {
		$performer = $this->mockRegisteredAuthorityWithPermissions( $permissions );

		$this->assertSame(
			$expected,
			RevisionRecord::userCanBitfield( $bitField, $field, $performer, $title )
		);
	}

	public static function provideHasSameContent() {
		// Create some slots with content
		$mainA = SlotRecord::newUnsaved( SlotRecord::MAIN, new DummyContentForTesting( 'A' ) );
		$mainB = SlotRecord::newUnsaved( SlotRecord::MAIN, new DummyContentForTesting( 'B' ) );
		$auxA = SlotRecord::newUnsaved( 'aux', new DummyContentForTesting( 'A' ) );
		$auxB = SlotRecord::newUnsaved( 'aux', new DummyContentForTesting( 'A' ) );

		return [
			'same record object' => [
				true,
				self::makeHasSameContentTestRecord( [ $mainA ], 12 ),
				self::makeHasSameContentTestRecord( [ $mainA ], 12 ),
			],
			'same record content, different object' => [
				true,
				self::makeHasSameContentTestRecord( [ $mainA ], 12 ),
				self::makeHasSameContentTestRecord( [ $mainA ], 13 )
			],
			'same record content, aux slot, different object' => [
				true,
				self::makeHasSameContentTestRecord( [ $auxA ], 12 ),
				self::makeHasSameContentTestRecord( [ $auxB ], 13 ),
			],
			'different content' => [
				false,
				self::makeHasSameContentTestRecord( [ $mainA ], 12 ),
				self::makeHasSameContentTestRecord( [ $mainB ], 13 ),
			],
			'different content and number of slots' => [
				false,
				self::makeHasSameContentTestRecord( [ $mainA ], 12 ),
				self::makeHasSameContentTestRecord( [ $mainA, $mainB ], 13 ),
			],
		];
	}

	/**
	 * @param SlotRecord[] $slots
	 * @param int $revId
	 * @return RevisionStoreRecord
	 */
	private static function makeHasSameContentTestRecord( array $slots, $revId ) {
		$slots = new RevisionSlots( $slots );

		return new RevisionStoreRecord(
			PageIdentityValue::localIdentity( 19, NS_MAIN, 'ProvideHasSameContent' ),
			new UserIdentityValue( 11, __METHOD__ ),
			CommentStoreComment::newUnsavedComment( __METHOD__ ),
			(object)[
				'rev_id' => strval( $revId ),
				'rev_page' => strval( 19 ),
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
	 */
	public function testHasSameContent(
		$expected,
		$record1,
		$record2
	) {
		$this->assertSame(
			$expected,
			$record1->hasSameContent( $record2 )
		);
	}

	public static function provideIsDeleted() {
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
