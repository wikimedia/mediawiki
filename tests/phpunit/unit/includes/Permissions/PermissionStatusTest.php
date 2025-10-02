<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Tests\Unit\Permissions;

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
		$this->assertFalse( $status->isOK() );
	}

	public function testRateLimitExceeded() {
		$status = PermissionStatus::newEmpty();

		$this->assertFalse( $status->isRateLimitExceeded() );

		$status->setRateLimitExceeded();
		$this->assertTrue( $status->isRateLimitExceeded() );
	}

}
