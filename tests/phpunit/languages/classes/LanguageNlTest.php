<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2011, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/LanguageNl.php */
class LanguageNlTest extends LanguageClassesTestCase {

	public static function providerFormatNum() {
		return [
			[ '1.234.567', '1234567' ],
			[ '12.345', '12345' ],
			[ '1', '1' ],
			[ '123', '123' ],
			[ '1.234', '1234' ],
			[ '12.345,56', '12345.56' ],
			[ '12.345.679.812.345.678', '12345679812345678' ],
			[ '0,123', '.12345' ],
			[ '-1.200.000', '-1200000' ],
			[ '-98', '-98' ],
			[ '-98', -98 ],
			[ '-12.345.678', -12345678 ],
			[ '', '' ],
			[ '', null ]
		];
	}

	/**
	 * @dataProvider providerFormatNum
	 * @covers Language::formatNum
	 */
	public function testFormatNum( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->formatNum( $value ) );
	}
}
