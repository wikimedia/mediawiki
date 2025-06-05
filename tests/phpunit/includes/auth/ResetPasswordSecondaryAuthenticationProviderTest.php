<?php

namespace MediaWiki\Tests\Auth;

use DynamicPropertyTestHelper;
use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\ButtonAuthenticationRequest;
use MediaWiki\Auth\PasswordAuthenticationRequest;
use MediaWiki\Auth\ResetPasswordSecondaryAuthenticationProvider;
use MediaWiki\Config\HashConfig;
use MediaWiki\Language\RawMessage;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Tests\Unit\Auth\AuthenticationProviderTestTrait;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\User\BotPasswordStore;
use MediaWiki\User\User;
use MediaWiki\User\UserNameUtils;
use MediaWikiIntegrationTestCase;
use StatusValue;
use stdClass;
use UnexpectedValueException;
use Wikimedia\TestingAccessWrapper;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\ResetPasswordSecondaryAuthenticationProvider
 */
class ResetPasswordSecondaryAuthenticationProviderTest extends MediaWikiIntegrationTestCase {
	use AuthenticationProviderTestTrait;
	use DummyServicesTrait;

	/**
	 * @dataProvider provideGetAuthenticationRequests
	 * @param string $action
	 * @param array $response
	 */
	public function testGetAuthenticationRequests( $action, $response ) {
		$provider = new ResetPasswordSecondaryAuthenticationProvider();

		$this->assertEquals( $response, $provider->getAuthenticationRequests( $action, [] ) );
	}

	public static function provideGetAuthenticationRequests() {
		return [
			[ AuthManager::ACTION_LOGIN, [] ],
			[ AuthManager::ACTION_CREATE, [] ],
			[ AuthManager::ACTION_LINK, [] ],
			[ AuthManager::ACTION_CHANGE, [] ],
			[ AuthManager::ACTION_REMOVE, [] ],
		];
	}

	public function testBasics() {
		$user = $this->createMock( User::class );
		$user2 = new User;
		$obj = new stdClass;
		$reqs = [ new stdClass ];

		$mb = $this->getMockBuilder( ResetPasswordSecondaryAuthenticationProvider::class )
			->onlyMethods( [ 'tryReset' ] );

		$methods = [
			'beginSecondaryAuthentication' => [ $user, $reqs ],
			'continueSecondaryAuthentication' => [ $user, $reqs ],
			'beginSecondaryAccountCreation' => [ $user, $user2, $reqs ],
			'continueSecondaryAccountCreation' => [ $user, $user2, $reqs ],
		];
		foreach ( $methods as $method => $args ) {
			$mock = $mb->getMock();
			$mock->expects( $this->once() )->method( 'tryReset' )
				->with( $this->identicalTo( $user ), $this->identicalTo( $reqs ) )
				->willReturn( $obj );
			$this->assertSame( $obj, $mock->$method( ...$args ) );
		}
	}

