<?php

/**
 * @covers ThrottledError
 * @author Adam Shorland
 */
class ThrottledErrorTest extends MediaWikiTestCase {

	protected $wgOut;

	protected function setUp() {
		parent::setUp();
		global $wgOut;
		$this->wgOut = clone $wgOut;
	}

	protected function tearDown() {
		parent::tearDown();
		global $wgOut;
		$wgOut = $this->wgOut;
	}

	public function testExceptionSetsStatusCode() {
		global $wgOut;
		$wgOut = $this->getMockWgOut();
		try {
			throw new ThrottledError();
		} catch ( ThrottledError $e ) {
			$e->report();
			$this->assertTrue( true );
		}
	}

	private function getMockWgOut() {
		$mock = $this->getMockBuilder( 'OutputPage' )
			->disableOriginalConstructor()
			->getMock();
		$mock->expects( $this->once() )
			->method( 'setStatusCode' )
			->with( 429 );
		return $mock;
	}

}
