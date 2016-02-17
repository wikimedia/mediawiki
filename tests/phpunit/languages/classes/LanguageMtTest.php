<?php
/**
 * @author Amir E. Aharoni
 * @copyright Copyright © 2012, Amir E. Aharoni
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageMt.php */
class LanguageMtTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePlural
	 * @covers Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = [ 'one', 'few', 'many', 'other' ];
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
			[ 'few', 0 ],
			[ 'one', 1 ],
			[ 'few', 2 ],
			[ 'few', 10 ],
			[ 'many', 11 ],
			[ 'many', 19 ],
			[ 'other', 20 ],
			[ 'other', 99 ],
			[ 'other', 100 ],
			[ 'other', 101 ],
			[ 'few', 102 ],
			[ 'few', 110 ],
			[ 'many', 111 ],
			[ 'many', 119 ],
			[ 'other', 120 ],
			[ 'other', 201 ],
		];
	}

	/**
	 * @dataProvider providePluralTwoForms
	 * @covers Language::convertPlural
	 */
	public function testPluralTwoForms( $result, $value ) {
		$forms = [ 'one', 'other' ];
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	public static function providePluralTwoForms() {
		return [
			[ 'other', 0 ],
			[ 'one', 1 ],
			[ 'other', 2 ],
			[ 'other', 10 ],
			[ 'other', 11 ],
			[ 'other', 19 ],
			[ 'other', 20 ],
			[ 'other', 99 ],
			[ 'other', 100 ],
			[ 'other', 101 ],
			[ 'other', 102 ],
			[ 'other', 110 ],
			[ 'other', 111 ],
			[ 'other', 119 ],
			[ 'other', 120 ],
			[ 'other', 201 ],
		];
	}
}
