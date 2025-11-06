<?php
namespace MediaWiki\Tests\Specials;

use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Specials\SpecialBlankpage;

/**
 * @license GPL-2.0-or-later
 * @author Addshore
 *
 * @covers \MediaWiki\Specials\SpecialBlankpage
 */
class SpecialBlankPageTest extends SpecialPageTestBase {

	protected function setUp(): void {
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
		[ $html, ] = $this->executeSpecialPage();
		$this->assertStringContainsString( '(intentionallyblankpage)', $html );
	}

}
