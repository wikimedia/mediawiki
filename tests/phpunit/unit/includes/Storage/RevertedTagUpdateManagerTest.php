<?php

namespace MediaWiki\Tests\Storage;

use MediaWiki\JobQueue\JobQueueGroup;
use MediaWiki\Storage\EditResult;
use MediaWiki\Storage\EditResultCache;
use MediaWiki\Storage\RevertedTagUpdateManager;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Storage\RevertedTagUpdateManager
 *
 * @see RevertedTagUpdateIntegrationTest for integration tests
 */
class RevertedTagUpdateManagerTest extends MediaWikiUnitTestCase {

	public static function provideApproveRevertedTagForRevision() {
		yield 'not revert' => [
			new EditResult( false, 1234, 0, null, null, false, false, [] ),
			false
		];
		yield 'is revert' => [
			new EditResult( false, 1234, 0, 1230, 1233, true, false, [] ),
			false
		];
		yield 'no EditResult' => [
			null,
			false
		];
	}

	/**
	 * @dataProvider provideApproveRevertedTagForRevision
	 */
	public function testApproveRevertedTagForRevision( $editResult, $expectedOutcome ) {
		$revisionId = 123;

		$editResult = new EditResult( false, 1234, 0,
			null, null, false, false, [] );
		$editResultCache = $this->createMock( EditResultCache::class );
		$editResultCache->expects( $this->once() )
			->method( 'get' )
			->with( $revisionId )
			->willReturn( $editResult );

		$jobQueueGroup = $this->createMock( JobQueueGroup::class );
		$jobQueueGroup
			->expects( $expectedOutcome ? $this->once() : $this->never() )
			->method( 'lazyPush' );

		$manager = new RevertedTagUpdateManager(
			$editResultCache,
			$jobQueueGroup
		);
		$this->assertSame(
			$expectedOutcome,
			$manager->approveRevertedTagForRevision( $revisionId ),
			'return value of approveRevertedTagForRevision()'
		);
	}

}
