<?php

use MediaWiki\Exception\BadTitleError;
use MediaWiki\Output\OutputPage;

/**
 * @covers \MediaWiki\Exception\BadTitleError
 * @author Addshore
 */
class BadTitleErrorTest extends MediaWikiIntegrationTestCase {

	public function testExceptionSetsStatusCode() {
		$mockOut = $this->createMock( OutputPage::class );
		$mockOut->expects( $this->once() )
			->method( 'setStatusCode' )
			->with( 404 );
		$this->setMwGlobals( 'wgOut', $mockOut );

		try {
			throw new BadTitleError();
		} catch ( BadTitleError $e ) {
			ob_start();
			$e->report();
			$text = ob_get_clean();
			$this->assertStringContainsString( $e->getMessage(), $text );
		}
	}

}
