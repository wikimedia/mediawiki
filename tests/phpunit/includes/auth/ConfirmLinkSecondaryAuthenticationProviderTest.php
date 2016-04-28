<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\ConfirmLinkSecondaryAuthenticationProvider
 */
class ConfirmLinkSecondaryAuthenticationProviderTest extends \MediaWikiTestCase {
	protected function setUp() {
		global $wgDisableAuthManager;

		parent::setUp();
		if ( $wgDisableAuthManager ) {
			$this->markTestSkipped( '$wgDisableAuthManager is set' );
		}
	}

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
			->setMethods( [ 'beginLinkAttempt', 'continueLinkAttempt' ] )
			->getMock();
		$mock->expects( $this->once() )->method( 'beginLinkAttempt' )
			->with( $this->identicalTo( $user ), $this->identicalTo( 'AuthManager::authnState' ) )
			->will( $this->returnValue( $obj ) );
		$mock->expects( $this->never() )->method( 'continueLinkAttempt' );

		$this->assertSame( $obj, $mock->beginSecondaryAuthentication( $user, [] ) );
	}

	public function testContinueSecondaryAuthentication() {
		$user = \User::newFromName( 'UTSysop' );
		$obj = new \stdClass;
		$reqs = [ new \stdClass ];

		$mock = $this->getMockBuilder( ConfirmLinkSecondaryAuthenticationProvider::class )
			->setMethods( [ 'beginLinkAttempt', 'continueLinkAttempt' ] )
			->getMock();
		$mock->expects( $this->never() )->method( 'beginLinkAttempt' );
		$mock->expects( $this->once() )->method( 'continueLinkAttempt' )
			->with(
				$this->identicalTo( $user ),
				$this->identicalTo( 'AuthManager::authnState' ),
				$this->identicalTo( $reqs )
			)
			->will( $this->returnValue( $obj ) );

		$this->assertSame( $obj, $mock->continueSecondaryAuthentication( $user, $reqs ) );
	}

	public function testBeginSecondaryAccountCreation() {
		$user = \User::newFromName( 'UTSysop' );
		$obj = new \stdClass;

		$mock = $this->getMockBuilder( ConfirmLinkSecondaryAuthenticationProvider::class )
			->setMethods( [ 'beginLinkAttempt', 'continueLinkAttempt' ] )
			->getMock();
		$mock->expects( $this->once() )->method( 'beginLinkAttempt' )
			->with( $this->identicalTo( $user ), $this->identicalTo( 'AuthManager::accountCreationState' ) )
			->will( $this->returnValue( $obj ) );
		$mock->expects( $this->never() )->method( 'continueLinkAttempt' );

		$this->assertSame( $obj, $mock->beginSecondaryAccountCreation( $user, $user, [] ) );
	}

	public function testContinueSecondaryAccountCreation() {
		$user = \User::newFromName( 'UTSysop' );
		$obj = new \stdClass;
		$reqs = [ new \stdClass ];

		$mock = $this->getMockBuilder( ConfirmLinkSecondaryAuthenticationProvider::class )
			->setMethods( [ 'beginLinkAttempt', 'continueLinkAttempt' ] )
			->getMock();
		$mock->expects( $this->never() )->method( 'beginLinkAttempt' );
		$mock->expects( $this->once() )->method( 'continueLinkAttempt' )
			->with(
				$this->identicalTo( $user ),
				$this->identicalTo( 'AuthManager::accountCreationState' ),
				$this->identicalTo( $reqs )
			)
			->will( $this->returnValue( $obj ) );

		$this->assertSame( $obj, $mock->continueSecondaryAccountCreation( $user, $user, $reqs ) );
	}

	/**
	 * Get requests for testing
	 * @return AuthenticationRequest[]
	 */
	private function getLinkRequests() {
		$reqs = [];

		$mb = $this->getMockBuilder( AuthenticationRequest::class )
			->setMethods( [ 'getUniqueId' ] );
		for ( $i = 1; $i <= 3; $i++ ) {
			$req = $mb->getMockForAbstractClass();
			$req->expects( $this->any() )->method( 'getUniqueId' )
				->will( $this->returnValue( "Request$i" ) );
			$req->id = $i - 1;
			$reqs[$req->getUniqueId()] = $req;
		}

		return $reqs;
	}

	public function testBeginLinkAttempt() {
		$user = \User::newFromName( 'UTSysop' );
		$provider = \TestingAccessWrapper::newFromObject(
			new ConfirmLinkSecondaryAuthenticationProvider
		);
		$request = new \FauxRequest();
		$manager = new AuthManager( $request, \RequestContext::getMain()->getConfig() );
		$provider->setManager( $manager );

		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginLinkAttempt( $user, 'state' )
		);

		$request->getSession()->setSecret( 'state', [
			'maybeLink' => [],
		] );
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginLinkAttempt( $user, 'state' )
		);

		$reqs = $this->getLinkRequests();
		$request->getSession()->setSecret( 'state', [
			'maybeLink' => $reqs
		] );
		$res = $provider->beginLinkAttempt( $user, 'state' );
		$this->assertInstanceOf( AuthenticationResponse::class, $res );
		$this->assertSame( AuthenticationResponse::UI, $res->status );
		$this->assertSame( 'authprovider-confirmlink-message', $res->message->getKey() );
		$this->assertCount( 1, $res->neededRequests );
		$req = $res->neededRequests[0];
		$this->assertInstanceOf( ConfirmLinkAuthenticationRequest::class, $req );
		$this->assertEquals( $reqs, \TestingAccessWrapper::newFromObject( $req )->linkRequests );
	}

	public function testContinueLinkAttempt() {
		$user = \User::newFromName( 'UTSysop' );
		$obj = new \stdClass;
		$reqs = $this->getLinkRequests();

		$done = [ false, false, false ];

		// First, test the pass-through for not containing the ConfirmLinkAuthenticationRequest
		$mock = $this->getMockBuilder( ConfirmLinkSecondaryAuthenticationProvider::class )
			->setMethods( [ 'beginLinkAttempt' ] )
			->getMock();
		$mock->expects( $this->once() )->method( 'beginLinkAttempt' )
			->with( $this->identicalTo( $user ), $this->identicalTo( 'state' ) )
			->will( $this->returnValue( $obj ) );
		$this->assertSame(
			$obj,
			\TestingAccessWrapper::newFromObject( $mock )->continueLinkAttempt( $user, 'state', $reqs )
		);

		// Now test the actual functioning
		$provider = $this->getMockBuilder( ConfirmLinkSecondaryAuthenticationProvider::class )
			->setMethods( [
				'beginLinkAttempt', 'providerAllowsAuthenticationDataChange',
				'providerChangeAuthenticationData'
			] )
			->getMock();
		$provider->expects( $this->never() )->method( 'beginLinkAttempt' );
		$provider->expects( $this->any() )->method( 'providerAllowsAuthenticationDataChange' )
			->will( $this->returnCallback( function ( $req ) use ( $reqs ) {
				return $req->getUniqueId() === 'Request3'
					? \StatusValue::newFatal( 'foo' ) : \StatusValue::newGood();
			} ) );
		$provider->expects( $this->any() )->method( 'providerChangeAuthenticationData' )
			->will( $this->returnCallback( function ( $req ) use ( &$done ) {
				$done[$req->id] = true;
			} ) );
		$config = new \HashConfig( [
			'AuthManagerConfig' => [
				'preauth' => [],
				'primaryauth' => [],
				'secondaryauth' => [
					[ 'factory' => function () use ( $provider ) {
						return $provider;
					} ],
				],
			],
		] );
		$request = new \FauxRequest();
		$manager = new AuthManager( $request, $config );
		$provider->setManager( $manager );
		$provider = \TestingAccessWrapper::newFromObject( $provider );

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
		$this->assertSame( [ false, false, false ], $done );

		$request->getSession()->setSecret( 'state', [
			'maybeLink' => [ $reqs['Request2'] ],
		] );
		$req->confirmedLinkIDs = [ 'Request1', 'Request2' ];
		$res = $provider->continueLinkAttempt( $user, 'state', [ $req ] );
		$this->assertEquals( AuthenticationResponse::newPass(), $res );
		$this->assertSame( [ false, true, false ], $done );
		$done = [ false, false, false ];

		$request->getSession()->setSecret( 'state', [
			'maybeLink' => $reqs,
		] );
		$req->confirmedLinkIDs = [ 'Request1', 'Request2' ];
		$res = $provider->continueLinkAttempt( $user, 'state', [ $req ] );
		$this->assertEquals( AuthenticationResponse::newPass(), $res );
		$this->assertSame( [ true, true, false ], $done );
		$done = [ false, false, false ];

		$request->getSession()->setSecret( 'state', [
			'maybeLink' => $reqs,
		] );
		$req->confirmedLinkIDs = [ 'Request1', 'Request3' ];
		$res = $provider->continueLinkAttempt( $user, 'state', [ $req ] );
		$this->assertEquals( AuthenticationResponse::UI, $res->status );
		$this->assertCount( 1, $res->neededRequests );
		$this->assertInstanceOf( ButtonAuthenticationRequest::class, $res->neededRequests[0] );
		$this->assertSame( [ true, false, false ], $done );
		$done = [ false, false, false ];

		$res = $provider->continueLinkAttempt( $user, 'state', [ $res->neededRequests[0] ] );
		$this->assertEquals( AuthenticationResponse::newPass(), $res );
		$this->assertSame( [ false, false, false ], $done );
	}

}
