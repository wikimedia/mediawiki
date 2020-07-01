<?php

/**
 * @covers ErrorPageError
 * @author Addshore
 */
class ErrorPageErrorTest extends MediaWikiIntegrationTestCase {

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

		$mock = $this->getMockBuilder( OutputPage::class )
			->disableOriginalConstructor()
			->getMock();
		$mock->expects( $this->once() )
			->method( 'showErrorPage' )
			->with( $title, $mockMessage, $params );
		$mock->expects( $this->once() )
			->method( 'output' );
		$this->setMwGlobals( 'wgOut', $mock );
		$this->setMwGlobals( 'wgCommandLineMode', false );

		$e = new ErrorPageError( $title, $mockMessage, $params );
		$e->report();
	}

}
