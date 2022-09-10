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
use MediaWiki\Block\Block;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Permissions\UserAuthority;
use MediaWikiUnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Status;
use User;

/**
 * @covers \MediaWiki\Permissions\UserAuthority
 */
class UserAuthorityTest extends MediaWikiUnitTestCase {

	/** @var string[] Some dummy message parameters to test error message formatting. */
	private const FAKE_BLOCK_MESSAGE_PARAMS = [
		'[[User:Blocker|Blocker]]',
		'Block reason that can contain {{templates}}',
		'192.168.0.1',
		'Blocker',
	];

	/**
	 * @param string[] $permissions
	 * @return PermissionManager
	 */
	private function newPermissionsManager( array $permissions ): PermissionManager {
		/** @var PermissionManager|MockObject $permissionManager */
		$permissionManager = $this->createMock(
			PermissionManager::class,
			[ 'userHasRight', 'userCan', 'getPermissionErrors', 'isBlockedFrom' ]
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

				if ( $user->getBlock() && $permission !== 'read' ) {
					$errors[] = array_merge(
						[ 'blockedtext-partial' ],
						self::FAKE_BLOCK_MESSAGE_PARAMS
					);
				}

				return $errors;
			}
		);

		$permissionManager->method( 'isBlockedFrom' )->willReturnCallback(
			static function ( User $user, $page ) {
				return $page->getDBkey() === 'Forbidden';
			}
		);

