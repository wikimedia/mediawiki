<?php

/**
 * @group Session
 * @covers MWSession
 */
class MWSessionTest extends MediaWikiTestCase {

	/**
	 * @uses MWSessionBackend
	 */
	public function testConstructor() {
		$rc = new ReflectionClass( 'MWSessionBackend' );
		if ( !method_exists( $rc, 'newInstanceWithoutConstructor' ) ) {
			$this->markTestSkipped( 'ReflectionClass::newInstanceWithoutConstructor isn\'t available' );
		}

		// Since we're not trying to test MWSessionBackend here, but we can't
		// mock it because it's final, we just create an instance without
		// bothering about the constructor.
		$backend = $rc->newInstanceWithoutConstructor();
		TestingAccessWrapper::newFromObject( $backend )->requests = array( 1 => 'dummy' );

		$session = new MWSession( $backend, 42 );
		$priv = TestingAccessWrapper::newFromObject( $session );
		$this->assertSame( $backend, $priv->backend );
		$this->assertSame( 42, $priv->index );
	}

	/**
	 * @dataProvider provideMethods
	 * @param string $m Method to test
	 * @param array $args Arguments to pass to the method
	 * @param bool $index Whether the backend method gets passed the index
	 * @param bool $ret Whether the method returns a value
	 */
	public function testMethods( $m, $args, $index, $ret ) {
		$rc = new ReflectionClass( 'MWSession' );
		if ( !method_exists( $rc, 'newInstanceWithoutConstructor' ) ) {
			$this->markTestSkipped( 'ReflectionClass::newInstanceWithoutConstructor isn\'t available' );
		}

		// Instead of actually constructing the MWSession, we use reflection to
		// bypass the constructor and plug a mock MWSessionBackend into the
		// private fields to avoid having to actually create a MWSessionBackend.
		$mock = $this->getMock( 'stdClass', array( $m, 'deregisterSession' ) );
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

		$retval = new stdclass;
		$tmp->will( $this->returnValue( $retval ) );

		$session = $rc->newInstanceWithoutConstructor();
		$priv = TestingAccessWrapper::newFromObject( $session );
		$priv->backend = $mock;
		$priv->index = 42;

		if ( $ret ) {
			$this->assertSame( $retval, call_user_func_array( array( $session, $m ), $args ) );
		} else {
			$this->assertNull( call_user_func_array( array( $session, $m ), $args ) );
		}

		// Trigger MWSession destructor
		$session = null;
	}

	public static function provideMethods() {
		return array(
			array( 'getId', array(), false, true ),
			array( 'resetId', array(), false, true ),
			array( 'isPersistent', array(), false, true ),
			array( 'persist', array(), false, false ),
			array( 'rememberUser', array(), false, true ),
			array( 'setRememberUser', array( true ), false, false ),
			array( 'getRequest', array(), true, true ),
			array( 'getUser', array(), false, true ),
			array( 'canSetUser', array(), false, true ),
			array( 'setUser', array( new stdclass ), false, false ),
			array( 'suggestLoginUsername', array(), true, true ),
			array( 'forceHTTPS', array(), false, true ),
			array( 'setForceHTTPS', array( true ), false, false ),
			array( 'save', array(), false, false ),
			array( 'renew', array(), false, false ),
		);
	}

