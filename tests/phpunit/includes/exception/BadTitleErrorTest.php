<?php
/**
 * @covers BadTitleError
 * @author Addshore
 */
class BadTitleErrorTest extends MediaWikiTestCase {

	public function testExceptionSetsStatusCode() {
		$this->setMwGlobals( 'wgOut', $this->getMockWgOut() );
		try {
			throw new BadTitleError();
		} catch ( BadTitleError $e ) {
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
			->with( 400 );
		return $mock;
	}

}
