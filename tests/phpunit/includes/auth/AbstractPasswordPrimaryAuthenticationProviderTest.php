<?php

namespace MediaWiki\Tests\Auth;

use MediaWiki\Auth\AbstractPasswordPrimaryAuthenticationProvider;
use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\PasswordAuthenticationRequest;
use MediaWiki\Config\HashConfig;
use MediaWiki\Config\MultiConfig;
use MediaWiki\MainConfigNames;
use MediaWiki\Password\Password;
use MediaWiki\Password\PasswordFactory;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Status\Status;
use MediaWiki\Tests\Unit\Auth\AuthenticationProviderTestTrait;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWikiIntegrationTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\AbstractPasswordPrimaryAuthenticationProvider
 */
class AbstractPasswordPrimaryAuthenticationProviderTest extends MediaWikiIntegrationTestCase {
	use AuthenticationProviderTestTrait;

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
		$this->initProvider( $provider, $this->getServiceContainer()->getMainConfig() );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$obj = $providerPriv->getPasswordFactory();
		$this->assertInstanceOf( PasswordFactory::class, $obj );
		$this->assertSame( $obj, $providerPriv->getPasswordFactory() );
	}

	public function testGetPassword() {
		$provider = $this->getMockForAbstractClass(
			AbstractPasswordPrimaryAuthenticationProvider::class
		);
		$this->initProvider( $provider, $this->getServiceContainer()->getMainConfig() );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$obj = $providerPriv->getPassword( null );
		$this->assertInstanceOf( Password::class, $obj );

		$obj = $providerPriv->getPassword( 'invalid' );
		$this->assertInstanceOf( Password::class, $obj );
	}

	public function testGetNewPasswordExpiry() {
		$userName = 'TestGetNewPasswordExpiry';
		$config = new HashConfig;
		$provider = $this->getMockForAbstractClass(
			AbstractPasswordPrimaryAuthenticationProvider::class
		);
		$this->initProvider( $provider, new MultiConfig( [ $config, $this->getServiceContainer()->getMainConfig() ] ) );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$config->set( MainConfigNames::PasswordExpirationDays, 0 );
		$this->assertNull( $providerPriv->getNewPasswordExpiry( $userName ) );

		$config->set( MainConfigNames::PasswordExpirationDays, 5 );
		$this->assertEqualsWithDelta(
			time() + 5 * 86400,
			wfTimestamp( TS_UNIX, $providerPriv->getNewPasswordExpiry( $userName ) ),
			2 /* Fuzz */
		);

		$this->initProvider(
			$provider,
			new MultiConfig( [ $config, $this->getServiceContainer()->getMainConfig() ] ),
			null,
			null,
			$this->createHookContainer( [
				'ResetPasswordExpiration' => function ( $user, &$expires ) use ( $userName ) {
					$this->assertSame( $userName, $user->getName() );
					$expires = '30001231235959';
				}
			] )
		);
		$this->assertSame( '30001231235959', $providerPriv->getNewPasswordExpiry( $userName ) );
	}

	public function testCheckPasswordValidity() {
		$uppCalled = 0;
		$uppStatus = Status::newGood( [] );
		$this->overrideConfigValue(
			MainConfigNames::PasswordPolicy,
			[
				'policies' => [
					'default' => [
						'Check' => true,
					],
				],
				'checks' => [
					'Check' => static function () use ( &$uppCalled, &$uppStatus ) {
						$uppCalled++;
						return $uppStatus;
					},
				],
			]
		);
		$this->clearHook( 'PasswordPoliciesForUser' );

		$provider = $this->getMockForAbstractClass(
			AbstractPasswordPrimaryAuthenticationProvider::class
		);
		$this->initProvider( $provider, $this->getServiceContainer()->getMainConfig() );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$username = '127.0.0.1';
		$anon = new User();
		$anon->setName( $username );
		$userFactory = $this->createMock( UserFactory::class );
		$userFactory->method( 'newFromName' )->with( $username )->willReturn( $anon );
		$this->setService( 'UserFactory', $userFactory );

		$this->assertEquals( $uppStatus, $providerPriv->checkPasswordValidity( $username, 'bar' ) );

		$uppStatus->fatal( 'arbitrary-warning' );
		$this->assertEquals( $uppStatus, $providerPriv->checkPasswordValidity( $username, 'bar' ) );
	}

	public function testSetPasswordResetFlag() {
		$config = new HashConfig( [
			MainConfigNames::InvalidPasswordReset => true,
		] );

		$services = $this->getServiceContainer();
		$manager = new AuthManager(
			new FauxRequest(),
			$services->getMainConfig(),
			$services->getObjectFactory(),
			$services->getHookContainer(),
			$services->getReadOnlyMode(),
			$services->getUserNameUtils(),
			$services->getBlockManager(),
			$services->getWatchlistManager(),
			$services->getDBLoadBalancer(),
			$services->getContentLanguage(),
			$services->getLanguageConverterFactory(),
			$services->getBotPasswordStore(),
			$services->getUserFactory(),
			$services->getUserIdentityLookup(),
			$services->getUserOptionsManager(),
			$services->getNotificationService()
		);

		$provider = $this->getMockForAbstractClass(
			AbstractPasswordPrimaryAuthenticationProvider::class
		);
		$this->initProvider( $provider, $config, null, $manager, $services->getHookContainer() );
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );

		$manager->removeAuthenticationSessionData( null );
		$status = Status::newGood();
		$providerPriv->setPasswordResetFlag( 'Foo', $status );
		$this->assertNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );

		$manager->removeAuthenticationSessionData( null );
		$status = Status::newGood( [ 'suggestChangeOnLogin' => true ] );
		$status->error( 'testing' );
		$providerPriv->setPasswordResetFlag( 'Foo', $status );
		$ret = $manager->getAuthenticationSessionData( 'reset-pass' );
		$this->assertNotNull( $ret );
		$this->assertSame( 'resetpass-validity-soft', $ret->msg->getKey() );
		$this->assertFalse( $ret->hard );

		$config->set( MainConfigNames::InvalidPasswordReset, false );
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
			->with( $req );

		$provider->providerRevokeAccessForUser( 'foo' );
	}

}
