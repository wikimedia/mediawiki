<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit\Permissions;

use MediaWiki\Block\AbstractBlock;
use MediaWiki\Block\Block;
use MediaWiki\Permissions\PermissionStatus;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Permissions\PermissionStatus
 */
class PermissionStatusTest extends MediaWikiUnitTestCase {

	public function testNewEmpty() {
		$status = PermissionStatus::newEmpty();

		$this->assertStatusGood( $status );

		// should not throw!
		$status->throwErrorPageError();
	}

	public function testBlock() {
		$status = PermissionStatus::newEmpty();

		$this->assertNull( $status->getBlock() );

		$block = $this->createMock( Block::class );
		$status->setBlock( $block );

		$this->assertSame( $block, $status->getBlock() );
		$this->assertTrue( $status->isBlocked() );
		$this->assertStatusNotOK( $status );
	}

	public function testRateLimitExceeded() {
		$status = PermissionStatus::newEmpty();

		$this->assertFalse( $status->isRateLimitExceeded() );

		$status->setRateLimitExceeded();
		$this->assertTrue( $status->isRateLimitExceeded() );
	}

	public function testMerge() {
		$status1 = PermissionStatus::newEmpty();
		$status1->setPermission( 'perm1' );

		$status2 = PermissionStatus::newEmpty();
		$block2 = $this->createMock( AbstractBlock::class );
		$block2->method( 'getIdentifier' )->willReturn( 2 );
		$status2->setBlock( $block2 );
		$status2->setPermission( 'perm2' );
		$status2->setRateLimitExceeded();
		$status2->fatal( 'foo' );

		$status1->merge( $status2 );
		$this->assertStatusNotOK( $status1 );
		$this->assertStatusError( 'foo', $status1 );
		$this->assertSame( 'perm1', $status1->getPermission() );
		$this->assertTrue( $status1->isRateLimitExceeded() );
		// TODO: Test merging two statuses that both have a block
		// This is currently not possible in a unit test, because CompositeBlock::createFromBlocks()
		// causes the CommentStore service to be used
		$this->assertSame( 2, $status1->getBlock()->getIdentifier() );
	}

}
