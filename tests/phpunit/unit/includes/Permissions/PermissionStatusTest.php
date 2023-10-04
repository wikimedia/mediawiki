<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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
