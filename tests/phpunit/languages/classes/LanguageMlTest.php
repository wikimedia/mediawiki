<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2011, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/LanguageMl.php */
class LanguageMlTest extends LanguageClassesTestCase {

	/**
	 * @dataProvider providerFormatNum
	 * @see bug 29495
	 * @covers Language::formatNum
	 */
	public function testFormatNum( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->formatNum( $value ) );
	}

	public static function providerFormatNum() {
		return [
			[ '12,34,567', '1234567' ],
			[ '12,345', '12345' ],
			[ '1', '1' ],
			[ '123', '123' ],
			[ '1,234', '1234' ],
			[ '12,345.56', '12345.56' ],
			[ '12,34,56,79,81,23,45,678', '12345679812345678' ],
			[ '.12345', '.12345' ],
			[ '-12,00,000', '-1200000' ],
			[ '-98', '-98' ],
			[ '-98', -98 ],
			[ '-1,23,45,678', -12345678 ],
			[ '', '' ],
			[ '', null ],
		];
	}
}
