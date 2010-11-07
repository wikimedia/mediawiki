<?php
require_once dirname(dirname(__FILE__)). '/bootstrap.php';

class LanguageTest extends MediaWikiTestSetup {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'en' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	function testLanguageConvertDoubleWidthToSingleWidth() {
		$this->assertEquals(
			"0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz",
			$this->lang->normalizeForSearch(
				"０１２３４５６７８９ＡＢＣＤＥＦＧＨＩＪＫＬＭＮＯＰＱＲＳＴＵＶＷＸＹＺａｂｃｄｅｆｇｈｉｊｋｌｍｎｏｐｑｒｓｔｕｖｗｘｙｚ"
			),
			'convertDoubleWidth() with the full alphabet and digits'
		);
	}
}
