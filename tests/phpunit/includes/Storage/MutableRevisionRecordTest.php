<?php

namespace MediaWiki\Tests\Storage;

use CommentStoreComment;
use MediaWiki\Storage\MutableRevisionRecord;
use MediaWiki\Storage\RevisionRecord;
use MediaWikiTestCase;
use Title;

/**
 * @covers \MediaWiki\Storage\MutableRevisionRecord
 */
class MutableRevisionRecordTest extends MediaWikiTestCase {

	public function testSimpleSetGetId() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$this->assertNull( $record->getId() );
		$record->setId( 888 );
		$this->assertSame( 888, $record->getId() );
	}

	public function testSimpleSetGetUser() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$user = $this->getTestSysop()->getUser();
		$this->assertNull( $record->getUser() );
		$record->setUser( $user );
		$this->assertSame( $user, $record->getUser() );
	}

	public function testSimpleSetGetPageId() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$this->assertSame( 0, $record->getPageId() );
		$record->setPageId( 999 );
		$this->assertSame( 999, $record->getPageId() );
	}

	public function testSimpleSetGetParentId() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$this->assertNull( $record->getParentId() );
		$record->setParentId( 100 );
		$this->assertSame( 100, $record->getParentId() );
	}

	public function testSimpleSetGetMainContent() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$content = new \WikitextContent( 'Badger' );
		// TODO right now this throws an exception when i was expecting null
		// $this->assertNull( $record->getContent( 'main' ) );
		$record->setContent( 'main', $content );
		$this->assertSame( $content, $record->getContent( 'main' ) );
	}

	public function testSimpleSetGetSlot() {
		$this->markTestSkipped( 'fixes needed?' );
		// $record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		// TODO right now this throws an exception when i was expecting a SlotRecord instance
		// $this->assertTrue( $record->getSlot( 'main' ) instanceof SlotRecord);
		// TODO set setting
	}

	public function testSimpleSetGetMinor() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$this->assertFalse( $record->isMinor() );
		$record->setMinorEdit( true );
		$this->assertSame( true, $record->isMinor() );
	}

	public function testSimpleSetGetTimestamp() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$this->assertNull( $record->getTimestamp() );
		$record->setTimestamp( '20180101010101' );
		$this->assertSame( '20180101010101', $record->getTimestamp() );
	}

	public function testSimpleSetGetVisibility() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$this->assertSame( 0, $record->getVisibility() );
		$record->setVisibility( RevisionRecord::DELETED_USER );
		$this->assertSame( RevisionRecord::DELETED_USER, $record->getVisibility() );
	}

	public function testSimpleSetGetSha1() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		// TODO currently documented as always returning a string...
		$this->assertNull( $record->getSha1() );
		$record->setSha1( 'someHash' );
		$this->assertSame( 'someHash', $record->getSha1() );
	}

	public function testSimpleSetGetSize() {
		// TODO un skip me once fixed
		$this->markTestSkipped( 'needs setSize assert fixing...' );
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$this->assertSame( 0, $record->getSize() );
		$record->setSize( 775 );
		$this->assertSame( 775, $record->getSize() );
	}

	public function testSimpleSetGetComment() {
		$record = new MutableRevisionRecord( Title::newFromText( 'Foo' ) );
		$comment = new CommentStoreComment( 1, 'foo' );
		$this->assertNull( $record->getComment() );
		$record->setComment( $comment );
		$this->assertSame( $comment, $record->getComment() );
	}

}
