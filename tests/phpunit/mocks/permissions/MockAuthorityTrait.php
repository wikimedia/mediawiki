<?php

namespace MediaWiki\Tests\Unit\Permissions;

use MediaWiki\Block\Block;
use MediaWiki\Block\BlockErrorFormatter;
use MediaWiki\Block\SystemBlock;
use MediaWiki\Context\IContextSource;
use MediaWiki\Language\Language;
use MediaWiki\Message\Message;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Permissions\RateLimiter;
use MediaWiki\Permissions\RateLimitSubject;
use MediaWiki\Permissions\SimpleAuthority;
use MediaWiki\Permissions\UltimateAuthority;
use MediaWiki\Permissions\UserAuthority;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\WebRequest;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityValue;
use PHPUnit\Framework\MockObject\MockObject;
use StatusValue;
use Wikimedia\Message\MessageValue;

/**
 * Various useful Authority mocks.
 * @stable to use (since 1.37)
 */
trait MockAuthorityTrait {

	/**
	 * Create mock ultimate Authority for anon user.
	 */
	private function mockAnonUltimateAuthority(): Authority {
		return new UltimateAuthority( new UserIdentityValue( 0, '127.0.0.1' ) );
	}

	/**
	 * Create mock ultimate Authority for a temp user.
	 */
	private function mockTempUltimateAuthority(): Authority {
		return new UltimateAuthority( new UserIdentityValue( 42, '~2024-1' ), true );
	}

	/**
	 * Create mock ultimate Authority for registered user.
	 */
	private function mockRegisteredUltimateAuthority(): Authority {
		return new UltimateAuthority( new UserIdentityValue( 9999, 'Petr' ) );
	}

	/**
	 * Create mock Authority for anon user with no permissions.
	 */
	private function mockAnonNullAuthority(): Authority {
		return new SimpleAuthority( new UserIdentityValue( 0, '127.0.0.1' ), [] );
	}

	/**
	 * Create mock Authority for a temp user with no permissions.
	 */
	private function mockTempNullAuthority(): Authority {
		return new SimpleAuthority( new UserIdentityValue( 42, '~2024-1' ), [], true );
	}

