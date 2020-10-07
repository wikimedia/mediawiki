<?php

namespace MediaWiki\Session;

use MediaWikiUnitTestCase;
use Psr\Log\LogLevel;
use Wikimedia\TestingAccessWrapper;

/**
 * @group Session
 * @covers MediaWiki\Session\Session
 */
class SessionUnitTest extends MediaWikiUnitTestCase {

	public function testConstructor() {
		$backend = TestUtils::getDummySessionBackend();
		TestingAccessWrapper::newFromObject( $backend )->requests = [ -1 => 'dummy' ];
		TestingAccessWrapper::newFromObject( $backend )->id = new SessionId( 'abc' );

		$session = new Session( $backend, 42, new \TestLogger );
		$priv = TestingAccessWrapper::newFromObject( $session );
		$this->assertSame( $backend, $priv->backend );
		$this->assertSame( 42, $priv->index );

		$request = new \FauxRequest();
		$priv2 = TestingAccessWrapper::newFromObject( $session->sessionWithRequest( $request ) );
		$this->assertSame( $backend, $priv2->backend );
		$this->assertNotSame( $priv->index, $priv2->index );
		$this->assertSame( $request, $priv2->getRequest() );
	}

	/**
	 * @dataProvider provideMethods
	 * @param string $m Method to test
	 * @param array $args Arguments to pass to the method
	 * @param bool $index Whether the backend method gets passed the index
	 * @param bool $ret Whether the method returns a value
	 */
	public function testMethods( $m, $args, $index, $ret ) {
		$mock = $this->getMockBuilder( DummySessionBackend::class )
			->setMethods( [ $m, 'deregisterSession' ] )
			->getMock();
		$mock->expects( $this->once() )->method( 'deregisterSession' )
			->with( $this->identicalTo( 42 ) );

		$tmp = $mock->expects( $this->once() )->method( $m );
		$expectArgs = [];
		if ( $index ) {
			$expectArgs[] = $this->identicalTo( 42 );
		}
		foreach ( $args as $arg ) {
			$expectArgs[] = $this->identicalTo( $arg );
		}
		$tmp = $tmp->with( ...$expectArgs );

		$retval = new \stdClass;
		$tmp->will( $this->returnValue( $retval ) );

		$session = TestUtils::getDummySession( $mock, 42 );

		$this->assertSame( $ret ? $retval : null, $session->$m( ...$args ) );

		// Trigger Session destructor
		$session = null;
	}

	public static function provideMethods() {
		return [
			[ 'getId', [], false, true ],
			[ 'getSessionId', [], false, true ],
			[ 'resetId', [], false, true ],
			[ 'getProvider', [], false, true ],
			[ 'isPersistent', [], false, true ],
			[ 'persist', [], false, false ],
			[ 'unpersist', [], false, false ],
			[ 'shouldRememberUser', [], false, true ],
			[ 'setRememberUser', [ true ], false, false ],
			[ 'getRequest', [], true, true ],
			[ 'getUser', [], false, true ],
			[ 'getAllowedUserRights', [], false, true ],
			[ 'canSetUser', [], false, true ],
			[ 'setUser', [ new \stdClass ], false, false ],
			[ 'suggestLoginUsername', [], true, true ],
			[ 'shouldForceHTTPS', [], false, true ],
			[ 'setForceHTTPS', [ true ], false, false ],
			[ 'getLoggedOutTimestamp', [], false, true ],
			[ 'setLoggedOutTimestamp', [ 123 ], false, false ],
			[ 'getProviderMetadata', [], false, true ],
			[ 'save', [], false, false ],
			[ 'delaySave', [], false, true ],
			[ 'renew', [], false, false ],
		];
	}

	public function testDataAccess() {
		$session = TestUtils::getDummySession();
		$backend = TestingAccessWrapper::newFromObject( $session )->backend;

		$this->assertSame( 1, $session->get( 'foo' ) );
		$this->assertEquals( 'zero', $session->get( 0 ) );
		$this->assertFalse( $backend->dirty );

		$this->assertNull( $session->get( 'null' ) );
		$this->assertEquals( 'default', $session->get( 'null', 'default' ) );
		$this->assertFalse( $backend->dirty );

		$session->set( 'foo', 55 );
		$this->assertEquals( 55, $backend->data['foo'] );
		$this->assertTrue( $backend->dirty );
		$backend->dirty = false;

		$session->set( 1, 'one' );
		$this->assertEquals( 'one', $backend->data[1] );
		$this->assertTrue( $backend->dirty );
		$backend->dirty = false;

		$session->set( 1, 'one' );
		$this->assertFalse( $backend->dirty );

		$this->assertTrue( $session->exists( 'foo' ) );
		$this->assertTrue( $session->exists( 1 ) );
		$this->assertFalse( $session->exists( 'null' ) );
		$this->assertFalse( $session->exists( 100 ) );
		$this->assertFalse( $backend->dirty );

		$session->remove( 'foo' );
		$this->assertArrayNotHasKey( 'foo', $backend->data );
		$this->assertTrue( $backend->dirty );
		$backend->dirty = false;
		$session->remove( 1 );
		$this->assertArrayNotHasKey( 1, $backend->data );
		$this->assertTrue( $backend->dirty );
		$backend->dirty = false;

		$session->remove( 101 );
		$this->assertFalse( $backend->dirty );

		$backend->data = [ 'a', 'b', '?' => 'c' ];
		$this->assertSame( 3, $session->count() );
		$this->assertCount( 3, $session );
		$this->assertFalse( $backend->dirty );

		$data = [];
		foreach ( $session as $key => $value ) {
			$data[$key] = $value;
		}
		$this->assertEquals( $backend->data, $data );
		$this->assertFalse( $backend->dirty );

		$this->assertEquals( $backend->data, iterator_to_array( $session ) );
		$this->assertFalse( $backend->dirty );
	}

