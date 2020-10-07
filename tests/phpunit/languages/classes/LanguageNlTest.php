<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2011, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/LanguageNl.php
 * @group Language
 */
class LanguageNlTest extends LanguageClassesTestCase {

	/**
	 * @covers Language::formatNum
	 * @dataProvider provideFormatNum
	 */
	public function testFormatNum( $formatted, $unformatted ) {
		$this->assertEquals( $formatted, $this->getLang()->formatNum( $unformatted ) );
	}

	public function provideFormatNum() {
		return [
			[ '1.234.567', '1234567' ],
			[ '12.345', '12345' ],
			[ '1', '1' ],
			[ '123', '123' ],
			[ '1.234', '1234' ],
			[ '12.345,56', '12345.56' ],
			[ ',1234556', '.1234556' ],
		];
	}
}
