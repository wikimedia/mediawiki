<?php
require_once dirname(dirname(__FILE__)). '/bootstrap.php';

class LanguageBeTaraskTest extends MediaWikiTestCase {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'Be_tarask' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	/** see bug 23156 & r64981 */
	function testSearchRightSingleQuotationMarkAsApostroph() {
		$this->assertEquals(
			"'",
			$this->lang->normalizeForSearch( '’' ),
			'bug 23156: U+2019 conversion to U+0027'
		);
	}
	/** see bug 23156 & r64981 */
	function testCommafy() {
		$this->assertEquals( '1,234,567', $this->lang->commafy( '1234567' ) );
		$this->assertEquals(    '12,345', $this->lang->commafy(   '12345' ) );
	}
	/** see bug 23156 & r64981 */
	function testDoesNotCommafyFourDigitsNumber() {
		$this->assertEquals(      '1234', $this->lang->commafy(    '1234' ) );
	}
}
