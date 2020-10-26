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

use MediaWiki\EditPage\Constraint\EditRightConstraint;
use MediaWiki\EditPage\Constraint\IEditConstraint;
use MediaWiki\Permissions\PermissionManager;

/**
 * Tests the EditRightConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\EditRightConstraint
 */
class EditRightConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;

	public function testPass() {
		$user = $this->createMock( User::class );
		$permissionManager = $this->createMock( PermissionManager::class );
		$permissionManager->expects( $this->once() )
			->method( 'userHasRight' )
			->with(
				$this->equalTo( $user ),
				$this->equalTo( 'edit' )
			)
			->willReturn( true );

		$constraint = new EditRightConstraint( $permissionManager, $user );
		$this->assertConstraintPassed( $constraint );
	}

	/**
	 * @dataProvider provideTestFailure
	 * @param bool $isAnon
	 * @param int $expectedValue
	 */
	public function testFailure( $isAnon, $expectedValue ) {
		$user = $this->createMock( User::class );
		$user->method( 'isAnon' )->willReturn( $isAnon );
		$permissionManager = $this->createMock( PermissionManager::class );
		$permissionManager->expects( $this->once() )
			->method( 'userHasRight' )
			->with(
				$this->equalTo( $user ),
				$this->equalTo( 'edit' )
			)
			->willReturn( false );

		$constraint = new EditRightConstraint( $permissionManager, $user );
		$this->assertConstraintFailed( $constraint, $expectedValue );
	}

	public function provideTestFailure() {
		yield 'Anonymous user' => [ true, IEditConstraint::AS_READ_ONLY_PAGE_ANON ];
		yield 'Registered user' => [ false, IEditConstraint::AS_READ_ONLY_PAGE_LOGGED ];
	}

}
