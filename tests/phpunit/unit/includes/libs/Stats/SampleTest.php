<?php

namespace Wikimedia\Tests\Stats;

use PHPUnit\Framework\TestCase;
use Wikimedia\Stats\Sample;

/**
 * @covers \Wikimedia\Stats\Sample
 */
class SampleTest extends TestCase {
	public function testSample() {
		$s = new Sample( [ 'a', 'b' ], 1 );
		$this->assertEquals( [ 'a', 'b' ], $s->getLabelValues() );
		$this->assertSame( 1.0, $s->getValue() );
	}
}
