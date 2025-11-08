<?php
namespace Wikimedia\Tests\Telemetry;

use MediaWikiUnitTestCase;
use Wikimedia\Telemetry\SpanContext;
use Wikimedia\Telemetry\StaticInjectionPropagator;

/**
 * @covers \Wikimedia\Telemetry\StaticInjectionPropagator
 */
class StaticInjectionPropagatorTest extends MediaWikiUnitTestCase {
	private StaticInjectionPropagator $propagator;

	protected function setUp(): void {
		parent::setUp();

		$this->propagator = new StaticInjectionPropagator( [
			'foo' => 'bar',
			'baz' => 'quux'
		] );
	}

	public function testShouldExtractNothing(): void {
		$extracted = $this->propagator->extract( [] );

		$this->assertNull( $extracted );
	}

	public function testShouldInjectConfiguredHeaders(): void {
		$carrier = [];
		$context = $this->createMock( SpanContext::class );

		$injected = $this->propagator->inject( $context, $carrier );

		$this->assertSame( [ 'foo' => 'bar', 'baz' => 'quux' ], $injected );
	}
}
