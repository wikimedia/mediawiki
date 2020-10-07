<?php

/**
 * @license GPL-2.0-or-later
 * @author Addshore
 *
 * @covers SpecialBlankpage
 */
class SpecialBlankPageTest extends SpecialPageTestBase {

	protected function setUp() : void {
		parent::setUp();
		$this->setUserLang( 'qqx' );
	}

	/**
	 * Returns a new instance of the special page under test.
	 *
	 * @return SpecialPage
	 */
	protected function newSpecialPage() {
		return new SpecialBlankpage();
	}

	public function testHasWikiMsg() {
		list( $html, ) = $this->executeSpecialPage();
		$this->assertStringContainsString( '(intentionallyblankpage)', $html );
	}

}
