<?php

/**
 * @group AuthManager
 * @covers AuthnSession
 */
class AuthnSessionTest extends MediaWikiTestCase {

	private function getMockSession( $key, $priority, $resettable, $methods = array() ) {
		$builder = $this->getMockBuilder( 'AuthnSession' );
		if ( $methods ) {
			$builder->setMethods( $methods );
		} else {
			$builder->setMockClassName( 'AuthnSessionMock' );
		}
		$session = $builder
			->disableOriginalConstructor()
			->getMockForAbstractClass();

		$session->method( 'canResetSessionKey' )
			->willReturn( $resettable );

		$session->__construct( new HashBagOStuff(), new Psr\Log\NullLogger(), $key, $priority );

		return $session;
	}

	public function testBasics() {
		try {
			$this->getMockSession( 'foo', -1, true );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( 'AuthnSession::__construct: Invalid priority', $ex->getMessage() );
		}
		try {
			$this->getMockSession( 'foo', AuthnSession::MAX_PRIORITY + 1, true );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( 'AuthnSession::__construct: Invalid priority', $ex->getMessage() );
		}

		try {
			$this->getMockSession( null, 1, false );
			$this->fail( 'Expected exception not thrown' );
		} catch ( InvalidArgumentException $ex ) {
			$this->assertSame( 'Cannot set null key with AuthnSessionMock', $ex->getMessage() );
		}

		$store = new HashBagOStuff();
		$logger = new Psr\Log\NullLogger();
		$mock = TestingAccessWrapper::newFromObject(
			$this->getMockForAbstractClass( 'AuthnSession', array(
				$store, $logger, 'foobar', 42
			) )
		);
		$this->assertSame( 'foobar', $mock->key );
		$this->assertSame( 'foobar', $mock->getSessionKey() );
		$this->assertSame( 42, $mock->priority );
		$this->assertSame( 42, $mock->getSessionPriority() );
		$this->assertSame( $store, $mock->store );
		$this->assertSame( $logger, $mock->logger );
	}

	public function testSetupPHPSessionHandler() {
		// Ignore "headers already sent" warnings during this test
		set_error_handler( function ( $errno, $errstr ) use ( &$warnings ) {
			if ( preg_match( '/headers already sent/', $errstr ) ) {
				return true;
			}
			return false;
		} );
		$reset = new ScopedCallback( 'restore_error_handler' );

		session_id( 'bogus' );

		$session = $this->getMockSession( 'foobar', 42, true );
		$sessionPriv = TestingAccessWrapper::newFromObject( $session );

		$session->setupPHPSessionHandler( 12345 );
		$this->assertSame( 12345, $sessionPriv->lifetime );

		$this->assertSame( false, wfIniGetBool( 'session.use_cookies' ) );
		$this->assertSame( false, wfIniGetBool( 'session.use_trans_sid' ) );
		$this->assertSame( 'foobar', session_id() );

		session_write_close();
		session_id( 'bogus' );

		$session = $this->getMockSession( null, 42, true );
		$session->setupPHPSessionHandler( 12345 );
		$this->assertSame( 'bogus', session_id() );
	}

	public function testSessionHandling() {
		// Ignore "headers already sent" warnings during this test
		set_error_handler( function ( $errno, $errstr ) use ( &$warnings ) {
			if ( preg_match( '/headers already sent/', $errstr ) ) {
				return true;
			}
			return false;
		} );
		$reset = new ScopedCallback( 'restore_error_handler' );

		// Set up garbage data in the session
		$_SESSION['AuthnSessionTest'] = 'bogus';

		// Create the session, assert that the data is clear
		$session = $this->getMockSession( 'foobar', 42, true );
		$session->setupPHPSessionHandler( 1 );
		$this->assertSame( array(), $_SESSION );

		// Set some data in the session so we can see if it works.
		$rand = mt_rand();
		$_SESSION['AuthnSessionTest'] = $rand;
		$expect = array( 'AuthnSessionTest' => $rand );
		session_write_close();

		// Screw up $_SESSION so we can tell the difference between "this
		// worked" and "this did nothing"
		$_SESSION['AuthnSessionTest'] = 'bogus';

		// Re-open the session and see that data was actually reloaded
		session_start();
		$this->assertSame( $expect, $_SESSION );

		// Make sure session_reset() works too.
		$_SESSION['AuthnSessionTest'] = 'bogus';
		session_reset();
		$this->assertSame( $expect, $_SESSION );

		// Test expiry
		session_write_close();
		ini_set( 'session.gc_divisor', 1 );
		ini_set( 'session.gc_probability', 1 );
		sleep( 2 );
		session_start();
		$this->assertSame( array(), $_SESSION );

		// Re-fill the session, then test that session_destroy() works.
		$_SESSION['AuthnSessionTest'] = $rand;
		session_write_close();
		session_start();
		$this->assertSame( $expect, $_SESSION );
		session_destroy();
		session_start();
		$this->assertSame( array(), $_SESSION );
		session_write_close();

		// Test that starting a session with a null key creates one (if possible)
		session_id( 'bogus' );
		$session = $this->getMockSession( null, 42, true, array( 'setNewSessionKey' ) );
		$session->setupPHPSessionHandler( 1 );
		$this->assertSame( 'bogus', session_id() );
		session_start();
		$this->assertNotNull( $session->getSessionKey() );
		$this->assertSame( $session->getSessionKey(), session_id() );

		// Test that our session handler won't clone someone else's session
		$session = $this->getMockSession( 'foobar', 42, true );
		$session->setupPHPSessionHandler( 1 );
		$_SESSION['id'] = 'foobar';
		session_write_close();

		session_id( 'bogus' );
		session_start();
		$this->assertSame( array(), $_SESSION );
		$_SESSION['id'] = 'bogus';
		session_write_close();

		session_id( 'foobar' );
		session_start();
		$this->assertSame( array( 'id' => 'foobar' ), $_SESSION );
		session_write_close();

		session_id( 'bogus' );
		session_start();
		$this->assertNotSame( array( 'id' => 'foobar' ), $_SESSION );
		session_destroy();

		session_id( 'foobar' );
		session_start();
		$this->assertSame( array( 'id' => 'foobar' ), $_SESSION );
	}

