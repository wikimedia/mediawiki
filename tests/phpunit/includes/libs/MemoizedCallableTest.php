<?php
/**
 * PHP Unit tests for MemoizedCallable class.
 * @covers MemoizedCallable
 */
class MemoizedCallableTest extends PHPUnit_Framework_TestCase {

	/**
	 * Consecutive calls to the memoized callable with the same arguments
	 * should result in just one invocation of the underlying callable.
	 *
	 * @requires function apc_store
	 */
	public function testCallableMemoized() {
		$observer = $this->getMock( 'stdClass', array( 'compute' ) );
		$observer->expects( $this->once() )->method( 'compute' );
		$memoized = new MemoizedCallable( array( $observer, 'compute' ) );
		$memoized->invoke();
		$memoized->invoke();
	}

	/**
	 * The memoized callable should relate inputs to outputs in the same
	 * way as the original underlying callable.
	 */
	public function testReturnValuePassedThrough() {
		$mock = $this->getMock( 'stdClass', array( 'compute' ) );
		$mock->expects( $this->any() )
			->method( 'compute' )
			->will( $this->returnCallback( 'strrev' ) );

		$memoized = new MemoizedCallable( array( $mock, 'compute' ) );
		$this->assertEquals( $memoized->invoke( 'input' ), 'tupni' );
	}

	/**
	 * TTL values should be capped at 86400.
	 */
	public function testMaxTTL() {
		$memoized = new MemoizedCallable( function () {
		}, 1000000 );
		$this->assertEquals( 86400, PHPUnit_Framework_Assert::readAttribute( $memoized, 'ttl' ) );
	}

	/**
	 * @expectedExceptionMessage non-scalar argument
	 * @expectedException        InvalidArgumentException
	 */
	public function testNonScalarArguments() {
		$memoized = new MemoizedCallable( function ( $a, $b ) {
			return $a + $b;
		} );

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
