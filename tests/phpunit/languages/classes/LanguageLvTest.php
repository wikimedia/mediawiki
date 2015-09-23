<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */

/** Tests for Latvian */
class LanguageLvTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePlural
	 * @covers Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = array( 'zero', 'one', 'other' );
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
			array( 'zero', 0 ),
			array( 'one', 1 ),
			array( 'zero', 11 ),
			array( 'one', 21 ),
			array( 'zero', 411 ),
			array( 'other', 2 ),
			array( 'other', 9 ),
			array( 'zero', 12 ),
			array( 'other', 12.345 ),
			array( 'zero', 20 ),
			array( 'other', 22 ),
			array( 'one', 31 ),
			array( 'zero', 200 ),
		);
	}
}