	public function testGenericImplementations() {
		$session = $this->getMockSession( 'foobar', 42, true );
		$this->assertNull( $session->getAuthenticationRequestType() );
		$this->assertNull( $session->suggestLoginUsername() );
		$this->assertFalse( $session->forceHTTPS() );

		$session = TestingAccessWrapper::newFromObject( $this->getMockSession( 'foo', 42, true ) );
		try {
			$session->setNewSessionKey( 'X' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( BadMethodCallException $ex ) {
			$this->assertSame(
				'AuthnSession::setNewSessionKey must be implemented if AuthnSessionMock::canResetSessionKey() returns true',
				$ex->getMessage()
			);
		}

		$session = TestingAccessWrapper::newFromObject( $this->getMockSession( 'foo', 42, false ) );
		try {
			$session->setNewSessionKey( 'X' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( LogicException $ex ) {
			$this->assertSame(
				'AuthnSession::setNewSessionKey was called when AuthnSessionMock::canResetSessionKey() returns false',
				$ex->getMessage()
			);
		}

		$session = TestingAccessWrapper::newFromObject( $this->getMockSession( 'foo', 42, true ) );
		$session->method( 'canSetSessionUserInfo' )
			->will( $this->onConsecutiveCalls( true, false ) );
		try {
			$session->setSessionUserInfo( 0, null, null, null );
			$this->fail( 'Expected exception not thrown' );
		} catch ( BadMethodCallException $ex ) {
			$this->assertSame(
				'AuthnSession::setSessionUserInfo must be implemented if AuthnSessionMock::canSetSessionUserInfo() returns true',
				$ex->getMessage()
			);
		}
		try {
			$session->setSessionUserInfo( 0, null, null, null );
			$this->fail( 'Expected exception not thrown' );
		} catch ( LogicException $ex ) {
			$this->assertSame(
				'AuthnSession::setSessionUserInfo was called when AuthnSessionMock::canSetSessionUserInfo() returns false',
				$ex->getMessage()
			);
		}
	}

	public function testResetSessionKey() {
		// Ignore "headers already sent" warnings during this test
		set_error_handler( function ( $errno, $errstr ) use ( &$warnings ) {
			if ( preg_match( '/headers already sent/', $errstr ) ) {
				return true;
			}
			return false;
		} );
		$reset = new ScopedCallback( 'restore_error_handler' );

		$methods = array( 'setNewSessionKey' );

		// Easy case, resetting isn't supported
		$session = $this->getMockSession( 'foobar', 42, false, $methods );
		$session->expects( $this->never() )->method( 'setNewSessionKey' );
		$this->assertSame( 'foobar', $session->resetSessionKey() );

		// Hard case, resetting is supported
		$session = $this->getMockSession( 'foobar', 42, true, $methods );
		$session->expects( $this->once() )->method( 'setNewSessionKey' )->willReturn( null );
		$sessionPriv = TestingAccessWrapper::newFromObject( $session );
		$session->setupPHPSessionHandler( 1 );
		$_SESSION['AuthnSessionTest'] = 'data!';
		session_write_close();
		session_start();
		$newKey = $session->resetSessionKey();
		$this->assertNotSame( 'foobar', $newKey );
		$this->assertSame( $newKey, session_id() );
		$this->assertSame( array( 'AuthnSessionTest' => 'data!' ), $_SESSION );

		// Make sure old session was destroyed
		session_write_close();
		$sessionPriv->key = 'foobar';
		$session->setupPHPSessionHandler( 1 );
		$this->assertSame( array(), $_SESSION );

		// Also test it doesn't copy $_SESSION when no session was open
		$session = $this->getMockSession( 'foobar', 42, true, $methods );
		$session->setupPHPSessionHandler( 1 );
		session_write_close();
		session_id( 'bogus' );
		$_SESSION['bogus'] = 'bogus';
		$session->resetSessionKey();
		$this->assertArrayNotHasKey( 'bogus', $_SESSION );

		// Also test it doesn't copy $_SESSION when a different session was open
		session_write_close();
		session_id( 'bogus' );
		session_start();
		$_SESSION['bogus'] = 'bogus';
		$session->resetSessionKey();
		$this->assertArrayNotHasKey( 'bogus', $_SESSION );
	}

	public function testToString() {
		$session = $this->getMockSession( 'foobar', 42, true, array( 'getSessionUserInfo' ) );
		$session->method( 'getSessionUserInfo' )
			->will( $this->onConsecutiveCalls(
				array( 0, null, null ),
				array( 1, 'User', 'Token' )
			) );

		$class = get_class( $session );
		$this->assertSame( "$class<anon>", $session->__toString() );
		$this->assertSame( "$class<1=User>", $session->__toString() );
	}
}
