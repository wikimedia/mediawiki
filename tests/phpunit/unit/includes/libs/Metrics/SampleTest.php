<?php

namespace Wikimedia\Tests\Metrics;

use PHPUnit\Framework\TestCase;
use Wikimedia\Metrics\Sample;

/**
 * @covers \Wikimedia\Metrics\Sample
 */
class SampleTest extends TestCase {
	public function testSample() {
		$s = new Sample( [ 'labels' => [ 'a', 'b' ], 'value' => 1 ] );
		$this->assertEquals( [ 'a', 'b' ], $s->getLabels() );
		$this->assertSame( 1.0, $s->getValue() );
	}
}
