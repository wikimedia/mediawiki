<?php

namespace MediaWiki\Auth;

use MediaWiki\MediaWikiServices;
use Wikimedia\TestingAccessWrapper;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\AbstractPasswordPrimaryAuthenticationProvider
 */
class AbstractPasswordPrimaryAuthenticationProviderTest extends \MediaWikiTestCase {
	public function testConstructor() {
		$provider = $this->getMockForAbstractClass(
			AbstractPasswordPrimaryAuthenticationProvider::class
		);
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$this->assertTrue( $providerPriv->authoritative );

		$provider = $this->getMockForAbstractClass(
			AbstractPasswordPrimaryAuthenticationProvider::class,
			[ [ 'authoritative' => false ] ]
		);
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$this->assertFalse( $providerPriv->authoritative );
	}

	public function testGetPasswordFactory() {
		$provider = $this->getMockForAbstractClass(
			AbstractPasswordPrimaryAuthenticationProvider::class
		);
		$provider->setConfig( MediaWikiServices::getInstance()->getMainConfig() );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$obj = $providerPriv->getPasswordFactory();
		$this->assertInstanceOf( \PasswordFactory::class, $obj );
		$this->assertSame( $obj, $providerPriv->getPasswordFactory() );
	}

	public function testGetPassword() {
		$provider = $this->getMockForAbstractClass(
			AbstractPasswordPrimaryAuthenticationProvider::class
		);
		$provider->setConfig( MediaWikiServices::getInstance()->getMainConfig() );
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$obj = $providerPriv->getPassword( null );
		$this->assertInstanceOf( \Password::class, $obj );

		$obj = $providerPriv->getPassword( 'invalid' );
		$this->assertInstanceOf( \Password::class, $obj );
	}

