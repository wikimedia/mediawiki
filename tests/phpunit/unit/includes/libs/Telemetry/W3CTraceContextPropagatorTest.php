<?php
namespace Wikimedia\Tests\Telemetry;

use MediaWikiUnitTestCase;
use Wikimedia\Telemetry\SpanContext;
use Wikimedia\Telemetry\W3CTraceContextPropagator;

/**
 * @covers \Wikimedia\Telemetry\W3CTraceContextPropagator
 */
class W3CTraceContextPropagatorTest extends MediaWikiUnitTestCase {

	private W3CTraceContextPropagator $propagator;

	protected function setUp(): void {
		parent::setUp();
		$this->propagator = new W3CTraceContextPropagator();
	}

	/**
	 * @dataProvider provideValidData
	 */
	public function testValidData( string $traceparent, string $traceId, string $spanId, bool $sampled ): void {
		foreach ( [ 'traceparent', 'TraceParent', 'TRACEPARENT' ] as $headerName ) {
			$spanContext = $this->propagator->extract( [ $headerName => $traceparent ] );
			$this->assertNotNull( $spanContext );
			$this->assertInstanceOf( SpanContext::class, $spanContext );
			$this->assertSame( $traceId, $spanContext->getTraceId() );
			$this->assertSame( $spanId, $spanContext->getSpanId() );
			$this->assertNull( $spanContext->getParentSpanId() );
			$this->assertSame( $sampled, $spanContext->isSampled() );
		}
	}

	public static function provideValidData(): array {
		return [
			'valid sampled' => [
				'traceparent' => '00-0af7651916cd43dd8448eb211c80319c-b7ad6b7169203331-01',
				'traceId' => '0af7651916cd43dd8448eb211c80319c',
				'spanId' => 'b7ad6b7169203331',
				'sampled' => true,
			],
			'valid sampled2' => [
				'traceparent' => '00-6caa1d0619cbb62675b83393847da965-69a6e424371b128f-01',
				'traceId' => '6caa1d0619cbb62675b83393847da965',
				'spanId' => '69a6e424371b128f',
				'sampled' => true,
			],
			'valid unsampled' => [
				'traceparent' => '00-0af7651916cd43dd8448eb211c80319c-b7ad6b7169203331-00',
				'traceId' => '0af7651916cd43dd8448eb211c80319c',
				'spanId' => 'b7ad6b7169203331',
				'sampled' => false,
			],
		];
	}

	/**
	 * @dataProvider provideInvalidTraceparents
	 */
	public function testInvalidData( $traceparent ): void {
		$this->assertNull( $this->propagator->extract( [ 'traceparent' => $traceparent ] ) );
	}

	public static function provideInvalidTraceparents(): array {
		return [
			'null' => [ null ],
			'false' => [ false ],
			'empty string' => [ '' ],
			'invalid format' => [ '00-aaaa-bbbb-c' ],
			'missing sampling' => [ '00-0af7651916cd43dd8448eb211c80319c-b7ad6b7169203331' ],
			'unsupported version' => [ '02-0af7651916cd43dd8448eb211c80319c-b7ad6b7169203331-01' ],
		];
	}

	/**
	 * @dataProvider provideInjectData
	 */
	public function testShouldInjectContext( ?array $spanData, array $expected ): void {
		$spanContext = null;

		if ( $spanData !== null ) {
			[ $traceId, $spanId, $sampled ] = $spanData;

			$spanContext = new SpanContext(
				$traceId,
				$spanId,
				null,
				'',
				$sampled
			);
		}

		$carrier = $this->propagator->inject( $spanContext, [] );

		$this->assertSame( $expected, $carrier );
	}

	public static function provideInjectData(): iterable {
		yield 'no active span context' => [
			null,
			[]
		];

		yield 'unsampled span' => [
			[ '0af7651916cd43dd8448eb211c80319c', 'b7ad6b7169203331', false ],
			[ 'traceparent' => '00-0af7651916cd43dd8448eb211c80319c-b7ad6b7169203331-00' ]
		];

		yield 'sampled span' => [
			[ '0af7651916cd43dd8448eb211c80319c', 'b7ad6b7169203331', true ],
			[ 'traceparent' => '00-0af7651916cd43dd8448eb211c80319c-b7ad6b7169203331-01' ]
		];
	}
}
