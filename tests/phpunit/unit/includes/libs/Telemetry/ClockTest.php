<?php
namespace Wikimedia\Tests\Telemetry;

use MediaWikiUnitTestCase;
use Wikimedia\Telemetry\Clock;

/**
 * @covers \Wikimedia\Telemetry\Clock
 */
class ClockTest extends MediaWikiUnitTestCase {
	private Clock $clock;

	protected function setUp(): void {
		parent::setUp();

		$this->clock = new Clock();
	}

	public function testShouldReturnCurrentTime(): void {
		$this->assertClockReturnsCurrentTime();
	}

	public function testShouldAllowMockingTimestamp(): void {
		Clock::setMockTime( 2_000 );

		$this->assertSame( 2_000, $this->clock->getCurrentNanoTime() );

		Clock::setMockTime( null );

		$this->assertClockReturnsCurrentTime();
	}

	/**
	 * Utility function to assert that the Clock being tested returns the current time, with some leeway.
	 * @return void
	 */
	private function assertClockReturnsCurrentTime() {
		$this->markTestSkipped( 'Flaky (T379562) - to be replaced by ConvertibleTimestamp::microtime()' );

		$referenceTime = (int)( 1e9 * microtime( true ) ) - hrtime( true );
		$currentTime = $referenceTime + hrtime( true );

		usleep( 1 );

		$now = $this->clock->getCurrentNanoTime();

		usleep( 1 );

		$afterTime = $referenceTime + hrtime( true );

		$this->assertGreaterThan(
			$currentTime,
			$now,
			'Too large time difference'
		);
		$this->assertLessThanOrEqual(
			$afterTime,
			$now,
			'Too large time difference'
		);
	}
}
