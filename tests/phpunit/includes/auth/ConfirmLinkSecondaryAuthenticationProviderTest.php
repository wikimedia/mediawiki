<?php

namespace MediaWiki\Auth;

/**
 * @group AuthManager
 * @covers MediaWiki\Auth\ConfirmLinkSecondaryAuthenticationProvider
 */
class ConfirmLinkSecondaryAuthenticationProviderTest extends \MediaWikiTestCase {

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

		$mock = $this->getMockBuilder( 'MediaWiki\\Auth\\ConfirmLinkSecondaryAuthenticationProvider' )
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

		$mock = $this->getMockBuilder( 'MediaWiki\\Auth\\ConfirmLinkSecondaryAuthenticationProvider' )
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

		$mock = $this->getMockBuilder( 'MediaWiki\\Auth\\ConfirmLinkSecondaryAuthenticationProvider' )
			->setMethods( [ 'beginLinkAttempt', 'continueLinkAttempt' ] )
			->getMock();
		$mock->expects( $this->once() )->method( 'beginLinkAttempt' )
			->with( $this->identicalTo( $user ), $this->identicalTo( 'AuthManager::accountCreationState' ) )
			->will( $this->returnValue( $obj ) );
		$mock->expects( $this->never() )->method( 'continueLinkAttempt' );

		$this->assertSame( $obj, $mock->beginSecondaryAccountCreation( $user, [] ) );
	}

	public function testContinueSecondaryAccountCreation() {
		$user = \User::newFromName( 'UTSysop' );
		$obj = new \stdClass;
		$reqs = [ new \stdClass ];

		$mock = $this->getMockBuilder( 'MediaWiki\\Auth\\ConfirmLinkSecondaryAuthenticationProvider' )
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

		$this->assertSame( $obj, $mock->continueSecondaryAccountCreation( $user, $reqs ) );
	}

	/**
	 * Get requests for testing
	 * @return AuthenticationRequest[]
	 */
	private function getLinkRequests() {
		$reqs = [];

		$mb = $this->getMockBuilder( 'MediaWiki\\Auth\\AuthenticationRequest' )
			->setMethods( [ 'getUniqueId' ] );
		for ( $i = 1; $i <= 3; $i++ ) {
			$req = $mb->getMockForAbstractClass();
			$req->expects( $this->any() )->method( 'getUniqueId' )
				->will( $this->returnValue( "Request$i" ) );
			$req->done = false;
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

		$request->getSession()->set( 'state', [
			'maybeLink' => [],
		] );
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginLinkAttempt( $user, 'state' )
		);

		$reqs = $this->getLinkRequests();
		$request->getSession()->set( 'state', [
			'maybeLink' => $reqs
		] );
		$res = $provider->beginLinkAttempt( $user, 'state' );
		$this->assertInstanceOf( 'MediaWiki\\Auth\\AuthenticationResponse', $res );
		$this->assertSame( AuthenticationResponse::UI, $res->status );
		$this->assertSame( 'authprovider-confirmlink-message', $res->message->getKey() );
		$this->assertCount( 1, $res->neededRequests );
		$req = $res->neededRequests[0];
		$this->assertInstanceOf( 'MediaWiki\\Auth\\ConfirmLinkAuthenticationRequest', $req );
		$this->assertSame( $reqs, \TestingAccessWrapper::newFromObject( $req )->linkRequests );
	}

	public function testContinueLinkAttempt() {
		$user = \User::newFromName( 'UTSysop' );
		$obj = new \stdClass;
		$reqs = $this->getLinkRequests();

		// First, test the pass-through for not containing the ConfirmLinkAuthenticationRequest
		$mock = $this->getMockBuilder( 'MediaWiki\\Auth\\ConfirmLinkSecondaryAuthenticationProvider' )
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
		$provider = $this->getMockBuilder( 'MediaWiki\\Auth\\ConfirmLinkSecondaryAuthenticationProvider' )
			->setMethods( [
				'beginLinkAttempt', 'providerAllowsAuthenticationDataChange',
				'providerChangeAuthenticationData'
			] )
			->getMock();
		$provider->expects( $this->never() )->method( 'beginLinkAttempt' );
		$provider->expects( $this->any() )->method( 'providerAllowsAuthenticationDataChange' )
			->will( $this->returnCallback( function ( $req ) use ( $reqs ) {
				$this->assertContains( $req, $reqs );
				return $req->getUniqueId() === 'Request3'
					? \StatusValue::newFatal( 'foo' ) : \StatusValue::newGood();
			} ) );
		$provider->expects( $this->any() )->method( 'providerChangeAuthenticationData' )
			->will( $this->returnCallback( function ( $req ) use ( $reqs ) {
				$this->assertContains( $req, $reqs );
				$req->done = true;
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

		$request->getSession()->set( 'state', [
			'maybeLink' => [],
		] );
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->continueLinkAttempt( $user, 'state', [ $req ] )
		);

		$request->getSession()->set( 'state', [
			'maybeLink' => $reqs
		] );
		$this->assertEquals(
			AuthenticationResponse::newPass(),
			$res = $provider->continueLinkAttempt( $user, 'state', [ $req ] )
		);
		$this->assertFalse( $reqs['Request1']->done );
		$this->assertFalse( $reqs['Request2']->done );
		$this->assertFalse( $reqs['Request3']->done );

		$request->getSession()->set( 'state', [
			'maybeLink' => [ $reqs['Request2'] ],
		] );
		$req->confirmedLinkIDs = [ 'Request1', 'Request2' ];
		$res = $provider->continueLinkAttempt( $user, 'state', [ $req ] );
		$this->assertEquals( AuthenticationResponse::newPass(), $res );
		$this->assertFalse( $reqs['Request1']->done );
		$this->assertTrue( $reqs['Request2']->done );
		$this->assertFalse( $reqs['Request3']->done );
		$reqs['Request2']->done = false;

		$request->getSession()->set( 'state', [
			'maybeLink' => $reqs,
		] );
		$req->confirmedLinkIDs = [ 'Request1', 'Request2' ];
		$res = $provider->continueLinkAttempt( $user, 'state', [ $req ] );
		$this->assertEquals( AuthenticationResponse::newPass(), $res );
		$this->assertTrue( $reqs['Request1']->done );
		$this->assertTrue( $reqs['Request2']->done );
		$this->assertFalse( $reqs['Request3']->done );
		$reqs['Request1']->done = false;
		$reqs['Request2']->done = false;

		$request->getSession()->set( 'state', [
			'maybeLink' => $reqs,
		] );
		$req->confirmedLinkIDs = [ 'Request1', 'Request3' ];
		$res = $provider->continueLinkAttempt( $user, 'state', [ $req ] );
		$this->assertEquals( AuthenticationResponse::UI, $res->status );
		$this->assertCount( 1, $res->neededRequests );
		$this->assertInstanceOf(
			'MediaWiki\\Auth\\ButtonAuthenticationRequest', $res->neededRequests[0]
		);
		$this->assertTrue( $reqs['Request1']->done );
		$this->assertFalse( $reqs['Request2']->done );
		$this->assertFalse( $reqs['Request3']->done );
		$reqs['Request1']->done = false;

		$res = $provider->continueLinkAttempt( $user, 'state', [ $res->neededRequests[0] ] );
		$this->assertEquals( AuthenticationResponse::newPass(), $res );
		$this->assertFalse( $reqs['Request1']->done );
		$this->assertFalse( $reqs['Request2']->done );
		$this->assertFalse( $reqs['Request3']->done );
	}

}
