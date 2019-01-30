<?php
/**
 * PHPUnit tests for MemoizedCallable class.
 * @covers MemoizedCallable
 */
class MemoizedCallableTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/**
	 * The memoized callable should relate inputs to outputs in the same
	 * way as the original underlying callable.
	 */
	public function testReturnValuePassedThrough() {
		$mock = $this->getMockBuilder( stdClass::class )
			->setMethods( [ 'reverse' ] )->getMock();
		$mock->expects( $this->any() )
			->method( 'reverse' )
			->will( $this->returnCallback( 'strrev' ) );

		$memoized = new MemoizedCallable( [ $mock, 'reverse' ] );
		$this->assertEquals( 'flow', $memoized->invoke( 'wolf' ) );
	}

	/**
	 * Consecutive calls to the memoized callable with the same arguments
	 * should result in just one invocation of the underlying callable.
	 *
	 * @requires extension apcu
	 */
	public function testCallableMemoized() {
		$observer = $this->getMockBuilder( stdClass::class )
			->setMethods( [ 'computeSomething' ] )->getMock();
		$observer->expects( $this->once() )
			->method( 'computeSomething' )
			->will( $this->returnValue( 'ok' ) );

		$memoized = new ArrayBackedMemoizedCallable( [ $observer, 'computeSomething' ] );

		// First invocation -- delegates to $observer->computeSomething()
		$this->assertEquals( 'ok', $memoized->invoke() );

		// Second invocation -- returns memoized result
		$this->assertEquals( 'ok', $memoized->invoke() );
	}

	/**
	 * @covers MemoizedCallable::invoke
	 */
	public function testInvokeVariadic() {
		$memoized = new MemoizedCallable( 'sprintf' );
		$this->assertEquals(
			$memoized->invokeArgs( [ 'this is %s', 'correct' ] ),
			$memoized->invoke( 'this is %s', 'correct' )
		);
	}

	/**
	 * @covers MemoizedCallable::call
	 */
	public function testShortcutMethod() {
		$this->assertEquals(
			'this is correct',
			MemoizedCallable::call( 'sprintf', [ 'this is %s', 'correct' ] )
		);
	}

	/**
	 * Outlier TTL values should be coerced to range 1 - 86400.
	 */
	public function testTTLMaxMin() {
		$memoized = new MemoizedCallable( 'abs', 100000 );
		$this->assertEquals( 86400, $this->readAttribute( $memoized, 'ttl' ) );

		$memoized = new MemoizedCallable( 'abs', -10 );
		$this->assertEquals( 1, $this->readAttribute( $memoized, 'ttl' ) );
	}

	/**
	 * Closure names should be distinct.
	 */
	public function testMemoizedClosure() {
		$a = new MemoizedCallable( function () {
			return 'a';
		} );

		$b = new MemoizedCallable( function () {
			return 'b';
		} );

		$this->assertEquals( $a->invokeArgs(), 'a' );
		$this->assertEquals( $b->invokeArgs(), 'b' );

		$this->assertNotEquals(
			$this->readAttribute( $a, 'callableName' ),
			$this->readAttribute( $b, 'callableName' )
		);

		$c = new ArrayBackedMemoizedCallable( function () {
			return rand();
		} );
		$this->assertEquals( $c->invokeArgs(), $c->invokeArgs(), 'memoized random' );
	}

	/**
	 * @expectedExceptionMessage non-scalar argument
	 * @expectedException        InvalidArgumentException
	 */
	public function testNonScalarArguments() {
		$memoized = new MemoizedCallable( 'gettype' );
		$memoized->invoke( new stdClass() );
	}

	/**
	 * @expectedExceptionMessage must be an instance of callable
	 * @expectedException        InvalidArgumentException
	 */
	public function testNotCallable() {
		$memoized = new MemoizedCallable( 14 );
	}
}

/**
 * A MemoizedCallable subclass that stores function return values
 * in an instance property rather than APC or APCu.
 */
class ArrayBackedMemoizedCallable extends MemoizedCallable {
	private $cache = [];

	protected function fetchResult( $key, &$success ) {
		if ( array_key_exists( $key, $this->cache ) ) {
			$success = true;
			return $this->cache[$key];
		}
		$success = false;
		return false;
	}

	protected function storeResult( $key, $result ) {
		$this->cache[$key] = $result;
	}
}
