<?php

namespace MediaWiki\Tests\Revision;

use CommentStoreComment;
use InvalidArgumentException;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\MutableRevisionSlots;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\RevisionSlotsUpdate;
use MediaWiki\User\UserIdentityValue;
use MediaWikiTestCase;
use TextContent;
use Title;
use User;
use WikitextContent;

/**
 * @covers \MediaWiki\Revision\MutableRevisionRecord
 * @covers \MediaWiki\Revision\RevisionRecord
 */
class MutableRevisionRecordTest extends MediaWikiTestCase {

	use RevisionRecordTests;

	/**
	 * @param array $rowOverrides
	 *
	 * @return MutableRevisionRecord
	 */
	protected function newRevision( array $rowOverrides = [] ) {
		$title = Title::newFromText( 'Dummy' );
		$title->resetArticleID( 17 );

		$user = new UserIdentityValue( 11, 'Tester', 0 );
		$comment = CommentStoreComment::newUnsavedComment( 'Hello World' );

		$record = new MutableRevisionRecord( $title );

		if ( isset( $rowOverrides['rev_deleted'] ) ) {
			$record->setVisibility( $rowOverrides['rev_deleted'] );
		}

		if ( isset( $rowOverrides['rev_id'] ) ) {
			$record->setId( $rowOverrides['rev_id'] );
		}

		if ( isset( $rowOverrides['rev_page'] ) ) {
			$record->setPageId( $rowOverrides['rev_page'] );
		}

		$record->setContent( SlotRecord::MAIN, new TextContent( 'Lorem Ipsum' ) );
		$record->setComment( $comment );
		$record->setUser( $user );
		$record->setTimestamp( '20101010000000' );

		return $record;
	}

	public function provideConstructor() {
		$title = Title::newFromText( 'Dummy' );
		$title->resetArticleID( 17 );

		yield [
			$title,
			'acmewiki'
		];
	}

	/**
	 * @dataProvider provideConstructor
	 *
	 * @param Title $title
	 * @param bool $wikiId
	 */
	public function testConstructorAndGetters(
		Title $title,
		$wikiId = false
	) {
		$rec = new MutableRevisionRecord( $title, $wikiId );

		$this->assertSame( $title, $rec->getPageAsLinkTarget(), 'getPageAsLinkTarget' );
		$this->assertSame( $wikiId, $rec->getWikiId(), 'getWikiId' );
	}

	public function provideConstructorFailure() {
		$title = Title::newFromText( 'Dummy' );
		$title->resetArticleID( 17 );

		yield 'not a wiki id' => [
			$title,
			null
		];
	}

	/**
	 * @dataProvider provideConstructorFailure
	 *
	 * @param Title $title
	 * @param bool $wikiId
	 */
	public function testConstructorFailure(
		Title $title,
		$wikiId = false
	) {
		$this->setExpectedException( InvalidArgumentException::class );
		new MutableRevisionRecord( $title, $wikiId );
	}

