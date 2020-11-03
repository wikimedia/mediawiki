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

use MediaWiki\EditPage\Constraint\CreationPermissionConstraint;
use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\Permissions\PermissionManager;

/**
 * Tests the CreationPermissionConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\CreationPermissionConstraint
 */
class CreationPermissionConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	private function getPermissionManager( $user, $title, $result ) {
		$permissionManager = $this->createMock( PermissionManager::class );
		$permissionManager->expects( $this->once() )
			->method( 'userCan' )
			->with(
				$this->equalTo( 'create' ),
				$this->equalTo( $user ),
				$this->equalTo( $title )
			)
			->willReturn( $result );
		return $permissionManager;
	}

	public function testPass() {
		$user = $this->createMock( User::class );
		$title = $this->createMock( Title::class );

		$constraint = new CreationPermissionConstraint(
			$this->getPermissionManager( $user, $title, true ),
			$user,
			$title
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function testFailure() {
		$user = $this->createMock( User::class );
		$title = $this->createMock( Title::class );

		$constraint = new CreationPermissionConstraint(
			$this->getPermissionManager( $user, $title, false ),
			$user,
			$title
		);
		$this->assertConstraintFailed( $constraint, IEditConstraint::AS_NO_CREATE_PERMISSION );
	}

}
