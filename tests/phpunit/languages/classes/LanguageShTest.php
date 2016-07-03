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
		$forms = [ 'one', 'few', 'other' ];
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
		return [
			[ 'other', 0 ],
			[ 'one', 1 ],
			[ 'few', 2 ],
			[ 'few', 4 ],
			[ 'other', 5 ],
			[ 'other', 10 ],
			[ 'other', 11 ],
			[ 'other', 12 ],
			[ 'one', 101 ],
			[ 'few', 102 ],
			[ 'other', 111 ],
		];
	}
}
