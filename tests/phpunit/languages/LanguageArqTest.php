<?php
/**
 * Based on LanguagMlTest
 * @file
 */

/** Tests for MediaWiki languages/LanguageArq.php */
class LanguageArqTest extends LanguageClassesTestCase {
	/**
	 * @covers Language::formatNum
	 * @todo split into a test and a dataprovider
	 */
	public function testFormatNum() {
		$this->assertEquals( '1.234.567', $this->getLang()->formatNum( '1234567' ) );
		$this->assertEquals( '-12,89', $this->getLang()->formatNum( -12.89 ) );
	}

}
