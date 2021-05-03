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

use InvalidArgumentException;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Permissions\UserAuthority;
use MediaWikiUnitTestCase;
use User;

/**
 * @covers \MediaWiki\Permissions\UserAuthority
 */
class UserAuthorityTest extends MediaWikiUnitTestCase {

	/**
	 * @param string[] $permissions
	 * @param User|null $actor The user to use, null to create a new mock
	 * @return UserAuthority
	 */
	private function newAuthority( array $permissions, User $actor = null ) : UserAuthority {
		$permissionManager = $this->createNoOpMock(
			PermissionManager::class,
			[ 'userHasRight', 'userCan', 'getPermissionErrors' ]
		);

		$permissionManager->method( 'userHasRight' )->willReturnCallback(
			static function ( $user, $permission ) use ( $permissions ) {
				return in_array( $permission, $permissions );
			}
		);

		$permissionManager->method( 'userCan' )->willReturnCallback(
			static function ( $permission, $user ) use ( $permissionManager ) {
				return $permissionManager->userHasRight( $user, $permission );
			}
		);

		$permissionManager->method( 'getPermissionErrors' )->willReturnCallback(
			static function ( $permission, $user, $target ) use ( $permissionManager ) {
				$errors = [];
				if ( !$permissionManager->userCan( $permission, $user, $target ) ) {
					$errors[] = [ 'permissionserrors' ];
				}

				return $errors;
			}
		);

		return new UserAuthority(
			$actor ?? $this->createNoOpMock( User::class ),
			$permissionManager
		);
	}

	public function testGetAuthor() {
		$actor = $this->createNoOpMock( User::class );
		$authority = $this->newAuthority( [], $actor );

		$this->assertSame( $actor, $authority->getUser() );
	}

	public function testPermissions() {
		$authority = $this->newAuthority( [ 'foo', 'bar' ] );

		$this->assertTrue( $authority->isAllowed( 'foo' ) );
		$this->assertTrue( $authority->isAllowed( 'bar' ) );
		$this->assertFalse( $authority->isAllowed( 'quux' ) );

		$this->assertTrue( $authority->isAllowedAll( 'foo', 'bar' ) );
		$this->assertTrue( $authority->isAllowedAny( 'bar', 'quux' ) );

		$this->assertFalse( $authority->isAllowedAll( 'foo', 'quux' ) );
		$this->assertFalse( $authority->isAllowedAny( 'xyzzy', 'quux' ) );
	}

	public function testProbablyCan() {
		$target = new PageIdentityValue( 321, NS_MAIN, __METHOD__, PageIdentity::LOCAL );
		$authority = $this->newAuthority( [ 'foo', 'bar' ] );

		$this->assertTrue( $authority->probablyCan( 'foo', $target ) );
		$this->assertTrue( $authority->probablyCan( 'bar', $target ) );
		$this->assertFalse( $authority->probablyCan( 'quux', $target ) );

		$status = new PermissionStatus();
		$authority->probablyCan( 'foo', $target, $status );
		$this->assertTrue( $status->isOK() );

		$authority->probablyCan( 'quux', $target, $status );
		$this->assertFalse( $status->isOK() );
	}

	public function testDefinitlyCan() {
		$target = new PageIdentityValue( 321, NS_MAIN, __METHOD__, PageIdentity::LOCAL );
		$authority = $this->newAuthority( [ 'foo', 'bar' ] );

		$this->assertTrue( $authority->definitelyCan( 'foo', $target ) );
		$this->assertTrue( $authority->definitelyCan( 'bar', $target ) );
		$this->assertFalse( $authority->definitelyCan( 'quux', $target ) );

		$status = new PermissionStatus();
		$authority->definitelyCan( 'foo', $target, $status );
		$this->assertTrue( $status->isOK() );

		$authority->definitelyCan( 'quux', $target, $status );
		$this->assertFalse( $status->isOK() );
	}

	public function testAuthorizeRead() {
		$target = new PageIdentityValue( 321, NS_MAIN, __METHOD__, PageIdentity::LOCAL );
		$authority = $this->newAuthority( [ 'foo', 'bar' ] );

		$this->assertTrue( $authority->authorizeRead( 'foo', $target ) );
		$this->assertTrue( $authority->authorizeRead( 'bar', $target ) );
		$this->assertFalse( $authority->authorizeRead( 'quux', $target ) );

		$status = new PermissionStatus();
		$authority->authorizeRead( 'foo', $target, $status );
		$this->assertTrue( $status->isOK() );

		$authority->authorizeRead( 'quux', $target, $status );
		$this->assertFalse( $status->isOK() );
	}

	public function testAuthorizeWrite() {
		$target = new PageIdentityValue( 321, NS_MAIN, __METHOD__, PageIdentity::LOCAL );
		$authority = $this->newAuthority( [ 'foo', 'bar' ] );

		$this->assertTrue( $authority->authorizeWrite( 'foo', $target ) );
		$this->assertTrue( $authority->authorizeWrite( 'bar', $target ) );
		$this->assertFalse( $authority->authorizeWrite( 'quux', $target ) );

		$status = new PermissionStatus();
		$authority->authorizeWrite( 'foo', $target, $status );
		$this->assertTrue( $status->isOK() );

		$authority->authorizeWrite( 'quux', $target, $status );
		$this->assertFalse( $status->isOK() );
	}

	public function testIsAllowedAnyThrowsOnEmptySet() {
		$authority = $this->newAuthority( [ 'foo', 'bar' ] );

		$this->expectException( InvalidArgumentException::class );
		$authority->isAllowedAny();
	}

	public function testIsAllowedAllThrowsOnEmptySet() {
		$authority = $this->newAuthority( [ 'foo', 'bar' ] );

		$this->expectException( InvalidArgumentException::class );
		$authority->isAllowedAll();
	}

}
