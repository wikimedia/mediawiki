<?php

namespace MediaWiki\Tests\Revision;

use CommentStoreComment;
use InvalidArgumentException;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionSlots;
use MediaWiki\Revision\RevisionArchiveRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use MediaWikiTestCase;
use TextContent;
use Title;

/**
 * @covers \MediaWiki\Revision\RevisionArchiveRecord
 * @covers \MediaWiki\Revision\RevisionRecord
 */
class RevisionArchiveRecordTest extends MediaWikiTestCase {

	use RevisionRecordTests;

	/**
	 * @param array $rowOverrides
	 *
	 * @return RevisionArchiveRecord
	 */
	protected function newRevision( array $rowOverrides = [] ) {
		$title = Title::newFromText( 'Dummy' );
		$title->resetArticleID( 17 );

		$user = new UserIdentityValue( 11, 'Tester', 0 );
		$comment = CommentStoreComment::newUnsavedComment( 'Hello World' );

		$main = SlotRecord::newUnsaved( SlotRecord::MAIN, new TextContent( 'Lorem Ipsum' ) );
		$aux = SlotRecord::newUnsaved( 'aux', new TextContent( 'Frumious Bandersnatch' ) );
		$slots = new RevisionSlots( [ $main, $aux ] );

		$row = [
			'ar_id' => '5',
			'ar_rev_id' => '7',
			'ar_page_id' => strval( $title->getArticleID() ),
			'ar_timestamp' => '20200101000000',
			'ar_deleted' => 0,
			'ar_minor_edit' => 0,
			'ar_parent_id' => '5',
			'ar_len' => $slots->computeSize(),
			'ar_sha1' => $slots->computeSha1(),
		];

		foreach ( $rowOverrides as $field => $value ) {
			$field = preg_replace( '/^rev_/', 'ar_', $field );
			$row[$field] = $value;
		}

		return new RevisionArchiveRecord( $title, $user, $comment, (object)$row, $slots );
	}

	public function provideConstructor() {
		$title = Title::newFromText( 'Dummy' );
		$title->resetArticleID( 17 );

		$user = new UserIdentityValue( 11, 'Tester', 0 );
		$comment = CommentStoreComment::newUnsavedComment( 'Hello World' );

		$main = SlotRecord::newUnsaved( SlotRecord::MAIN, new TextContent( 'Lorem Ipsum' ) );
		$aux = SlotRecord::newUnsaved( 'aux', new TextContent( 'Frumious Bandersnatch' ) );
		$slots = new RevisionSlots( [ $main, $aux ] );

		$protoRow = [
			'ar_id' => '5',
			'ar_rev_id' => '7',
			'ar_page_id' => strval( $title->getArticleID() ),
			'ar_timestamp' => '20200101000000',
			'ar_deleted' => 0,
			'ar_minor_edit' => 0,
			'ar_parent_id' => '5',
			'ar_len' => $slots->computeSize(),
			'ar_sha1' => $slots->computeSha1(),
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
		$row['ar_minor_edit'] = '1';
		$row['ar_deleted'] = strval( RevisionRecord::DELETED_USER );

		yield 'minor deleted' => [
			$title,
			$user,
			$comment,
			(object)$row,
			$slots
		];

		$row = $protoRow;
		unset( $row['ar_parent'] );

		yield 'no parent' => [
			$title,
			$user,
			$comment,
			(object)$row,
			$slots
		];

		$row = $protoRow;
		$row['ar_len'] = null;
		$row['ar_sha1'] = '';

		yield 'ar_len is null, ar_sha1 is ""' => [
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
		$rec = new RevisionArchiveRecord( $title, $user, $comment, $row, $slots, $wikiId );

		$this->assertSame( $title, $rec->getPageAsLinkTarget(), 'getPageAsLinkTarget' );
		$this->assertSame( $user, $rec->getUser( RevisionRecord::RAW ), 'getUser' );
		$this->assertSame( $comment, $rec->getComment(), 'getComment' );

		$this->assertSame( $slots->getSlotRoles(), $rec->getSlotRoles(), 'getSlotRoles' );
		$this->assertSame( $wikiId, $rec->getWikiId(), 'getWikiId' );

		$this->assertSame( (int)$row->ar_id, $rec->getArchiveId(), 'getArchiveId' );
		$this->assertSame( (int)$row->ar_rev_id, $rec->getId(), 'getId' );
		$this->assertSame( (int)$row->ar_page_id, $rec->getPageId(), 'getId' );
		$this->assertSame( $row->ar_timestamp, $rec->getTimestamp(), 'getTimestamp' );
		$this->assertSame( (int)$row->ar_deleted, $rec->getVisibility(), 'getVisibility' );
		$this->assertSame( (bool)$row->ar_minor_edit, $rec->isMinor(), 'getIsMinor' );

		if ( isset( $row->ar_parent_id ) ) {
			$this->assertSame( (int)$row->ar_parent_id, $rec->getParentId(), 'getParentId' );
		} else {
			$this->assertSame( 0, $rec->getParentId(), 'getParentId' );
		}

		if ( isset( $row->ar_len ) ) {
			$this->assertSame( (int)$row->ar_len, $rec->getSize(), 'getSize' );
		} else {
			$this->assertSame( $slots->computeSize(), $rec->getSize(), 'getSize' );
		}

		if ( !empty( $row->ar_sha1 ) ) {
			$this->assertSame( $row->ar_sha1, $rec->getSha1(), 'getSha1' );
		} else {
			$this->assertSame( $slots->computeSha1(), $rec->getSha1(), 'getSha1' );
		}
	}

	public function provideConstructorFailure() {
		$title = Title::newFromText( 'Dummy' );
		$title->resetArticleID( 17 );

		$user = new UserIdentityValue( 11, 'Tester', 0 );

		$comment = CommentStoreComment::newUnsavedComment( 'Hello World' );

		$main = SlotRecord::newUnsaved( SlotRecord::MAIN, new TextContent( 'Lorem Ipsum' ) );
		$aux = SlotRecord::newUnsaved( 'aux', new TextContent( 'Frumious Bandersnatch' ) );
		$slots = new RevisionSlots( [ $main, $aux ] );

		$protoRow = [
			'ar_id' => '5',
			'ar_rev_id' => '7',
			'ar_page_id' => strval( $title->getArticleID() ),
			'ar_timestamp' => '20200101000000',
			'ar_deleted' => 0,
			'ar_minor_edit' => 0,
			'ar_parent_id' => '5',
			'ar_len' => $slots->computeSize(),
			'ar_sha1' => $slots->computeSha1(),
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
		$row['ar_timestamp'] = 'kittens';

		yield 'bad timestamp' => [
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

		// NOTE: $title->getArticleID does *not* have to match ar_page_id in all cases!
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
		new RevisionArchiveRecord( $title, $user, $comment, $row, $slots, $wikiId );
	}

}
