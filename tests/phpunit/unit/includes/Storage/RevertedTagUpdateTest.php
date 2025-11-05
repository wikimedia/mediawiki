<?php

namespace MediaWiki\Tests\Storage;

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Json\FormatJson;
use MediaWiki\MainConfigNames;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Storage\EditResult;
use MediaWiki\Storage\RevertedTagUpdate;
use MediaWikiUnitTestCase;
use MockTitleTrait;
use PHPUnit\Framework\MockObject\Stub\ReturnCallback;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use TestLogger;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * @covers \MediaWiki\Storage\RevertedTagUpdate
 * @see RevertedTagUpdateIntegrationTest for an end-to-end test with the database
 */
class RevertedTagUpdateTest extends MediaWikiUnitTestCase {
	use MockTitleTrait;

	/**
	 * Convenience function for creating a RevertedTagUpdate object
	 *
	 * @param ChangeTagsStore $changeTagsStore
	 * @param RevisionStore $revisionStore
	 * @param LoggerInterface $logger
	 * @param string[] $softwareTags
	 * @param int $revertedTagMaxDepth
	 * @param int $revertId
	 * @param EditResult $editResult
	 *
	 * @return RevertedTagUpdate
	 */
	private function newRevertedTagUpdate(
		$changeTagsStore,
		RevisionStore $revisionStore,
		LoggerInterface $logger,
		array $softwareTags,
		int $revertedTagMaxDepth,
		int $revertId,
		EditResult $editResult
	): RevertedTagUpdate {
		$dbProvider = $this->createMock( IConnectionProvider::class );
		$changeTagsStore->method( 'getSoftwareTags' )
			->willReturn( $softwareTags );

		$serviceOptions = new ServiceOptions(
			RevertedTagUpdate::CONSTRUCTOR_OPTIONS,
			[ MainConfigNames::RevertedTagMaxDepth => $revertedTagMaxDepth ]
		);

		return new RevertedTagUpdate(
			$revisionStore,
			$logger,
			$changeTagsStore,
			$dbProvider,
			$serviceOptions,
			$revertId,
			$editResult
		);
	}

	/**
	 * Returns a dummy RevisionRecord.
	 *
	 * @param int $pageId
	 * @param int $revisionId
	 * @param string $timestamp
	 * @param string|null $sha1 SHA1 of the revision. When null, will generate
	 *   a distinct SHA1 for the revision. Does not have to be a proper SHA1.
	 *
	 * @return MutableRevisionRecord
	 */
	private function getDummyRevision(
		int $pageId = 100,
		int $revisionId = 123,
		string $timestamp = '20100101202020',
		?string $sha1 = null
	) {
		$revisionRecord = new TestMutableRevisionRecord(
			$this->makeMockTitle( __METHOD__, [ 'id' => $pageId ] ),
			TestMutableRevisionRecord::LOCAL,
			$sha1 ?? strval( $revisionId )
		);
		$revisionRecord->setId( $revisionId );
		$revisionRecord->setTimestamp( $timestamp );
		$revisionRecord->setPageId( $pageId );

		return $revisionRecord;
	}

	/**
	 * Returns a closure that determines the return value of ChangeTagsStore::addTags()
	 *
	 * @param int $revertedRevisionId
	 * @param int $newRevisionId
	 * @param EditResult $editResult
	 * @return callable
	 */
	private function getChangeTagsReturnCallback(
		int $revertedRevisionId,
		int $newRevisionId,
		EditResult $editResult
	): callable {
		return function (
			$tags, $rc_id, $rev_id,
			$log_id, $params
		) use ( $newRevisionId, $revertedRevisionId, $editResult ) {
			$this->assertSame(
				[ ChangeTags::TAG_REVERTED ],
				$tags,
				'RevertedTagUpdate::markAsReverted() $revisionId'
			);
			$this->assertSame(
				$revertedRevisionId,
				$rev_id,
				'RevertedTagUpdate::markAsReverted() $revisionId'
			);
			$this->assertSame(
				FormatJson::encode( array_merge(
					[ 'revertId' => $newRevisionId ],
					$editResult->jsonSerialize()
				) ),
				$params
			);
		};
	}

