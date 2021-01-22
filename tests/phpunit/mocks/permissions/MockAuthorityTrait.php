<?php

namespace MediaWiki\Tests\Unit\Permissions;

use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\SimpleAuthority;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Various useful Authority mocks.
 */
trait MockAuthorityTrait {

	/**
	 * Create mock ultimate Authority for anon user.
	 *
	 * @return Authority
	 */
	private function mockAnonUltimateAuthority(): Authority {
		return new UltimateAuthority( new UserIdentityValue( 0, '127.0.0.1', 0 ) );
	}

	/**
	 * Create mock ultimate Authority for registered user.
	 *
	 * @return Authority
	 */
	private function mockRegisteredUltimateAuthority(): Authority {
		return new UltimateAuthority( new UserIdentityValue( 42, 'Petr', 24 ) );
	}

	/**
	 * Create mock Authority for anon user with no permissions.
	 *
	 * @return Authority
	 */
	private function mockAnonNullAuthority(): Authority {
		return new SimpleAuthority( new UserIdentityValue( 0, '127.0.0.1', 0 ), [] );
	}

	/**
	 * Create mock Authority for a registered user with no permissions.
	 *
	 * @return Authority
	 */
	private function mockRegisteredNullAuthority(): Authority {
		return new UltimateAuthority( new UserIdentityValue( 42, 'Petr', 24 ) );
	}

	/**
	 * Create a mock Authority for anon user with $permissions.
	 *
	 * @param array $permissions
	 * @return Authority
	 */
	private function mockAnonAuthorityWithPermissions( array $permissions ): Authority {
		return new SimpleAuthority( new UserIdentityValue( 0, '127.0.0.1', 0 ), $permissions );
	}

	/**
	 * Create a mock Authority for a registered user with $permissions.
	 *
	 * @param array $permissions
	 * @return Authority
	 */
	private function mockRegisteredAuthorityWithPermissions( array $permissions ): Authority {
		return new SimpleAuthority( new UserIdentityValue( 42, 'Petr', 24 ), $permissions );
	}

	/**
	 * Create mock Authority for anon user where permissions are determined by $callback.
	 *
	 * @param callable $permissionCallback
	 * @return Authority
	 */
	private function mockAnonAuthority( callable $permissionCallback ): Authority {
		return $this->mockAuthority(
			new UserIdentityValue( 0, '127.0.0.1', 0 ),
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
			new UserIdentityValue( 42, 'Petr', 24 ),
			$permissionCallback
		);
	}

	/**
	 * Create mock Authority for $performer where permissions are determined by $callback.
	 *
	 * @param UserIdentity $performer
	 * @param callable $permissionCallback ( string $permission, ?PageIdentity $page )
	 * @return Authority
	 */
	private function mockAuthority( UserIdentity $performer, callable $permissionCallback ): Authority {
		$mock = $this->createMock( Authority::class );
		$mock->method( 'getActor' )->willReturn( $performer );
		$methods = [ 'isAllowed', 'probablyCan', 'definitelyCan', 'authorizeRead', 'authorizeWrite' ];
		foreach ( $methods as $method ) {
			$mock->method( $method )->willReturnCallback( $permissionCallback );
		}
		$mock->method( 'isAllowedAny' )
			->willReturnCallback( function ( ...$permissions ) use ( $permissionCallback ) {
				foreach ( $permissions as $permission ) {
					if ( $permissionCallback( $permission ) ) {
						return true;
					}
				}
				return false;
			} );
		$mock->method( 'isAllowedAll' )
			->willReturnCallback( function ( ...$permissions ) use ( $permissionCallback ) {
				foreach ( $permissions as $permission ) {
					if ( $permissionCallback( $permission ) ) {
						return false;
					}
				}
				return true;
			} );
		return $mock;
	}

	/**
	 * Expected to be provided by the class, probably inherited from TestCase.
	 *
	 * @param string $originalClassName
	 *
	 * @return MockObject
	 */
	abstract protected function createMock( $originalClassName ): MockObject;
}
