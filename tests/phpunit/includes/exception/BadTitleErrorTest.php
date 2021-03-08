<?php
/**
 * @covers BadTitleError
 * @author Addshore
 */
class BadTitleErrorTest extends MediaWikiIntegrationTestCase {

	public function testExceptionSetsStatusCode() {
		$mockOut = $this->getMockBuilder( OutputPage::class )
			->disableOriginalConstructor()
			->getMock();
		$mockOut->expects( $this->once() )
			->method( 'setStatusCode' )
			->with( 400 );
		$this->setMwGlobals( 'wgOut', $mockOut );

		try {
			throw new BadTitleError();
		} catch ( BadTitleError $e ) {
			ob_start();
			$e->report();
			$text = ob_get_clean();
			$this->assertStringContainsString( $e->getText(), $text );
		}
	}

}