	public function testGetNewPasswordExpiry() {
		$config = new \HashConfig;
		$provider = $this->getMockForAbstractClass(
			AbstractPasswordPrimaryAuthenticationProvider::class
		);
		$provider->setConfig( new \MultiConfig( [
			$config,
			MediaWikiServices::getInstance()->getMainConfig()
		] ) );
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$this->mergeMwGlobalArrayValue( 'wgHooks', [ 'ResetPasswordExpiration' => [] ] );

		$config->set( 'PasswordExpirationDays', 0 );
		$this->assertNull( $providerPriv->getNewPasswordExpiry( 'UTSysop' ) );

		$config->set( 'PasswordExpirationDays', 5 );
		$this->assertEquals(
			time() + 5 * 86400,
			wfTimestamp( TS_UNIX, $providerPriv->getNewPasswordExpiry( 'UTSysop' ) ),
			'',
			2 /* Fuzz */
		);

		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'ResetPasswordExpiration' => [ function ( $user, &$expires ) {
				$this->assertSame( 'UTSysop', $user->getName() );
				$expires = '30001231235959';
			} ]
		] );
		$this->assertEquals( '30001231235959', $providerPriv->getNewPasswordExpiry( 'UTSysop' ) );
	}

	public function testCheckPasswordValidity() {
		$uppCalled = 0;
		$uppStatus = \Status::newGood( [] );
		$this->setMwGlobals( [
			'wgPasswordPolicy' => [
				'policies' => [
					'default' => [
						'Check' => true,
					],
				],
				'checks' => [
					'Check' => function () use ( &$uppCalled, &$uppStatus ) {
						$uppCalled++;
						return $uppStatus;
					},
				],
			]
		] );

		$provider = $this->getMockForAbstractClass(
			AbstractPasswordPrimaryAuthenticationProvider::class
		);
		$provider->setConfig( MediaWikiServices::getInstance()->getMainConfig() );
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$this->assertEquals( $uppStatus, $providerPriv->checkPasswordValidity( 'foo', 'bar' ) );

		$uppStatus->fatal( 'arbitrary-warning' );
		$this->assertEquals( $uppStatus, $providerPriv->checkPasswordValidity( 'foo', 'bar' ) );
	}

	public function testSetPasswordResetFlag() {
		$config = new \HashConfig( [
			'InvalidPasswordReset' => true,
		] );

		$manager = new AuthManager(
			new \FauxRequest(),
			MediaWikiServices::getInstance()->getMainConfig()
		);

		$provider = $this->getMockForAbstractClass(
			AbstractPasswordPrimaryAuthenticationProvider::class
		);
		$provider->setConfig( $config );
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$provider->setManager( $manager );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$manager->removeAuthenticationSessionData( null );
		$status = \Status::newGood();
		$providerPriv->setPasswordResetFlag( 'Foo', $status );
		$this->assertNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );

		$manager->removeAuthenticationSessionData( null );
		$status = \Status::newGood( [ 'suggestChangeOnLogin' => true ] );
		$status->error( 'testing' );
		$providerPriv->setPasswordResetFlag( 'Foo', $status );
		$ret = $manager->getAuthenticationSessionData( 'reset-pass' );
		$this->assertNotNull( $ret );
		$this->assertSame( 'resetpass-validity-soft', $ret->msg->getKey() );
		$this->assertFalse( $ret->hard );

		$config->set( 'InvalidPasswordReset', false );
		$manager->removeAuthenticationSessionData( null );
		$providerPriv->setPasswordResetFlag( 'Foo', $status );
		$ret = $manager->getAuthenticationSessionData( 'reset-pass' );
		$this->assertNull( $ret );
	}

	public function testFailResponse() {
		$provider = $this->getMockForAbstractClass(
			AbstractPasswordPrimaryAuthenticationProvider::class,
			[ [ 'authoritative' => false ] ]
		);
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$req = new PasswordAuthenticationRequest;

		$ret = $providerPriv->failResponse( $req );
		$this->assertSame( AuthenticationResponse::ABSTAIN, $ret->status );

		$provider = $this->getMockForAbstractClass(
			AbstractPasswordPrimaryAuthenticationProvider::class,
			[ [ 'authoritative' => true ] ]
		);
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$req->password = '';
		$ret = $providerPriv->failResponse( $req );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'wrongpasswordempty', $ret->message->getKey() );

		$req->password = 'X';
		$ret = $providerPriv->failResponse( $req );
		$this->assertSame( AuthenticationResponse::FAIL, $ret->status );
		$this->assertSame( 'wrongpassword', $ret->message->getKey() );
	}

	/**
	 * @dataProvider provideGetAuthenticationRequests
	 * @param string $action
	 * @param array $response
	 */
	public function testGetAuthenticationRequests( $action, $response ) {
		$provider = $this->getMockForAbstractClass(
			AbstractPasswordPrimaryAuthenticationProvider::class
		);

		$this->assertEquals( $response, $provider->getAuthenticationRequests( $action, [] ) );
	}

	public static function provideGetAuthenticationRequests() {
		return [
			[ AuthManager::ACTION_LOGIN, [ new PasswordAuthenticationRequest() ] ],
			[ AuthManager::ACTION_CREATE, [ new PasswordAuthenticationRequest() ] ],
			[ AuthManager::ACTION_LINK, [] ],
			[ AuthManager::ACTION_CHANGE, [ new PasswordAuthenticationRequest() ] ],
			[ AuthManager::ACTION_REMOVE, [ new PasswordAuthenticationRequest() ] ],
		];
	}

	public function testProviderRevokeAccessForUser() {
		$req = new PasswordAuthenticationRequest;
		$req->action = AuthManager::ACTION_REMOVE;
		$req->username = 'foo';
		$req->password = null;

		$provider = $this->getMockForAbstractClass(
			AbstractPasswordPrimaryAuthenticationProvider::class
		);
		$provider->expects( $this->once() )
			->method( 'providerChangeAuthenticationData' )
			->with( $this->equalTo( $req ) );

		$provider->providerRevokeAccessForUser( 'foo' );
	}

}
