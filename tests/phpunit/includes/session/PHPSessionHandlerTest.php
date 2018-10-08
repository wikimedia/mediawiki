<?php

namespace MediaWiki\Session;

use Psr\Log\LogLevel;
use MediaWikiTestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Session
 * @covers MediaWiki\Session\PHPSessionHandler
 */
class PHPSessionHandlerTest extends MediaWikiTestCase {

	private function getResetter( &$rProp = null ) {
		$reset = [];

		$rProp = new \ReflectionProperty( PHPSessionHandler::class, 'instance' );
		$rProp->setAccessible( true );
		if ( $rProp->getValue() ) {
			$old = TestingAccessWrapper::newFromObject( $rProp->getValue() );
			$oldManager = $old->manager;
			$oldStore = $old->store;
			$oldLogger = $old->logger;
			$reset[] = new \Wikimedia\ScopedCallback(
				[ PHPSessionHandler::class, 'install' ],
				[ $oldManager, $oldStore, $oldLogger ]
			);
		}

		return $reset;
	}

	public function testEnableFlags() {
		$handler = TestingAccessWrapper::newFromObject(
			$this->getMockBuilder( PHPSessionHandler::class )
				->setMethods( null )
				->disableOriginalConstructor()
				->getMock()
		);

		$rProp = new \ReflectionProperty( PHPSessionHandler::class, 'instance' );
		$rProp->setAccessible( true );
		$reset = new \Wikimedia\ScopedCallback( [ $rProp, 'setValue' ], [ $rProp->getValue() ] );
		$rProp->setValue( $handler );

		$handler->setEnableFlags( 'enable' );
		$this->assertTrue( $handler->enable );
		$this->assertFalse( $handler->warn );
		$this->assertTrue( PHPSessionHandler::isEnabled() );

		$handler->setEnableFlags( 'warn' );
		$this->assertTrue( $handler->enable );
		$this->assertTrue( $handler->warn );
		$this->assertTrue( PHPSessionHandler::isEnabled() );

		$handler->setEnableFlags( 'disable' );
		$this->assertFalse( $handler->enable );
		$this->assertFalse( PHPSessionHandler::isEnabled() );

		$rProp->setValue( null );
		$this->assertFalse( PHPSessionHandler::isEnabled() );
	}

	public function testInstall() {
		$reset = $this->getResetter( $rProp );
		$rProp->setValue( null );

		session_write_close();
		ini_set( 'session.use_cookies', 1 );
		ini_set( 'session.use_trans_sid', 1 );

		$store = new TestBagOStuff();
		$logger = new \TestLogger();
		$manager = new SessionManager( [
			'store' => $store,
			'logger' => $logger,
		] );

		$this->assertFalse( PHPSessionHandler::isInstalled() );
		PHPSessionHandler::install( $manager );
		$this->assertTrue( PHPSessionHandler::isInstalled() );

		$this->assertFalse( wfIniGetBool( 'session.use_cookies' ) );
		$this->assertFalse( wfIniGetBool( 'session.use_trans_sid' ) );

		$this->assertNotNull( $rProp->getValue() );
		$priv = TestingAccessWrapper::newFromObject( $rProp->getValue() );
		$this->assertSame( $manager, $priv->manager );
		$this->assertSame( $store, $priv->store );
		$this->assertSame( $logger, $priv->logger );
	}

