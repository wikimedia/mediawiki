<?php
/**
 * Test for Manx (Gaelg) language
 *
 * @author Santhosh Thottingal
 * @copyright Copyright © 2013, Santhosh Thottingal
 * @file
 */

class LanguageGvTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePlural
	 * @covers Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = array( 'one', 'two', 'few', 'other' );
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	/**
	 * @dataProvider providePlural
	 * @covers Language::getPluralRuleType
	 */
	public function testGetPluralRuleType( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->getPluralRuleType( $value ) );
	}

	public static function providePlural() {
		return array(
			array( 'few', 0 ),
			array( 'one', 1 ),
			array( 'two', 2 ),
			array( 'other', 3 ),
			array( 'few', 20 ),
			array( 'one', 21 ),
			array( 'two', 22 ),
			array( 'other', 23 ),
			array( 'other', 50 ),
			array( 'few', 60 ),
			array( 'few', 80 ),
			array( 'few', 100 )
		);
	}
}
