<?php
namespace Wikimedia\Tests\Telemetry;

use MediaWikiUnitTestCase;
use Wikimedia\Assert\InvariantException;
use Wikimedia\Assert\PreconditionException;
use Wikimedia\Telemetry\SpanContext;
use Wikimedia\Telemetry\SpanInterface;
use Wikimedia\Telemetry\TracerState;

/**
 * @covers \Wikimedia\Telemetry\TracerState
 */
class TracerStateTest extends MediaWikiUnitTestCase {
	private TracerState $tracerState;

	protected function setUp(): void {
		parent::setUp();

		$this->tracerState = new TracerState();
	}

	public function testShouldMaintainStackOfActiveSpans(): void {
		$firstSpanContext = new SpanContext(
			'',
			str_repeat( 'a', 16 ),
			null,
			'',
			true
		);

		$secondSpanContext = new SpanContext(
			'',
			str_repeat( 'b', 16 ),
			null,
			'',
			true
		);

		$this->assertNull( $this->tracerState->getActiveSpanContext() );

		$this->tracerState->activateSpan( $firstSpanContext );
		$this->assertSame( $firstSpanContext, $this->tracerState->getActiveSpanContext() );

		$this->tracerState->activateSpan( $secondSpanContext );
		$this->assertSame( $secondSpanContext, $this->tracerState->getActiveSpanContext() );

		$this->tracerState->deactivateSpan( $secondSpanContext );
		$this->assertSame( $firstSpanContext, $this->tracerState->getActiveSpanContext() );

		$this->tracerState->deactivateSpan( $firstSpanContext );
		$this->assertNull( $this->tracerState->getActiveSpanContext() );
	}

	public function testShouldRejectDeactivatingSpanWhenNoSpanIsActive(): void {
		$this->expectException( InvariantException::class );
		$this->expectExceptionMessage( 'Attempted to deactivate a span which is not the active span.' );

		try {
			$span = $this->createMock( SpanInterface::class );
			$this->tracerState->deactivateSpan( new SpanContext( '', '', null, '', false ) );
		} finally {
			$this->assertNull( $this->tracerState->getActiveSpanContext() );
		}
	}

	public function testShouldRejectDeactivatingSpanWhenItIsNotActive(): void {
		$this->expectException( InvariantException::class );
		$this->expectExceptionMessage( 'Attempted to deactivate a span which is not the active span.' );

		$activeSpanContext = new SpanContext(
			'',
			str_repeat( 'a', 16 ),
			null,
			'',
			true
		);

		$this->tracerState->activateSpan( $activeSpanContext );

		try {
			$otherSpanContext = new SpanContext(
				'',
				str_repeat( 'b', 16 ),
				null,
				'',
				true
			);

			$this->tracerState->deactivateSpan( $otherSpanContext );
		} finally {
			$this->assertSame( $activeSpanContext, $this->tracerState->getActiveSpanContext() );
		}
	}

	public function testShouldAddAndReturnSpans(): void {
		$firstSpanContext = new SpanContext(
			'',
			str_repeat( 'a', 16 ),
			null,
			'',
			true
		);

		$secondSpanContext = new SpanContext(
			'',
			str_repeat( 'b', 16 ),
			null,
			'',
			true
		);

		$this->tracerState->addSpanContext( $firstSpanContext );
		$this->tracerState->addSpanContext( $secondSpanContext );

		$this->assertSame( [ $firstSpanContext, $secondSpanContext ], $this->tracerState->getSpanContexts() );

		$this->tracerState->clearSpanContexts();

		$this->assertSame( [], $this->tracerState->getSpanContexts() );
	}

	public function testSetAndEndRootSpan(): void {
		$rootSpan = $this->createMock( SpanInterface::class );
		$rootSpan->expects( $this->once() )
			->method( 'end' );
		$rootSpan->expects( $this->once() )
			->method( 'setSpanStatus' )
			->with( SpanInterface::SPAN_STATUS_ERROR );

		$this->tracerState->setRootSpan( $rootSpan );
		$this->tracerState->endRootSpan( SpanInterface::SPAN_STATUS_ERROR );
	}

	public function testCannotSetRootSpanAgain(): void {
		$this->expectException( PreconditionException::class );

		$rootSpan = $this->createMock( SpanInterface::class );

		$this->tracerState->setRootSpan( $rootSpan );
		$this->tracerState->setRootSpan( $rootSpan );
	}
}
