<?php

namespace MediaWiki\Tests\Auth;

use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\ButtonAuthenticationRequest;
use MediaWiki\Auth\PasswordAuthenticationRequest;
use MediaWiki\Auth\ResetPasswordSecondaryAuthenticationProvider;
use MediaWiki\Language\RawMessage;
use MediaWiki\Message\Message;
use MediaWiki\Status\Status;
use MediaWiki\Tests\Unit\Auth\AuthenticationProviderTestTrait;
use MediaWiki\User\User;
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

	/**
	 * @dataProvider provideTryResetUnmetRequirements
	 */
	public function testTryResetShouldNotChangeDataIfRequirementsNotMet(
		array $sessionData,
		array $authReqs,
		AuthenticationResponse $expectedResponse
	): void {
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

		$res = $provider->tryReset( $user, $authReqs );

		$this->assertEquals( $expectedResponse, $res );
	}

	public static function provideTryResetUnmetRequirements(): iterable {
		$msg = new RawMessage( 'foo' );

		$requiredPassReq = new PasswordAuthenticationRequest();
		$requiredPassReq->action = AuthManager::ACTION_CHANGE;

		yield 'missing required request with hard flag' => [
			[
				'msg' => $msg,
				'hard' => true,
			],
			[],
			AuthenticationResponse::newUI( [ $requiredPassReq ], $msg )
		];

		$skipReq = new ButtonAuthenticationRequest(
			'skipReset',
			wfMessage( 'authprovider-resetpass-skip-label' ),
			wfMessage( 'authprovider-resetpass-skip-help' )
		);

		$wrongActionReq = new PasswordAuthenticationRequest();
		$wrongActionReq->action = AuthManager::ACTION_LOGIN;
		$wrongActionReq->password = 'Foo';
		$wrongActionReq->retype = 'Foo';

		yield 'wrong action type' => [
			[
				'msg' => $msg,
				'hard' => true,
			],
			[ $skipReq, $wrongActionReq ],
			AuthenticationResponse::newUI( [ $requiredPassReq ], $msg )
		];

		$badRetypeReq = new PasswordAuthenticationRequest();
		$badRetypeReq->action = AuthManager::ACTION_CHANGE;
		$badRetypeReq->password = 'Foo';
		$badRetypeReq->retype = 'Bar';

		yield 'bad retype value' => [
			[
				'msg' => $msg,
				'hard' => true,
			],
			[ $skipReq, $badRetypeReq ],
			AuthenticationResponse::newUI( [ $requiredPassReq ], new Message( 'badretype' ), 'error' )
		];

		$passReq = new PasswordAuthenticationRequest();
		$passReq->action = AuthManager::ACTION_CHANGE;
		$passReq->password = 'Foo';
		$passReq->retype = 'Foo';

		$optionalPassReq = clone $passReq;
		$optionalPassReq->required = AuthenticationRequest::OPTIONAL;

		yield 'missing required request without hard flag' => [
			[
				'msg' => $msg,
				'hard' => false,
				'req' => $passReq,
			],
			[],
			AuthenticationResponse::newUI( [ $optionalPassReq, $skipReq ], $msg )
		];

		$customPassReq = new class extends PasswordAuthenticationRequest {
		};
		$customPassReq->action = AuthManager::ACTION_CHANGE;
		$customPassReq->password = 'Foo';
		$customPassReq->retype = 'Foo';

		$optionalPassReq = clone $customPassReq;
		$optionalPassReq->required = AuthenticationRequest::OPTIONAL;

		yield 'mismatched request type' => [
			[
				'msg' => $msg,
				'hard' => false,
				'req' => $customPassReq,
			],
			[ $passReq ],
			AuthenticationResponse::newUI( [ $optionalPassReq, $skipReq ], $msg )
		];
	}

	/**
	 * @dataProvider provideTryResetShouldSucceedOnValidInput
	 *
	 * @param array $sessionData
	 * @param array $authReqs
	 * @param AuthenticationRequest $expectedAuthManagerReq
	 * @return void
	 */
	public function testTryResetShouldSucceedOnValidInputIfAuthManagerAllows(
		array $sessionData,
		array $authReqs,
		AuthenticationRequest $expectedAuthManagerReq
	): void {
		$user = $this->createMock( User::class );
		$user->method( 'getName' )
			->willReturn( 'TestUser' );

		$expectedAuthManagerReq->username = $user->getName();

		$authManager = $this->createMock( AuthManager::class );
		$authManager->method( 'getAuthenticationSessionData' )
			->with( 'reset-pass' )
			->willReturn( $sessionData );

		$authManager->method( 'allowsAuthenticationDataChange' )
			->with( $expectedAuthManagerReq )
			->willReturn( StatusValue::newGood() );

		$authManager->expects( $this->once() )
			->method( 'changeAuthenticationData' );

		$authManager->expects( $this->once() )
			->method( 'removeAuthenticationSessionData' )
			->with( 'reset-pass' );

		$provider = new ResetPasswordSecondaryAuthenticationProvider();
		$this->initProvider( $provider, null, null, $authManager );

		$provider = TestingAccessWrapper::newFromObject( $provider );

		$res = $provider->tryReset( $user, $authReqs );

		$this->assertEquals( AuthenticationResponse::newPass(), $res );
	}

	public static function provideTryResetShouldSucceedOnValidInput(): iterable {
		$msg = new RawMessage( 'foo' );

		$skipReq = new ButtonAuthenticationRequest(
			'skipReset',
			wfMessage( 'authprovider-resetpass-skip-label' ),
			wfMessage( 'authprovider-resetpass-skip-help' )
		);

		$passReq = new PasswordAuthenticationRequest();
		$passReq->action = AuthManager::ACTION_CHANGE;
		$passReq->password = 'Foo';
		$passReq->retype = 'Foo';

		yield 'valid input with hard flag' => [
			[
				'msg' => $msg,
				'hard' => true,
			],
			[ $skipReq, $passReq ],
			$passReq
		];

		yield 'valid input without hard flag' => [
			[
				'msg' => $msg,
				'hard' => false,
				'req' => $passReq,
			],
			[ $passReq ],
			$passReq
		];
	}

	public function testTryResetShouldPassWithBadRetypeWhenNotInHardMode(): void {
		$user = $this->createNoOpMock( User::class );

		$skipReq = new ButtonAuthenticationRequest(
			'skipReset',
			wfMessage( 'authprovider-resetpass-skip-label' ),
			wfMessage( 'authprovider-resetpass-skip-help' )
		);

		$badRetypeReq = new PasswordAuthenticationRequest();
		$badRetypeReq->action = AuthManager::ACTION_CHANGE;
		$badRetypeReq->password = 'Foo';
		$badRetypeReq->retype = 'Bar';

		$authManager = $this->createMock( AuthManager::class );
		$authManager->method( 'getAuthenticationSessionData' )
			->with( 'reset-pass' )
			->willReturn( [
				'msg' => wfMessage( 'foo' ),
				'hard' => false,
				'req' => $badRetypeReq,
			] );

		$authManager->expects( $this->once() )
			->method( 'removeAuthenticationSessionData' )
			->with( 'reset-pass' );

		$authManager->expects( $this->never() )
			->method( $this->logicalNot(
				$this->logicalOr(
					$this->equalTo( 'getAuthenticationSessionData' ),
					$this->equalTo( 'removeAuthenticationSessionData' )
				)
			) );

		$provider = new ResetPasswordSecondaryAuthenticationProvider();
		$this->initProvider( $provider, null, null, $authManager );

		$provider = TestingAccessWrapper::newFromObject( $provider );

		$res = $provider->tryReset( $user, [ $skipReq, $badRetypeReq ] );

		$this->assertEquals( AuthenticationResponse::newPass(), $res );
	}

	public function testTryResetShouldFailIfRejectedByAuthManager(): void {
		$user = $this->createMock( User::class );
		$user->method( 'getName' )
			->willReturn( 'TestUser' );

		$skipReq = new ButtonAuthenticationRequest(
			'skipReset',
			wfMessage( 'authprovider-resetpass-skip-label' ),
			wfMessage( 'authprovider-resetpass-skip-help' )
		);
		$passReq = new PasswordAuthenticationRequest();
		$passReq->action = AuthManager::ACTION_CHANGE;
		$passReq->password = 'Foo';
		$passReq->retype = 'Foo';

		$authManager = $this->createMock( AuthManager::class );
		$authManager->method( 'getAuthenticationSessionData' )
			->with( 'reset-pass' )
			->willReturn( [
				'msg' => wfMessage( 'foo' ),
				'hard' => true,
			] );

		$authManager->method( 'allowsAuthenticationDataChange' )
			->with( $passReq )
			->willReturn( Status::newFatal( 'arbitrary-fail' ) );

		$authManager->expects( $this->never() )
			->method( $this->logicalOr(
				$this->equalTo( 'changeAuthenticationData' ),
				$this->equalTo( 'removeAuthenticationSessionData' )
			) );

		$provider = new ResetPasswordSecondaryAuthenticationProvider();
		$this->initProvider( $provider, null, null, $authManager );

		$provider = TestingAccessWrapper::newFromObject( $provider );

		$res = $provider->tryReset( $user, [ $skipReq, $passReq ] );

		$expectedAuthReq = new PasswordAuthenticationRequest();
		$expectedAuthReq->action = AuthManager::ACTION_CHANGE;

		$this->assertSame( AuthenticationResponse::UI, $res->status );
		$this->assertEquals( [ $expectedAuthReq ], $res->neededRequests );
		$this->assertSame( 'arbitrary-fail', $res->message->getKey() );
	}

}
