<?php

use MediaWiki\MediaWikiServices;

/**
 * @covers ListToggle
 */
class ListToggleTest extends MediaWikiIntegrationTestCase {

	/**
	 * @covers ListToggle::__construct
	 */
	public function testConstruct() {
		$output = $this->getMockBuilder( OutputPage::class )
			->setMethods( null )
			->disableOriginalConstructor()
			->getMock();

		$listToggle = new ListToggle( $output );

		$this->assertInstanceOf( ListToggle::class, $listToggle );
		$this->assertContains( 'mediawiki.checkboxtoggle', $output->getModules() );
		$this->assertContains( 'mediawiki.checkboxtoggle.styles', $output->getModuleStyles() );
	}

	/**
	 * @covers ListToggle::getHTML
	 */
	public function testGetHTML() {
		$output = $this->createMock( OutputPage::class );
		$output->expects( $this->any() )
			->method( 'msg' )
			->will( $this->returnCallback( function ( $key ) {
				return wfMessage( $key )->inLanguage( 'qqx' );
			} ) );
		$output->expects( $this->once() )
			->method( 'getLanguage' )
			->will( $this->returnValue(
				MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'qqx' ) ) );

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
