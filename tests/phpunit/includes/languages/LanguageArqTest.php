<?php
/**
 * @author Joel Sahleen
 * @copyright Copyright © 2014, Joel Sahleen
 * @file
 */

/**
 * @group Language
 */
class LanguageArqTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider provideNumber
	 * @covers Language::formatNum
	 */
	public function testFormatNum( $value, $result ) {
		$this->assertEquals( $result, $this->getLang()->formatNum( $value ) );
	}

	public static function provideNumber() {
		return [
			[ '1234567', '1.234.567' ],
			[ '1234567.568', '1.234.567,568' ],
			[ '-12.89', '−12,89' ]
		];
	}

}
