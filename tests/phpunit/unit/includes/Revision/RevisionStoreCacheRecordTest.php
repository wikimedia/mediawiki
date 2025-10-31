<?php

namespace MediaWiki\Tests\Unit\Revision;

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionSlots;
use MediaWiki\Revision\RevisionStoreCacheRecord;
use MediaWiki\Revision\RevisionStoreRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Tests\Mocks\Content\DummyContentForTesting;
use MediaWiki\User\UserIdentityValue;

/**
 * @covers \MediaWiki\Revision\RevisionStoreCacheRecord
 * @covers \MediaWiki\Revision\RevisionStoreRecord
 * @covers \MediaWiki\Revision\RevisionRecord
 */
class RevisionStoreCacheRecordTest extends RevisionStoreRecordTest {

	use RevisionRecordTests;

	/**
	 * @param array $rowOverrides
	 * @param callable|false $callback function to use, or false for a default no-op callback
	 *
	 * @return RevisionStoreRecord
	 */
	protected function newRevision( array $rowOverrides = [], $callback = false ) {
		$wikiId = $rowOverrides['wikiId'] ?? RevisionRecord::LOCAL;

		$title = new PageIdentityValue( 17, NS_MAIN, 'Dummy', $wikiId );

		$user = new UserIdentityValue( 11, 'Tester' );
		$comment = CommentStoreComment::newUnsavedComment( 'Hello World' );

		$main = SlotRecord::newUnsaved( SlotRecord::MAIN, new DummyContentForTesting( 'Lorem Ipsum' ) );
		$aux = SlotRecord::newUnsaved( 'aux', new DummyContentForTesting( 'Frumious Bandersnatch' ) );
		$slots = new RevisionSlots( [ $main, $aux ] );

		$row = [
			'rev_id' => '7',
			'rev_page' => '17',
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
				$this->assertSame( 7, $revId );
				return [
					$row['rev_deleted'],
					new UserIdentityValue( intval( $row['rev_user'] ), 'Bla' )
				];
			};
		}

		return new RevisionStoreCacheRecord(
			$callback,
			$title,
			$user,
			$comment,
			(object)$row,
			$slots,
			$wikiId
		);
	}

	public function testCallback() {
		// Provide a callback that returns non-default values. Asserting the revision returns
		// these values confirms callback execution and behavior. Also confirm the callback
		// is only invoked once, even for multiple getter calls.
		$callbackInvoked = 0;
		$callback = function ( $revId ) use ( &$callbackInvoked ) {
			$this->assertSame( 7, $revId );
			$callbackInvoked++;
			return [
				RevisionRecord::DELETED_TEXT,
				new UserIdentityValue( 12, 'Lalala' )
			];
		};
		$rev = $this->newRevision( [], $callback );

		$this->assertSame( RevisionRecord::DELETED_TEXT, $rev->getVisibility() );
		$this->assertSame( 12, $rev->getUser()->getId() );
		$this->assertSame( 1, $callbackInvoked );
	}

}