	public static function provideRevertedTagUpdateDisabled() {
		yield 'mw-reverted tag is disabled' => [ [], 15 ];
		yield '$wgRevertedTagMaxDepth is 0' => [ [ 'mw-reverted' ], 0 ];
	}

	/**
	 * Test cases where the update is disabled through configuration.
	 *
	 * @dataProvider provideRevertedTagUpdateDisabled
	 */
	public function testRevertedTagUpdateDisabled(
		array $softwareChangeTags,
		int $revertedTagMaxDepth
	) {
		$update = $this->newRevertedTagUpdate(
			$this->createMock( ChangeTagsStore::class ),
			$this->createNoOpMock( RevisionStore::class ),
			new TestLogger(),
			$softwareChangeTags,
			$revertedTagMaxDepth,
			123,
			$this->createNoOpMock( EditResult::class )
		);
		$update->doUpdate();
	}

	public static function provideInvalidEditResults() {
		yield 'edit is not a revert' => [
			new EditResult(
				false,
				false,
				null,
				null,
				null,
				false,
				false,
				[]
			)
		];

		yield 'newest reverted revision ID is null' => [
			new EditResult(
				false,
				9,
				EditResult::REVERT_ROLLBACK,
				15,
				null,
				true,
				false,
				[ 'mw-rollback' ]
			)
		];
	}

	/**
	 * @dataProvider provideInvalidEditResults
	 */
	public function testInvalidEditResult( EditResult $editResult ) {
		$logger = new TestLogger( true );
		$update = $this->newRevertedTagUpdate(
			$this->createMock( ChangeTagsStore::class ),
			$this->createNoOpMock( RevisionStore::class ),
			$logger,
			[ 'mw-reverted' ],
			15,
			123,
			$editResult
		);
		$update->doUpdate();

		$this->assertSame( [
			[
				LogLevel::ERROR,
				'Invalid EditResult specified.'
			],
		], $logger->getBuffer() );
	}

	/**
	 * Test the case where the supposedly reverted revisions are missing from the DB.
	 */
	public function testMissingRevertedRevisions() {
		$revisionStore = $this->createMock( RevisionStore::class );
		$revisionStore->expects( $this->once() )
			->method( 'getRevisionById' )
			->willReturn( null );

		$logger = new TestLogger( true );

		$editResult = new EditResult(
			false,
			122,
			EditResult::REVERT_ROLLBACK,
			123,
			125,
			true,
			false,
			[ 'mw-rollback' ]
		);

		$update = $this->newRevertedTagUpdate(
			$this->createMock( ChangeTagsStore::class ),
			$revisionStore,
			$logger,
			[ 'mw-reverted' ],
			15,
			300,
			$editResult
		);
		$update->doUpdate();

		$this->assertSame( [
			[
				LogLevel::ERROR,
				'Could not find the newest or oldest reverted revision in the database.'
			],
		], $logger->getBuffer() );
	}

	/**
	 * Test the case where the actual revert revision is missing from the DB.
	 */
	public function testMissingRevertRevision() {
		$dummyRevision = $this->createNoOpMock( RevisionRecord::class );
		$revisionStore = $this->createMock( RevisionStore::class );
		$revisionStore->method( 'getRevisionById' )
			->willReturnMap( [
				[ 123, 0, null, $dummyRevision ], // oldest reverted
				[ 125, 0, null, $dummyRevision ], // newest reverted
				[ 300, 0, null, null ], // the revert
			] );

		$logger = new TestLogger( true );

		$editResult = new EditResult(
			false,
			122,
			EditResult::REVERT_ROLLBACK,
			123,
			125,
			true,
			false,
			[ 'mw-rollback' ]
		);

		$update = $this->newRevertedTagUpdate(
			$this->createMock( ChangeTagsStore::class ),
			$revisionStore,
			$logger,
			[ 'mw-reverted' ],
			15,
			300,
			$editResult
		);
		$update->doUpdate();

		$this->assertSame( [
			[
				LogLevel::ERROR,
				'Could not find the revert revision in the database.'
			],
		], $logger->getBuffer() );
	}

	public static function providePageIdMismatch() {
		yield 'mismatch between reverted revisions' => [
			10,
			22,
			10
		];

		yield 'mismatch between reverted revisions and the revert' => [
			10,
			10,
			22
		];
	}

