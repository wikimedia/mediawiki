<?php
/**
 * Based on LanguageMlTest
 * @author Joel Sahleen
 * @copyright Copyright © 2014, Joel Sahleen
 * @file
 */

/** Tests for MediaWiki languages/LanguageArq.php */
class LanguageArqTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider provideNumber
	 * @covers Language::formatNum
	 */
	public function testFormatNum( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->formatNum( $value ) );
	}

	public static function provideNumber() {
		return array(
			array( '1.234.567', '1234567' ),
			array( '-12,89', -12.89 ),
			);
	}

}
