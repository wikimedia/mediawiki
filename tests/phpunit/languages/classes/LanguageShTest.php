<?php
/**
 * @author Amir E. Aharoni
 * @copyright Copyright © 2012, Amir E. Aharoni
 * @file
 */

/** Tests for  srpskohrvatski / српскохрватски / Serbocroatian */
class LanguageShTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePlural
	 * @covers Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = array( 'one', 'few', 'other' );
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
			array( 'other', 0 ),
			array( 'one', 1 ),
			array( 'few', 2 ),
			array( 'few', 4 ),
			array( 'other', 5 ),
			array( 'other', 10 ),
			array( 'other', 11 ),
			array( 'other', 12 ),
			array( 'one', 101 ),
			array( 'few', 102 ),
			array( 'other', 111 ),
		);
	}
}
