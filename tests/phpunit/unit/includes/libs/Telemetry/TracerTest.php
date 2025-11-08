<?php
namespace Wikimedia\Tests\Telemetry;

use MediaWikiUnitTestCase;
use Wikimedia\Telemetry\Clock;
use Wikimedia\Telemetry\CompositePropagator;
use Wikimedia\Telemetry\ContextPropagatorInterface;
use Wikimedia\Telemetry\ExporterInterface;
use Wikimedia\Telemetry\NoopSpan;
use Wikimedia\Telemetry\SamplerInterface;
use Wikimedia\Telemetry\SpanContext;
use Wikimedia\Telemetry\SpanInterface;
use Wikimedia\Telemetry\StaticInjectionPropagator;
use Wikimedia\Telemetry\Tracer;
use Wikimedia\Telemetry\TracerState;
use Wikimedia\Telemetry\W3CTraceContextPropagator;

/**
 * @covers \Wikimedia\Telemetry\Tracer
 * @covers \Wikimedia\Telemetry\Span
 */
class TracerTest extends MediaWikiUnitTestCase {
	private Clock $clock;
	private SamplerInterface $sampler;
	private ExporterInterface $exporter;
	private TracerState $tracerState;
	private ContextPropagatorInterface $contextPropagator;
	private string $xReqId = 'abcd-12345';

	private Tracer $tracer;

	protected function setUp(): void {
		parent::setUp();

		$this->clock = $this->createMock( Clock::class );
		$this->sampler = $this->createMock( SamplerInterface::class );
		$this->exporter = $this->createMock( ExporterInterface::class );

		$this->tracerState = new TracerState();
		$this->contextPropagator = new CompositePropagator( [
			new StaticInjectionPropagator( [ 'X-Request-Id' => $this->xReqId ] ),
			new W3CTraceContextPropagator(),
		] );

		$this->clock->method( 'getCurrentNanoTime' )
			->willReturnCallback( static fn () => hrtime( true ) );

		$this->tracer = new Tracer(
			$this->clock,
			$this->sampler,
			$this->exporter,
			$this->tracerState,
			$this->contextPropagator
		);
	}

	public function testSpanAndTraceIds(): void {
		$this->sampler->method( 'shouldSample' )
			->willReturn( true );

		$rootSpan = $this->tracer->createRootSpan( 'test span' )
			->start();
		$rootSpan->activate();

		$span = $this->tracer->createSpan( 'test' )
			->start();

		$explicitSpan = $this->tracer->createSpanWithParent( 'test', $span->getContext() )
			->start();

		foreach ( [ $rootSpan, $span, $explicitSpan ] as $span ) {
			$this->assertMatchesRegularExpression(
				'/^[a-f0-9]{32}$/',
				$span->getContext()->getTraceId(),
				'The trace ID should be a 128-bit hexadecimal string'
			);
			$this->assertMatchesRegularExpression(
				'/^[a-f0-9]{16}$/',
				$span->getContext()->getSpanId(),
				'The span ID should be a 64-bit hexadecimal string'
			);
		}
	}

	public function testSpanCreation(): void {
		$this->sampler->method( 'shouldSample' )
			->willReturn( true );

		$activeSpan = $this->tracer->createRootSpan( 'test active span' )
			->start();
		$activeSpan->activate();

		$span = $this->tracer->createRootSpan( 'test span', false )
			->start();

		$this->assertNull(
			$span->getContext()->getParentSpanId(),
			'The test span should have been explicitly created as a root span, ignoring the active span'
		);
		$this->assertNotEquals(
			$activeSpan->getContext()->getTraceId(),
			$span->getContext()->getTraceId(),
			'The test span should have started a new trace because it is a root span'
		);
	}

	public function testMultipleSpanCreation(): void {
		$this->sampler->method( 'shouldSample' )
			->willReturn( true );

		/** @var SpanInterface[] $spans */
		$spans = [];
		$spanIds = [];
		for ( $i = 1; $i <= 4; $i++ ) {
			$span = $i === 1 ? $this->tracer->createRootSpan( "test span #$i" ) :
				$this->tracer->createSpan( "test span #$i" );
			$span = $span->start();
			$span->activate();

			if ( $i === 1 ) {
				$this->assertNull(
					$span->getContext()->getParentSpanId(),
					'The first span should have no parent, because there is no active span'
				);
			} else {
				$this->assertSame(
					$spans[$i - 2]->getContext()->getSpanId(),
					$span->getContext()->getParentSpanId(),
					"The parent of span #$i should have been the previous span, which was active"
				);
				$this->assertSame(
					$spans[0]->getContext()->getTraceId(),
					$span->getContext()->getTraceId(),
					'All spans should be part of the same trace'
				);
			}

			$spans[] = $span;
			$spanIds[] = $span->getContext()->getSpanId();
		}

		$span = null;

		while ( array_pop( $spans ) !== null );

		$exportedSpanIds = array_map(
			static fn ( SpanContext $spanContext ) => $spanContext->getSpanId(),
			$this->tracerState->getSpanContexts()
		);

		$this->assertSame(
			array_reverse( $spanIds ),
			$exportedSpanIds
		);
	}

