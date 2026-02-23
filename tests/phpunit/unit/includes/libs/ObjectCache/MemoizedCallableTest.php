<?php

namespace Wikimedia\Tests;

use InvalidArgumentException;
use MediaWikiCoversValidator;
use MemoizedCallable;
use PHPUnit\Framework\TestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \MemoizedCallable
 * @group BagOStuff
 */
class MemoizedCallableTest extends TestCase {

	use MediaWikiCoversValidator;

	/**
	 * The memoized callable should relate inputs to outputs in the same
	 * way as the original underlying callable.
	 */
	public function testReturnValuePassedThrough() {
		$memoized = new MemoizedCallable( [ MemoizedCallableCallObject::class, 'reverse' ] );

		$this->assertEquals( 'flow', $memoized->invoke( 'wolf' ) );
	}

	/**
	 * Consecutive calls to the memoized callable with the same arguments
	 * should result in just one invocation of the underlying callable.
	 */
	public function testCallableMemoized() {
		$memoized = new ArrayBackedMemoizedCallable( [ MemoizedCallableCallObject::class, 'computeSomething' ] );

		$this->assertSame( 0, MemoizedCallableCallObject::$computeCalls );

		// First invocation -- delegates to $this->computeSomething()
		$this->assertEquals( 'ok', $memoized->invoke() );

		// Second invocation -- returns memoized result
		$this->assertEquals( 'ok', $memoized->invoke() );

		// $this->computeSomething must be called only once
		$this->assertSame( 1, MemoizedCallableCallObject::$computeCalls );
	}

	public function testInvokeVariadic() {
		$memoized = new MemoizedCallable( 'sprintf' );
		$this->assertEquals(
			$memoized->invokeArgs( [ 'this is %s', 'correct' ] ),
			$memoized->invoke( 'this is %s', 'correct' )
		);
	}

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
		$memoized = TestingAccessWrapper::newFromObject( new MemoizedCallable( 'abs', 100000 ) );
		$this->assertEquals( 86400, $memoized->ttl );

		$memoized = TestingAccessWrapper::newFromObject( new MemoizedCallable( 'abs', -10 ) );
		$this->assertSame( 1, $memoized->ttl );
	}

	/**
	 * Callable names should be distinct.
	 */
	public function testMemoizedClosure() {
		$a = new MemoizedCallable( [ MemoizedCallableCallObject::class, 'makeA' ] );
		$b = new MemoizedCallable( [ MemoizedCallableCallObject::class, 'makeB' ] );

		$this->assertEquals( 'a', $a->invokeArgs() );
		$this->assertEquals( 'b', $b->invokeArgs() );

		$a = TestingAccessWrapper::newFromObject( $a );
		$b = TestingAccessWrapper::newFromObject( $b );

		$this->assertNotEquals(
			$a->callableName,
			$b->callableName
		);

		$c = new ArrayBackedMemoizedCallable( [ MemoizedCallableCallObject::class, 'makeRand' ] );
		$this->assertEquals( $c->invokeArgs(), $c->invokeArgs(), 'memoized random' );
	}

	public function testNonScalarArguments() {
		$memoized = new MemoizedCallable( 'gettype' );
		$this->expectExceptionMessage( "non-scalar argument" );
		$this->expectException( InvalidArgumentException::class );
		$memoized->invoke( (object)[] );
	}

	/**
	 * @dataProvider provideInvalidCallables
	 */
	public function testInvalidCallables( $exceptionMsg, $callable ) {
		$this->expectExceptionMessage( $exceptionMsg );
		$this->expectException( InvalidArgumentException::class );
		$memoized = new MemoizedCallable( $callable );
	}

	public static function provideInvalidCallables() {
		$closureMsg = 'Cannot memoize unnamed closure';
		$objectMsg = 'Cannot memoize object-bound callable';

		yield 'obj' => [ $objectMsg, new MemoizedCallableCallObject() ];
		yield 'obj2' => [ $objectMsg, [ new MemoizedCallableCallObject(), 'makeRand' ] ];
		yield 'arrow' => [ $closureMsg, static fn ( $a ) => $a * 2 ];
		yield 'fcc' => [ $closureMsg, strlen( ... ) ];
		yield 'closure' => [ $closureMsg, static fn () => 'a'
		];
	}

	public function testNotCallable() {
		$this->expectExceptionMessage( "must be a callable" );
		$this->expectException( InvalidArgumentException::class );
		$memoized = new MemoizedCallable( 14 );
	}
}

/**
 * A MemoizedCallable subclass that stores function return values
 * in an instance property rather than APC or APCu.
 */
class ArrayBackedMemoizedCallable extends MemoizedCallable {
	/** @var array */
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

class MemoizedCallableCallObject {

	/** For testCallableMemoized() only */
	public static int $computeCalls = 0;

	public static function makeA() {
		return 'a';
	}

	public static function makeB() {
		return 'b';
	}

	public static function makeRand() {
		return rand();
	}

	public static function reverse( $v ) {
		return strrev( $v );
	}

	public function __invoke() {
		return 'c';
	}

	public static function computeSomething() {
		self::$computeCalls++;
		return 'ok';
	}

}
