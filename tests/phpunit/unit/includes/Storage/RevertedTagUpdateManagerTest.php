<?php

namespace MediaWiki\Tests\Storage;

use JobQueueGroup;
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

	/**
	 * @covers \MediaWiki\Storage\RevertedTagUpdateManager::approveRevertedTagForRevision
	 */
	public function testApproveRevertedTagForRevision() {
		$revisionId = 123;

		$editResult = $this->createMock( EditResult::class );
		$editResultCache = $this->createMock( EditResultCache::class );
		$editResultCache->expects( $this->once() )
			->method( 'get' )
			->with( $revisionId )
			->willReturn( $editResult );

		$jobQueueGroup = $this->createMock( JobQueueGroup::class );
		$jobQueueGroup->expects( $this->once() )
			->method( 'lazyPush' );

		$manager = new RevertedTagUpdateManager(
			$editResultCache,
			$jobQueueGroup
		);
		$this->assertTrue(
			$manager->approveRevertedTagForRevision( $revisionId ),
			'The operation is successful'
		);
	}

	/**
	 * @covers \MediaWiki\Storage\RevertedTagUpdateManager::approveRevertedTagForRevision
	 */
	public function testApproveRevertedTagForRevision_missingEditResult() {
		$revisionId = 123;

		$editResultCache = $this->createMock( EditResultCache::class );
		$editResultCache->expects( $this->once() )
			->method( 'get' )
			->with( $revisionId )
			->willReturn( null );

		$jobQueueGroup = $this->createNoOpMock( JobQueueGroup::class );

		$manager = new RevertedTagUpdateManager(
			$editResultCache,
			$jobQueueGroup
		);
		$this->assertFalse(
			$manager->approveRevertedTagForRevision( $revisionId ),
			'The operation is not successful'
		);
	}
}
