<?php

class ThrottleTest extends MediaWikiTestCase {
	private $throttle;

	function setUp() {
		parent::setUp();

		$this->throttle = new Throttle(
			array( 'unittest' ),
			2,
			3600,
			Throttle::GLOBAL_KEY,
			new HashBagOStuff
		);
		$this->throttle->clear();
	}

	public function testIncrement() {
		$this->assertFalse( $this->throttle->throttled(), 'Not throttled when cleared.' );
		$this->assertFalse( $this->throttle->increment(), 'Not throttled before the limit.' );
		$this->assertTrue( $this->throttle->increment(), 'Throttled after the limit.' );
	}

	public function testThrottleNow() {
		$this->assertFalse( $this->throttle->throttled(), 'Not throttled when cleared.' );
		$this->throttle->throttleNow();
		$this->assertTrue( $this->throttle->throttled(), 'Throttled after throttleNow().' );
	}
}
