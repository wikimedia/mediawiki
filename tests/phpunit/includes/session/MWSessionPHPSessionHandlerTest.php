<?php

/**
 * @group Session
 * @covers MWSessionPHPSessionHandler
 */
class MWSessionPHPSessionHandlerTest extends MediaWikiTestCase {

	public static function provideHandlers() {
		return array(
			array( 'php' ),
			array( 'php_serialize' ),
		);
	}

	/**
	 * @uses MWSessionManager
	 * @uses MWSessionInfo
	 * @uses MWSessionUserInfo
	 * @uses MWSession
	 * @uses MWSessionProvider
	 * @uses MWSessionBackend
	 * @dataProvider provideHandlers
	 * @param string $handler php serialize_handler to use
	 */
	public function testSessionHandling( $handler ) {
		// Ignore "headers already sent" warnings during this test
		set_error_handler( function ( $errno, $errstr ) use ( &$warnings ) {
			if ( preg_match( '/headers already sent/', $errstr ) ) {
				return true;
			}
			return false;
		} );
		$reset = new ScopedCallback( function () {
			// Reinstall the global session save handler
			$rProp = new ReflectionProperty( 'MWSessionManager', 'instance' );
			$rProp->setAccessible( true );
			$old = $rProp->getValue();
			if ( $old ) {
				$priv = TestingAccessWrapper::newFromObject( $old );
				MWSessionPHPSessionHandler::install( $priv->store, $priv->logger );
			}
			// Remove the error handler we added
			restore_error_handler();
		} );

		$this->setMwGlobals( array(
			'wgMWSessionProviders' => array( array( 'class' => 'DummySessionProvider' ) ),
			'wgObjectCacheSessionExpiry' => 2,
		) );

		$store = new HashBagOStuff();
		$logger = new Psr\Log\NullLogger();

		// Set up the session manager
		$manager = new MWSessionManager( array(
			'store' => $store,
			'logger' => $logger,
			'request' => new FauxRequest,
		) );
		$rProp = new ReflectionProperty( 'MWSessionManager', 'instance' );
		$rProp->setAccessible( true );
		$old = $rProp->getValue();
		$reset2 = new ScopedCallback( array( $rProp, 'setValue' ), array( $old ) );
		$rProp->setValue( $manager );
		$this->assertSame( $manager, MWSessionManager::singleton(), 'Sanity check' );
		MWSessionPHPSessionHandler::install( $store, $logger );

		MediaWiki\suppressWarnings();
		ini_set( 'session.serialize_handler', $handler );
		MediaWiki\restoreWarnings();
		if ( ini_get( 'session.serialize_handler' ) !== $handler ) {
			$this->markTestSkipped( "Cannot set session.serialize_handler to \"$handler\"" );
		}

		// Session IDs for testing
		$sessionA = str_repeat( 'a', 32 );
		$sessionB = str_repeat( 'b', 32 );
		$sessionC = str_repeat( 'c', 32 );
		$sessionD = str_repeat( 'd', 32 );

		// Set up garbage data in the session
		$_SESSION['AuthenticationSessionTest'] = 'bogus';

		session_id( $sessionA );
		session_start();
		$this->assertSame( array(), $_SESSION );
		$this->assertSame( $sessionA, session_id() );

		// Set some data in the session so we can see if it works.
		$rand = mt_rand();
		$_SESSION['AuthenticationSessionTest'] = $rand;
		$expect = array( 'AuthenticationSessionTest' => $rand );
		session_write_close();

		// Screw up $_SESSION so we can tell the difference between "this
		// worked" and "this did nothing"
		$_SESSION['AuthenticationSessionTest'] = 'bogus';

		// Re-open the session and see that data was actually reloaded
		session_start();
		$this->assertSame( $expect, $_SESSION );

		// Make sure session_reset() works too.
		if ( function_exists( 'session_reset' ) ) {
			$_SESSION['AuthenticationSessionTest'] = 'bogus';
			session_reset();
			$this->assertSame( $expect, $_SESSION );
		}

		// Test expiry
		session_write_close();
		ini_set( 'session.gc_divisor', 1 );
		ini_set( 'session.gc_probability', 1 );
		sleep( 3 );
		session_start();
		$this->assertSame( array(), $_SESSION );

		// Re-fill the session, then test that session_destroy() works.
		$_SESSION['AuthenticationSessionTest'] = $rand;
		session_write_close();
		session_start();
		$this->assertSame( $expect, $_SESSION );
		session_destroy();
		session_id( $sessionA );
		session_start();
		$this->assertSame( array(), $_SESSION );
		session_write_close();

		// Test that our session handler won't clone someone else's session
		session_id( $sessionB );
		session_start();
		$this->assertSame( $sessionB, session_id() );
		$_SESSION['id'] = 'B';
		session_write_close();

		session_id( $sessionC );
		session_start();
		$this->assertSame( array(), $_SESSION );
		$_SESSION['id'] = 'C';
		session_write_close();

		session_id( $sessionB );
		session_start();
		$this->assertSame( array( 'id' => 'B' ), $_SESSION );
		session_write_close();

		session_id( $sessionC );
		session_start();
		$this->assertSame( array( 'id' => 'C' ), $_SESSION );
		session_destroy();

		session_id( $sessionB );
		session_start();
		$this->assertSame( array( 'id' => 'B' ), $_SESSION );

		// Test merging between MWSession and $_SESSION
		session_write_close();

		$session = $manager->getEmptySession( null, $sessionD );
		$session->set( 'Unchanged', 'setup' );
		$session->set( 'Changed in $_SESSION', 'setup' );
		$session->set( 'Changed in MWSession', 'setup' );
		$session->set( 'Changed in both', 'setup' );
		$session->set( 'Deleted in MWSession', 'setup' );
		$session->set( 'Deleted in $_SESSION', 'setup' );
		$session->set( 'Deleted in both', 'setup' );
		$session->set( 'Deleted in MWSession, changed in $_SESSION', 'setup' );
		$session->set( 'Deleted in $_SESSION, changed in MWSession', 'setup' );
		$session->persist();
		$session->save();

		session_id( $sessionD );
		session_start();
		$session->set( 'Added in MWSession', 'MWSession' );
		$session->set( 'Added in both', 'MWSession' );
		$session->set( 'Changed in MWSession', 'MWSession' );
		$session->set( 'Changed in both', 'MWSession' );
		$session->set( 'Deleted in $_SESSION, changed in MWSession', 'MWSession' );
		$session->remove( 'Deleted in MWSession' );
		$session->remove( 'Deleted in both' );
		$session->remove( 'Deleted in MWSession, changed in $_SESSION' );
		$session->save();
		$_SESSION['Added in $_SESSION'] = '$_SESSION';
		$_SESSION['Added in both'] = '$_SESSION';
		$_SESSION['Changed in $_SESSION'] = '$_SESSION';
		$_SESSION['Changed in both'] = '$_SESSION';
		$_SESSION['Deleted in MWSession, changed in $_SESSION'] = '$_SESSION';
		unset( $_SESSION['Deleted in $_SESSION'] );
		unset( $_SESSION['Deleted in both'] );
		unset( $_SESSION['Deleted in $_SESSION, changed in MWSession'] );
		session_write_close();

		$this->assertEquals( array(
			'Added in MWSession' => 'MWSession',
			'Added in $_SESSION' => '$_SESSION',
			'Added in both' => 'MWSession',
			'Unchanged' => 'setup',
			'Changed in MWSession' => 'MWSession',
			'Changed in $_SESSION' => '$_SESSION',
			'Changed in both' => 'MWSession',
			'Deleted in MWSession, changed in $_SESSION' => '$_SESSION',
			'Deleted in $_SESSION, changed in MWSession' => 'MWSession',
		), iterator_to_array( $session ) );
	}

}