	public function testSetGetId() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$this->assertNull( $record->getId() );
		$record->setId( 888 );
		$this->assertSame( 888, $record->getId() );
	}

	public function testSetGetUser() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$user = $this->getTestSysop()->getUser();
		$this->assertNull( $record->getUser() );
		$record->setUser( $user );
		$this->assertSame( $user, $record->getUser() );
	}

	public function testSetGetPageId() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$this->assertSame( 0, $record->getPageId() );
		$record->setPageId( 999 );
		$this->assertSame( 999, $record->getPageId() );
	}

	public function testSetGetParentId() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$this->assertNull( $record->getParentId() );
		$record->setParentId( 100 );
		$this->assertSame( 100, $record->getParentId() );
	}

	public function testGetMainContentWhenEmpty() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$this->setExpectedException( RevisionAccessException::class );
		$this->assertNull( $record->getContent( SlotRecord::MAIN ) );
	}

	public function testSetGetMainContent() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$content = new WikitextContent( 'Badger' );
		$record->setContent( SlotRecord::MAIN, $content );
		$this->assertSame( $content, $record->getContent( SlotRecord::MAIN ) );
	}

	public function testGetSlotWhenEmpty() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$this->assertFalse( $record->hasSlot( SlotRecord::MAIN ) );

		$this->setExpectedException( RevisionAccessException::class );
		$record->getSlot( SlotRecord::MAIN );
	}

	public function testSetGetSlot() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$slot = SlotRecord::newUnsaved(
			SlotRecord::MAIN,
			new WikitextContent( 'x' )
		);
		$record->setSlot( $slot );
		$this->assertTrue( $record->hasSlot( SlotRecord::MAIN ) );
		$this->assertSame( $slot, $record->getSlot( SlotRecord::MAIN ) );
	}

	public function testSetGetMinor() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$this->assertFalse( $record->isMinor() );
		$record->setMinorEdit( true );
		$this->assertSame( true, $record->isMinor() );
	}

	public function testSetGetTimestamp() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$this->assertNull( $record->getTimestamp() );
		$record->setTimestamp( '20180101010101' );
		$this->assertSame( '20180101010101', $record->getTimestamp() );
	}

	public function testSetGetVisibility() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$this->assertSame( 0, $record->getVisibility() );
		$record->setVisibility( RevisionRecord::DELETED_USER );
		$this->assertSame( RevisionRecord::DELETED_USER, $record->getVisibility() );
	}

	public function testSetGetSha1() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$this->assertSame( 'phoiac9h4m842xq45sp7s6u21eteeq1', $record->getSha1() );
		$record->setSha1( 'someHash' );
		$this->assertSame( 'someHash', $record->getSha1() );
	}

	public function testGetSlots() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$this->assertInstanceOf( MutableRevisionSlots::class, $record->getSlots() );
	}

	public function testSetGetSize() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$this->assertSame( 0, $record->getSize() );
		$record->setSize( 775 );
		$this->assertSame( 775, $record->getSize() );
	}

	public function testSetGetComment() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$comment = new CommentStoreComment( 1, 'foo' );
		$this->assertNull( $record->getComment() );
		$record->setComment( $comment );
		$this->assertSame( $comment, $record->getComment() );
	}

	public function testSimpleGetOriginalAndInheritedSlots() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$mainSlot = new SlotRecord(
			(object)[
				'slot_id' => 1,
				'slot_revision_id' => null, // unsaved
				'slot_content_id' => 1,
				'content_address' => null, // touched
				'model_name' => 'x',
				'role_name' => 'main',
				'slot_origin' => null // touched
			],
			new WikitextContent( 'main' )
		);
		$auxSlot = new SlotRecord(
			(object)[
				'slot_id' => 2,
				'slot_revision_id' => null, // unsaved
				'slot_content_id' => 1,
				'content_address' => 'foo', // inherited
				'model_name' => 'x',
				'role_name' => 'aux',
				'slot_origin' => 1 // inherited
			],
			new WikitextContent( 'aux' )
		);

		$record->setSlot( $mainSlot );
		$record->setSlot( $auxSlot );

		$this->assertSame( [ 'main' ], $record->getOriginalSlots()->getSlotRoles() );
		$this->assertSame( $mainSlot, $record->getOriginalSlots()->getSlot( SlotRecord::MAIN ) );

		$this->assertSame( [ 'aux' ], $record->getInheritedSlots()->getSlotRoles() );
		$this->assertSame( $auxSlot, $record->getInheritedSlots()->getSlot( 'aux' ) );
	}

	public function testSimpleremoveSlot() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );

		$a = new WikitextContent( 'a' );
		$b = new WikitextContent( 'b' );

		$record->inheritSlot( SlotRecord::newSaved( 7, 3, 'a', SlotRecord::newUnsaved( 'a', $a ) ) );
		$record->inheritSlot( SlotRecord::newSaved( 7, 4, 'b', SlotRecord::newUnsaved( 'b', $b ) ) );

		$record->removeSlot( 'b' );

		$this->assertTrue( $record->hasSlot( 'a' ) );
		$this->assertFalse( $record->hasSlot( 'b' ) );
	}

	public function testApplyUpdate() {
		$update = new RevisionSlotsUpdate();

		$a = new WikitextContent( 'a' );
		$b = new WikitextContent( 'b' );
		$c = new WikitextContent( 'c' );
		$x = new WikitextContent( 'x' );

		$update->modifyContent( 'b', $x );
		$update->modifyContent( 'c', $x );
		$update->removeSlot( 'c' );
		$update->removeSlot( 'd' );

		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$record->inheritSlot( SlotRecord::newSaved( 7, 3, 'a', SlotRecord::newUnsaved( 'a', $a ) ) );
		$record->inheritSlot( SlotRecord::newSaved( 7, 4, 'b', SlotRecord::newUnsaved( 'b', $b ) ) );
		$record->inheritSlot( SlotRecord::newSaved( 7, 5, 'c', SlotRecord::newUnsaved( 'c', $c ) ) );

		$record->applyUpdate( $update );

		$this->assertEquals( [ 'b' ], array_keys( $record->getOriginalSlots()->getSlots() ) );
		$this->assertEquals( $a, $record->getSlot( 'a' )->getContent() );
		$this->assertEquals( $x, $record->getSlot( 'b' )->getContent() );
		$this->assertFalse( $record->hasSlot( 'c' ) );
	}

	public function provideNotReadyForInsertion() {
		/** @var Title $title */
		$title = $this->getMock( Title::class );

		/** @var User $user */
		$user = $this->getMock( User::class );

		/** @var CommentStoreComment $comment */
		$comment = $this->getMockBuilder( CommentStoreComment::class )
			->disableOriginalConstructor()
			->getMock();

		$content = new TextContent( 'Test' );

		$rev = new MutableRevisionRecord( $title );
		yield 'empty' => [ $rev ];

		$rev = new MutableRevisionRecord( $title );
		$rev->setContent( SlotRecord::MAIN, $content );
		$rev->setUser( $user );
		$rev->setComment( $comment );
		yield 'no timestamp' => [ $rev ];

		$rev = new MutableRevisionRecord( $title );
		$rev->setUser( $user );
		$rev->setComment( $comment );
		$rev->setTimestamp( '20101010000000' );
		yield 'no content' => [ $rev ];

		$rev = new MutableRevisionRecord( $title );
		$rev->setContent( SlotRecord::MAIN, $content );
		$rev->setComment( $comment );
		$rev->setTimestamp( '20101010000000' );
		yield 'no user' => [ $rev ];

		$rev = new MutableRevisionRecord( $title );
		$rev->setUser( $user );
		$rev->setContent( SlotRecord::MAIN, $content );
		$rev->setTimestamp( '20101010000000' );
		yield 'no comment' => [ $rev ];
	}

	/**
	 * @dataProvider provideNotReadyForInsertion
	 */
	public function testNotReadyForInsertion( $rev ) {
		$this->assertFalse( $rev->isReadyForInsertion() );
	}
}