	/**
	 * Create mock Authority for a registered user with no permissions.
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
	 * Create a mock Authority for a temp user with $permissions.
	 *
	 * @param array $permissions
	 * @return Authority
	 */
	private function mockTempAuthorityWithPermissions( array $permissions ): Authority {
		return new SimpleAuthority( new UserIdentityValue( 42, '~2024-1' ), $permissions, true );
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
	 * @param bool $isTemp
	 * @return Authority
	 */
	private function mockUserAuthorityWithPermissions(
		UserIdentity $user,
		array $permissions,
		bool $isTemp = false
	): Authority {
		return new SimpleAuthority( $user, $permissions, $isTemp );
	}

	/**
	 * Create a mock Authority for $user with $block and $permissions.
	 *
	 * @param UserIdentity $user
	 * @param Block $block
	 * @param array $permissions
	 * @param bool $isTemp
	 *
	 * @return Authority
	 */
	private function mockUserAuthorityWithBlock(
		UserIdentity $user,
		Block $block,
		array $permissions = [],
		bool $isTemp = false
	): Authority {
		return $this->mockAuthority(
			$user,
			static function ( $permission ) use ( $permissions ) {
				return in_array( $permission, $permissions );
			},
			$block,
			$isTemp
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
	 * Create a mock Authority for a temp user with all but $permissions
	 * @param array $permissions
	 * @return Authority
	 */
	private function mockTempAuthorityWithoutPermissions( array $permissions ): Authority {
		return $this->mockUserAuthorityWithoutPermissions(
			new UserIdentityValue( 42, '~2024-1' ),
			$permissions,
			true
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
	 * @param bool $isTemp
	 * @return Authority
	 */
	private function mockUserAuthorityWithoutPermissions(
		UserIdentity $user,
		array $permissions,
		bool $isTemp = false
	): Authority {
		return $this->mockAuthority(
			$user,
			static function ( $permission ) use ( $permissions ) {
				return !in_array( $permission, $permissions );
			},
			null,
			$isTemp
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
	 * Create mock Authority for a temp user where permissions are determined by $callback.
	 *
	 * @param callable $permissionCallback
	 * @return Authority
	 */
	private function mockTempAuthority( callable $permissionCallback ): Authority {
		return $this->mockAuthority(
			new UserIdentityValue( 42, '~2024-1' ),
			$permissionCallback,
			null,
			true
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
	 * @param callable $permissionCallback ( string $permission, ?PageIdentity $page = null, ?PermissionStatus $status = null )
	 * @param Block|null $block
	 * @param bool $isTemp
	 *
	 * @return Authority
	 */
	private function mockAuthority(
		UserIdentity $user,
		callable $permissionCallback,
		?Block $block = null,
		bool $isTemp = false
	): Authority {
		$mock = $this->createMock( Authority::class );
		$mock->method( 'getUser' )->willReturn( $user );
		$methodsWithTitle = [ 'probablyCan', 'definitelyCan', 'authorizeRead', 'authorizeWrite' ];
		$methodsWithoutTitle = [ 'isDefinitelyAllowed', 'authorizeAction' ];
		foreach ( $methodsWithTitle as $method ) {
			$mock->method( $method )->willReturnCallback( static function (
				string $permission, PageIdentity $target, ?PermissionStatus $status = null
			) use ( $block, $permissionCallback ) {
				$ok = $permissionCallback( $permission, $target, $status );

				if ( !$ok && $status ) {
					$status->fatal( 'permissionserrors' );
					$status->setPermission( $permission );
				}
				if ( $block && $status ) {
					$status->fatal( 'blockedtext' );
					$status->setBlock( $block );
				}

				return $ok && !$block;
			} );
		}
		foreach ( $methodsWithoutTitle as $method ) {
			$mock->method( $method )->willReturnCallback( static function (
				string $permission, ?PermissionStatus $status = null
			) use ( $permissionCallback, $block ) {
				$ok = $permissionCallback( $permission, null, $status );

				if ( !$ok && $status ) {
					$status->fatal( 'permissionserrors' );
					$status->setPermission( $permission );
				}
				if ( $block && $status ) {
					$status->fatal( 'blockedtext' );
					$status->setBlock( $block );
				}

				return $ok && !$block;
			} );
		}
		$mock->method( 'isAllowed' )
			->willReturnCallback( static function (
				string $permission, ?PermissionStatus $status = null
			) use ( $permissionCallback ) {
				$ok = $permissionCallback( $permission, null, $status );

				if ( !$ok && $status ) {
					$status->fatal( 'permissionserrors' );
					$status->setPermission( $permission );
				}

				return $ok;
			} );
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
		$mock->method( 'isRegistered' )->willReturn( $user->isRegistered() );
		$mock->method( 'isTemp' )->willReturn( $isTemp );
		$mock->method( 'isNamed' )->willReturn( $user->isRegistered() && !$isTemp );
		return $mock;
	}

	/** @return string[] Some dummy message parameters to test error message formatting. */
	private function getFakeBlockMessageParams(): array {
		return [
			'[[User:Blocker|Blocker]]',
			'Block reason that can contain {{templates}}',
			'192.168.0.1',
			'Blocker',
		];
	}

	/**
	 * @param bool $limited
	 * @return RateLimiter
	 */
	private function newRateLimiter( $limited = false ): RateLimiter {
		/** @var RateLimiter&MockObject $rateLimiter */
		$rateLimiter = $this->createNoOpMock(
			RateLimiter::class,
			[ 'limit', 'isLimitable' ]
		);

		$rateLimiter->method( 'limit' )->willReturn( $limited );
		$rateLimiter->method( 'isLimitable' )->willReturn( true );

		return $rateLimiter;
	}

	/**
	 * @param string[] $permissions
	 * @return PermissionManager
	 */
	private function newPermissionsManager( array $permissions ): PermissionManager {
		/** @var PermissionManager&MockObject $permissionManager */
		$permissionManager = $this->createNoOpMock(
			PermissionManager::class,
			[
				'userHasRight',
				'userHasAnyRight',
				'userHasAllRights',
				'userCan',
				'getPermissionStatus',
				'getPermissionErrors',
				'isBlockedFrom',
				'getApplicableBlock',
				'newFatalPermissionDeniedStatus',
			]
		);

		$permissionManager->method( 'userHasRight' )->willReturnCallback(
			static function ( $user, $permission ) use ( $permissions ) {
				return in_array( $permission, $permissions );
			}
		);

		$permissionManager->method( 'userHasAnyRight' )->willReturnCallback(
			static function ( $user, ...$actions ) use ( $permissions ) {
				return array_diff( $actions, $permissions ) != $actions;
			}
		);

		$permissionManager->method( 'userHasAllRights' )->willReturnCallback(
			static function ( $user, ...$actions ) use ( $permissions ) {
				return !array_diff( $actions, $permissions );
			}
		);

		$permissionManager->method( 'userCan' )->willReturnCallback(
			static function ( $permission, $user ) use ( $permissionManager ) {
				return $permissionManager->userHasRight( $user, $permission );
			}
		);

		$fakeBlockMessageParams = $this->getFakeBlockMessageParams();
		// If the user has a block, the block applies to all actions except for 'read'
		$permissionManager->method( 'getPermissionStatus' )->willReturnCallback(
			static function ( $permission, $user, $target ) use ( $permissionManager, $fakeBlockMessageParams ) {
				$status = PermissionStatus::newEmpty();
				if ( !$permissionManager->userCan( $permission, $user, $target ) ) {
					$status->fatal( 'permissionserrors' );
				}
				if ( $user->getBlock() && $permission !== 'read' ) {
					$status->setBlock( $user->getBlock() );
					$status->fatal( MessageValue::new( 'blockedtext-partial', $fakeBlockMessageParams ) );
				}
				return $status;
			}
		);

		$permissionManager->method( 'newFatalPermissionDeniedStatus' )->willReturnCallback(
			static function ( $permission, $context ) use ( $permissionManager ) {
				return StatusValue::newFatal( 'permissionserrors' );
			}
		);

		// If the page's title is "Forbidden", will return a SystemBlock. Likewise,
		// if the action is 'blocked', this will return a SystemBlock.
		$permissionManager->method( 'getApplicableBlock' )->willReturnCallback(
			static function ( $action, User $user, $rigor, $page ) {
				if ( $page && $page->getDBkey() === 'Forbidden' ) {
					return new SystemBlock();
				}

				if ( $action === 'blocked' ) {
					return new SystemBlock();
				}

				return null;
			}
		);

		$permissionManager->method( 'isBlockedFrom' )->willReturnCallback(
			static function ( User $user, $page ) {
				return $page->getDBkey() === 'Forbidden';
			}
		);

		return $permissionManager;
	}

	private function newUser( ?Block $block = null, bool $isTemp = false ): User {
		/** @var User&MockObject $actor */
		$actor = $this->createNoOpMock( User::class, [ 'getBlock', 'isNewbie', 'toRateLimitSubject' ] );
		$actor->method( 'getBlock' )->willReturn( $block );
		$actor->method( 'isNewbie' )->willReturn( false );
		$actor->method( 'isTemp' )->willReturn( $isTemp );
		$actor->method( 'isNamed' )->willReturn( !$isTemp );

		$subject = new RateLimitSubject( $actor, '::1', [] );
		$actor->method( 'toRateLimitSubject' )->willReturn( $subject );
		return $actor;
	}

	private function newBlockErrorFormatter(): BlockErrorFormatter {
		$blockErrorFormatter = $this->createNoOpMock( BlockErrorFormatter::class, [ 'getMessages' ] );
		$blockErrorFormatter->method( 'getMessages' )->willReturn( [ new Message( 'blocked' ) ] );
		return $blockErrorFormatter;
	}

	private function newContext(): IContextSource {
		$language = $this->createNoOpMock( Language::class, [ 'getCode' ] );
		$language->method( 'getCode' )->willReturn( 'en' );

		$context = $this->createNoOpMock( IContextSource::class, [ 'getLanguage' ] );
		$context->method( 'getLanguage' )->willReturn( $language );
		return $context;
	}

	private function newRequest(): WebRequest {
		$request = new FauxRequest();
		$request->setIP( '1.2.3.4' );
		return $request;
	}

	private function newUserAuthority( array $options = [] ): UserAuthority {
		$permissionManager = $options['permissionManager']
			?? $this->newPermissionsManager( $options['permissions'] ?? [] );

		$rateLimiter = $options['rateLimiter']
			?? $this->newRateLimiter( $options['limited'] ?? false );

		$blockErrorFormatter = $options['blockErrorFormatter']
			?? $this->newBlockErrorFormatter();

		return new UserAuthority(
			$options['actor'] ?? $this->newUser(),
			$options['request'] ?? $this->newRequest(),
			$options['context'] ?? $this->newContext(),
			$permissionManager,
			$rateLimiter,
			$blockErrorFormatter
		);
	}
}
