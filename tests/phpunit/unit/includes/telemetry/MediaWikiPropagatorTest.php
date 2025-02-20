<?php
namespace MediaWiki\Tests\Unit\Telemetry;

use MediaWiki\Telemetry\MediaWikiPropagator;
use MediaWikiUnitTestCase;
use Wikimedia\Http\TelemetryHeadersInterface;
use Wikimedia\Telemetry\SpanContext;

/**
 * @covers \MediaWiki\Telemetry\MediaWikiPropagator
 */
class MediaWikiPropagatorTest extends MediaWikiUnitTestCase {
	private TelemetryHeadersInterface $telemetry;

	private MediaWikiPropagator $propagator;

	protected function setUp(): void {
		parent::setUp();

		$this->telemetry = $this->createMock( TelemetryHeadersInterface::class );
		$this->propagator = new MediaWikiPropagator( $this->telemetry );
	}

	public function testShouldInjectTelemetryHeadersIntoCarrier(): void {
		$spanContext = $this->createMock( SpanContext::class );
		$carrier = [];

		$this->telemetry->method( 'getRequestHeaders' )
			->willReturn( [ 'X-Request-Id' => 'bar' ] );

		$carrier = $this->propagator->inject( $spanContext, $carrier );

		$this->assertSame( [ 'X-Request-Id' => 'bar' ], $carrier );
	}

	public function testShouldExtractNoContext(): void {
		$this->telemetry->expects( $this->never() )
			->method( 'getRequestHeaders' );

		$spanContext = $this->propagator->extract( [ 'X-Request-Id' => 'bar' ] );

		$this->assertNull( $spanContext );
	}
}