	public function testArrayAccess() {
		$logger = new \TestLogger;
		$session = TestUtils::getDummySession( null, -1, $logger );
		$backend = TestingAccessWrapper::newFromObject( $session )->backend;

		$this->assertSame( 1, $session['foo'] );
		$this->assertEquals( 'zero', $session[0] );
		$this->assertFalse( $backend->dirty );

		$logger->setCollect( true );
		$this->assertNull( $session['null'] );
		$logger->setCollect( false );
		$this->assertFalse( $backend->dirty );
		$this->assertSame( [
			[ LogLevel::DEBUG, 'Undefined index (auto-adds to session with a null value): null' ]
		], $logger->getBuffer() );
		$logger->clearBuffer();

		$session['foo'] = 55;
		$this->assertEquals( 55, $backend->data['foo'] );
		$this->assertTrue( $backend->dirty );
		$backend->dirty = false;

		$session[1] = 'one';
		$this->assertEquals( 'one', $backend->data[1] );
		$this->assertTrue( $backend->dirty );
		$backend->dirty = false;

		$session[1] = 'one';
		$this->assertFalse( $backend->dirty );

		$session['bar'] = [ 'baz' => [] ];
		$session['bar']['baz']['quux'] = 2;
		$this->assertEquals( [ 'baz' => [ 'quux' => 2 ] ], $backend->data['bar'] );

		$logger->setCollect( true );
		$session['bar2']['baz']['quux'] = 3;
		$logger->setCollect( false );
		$this->assertEquals( [ 'baz' => [ 'quux' => 3 ] ], $backend->data['bar2'] );
		$this->assertSame( [
			[ LogLevel::DEBUG, 'Undefined index (auto-adds to session with a null value): bar2' ]
		], $logger->getBuffer() );
		$logger->clearBuffer();

		$backend->dirty = false;
		$this->assertTrue( isset( $session['foo'] ) );
		$this->assertTrue( isset( $session[1] ) );
		$this->assertFalse( isset( $session['null'] ) );
		$this->assertFalse( isset( $session['missing'] ) );
		$this->assertFalse( isset( $session[100] ) );
		$this->assertFalse( $backend->dirty );

		unset( $session['foo'] );
		$this->assertArrayNotHasKey( 'foo', $backend->data );
		$this->assertTrue( $backend->dirty );
		$backend->dirty = false;
		unset( $session[1] );
		$this->assertArrayNotHasKey( 1, $backend->data );
		$this->assertTrue( $backend->dirty );
		$backend->dirty = false;

		unset( $session[101] );
		$this->assertFalse( $backend->dirty );
	}

	public function testTokens() {
		$session = TestUtils::getDummySession();
		$priv = TestingAccessWrapper::newFromObject( $session );
		$backend = $priv->backend;

		$token = TestingAccessWrapper::newFromObject( $session->getToken() );
		$this->assertArrayHasKey( 'wsTokenSecrets', $backend->data );
		$this->assertArrayHasKey( 'default', $backend->data['wsTokenSecrets'] );
		$secret = $backend->data['wsTokenSecrets']['default'];
		$this->assertSame( $secret, $token->secret );
		$this->assertSame( '', $token->salt );
		$this->assertTrue( $token->wasNew() );

		$token = TestingAccessWrapper::newFromObject( $session->getToken( 'foo' ) );
		$this->assertSame( $secret, $token->secret );
		$this->assertSame( 'foo', $token->salt );
		$this->assertFalse( $token->wasNew() );

		$backend->data['wsTokenSecrets']['secret'] = 'sekret';
		$token = TestingAccessWrapper::newFromObject(
			$session->getToken( [ 'bar', 'baz' ], 'secret' )
		);
		$this->assertSame( 'sekret', $token->secret );
		$this->assertSame( 'bar|baz', $token->salt );
		$this->assertFalse( $token->wasNew() );

		$session->resetToken( 'secret' );
		$this->assertArrayHasKey( 'wsTokenSecrets', $backend->data );
		$this->assertArrayHasKey( 'default', $backend->data['wsTokenSecrets'] );
		$this->assertArrayNotHasKey( 'secret', $backend->data['wsTokenSecrets'] );

		$session->resetAllTokens();
		$this->assertArrayNotHasKey( 'wsTokenSecrets', $backend->data );
	}

}
