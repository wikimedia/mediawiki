<?php

use MediaWiki\Html\ListToggle;
use MediaWiki\Language\Language;
use MediaWiki\Output\OutputPage;
use MediaWiki\Tests\Unit\FakeQqxMessageLocalizer;

/**
 * @covers \MediaWiki\Html\ListToggle
 */
class ListToggleTest extends MediaWikiUnitTestCase {

	public function testConstruct() {
		$output = $this->createMock( OutputPage::class );
		$output->expects( $this->once() )
			->method( 'addModules' )
			->with( 'mediawiki.checkboxtoggle' );
		$output->expects( $this->once() )
			->method( 'addModuleStyles' )
			->with( 'mediawiki.checkboxtoggle.styles' );

		new ListToggle( $output );
	}

	public function testGetHTML() {
		$language = $this->createMock( Language::class );
		$language->method( 'commaList' )
			->willReturnCallback( static function ( array $list ) {
				return implode( '(comma-separator)', $list );
			} );

		$output = $this->createMock( OutputPage::class );
		$output->method( 'msg' )
			->willReturnCallback( [ new FakeQqxMessageLocalizer(), 'msg' ] );
		$output->expects( $this->once() )
			->method( 'getLanguage' )
			->willReturn( $language );

		$listToggle = new ListToggle( $output );

		$html = $listToggle->getHTML();
		$this->assertEquals( '<div class="mw-checkbox-toggle-controls">' .
			'(checkbox-select: <a class="mw-checkbox-all" role="button"' .
			' tabindex="0">(checkbox-all)</a>(comma-separator)' .
			'<a class="mw-checkbox-none" role="button" tabindex="0">' .
			'(checkbox-none)</a>(comma-separator)<a class="mw-checkbox-invert" ' .
			'role="button" tabindex="0">(checkbox-invert)</a>)</div>',
			$html );
	}
}
