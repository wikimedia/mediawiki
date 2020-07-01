<?php

use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\TemporaryPasswordAuthenticationRequest;
use MediaWiki\Block\CompositeBlock;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\SystemBlock;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MediaWikiServices;
use MediaWiki\Permissions\PermissionManager;
use Psr\Log\NullLogger;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * @covers PasswordReset
 * @group Database
 */
class PasswordResetTest extends MediaWikiIntegrationTestCase {
	private const VALID_IP = '1.2.3.4';
	private const VALID_EMAIL = 'foo@bar.baz';

	/**
	 * @dataProvider provideIsAllowed
	 */
	public function testIsAllowed( $passwordResetRoutes, $enableEmail,
		$allowsAuthenticationDataChange, $canEditPrivate, $block, $globalBlock, $isAllowed
	) {
		$config = $this->makeConfig( $enableEmail, $passwordResetRoutes, false );

		$authManager = $this->getMockBuilder( AuthManager::class )->disableOriginalConstructor()
			->getMock();
		$authManager->expects( $this->any() )->method( 'allowsAuthenticationDataChange' )
			->willReturn( $allowsAuthenticationDataChange ? Status::newGood() : Status::newFatal( 'foo' ) );

		$user = $this->getMockBuilder( User::class )->getMock();
		$user->expects( $this->any() )->method( 'getName' )->willReturn( 'Foo' );
		$user->expects( $this->any() )->method( 'getBlock' )->willReturn( $block );
		$user->expects( $this->any() )->method( 'getGlobalBlock' )->willReturn( $globalBlock );

		$permissionManager = $this->getMockBuilder( PermissionManager::class )
			->disableOriginalConstructor()
			->getMock();
		$permissionManager->method( 'userHasRight' )
			->with( $user, 'editmyprivateinfo' )
			->willReturn( $canEditPrivate );

		$loadBalancer = $this->createMock( ILoadBalancer::class );

		$hookContainer = $this->createHookContainer();

		$passwordReset = new PasswordReset(
			$config,
			$authManager,
			$permissionManager,
			$loadBalancer,
			new NullLogger(),
			$hookContainer
		);

		$this->assertSame( $isAllowed, $passwordReset->isAllowed( $user )->isGood() );
	}

