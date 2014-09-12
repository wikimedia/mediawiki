<?php

/**
 * @covers ErrorPageError
 * @author Adam Shorland
 */
class ErrorPageErrorTest extends MediaWikiTestCase {

	private $wgOut;

	protected function setUp() {
		parent::setUp();
		global $wgOut;
		$this->wgOut = clone $wgOut;
	}

	protected function tearDown() {
		global $wgOut;
		$wgOut = $this->wgOut;
		parent::tearDown();
	}

	private function getMockMessage() {
		$mockMessage = $this->getMockBuilder( 'Message' )
			->disableOriginalConstructor()
			->getMock();
		$mockMessage->expects( $this->once() )
			->method( 'inLanguage' )
			->will( $this->returnValue( $mockMessage ) );
		$mockMessage->expects( $this->once() )
			->method( 'useDatabase' )
			->will( $this->returnValue( $mockMessage ) );
		return $mockMessage;
	}

	public function testConstruction() {
		$mockMessage = $this->getMockMessage();
		$title = 'Foo';
		$params = array( 'Baz' );
		$e = new ErrorPageError( $title, $mockMessage, $params );
		$this->assertEquals( $title, $e->title );
		$this->assertEquals( $mockMessage, $e->msg );
		$this->assertEquals( $params, $e->params );
	}

	public function testReport() {
		$mockMessage = $this->getMockMessage();
		$title = 'Foo';
		$params = array( 'Baz' );

		global $wgOut;
		$wgOut = $this->getMockBuilder( 'OutputPage' )
			->disableOriginalConstructor()
			->getMock();
		$wgOut->expects( $this->once() )
			->method( 'showErrorPage' )
			->with( $title, $mockMessage, $params );
		$wgOut->expects( $this->once() )
			->method( 'output' );

		$e = new ErrorPageError( $title, $mockMessage, $params );
		$e->report();
	}



}
