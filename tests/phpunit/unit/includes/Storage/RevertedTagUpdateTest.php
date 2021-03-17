<?php

namespace MediaWiki\Tests\Storage;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Storage\EditResult;
use MediaWiki\Storage\RevertedTagUpdate;
use MediaWiki\Storage\RevisionRecord;
use MediaWikiUnitTestCase;
use MockTitleTrait;
use PHPUnit\Framework\MockObject\Builder\InvocationMocker;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @covers \MediaWiki\Storage\RevertedTagUpdate
 * @see RevertedTagUpdateIntegrationTest for an end-to-end test with the database
 */
class RevertedTagUpdateTest extends MediaWikiUnitTestCase {
	use MockTitleTrait;

	/**
	 * Convenience function for creating a RevertedTagUpdate that does not use
	 * the ChangeTags static class. Instead, a mock for a future ChangeTags-like
	 * object should be provided.
	 * TODO: clean this up once T245964 is resolved
	 *
	 * @param \FutureChangeTags $futureChangeTags
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
		$futureChangeTags,
		RevisionStore $revisionStore,
		LoggerInterface $logger,
		array $softwareTags,
		int $revertedTagMaxDepth,
		int $revertId,
		EditResult $editResult
	) : RevertedTagUpdate {
		// LoadBalancer is never used in unit tests because getTags is overridden
		$loadBalancer = $this->createNoOpMock( ILoadBalancer::class );

		return new class(
			$futureChangeTags,
			$revisionStore,
			$logger,
			$softwareTags,
			$loadBalancer,
			$revertedTagMaxDepth,
			$revertId,
			$editResult
		) extends RevertedTagUpdate {

			protected $futureChangeTags;

			public function __construct(
				$futureChangeTags,
				RevisionStore $revisionStore,
				LoggerInterface $logger,
				array $softwareTags,
				ILoadBalancer $loadBalancer,
				int $revertedTagMaxDepth,
				int $revertId,
				EditResult $editResult
			) {
				$serviceOptions = new ServiceOptions(
					RevertedTagUpdate::CONSTRUCTOR_OPTIONS,
					[ 'RevertedTagMaxDepth' => $revertedTagMaxDepth ]
				);

				parent::__construct(
					$revisionStore,
					$logger,
					$softwareTags,
					$loadBalancer,
					$serviceOptions,
					$revertId,
					$editResult
				);
				$this->futureChangeTags = $futureChangeTags;
			}

			protected function markAsReverted( int $revisionId, array $extraParams ) {
				$this->futureChangeTags->addTags(
					$revisionId,
					$extraParams
				);
			}

			protected function getChangeTags( int $revisionId ) {
				return $this->futureChangeTags->getTags(
					$revisionId
				);
			}
		};
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
		$revisionRecord = new MutableRevisionRecord(
			$this->makeMockTitle( __METHOD__, [ 'id' => $pageId ] )
		);
		$revisionRecord->setId( $revisionId );
		$revisionRecord->setTimestamp( $timestamp );
		$revisionRecord->setPageId( $pageId );
		// Not a valid SHA-1, but enough to make these revisions appear like they have
		// different contents.
		$revisionRecord->setSha1( $sha1 ?? strval( $revisionId ) );
		return $revisionRecord;
	}

	/**
	 * Returns a LoggerInterface expecting to have one of its methods called with
	 * a specific message.
	 *
	 * @param string $message The message that's expected to be passed to the logger.
	 * @param string $method Method of the logger that's expected to be called, e.g.:
	 *   'error', 'warning', 'notice', etc. Default is 'error'.
	 *
	 * @return LoggerInterface
	 */
	private function getMockLogger(
		string $message,
		string $method = 'error'
	) : LoggerInterface {
		$logger = $this->createMock( LoggerInterface::class );
		$logger->expects( $this->once() )
			->method( $method )
			->with( $this->equalTo( $message ) );
		return $logger;
	}

