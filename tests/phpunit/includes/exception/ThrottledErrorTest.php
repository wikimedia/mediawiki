<?php

use MediaWiki\Output\OutputPage;

/**
 * @covers ThrottledError
 * @author Addshore
 */
class ThrottledErrorTest extends MediaWikiIntegrationTestCase {

	public function testExceptionSetsStatusCode() {
		$mockOut = $this->createMock( OutputPage::class );
		$mockOut->expects( $this->once() )
			->method( 'setStatusCode' )
			->with( 429 );
		$this->setMwGlobals( 'wgOut', $mockOut );

		try {
			throw new ThrottledError();
		} catch ( ThrottledError $e ) {
			ob_start();
			$e->report();
			$text = ob_get_clean();
			$this->assertStringContainsString( $e->getText(), $text );
		}
	}

}