	public function provideIsAllowed() {
		return [
			'no routes' => [
				'passwordResetRoutes' => [],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'block' => null,
				'globalBlock' => null,
				'isAllowed' => false,
			],
			'email disabled' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => false,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'block' => null,
				'globalBlock' => null,
				'isAllowed' => false,
			],
			'auth data change disabled' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => false,
				'canEditPrivate' => true,
				'block' => null,
				'globalBlock' => null,
				'isAllowed' => false,
			],
			'cannot edit private data' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => false,
				'block' => null,
				'globalBlock' => null,
				'isAllowed' => false,
			],
			'blocked with account creation disabled' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'block' => new DatabaseBlock( [ 'createAccount' => true ] ),
				'globalBlock' => null,
				'isAllowed' => false,
			],
			'blocked w/o account creation disabled' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'block' => new DatabaseBlock( [] ),
				'globalBlock' => null,
				'isAllowed' => true,
			],
			'using blocked proxy' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'block' => new SystemBlock(
					[ 'systemBlock' => 'proxy' ]
				),
				'globalBlock' => null,
				'isAllowed' => false,
			],
			'globally blocked with account creation not disabled' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'block' => null,
				'globalBlock' => new SystemBlock(
					[ 'systemBlock' => 'global-block' ]
				),
				'isAllowed' => true,
			],
			'blocked via wgSoftBlockRanges' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'block' => new SystemBlock(
					[ 'systemBlock' => 'wgSoftBlockRanges', 'anonOnly' => true ]
				),
				'globalBlock' => null,
				'isAllowed' => true,
			],
			'blocked with an unknown system block type' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'block' => new SystemBlock( [ 'systemBlock' => 'unknown' ] ),
				'globalBlock' => null,
				'isAllowed' => false,
			],
			'blocked with multiple blocks, all allowing password reset' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'block' => new CompositeBlock( [
					'originalBlocks' => [
						new SystemBlock( [ 'systemBlock' => 'wgSoftBlockRanges', 'anonOnly' => true ] ),
						new Block( [] ),
					]
				] ),
				'globalBlock' => null,
				'isAllowed' => true,
			],
			'blocked with multiple blocks, not all allowing password reset' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'block' => new CompositeBlock( [
					'originalBlocks' => [
						new SystemBlock( [ 'systemBlock' => 'wgSoftBlockRanges', 'anonOnly' => true ] ),
						new SystemBlock( [ 'systemBlock' => 'proxy' ] ),
					]
				] ),
				'globalBlock' => null,
				'isAllowed' => false,
			],
			'all OK' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'block' => null,
				'globalBlock' => null,
				'isAllowed' => true,
			],
		];
	}

	public function testExecute_notAllowed() {
		$user = $this->createMock( User::class );
		/** @var User $user */

		$passwordReset = $this->getMockBuilder( PasswordReset::class )
			->disableOriginalConstructor()
			->setMethods( [ 'isAllowed' ] )
			->getMock();
		$passwordReset->expects( $this->any() )
			->method( 'isAllowed' )
			->with( $user )
			->willReturn( Status::newFatal( 'somestatuscode' ) );
		/** @var PasswordReset $passwordReset */

		$this->expectException( \LogicException::class );
		$passwordReset->execute( $user );
	}

	/**
	 * @dataProvider provideExecute
	 * @param string|bool $expectedError
	 * @param ServiceOptions $config
	 * @param User $performingUser
	 * @param PermissionManager $permissionManager
	 * @param AuthManager $authManager
	 * @param string|null $username
	 * @param string|null $email
	 * @param User[] $usersWithEmail
	 * @covers SendPasswordResetEmailUpdate
	 */
	public function testExecute(
		$expectedError,
		ServiceOptions $config,
		User $performingUser,
		PermissionManager $permissionManager,
		AuthManager $authManager,
		$username = '',
		$email = '',
		array $usersWithEmail = []
	) {
		// Unregister the hooks for proper unit testing
		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'User::mailPasswordInternal' => [],
			'SpecialPasswordResetOnSubmit' => [],
		] );

		$loadBalancer = $this->createMock( ILoadBalancer::class );

		$users = $this->makeUsers();

		$lookupUser = function ( $username ) use ( $users ) {
			return $users[ $username ] ?? false;
		};

		$passwordReset = $this->getMockBuilder( PasswordReset::class )
			->setMethods( [ 'getUsersByEmail', 'isAllowed', 'lookupUser' ] )
			->setConstructorArgs( [
				$config,
				$authManager,
				$permissionManager,
				$loadBalancer,
				new NullLogger(),
				MediaWikiServices::getInstance()->getHookContainer()
			] )
			->getMock();
		$passwordReset->method( 'getUsersByEmail' )->with( $email )
			->willReturn( array_map( $lookupUser, $usersWithEmail ) );
		$passwordReset->method( 'isAllowed' )
			->willReturn( Status::newGood() );
		$passwordReset->method( 'lookupUser' )
			->willReturnCallback( $lookupUser );

		/** @var PasswordReset $passwordReset */
		$status = $passwordReset->execute( $performingUser, $username, $email );
		$this->assertStatus( $status, $expectedError );
	}

	public function provideExecute() {
		$defaultConfig = $this->makeConfig( true, [ 'username' => true, 'email' => true ], false );
		$emailRequiredConfig = $this->makeConfig( true, [ 'username' => true, 'email' => true ], true );
		$performingUser = $this->makePerformingUser( self::VALID_IP, false );
		$throttledUser = $this->makePerformingUser( self::VALID_IP, true );
		$permissionManager = $this->makePermissionManager( $performingUser, true );

		return [
			'Throttled, pretend everything is ok' => [
				'expectedError' => false,
				'config' => $defaultConfig,
				'performingUser' => $throttledUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager(),
				'username' => 'User1',
				'email' => '',
				'usersWithEmail' => [],
			],
			'Throttled, email required for resets, is invalid, pretend everything is ok' => [
				'expectedError' => false,
				'config' => $emailRequiredConfig,
				'performingUser' => $throttledUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager(),
				'username' => 'User1',
				'email' => '[invalid email]',
				'usersWithEmail' => [],
			],
			'Invalid email, pretend everything is OK' => [
				'expectedError' => false,
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager(),
				'username' => '',
				'email' => '[invalid email]',
				'usersWithEmail' => [],
			],
			'No username, no email' => [
				'expectedError' => 'passwordreset-nodata',
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager(),
				'username' => '',
				'email' => '',
				'usersWithEmail' => [],
			],
			'Email route not enabled' => [
				'expectedError' => 'passwordreset-nodata',
				'config' => $this->makeConfig( true, [ 'username' => true ], false ),
				'performingUser' => $performingUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager(),
				'username' => '',
				'email' => self::VALID_EMAIL,
				'usersWithEmail' => [],
			],
			'Username route not enabled' => [
				'expectedError' => 'passwordreset-nodata',
				'config' => $this->makeConfig( true, [ 'email' => true ], false ),
				'performingUser' => $performingUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager(),
				'username' => 'User1',
				'email' => '',
				'usersWithEmail' => [],
			],
			'No routes enabled' => [
				'expectedError' => 'passwordreset-nodata',
				'config' => $this->makeConfig( true, [], false ),
				'performingUser' => $performingUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager(),
				'username' => 'User1',
				'email' => self::VALID_EMAIL,
				'usersWithEmail' => [],
			],
			'Email required for resets but is empty, pretend everything is OK' => [
				'expectedError' => false,
				'config' => $emailRequiredConfig,
				'performingUser' => $performingUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager(),
				'username' => 'User1',
				'email' => '',
				'usersWithEmail' => [],
			],
			'Email required for resets but is invalid, pretend everything is OK' => [
				'expectedError' => false,
				'config' => $emailRequiredConfig,
				'performingUser' => $performingUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager(),
				'username' => 'User1',
				'email' => '[invalid email]',
				'usersWithEmail' => [],
			],
			'Password email already sent within 24 hours, pretend everything is ok' => [
				'expectedError' => false,
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager( [ 'User1' ], 0, [], [ 'User1' ] ),
				'username' => 'User1',
				'email' => '',
				'usersWithEmail' => [ 'User1' ],
			],
			'No user by this username, pretend everything is OK' => [
				'expectedError' => false,
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager(),
				'username' => 'Nonexistent user',
				'email' => '',
				'usersWithEmail' => [],
			],
			'Username is not valid' => [
				'expectedError' => 'noname',
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager(),
				'username' => 'Invalid|username',
				'email' => '',
				'usersWithEmail' => [],
			],
			'If no users with this email found, pretend everything is OK' => [
				'expectedError' => false,
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager(),
				'username' => '',
				'email' => 'some@not.found.email',
				'usersWithEmail' => [],
			],
			'No email for the user, pretend everything is OK' => [
				'expectedError' => false,
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager(),
				'username' => 'BadUser',
				'email' => '',
				'usersWithEmail' => [],
			],
			'Email required for resets, no match' => [
				'expectedError' => false,
				'config' => $emailRequiredConfig,
				'performingUser' => $performingUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager(),
				'username' => 'User1',
				'email' => 'some@other.email',
				'usersWithEmail' => [],
			],
			"Couldn't determine the performing user's IP" => [
				'expectedError' => 'badipaddress',
				'config' => $defaultConfig,
				'performingUser' => $this->makePerformingUser( null, false ),
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager(),
				'username' => 'User1',
				'email' => '',
				'usersWithEmail' => [],
			],
			'User is allowed, but ignored' => [
				'expectedError' => 'passwordreset-ignored',
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager( [ 'User1' ], 0, [ 'User1' ] ),
				'username' => 'User1',
				'email' => '',
				'usersWithEmail' => [],
			],
			'One of users is ignored' => [
				'expectedError' => 'passwordreset-ignored',
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager( [ 'User1', 'User2' ], 0, [ 'User2' ] ),
				'username' => '',
				'email' => self::VALID_EMAIL,
				'usersWithEmail' => [ 'User1', 'User2' ],
			],
			'User is rejected' => [
				'expectedError' => 'rejected by test mock',
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager(),
				'username' => 'User1',
				'email' => '',
				'usersWithEmail' => [],
			],
			'One of users is rejected' => [
				'expectedError' => 'rejected by test mock',
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager( [ 'User1' ] ),
				'username' => '',
				'email' => self::VALID_EMAIL,
				'usersWithEmail' => [ 'User1', 'User2' ],
			],
			'Reset one user via password' => [
				'expectedError' => false,
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager( [ 'User1' ], 1 ),
				'username' => 'User1',
				'email' => self::VALID_EMAIL,
				// Make sure that only the user specified by username is reset
				'usersWithEmail' => [ 'User1', 'User2' ],
			],
			'Reset one user via email' => [
				'expectedError' => false,
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager( [ 'User1' ], 1 ),
				'username' => '',
				'email' => self::VALID_EMAIL,
				'usersWithEmail' => [ 'User1' ],
			],
			'Reset multiple users via email' => [
				'expectedError' => false,
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager( [ 'User1', 'User2' ], 2 ),
				'username' => '',
				'email' => self::VALID_EMAIL,
				'usersWithEmail' => [ 'User1', 'User2' ],
			],
			"Email is required for resets, user didn't opt in" => [
				'expectedError' => false,
				'config' => $emailRequiredConfig,
				'performingUser' => $performingUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager( [ 'User2' ], 1 ),
				'username' => 'User2',
				'email' => self::VALID_EMAIL,
				'usersWithEmail' => [ 'User2' ],
			],
			'Reset three users via email that did not opt in, multiple users with same email' => [
				'expectedError' => false,
				'config' => $emailRequiredConfig,
				'performingUser' => $performingUser,
				'permissionManager' => $permissionManager,
				'authManager' => $this->makeAuthManager( [ 'User2', 'User3', 'User4' ], 3, [ 'User1' ] ),
				'username' => '',
				'email' => self::VALID_EMAIL,
				'usersWithEmail' => [ 'User1', 'User2', 'User3', 'User4' ],
			],
		];
	}

	private function assertStatus( StatusValue $status, $error = false ) {
		if ( $error === false ) {
			$this->assertTrue( $status->isGood(), 'Expected status to be good' );
		} else {
			$this->assertFalse( $status->isGood(), 'Expected status to not be good' );
			if ( is_string( $error ) ) {
				$this->assertNotEmpty( $status->getErrors() );
				$message = $status->getErrors()[0]['message'];
				if ( $message instanceof MessageSpecifier ) {
					$message = $message->getKey();
				}
				$this->assertSame( $error, $message );
			}
		}
	}

	private function makeConfig( $enableEmail, array $passwordResetRoutes, $emailForResets ) {
		$hash = [
			'AllowRequiringEmailForResets' => $emailForResets,
			'EnableEmail' => $enableEmail,
			'PasswordResetRoutes' => $passwordResetRoutes,
		];

		return new ServiceOptions( PasswordReset::CONSTRUCTOR_OPTIONS, $hash );
	}

	/**
	 * @param string|null $ip
	 * @param bool $pingLimited
	 * @return User
	 */
	private function makePerformingUser( $ip, $pingLimited ) : User {
		$request = $this->getMockBuilder( WebRequest::class )
			->getMock();
		$request->method( 'getIP' )
			->willReturn( $ip );
		/** @var WebRequest $request */

		$user = $this->getMockBuilder( User::class )
			->setMethods( [ 'getName', 'pingLimiter', 'getRequest' ] )
			->getMock();

		$user->method( 'getName' )
			->willReturn( 'SomeUser' );
		$user->method( 'pingLimiter' )
			->with( 'mailpassword' )
			->willReturn( $pingLimited );
		$user->method( 'getRequest' )
			->willReturn( $request );

		/** @var User $user */
		return $user;
	}

	private function makePermissionManager( User $performingUser, $isAllowed ) : PermissionManager {
		$permissionManager = $this->getMockBuilder( PermissionManager::class )
			->disableOriginalConstructor()
			->getMock();
		$permissionManager->method( 'userHasRight' )
			->with( $performingUser, 'editmyprivateinfo' )
			->willReturn( $isAllowed );

		/** @var PermissionManager $permissionManager */
		return $permissionManager;
	}

	/**
	 * @param string[] $allowed Usernames that are allowed to send password reset email
	 *  by AuthManager's allowsAuthenticationDataChange method.
	 * @param int $numUsersToAuth Number of users that will receive email
	 * @param string[] $ignored Usernames that are allowed but ignored by AuthManager's
	 *  allowsAuthenticationDataChange method and will not receive password reset email.
	 * @param string[] $mailThrottledLimited Usernames that have already
	 *  received the password reset email within a given time, and AuthManager
	 *  changeAuthenticationData method will mark them as 'throttled-mailpassword.'
	 * @return AuthManager
	 */
	private function makeAuthManager(
		array $allowed = [],
		$numUsersToAuth = 0,
		array $ignored = [],
		array $mailThrottledLimited = []
	) : AuthManager {
		$authManager = $this->getMockBuilder( AuthManager::class )
			->disableOriginalConstructor()
			->getMock();
		$authManager->method( 'allowsAuthenticationDataChange' )
			->willReturnCallback(
				function ( TemporaryPasswordAuthenticationRequest $req )
						use ( $allowed, $ignored, $mailThrottledLimited ) {
					if ( in_array( $req->username, $mailThrottledLimited, true ) ) {
						return Status::newGood( 'throttled-mailpassword' );
					}

					$value = in_array( $req->username, $ignored, true )
						? 'ignored'
						: 'okie dokie';

					return in_array( $req->username, $allowed, true )
						? Status::newGood( $value )
						: Status::newFatal( 'rejected by test mock' );
				} );
		// changeAuthenticationData is executed in the deferred update class
		// SendPasswordResetEmailUpdate
		$authManager->expects( $this->exactly( $numUsersToAuth ) )
			->method( 'changeAuthenticationData' );

		/** @var AuthManager $authManager */
		return $authManager;
	}

	/**
	 * @return User[]
	 */
	private function makeUsers() {
		$user1 = $this->getMockBuilder( User::class )->getMock();
		$user2 = $this->getMockBuilder( User::class )->getMock();
		$user3 = $this->getMockBuilder( User::class )->getMock();
		$user4 = $this->getMockBuilder( User::class )->getMock();
		$user1->method( 'getName' )->willReturn( 'User1' );
		$user2->method( 'getName' )->willReturn( 'User2' );
		$user3->method( 'getName' )->willReturn( 'User3' );
		$user4->method( 'getName' )->willReturn( 'User4' );
		$user1->method( 'getId' )->willReturn( 1 );
		$user2->method( 'getId' )->willReturn( 2 );
		$user3->method( 'getId' )->willReturn( 3 );
		$user4->method( 'getId' )->willReturn( 4 );
		$user1->method( 'getEmail' )->willReturn( self::VALID_EMAIL );
		$user2->method( 'getEmail' )->willReturn( self::VALID_EMAIL );
		$user3->method( 'getEmail' )->willReturn( self::VALID_EMAIL );
		$user4->method( 'getEmail' )->willReturn( self::VALID_EMAIL );

		$user1->method( 'getBoolOption' )
			->with( 'requireemail' )
			->willReturn( true );

		$badUser = $this->getMockBuilder( User::class )->getMock();
		$badUser->method( 'getName' )->willReturn( 'BadUser' );
		$badUser->method( 'getId' )->willReturn( 5 );
		$badUser->method( 'getEmail' )->willReturn( null );

		return [
			'User1' => $user1,
			'User2' => $user2,
			'User3' => $user3,
			'User4' => $user4,
			'BadUser' => $badUser,
		];
	}
}