		return $permissionManager;
	}

	private function newUser(): User {
		/** @var User|MockObject $actor */
		$actor = $this->createNoOpMock( User::class, [ 'getBlock' ] );
		$actor->method( 'getBlock' )->willReturn( null );
		return $actor;
	}

	private function newAuthority( array $permissions, User $actor = null ): Authority {
		/** @var PermissionManager|MockObject $permissionManager */
		$permissionManager = $this->newPermissionsManager( $permissions );
		return new UserAuthority(
			$actor ?? $this->newUser(),
			$permissionManager
		);
	}

	public function testGetUser() {
		$user = $this->newUser();
		$authority = $this->newAuthority( [], $user );

		$this->assertSame( $user, $authority->getUser() );
	}

	public function testGetUserBlockNotBlocked() {
		$authority = $this->newAuthority( [] );
		$this->assertNull( $authority->getBlock() );
	}

	/**
	 * @param Block|null $block
	 *
	 * @return MockObject|User
	 */
	private function getBlockedUser( $block = null ) {
		$block = $block ?: $this->createNoOpMock( Block::class );

		$user = $this->createNoOpMock( User::class, [ 'getBlock' ] );
		$user->method( 'getBlock' )
			->willReturn( $block );

		return $user;
	}

	public function testGetUserBlockWasBlocked() {
		$block = $this->createNoOpMock( Block::class );
		$user = $this->getBlockedUser( $block );

		$authority = $this->newAuthority( [], $user );
		$this->assertSame( $block, $authority->getBlock() );
	}

	public function testBlockedUserCanRead() {
		$block = $this->createNoOpMock( Block::class );
		$user = $this->getBlockedUser( $block );

		$authority = $this->newAuthority( [ 'read', 'edit' ], $user );

		$status = PermissionStatus::newEmpty();
		$target = new PageIdentityValue( 321, NS_MAIN, __METHOD__, PageIdentity::LOCAL );
		$this->assertTrue( $authority->authorizeRead( 'read', $target, $status ) );
		$this->assertStatusOK( $status );
	}

	public function testBlockedUserCanNotWrite() {
		$block = $this->createNoOpMock( Block::class );
		$user = $this->getBlockedUser( $block );

		$authority = $this->newAuthority( [ 'read', 'edit' ], $user );

		$status = PermissionStatus::newEmpty();
		$target = new PageIdentityValue( 321, NS_MAIN, __METHOD__, PageIdentity::LOCAL );
		$this->assertFalse( $authority->authorizeRead( 'edit', $target, $status ) );
		$this->assertStatusNotOK( $status );
		$this->assertSame( $block, $status->getBlock() );
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
		$this->assertStatusOK( $status );

		$authority->probablyCan( 'quux', $target, $status );
		$this->assertStatusNotOK( $status );
	}

	public function testDefinitlyCan() {
		$target = new PageIdentityValue( 321, NS_MAIN, __METHOD__, PageIdentity::LOCAL );
		$authority = $this->newAuthority( [ 'foo', 'bar' ] );

		$this->assertTrue( $authority->definitelyCan( 'foo', $target ) );
		$this->assertTrue( $authority->definitelyCan( 'bar', $target ) );
		$this->assertFalse( $authority->definitelyCan( 'quux', $target ) );

		$status = new PermissionStatus();
		$authority->definitelyCan( 'foo', $target, $status );
		$this->assertStatusOK( $status );

		$authority->definitelyCan( 'quux', $target, $status );
		$this->assertStatusNotOK( $status );
	}

	public function testAuthorizeRead() {
		$target = new PageIdentityValue( 321, NS_MAIN, __METHOD__, PageIdentity::LOCAL );
		$authority = $this->newAuthority( [ 'foo', 'bar' ] );

		$this->assertTrue( $authority->authorizeRead( 'foo', $target ) );
		$this->assertTrue( $authority->authorizeRead( 'bar', $target ) );
		$this->assertFalse( $authority->authorizeRead( 'quux', $target ) );

		$status = new PermissionStatus();
		$authority->authorizeRead( 'foo', $target, $status );
		$this->assertStatusOK( $status );

		$authority->authorizeRead( 'quux', $target, $status );
		$this->assertStatusNotOK( $status );
	}

	public function testAuthorizeWrite() {
		$target = new PageIdentityValue( 321, NS_MAIN, __METHOD__, PageIdentity::LOCAL );
		$authority = $this->newAuthority( [ 'foo', 'bar' ] );

		$this->assertTrue( $authority->authorizeWrite( 'foo', $target ) );
		$this->assertTrue( $authority->authorizeWrite( 'bar', $target ) );
		$this->assertFalse( $authority->authorizeWrite( 'quux', $target ) );

		$status = new PermissionStatus();
		$authority->authorizeWrite( 'foo', $target, $status );
		$this->assertStatusOK( $status );

		$authority->authorizeWrite( 'quux', $target, $status );
		$this->assertStatusNotOK( $status );
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

	public function testGetBlock_none() {
		$actor = $this->newUser();

		$authority = $this->newAuthority( [ 'foo', 'bar' ], $actor );

		$this->assertNull( $authority->getBlock() );
	}

	public function testGetBlock_blocked() {
		$block = $this->createNoOpMock( Block::class );
		$actor = $this->getBlockedUser( $block );

		$authority = $this->newAuthority( [ 'foo', 'bar' ], $actor );

		$this->assertSame( $block, $authority->getBlock() );
	}

	/**
	 * Regression test for T306494: check that when creating a PermissionStatus,
	 * the message contains all parameters and when converted to a Status, the parameters
	 * are not wikitext escaped.
	 */
	public function testInternalCanWithPermissionStatusMessageFormatting() {
		$block = $this->createNoOpMock( Block::class );
		$user = $this->getBlockedUser( $block );

		$authority = $this->newAuthority( [ 'read', 'edit' ], $user );

		$permissionStatus = PermissionStatus::newEmpty();
		$target = new PageIdentityValue( 321, NS_MAIN, __METHOD__, PageIdentity::LOCAL );

		$authority->authorizeWrite(
			'edit',
			$target,
			$permissionStatus
		);

		$this->assertTrue( $permissionStatus->hasMessage( 'blockedtext-partial' ) );

		$status = Status::wrap( $permissionStatus );

		$message = $status->getMessage();
		$this->assertEquals( 'blockedtext-partial', $message->getKey() );
		$this->assertArrayEquals(
			self::FAKE_BLOCK_MESSAGE_PARAMS,
			$message->getParams()
		);
	}
}