	/**
	 * @dataProvider providePageIdMismatch
	 */
	public function testPageIdMismatch(
		int $oldestRevertedPageId,
		int $newestRevertedPageId,
		int $revertPageId
	) {
		$oldestReverted = $this->getDummyRevision( $oldestRevertedPageId );
		$newestReverted = $this->getDummyRevision( $newestRevertedPageId );
		$revert = $this->getDummyRevision( $revertPageId );

		$revisionStore = $this->createMock( RevisionStore::class );
		$revisionStore->method( 'getRevisionById' )
			->willReturnMap( [
				[ 123, 0, null, $oldestReverted ],
				[ 125, 0, null, $newestReverted ],
				[ 300, 0, null, $revert ],
			] );

		$logger = new TestLogger( true );

		$editResult = new EditResult(
			false,
			122,
			EditResult::REVERT_ROLLBACK,
			123,
			125,
			true,
			false,
			[ 'mw-rollback' ]
		);

		$update = $this->newRevertedTagUpdate(
			$this->createMock( ChangeTagsStore::class ),
			$revisionStore,
			$logger,
			[ 'mw-reverted' ],
			15,
			300,
			$editResult
		);
		$update->doUpdate();

		$this->assertSame( [
			[
				LogLevel::ERROR,
				'The revert and reverted revisions belong to different pages.'
			],
		], $logger->getBuffer() );
	}

	/**
	 * Tests whether the update is skipped when the revert is marked as deleted.
	 */
	public function testSkipUpdateWhenRevertIsDeleted() {
		$dummyRevision = $this->getDummyRevision();
		$dummyRevision->setVisibility( RevisionRecord::DELETED_TEXT );
		$revisionStore = $this->createMock( RevisionStore::class );
		$revisionStore->method( 'getRevisionById' )
			->willReturn( $dummyRevision );

		$logger = new TestLogger( true );

		$editResult = new EditResult(
			false,
			122,
			EditResult::REVERT_ROLLBACK,
			123,
			125,
			true,
			false,
			[ 'mw-rollback' ]
		);

		$update = $this->newRevertedTagUpdate(
			$this->createMock( ChangeTagsStore::class ),
			$revisionStore,
			$logger,
			[ 'mw-reverted' ],
			15,
			300,
			$editResult
		);
		$update->doUpdate();

		$this->assertSame( [
			[
				LogLevel::INFO,
				'The revert\'s text had been marked as deleted before the update was ' .
					'executed. Skipping...',
			],
		], $logger->getBuffer() );
	}

	/**
	 * Tests whether the update is skipped when the revert is marked as reverted.
	 */
	public function testSkipUpdateWhenRevertIsReverted() {
		$dummyRevision = $this->getDummyRevision();
		$revisionStore = $this->createMock( RevisionStore::class );
		$revisionStore->method( 'getRevisionById' )
			->willReturn( $dummyRevision );

		$logger = new TestLogger( true );

		$changeTagsStore = $this->createMock( ChangeTagsStore::class );
		$changeTagsStore->expects( $this->once() )
			->method( 'getTags' )
			->with( $this->anything(), null, 300 )
			->willReturn( [ 'mw-reverted' ] );

		$editResult = new EditResult(
			false,
			122,
			EditResult::REVERT_ROLLBACK,
			123,
			125,
			true,
			false,
			[ 'mw-rollback' ]
		);

		$update = $this->newRevertedTagUpdate(
			$changeTagsStore,
			$revisionStore,
			$logger,
			[ 'mw-reverted' ],
			15,
			300,
			$editResult
		);
		$update->doUpdate();

		$this->assertSame( [
			[
				LogLevel::INFO,
				'The revert had been reverted before the update was executed. Skipping...'
			],
		], $logger->getBuffer() );
	}

