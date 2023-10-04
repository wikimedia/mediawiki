<?php

use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\TemporaryPasswordAuthenticationRequest;
use MediaWiki\Block\CompositeBlock;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\SystemBlock;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Request\WebRequest;
use MediaWiki\Status\Status;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\User\StaticUserOptionsLookup;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserNameUtils;
use MediaWiki\User\UserOptionsLookup;
use Psr\Log\NullLogger;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * TODO make this a unit test, all dependencies are injected, but DatabaseBlock::__construct()
 * can't be used in unit tests.
 *
 * @covers PasswordReset
 * @group Database
 */
class PasswordResetTest extends MediaWikiIntegrationTestCase {
	use DummyServicesTrait;

	private const VALID_IP = '1.2.3.4';
	private const VALID_EMAIL = 'foo@bar.baz';

	/**
	 * @dataProvider provideIsAllowed
	 */
	public function testIsAllowed( $passwordResetRoutes, $enableEmail,
		$allowsAuthenticationDataChange, $canEditPrivate, $block, $isAllowed
	) {
		$config = $this->makeConfig( $enableEmail, $passwordResetRoutes, false );

		$authManager = $this->createMock( AuthManager::class );
		$authManager->method( 'allowsAuthenticationDataChange' )
			->willReturn( $allowsAuthenticationDataChange ? Status::newGood() : Status::newFatal( 'foo' ) );

		$user = $this->createMock( User::class );
		$user->method( 'getName' )->willReturn( 'Foo' );
		$user->method( 'getBlock' )->willReturn( $block );
		$user->method( 'isAllowed' )->with( 'editmyprivateinfo' )->willReturn( $canEditPrivate );

		$passwordReset = new PasswordReset(
			$config,
			new NullLogger(),
			$authManager,
			$this->createHookContainer(),
			$this->createNoOpMock( IConnectionProvider::class ),
			$this->createNoOpMock( UserFactory::class ),
			$this->createNoOpMock( UserNameUtils::class ),
			$this->createNoOpMock( UserOptionsLookup::class )
		);

		$this->assertSame( $isAllowed, $passwordReset->isAllowed( $user )->isGood() );
	}

