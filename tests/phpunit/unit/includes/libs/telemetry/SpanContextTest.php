<?php
namespace Wikimedia\Tests\Telemetry;

use MediaWikiUnitTestCase;
use Wikimedia\Telemetry\SpanContext;

/**
 * @covers \Wikimedia\Telemetry\SpanContext
 */
class SpanContextTest extends MediaWikiUnitTestCase {

	/**
	 * @dataProvider provideValidData
	 */
	public function testNewFromTraceparentValidData( string $traceparent, string $traceId, string $spanId, bool $sampled ): void {
		$spanContext = SpanContext::newFromTraceparentHeader( $traceparent );
		$this->assertNotNull( $spanContext );
		$this->assertInstanceOf( SpanContext::class, $spanContext );
		$this->assertSame( $traceId, $spanContext->getTraceId() );
		$this->assertSame( $spanId, $spanContext->getSpanId() );
		$this->assertNull( $spanContext->getParentSpanId() );
		$this->assertSame( $sampled, $spanContext->isSampled() );
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
	 * @param string|null|false $traceparent
	 */
	public function testNewFromTraceparentInvalidData( $traceparent ): void {
		$this->assertNull( SpanContext::newFromTraceparentHeader( $traceparent ) );
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
}
