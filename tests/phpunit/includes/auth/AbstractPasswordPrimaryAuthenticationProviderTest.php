<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\AbstractPasswordPrimaryAuthenticationProvider
 */
class AbstractPasswordPrimaryAuthenticationProviderTest extends \MediaWikiTestCase {
	public function testConstructor() {
		$provider = $this->getMockForAbstractClass(
			'MediaWiki\\Auth\\AbstractPasswordPrimaryAuthenticationProvider'
		);
		$providerPriv = \TestingAccessWrapper::newFromObject( $provider );
		$this->assertTrue( $providerPriv->authoritative );

		$provider = $this->getMockForAbstractClass(
			'MediaWiki\\Auth\\AbstractPasswordPrimaryAuthenticationProvider',
			[ [ 'authoritative' => false ] ]
		);
		$providerPriv = \TestingAccessWrapper::newFromObject( $provider );
		$this->assertFalse( $providerPriv->authoritative );
	}

	public function testGetPasswordFactory() {
		$provider = $this->getMockForAbstractClass(
			'MediaWiki\\Auth\\AbstractPasswordPrimaryAuthenticationProvider'
		);
		$provider->setConfig( \ConfigFactory::getDefaultInstance()->makeConfig( 'main' ) );
		$providerPriv = \TestingAccessWrapper::newFromObject( $provider );

		$obj = $providerPriv->getPasswordFactory();
		$this->assertInstanceOf( 'PasswordFactory', $obj );
		$this->assertSame( $obj, $providerPriv->getPasswordFactory() );
	}

	public function testGetPassword() {
		$provider = $this->getMockForAbstractClass(
			'MediaWiki\\Auth\\AbstractPasswordPrimaryAuthenticationProvider'
		);
		$provider->setConfig( \ConfigFactory::getDefaultInstance()->makeConfig( 'main' ) );
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$providerPriv = \TestingAccessWrapper::newFromObject( $provider );

		$obj = $providerPriv->getPassword( null );
		$this->assertInstanceOf( 'Password', $obj );

		$obj = $providerPriv->getPassword( 'invalid' );
		$this->assertInstanceOf( 'Password', $obj );
	}

	public function testCheckPasswordValidity() {
		$uppCalled = 0;
		$uppStatus = \Status::newGood();
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
			'MediaWiki\\Auth\\AbstractPasswordPrimaryAuthenticationProvider'
		);
		$provider->setConfig( \ConfigFactory::getDefaultInstance()->makeConfig( 'main' ) );
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$providerPriv = \TestingAccessWrapper::newFromObject( $provider );

		$this->assertEquals( $uppStatus, $providerPriv->checkPasswordValidity( 'foo', 'bar' ) );

		$uppStatus->fatal( 'arbitrary-warning' );
		$this->assertEquals( $uppStatus, $providerPriv->checkPasswordValidity( 'foo', 'bar' ) );
	}

	public function testSetPasswordResetFlag() {
		$config = new \HashConfig( [
			'InvalidPasswordReset' => true,
		] );

		$manager = new AuthManager(
			new \FauxRequest(), \ConfigFactory::getDefaultInstance()->makeConfig( 'main' )
		);

		$provider = $this->getMockForAbstractClass(
			'MediaWiki\\Auth\\AbstractPasswordPrimaryAuthenticationProvider'
		);
		$provider->setConfig( $config );
		$provider->setLogger( new \Psr\Log\NullLogger() );
		$provider->setManager( $manager );
		$providerPriv = \TestingAccessWrapper::newFromObject( $provider );

		$manager->removeAuthenticationSessionData( null );
		$status = \Status::newGood();
		$providerPriv->setPasswordResetFlag( 'Foo', $status );
		$this->assertNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );

		$manager->removeAuthenticationSessionData( null );
		$status = \Status::newGood();
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
			'MediaWiki\\Auth\\AbstractPasswordPrimaryAuthenticationProvider',
			[ [ 'authoritative' => false ] ]
		);
		$providerPriv = \TestingAccessWrapper::newFromObject( $provider );

		$req = new PasswordAuthenticationRequest;

		$ret = $providerPriv->failResponse( $req );
		$this->assertSame( AuthenticationResponse::ABSTAIN, $ret->status );

		$provider = $this->getMockForAbstractClass(
			'MediaWiki\\Auth\\AbstractPasswordPrimaryAuthenticationProvider',
			[ [ 'authoritative' => true ] ]
		);
		$providerPriv = \TestingAccessWrapper::newFromObject( $provider );

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
			'MediaWiki\\Auth\\AbstractPasswordPrimaryAuthenticationProvider'
		);

		$this->assertEquals( $response, $provider->getAuthenticationRequests( $action, [] ) );
	}

	public static function provideGetAuthenticationRequests() {
		return [
			[ AuthManager::ACTION_LOGIN, [ new PasswordAuthenticationRequest ] ],
			[ AuthManager::ACTION_CREATE, [ new PasswordAuthenticationRequest( true ) ] ],
			[ AuthManager::ACTION_LINK, [] ],
			[ AuthManager::ACTION_CHANGE, [ new PasswordAuthenticationRequest( true ) ] ],
			[ AuthManager::ACTION_REMOVE, [ new PasswordAuthenticationRequest ] ],
		];
	}

	public function testProviderRevokeAccessForUser() {
		$req = new PasswordAuthenticationRequest;
		$req->username = 'foo';
		$req->password = null;

		$provider = $this->getMockForAbstractClass(
			'MediaWiki\\Auth\\AbstractPasswordPrimaryAuthenticationProvider'
		);
		$provider->expects( $this->once() )
			->method( 'providerChangeAuthenticationData' )
			->with( $this->equalTo( $req ) );

		$provider->providerRevokeAccessForUser( 'foo' );
	}

}
