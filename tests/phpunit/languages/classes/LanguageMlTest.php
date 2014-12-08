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
		return array(
			array( '12,34,567', '1234567' ),
			array( '12,345', '12345' ),
			array( '1', '1' ),
			array( '123', '123' ),
			array( '1,234', '1234' ),
			array( '12,345.56', '12345.56' ),
			array( '12,34,56,79,81,23,45,678', '12345679812345678' ),
			array( '.12345', '.12345' ),
			array( '-12,00,000', '-1200000' ),
			array( '-98', '-98' ),
			array( '-98', -98 ),
			array( '-1,23,45,678', -12345678 ),
			array( '', '' ),
			array( '', null ),
		);
	}
}
