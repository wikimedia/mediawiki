<?php

/**
 * @covers ThrottledError
 * @author Addshore
 */
class ThrottledErrorTest extends MediaWikiTestCase {

	public function testExceptionSetsStatusCode() {
		$this->setMwGlobals( 'wgOut', $this->getMockWgOut() );
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