	public static function provideIsAllowed() {
		return [
			'no routes' => [
				'passwordResetRoutes' => [],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'block' => null,
				'isAllowed' => false,
			],
			'email disabled' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => false,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'block' => null,
				'isAllowed' => false,
			],
			'auth data change disabled' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => false,
				'canEditPrivate' => true,
				'block' => null,
				'isAllowed' => false,
			],
			'cannot edit private data' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => false,
				'block' => null,
				'isAllowed' => false,
			],
			'blocked with account creation disabled' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'block' => new DatabaseBlock( [ 'createAccount' => true ] ),
				'isAllowed' => false,
			],
			'blocked w/o account creation disabled' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'block' => new DatabaseBlock( [] ),
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
				'isAllowed' => false,
			],
			'globally blocked with account creation not disabled' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'block' => null,
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
				'isAllowed' => true,
			],
			'blocked with an unknown system block type' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'block' => new SystemBlock( [ 'systemBlock' => 'unknown' ] ),
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
						new DatabaseBlock( [] ),
					]
				] ),
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
				'isAllowed' => false,
			],
			'all OK' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'block' => null,
				'isAllowed' => true,
			],
		];
	}

	public function testExecute_notAllowed() {
		$user = $this->createMock( User::class );
		/** @var User $user */

		$passwordReset = $this->getMockBuilder( PasswordReset::class )
			->disableOriginalConstructor()
			->onlyMethods( [ 'isAllowed' ] )
			->getMock();
		$passwordReset->method( 'isAllowed' )
			->with( $user )
			->willReturn( Status::newFatal( 'somestatuscode' ) );
		/** @var PasswordReset $passwordReset */

		$this->expectException( LogicException::class );
		$passwordReset->execute( $user );
	}

	/**
	 * @dataProvider provideExecute
	 * @param string|bool $expectedError
	 * @param ServiceOptions $config
	 * @param User $performingUser
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
		AuthManager $authManager,
		$username = '',
		$email = '',
		array $usersWithEmail = []
	) {
		$users = $this->makeUsers();

		// Only User1 has `requireemail` true, everything else false (so that is the default)
		$userOptionsLookup = new StaticUserOptionsLookup(
			[ 'User1' => [ 'requireemail' => true ] ],
			[ 'requireemail' => false ]
		);

		// Similar to $lookupUser callback, but with null instead of false
		$userFactory = $this->createMock( UserFactory::class );
		$userFactory->method( 'newFromName' )
			->willReturnCallback(
				static function ( $username ) use ( $users ) {
					return $users[ $username ] ?? null;
				}
			);

		$lookupUser = static function ( $username ) use ( $users ) {
			return $users[ $username ] ?? false;
		};

		$passwordReset = $this->getMockBuilder( PasswordReset::class )
			->onlyMethods( [ 'getUsersByEmail', 'isAllowed' ] )
			->setConstructorArgs( [
				$config,
				new NullLogger(),
				$authManager,
				$this->createHookContainer(),
				$this->createNoOpMock( IConnectionProvider::class ),
				$userFactory,
				$this->getDummyUserNameUtils(),
				$userOptionsLookup
			] )
			->getMock();
		$passwordReset->method( 'getUsersByEmail' )->with( $email )
			->willReturn( array_map( $lookupUser, $usersWithEmail ) );
		$passwordReset->method( 'isAllowed' )
			->willReturn( Status::newGood() );

		/** @var PasswordReset $passwordReset */
		$status = $passwordReset->execute( $performingUser, $username, $email );

		if ( is_string( $expectedError ) ) {
			$this->assertStatusError( $expectedError, $status );
		} elseif ( $expectedError ) {
			$this->assertStatusNotOk( $status );
		} else {
			$this->assertStatusGood( $status );
		}
	}

	public function provideExecute() {
		$defaultConfig = $this->makeConfig( true, [ 'username' => true, 'email' => true ], false );
		$emailRequiredConfig = $this->makeConfig( true, [ 'username' => true, 'email' => true ], true );
		$performingUser = $this->makePerformingUser( self::VALID_IP, false );
		$throttledUser = $this->makePerformingUser( self::VALID_IP, true );

		return [
			'Throttled, pretend everything is ok' => [
				'expectedError' => false,
				'config' => $defaultConfig,
				'performingUser' => $throttledUser,
				'authManager' => $this->makeAuthManager(),
				'username' => 'User1',
				'email' => '',
				'usersWithEmail' => [],
			],
			'Throttled, email required for resets, is invalid, pretend everything is ok' => [
				'expectedError' => false,
				'config' => $emailRequiredConfig,
				'performingUser' => $throttledUser,
				'authManager' => $this->makeAuthManager(),
				'username' => 'User1',
				'email' => '[invalid email]',
				'usersWithEmail' => [],
			],
			'Invalid email, pretend everything is OK' => [
				'expectedError' => false,
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'authManager' => $this->makeAuthManager(),
				'username' => '',
				'email' => '[invalid email]',
				'usersWithEmail' => [],
			],
			'No username, no email' => [
				'expectedError' => 'passwordreset-nodata',
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'authManager' => $this->makeAuthManager(),
				'username' => '',
				'email' => '',
				'usersWithEmail' => [],
			],
			'Email route not enabled' => [
				'expectedError' => 'passwordreset-nodata',
				'config' => $this->makeConfig( true, [ 'username' => true ], false ),
				'performingUser' => $performingUser,
				'authManager' => $this->makeAuthManager(),
				'username' => '',
				'email' => self::VALID_EMAIL,
				'usersWithEmail' => [],
			],
			'Username route not enabled' => [
				'expectedError' => 'passwordreset-nodata',
				'config' => $this->makeConfig( true, [ 'email' => true ], false ),
				'performingUser' => $performingUser,
				'authManager' => $this->makeAuthManager(),
				'username' => 'User1',
				'email' => '',
				'usersWithEmail' => [],
			],
			'No routes enabled' => [
				'expectedError' => 'passwordreset-nodata',
				'config' => $this->makeConfig( true, [], false ),
				'performingUser' => $performingUser,
				'authManager' => $this->makeAuthManager(),
				'username' => 'User1',
				'email' => self::VALID_EMAIL,
				'usersWithEmail' => [],
			],
			'Email required for resets but is empty, pretend everything is OK' => [
				'expectedError' => false,
				'config' => $emailRequiredConfig,
				'performingUser' => $performingUser,
				'authManager' => $this->makeAuthManager(),
				'username' => 'User1',
				'email' => '',
				'usersWithEmail' => [],
			],
			'Email required for resets but is invalid, pretend everything is OK' => [
				'expectedError' => false,
				'config' => $emailRequiredConfig,
				'performingUser' => $performingUser,
				'authManager' => $this->makeAuthManager(),
				'username' => 'User1',
				'email' => '[invalid email]',
				'usersWithEmail' => [],
			],
			'Password email already sent within 24 hours, pretend everything is ok' => [
				'expectedError' => false,
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'authManager' => $this->makeAuthManager( [ 'User1' ], 0, [], [ 'User1' ] ),
				'username' => 'User1',
				'email' => '',
				'usersWithEmail' => [ 'User1' ],
			],
			'No user by this username, pretend everything is OK' => [
				'expectedError' => false,
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'authManager' => $this->makeAuthManager(),
				'username' => 'Nonexistent user',
				'email' => '',
				'usersWithEmail' => [],
			],
			'Username is not valid' => [
				'expectedError' => 'noname',
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'authManager' => $this->makeAuthManager(),
				'username' => 'Invalid|username',
				'email' => '',
				'usersWithEmail' => [],
			],
			'If no users with this email found, pretend everything is OK' => [
				'expectedError' => false,
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'authManager' => $this->makeAuthManager(),
				'username' => '',
				'email' => 'some@not.found.email',
				'usersWithEmail' => [],
			],
			'No email for the user, pretend everything is OK' => [
				'expectedError' => false,
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'authManager' => $this->makeAuthManager(),
				'username' => 'BadUser',
				'email' => '',
				'usersWithEmail' => [],
			],
			'Email required for resets, no match' => [
				'expectedError' => false,
				'config' => $emailRequiredConfig,
				'performingUser' => $performingUser,
				'authManager' => $this->makeAuthManager(),
				'username' => 'User1',
				'email' => 'some@other.email',
				'usersWithEmail' => [],
			],
			"Couldn't determine the performing user's IP" => [
				'expectedError' => 'badipaddress',
				'config' => $defaultConfig,
				'performingUser' => $this->makePerformingUser( '', false ),
				'authManager' => $this->makeAuthManager(),
				'username' => 'User1',
				'email' => '',
				'usersWithEmail' => [],
			],
			'User is allowed, but ignored' => [
				'expectedError' => 'passwordreset-ignored',
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'authManager' => $this->makeAuthManager( [ 'User1' ], 0, [ 'User1' ] ),
				'username' => 'User1',
				'email' => '',
				'usersWithEmail' => [],
			],
			'One of users is ignored' => [
				'expectedError' => 'passwordreset-ignored',
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'authManager' => $this->makeAuthManager( [ 'User1', 'User2' ], 0, [ 'User2' ] ),
				'username' => '',
				'email' => self::VALID_EMAIL,
				'usersWithEmail' => [ 'User1', 'User2' ],
			],
			'User is rejected' => [
				'expectedError' => 'rejected by test mock',
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'authManager' => $this->makeAuthManager(),
				'username' => 'User1',
				'email' => '',
				'usersWithEmail' => [],
			],
			'One of users is rejected' => [
				'expectedError' => 'rejected by test mock',
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'authManager' => $this->makeAuthManager( [ 'User1' ] ),
				'username' => '',
				'email' => self::VALID_EMAIL,
				'usersWithEmail' => [ 'User1', 'User2' ],
			],
			'Reset one user via password' => [
				'expectedError' => false,
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
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
				'authManager' => $this->makeAuthManager( [ 'User1' ], 1 ),
				'username' => '',
				'email' => self::VALID_EMAIL,
				'usersWithEmail' => [ 'User1' ],
			],
			'Reset multiple users via email' => [
				'expectedError' => false,
				'config' => $defaultConfig,
				'performingUser' => $performingUser,
				'authManager' => $this->makeAuthManager( [ 'User1', 'User2' ], 2 ),
				'username' => '',
				'email' => self::VALID_EMAIL,
				'usersWithEmail' => [ 'User1', 'User2' ],
			],
			"Email is required for resets, user didn't opt in" => [
				'expectedError' => false,
				'config' => $emailRequiredConfig,
				'performingUser' => $performingUser,
				'authManager' => $this->makeAuthManager( [ 'User2' ], 1 ),
				'username' => 'User2',
				'email' => self::VALID_EMAIL,
				'usersWithEmail' => [ 'User2' ],
			],
			'Reset three users via email that did not opt in, multiple users with same email' => [
				'expectedError' => false,
				'config' => $emailRequiredConfig,
				'performingUser' => $performingUser,
				'authManager' => $this->makeAuthManager( [ 'User2', 'User3', 'User4' ], 3, [ 'User1' ] ),
				'username' => '',
				'email' => self::VALID_EMAIL,
				'usersWithEmail' => [ 'User1', 'User2', 'User3', 'User4' ],
			],
		];
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
	 * @param string $ip
	 * @param bool $pingLimited
	 * @return User
	 */
	private function makePerformingUser( string $ip, $pingLimited ): User {
		$request = $this->createMock( WebRequest::class );
		$request->method( 'getIP' )
			->willReturn( $ip );
		/** @var WebRequest $request */

		$user = $this->getMockBuilder( User::class )
			->onlyMethods( [ 'getName', 'pingLimiter', 'getRequest', 'isAllowed' ] )
			->getMock();

		$user->method( 'getName' )
			->willReturn( 'SomeUser' );
		$user->method( 'pingLimiter' )
			->with( 'mailpassword' )
			->willReturn( $pingLimited );
		$user->method( 'getRequest' )
			->willReturn( $request );

		// Always has the relevant rights, just checking based on rate limits
		$user->method( 'isAllowed' )->with( 'editmyprivateinfo' )->willReturn( true );

		/** @var User $user */
		return $user;
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
	): AuthManager {
		$authManager = $this->createMock( AuthManager::class );
		$authManager->method( 'allowsAuthenticationDataChange' )
			->willReturnCallback(
				static function ( TemporaryPasswordAuthenticationRequest $req )
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
		$getGoodUserCb = function ( int $num ) {
			$user = $this->createMock( User::class );
			$user->method( 'getName' )->willReturn( "User$num" );
			$user->method( 'getId' )->willReturn( $num );
			$user->method( 'isRegistered' )->willReturn( true );
			$user->method( 'getEmail' )->willReturn( self::VALID_EMAIL );
			return $user;
		};
		$user1 = $getGoodUserCb( 1 );
		$user2 = $getGoodUserCb( 2 );
		$user3 = $getGoodUserCb( 3 );
		$user4 = $getGoodUserCb( 4 );

		$badUser = $this->createMock( User::class );
		$badUser->method( 'getName' )->willReturn( 'BadUser' );
		$badUser->method( 'getId' )->willReturn( 5 );
		$badUser->method( 'isRegistered' )->willReturn( true );
		$badUser->method( 'getEmail' )->willReturn( '' );

		return [
			'User1' => $user1,
			'User2' => $user2,
			'User3' => $user3,
			'User4' => $user4,
			'BadUser' => $badUser,
		];
	}
}
