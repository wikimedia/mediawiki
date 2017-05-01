<?php

use MediaWiki\Auth\AuthManager;

/**
 * @group Database
 */
class PasswordResetTest extends PHPUnit_Framework_TestCase {
	/**
	 * @dataProvider provideIsAllowed
	 */
	public function testIsAllowed( $passwordResetRoutes, $enableEmail,
		$allowsAuthenticationDataChange, $canEditPrivate, $canSeePassword,
		$userIsBlocked, $isAllowed, $isAllowedToDisplayPassword
	) {
		$config = new HashConfig( [
			'PasswordResetRoutes' => $passwordResetRoutes,
			'EnableEmail' => $enableEmail,
		] );

		$authManager = $this->getMockBuilder( AuthManager::class )->disableOriginalConstructor()
			->getMock();
		$authManager->expects( $this->any() )->method( 'allowsAuthenticationDataChange' )
			->willReturn( $allowsAuthenticationDataChange ? Status::newGood() : Status::newFatal( 'foo' ) );

		$user = $this->getMock( User::class );
		$user->expects( $this->any() )->method( 'getName' )->willReturn( 'Foo' );
		$user->expects( $this->any() )->method( 'isBlocked' )->willReturn( $userIsBlocked );
		$user->expects( $this->any() )->method( 'isAllowed' )
			->will( $this->returnCallback( function ( $perm ) use ( $canEditPrivate, $canSeePassword ) {
				if ( $perm === 'editmyprivateinfo' ) {
					return $canEditPrivate;
				} elseif ( $perm === 'passwordreset' ) {
					return $canSeePassword;
				} else {
					$this->fail( 'Unexpected permission check' );
				}
			} ) );

		$passwordReset = new PasswordReset( $config, $authManager );

		$this->assertSame( $isAllowed, $passwordReset->isAllowed( $user )->isGood() );
		$this->assertSame( $isAllowedToDisplayPassword,
			$passwordReset->isAllowed( $user, true )->isGood() );
	}

	public function provideIsAllowed() {
		return [
			[
				'passwordResetRoutes' => [],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'canSeePassword' => true,
				'userIsBlocked' => false,
				'isAllowed' => false,
				'isAllowedToDisplayPassword' => false,
			],
			[
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => false,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'canSeePassword' => true,
				'userIsBlocked' => false,
				'isAllowed' => false,
				'isAllowedToDisplayPassword' => false,
			],
			[
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => false,
				'canEditPrivate' => true,
				'canSeePassword' => true,
				'userIsBlocked' => false,
				'isAllowed' => false,
				'isAllowedToDisplayPassword' => false,
			],
			[
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => false,
				'canSeePassword' => true,
				'userIsBlocked' => false,
				'isAllowed' => false,
				'isAllowedToDisplayPassword' => false,
			],
			[
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'canSeePassword' => true,
				'userIsBlocked' => true,
				'isAllowed' => false,
				'isAllowedToDisplayPassword' => false,
			],
			[
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'canSeePassword' => false,
				'userIsBlocked' => false,
				'isAllowed' => true,
				'isAllowedToDisplayPassword' => false,
			],
			[
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'canSeePassword' => true,
				'userIsBlocked' => false,
				'isAllowed' => true,
				'isAllowedToDisplayPassword' => true,
			],
		];
	}

	public function testExecute_email() {
		$config = new HashConfig( [
			'PasswordResetRoutes' => [ 'username' => true, 'email' => true ],
			'EnableEmail' => true,
		] );

		$authManager = $this->getMockBuilder( AuthManager::class )->disableOriginalConstructor()
			->getMock();
		$authManager->expects( $this->any() )->method( 'allowsAuthenticationDataChange' )
			->willReturn( Status::newGood() );
		$authManager->expects( $this->exactly( 2 ) )->method( 'changeAuthenticationData' );

		$request = new FauxRequest();
		$request->setIP( '1.2.3.4' );
		$performingUser = $this->getMock( User::class );
		$performingUser->expects( $this->any() )->method( 'getRequest' )->willReturn( $request );
		$performingUser->expects( $this->any() )->method( 'isAllowed' )->willReturn( true );

		$targetUser1 = $this->getMock( User::class );
		$targetUser2 = $this->getMock( User::class );
		$targetUser1->expects( $this->any() )->method( 'getName' )->willReturn( 'User1' );
		$targetUser2->expects( $this->any() )->method( 'getName' )->willReturn( 'User2' );
		$targetUser1->expects( $this->any() )->method( 'getId' )->willReturn( 1 );
		$targetUser2->expects( $this->any() )->method( 'getId' )->willReturn( 2 );
		$targetUser1->expects( $this->any() )->method( 'getEmail' )->willReturn( 'foo@bar.baz' );
		$targetUser2->expects( $this->any() )->method( 'getEmail' )->willReturn( 'foo@bar.baz' );

		$passwordReset = $this->getMockBuilder( PasswordReset::class )
			->setMethods( [ 'getUsersByEmail' ] )->setConstructorArgs( [ $config, $authManager ] )
			->getMock();
		$passwordReset->expects( $this->any() )->method( 'getUsersByEmail' )->with( 'foo@bar.baz' )
			->willReturn( [ $targetUser1, $targetUser2 ] );

		$status = $passwordReset->isAllowed( $performingUser );
		$this->assertTrue( $status->isGood() );

		$status = $passwordReset->execute( $performingUser, null, 'foo@bar.baz' );
		$this->assertTrue( $status->isGood() );
	}
}
