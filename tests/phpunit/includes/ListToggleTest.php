<?php

/**
 * @covers ListToggle
 */
class ListToggleTest extends MediaWikiTestCase {

	/**
	 * @covers ListToggle::__construct
	 */
	public function testConstruct() {
		$specialpage = new SpecialPage( 'TestPage' );
		$output = $specialpage->getOutput();
		$listToggle = new ListToggle( $output );

		$this->assertInstanceOf('ListToggle', $listToggle);
	}

	/**
	 * @covers ListToggle::getHTML
	 */
	public function testGetHTML() {
		$specialpage = new SpecialPage( 'TestPage' );
		$output = $specialpage->getOutput();
		$listToggle = new ListToggle( $output );

		$html = $listToggle->getHTML();
		$this->assertRegExp('/<div class="mw-checkbox-toggle-controls">/',
			$html);
	}
}