	public function testTryReset() {
		$username = 'TestTryReset';
		$user = User::newFromName( $username );

		$provider = $this->getMockBuilder(
			ResetPasswordSecondaryAuthenticationProvider::class
		)
			->onlyMethods( [
				'providerAllowsAuthenticationDataChange', 'providerChangeAuthenticationData'
			] )
			->getMock();
		$provider->method( 'providerAllowsAuthenticationDataChange' )
			->willReturnCallback( function ( $req ) use ( $username ) {
				$this->assertSame( $username, $req->username );
				return DynamicPropertyTestHelper::getDynamicProperty( $req, 'allow' );
			} );
		$provider->method( 'providerChangeAuthenticationData' )
			->willReturnCallback( function ( $req ) use ( $username ) {
				$this->assertSame( $username, $req->username );
				DynamicPropertyTestHelper::setDynamicProperty( $req, 'done', true );
			} );
		$config = new HashConfig( [
			MainConfigNames::AuthManagerConfig => [
				'preauth' => [],
				'primaryauth' => [],
				'secondaryauth' => [
					[ 'factory' => static function () use ( $provider ) {
						return $provider;
					} ],
				],
			],
		] );
		$mwServices = $this->getServiceContainer();
		$manager = new AuthManager(
			new FauxRequest,
			$config,
			$this->getDummyObjectFactory(),
			$this->createHookContainer(),
			$mwServices->getReadOnlyMode(),
			$this->createNoOpMock( UserNameUtils::class ),
			$mwServices->getBlockManager(),
			$mwServices->getWatchlistManager(),
			$mwServices->getDBLoadBalancer(),
			$mwServices->getContentLanguage(),
			$mwServices->getLanguageConverterFactory(),
			$this->createMock( BotPasswordStore::class ),
			$mwServices->getUserFactory(),
			$mwServices->getUserIdentityLookup(),
			$mwServices->getUserOptionsManager(),
			$mwServices->getNotificationService()
		);
		$this->initProvider( $provider, null, null, $manager );
		$provider = TestingAccessWrapper::newFromObject( $provider );

		$msg = wfMessage( 'foo' );
		$skipReq = new ButtonAuthenticationRequest(
			'skipReset',
			wfMessage( 'authprovider-resetpass-skip-label' ),
			wfMessage( 'authprovider-resetpass-skip-help' )
		);
		$passReq = new PasswordAuthenticationRequest();
		$passReq->action = AuthManager::ACTION_CHANGE;
		$passReq->password = 'Foo';
		$passReq->retype = 'Bar';
		DynamicPropertyTestHelper::setDynamicProperty( $passReq, 'allow', StatusValue::newGood() );
		DynamicPropertyTestHelper::setDynamicProperty( $passReq, 'done', false );

		$passReq2 = $this->getMockBuilder( PasswordAuthenticationRequest::class )
			->enableProxyingToOriginalMethods()
			->getMock();
		$passReq2->action = AuthManager::ACTION_CHANGE;
		$passReq2->password = 'Foo';
		$passReq2->retype = 'Foo';
		DynamicPropertyTestHelper::setDynamicProperty( $passReq2, 'allow', StatusValue::newGood() );
		DynamicPropertyTestHelper::setDynamicProperty( $passReq2, 'done', false );

		$passReq3 = new PasswordAuthenticationRequest();
		$passReq3->action = AuthManager::ACTION_LOGIN;
		$passReq3->password = 'Foo';
		$passReq3->retype = 'Foo';
		DynamicPropertyTestHelper::setDynamicProperty( $passReq3, 'allow', StatusValue::newGood() );
		DynamicPropertyTestHelper::setDynamicProperty( $passReq3, 'done', false );

		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => $msg,
			'hard' => true,
		] );
		$res = $provider->tryReset( $user, [] );
		$this->assertInstanceOf( AuthenticationResponse::class, $res );
		$this->assertSame( AuthenticationResponse::UI, $res->status );
		$this->assertEquals( $msg, $res->message );
		$this->assertCount( 1, $res->neededRequests );
		$this->assertInstanceOf(
			PasswordAuthenticationRequest::class,
			$res->neededRequests[0]
		);
		$this->assertNotNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertFalse( DynamicPropertyTestHelper::getDynamicProperty( $passReq, 'done' ) );

		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => $msg,
			'hard' => false,
			'req' => $passReq,
		] );
		$res = $provider->tryReset( $user, [] );
		$this->assertInstanceOf( AuthenticationResponse::class, $res );
		$this->assertSame( AuthenticationResponse::UI, $res->status );
		$this->assertEquals( $msg, $res->message );
		$this->assertCount( 2, $res->neededRequests );
		$expectedPassReq = clone $passReq;
		$expectedPassReq->required = AuthenticationRequest::OPTIONAL;
		$this->assertEquals( $expectedPassReq, $res->neededRequests[0] );
		$this->assertEquals( $skipReq, $res->neededRequests[1] );
		$this->assertNotNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertFalse( DynamicPropertyTestHelper::getDynamicProperty( $passReq, 'done' ) );

		$passReq->retype = 'Bad';
		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => $msg,
			'hard' => false,
			'req' => $passReq,
		] );
		$res = $provider->tryReset( $user, [ $skipReq, $passReq ] );
		$this->assertEquals( AuthenticationResponse::newPass(), $res );
		$this->assertNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertFalse( DynamicPropertyTestHelper::getDynamicProperty( $passReq, 'done' ) );

		$passReq->retype = 'Bad';
		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => $msg,
			'hard' => true,
		] );
		$res = $provider->tryReset( $user, [ $skipReq, $passReq ] );
		$this->assertSame( AuthenticationResponse::UI, $res->status );
		$this->assertSame( 'badretype', $res->message->getKey() );
		$this->assertCount( 1, $res->neededRequests );
		$this->assertInstanceOf(
			PasswordAuthenticationRequest::class,
			$res->neededRequests[0]
		);
		$this->assertNotNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertFalse( DynamicPropertyTestHelper::getDynamicProperty( $passReq, 'done' ) );

		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => $msg,
			'hard' => true,
		] );
		$res = $provider->tryReset( $user, [ $skipReq, $passReq3 ] );
		$this->assertSame( AuthenticationResponse::UI, $res->status );
		$this->assertEquals( $msg, $res->message );
		$this->assertCount( 1, $res->neededRequests );
		$this->assertInstanceOf(
			PasswordAuthenticationRequest::class,
			$res->neededRequests[0]
		);
		$this->assertNotNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertFalse( DynamicPropertyTestHelper::getDynamicProperty( $passReq, 'done' ) );

		$passReq->retype = $passReq->password;
		DynamicPropertyTestHelper::setDynamicProperty( $passReq, 'allow', StatusValue::newFatal( 'arbitrary-fail' ) );
		$res = $provider->tryReset( $user, [ $skipReq, $passReq ] );
		$this->assertSame( AuthenticationResponse::UI, $res->status );
		$this->assertSame( 'arbitrary-fail', $res->message->getKey() );
		$this->assertCount( 1, $res->neededRequests );
		$this->assertInstanceOf(
			PasswordAuthenticationRequest::class,
			$res->neededRequests[0]
		);
		$this->assertNotNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertFalse( DynamicPropertyTestHelper::getDynamicProperty( $passReq, 'done' ) );

		DynamicPropertyTestHelper::setDynamicProperty( $passReq, 'allow', StatusValue::newGood() );
		$res = $provider->tryReset( $user, [ $skipReq, $passReq ] );
		$this->assertEquals( AuthenticationResponse::newPass(), $res );
		$this->assertNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertTrue( DynamicPropertyTestHelper::getDynamicProperty( $passReq, 'done' ) );

		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => $msg,
			'hard' => false,
			'req' => $passReq2,
		] );
		$res = $provider->tryReset( $user, [ $passReq2 ] );
		$this->assertEquals( AuthenticationResponse::newPass(), $res );
		$this->assertNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertTrue( DynamicPropertyTestHelper::getDynamicProperty( $passReq2, 'done' ) );

		DynamicPropertyTestHelper::setDynamicProperty( $passReq, 'done', false );
		DynamicPropertyTestHelper::setDynamicProperty( $passReq2, 'done', false );
		$manager->setAuthenticationSessionData( 'reset-pass', [
			'msg' => $msg,
			'hard' => false,
			'req' => $passReq2,
		] );
		$res = $provider->tryReset( $user, [ $passReq ] );
		$this->assertInstanceOf( AuthenticationResponse::class, $res );
		$this->assertSame( AuthenticationResponse::UI, $res->status );
		$this->assertEquals( $msg, $res->message );
		$this->assertCount( 2, $res->neededRequests );
		$expectedPassReq = clone $passReq2;
		$expectedPassReq->required = AuthenticationRequest::OPTIONAL;
		$this->assertEquals( $expectedPassReq, $res->neededRequests[0] );
		$this->assertEquals( $skipReq, $res->neededRequests[1] );
		$this->assertNotNull( $manager->getAuthenticationSessionData( 'reset-pass' ) );
		$this->assertFalse( DynamicPropertyTestHelper::getDynamicProperty( $passReq, 'done' ) );
		$this->assertFalse( DynamicPropertyTestHelper::getDynamicProperty( $passReq2, 'done' ) );
	}

	public function testTryResetShouldAbstainIfNoSessionData(): void {
		$user = $this->createNoOpMock( User::class );

		$authManager = $this->createMock( AuthManager::class );
		$authManager->method( 'getAuthenticationSessionData' )
			->with( 'reset-pass' )
			->willReturn( null );

		$authManager->expects( $this->never() )
			->method( $this->logicalNot( $this->equalTo( 'getAuthenticationSessionData' ) ) );

		$provider = new ResetPasswordSecondaryAuthenticationProvider();
		$this->initProvider( $provider, null, null, $authManager );

		$provider = TestingAccessWrapper::newFromObject( $provider );

		$res = $provider->tryReset( $user, [] );

		$this->assertEquals( AuthenticationResponse::newAbstain(), $res );
	}

	/**
	 * @dataProvider provideInvalidResetSessionData
	 */
	public function testTryResetShouldThrowOnInvalidSessionData(
		array|stdClass|string $sessionData,
		string $expectedExceptionMessage
	): void {
		$this->expectException( UnexpectedValueException::class );
		$this->expectExceptionMessage( $expectedExceptionMessage );

		$user = $this->createNoOpMock( User::class );

		$authManager = $this->createMock( AuthManager::class );
		$authManager->method( 'getAuthenticationSessionData' )
			->with( 'reset-pass' )
			->willReturn( $sessionData );

		$authManager->expects( $this->never() )
			->method( $this->logicalNot( $this->equalTo( 'getAuthenticationSessionData' ) ) );

		$provider = new ResetPasswordSecondaryAuthenticationProvider();
		$this->initProvider( $provider, null, null, $authManager );

		$provider = TestingAccessWrapper::newFromObject( $provider );

		$provider->tryReset( $user, [] );
	}

	public static function provideInvalidResetSessionData(): iterable {
		yield 'malformed string' => [
			'foo',
			'reset-pass is not valid'
		];

		yield 'empty object' => [
			(object)[],
			'reset-pass msg is missing'
		];

		yield 'missing msg' => [
			[
				'hard' => true,
				'req' => new PasswordAuthenticationRequest(),
			],
			'reset-pass msg is missing'
		];

		yield 'invalid msg type' => [
			[
				'msg' => 'foo',
				'hard' => true,
				'req' => new PasswordAuthenticationRequest(),
			],
			'reset-pass msg is not valid'
		];

		yield 'missing hard flag' => [
			[
				'msg' => new RawMessage( 'foo' ),
				'req' => new PasswordAuthenticationRequest(),
			],
			'reset-pass hard is missing'
		];

		yield 'invalid req type' => [
			[
				'msg' => new RawMessage( 'foo' ),
				'hard' => true,
				'req' => 'foo',
			],
			'reset-pass req is not valid'
		];

		$loginReq = new PasswordAuthenticationRequest();
		$loginReq->action = AuthManager::ACTION_LOGIN;
		$loginReq->password = 'Foo';
		$loginReq->retype = 'Foo';

		yield 'invalid request action' => [
			[
				'msg' => new RawMessage( 'foo' ),
				'hard' => false,
				'req' => $loginReq,
			],
			'reset-pass req is not valid'
		];
	}
}
