<?php

/**
 * @covers Throttle
 */
class ThrottleTest extends MediaWikiTestCase {
	private $throttle;

	protected function setUp() {
		parent::setUp();

		$this->throttle = new Throttle(
			array( 'unittest' ),
			2,
			3600,
			Throttle::GLOBAL_KEY,
			new HashBagOStuff
		);
	}

	/**
	 * @covers Throttle::increment
	 */
	public function testIncrement() {
		$this->assertFalse( $this->throttle->throttled(), 'Not throttled when cleared.' );
		$this->assertFalse( $this->throttle->increment(), 'Not throttled before the limit.' );
		$this->assertTrue( $this->throttle->increment(), 'Throttled after the limit.' );
	}

	/**
	 * @covers Throttle::throttleNow
	 */
	public function testThrottleNow() {
		$this->assertFalse( $this->throttle->throttled(), 'Not throttled when cleared.' );
		$this->throttle->throttleNow();
		$this->assertTrue( $this->throttle->throttled(), 'Throttled after throttleNow().' );
	}

	/**
	 * @depends testThrottleNow
	 * @covers Throttle::throttled
	 */
	public function testThrottled() {
		$local = clone $this->throttle;
		$local->throttleNow();

		$this->assertTrue( $local->throttled() );
		$this->assertTrue( $this->throttle->throttled() );
	}

	/**
	 * @covers Throttle::clear
	 */
	public function testClear() {
		$local = clone $this->throttle;
		$local->throttleNow();
		$local->clear();

		$this->assertFalse( $local->throttled() );
		$this->assertFalse( $this->throttle->throttled() );
	}
}
