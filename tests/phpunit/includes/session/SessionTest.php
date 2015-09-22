<?php

namespace MediaWiki\Session;

use MediaWikiTestCase;
use User;

/**
 * @group Session
 * @covers MediaWiki\Session\Session
 */
class SessionTest extends MediaWikiTestCase {

	public function testConstructor() {
		$backend = TestUtils::getDummySessionBackend();
		\TestingAccessWrapper::newFromObject( $backend )->requests = array( -1 => 'dummy' );
		\TestingAccessWrapper::newFromObject( $backend )->id = new SessionId( 'abc' );

		$session = new Session( $backend, 42 );
		$priv = \TestingAccessWrapper::newFromObject( $session );
		$this->assertSame( $backend, $priv->backend );
		$this->assertSame( 42, $priv->index );

		$request = new \FauxRequest();
		$priv2 = \TestingAccessWrapper::newFromObject( $session->sessionWithRequest( $request ) );
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
		$mock = $this->getMock( 'MediaWiki\\Session\\DummySessionBackend',
			array( $m, 'deregisterSession' ) );
		$mock->expects( $this->once() )->method( 'deregisterSession' )
			->with( $this->identicalTo( 42 ) );

		$tmp = $mock->expects( $this->once() )->method( $m );
		$expectArgs = array();
		if ( $index ) {
			$expectArgs[] = $this->identicalTo( 42 );
		}
		foreach ( $args as $arg ) {
			$expectArgs[] = $this->identicalTo( $arg );
		}
		$tmp = call_user_func_array( array( $tmp, 'with' ), $expectArgs );

		$retval = new \stdClass;
		$tmp->will( $this->returnValue( $retval ) );

		$session = TestUtils::getDummySession( $mock, 42 );

		if ( $ret ) {
			$this->assertSame( $retval, call_user_func_array( array( $session, $m ), $args ) );
		} else {
			$this->assertNull( call_user_func_array( array( $session, $m ), $args ) );
		}

		// Trigger Session destructor
		$session = null;
	}

	public static function provideMethods() {
		return array(
			array( 'getId', array(), false, true ),
			array( 'getSessionId', array(), false, true ),
			array( 'resetId', array(), false, true ),
			array( 'getProvider', array(), false, true ),
			array( 'isPersistent', array(), false, true ),
			array( 'persist', array(), false, false ),
			array( 'shouldRememberUser', array(), false, true ),
			array( 'setRememberUser', array( true ), false, false ),
			array( 'getRequest', array(), true, true ),
			array( 'getUser', array(), false, true ),
			array( 'canSetUser', array(), false, true ),
			array( 'setUser', array( new \stdClass ), false, false ),
			array( 'suggestLoginUsername', array(), true, true ),
			array( 'shouldForceHTTPS', array(), false, true ),
			array( 'setForceHTTPS', array( true ), false, false ),
			array( 'getLoggedOutTimestamp', array(), false, true ),
			array( 'setLoggedOutTimestamp', array( 123 ), false, false ),
			array( 'getProviderMetadata', array(), false, true ),
			array( 'save', array(), false, false ),
			array( 'delaySave', array(), false, true ),
			array( 'renew', array(), false, false ),
		);
	}

	public function testDataAccess() {
		$session = TestUtils::getDummySession();
		$backend = \TestingAccessWrapper::newFromObject( $session )->backend;

		$this->assertEquals( 1, $session->get( 'foo' ) );
		$this->assertEquals( 'zero', $session->get( 0 ) );
		$this->assertFalse( $backend->dirty );

		$this->assertEquals( null, $session->get( 'null' ) );
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

		$backend->data = array( 'a', 'b', '?' => 'c' );
		$this->assertSame( 3, $session->count() );
		$this->assertSame( 3, count( $session ) );
		$this->assertFalse( $backend->dirty );

		$data = array();
		foreach ( $session as $key => $value ) {
			$data[$key] = $value;
		}
		$this->assertEquals( $backend->data, $data );
		$this->assertFalse( $backend->dirty );

		$this->assertEquals( $backend->data, iterator_to_array( $session ) );
		$this->assertFalse( $backend->dirty );
	}

	public function testClear() {
		$session = TestUtils::getDummySession();
		$priv = \TestingAccessWrapper::newFromObject( $session );

		$backend = $this->getMock(
			'MediaWiki\\Session\\DummySessionBackend', array( 'canSetUser', 'setUser', 'save' )
		);
		$backend->expects( $this->once() )->method( 'canSetUser' )
			->will( $this->returnValue( true ) );
		$backend->expects( $this->once() )->method( 'setUser' )
			->with( $this->callback( function ( $user ) {
				return $user instanceof User && $user->isAnon();
			} ) );
		$backend->expects( $this->once() )->method( 'save' );
		$priv->backend = $backend;
		$session->clear();
		$this->assertSame( array(), $backend->data );
		$this->assertTrue( $backend->dirty );

		$backend = $this->getMock(
			'MediaWiki\\Session\\DummySessionBackend', array( 'canSetUser', 'setUser', 'save' )
		);
		$backend->data = array();
		$backend->expects( $this->once() )->method( 'canSetUser' )
			->will( $this->returnValue( true ) );
		$backend->expects( $this->once() )->method( 'setUser' )
			->with( $this->callback( function ( $user ) {
				return $user instanceof User && $user->isAnon();
			} ) );
		$backend->expects( $this->once() )->method( 'save' );
		$priv->backend = $backend;
		$session->clear();
		$this->assertFalse( $backend->dirty );

		$backend = $this->getMock(
			'MediaWiki\\Session\\DummySessionBackend', array( 'canSetUser', 'setUser', 'save' )
		);
		$backend->expects( $this->once() )->method( 'canSetUser' )
			->will( $this->returnValue( false ) );
		$backend->expects( $this->never() )->method( 'setUser' );
		$backend->expects( $this->once() )->method( 'save' );
		$priv->backend = $backend;
		$session->clear();
		$this->assertSame( array(), $backend->data );
		$this->assertTrue( $backend->dirty );
	}

}
