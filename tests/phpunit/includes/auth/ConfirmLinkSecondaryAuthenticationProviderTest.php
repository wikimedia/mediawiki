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

		$this->assertEquals( $response, $provider->getAuthenticationRequests( $action, array() ) );
	}

	public static function provideGetAuthenticationRequests() {
		return array(
			array( AuthManager::ACTION_LOGIN, array() ),
			array( AuthManager::ACTION_CREATE, array() ),
			array( AuthManager::ACTION_LINK, array() ),
			array( AuthManager::ACTION_CHANGE, array() ),
			array( AuthManager::ACTION_REMOVE, array() ),
		);
	}

	public function testBeginSecondaryAuthentication() {
		$user = \User::newFromName( 'UTSysop' );
		$obj = new \stdClass;

		$mock = $this->getMockBuilder( 'MediaWiki\\Auth\\ConfirmLinkSecondaryAuthenticationProvider' )
			->setMethods( array( 'beginLinkAttempt', 'continueLinkAttempt' ) )
			->getMock();
		$mock->expects( $this->once() )->method( 'beginLinkAttempt' )
			->with( $this->identicalTo( $user ), $this->identicalTo( 'AuthManager::authnState' ) )
			->will( $this->returnValue( $obj ) );
		$mock->expects( $this->never() )->method( 'continueLinkAttempt' );

		$this->assertSame( $obj, $mock->beginSecondaryAuthentication( $user, array() ) );
	}

	public function testContinueSecondaryAuthentication() {
		$user = \User::newFromName( 'UTSysop' );
		$obj = new \stdClass;
		$reqs = array( new \stdClass );

		$mock = $this->getMockBuilder( 'MediaWiki\\Auth\\ConfirmLinkSecondaryAuthenticationProvider' )
			->setMethods( array( 'beginLinkAttempt', 'continueLinkAttempt' ) )
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
			->setMethods( array( 'beginLinkAttempt', 'continueLinkAttempt' ) )
			->getMock();
		$mock->expects( $this->once() )->method( 'beginLinkAttempt' )
			->with( $this->identicalTo( $user ), $this->identicalTo( 'AuthManager::accountCreationState' ) )
			->will( $this->returnValue( $obj ) );
		$mock->expects( $this->never() )->method( 'continueLinkAttempt' );

		$this->assertSame( $obj, $mock->beginSecondaryAccountCreation( $user, array() ) );
	}

	public function testContinueSecondaryAccountCreation() {
		$user = \User::newFromName( 'UTSysop' );
		$obj = new \stdClass;
		$reqs = array( new \stdClass );

		$mock = $this->getMockBuilder( 'MediaWiki\\Auth\\ConfirmLinkSecondaryAuthenticationProvider' )
			->setMethods( array( 'beginLinkAttempt', 'continueLinkAttempt' ) )
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
		$reqs = array();

		$mb = $this->getMockBuilder( 'MediaWiki\\Auth\\AuthenticationRequest' )
			->setMethods( array( 'getUniqueId' ) );
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

		$request->getSession()->set( 'state', array(
			'maybeLink' => array(),
		) );
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->beginLinkAttempt( $user, 'state' )
		);

		$reqs = $this->getLinkRequests();
		$request->getSession()->set( 'state', array(
			'maybeLink' => $reqs
		) );
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
		$that = $this;
		$user = \User::newFromName( 'UTSysop' );
		$obj = new \stdClass;
		$reqs = $this->getLinkRequests();

		// First, test the pass-through for not containing the ConfirmLinkAuthenticationRequest
		$mock = $this->getMockBuilder( 'MediaWiki\\Auth\\ConfirmLinkSecondaryAuthenticationProvider' )
			->setMethods( array( 'beginLinkAttempt' ) )
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
			->setMethods( array(
				'beginLinkAttempt', 'providerAllowsAuthenticationDataChange',
				'providerChangeAuthenticationData'
			) )
			->getMock();
		$provider->expects( $this->never() )->method( 'beginLinkAttempt' );
		$provider->expects( $this->any() )->method( 'providerAllowsAuthenticationDataChange' )
			->will( $this->returnCallback( function ( $req ) use ( $that, $reqs ) {
				$that->assertContains( $req, $reqs );
				return $req->getUniqueId() === 'Request3'
					? \StatusValue::newFatal( 'foo' ) : \StatusValue::newGood();
			} ) );
		$provider->expects( $this->any() )->method( 'providerChangeAuthenticationData' )
			->will( $this->returnCallback( function ( $req ) use ( $that, $reqs ) {
				$that->assertContains( $req, $reqs );
				$req->done = true;
			} ) );
		$config = new \HashConfig( array(
			'AuthManagerConfig' => array(
				'preauth' => array(),
				'primaryauth' => array(),
				'secondaryauth' => array(
					array( 'factory' => function () use ( $provider ) {
						return $provider;
					} ),
				),
			),
		) );
		$request = new \FauxRequest();
		$manager = new AuthManager( $request, $config );
		$provider->setManager( $manager );
		$provider = \TestingAccessWrapper::newFromObject( $provider );

		$req = new ConfirmLinkAuthenticationRequest( $reqs );

		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->continueLinkAttempt( $user, 'state', array( $req ) )
		);

		$request->getSession()->set( 'state', array(
			'maybeLink' => array(),
		) );
		$this->assertEquals(
			AuthenticationResponse::newAbstain(),
			$provider->continueLinkAttempt( $user, 'state', array( $req ) )
		);

		$request->getSession()->set( 'state', array(
			'maybeLink' => $reqs
		) );
		$this->assertEquals(
			AuthenticationResponse::newPass(),
			$res = $provider->continueLinkAttempt( $user, 'state', array( $req ) )
		);
		$this->assertFalse( $reqs['Request1']->done );
		$this->assertFalse( $reqs['Request2']->done );
		$this->assertFalse( $reqs['Request3']->done );

		$request->getSession()->set( 'state', array(
			'maybeLink' => array( $reqs['Request2'] ),
		) );
		$req->confirmedLinkIDs = array( 'Request1', 'Request2' );
		$res = $provider->continueLinkAttempt( $user, 'state', array( $req ) );
		$this->assertEquals( AuthenticationResponse::newPass(), $res );
		$this->assertFalse( $reqs['Request1']->done );
		$this->assertTrue( $reqs['Request2']->done );
		$this->assertFalse( $reqs['Request3']->done );
		$reqs['Request2']->done = false;

		$request->getSession()->set( 'state', array(
			'maybeLink' => $reqs,
		) );
		$req->confirmedLinkIDs = array( 'Request1', 'Request2' );
		$res = $provider->continueLinkAttempt( $user, 'state', array( $req ) );
		$this->assertEquals( AuthenticationResponse::newPass(), $res );
		$this->assertTrue( $reqs['Request1']->done );
		$this->assertTrue( $reqs['Request2']->done );
		$this->assertFalse( $reqs['Request3']->done );
		$reqs['Request1']->done = false;
		$reqs['Request2']->done = false;

		$request->getSession()->set( 'state', array(
			'maybeLink' => $reqs,
		) );
		$req->confirmedLinkIDs = array( 'Request1', 'Request3' );
		$res = $provider->continueLinkAttempt( $user, 'state', array( $req ) );
		$this->assertEquals( AuthenticationResponse::UI, $res->status );
		$this->assertCount( 1, $res->neededRequests );
		$this->assertInstanceOf(
			'MediaWiki\\Auth\\ButtonAuthenticationRequest', $res->neededRequests[0]
		);
		$this->assertTrue( $reqs['Request1']->done );
		$this->assertFalse( $reqs['Request2']->done );
		$this->assertFalse( $reqs['Request3']->done );
		$reqs['Request1']->done = false;

		$res = $provider->continueLinkAttempt( $user, 'state', array( $res->neededRequests[0] ) );
		$this->assertEquals( AuthenticationResponse::newPass(), $res );
		$this->assertFalse( $reqs['Request1']->done );
		$this->assertFalse( $reqs['Request2']->done );
		$this->assertFalse( $reqs['Request3']->done );
	}

}