	/**
	 * @dataProvider provideHandlers
	 * @param string $handler php serialize_handler to use
	 */
	public function testSessionHandling( $handler ) {
		$this->hideDeprecated( '$_SESSION' );
		$reset[] = $this->getResetter( $rProp );

		$this->setMwGlobals( [
			'wgSessionProviders' => [ [ 'class' => \DummySessionProvider::class ] ],
			'wgObjectCacheSessionExpiry' => 2,
		] );

		$store = new TestBagOStuff();
		$logger = new \TestLogger( true, function ( $m ) {
			// Discard all log events starting with expected prefix
			return preg_match( '/^SessionBackend "\{session\}" /', $m ) ? null : $m;
		} );
		$manager = new SessionManager( [
			'store' => $store,
			'logger' => $logger,
		] );
		PHPSessionHandler::install( $manager );
		$wrap = TestingAccessWrapper::newFromObject( $rProp->getValue() );
		$reset[] = new \Wikimedia\ScopedCallback(
			[ $wrap, 'setEnableFlags' ],
			[ $wrap->enable ? ( $wrap->warn ? 'warn' : 'enable' ) : 'disable' ]
		);
		$wrap->setEnableFlags( 'warn' );

		\Wikimedia\suppressWarnings();
		ini_set( 'session.serialize_handler', $handler );
		\Wikimedia\restoreWarnings();
		if ( ini_get( 'session.serialize_handler' ) !== $handler ) {
			$this->markTestSkipped( "Cannot set session.serialize_handler to \"$handler\"" );
		}

		// Session IDs for testing
		$sessionA = str_repeat( 'a', 32 );
		$sessionB = str_repeat( 'b', 32 );
		$sessionC = str_repeat( 'c', 32 );

		// Set up garbage data in the session
		$_SESSION['AuthenticationSessionTest'] = 'bogus';

		session_id( $sessionA );
		session_start();
		$this->assertSame( [], $_SESSION );
		$this->assertSame( $sessionA, session_id() );

		// Set some data in the session so we can see if it works.
		$rand = mt_rand();
		$_SESSION['AuthenticationSessionTest'] = $rand;
		$expect = [ 'AuthenticationSessionTest' => $rand ];
		session_write_close();
		$this->assertSame( [
			[ LogLevel::WARNING, 'Something wrote to $_SESSION!' ],
		], $logger->getBuffer() );

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

		// Re-fill the session, then test that session_destroy() works.
		$_SESSION['AuthenticationSessionTest'] = $rand;
		session_write_close();
		session_start();
		$this->assertSame( $expect, $_SESSION );
		session_destroy();
		session_id( $sessionA );
		session_start();
		$this->assertSame( [], $_SESSION );
		session_write_close();

		// Test that our session handler won't clone someone else's session
		session_id( $sessionB );
		session_start();
		$this->assertSame( $sessionB, session_id() );
		$_SESSION['id'] = 'B';
		session_write_close();

		session_id( $sessionC );
		session_start();
		$this->assertSame( [], $_SESSION );
		$_SESSION['id'] = 'C';
		session_write_close();

		session_id( $sessionB );
		session_start();
		$this->assertSame( [ 'id' => 'B' ], $_SESSION );
		session_write_close();

		session_id( $sessionC );
		session_start();
		$this->assertSame( [ 'id' => 'C' ], $_SESSION );
		session_destroy();

		session_id( $sessionB );
		session_start();
		$this->assertSame( [ 'id' => 'B' ], $_SESSION );

		// Test merging between Session and $_SESSION
		session_write_close();

		$session = $manager->getEmptySession();
		$session->set( 'Unchanged', 'setup' );
		$session->set( 'Unchanged, null', null );
		$session->set( 'Changed in $_SESSION', 'setup' );
		$session->set( 'Changed in Session', 'setup' );
		$session->set( 'Changed in both', 'setup' );
		$session->set( 'Deleted in Session', 'setup' );
		$session->set( 'Deleted in $_SESSION', 'setup' );
		$session->set( 'Deleted in both', 'setup' );
		$session->set( 'Deleted in Session, changed in $_SESSION', 'setup' );
		$session->set( 'Deleted in $_SESSION, changed in Session', 'setup' );
		$session->persist();
		$session->save();

		session_id( $session->getId() );
		session_start();
		$session->set( 'Added in Session', 'Session' );
		$session->set( 'Added in both', 'Session' );
		$session->set( 'Changed in Session', 'Session' );
		$session->set( 'Changed in both', 'Session' );
		$session->set( 'Deleted in $_SESSION, changed in Session', 'Session' );
		$session->remove( 'Deleted in Session' );
		$session->remove( 'Deleted in both' );
		$session->remove( 'Deleted in Session, changed in $_SESSION' );
		$session->save();
		$_SESSION['Added in $_SESSION'] = '$_SESSION';
		$_SESSION['Added in both'] = '$_SESSION';
		$_SESSION['Changed in $_SESSION'] = '$_SESSION';
		$_SESSION['Changed in both'] = '$_SESSION';
		$_SESSION['Deleted in Session, changed in $_SESSION'] = '$_SESSION';
		unset( $_SESSION['Deleted in $_SESSION'] );
		unset( $_SESSION['Deleted in both'] );
		unset( $_SESSION['Deleted in $_SESSION, changed in Session'] );
		session_write_close();

		$this->assertEquals( [
			'Added in Session' => 'Session',
			'Added in $_SESSION' => '$_SESSION',
			'Added in both' => 'Session',
			'Unchanged' => 'setup',
			'Unchanged, null' => null,
			'Changed in Session' => 'Session',
			'Changed in $_SESSION' => '$_SESSION',
			'Changed in both' => 'Session',
			'Deleted in Session, changed in $_SESSION' => '$_SESSION',
			'Deleted in $_SESSION, changed in Session' => 'Session',
		], iterator_to_array( $session ) );

		$session->clear();
		$session->set( 42, 'forty-two' );
		$session->set( 'forty-two', 42 );
		$session->set( 'wrong', 43 );
		$session->persist();
		$session->save();

		session_start();
		$this->assertArrayHasKey( 'forty-two', $_SESSION );
		$this->assertSame( 42, $_SESSION['forty-two'] );
		$this->assertArrayHasKey( 'wrong', $_SESSION );
		unset( $_SESSION['wrong'] );
		session_write_close();

		$this->assertEquals( [
			42 => 'forty-two',
			'forty-two' => 42,
		], iterator_to_array( $session ) );

		// Test that write doesn't break if the session is invalid
		$session = $manager->getEmptySession();
		$session->persist();
		$id = $session->getId();
		unset( $session );
		session_id( $id );
		session_start();
		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'SessionCheckInfo' => [ function ( &$reason ) {
				$reason = 'Testing';
				return false;
			} ],
		] );
		$this->assertNull( $manager->getSessionById( $id, true ), 'sanity check' );
		session_write_close();

		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'SessionCheckInfo' => [],
		] );
		$this->assertNotNull( $manager->getSessionById( $id, true ), 'sanity check' );
	}

	public static function provideHandlers() {
		return [
			[ 'php' ],
			[ 'php_binary' ],
			[ 'php_serialize' ],
		];
	}

	/**
	 * @dataProvider provideDisabled
	 * @expectedException BadMethodCallException
	 * @expectedExceptionMessage Attempt to use PHP session management
	 */
	public function testDisabled( $method, $args ) {
		$rProp = new \ReflectionProperty( PHPSessionHandler::class, 'instance' );
		$rProp->setAccessible( true );
		$handler = $this->getMockBuilder( PHPSessionHandler::class )
			->setMethods( null )
			->disableOriginalConstructor()
			->getMock();
		TestingAccessWrapper::newFromObject( $handler )->setEnableFlags( 'disable' );
		$oldValue = $rProp->getValue();
		$rProp->setValue( $handler );
		$reset = new \Wikimedia\ScopedCallback( [ $rProp, 'setValue' ], [ $oldValue ] );

		call_user_func_array( [ $handler, $method ], $args );
	}

	public static function provideDisabled() {
		return [
			[ 'open', [ '', '' ] ],
			[ 'read', [ '' ] ],
			[ 'write', [ '', '' ] ],
			[ 'destroy', [ '' ] ],
		];
	}

	/**
	 * @dataProvider provideWrongInstance
	 * @expectedException UnexpectedValueException
	 * @expectedExceptionMessageRegExp /: Wrong instance called!$/
	 */
	public function testWrongInstance( $method, $args ) {
		$handler = $this->getMockBuilder( PHPSessionHandler::class )
			->setMethods( null )
			->disableOriginalConstructor()
			->getMock();
		TestingAccessWrapper::newFromObject( $handler )->setEnableFlags( 'enable' );

		call_user_func_array( [ $handler, $method ], $args );
	}

	public static function provideWrongInstance() {
		return [
			[ 'open', [ '', '' ] ],
			[ 'close', [] ],
			[ 'read', [ '' ] ],
			[ 'write', [ '', '' ] ],
			[ 'destroy', [ '' ] ],
			[ 'gc', [ 0 ] ],
		];
	}

}
