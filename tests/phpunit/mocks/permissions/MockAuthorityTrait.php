<?php

namespace MediaWiki\Tests\Unit\Permissions;

use MediaWiki\Block\Block;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\SimpleAuthority;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;

/**
 * Various useful Authority mocks.
 * @stable to use (since 1.37)
 */
trait MockAuthorityTrait {

	/**
	 * Create mock ultimate Authority for anon user.
	 *
	 * @return Authority
	 */
	private function mockAnonUltimateAuthority(): Authority {
		return new UltimateAuthority( new UserIdentityValue( 0, '127.0.0.1' ) );
	}

	/**
	 * Create mock ultimate Authority for registered user.
	 *
	 * @return Authority
	 */
	private function mockRegisteredUltimateAuthority(): Authority {
		return new UltimateAuthority( new UserIdentityValue( 9999, 'Petr' ) );
	}

	/**
	 * Create mock Authority for anon user with no permissions.
	 *
	 * @return Authority
	 */
	private function mockAnonNullAuthority(): Authority {
		return new SimpleAuthority( new UserIdentityValue( 0, '127.0.0.1' ), [] );
	}

	/**
	 * Create mock Authority for a registered user with no permissions.
	 *
	 * @return Authority
	 */
	private function mockRegisteredNullAuthority(): Authority {
		return new SimpleAuthority( new UserIdentityValue( 9999, 'Petr' ), [] );
	}

	/**
	 * Create a mock Authority for anon user with $permissions.
	 *
	 * @param array $permissions
	 * @return Authority
	 */
	private function mockAnonAuthorityWithPermissions( array $permissions ): Authority {
		return new SimpleAuthority( new UserIdentityValue( 0, '127.0.0.1' ), $permissions );
	}

	/**
	 * Create a mock Authority for a registered user with $permissions.
	 *
	 * @param array $permissions
	 * @return Authority
	 */
	private function mockRegisteredAuthorityWithPermissions( array $permissions ): Authority {
		return new SimpleAuthority( new UserIdentityValue( 9999, 'Petr' ), $permissions );
	}

	/**
	 * Create a mock Authority for a $user with $permissions.
	 *
	 * @param UserIdentity $user
	 * @param array $permissions
	 * @return Authority
	 */
	private function mockUserAuthorityWithPermissions(
		UserIdentity $user,
		array $permissions
	): Authority {
		return new SimpleAuthority( $user, $permissions );
	}

	/**
	 * Create a mock Authority for $user with $block and $permissions.
	 *
	 * @param UserIdentity $user
	 * @param Block $block
	 * @param array $permissions
	 *
	 * @return Authority
	 */
	private function mockUserAuthorityWithBlock(
		UserIdentity $user,
		Block $block,
		array $permissions = []
	): Authority {
		return $this->mockAuthority(
			$user,
			static function ( $permission ) use ( $permissions ) {
				return in_array( $permission, $permissions );
			},
			$block
		);
	}

	/**
	 * Create a mock Authority for an anon user with all but $permissions
	 * @param array $permissions
	 * @return Authority
	 */
	private function mockAnonAuthorityWithoutPermissions( array $permissions ): Authority {
		return $this->mockUserAuthorityWithoutPermissions(
			new UserIdentityValue( 0, '127.0.0.1' ),
			$permissions
		);
	}

	/**
	 * Create a mock Authority for a registered user with all but $permissions
	 * @param array $permissions
	 * @return Authority
	 */
	private function mockRegisteredAuthorityWithoutPermissions( array $permissions ): Authority {
		return $this->mockUserAuthorityWithoutPermissions(
			new UserIdentityValue( 9999, 'Petr' ),
			$permissions
		);
	}

	/**
	 * Create a mock Authority for a $user with all but $permissions
	 * @param UserIdentity $user
	 * @param array $permissions
	 * @return Authority
	 */
	private function mockUserAuthorityWithoutPermissions(
		UserIdentity $user,
		array $permissions
	): Authority {
		return $this->mockAuthority(
			$user,
			static function ( $permission ) use ( $permissions ) {
				return !in_array( $permission, $permissions );
			}
		);
	}

	/**
	 * Create mock Authority for anon user where permissions are determined by $callback.
	 *
	 * @param callable $permissionCallback
	 * @return Authority
	 */
	private function mockAnonAuthority( callable $permissionCallback ): Authority {
		return $this->mockAuthority(
			new UserIdentityValue( 0, '127.0.0.1' ),
			$permissionCallback
		);
	}

	/**
	 * Create mock Authority for registered user where permissions are determined by $callback.
	 *
	 * @param callable $permissionCallback
	 * @return Authority
	 */
	private function mockRegisteredAuthority( callable $permissionCallback ): Authority {
		return $this->mockAuthority(
			new UserIdentityValue( 9999, 'Petr' ),
			$permissionCallback
		);
	}

	/**
	 * Create mock Authority for $user where permissions are determined by $callback.
	 *
	 * @param UserIdentity $user
	 * @param callable $permissionCallback ( string $permission, PageIdentity $page = null )
	 * @param Block|null $block
	 *
	 * @return Authority
	 */
	private function mockAuthority(
		UserIdentity $user,
		callable $permissionCallback,
		Block $block = null
	): Authority {
		$mock = $this->createMock( Authority::class );
		$mock->method( 'getUser' )->willReturn( $user );
		$methods = [ 'isAllowed', 'probablyCan', 'definitelyCan', 'authorizeRead', 'authorizeWrite' ];
		foreach ( $methods as $method ) {
			$mock->method( $method )->willReturnCallback( $permissionCallback );
		}
		$mock->method( 'isAllowedAny' )
			->willReturnCallback( static function ( ...$permissions ) use ( $permissionCallback ) {
				foreach ( $permissions as $permission ) {
					if ( $permissionCallback( $permission ) ) {
						return true;
					}
				}
				return false;
			} );
		$mock->method( 'isAllowedAll' )
			->willReturnCallback( static function ( ...$permissions ) use ( $permissionCallback ) {
				foreach ( $permissions as $permission ) {
					if ( !$permissionCallback( $permission ) ) {
						return false;
					}
				}
				return true;
			} );
		$mock->method( 'getBlock' )->willReturn( $block );
		return $mock;
	}
}
