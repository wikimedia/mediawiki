<?php

namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

class LanguageNbTest extends LanguageClassesTestCase {
	/**
	 * Regression test for T391423
	 * @covers \MediaWiki\Language\Language::getJsDateFormats
	 */
	public function testGetJsDateFormats() {
		$lang = $this->getLang();
		$result = $lang->getJsDateFormats();
		$this->assertArrayHasKey( 'dmy both', $result );
	}
}