	/**
	 * Sets up assertions to run inside RevertedTagUpdate::markAsReverted()
	 * overloaded method.
	 *
	 * @param InvocationMocker $futureChangeTagsMocker
	 * @param int $revertedRevisionId
	 * @param int $newRevisionId
	 * @param EditResult $editResult
	 */
	private function setFutureChangeTagsAsserts(
		InvocationMocker $futureChangeTagsMocker,
		int $revertedRevisionId,
		int $newRevisionId,
		EditResult $editResult
	) {
		$futureChangeTagsMocker->willReturnCallback( function (
			int $revisionId,
			array $extraParams
		) use ( $newRevisionId, $revertedRevisionId, $editResult ) {
			$this->assertSame(
				$revertedRevisionId,
				$revisionId,
				'RevertedTagUpdate::markAsReverted() $revisionId'
			);
			$this->assertArrayEquals(
				array_merge(
					[ 'revertId' => $newRevisionId ],
					$editResult->jsonSerialize()
				),
				$extraParams,
				false,
				true,
				'RevertedTagUpdate::markAsReverted()'
			);
		} );
	}

	public function provideRevertedTagUpdateDisabled() : array {
		return [
			'mw-reverted tag is disabled' => [
				[],
				15
			],
			'$wgRevertedTagMaxDepth is 0' => [
				[ 'mw-reverted' ],
				0
			]
		];
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
		$futureChangeTags = $this->getMockBuilder( \FutureChangeTags::class )
			->allowMockingUnknownTypes()
			->setMethods( [ 'addTags' ] )
			->getMock();
		$futureChangeTags->expects( $this->never() )->method( 'addTags' );

		$revisionStore = $this->createNoOpMock( RevisionStore::class );
		$logger = $this->createNoOpMock( LoggerInterface::class );
		$editResult = $this->createNoOpMock( EditResult::class );

		$update = $this->newRevertedTagUpdate(
			$futureChangeTags,
			$revisionStore,
			$logger,
			$softwareChangeTags,
			$revertedTagMaxDepth,
			123,
			$editResult
		);
		$update->doUpdate();
	}

	public function provideInvalidEditResults() {
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
		$futureChangeTags = $this->getMockBuilder( \FutureChangeTags::class )
			->allowMockingUnknownTypes()
			->setMethods( [ 'addTags' ] )
			->getMock();
		$futureChangeTags->expects( $this->never() )->method( 'addTags' );

		$logger = $this->getMockLogger( 'Invalid EditResult specified.' );

		$revisionStore = $this->createNoOpMock( RevisionStore::class );

		$update = $this->newRevertedTagUpdate(
			$futureChangeTags,
			$revisionStore,
			$logger,
			[ 'mw-reverted' ],
			15,
			123,
			$editResult
		);
		$update->doUpdate();
	}

	/**
	 * Test the case where the supposedly reverted revisions are missing from the DB.
	 */
	public function testMissingRevertedRevisions() {
		$revisionStore = $this->createMock( RevisionStore::class );
		$revisionStore->expects( $this->once() )
			->method( 'getRevisionById' )
			->willReturn( null );

		// an error should be logged
		$logger = $this->getMockLogger(
			'Could not find the newest or oldest reverted revision in the database.'
		);

		$futureChangeTags = $this->getMockBuilder( \FutureChangeTags::class )
			->allowMockingUnknownTypes()
			->setMethods( [ 'addTags' ] )
			->getMock();
		$futureChangeTags->expects( $this->never() )->method( 'addTags' );

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
			$futureChangeTags,
			$revisionStore,
			$logger,
			[ 'mw-reverted' ],
			15,
			300,
			$editResult
		);
		$update->doUpdate();
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

		$logger = $this->getMockLogger(
			'Could not find the revert revision in the database.'
		);

