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

use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\EditPage\Constraint\UserBlockConstraint;
use MediaWiki\Permissions\PermissionManager;

/**
 * Tests the UserBlockConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\UserBlockConstraint
 */
class UserBlockConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	public function testPass() {
		$title = $this->createMock( Title::class );
		$user = $this->createMock( User::class );
		$permissionManager = $this->createMock( PermissionManager::class );
		$permissionManager->expects( $this->once() )
			->method( 'isBlockedFrom' )
			->with(
				$this->equalTo( $user ),
				$this->equalTo( $title )
			)
			->willReturn( false );

		$constraint = new UserBlockConstraint( $permissionManager, $title, $user );
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		$title = $this->createMock( Title::class );
		$user = $this->createMock( User::class );
		$permissionManager = $this->createMock( PermissionManager::class );
		$permissionManager->expects( $this->once() )
			->method( 'isBlockedFrom' )
			->with(
				$this->equalTo( $user ),
				$this->equalTo( $title )
			)
			->willReturn( true );

		$constraint = new UserBlockConstraint( $permissionManager, $title, $user );
		$this->assertConstraintFailed(
			$constraint,
			IEditConstraint::AS_BLOCKED_PAGE_FOR_USER
		);
	}

}
