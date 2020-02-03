<?php
/**
 * @author Amir E. Aharoni
 * @copyright Copyright © 2012, Amir E. Aharoni
 * @file
 */

/** Tests for Samogitian
 * @group Language
 */
class LanguageSgsTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePluralAllForms
	 * @covers Language::convertPlural
	 */
	public function testPluralAllForms( $result, $value ) {
		$forms = [ 'one', 'two', 'few', 'other' ];
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	/**
	 * @dataProvider providePluralAllForms
	 * @covers Language::getPluralRuleType
	 */
	public function testGetPluralRuleType( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->getPluralRuleType( $value ) );
	}

	public static function providePluralAllForms() {
		return [
			[ 'few', 0 ],
			[ 'one', 1 ],
			[ 'two', 2 ],
			[ 'other', 3 ],
			[ 'few', 10 ],
			[ 'few', 11 ],
			[ 'few', 12 ],
			[ 'few', 19 ],
			[ 'other', 20 ],
			[ 'few', 100 ],
			[ 'one', 101 ],
			[ 'few', 111 ],
			[ 'few', 112 ],
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
			[ 'other', 3 ],
			[ 'other', 10 ],
			[ 'other', 11 ],
			[ 'other', 12 ],
			[ 'other', 19 ],
			[ 'other', 20 ],
			[ 'other', 100 ],
			[ 'one', 101 ],
			[ 'other', 111 ],
			[ 'other', 112 ],
		];
	}
}
