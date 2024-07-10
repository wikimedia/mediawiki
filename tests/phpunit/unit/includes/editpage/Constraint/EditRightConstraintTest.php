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
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

/**
 * Tests the EditRightConstraint
 *
 * @author DannyS712
 *
 * @covers \MediaWiki\EditPage\Constraint\EditRightConstraint
 */
class EditRightConstraintTest extends MediaWikiUnitTestCase {
	use EditConstraintTestTrait;
	use MockAuthorityTrait;
	use MockTitleTrait;

	/**
	 * @dataProvider provideTestPass
	 * @param User $performer
	 * @param bool $new
	 * @param PermissionManager $permissionManager
	 * @return void
	 */
	public function testPass( User $performer, bool $new, PermissionManager $permissionManager ) {
		$constraint = new EditRightConstraint(
			$performer,
			$permissionManager,
			$this->createMock( Title::class ),
			$new
		);
		$this->assertConstraintPassed( $constraint );
	}

	public function provideTestPass() {
		$title = $this->createMock( Title::class );
		$userEdit = $this->createMock( User::class );
		$permissionManagerEdit = $this->createMock( PermissionManager::class );
		$permissionManagerEdit->expects( $this->once() )
			->method( 'userCan' )
			->with(
				'edit',
				$userEdit,
				$title
			)
			->willReturn( true );
		$userCreateAndEdit = $this->createMock( User::class );
		$userCreateAndEdit->expects( $this->once() )
			->method( 'authorizeWrite' )
			->with(
				'create',
				$title
			)
			->willReturn( true );
		$permissionManagerCreateAndEdit = $this->createMock( PermissionManager::class );
		$permissionManagerCreateAndEdit->expects( $this->once() )
			->method( 'userCan' )
			->with(
				'edit',
				$userCreateAndEdit,
				$title
			)
			->willReturn( true );
		yield 'Edit existing page' => [
			'performer' => $userEdit,
			'new' => false,
			'permissionManager' => $permissionManagerEdit
		];
		yield 'Create a new page' => [
			'performer' => $userCreateAndEdit,
			'new' => true,
			'permissionManager' => $permissionManagerCreateAndEdit
		];
	}

	/**
	 * @dataProvider provideTestFailure
	 * @param User $performer
	 * @param bool $new
	 * @param PermissionManager $permissionManager
	 * @param int $expectedValue
	 */
	public function testFailure(
		User $performer, bool $new, PermissionManager $permissionManager, int $expectedValue
	) {
		$title = $this->createMock( Title::class );
		$constraint = new EditRightConstraint(
			$performer,
			$permissionManager,
			$title,
			$new
		);
		$this->assertConstraintFailed( $constraint, $expectedValue );
	}

	public function provideTestFailure() {
		$title = $this->createMock( Title::class );
		$anon = $this->createMock( User::class );
		$anon->expects( $this->once() )->method( 'isRegistered' )->willReturn( false );
		$permissionManagerAnon = $this->createMock( PermissionManager::class );
		$permissionManagerAnon->expects( $this->once() )
			->method( 'userCan' )
			->with(
				'edit',
				$anon,
				$title
			)
			->willReturn( false );
		$reg = $this->createMock( User::class );
		$reg->expects( $this->once() )->method( 'isRegistered' )->willReturn( true );
		$permissionManagerReg = $this->createMock( PermissionManager::class );
		$permissionManagerReg->expects( $this->once() )
			->method( 'userCan' )
			->with(
				'edit',
				$reg,
				$title
			)
			->willReturn( false );
		$userWithoutCreatePerm = $this->createMock( User::class );
		$userWithoutCreatePerm->expects( $this->once() )
			->method( 'authorizeWrite' )
			->with(
				'create',
				$title
			)
			->willReturn( false );
		yield 'Anonymous user' => [
			'performer' => $anon,
			'new' => false,
			'permissionManager' => $permissionManagerAnon,
			'expectedValue' => IEditConstraint::AS_READ_ONLY_PAGE_ANON,
		];
		yield 'Registered user' => [
			'performer' => $reg,
			'new' => false,
			'permissionManager' => $permissionManagerReg,
			'expectedValue' => IEditConstraint::AS_READ_ONLY_PAGE_LOGGED,
		];
		yield 'User without create permission creates a page' => [
			'performer' => $userWithoutCreatePerm,
			'new' => true,
			'permissionManager' => $this->createMock( PermissionManager::class ),
			'expectedValue' => IEditConstraint::AS_NO_CREATE_PERMISSION,
		];
	}

}
