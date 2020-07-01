<?php

namespace MediaWiki\Auth;

use BagOStuff;
use HashBagOStuff;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Wikimedia\TestingAccessWrapper;

/**
 * @group AuthManager
 * @covers \MediaWiki\Auth\Throttler
 */
class ThrottlerTest extends \MediaWikiIntegrationTestCase {
	public function testConstructor() {
		$cache = new \HashBagOStuff();
		$logger = $this->getMockBuilder( AbstractLogger::class )
			->setMethods( [ 'log' ] )
			->getMockForAbstractClass();

		$throttler = new Throttler(
			[ [ 'count' => 123, 'seconds' => 456 ] ],
			[ 'type' => 'foo', 'cache' => $cache ]
		);
		$throttler->setLogger( $logger );
		$throttlerPriv = TestingAccessWrapper::newFromObject( $throttler );
		$this->assertSame( [ [ 'count' => 123, 'seconds' => 456 ] ], $throttlerPriv->conditions );
		$this->assertSame( 'foo', $throttlerPriv->type );
		$this->assertSame( $cache, $throttlerPriv->cache );
		$this->assertSame( $logger, $throttlerPriv->logger );

		$throttler = new Throttler( [ [ 'count' => 123, 'seconds' => 456 ] ] );
		$throttler->setLogger( new NullLogger() );
		$throttlerPriv = TestingAccessWrapper::newFromObject( $throttler );
		$this->assertSame( [ [ 'count' => 123, 'seconds' => 456 ] ], $throttlerPriv->conditions );
		$this->assertSame( 'custom', $throttlerPriv->type );
		$this->assertInstanceOf( BagOStuff::class, $throttlerPriv->cache );
		$this->assertInstanceOf( LoggerInterface::class, $throttlerPriv->logger );

		$this->setMwGlobals( [ 'wgPasswordAttemptThrottle' => [ [ 'count' => 321,
			'seconds' => 654 ] ] ] );
		$throttler = new Throttler();
		$throttler->setLogger( new NullLogger() );
		$throttlerPriv = TestingAccessWrapper::newFromObject( $throttler );
		$this->assertSame( [ [ 'count' => 321, 'seconds' => 654 ] ], $throttlerPriv->conditions );
		$this->assertSame( 'password', $throttlerPriv->type );
		$this->assertInstanceOf( BagOStuff::class, $throttlerPriv->cache );
		$this->assertInstanceOf( LoggerInterface::class, $throttlerPriv->logger );

		try {
			new Throttler( [], [ 'foo' => 1, 'bar' => 2, 'baz' => 3 ] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \InvalidArgumentException $ex ) {
			$this->assertSame( 'unrecognized parameters: foo, bar, baz', $ex->getMessage() );
		}
	}

	/**
	 * @dataProvider provideNormalizeThrottleConditions
	 */
	public function testNormalizeThrottleConditions( $condition, $normalized ) {
		$throttler = new Throttler( $condition );
		$throttler->setLogger( new NullLogger() );
		$throttlerPriv = TestingAccessWrapper::newFromObject( $throttler );
		$this->assertSame( $normalized, $throttlerPriv->conditions );
	}

	public function provideNormalizeThrottleConditions() {
		return [
			[
				[],
				[],
			],
			[
				[ 'count' => 1, 'seconds' => 2 ],
				[ [ 'count' => 1, 'seconds' => 2 ] ],
			],
			[
				[ [ 'count' => 1, 'seconds' => 2 ], [ 'count' => 2, 'seconds' => 3 ] ],
				[ [ 'count' => 1, 'seconds' => 2 ], [ 'count' => 2, 'seconds' => 3 ] ],
			],
		];
	}

	public function testNormalizeThrottleConditions2() {
		$priv = TestingAccessWrapper::newFromClass( Throttler::class );
		$this->assertSame( [], $priv->normalizeThrottleConditions( null ) );
		$this->assertSame( [], $priv->normalizeThrottleConditions( 'bad' ) );
	}

	public function testIncrease() {
		$cache = new \HashBagOStuff();
		$throttler = new Throttler( [
			[ 'count' => 2, 'seconds' => 10, ],
			[ 'count' => 4, 'seconds' => 15, 'allIPs' => true ],
		], [ 'cache' => $cache ] );
		$throttler->setLogger( new NullLogger() );

		$result = $throttler->increase( 'SomeUser', '1.2.3.4' );
		$this->assertFalse( $result, 'should not throttle' );

		$result = $throttler->increase( 'SomeUser', '1.2.3.4' );
		$this->assertFalse( $result, 'should not throttle' );

		$result = $throttler->increase( 'SomeUser', '1.2.3.4' );
		$this->assertSame( [ 'throttleIndex' => 0, 'count' => 2, 'wait' => 10 ], $result );

		$result = $throttler->increase( 'OtherUser', '1.2.3.4' );
		$this->assertFalse( $result, 'should not throttle' );

		$result = $throttler->increase( 'SomeUser', '2.3.4.5' );
		$this->assertFalse( $result, 'should not throttle' );

		$result = $throttler->increase( 'SomeUser', '3.4.5.6' );
		$this->assertFalse( $result, 'should not throttle' );

		$result = $throttler->increase( 'SomeUser', '3.4.5.6' );
		$this->assertSame( [ 'throttleIndex' => 1, 'count' => 4, 'wait' => 15 ], $result );
	}

	public function testZeroCount() {
		$cache = new \HashBagOStuff();
		$throttler = new Throttler( [ [ 'count' => 0, 'seconds' => 10 ] ], [ 'cache' => $cache ] );
		$throttler->setLogger( new NullLogger() );

		$result = $throttler->increase( 'SomeUser', '1.2.3.4' );
		$this->assertFalse( $result, 'should not throttle, count=0 is ignored' );

		$result = $throttler->increase( 'SomeUser', '1.2.3.4' );
		$this->assertFalse( $result, 'should not throttle, count=0 is ignored' );

		$result = $throttler->increase( 'SomeUser', '1.2.3.4' );
		$this->assertFalse( $result, 'should not throttle, count=0 is ignored' );
	}

	public function testNamespacing() {
		$cache = new \HashBagOStuff();
		$throttler1 = new Throttler( [ [ 'count' => 1, 'seconds' => 10 ] ],
			[ 'cache' => $cache, 'type' => 'foo' ] );
		$throttler2 = new Throttler( [ [ 'count' => 1, 'seconds' => 10 ] ],
			[ 'cache' => $cache, 'type' => 'foo' ] );
		$throttler3 = new Throttler( [ [ 'count' => 1, 'seconds' => 10 ] ],
			[ 'cache' => $cache, 'type' => 'bar' ] );
		$throttler1->setLogger( new NullLogger() );
		$throttler2->setLogger( new NullLogger() );
		$throttler3->setLogger( new NullLogger() );

		$throttled = [ 'throttleIndex' => 0, 'count' => 1, 'wait' => 10 ];

		$result = $throttler1->increase( 'SomeUser', '1.2.3.4' );
		$this->assertFalse( $result, 'should not throttle' );

		$result = $throttler1->increase( 'SomeUser', '1.2.3.4' );
		$this->assertEquals( $throttled, $result, 'should throttle' );

		$result = $throttler2->increase( 'SomeUser', '1.2.3.4' );
		$this->assertEquals( $throttled, $result, 'should throttle, same namespace' );

		$result = $throttler3->increase( 'SomeUser', '1.2.3.4' );
		$this->assertFalse( $result, 'should not throttle, different namespace' );
	}

	public function testExpiration() {
		$cache = $this->getMockBuilder( HashBagOStuff::class )
			->setMethods( [ 'add' ] )->getMock();
		$throttler = new Throttler( [ [ 'count' => 3, 'seconds' => 10 ] ], [ 'cache' => $cache ] );
		$throttler->setLogger( new NullLogger() );

		$cache->expects( $this->once() )->method( 'add' )->with( $this->anything(), 1, 10 );
		$throttler->increase( 'SomeUser' );
	}

	/**
	 */
	public function testException() {
		$throttler = new Throttler( [ [ 'count' => 3, 'seconds' => 10 ] ] );
		$throttler->setLogger( new NullLogger() );
		$this->expectException( \InvalidArgumentException::class );
		$throttler->increase();
	}

	public function testLog() {
		$cache = new \HashBagOStuff();
		$throttler = new Throttler( [ [ 'count' => 1, 'seconds' => 10 ] ], [ 'cache' => $cache ] );

		$logger = $this->getMockBuilder( AbstractLogger::class )
			->setMethods( [ 'log' ] )
			->getMockForAbstractClass();
		$logger->expects( $this->never() )->method( 'log' );
		$throttler->setLogger( $logger );
		$result = $throttler->increase( 'SomeUser', '1.2.3.4' );
		$this->assertFalse( $result, 'should not throttle' );

		$logger = $this->getMockBuilder( AbstractLogger::class )
			->setMethods( [ 'log' ] )
			->getMockForAbstractClass();
		$logger->expects( $this->once() )->method( 'log' )->with( $this->anything(), $this->anything(), [
			'throttle' => 'custom',
			'index' => 0,
			'ipKey' => '1.2.3.4',
			'username' => 'SomeUser',
			'count' => 1,
			'expiry' => 10,
			'method' => 'foo',
		] );
		$throttler->setLogger( $logger );
		$result = $throttler->increase( 'SomeUser', '1.2.3.4', 'foo' );
		$this->assertSame( [ 'throttleIndex' => 0, 'count' => 1, 'wait' => 10 ], $result );
	}

	public function testClear() {
		$cache = new \HashBagOStuff();
		$throttler = new Throttler( [ [ 'count' => 1, 'seconds' => 10 ] ], [ 'cache' => $cache ] );
		$throttler->setLogger( new NullLogger() );

		$result = $throttler->increase( 'SomeUser', '1.2.3.4' );
		$this->assertFalse( $result, 'should not throttle' );

		$result = $throttler->increase( 'SomeUser', '1.2.3.4' );
		$this->assertSame( [ 'throttleIndex' => 0, 'count' => 1, 'wait' => 10 ], $result );

		$result = $throttler->increase( 'OtherUser', '1.2.3.4' );
		$this->assertFalse( $result, 'should not throttle' );

		$result = $throttler->increase( 'OtherUser', '1.2.3.4' );
		$this->assertSame( [ 'throttleIndex' => 0, 'count' => 1, 'wait' => 10 ], $result );

		$throttler->clear( 'SomeUser', '1.2.3.4' );

		$result = $throttler->increase( 'SomeUser', '1.2.3.4' );
		$this->assertFalse( $result, 'should not throttle' );

		$result = $throttler->increase( 'OtherUser', '1.2.3.4' );
		$this->assertSame( [ 'throttleIndex' => 0, 'count' => 1, 'wait' => 10 ], $result );
	}
}