	public function testDataAccess() {
		$rc = new ReflectionClass( 'MWSession' );
		if ( !method_exists( $rc, 'newInstanceWithoutConstructor' ) ) {
			$this->markTestSkipped( 'ReflectionClass::newInstanceWithoutConstructor isn\'t available' );
		}

		// Instead of actually constructing the MWSession, we use reflection to
		// bypass the constructor and plug a mock MWSessionBackend into the
		// private fields to avoid having to actually create a MWSessionBackend.
		$backend = new DummySessionBackend;
		$session = $rc->newInstanceWithoutConstructor();
		$priv = TestingAccessWrapper::newFromObject( $session );
		$priv->backend = $backend;
		$priv->index = 42;

		$this->assertEquals( 1, $session->get( 'foo' ) );
		$this->assertEquals( 1, $session['foo'] );
		$this->assertEquals( 'zero', $session->get( 0 ) );
		$this->assertEquals( 'zero', $session[0] );
		$this->assertFalse( $backend->dirty );

		$this->assertEquals( null, $session->get( 'null' ) );
		$this->assertEquals( 'default', $session->get( 'null', 'default' ) );
		$this->assertFalse( $backend->dirty );

		$session->set( 'foo', 55 );
		$this->assertEquals( 55, $backend->data['foo'] );
		$this->assertTrue( $backend->dirty );
		$backend->dirty = false;
		$session['foo'] = 56;
		$this->assertEquals( 56, $backend->data['foo'] );
		$this->assertTrue( $backend->dirty );
		$backend->dirty = false;

		$session->set( 1, 'one' );
		$this->assertEquals( 'one', $backend->data[1] );
		$this->assertTrue( $backend->dirty );
		$backend->dirty = false;
		$session[2] = 'two';
		$this->assertEquals( 'two', $backend->data[2] );
		$this->assertTrue( $backend->dirty );
		$backend->dirty = false;

		$session->set( 1, 'one' );
		$this->assertFalse( $backend->dirty );
		$session[2] = 'two';
		$this->assertFalse( $backend->dirty );

		$this->assertTrue( $session->exists( 'foo' ) );
		$this->assertTrue( $session->exists( 1 ) );
		$this->assertFalse( $session->exists( 'null' ) );
		$this->assertFalse( $session->exists( 100 ) );
		$this->assertFalse( $backend->dirty );

		$this->assertTrue( isset( $session['foo'] ) );
		$this->assertTrue( isset( $session[1] ) );
		$this->assertFalse( isset( $session['null'] ) );
		$this->assertFalse( isset( $session[100] ) );
		$this->assertFalse( $backend->dirty );

		$session->remove( 'foo' );
		$this->assertArrayNotHasKey( 'foo', $backend->data );
		$this->assertTrue( $backend->dirty );
		$backend->dirty = false;
		unset( $session['bar'] );
		$this->assertArrayNotHasKey( 'bar', $backend->data );
		$this->assertTrue( $backend->dirty );
		$backend->dirty = false;
		$session->remove( 1 );
		$this->assertArrayNotHasKey( 1, $backend->data );
		$this->assertTrue( $backend->dirty );
		$backend->dirty = false;
		unset( $session[2] );
		$this->assertArrayNotHasKey( 2, $backend->data );
		$this->assertTrue( $backend->dirty );
		$backend->dirty = false;

		$session->remove( 101 );
		$this->assertFalse( $backend->dirty );
		unset( $session[102] );
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
		$rc = new ReflectionClass( 'MWSession' );
		if ( !method_exists( $rc, 'newInstanceWithoutConstructor' ) ) {
			$this->markTestSkipped( 'ReflectionClass::newInstanceWithoutConstructor isn\'t available' );
		}

		// Instead of actually constructing the MWSession, we use reflection to
		// bypass the constructor and plug a mock MWSessionBackend into the
		// private fields to avoid having to actually create a MWSessionBackend.
		$session = $rc->newInstanceWithoutConstructor();
		$priv = TestingAccessWrapper::newFromObject( $session );
		$priv->backend = new DummySessionBackend;
		$priv->index = 42;

		$backend = $this->getMock( 'DummySessionBackend', array( 'canSetUser', 'setUser', 'save' ) );
		$backend->expects( $this->once() )->method( 'canSetUser' )
			->willReturn( true );
		$backend->expects( $this->once() )->method( 'setUser' )
			->with( $this->callback( function ( $user ) {
				return $user instanceof User && $user->isAnon();
			} ) );
		$backend->expects( $this->once() )->method( 'save' );
		$priv->backend = $backend;
		$session->clear();
		$this->assertSame( array(), $backend->data );
		$this->assertTrue( $backend->dirty );

		$backend = $this->getMock( 'DummySessionBackend', array( 'canSetUser', 'setUser', 'save' ) );
		$backend->data = array();
		$backend->expects( $this->once() )->method( 'canSetUser' )
			->willReturn( true );
		$backend->expects( $this->once() )->method( 'setUser' )
			->with( $this->callback( function ( $user ) {
				return $user instanceof User && $user->isAnon();
			} ) );
		$backend->expects( $this->once() )->method( 'save' );
		$priv->backend = $backend;
		$session->clear();
		$this->assertFalse( $backend->dirty );

		$backend = $this->getMock( 'DummySessionBackend', array( 'canSetUser', 'setUser', 'save' ) );
		$backend->expects( $this->once() )->method( 'canSetUser' )
			->willReturn( false );
		$backend->expects( $this->never() )->method( 'setUser' );
		$backend->expects( $this->once() )->method( 'save' );
		$priv->backend = $backend;
		$session->clear();
		$this->assertSame( array(), $backend->data );
		$this->assertTrue( $backend->dirty );
	}

	public function testSessionWithRequest() {
		$rc = new ReflectionClass( 'MWSession' );
		if ( !method_exists( $rc, 'newInstanceWithoutConstructor' ) ) {
			$this->markTestSkipped( 'ReflectionClass::newInstanceWithoutConstructor isn\'t available' );
		}

		// Instead of actually constructing the MWSession, we use reflection to
		// bypass the constructor and plug a mock MWSessionBackend into the
		// private fields to avoid having to actually create a MWSessionBackend.
		$session = $rc->newInstanceWithoutConstructor();
		$priv = TestingAccessWrapper::newFromObject( $session );
		$priv->backend = new DummySessionBackend;
		$priv->index = 42;

		$request = new FauxRequest();
		$ret = new stdclass;

		$backend = $this->getMock( 'DummySessionBackend', array( 'getSession' ) );
		$backend->expects( $this->once() )->method( 'getSession' )
			->with( $this->identicalTo( $request ) )
			->willReturn( $ret );
		$priv->backend = $backend;

		$this->assertSame( $ret, $session->sessionWithRequest( $request ) );
	}

}

class DummySessionBackend {
	public $data = array(
		'foo' => 1,
		'bar' => 2,
		0 => 'zero',
	);
	public $dirty = false;

	public function &getData() {
		return $this->data;
	}

	public function dirty() {
		$this->dirty = true;
	}

	public function deregisterSession( $index ) {
	}
}
