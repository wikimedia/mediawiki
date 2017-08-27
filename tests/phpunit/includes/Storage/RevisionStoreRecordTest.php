<?php

namespace MediaWiki\Tests\Storage;

use CommentStoreComment;
use MediaWiki\Storage\RevisionRecord;
use MediaWiki\Storage\RevisionSlots;
use MediaWiki\Storage\RevisionStoreRecord;
use MediaWiki\Storage\SlotRecord;
use MediaWikiTestCase;
use TextContent;
use Title;

/**
 * @covers MediaWiki\Storage\RevisionStoreRecord
 */
class RevisionStoreRecordTest extends MediaWikiTestCase {

	public function provideConstructor() {
		$title = Title::newFromText( 'Dummy' );
		$title->resetArticleID( 17 );

		$comment = CommentStoreComment::newUnsavedComment( 'Hello World' );

		$main = SlotRecord::newUnsaved( 'main', new TextContent( 'Lorem Ipsum' ) );
		$aux = SlotRecord::newUnsaved( 'aux', new TextContent( 'Frumious Bandersnatch' ) );
		$slots = new RevisionSlots( [ $main, $aux ] );

		$protoRow = [
			'rev_id' => '7',
			'rev_page' => strval( $title->getArticleID() ),
			'rev_timestamp' => '20200101000000',
			'rev_user' => 11,
			'rev_user_text' => 'Tester',
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
			$comment,
			(object)$row,
			$slots
		];

		$row = $protoRow;
		$row['user_name'] = 'Testorator';

		yield 'user_name' => [
			$title,
			$comment,
			(object)$row,
			$slots
		];

		$row = $protoRow;
		$row['page_latest'] = $row['rev_id'];

		yield 'latest' => [
			$title,
			$comment,
			(object)$row,
			$slots
		];

		$row = $protoRow;
		unset( $row['rev_parent'] );

		yield 'no parent' => [
			$title,
			$comment,
			(object)$row,
			$slots
		];

		$row = $protoRow;
		unset( $row['rev_len'] );
		unset( $row['rev_sha1'] );

		yield 'no length, no hash' => [
			$title,
			$comment,
			(object)$row,
			$slots
		];
	}

	/**
	 * @dataProvider provideConstructor
	 *
	 * @param Title $title
	 * @param CommentStoreComment $comment
	 * @param object $row
	 * @param RevisionSlots $slots
	 * @param bool $wikiId
	 */
	public function testConstructorAndGetters(
		Title $title,
		CommentStoreComment $comment,
		$row,
		RevisionSlots $slots,
		$wikiId = false
	) {
		$rec = new RevisionStoreRecord( $title, $comment, $row, $slots, $wikiId );

		$this->assertSame( $title, $rec->getTitle(), 'getTitle' );
		$this->assertSame( $comment, $rec->getComment(), 'getComment' );

		$this->assertSame( $slots->getSlotRoles(), $rec->getSlotRoles(), 'getSlotRoles' );
		$this->assertSame( $wikiId, $rec->getWikiId(), 'getWikiId' );

		$this->assertSame( (int)$row->rev_id, $rec->getId(), 'getId' );
		$this->assertSame( (int)$row->rev_page, $rec->getPageId(), 'getId' );
		$this->assertSame( $row->rev_timestamp, $rec->getTimestamp(), 'getTimestamp' );
		$this->assertSame( (int)$row->rev_user, $rec->getUserId( RevisionRecord::RAW ), 'getUserId' );
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

		if ( isset( $row->user_name ) ) {
			$this->assertSame(
				$row->user_name,
				$rec->getUserText( RevisionRecord::RAW ),
				'getUserText'
			);
		} else {
			$this->assertSame(
				$row->rev_user_text,
				$rec->getUserText( RevisionRecord::RAW ),
				'getUserText'
			);
		}
	}

	public function testConstructorFailure() {
	}

	public function testGetComment_audience() {
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

}
