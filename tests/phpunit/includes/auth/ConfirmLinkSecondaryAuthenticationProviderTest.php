<?php

namespace MediaWiki\Auth;

use MediaWiki\Tests\Unit\Auth\AuthenticationProviderTestTrait;
use MediaWiki\User\UserNameUtils;
use Psr\Container\ContainerInterface;
use Wikimedia\ObjectFactory\ObjectFactory;
use Wikimedia\TestingAccessWrapper;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\ConfirmLinkSecondaryAuthenticationProvider
 */
class ConfirmLinkSecondaryAuthenticationProviderTest extends \MediaWikiIntegrationTestCase {
	use AuthenticationProviderTestTrait;

	/**
	 * @dataProvider provideGetAuthenticationRequests
	 * @param string $action
	 * @param array $response
	 */
	public function testGetAuthenticationRequests( $action, $response ) {
		$provider = new ConfirmLinkSecondaryAuthenticationProvider();

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

	public function testBeginSecondaryAuthentication() {
		$user = \User::newFromName( 'UTSysop' );
		$obj = new \stdClass;

		$mock = $this->getMockBuilder( ConfirmLinkSecondaryAuthenticationProvider::class )
			->onlyMethods( [ 'beginLinkAttempt', 'continueLinkAttempt' ] )
			->getMock();
		$mock->expects( $this->once() )->method( 'beginLinkAttempt' )
			->with( $this->identicalTo( $user ), $this->identicalTo( 'AuthManager::authnState' ) )
			->willReturn( $obj );
		$mock->expects( $this->never() )->method( 'continueLinkAttempt' );

		$this->assertSame( $obj, $mock->beginSecondaryAuthentication( $user, [] ) );
	}

	public function testContinueSecondaryAuthentication() {
		$user = \User::newFromName( 'UTSysop' );
		$obj = new \stdClass;
		$reqs = [ new \stdClass ];

		$mock = $this->getMockBuilder( ConfirmLinkSecondaryAuthenticationProvider::class )
			->onlyMethods( [ 'beginLinkAttempt', 'continueLinkAttempt' ] )
			->getMock();
		$mock->expects( $this->never() )->method( 'beginLinkAttempt' );
		$mock->expects( $this->once() )->method( 'continueLinkAttempt' )
			->with(
				$this->identicalTo( $user ),
				$this->identicalTo( 'AuthManager::authnState' ),
				$this->identicalTo( $reqs )
			)
			->willReturn( $obj );

		$this->assertSame( $obj, $mock->continueSecondaryAuthentication( $user, $reqs ) );
	}

	public function testBeginSecondaryAccountCreation() {
		$user = \User::newFromName( 'UTSysop' );
		$obj = new \stdClass;

		$mock = $this->getMockBuilder( ConfirmLinkSecondaryAuthenticationProvider::class )
			->onlyMethods( [ 'beginLinkAttempt', 'continueLinkAttempt' ] )
			->getMock();
		$mock->expects( $this->once() )->method( 'beginLinkAttempt' )
			->with( $this->identicalTo( $user ), $this->identicalTo( 'AuthManager::accountCreationState' ) )
			->willReturn( $obj );
		$mock->expects( $this->never() )->method( 'continueLinkAttempt' );

		$this->assertSame( $obj, $mock->beginSecondaryAccountCreation( $user, $user, [] ) );
	}

	public function testContinueSecondaryAccountCreation() {
		$user = \User::newFromName( 'UTSysop' );
		$obj = new \stdClass;
		$reqs = [ new \stdClass ];

		$mock = $this->getMockBuilder( ConfirmLinkSecondaryAuthenticationProvider::class )
			->onlyMethods( [ 'beginLinkAttempt', 'continueLinkAttempt' ] )
			->getMock();
		$mock->expects( $this->never() )->method( 'beginLinkAttempt' );
		$mock->expects( $this->once() )->method( 'continueLinkAttempt' )
			->with(
				$this->identicalTo( $user ),
				$this->identicalTo( 'AuthManager::accountCreationState' ),
				$this->identicalTo( $reqs )
			)
			->willReturn( $obj );

		$this->assertSame( $obj, $mock->continueSecondaryAccountCreation( $user, $user, $reqs ) );
	}

	/**
	 * Get requests for testing
	 * @return AuthenticationRequest[]
	 */
	private function getLinkRequests() {
		$reqs = [];

		$mb = $this->getMockBuilder( AuthenticationRequest::class )
			->onlyMethods( [ 'getUniqueId' ] );
		for ( $i = 1; $i <= 3; $i++ ) {
			$uid = "Request$i";
			$req = $mb->getMockForAbstractClass();
			$req->method( 'getUniqueId' )->willReturn( $uid );
			$reqs[$uid] = $req;
		}

		return $reqs;
	}

	public function testBeginLinkAttempt() {
		$badReq = $this->getMockBuilder( AuthenticationRequest::class )
			->onlyMethods( [ 'getUniqueId' ] )
			->getMockForAbstractClass();
		$badReq->method( 'getUniqueId' )
			->willReturn( "BadReq" );

		$user = \User::newFromName( 'UTSysop' );
		$provider = new ConfirmLinkSecondaryAuthenticationProvider;
		$providerPriv = TestingAccessWrapper::newFromObject( $provider );
		$request = new \FauxRequest();
		$mwServices = $this->getServiceContainer();

		$manager = $this->getMockBuilder( AuthManager::class )
			->onlyMethods( [ 'allowsAuthenticationDataChange' ] )
			->setConstructorArgs( [
				$request,
				$mwServices->getMainConfig(),
				$mwServices->getObjectFactory(),
				$mwServices->getHookContainer(),
				$mwServices->getReadOnlyMode(),
				$this->createNoOpMock( UserNameUtils::class ),
				$mwServices->getBlockManager(),
				$mwServices->getWatchlistManager(),
				$mwServices->getDBLoadBalancer(),
				$mwServices->getContentLanguage(),
				$mwServices->getLanguageConverterFactory(),
				$mwServices->getBotPasswordStore(),
				$mwServices->getUserFactory(),
				$mwServices->getUserIdentityLookup(),
				$mwServices->getUserOptionsManager()
			] )
			->getMock();
		$manager->method( 'allowsAuthenticationDataChange' )
			->willReturnCallback( static function ( $req ) {
				return $req->getUniqueId() !== 'BadReq'
					? \StatusValue::newGood()
					: \StatusValue::newFatal( 'no' );
			} );
		$this->initProvider( $provider, null, null, $manager );

		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$providerPriv->beginLinkAttempt( $user, 'state' )
		);

		$request->getSession()->setSecret( 'state', [
			'maybeLink' => [],
		] );
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$providerPriv->beginLinkAttempt( $user, 'state' )
		);

