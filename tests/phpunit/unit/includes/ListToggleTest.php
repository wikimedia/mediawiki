<?php

/**
 * @covers ListToggle
 */
class ListToggleTest extends MediaWikiUnitTestCase {

	/**
	 * @covers ListToggle::__construct
	 */
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

	/**
	 * @covers ListToggle::getHTML
	 */
	public function testGetHTML() {
		$language = $this->createMock( Language::class );
		$language->method( 'commaList' )
			->willReturnCallback( static function ( array $list ) {
				return implode( '(comma-separator)', $list );
			} );

		$output = $this->createMock( OutputPage::class );
		$output->expects( $this->any() )
			->method( 'msg' )
			->will( $this->returnCallback( static function ( $key ) {
				return new class( $key ) extends Message {
					protected function fetchMessage() {
						return "($this->key$*)";
					}

					protected function transformText( $string ) {
						return $string;
					}
				};
			} ) );
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