	public function testCreatingSpansWithoutActiveSpan(): void {
		$this->clock->method( 'getCurrentNanoTime' )
			->willReturnCallback( static fn () => hrtime( true ) );

		$this->sampler->method( 'shouldSample' )
			->willReturn( true );

		$traceIds = [];

		for ( $i = 1; $i <= 2; $i++ ) {
			$span = $this->tracer->createRootSpan( "test span #$i" )
				->start();

			$this->assertNull( $span->getContext()->getParentSpanId(), "span #$i should have no parent" );
			$this->assertNotContains(
				$span->getContext()->getTraceId(),
				$traceIds,
				'All spans should be root spans, starting a new trace'
			);

			$traceIds[] = $span->getContext()->getTraceId();
		}
	}

	public function testCreatingSpanWithExplicitParent(): void {
		$this->sampler->method( 'shouldSample' )
			->willReturn( true );

		$activeSpan = $this->tracer->createRootSpan( 'test active span' )
			->start();

		$parentSpan = $this->tracer->createRootSpan( 'parent span' )
			->start();

		$span = $this->tracer->createSpanWithParent( 'test span', $parentSpan->getContext() )
			->start();

		$this->assertSame(
			$parentSpan->getContext()->getSpanId(),
			$span->getContext()->getParentSpanId(),
			'The test span should have been assigned the given span as the parent, ignoring the active span'
		);
		$this->assertSame(
			$parentSpan->getContext()->getTraceId(),
			$span->getContext()->getTraceId(),
			'The test span should have been part of the same trace as its parent'
		);
	}

	public function testShouldExportSharedStateOnShutdown(): void {
		$this->exporter->expects( $this->once() )
			->method( 'export' )
			->with( $this->tracerState );

		$this->tracer->shutdown();
	}

	public function testShouldMakeSpanCreationNoopPostShutdown(): void {
		$this->sampler->method( 'shouldSample' )
			->willReturn( true );

		$this->tracer->shutdown();

		$orphanedSpan = $this->tracer->createSpan( 'orphaned span' )
			->start();

		$rootSpan = $this->tracer->createRootSpan( 'parent span' )
			->start();

		$span = $this->tracer->createSpan( 'orphaned span' )
			->start();

		$explicitParentSpan = $this->tracer->createSpanWithParent( 'test span', $orphanedSpan->getContext() )
			->start();

		$this->assertInstanceOf( NoopSpan::class, $orphanedSpan );
		$this->assertInstanceOf( NoopSpan::class, $rootSpan );
		$this->assertInstanceOf( NoopSpan::class, $span );
		$this->assertInstanceOf( NoopSpan::class, $explicitParentSpan );
	}

	public function testGetRequestHeadersNoActiveSpan(): void {
		$reqHdrs = $this->tracer->getRequestHeaders();

		$this->assertArrayEquals( [ "X-Request-Id" => "abcd-12345" ], $reqHdrs );
		$this->assertArrayNotHasKey( 'traceparent', $reqHdrs );
	}

	public function testGetRequestHeadersActiveSpan(): void {
		$this->sampler->method( 'shouldSample' )
			->willReturn( true );

		$activeSpan = $this->tracer->createRootSpan( 'test active span' )
			->start();
		$activeSpan->activate();

		$reqHdrs = $this->tracer->getRequestHeaders();

		$this->assertArrayContains( [ "X-Request-Id" => "abcd-12345" ], $reqHdrs );
		$this->assertArrayHasKey( 'traceparent', $reqHdrs );
		$this->assertStringContainsString( $activeSpan->getContext()->getTraceId(), $reqHdrs['traceparent'] );
		$this->assertStringContainsString( $activeSpan->getContext()->getSpanId(), $reqHdrs['traceparent'] );
	}

	public function testCreateSpanFromCarrierWithoutContext(): void {
		$span = $this->tracer->createRootSpanFromCarrier( 'test span', [] );

		$this->assertNull( $span->getContext()->getParentSpanId() );
	}

	public function testCreateRootSpanFromValidCarrier(): void {
		$traceId = '0af7651916cd43dd8448eb211c80319c';
		$spanId = 'b7ad6b7169203331';
		$traceparent = "00-$traceId-$spanId-01";

		$this->sampler->method( 'shouldSample' )
			->willReturn( true );

		$span = $this->tracer->createRootSpanFromCarrier( 'test span', [ 'traceparent' => $traceparent ] )
			->start();

		$this->assertSame( $traceId, $span->getContext()->getTraceId() );
		$this->assertSame( $spanId, $span->getContext()->getParentSpanId() );
		$this->assertTrue( $span->getContext()->isSampled() );
	}
}
