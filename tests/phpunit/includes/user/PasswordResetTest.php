<?php

use MediaWiki\Auth\AuthManager;

/**
 * @covers PasswordReset
 * @group Database
 */
class PasswordResetTest extends MediaWikiTestCase {
	/**
	 * @dataProvider provideIsAllowed
	 */
	public function testIsAllowed( $passwordResetRoutes, $enableEmail,
		$allowsAuthenticationDataChange, $canEditPrivate, $block, $globalBlock, $isAllowed
	) {
		$config = new HashConfig( [
			'PasswordResetRoutes' => $passwordResetRoutes,
			'EnableEmail' => $enableEmail,
		] );

		$authManager = $this->getMockBuilder( AuthManager::class )->disableOriginalConstructor()
			->getMock();
		$authManager->expects( $this->any() )->method( 'allowsAuthenticationDataChange' )
			->willReturn( $allowsAuthenticationDataChange ? Status::newGood() : Status::newFatal( 'foo' ) );

		$user = $this->getMockBuilder( User::class )->getMock();
		$user->expects( $this->any() )->method( 'getName' )->willReturn( 'Foo' );
		$user->expects( $this->any() )->method( 'getBlock' )->willReturn( $block );
		$user->expects( $this->any() )->method( 'getGlobalBlock' )->willReturn( $globalBlock );
		$user->expects( $this->any() )->method( 'isAllowed' )
			->will( $this->returnCallback( function ( $perm ) use ( $canEditPrivate ) {
				if ( $perm === 'editmyprivateinfo' ) {
					return $canEditPrivate;
				} else {
					$this->fail( 'Unexpected permission check' );
				}
			} ) );

		$passwordReset = new PasswordReset( $config, $authManager );

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
				'block' => new Block( [ 'createAccount' => true ] ),
				'globalBlock' => null,
				'isAllowed' => false,
			],
			'blocked w/o account creation disabled' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'block' => new Block( [] ),
				'globalBlock' => null,
				'isAllowed' => true,
			],
			'using blocked proxy' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'block' => new Block( [ 'systemBlock' => 'proxy' ] ),
				'globalBlock' => null,
				'isAllowed' => false,
			],
			'globally blocked with account creation disabled' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'block' => null,
				'globalBlock' => new Block( [ 'systemBlock' => 'global-block', 'createAccount' => true ] ),
				'isAllowed' => false,
			],
			'globally blocked with account creation not disabled' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'block' => null,
				'globalBlock' => new Block( [ 'systemBlock' => 'global-block', 'createAccount' => false ] ),
				'isAllowed' => true,
			],
			'blocked via wgSoftBlockRanges' => [
				'passwordResetRoutes' => [ 'username' => true ],
				'enableEmail' => true,
				'allowsAuthenticationDataChange' => true,
				'canEditPrivate' => true,
				'block' => new Block( [ 'systemBlock' => 'wgSoftBlockRanges', 'anonOnly' => true ] ),
				'globalBlock' => null,
				'isAllowed' => true,
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

	public function testExecute_email() {
		$config = new HashConfig( [
			'PasswordResetRoutes' => [ 'username' => true, 'email' => true ],
			'EnableEmail' => true,
		] );

		// Unregister the hooks for proper unit testing
		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'User::mailPasswordInternal' => [],
			'SpecialPasswordResetOnSubmit' => [],
		] );

		$authManager = $this->getMockBuilder( AuthManager::class )->disableOriginalConstructor()
			->getMock();
		$authManager->expects( $this->any() )->method( 'allowsAuthenticationDataChange' )
			->willReturn( Status::newGood() );
		$authManager->expects( $this->exactly( 2 ) )->method( 'changeAuthenticationData' );

		$request = new FauxRequest();
		$request->setIP( '1.2.3.4' );
		$performingUser = $this->getMockBuilder( User::class )->getMock();
		$performingUser->expects( $this->any() )->method( 'getRequest' )->willReturn( $request );
		$performingUser->expects( $this->any() )->method( 'isAllowed' )->willReturn( true );

		$targetUser1 = $this->getMockBuilder( User::class )->getMock();
		$targetUser2 = $this->getMockBuilder( User::class )->getMock();
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
