<?php

/**
 * @covers ErrorPageError
 * @author Addshore
 */
class ErrorPageErrorTest extends MediaWikiTestCase {

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
		$params = [ 'Baz' ];
		$e = new ErrorPageError( $title, $mockMessage, $params );
		$this->assertEquals( $title, $e->title );
		$this->assertEquals( $mockMessage, $e->msg );
		$this->assertEquals( $params, $e->params );
	}

	public function testReport() {
		$mockMessage = $this->getMockMessage();
		$title = 'Foo';
		$params = [ 'Baz' ];

		$mock = $this->getMockBuilder( 'OutputPage' )
			->disableOriginalConstructor()
			->getMock();
		$mock->expects( $this->once() )
			->method( 'showErrorPage' )
			->with( $title, $mockMessage, $params );
		$mock->expects( $this->once() )
			->method( 'output' );
		$this->setMwGlobals( 'wgOut', $mock );

		$e = new ErrorPageError( $title, $mockMessage, $params );
		$e->report();
	}

}
