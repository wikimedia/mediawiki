<?php
namespace Wikimedia\Tests\Telemetry;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use MediaWiki\MainConfigNames;
use MediaWikiIntegrationTestCase;
use Wikimedia\Telemetry\Clock;
use Wikimedia\Telemetry\NoopTracer;
use Wikimedia\Telemetry\SpanInterface;
use Wikimedia\Telemetry\Tracer;
use Wikimedia\Telemetry\TracerState;

/**
 * @covers \Wikimedia\Telemetry\Tracer
 * @covers \Wikimedia\Telemetry\OtlpHttpExporter
 */
class TelemetryIntegrationTest extends MediaWikiIntegrationTestCase {
	private const EXAMPLE_TRACING_CONFIG = [
		'serviceName' => 'test-service',
		'samplingProbability' => 100,
		'endpoint' => 'http://198.51.100.42:4318/v1/traces'
	];

	private const EXAMPLE_TRACING_CONFIG_NO_SAMPLING = [
		'serviceName' => 'test-service',
		'samplingProbability' => 0,
		'endpoint' => 'http://198.51.100.42:4318/v1/traces'
	];

	private MockHandler $handler;

	protected function setUp(): void {
		parent::setUp();

		$this->handler = new MockHandler();
		$this->setService( '_TracerHTTPClient', new Client( [
			'handler' => $this->handler,
			'http_errors' => false
		] ) );
	}

	protected function tearDown(): void {
		parent::tearDown();
		Clock::setMockTime( null );
		TracerState::destroyInstance();
	}

	public function testShouldDoNothingWhenTracingDisabled(): void {
		$this->overrideConfigValue( MainConfigNames::OpenTelemetryConfig, null );

		$tracer = $this->getServiceContainer()->getTracer();
		$span = $tracer->createSpan( 'test' )
			->start();

		$span->end();

		$tracer->shutdown();

		$this->assertInstanceOf( NoopTracer::class, $tracer );
		$this->assertNull( $this->handler->getLastRequest() );
	}

	public function testShouldDoNothingWhenNotSampled(): void {
		$this->overrideConfigValue(
			MainConfigNames::OpenTelemetryConfig,
			self::EXAMPLE_TRACING_CONFIG_NO_SAMPLING
		);

		$tracer = $this->getServiceContainer()->getTracer();
		$span = $tracer->createRootSpan( 'test' )
			->start();
		$span->activate();

		$child = $tracer->createSpan( 'child' )
			->start();

		$child->end();

		$span->end();

		$tracer->shutdown();

		$this->assertInstanceOf( Tracer::class, $tracer );
		$this->assertNull( $this->handler->getLastRequest() );
	}

	public function testShouldNotExportDataWhenNoSpansWereCreated(): void {
		$this->overrideConfigValue( MainConfigNames::OpenTelemetryConfig, self::EXAMPLE_TRACING_CONFIG );

		$tracer = $this->getServiceContainer()->getTracer();

		$tracer->shutdown();

		$this->assertInstanceOf( Tracer::class, $tracer );
		$this->assertNull( $this->handler->getLastRequest() );
	}

	public function testShouldNotExportDataWhenTracerWasNotExplicitlyShutdown(): void {
		$this->overrideConfigValue( MainConfigNames::OpenTelemetryConfig, self::EXAMPLE_TRACING_CONFIG );

		$tracer = $this->getServiceContainer()->getTracer();
		$span = $tracer->createRootSpan( 'test' )
			->start();

		$span->end();

		$this->assertInstanceOf( Tracer::class, $tracer );
		$this->assertNull( $this->handler->getLastRequest() );
	}

	public function testShouldExportDataOnShutdownWhenTracingEnabled(): void {
		$this->overrideConfigValue( MainConfigNames::OpenTelemetryConfig, self::EXAMPLE_TRACING_CONFIG );
		$this->handler->append( new Response( 200 ) );

		$mockTime = 5481675965496;
		Clock::setMockTime( $mockTime );

		$tracer = $this->getServiceContainer()->getTracer();
		$span = $tracer->createRootSpan( 'test' )
			->setSpanKind( SpanInterface::SPAN_KIND_SERVER )
			->start();

		$span->activate();

		$mockTime += 100;
		Clock::setMockTime( $mockTime );

		$childSpan = $tracer->createSpan( 'child' )
			->setAttributes( [ 'some-key' => 'test', 'ignored' => new \stdClass() ] )
			->start();

		$mockTime += 250;
		Clock::setMockTime( $mockTime );

		$childSpan->end();

		$mockTime += 74;
		Clock::setMockTime( $mockTime );

		$span->setSpanStatus( SpanInterface::SPAN_STATUS_ERROR );
		$span->end();

		$this->assertNull(
			$this->handler->getLastRequest(),
			'Exporting trace data should be deferred until the tracer is explicitly shut down'
		);

		$tracer->shutdown();

		$request = $this->handler->getLastRequest();

		$this->assertInstanceOf( Tracer::class, $tracer );
		$this->assertSame( 'http://198.51.100.42:4318/v1/traces', (string)$request->getUri() );
		$this->assertSame( 'application/json', $request->getHeaderLine( 'Content-Type' ) );

		$expected = file_get_contents( __DIR__ . '/expected-trace-data.json' );

		$expected = strtr( $expected, [
			'<TRACE-ID>' => $span->getContext()->getTraceId(),
			'<SPAN-1-ID>' => $span->getContext()->getSpanId(),
			'<SPAN-2-ID>' => $childSpan->getContext()->getSpanId(),
			'<HOST-NAME>' => wfHostname()
		] );

		$this->assertJsonStringEqualsJsonString(
			$expected,
			(string)$request->getBody()
		);
	}
}
