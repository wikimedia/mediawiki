<?php
namespace Wikimedia\Tests\Telemetry;

use MediaWikiUnitTestCase;
use Wikimedia\Telemetry\NoopSpan;
use Wikimedia\Telemetry\NoopTracer;
use Wikimedia\Telemetry\SpanContext;
use Wikimedia\Telemetry\TracerInterface;

/**
 * @covers \Wikimedia\Telemetry\NoopTracer
 */
class NoopTracerTest extends MediaWikiUnitTestCase {
	private TracerInterface $tracer;

	protected function setUp(): void {
		parent::setUp();

		$this->tracer = new NoopTracer();
	}

	public function testShouldCreateNoopSpan(): void {
		$span = $this->tracer->createSpan( 'test' );

		$this->assertInstanceOf( NoopSpan::class, $span );
		$this->assertNull( $span->getContext()->getParentSpanId() );
	}

	public function testShouldCreateNoopRootSpan(): void {
		$span = $this->tracer->createRootSpan( 'test' );

		$this->assertInstanceOf( NoopSpan::class, $span );
		$this->assertNull( $span->getContext()->getParentSpanId() );
	}

	public function testShouldCreateNoopSpanWithParent(): void {
		$span = $this->tracer->createSpanWithParent( 'test', new SpanContext( '', '', null, '', false ) );

		$this->assertInstanceOf( NoopSpan::class, $span );
		$this->assertNull( $span->getContext()->getParentSpanId() );
	}
}
