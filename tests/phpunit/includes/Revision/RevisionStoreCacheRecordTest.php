<?php

namespace MediaWiki\Tests\Revision;

use CommentStoreComment;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionSlots;
use MediaWiki\Revision\RevisionStoreCacheRecord;
use MediaWiki\Revision\RevisionStoreRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\User\UserIdentityValue;
use TextContent;
use Title;

/**
 * @covers \MediaWiki\Revision\RevisionStoreCacheRecord
 * @covers \MediaWiki\Revision\RevisionStoreRecord
 * @covers \MediaWiki\Revision\RevisionRecord
 */
class RevisionStoreCacheRecordTest extends RevisionStoreRecordTest {

	/**
	 * @param array $rowOverrides
	 * @param bool|callable callback function to use, or false for a default no-op callback
	 *
	 * @return RevisionStoreRecord
	 */
	protected function newRevision( array $rowOverrides = [], $callback = false ) {
		$title = Title::newFromText( 'Dummy' );
		$title->resetArticleID( 17 );

		$user = new UserIdentityValue( 11, 'Tester', 0 );
		$comment = CommentStoreComment::newUnsavedComment( 'Hello World' );

		$main = SlotRecord::newUnsaved( SlotRecord::MAIN, new TextContent( 'Lorem Ipsum' ) );
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
			'rev_user' => '11',
			'page_latest' => '18',
		];

		$row = array_merge( $row, $rowOverrides );

		if ( !$callback ) {
			$callback = function ( $revId ) use ( $row ) {
				return (object)$row;
			};
		}

		return new RevisionStoreCacheRecord(
			$callback,
			$title,
			$user,
			$comment,
			(object)$row,
			$slots
		);
	}

	public function testCallback() {
		// Provide a callback that returns non-default values. Asserting the revision returns
		// these values confirms callback execution and behavior. Also confirm the callback
		// is only invoked once, even for multiple getter calls.
		$rowOverrides = [
			'rev_deleted' => RevisionRecord::DELETED_TEXT,
			'rev_user' => 12,
		];
		$callbackInvoked = 0;
		$callback = function ( $revId ) use ( &$callbackInvoked, $rowOverrides ) {
			$callbackInvoked++;
			return (object)$rowOverrides;
		};
		$rev = $this->newRevision( [], $callback );

		$this->assertSame( RevisionRecord::DELETED_TEXT, $rev->getVisibility() );
		$this->assertSame( 12, $rev->getUser()->getId() );
		$this->assertSame( 1, $callbackInvoked );
	}

}
