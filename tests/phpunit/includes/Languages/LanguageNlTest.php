<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2011, Santhosh Thottingal
 * @file
 */
namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

/**
 * @group Language
 */
class LanguageNlTest extends LanguageClassesTestCase {

	/**
	 * @covers \MediaWiki\Language\Language::formatNum
	 * @dataProvider provideFormatNum
	 */
	public function testFormatNum( $unformatted, $formatted ) {
		$this->assertEquals( $formatted, $this->getLang()->formatNum( $unformatted ) );
	}

	public static function provideFormatNum() {
		return [
			[ '1234567', '1.234.567' ],
			[ '12345', '12.345' ],
			[ '1', '1' ],
			[ '123', '123' ],
			[ '1234', '1.234' ],
			[ '12345.56', '12.345,56' ],
			[ '.1234556', ',1234556' ],
			[ '12345679812345678', '12.345.679.812.345.678' ],
			[ '.12345', ',12345' ],
			[ '-1200000', '−1.200.000' ],
			[ '-98', '−98' ],
			[ -98, '−98' ],
			[ -12345678, '−12.345.678' ],
			[ '', '' ],
			[ null, '' ]
		];
	}
}
