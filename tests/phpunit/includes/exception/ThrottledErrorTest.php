<?php

/**
 * @covers ThrottledError
 * @author Addshore
 */
class ThrottledErrorTest extends MediaWikiIntegrationTestCase {

	public function testExceptionSetsStatusCode() {
		$this->setMwGlobals( 'wgOut', $this->getMockWgOut() );
		try {
			throw new ThrottledError();
		} catch ( ThrottledError $e ) {
			ob_start();
			$e->report();
			$text = ob_get_clean();
			$this->assertStringContainsString( $e->getText(), $text );
		}
	}

	private function getMockWgOut() {
		$mock = $this->getMockBuilder( OutputPage::class )
			->disableOriginalConstructor()
			->getMock();
		$mock->expects( $this->once() )
			->method( 'setStatusCode' )
			->with( 429 );
		return $mock;
	}

}