		$reqs = $this->getLinkRequests();
		$request->getSession()->setSecret( 'state', [
			'maybeLink' => $reqs + [ 'BadReq' => $badReq ]
		] );
		$res = $providerPriv->beginLinkAttempt( $user, 'state' );
		$this->assertInstanceOf( AuthenticationResponse::class, $res );
		$this->assertSame( AuthenticationResponse::UI, $res->status );
		$this->assertSame( 'authprovider-confirmlink-message', $res->message->getKey() );
		$this->assertCount( 1, $res->neededRequests );
		$req = $res->neededRequests[0];
		$this->assertInstanceOf( ConfirmLinkAuthenticationRequest::class, $req );
		$expectReqs = $this->getLinkRequests();
		foreach ( $expectReqs as $r ) {
			$r->action = AuthManager::ACTION_CHANGE;
			$r->username = $user->getName();
		}
		$this->assertEquals( $expectReqs, TestingAccessWrapper::newFromObject( $req )->linkRequests );
	}

	public function testContinueLinkAttempt() {
		$user = \User::newFromName( 'UTSysop' );
		$obj = new \stdClass;
		$reqs = $this->getLinkRequests();

		$done = [];

		// First, test the pass-through for not containing the ConfirmLinkAuthenticationRequest
		$mock = $this->getMockBuilder( ConfirmLinkSecondaryAuthenticationProvider::class )
			->onlyMethods( [ 'beginLinkAttempt' ] )
			->getMock();
		$mock->expects( $this->once() )->method( 'beginLinkAttempt' )
			->with( $this->identicalTo( $user ), $this->identicalTo( 'state' ) )
			->willReturn( $obj );
		$this->assertSame(
			$obj,
			TestingAccessWrapper::newFromObject( $mock )->continueLinkAttempt( $user, 'state', $reqs )
		);

		// Now test the actual functioning
		$provider = $this->getMockBuilder( ConfirmLinkSecondaryAuthenticationProvider::class )
			->onlyMethods( [
				'beginLinkAttempt', 'providerAllowsAuthenticationDataChange',
				'providerChangeAuthenticationData'
			] )
			->getMock();
		$provider->expects( $this->never() )->method( 'beginLinkAttempt' );
		$provider->method( 'providerAllowsAuthenticationDataChange' )
			->willReturnCallback( static function ( $req ) {
				return $req->getUniqueId() === 'Request3'
					? \StatusValue::newFatal( 'foo' ) : \StatusValue::newGood();
			} );
		$provider->method( 'providerChangeAuthenticationData' )
			->willReturnCallback( static function ( $req ) use ( &$done ) {
				$done[$req->getUniqueId()] = true;
			} );
		$config = new \HashConfig( [
			'AuthManagerConfig' => [
				'preauth' => [],
				'primaryauth' => [],
				'secondaryauth' => [
					[ 'factory' => static function () use ( $provider ) {
						return $provider;
					} ],
				],
			],
		] );
		$request = new \FauxRequest();
		$mwServices = $this->getServiceContainer();
		$manager = new AuthManager(
			$request,
			$config,
			new ObjectFactory( $this->createNoOpMock( ContainerInterface::class ) ),
			$mwServices->getHookContainer(),
			$mwServices->getReadOnlyMode(),
			$mwServices->getUserNameUtils(),
			$mwServices->getBlockManager(),
			$mwServices->getWatchlistManager(),
			$mwServices->getDBLoadBalancer(),
			$mwServices->getContentLanguage(),
			$mwServices->getLanguageConverterFactory(),
			$mwServices->getBotPasswordStore(),
			$mwServices->getUserFactory(),
			$mwServices->getUserIdentityLookup(),
			$mwServices->getUserOptionsManager()
		);
		$this->initProvider( $provider, null, null, $manager );
		$provider = TestingAccessWrapper::newFromObject( $provider );

		$req = new ConfirmLinkAuthenticationRequest( $reqs );

		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->continueLinkAttempt( $user, 'state', [ $req ] )
		);

		$request->getSession()->setSecret( 'state', [
			'maybeLink' => [],
		] );
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->continueLinkAttempt( $user, 'state', [ $req ] )
		);

		$request->getSession()->setSecret( 'state', [
			'maybeLink' => $reqs
		] );
		$this->assertEquals(
			AuthenticationResponse::newPass(),
			$res = $provider->continueLinkAttempt( $user, 'state', [ $req ] )
		);
		$this->assertSame( [], $done );

		$request->getSession()->setSecret( 'state', [
			'maybeLink' => [ $reqs['Request2'] ],
		] );
		$req->confirmedLinkIDs = [ 'Request1', 'Request2' ];
		$res = $provider->continueLinkAttempt( $user, 'state', [ $req ] );
		$this->assertEquals( AuthenticationResponse::newPass(), $res );
		$this->assertSame( [ 'Request2' => true ], $done );
		$done = [];

		$request->getSession()->setSecret( 'state', [
			'maybeLink' => $reqs,
		] );
		$req->confirmedLinkIDs = [ 'Request1', 'Request2' ];
		$res = $provider->continueLinkAttempt( $user, 'state', [ $req ] );
		$this->assertEquals( AuthenticationResponse::newPass(), $res );
		$this->assertSame( [ 'Request1' => true, 'Request2' => true ], $done );
		$done = [];

		$request->getSession()->setSecret( 'state', [
			'maybeLink' => $reqs,
		] );
		$req->confirmedLinkIDs = [ 'Request1', 'Request3' ];
		$res = $provider->continueLinkAttempt( $user, 'state', [ $req ] );
		$this->assertEquals( AuthenticationResponse::UI, $res->status );
		$this->assertCount( 1, $res->neededRequests );
		$this->assertInstanceOf( ButtonAuthenticationRequest::class, $res->neededRequests[0] );
		$this->assertSame( [ 'Request1' => true ], $done );
		$done = [];

		$res = $provider->continueLinkAttempt( $user, 'state', [ $res->neededRequests[0] ] );
		$this->assertEquals( AuthenticationResponse::newPass(), $res );
		$this->assertSame( [], $done );
	}

}
