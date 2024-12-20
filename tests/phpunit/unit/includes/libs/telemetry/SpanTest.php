<?php
namespace Wikimedia\Tests\Telemetry;

use MediaWikiUnitTestCase;
use Wikimedia\Assert\PreconditionException;
use Wikimedia\Telemetry\Clock;
use Wikimedia\Telemetry\Span;
use Wikimedia\Telemetry\SpanContext;
use Wikimedia\Telemetry\TracerState;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \Wikimedia\Telemetry\Span
 */
class SpanTest extends MediaWikiUnitTestCase {
	private Clock $clock;
	private TracerState $tracerState;

	protected function setUp(): void {
		parent::setUp();

		$this->clock = $this->createMock( Clock::class );
		$this->tracerState = $this->createMock( TracerState::class );
	}

	private function createSpan(): Span {
		$context = new SpanContext(
			str_repeat( 'a', 64 ),
			str_repeat( 'b', 16 ),
			null,
			'test',
			true
		);
		return new Span(
			$this->clock,
			$this->tracerState,
			$context
		);
	}

	public function testDuplicateSpanStartShouldError(): void {
		$this->expectException( PreconditionException::class );
		$this->expectExceptionMessage( 'Cannot start a span more than once' );

		$span = $this->createSpan();

		$span->start();
		$span->start();
	}

	public function testEndingUnstartedSpanShouldError(): void {
		$this->expectException( PreconditionException::class );
		$this->expectExceptionMessage( 'Cannot end a span that has not been started' );

		$span = $this->createSpan();

		$span->end();
	}

	public function testShouldExposeDataViaContext(): void {
		$traceId = str_repeat( 'c', 64 );
		$spanId = str_repeat( 'd', 16 );
		$context = new SpanContext(
			$traceId,
			$spanId,
			null,
			'test',
			true
		);

		$span = new Span(
			$this->clock,
			$this->tracerState,
			$context
		);
		$span->start();

		$this->assertSame( $traceId, $span->getContext()->getTraceId() );
		$this->assertSame( $spanId, $span->getContext()->getSpanId() );
		$this->assertTrue( $span->getContext()->isSampled() );
	}

	/**
	 * @dataProvider provideInactiveSpanTestCases
	 */
	public function testShouldNotDeactivateSpanInSharedStateByGoingOutOfScopeIfItWasNeverActive(
		?SpanContext $activeSpanContext
	): void {
		$span = $this->createSpan();

		$this->tracerState->expects( $this->never() )
			->method( 'activateSpan' );

		$this->tracerState->method( 'getActiveSpanContext' )
			->willReturn( $activeSpanContext );

		$this->tracerState->expects( $this->never() )
			->method( 'deactivateSpan' );

		$span->start();

		$span = null;
	}

	public static function provideInactiveSpanTestCases(): iterable {
		yield 'no active span' => [ null ];
		yield 'different active span' => [ new SpanContext( '', 'bbb', null, '', false ) ];
	}

	public function testShouldActivateAndDeactivateSpanInSharedStateByGoingOutOfScope(): void {
		$span = $this->createSpan();

		$this->tracerState->expects( $this->once() )
			->method( 'activateSpan' )
			->with( $span->getContext() );

		$this->tracerState->method( 'getActiveSpanContext' )
			->willReturn( $span->getContext() );

		$this->tracerState->expects( $this->once() )
			->method( 'deactivateSpan' )
			->with( $span->getContext() );

		$span->start();
		$span->activate();

		$span = null;
	}

	public function testShouldDeactivateSpanInSharedStateExplicitly(): void {
		$span = $this->createSpan();

		$this->tracerState->expects( $this->once() )
			->method( 'deactivateSpan' )
			->with( $span->getContext() );

		$span->start();
		$span->deactivate();
	}

	public function testShouldAddSpanToSharedStateOnceWhenEndedExplicitly(): void {
		$span = $this->createSpan();

		$this->tracerState->expects( $this->once() )
			->method( 'addSpanContext' )
			->with( $span->getContext() );

		$span->start();

		$span->end();
		$span->end();
	}

	public function testShouldAddSpanToSharedStateOnceWhenEndedByGoingOutOfScope(): void {
		$span = $this->createSpan();

		$this->tracerState->expects( $this->once() )
			->method( 'addSpanContext' )
			->with( $span->getContext() );

		$span->start();

		$span = null;
	}

	public function testShouldMergeAttributes(): void {
		$span = $this->createSpan();
		$context = TestingAccessWrapper::newFromObject( $span->getContext() );

		$span->start();
		$span->setAttributes( [ 'a' => 1, 'b' => 2 ] );
		$span->setAttributes( [ 'b' => 3, 'c' => 4 ] );

		$this->assertSame( [ 'a' => 1, 'b' => 3, 'c' => 4 ], $context->attributes );
	}
}