	/**
	 * Test the case where only one revision was reverted and we can omit some DB code.
	 */
	public function testSingleRevertedRevision() {
		$revisionStore = $this->createMock( RevisionStore::class );
		$revisionStore->method( 'getRevisionById' )
			->willReturn( $this->getDummyRevision() );

		$editResult = new EditResult(
			false,
			122,
			EditResult::REVERT_ROLLBACK,
			123,
			123,
			true,
			false,
			[ 'mw-rollback' ]
		);

		$changeTagsStore = $this->createMock( ChangeTagsStore::class );
		$changeTagsStore->expects( $this->once() )
			->method( 'getTags' )
			->willReturn( [] );
		$changeTagsStore->expects( $this->once() )
			->method( 'addTags' )
			->willReturnCallback(
				$this->getChangeTagsReturnCallback( 123, 124, $editResult )
			);

		$update = $this->newRevertedTagUpdate(
			$changeTagsStore,
			$revisionStore,
			new TestLogger(),
			[ 'mw-reverted' ],
			15,
			124,
			$editResult
		);
		$update->doUpdate();
	}

	/**
	 * Reverts deeper than $wgRevertedTagMaxDepth should not have the reverted tag applied.
	 */
	public function testRevertTooDeep() {
		$revisionStore = $this->createMock( RevisionStore::class );
		$revisionStore->method( 'getRevisionById' )
			->willReturnCallback( function ( int $id ) {
				return $this->getDummyRevision( 100, $id );
			} );
		$revisionStore->expects( $this->once() )
			->method( 'getRevisionIdsBetween' )
			->willReturn( [ 123, 124, 125, 126 ] ); // 4 reverted edits

		$editResult = new EditResult(
			false,
			false,
			EditResult::REVERT_UNDO,
			123,
			126,
			false,
			false,
			[ 'mw-undo' ]
		);

		$changeTagsStore = $this->createMock( ChangeTagsStore::class, [ 'getTags' ] );
		$changeTagsStore->expects( $this->once() )
			->method( 'getTags' )
			->willReturn( [] );

		$logger = new TestLogger( true );

		$update = $this->newRevertedTagUpdate(
			$changeTagsStore,
			$revisionStore,
			$logger,
			[ 'mw-reverted' ],
			3,
			130,
			$editResult
		);
		$update->doUpdate();

		$this->assertSame( [
			[
				LogLevel::INFO,
				'The revert is deeper than $wgRevertedTagMaxDepth. Skipping...'
			],
		], $logger->getBuffer() );
	}

	/**
	 * Test marking multiple revisions as reverted.
	 *
	 * Also ensures that null revisions (e.g. move and protection entries) are not
	 * marked as 'reverted', see: T265312
	 */
	public function testMultipleRevertedRevisions() {
		$revisionStore = $this->createMock( RevisionStore::class );
		$revisionStore->method( 'getRevisionById' )
			->willReturnCallback( function ( int $id ) {
				return $this->getDummyRevision(
					100,
					$id,
					'20100101202020',
					// Make it appear as though rev 125 has the same content as 124
					$id === 125 ? 124 : $id
				);
			} );
		$revisionStore->expects( $this->once() )
			->method( 'getRevisionIdsBetween' )
			->willReturn( [ 123, 124, 125, 126 ] );

		$editResult = new EditResult(
			false,
			false,
			EditResult::REVERT_UNDO,
			123,
			126,
			false,
			false,
			[ 'mw-undo' ]
		);

		$changeTagsStore = $this->createMock( ChangeTagsStore::class );

		// Revision 125 has the same content as 124, so it should not be marked
		// as reverted. See: T265312
		$reallyRevertedRevs = [ 123, 124, 126 ];
		$changeTagsStoreRetCallbacks = [];
		for ( $i = 0; $i <= 2; $i++ ) {
			$changeTagsStoreRetCallbacks[] = new ReturnCallback(
				$this->getChangeTagsReturnCallback( $reallyRevertedRevs[$i], 130, $editResult )
			);
		}
		$changeTagsStore
			->method( 'addTags' )
			->willReturnOnConsecutiveCalls( ...$changeTagsStoreRetCallbacks );
		$changeTagsStore->expects( $this->exactly( 3 ) )
			->method( 'addTags' );
		$changeTagsStore->expects( $this->once() )
			->method( 'getTags' )
			->willReturn( [] );

		$update = $this->newRevertedTagUpdate(
			$changeTagsStore,
			$revisionStore,
			new TestLogger(),
			[ 'mw-reverted' ],
			15,
			130,
			$editResult
		);
		$update->doUpdate();
	}
}
