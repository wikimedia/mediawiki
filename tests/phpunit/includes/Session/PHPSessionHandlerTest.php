<?php

namespace MediaWiki\Tests\Session;

use BadMethodCallException;
use DummySessionProvider;
use MediaWiki\MainConfigNames;
use MediaWiki\Session\PHPSessionHandler;
use MediaWiki\Session\SessionManager;
use MediaWiki\Session\SingleBackendSessionStore;
use MediaWikiIntegrationTestCase;
use Psr\Log\LogLevel;
use stdClass;
use TestLogger;
use UnexpectedValueException;
use Wikimedia\ScopedCallback;
use Wikimedia\Stats\StatsFactory;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Session
 * @covers \MediaWiki\Session\PHPSessionHandler
 */
class PHPSessionHandlerTest extends MediaWikiIntegrationTestCase {

	private function getResetter( &$staticAccess = null ) {
		$reset = [];

		$staticAccess = TestingAccessWrapper::newFromClass( PHPSessionHandler::class );
		if ( $staticAccess->instance ) {
			$old = TestingAccessWrapper::newFromObject( $staticAccess->instance );
			$oldManager = $old->manager;
			$oldLogger = $old->logger;
			$reset[] = new ScopedCallback(
				static fn () => PHPSessionHandler::install( $oldManager, $oldLogger )
			);
		}

		return $reset;
	}

	public function testEnableFlags() {
		$handler = TestingAccessWrapper::newFromObject(
			$this->createPartialMock( PHPSessionHandler::class, [] )
		);

		$staticAccess = TestingAccessWrapper::newFromClass( PHPSessionHandler::class );
		$oldValue = $staticAccess->instance;
		$reset = new ScopedCallback( static function () use ( $staticAccess, $oldValue ) {
			$staticAccess->instance = $oldValue;
		} );
		$staticAccess->instance = $handler;

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

		$staticAccess->instance = null;
		$this->assertFalse( PHPSessionHandler::isEnabled() );
	}

	public function testInstall() {
		$reset = $this->getResetter( $staticAccess );
		$staticAccess->instance = null;

		session_write_close();
		ini_set( 'session.use_cookies', 1 );

		$store = new TestBagOStuff();
		// Tolerate debug message, anything else is unexpected
		$logger = new TestLogger( false, static function ( $m ) {
			return preg_match( '/^SessionManager using store/', $m ) ? null : $m;
		} );

		$services = $this->getServiceContainer();
		$manager = new SessionManager(
			$services->getMainConfig(),
			$logger,
			$services->getCentralIdLookup(),
			$services->getHookContainer(),
			$services->getObjectFactory(),
			$services->getProxyLookup(),
			$services->getUrlUtils(),
			$services->getUserNameUtils(),
			$services->getSessionStore()
		);

		$this->assertFalse( PHPSessionHandler::isInstalled() );
		PHPSessionHandler::install( $manager );
		$this->assertTrue( PHPSessionHandler::isInstalled() );

		$this->assertFalse( wfIniGetBool( 'session.use_cookies' ) );

		$this->assertNotNull( $staticAccess->instance );
		$priv = TestingAccessWrapper::newFromObject( $staticAccess->instance );
		$this->assertSame( $manager, $priv->manager );
		$this->assertSame( $logger, $priv->logger );
	}

	/**
	 * @dataProvider provideHandlers
	 * @param string $handler php serialize_handler to use
	 */
	public function testSessionHandling( $handler ) {
		$this->hideDeprecated( '$_SESSION' );
		$reset = $this->getResetter( $staticAccess );

		$this->overrideConfigValues( [
			MainConfigNames::SessionProviders => [ [ 'class' => DummySessionProvider::class ] ],
			MainConfigNames::ObjectCacheSessionExpiry => 2,
		] );

		$logger = new TestLogger( true, static function ( $m ) {
			return (
				// Discard all log events starting with expected prefix
				preg_match( '/^SessionBackend "\{session\}" /', $m )
				// Also discard logs from T264793
				|| preg_match( '/^(Persisting|Unpersisting) session (for|due to)/', $m )
			) ? null : $m;
		} );

		$services = $this->getServiceContainer();
		$manager = new SessionManager(
			$services->getMainConfig(),
			$logger,
			$services->getCentralIdLookup(),
			$services->getHookContainer(),
			$services->getObjectFactory(),
			$services->getProxyLookup(),
			$services->getUrlUtils(),
			$services->getUserNameUtils(),
			new SingleBackendSessionStore( new TestBagOStuff(), $logger, StatsFactory::newNull() )
		);
		PHPSessionHandler::install( $manager );
		$wrap = TestingAccessWrapper::newFromObject( $staticAccess->instance );
		$oldFlags = $wrap->enable ? ( $wrap->warn ? 'warn' : 'enable' ) : 'disable';
		$reset[] = new ScopedCallback(
			static fn () => $wrap->setEnableFlags( $oldFlags )
		);
		$wrap->setEnableFlags( 'warn' );

		@ini_set( 'session.serialize_handler', $handler );
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
			[ LogLevel::DEBUG, 'SessionManager using store MediaWiki\Tests\Session\TestBagOStuff' ],
			[ LogLevel::WARNING, "Something wrote to \$_SESSION['AuthenticationSessionTest']!" ],
		], $logger->getBuffer() );

		// Screw up $_SESSION so we can tell the difference between "this
		// worked" and "this did nothing"
		$_SESSION['AuthenticationSessionTest'] = 'bogus';

		// Re-open the session and see that data was actually reloaded
		session_start();
		$this->assertSame( $expect, $_SESSION );

		// Make sure session_reset() works too.
		$_SESSION['AuthenticationSessionTest'] = 'bogus';
		session_reset();
		$this->assertSame( $expect, $_SESSION );

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
		$this->setTemporaryHook(
			'SessionCheckInfo',
			static function ( &$reason ) {
				$reason = 'Testing';
				return false;
			}
		);
		session_write_close();

		// Object identities change during a serialization roundtrip. This shouldn't trigger a save.
		$logger->clearBuffer();
		$session = $manager->getEmptySession();
		$session->set( 'object in session', new stdClass() );
		$session->persist();
		$session->save();
		session_id( $session->getId() );
		session_start();
		session_write_close();
		// should not log "Something wrote to $_SESSION['object in session']!"
		$this->assertSame( [], $logger->getBuffer() );

		$this->clearHook( 'SessionCheckInfo' );
		$this->assertNotNull( $manager->getSessionById( $id, true ) );
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
	 */
	public function testDisabled( $method, $args ) {
		$staticAccess = TestingAccessWrapper::newFromClass( PHPSessionHandler::class );
		$handler = $this->createPartialMock( PHPSessionHandler::class, [] );
		TestingAccessWrapper::newFromObject( $handler )->setEnableFlags( 'disable' );
		$oldValue = $staticAccess->instance;
		$staticAccess->instance = $handler;
		$reset = new ScopedCallback( static function () use ( $staticAccess, $oldValue ) {
			$staticAccess->instance = $oldValue;
		} );

		$this->expectException( BadMethodCallException::class );
		$this->expectExceptionMessage( "Attempt to use PHP session management" );
		$handler->$method( ...$args );
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
	 */
	public function testWrongInstance( $method, $args ) {
		$handler = $this->createPartialMock( PHPSessionHandler::class, [] );
		TestingAccessWrapper::newFromObject( $handler )->setEnableFlags( 'enable' );

		$this->expectException( UnexpectedValueException::class );
		$this->expectExceptionMessageMatches( "/: Wrong instance called!$/" );
		$handler->$method( ...$args );
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
