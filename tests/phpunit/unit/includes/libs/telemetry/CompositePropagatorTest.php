<?php
namespace Wikimedia\Tests\Telemetry;

use MediaWikiUnitTestCase;
use Wikimedia\Assert\ParameterAssertionException;
use Wikimedia\Telemetry\CompositePropagator;
use Wikimedia\Telemetry\ContextPropagatorInterface;
use Wikimedia\Telemetry\SpanContext;

/**
 * @covers \Wikimedia\Telemetry\CompositePropagator
 */
class CompositePropagatorTest extends MediaWikiUnitTestCase {
	public function testShouldFailWhenNoPropagatorsGiven(): void {
		$this->expectException( ParameterAssertionException::class );
		$this->expectExceptionMessage( '$propagators: must not be empty' );

		new CompositePropagator( [] );
	}

	public function testShouldExtractFirstNotNullContext(): void {
		$carrier = [ 'foo' => 'bar' ];
		$context = $this->createMock( SpanContext::class );

		$propagator1 = $this->createMock( ContextPropagatorInterface::class );
		$propagator1->method( 'extract' )
			->with( $carrier )
			->willReturn( null );

		$propagator2 = $this->createMock( ContextPropagatorInterface::class );
		$propagator2->method( 'extract' )
			->with( $carrier )
			->willReturn( $context );

		$propagator3 = $this->createMock( ContextPropagatorInterface::class );
		$propagator3->expects( $this->never() )
			->method( 'extract' );

		$compositePropagator = new CompositePropagator( [
			$propagator1,
			$propagator2,
			$propagator3
		] );

		$extracted = $compositePropagator->extract( $carrier );

		$this->assertInstanceOf( SpanContext::class, $extracted );
	}

	public function testShouldReturnNullWhenNoContextWasExtracted(): void {
		$carrier = [ 'foo' => 'bar' ];

		$propagators = [];

		for ( $i = 1; $i <= 3; $i++ ) {
			$propagator = $this->createMock( ContextPropagatorInterface::class );
			$propagator->method( 'extract' )
				->with( $carrier )
				->willReturn( null );
			$propagators[] = $propagator;
		}

		$compositePropagator = new CompositePropagator( $propagators );

		$extracted = $compositePropagator->extract( $carrier );

		$this->assertNull( $extracted );
	}

	public function testShouldAllowAllPropagatorsToInject(): void {
		$carrier = [];

		$context = $this->createMock( SpanContext::class );

		$propagator1 = $this->createMock( ContextPropagatorInterface::class );
		$propagator1->method( 'inject' )
			->with( $context, $carrier )
			->willReturn( [ 'foo' => 'baz' ] );

		$propagator2 = $this->createMock( ContextPropagatorInterface::class );
		$propagator2->method( 'inject' )
			->with( $context, [ 'foo' => 'baz' ] )
			->willReturn( [ 'foo' => 'baz', 'bar' => 'qux' ] );

		$propagator3 = $this->createMock( ContextPropagatorInterface::class );
		$propagator3->method( 'inject' )
			->with( $context, [ 'foo' => 'baz', 'bar' => 'qux' ] )
			->willReturn( [ 'foo' => 'quux', 'bar' => 'qux' ] );

		$compositePropagator = new CompositePropagator( [
			$propagator1,
			$propagator2,
			$propagator3
		] );

		$injected = $compositePropagator->inject( $context, $carrier );

		$this->assertSame( [ 'foo' => 'quux', 'bar' => 'qux' ], $injected );
	}
}