		$futureChangeTags = $this->getMockBuilder( \FutureChangeTags::class )
			->allowMockingUnknownTypes()
			->setMethods( [ 'addTags' ] )
			->getMock();
		$futureChangeTags->expects( $this->never() )->method( 'addTags' );

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
			$futureChangeTags,
			$revisionStore,
			$logger,
			[ 'mw-reverted' ],
			15,
			300,
			$editResult
		);
		$update->doUpdate();
	}

	public function providePageIdMismatch() {
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

		$logger = $this->getMockLogger(
			'The revert and reverted revisions belong to different pages.'
		);

		$futureChangeTags = $this->getMockBuilder( \FutureChangeTags::class )
			->allowMockingUnknownTypes()
			->setMethods( [ 'addTags' ] )
			->getMock();
		$futureChangeTags->expects( $this->never() )->method( 'addTags' );

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
			$futureChangeTags,
			$revisionStore,
			$logger,
			[ 'mw-reverted' ],
			15,
			300,
			$editResult
		);
		$update->doUpdate();
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

		$logger = $this->getMockLogger(
			'The revert\'s text had been marked as deleted before the update was ' .
			'executed. Skipping...',
			'notice'
		);

		$futureChangeTags = $this->getMockBuilder( \FutureChangeTags::class )
			->allowMockingUnknownTypes()
			->setMethods( [ 'addTags' ] )
			->getMock();
		$futureChangeTags->expects( $this->never() )->method( 'addTags' );

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
			$futureChangeTags,
			$revisionStore,
			$logger,
			[ 'mw-reverted' ],
			15,
			300,
			$editResult
		);
		$update->doUpdate();
	}

	/**
	 * Tests whether the update is skipped when the revert is marked as reverted.
	 */
	public function testSkipUpdateWhenRevertIsReverted() {
		$dummyRevision = $this->getDummyRevision();
		$revisionStore = $this->createMock( RevisionStore::class );
		$revisionStore->method( 'getRevisionById' )
			->willReturn( $dummyRevision );

		$logger = $this->getMockLogger(
			'The revert had been reverted before the update was executed. Skipping...',
			'notice'
		);

		$futureChangeTags = $this->getMockBuilder( \FutureChangeTags::class )
			->allowMockingUnknownTypes()
			->setMethods( [ 'addTags', 'getTags' ] )
			->getMock();
		$futureChangeTags->expects( $this->never() )->method( 'addTags' );
		$futureChangeTags->expects( $this->once() )
			->method( 'getTags' )
			->with( 300 )
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
			$futureChangeTags,
			$revisionStore,
			$logger,
			[ 'mw-reverted' ],
			15,
			300,
			$editResult
		);
		$update->doUpdate();
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

		$futureChangeTags = $this->getMockBuilder( \FutureChangeTags::class )
			->allowMockingUnknownTypes()
			->setMethods( [ 'addTags', 'getTags' ] )
			->getMock();
		$futureChangeTags->expects( $this->once() )
			->method( 'getTags' )
			->willReturn( [] );
		$this->setFutureChangeTagsAsserts(
			$futureChangeTags->expects( $this->once() )
				->method( 'addTags' ),
			123,
			124,
			$editResult
		);

		$logger = $this->createNoOpMock( LoggerInterface::class );

		$update = $this->newRevertedTagUpdate(
			$futureChangeTags,
			$revisionStore,
			$logger,
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

		$futureChangeTags = $this->getMockBuilder( \FutureChangeTags::class )
			->allowMockingUnknownTypes()
			->setMethods( [ 'addTags', 'getTags' ] )
			->getMock();
		$futureChangeTags->expects( $this->never() )
			->method( 'addTags' );
		$futureChangeTags->expects( $this->once() )
			->method( 'getTags' )
			->willReturn( [] );

		$logger = $this->getMockLogger(
			'The revert is deeper than $wgRevertedTagMaxDepth. Skipping...',
			'notice'
		);

		$update = $this->newRevertedTagUpdate(
			$futureChangeTags,
			$revisionStore,
			$logger,
			[ 'mw-reverted' ],
			3,
			130,
			$editResult
		);
		$update->doUpdate();
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

		$futureChangeTags = $this->getMockBuilder( \FutureChangeTags::class )
			->allowMockingUnknownTypes()
			->setMethods( [ 'addTags', 'getTags' ] )
			->getMock();

		// Revision 125 has the same content as 124, so it should not be marked
		// as reverted. See: T265312
		$reallyRevertedRevs = [ 123, 124, 126 ];
		for ( $i = 0; $i <= 2; $i++ ) {
			$this->setFutureChangeTagsAsserts(
				// $i + 1 because getTags is invoked first
				$futureChangeTags->expects( $this->at( $i + 1 ) )
					->method( 'addTags' ),
				$reallyRevertedRevs[$i],
				130,
				$editResult
			);
		}
		$futureChangeTags->expects( $this->exactly( 3 ) )
			->method( 'addTags' );
		$futureChangeTags->expects( $this->once() )
			->method( 'getTags' )
			->willReturn( [] );

		$logger = $this->createNoOpMock( LoggerInterface::class );

		$update = $this->newRevertedTagUpdate(
			$futureChangeTags,
			$revisionStore,
			$logger,
			[ 'mw-reverted' ],
			15,
			130,
			$editResult
		);
		$update->doUpdate();
	}
}
