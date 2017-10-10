<?php
/**
 * @author Amir E. Aharoni
 * @copyright Copyright © 2012, Amir E. Aharoni
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguagePl.php */
class LanguagePlTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePlural
	 * @covers Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = [ 'one', 'few', 'many' ];
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
			[ 'many', 0 ],
			[ 'one', 1 ],
			[ 'few', 2 ],
			[ 'few', 3 ],
			[ 'few', 4 ],
			[ 'many', 5 ],
			[ 'many', 9 ],
			[ 'many', 10 ],
			[ 'many', 11 ],
			[ 'many', 21 ],
			[ 'few', 22 ],
			[ 'few', 23 ],
			[ 'few', 24 ],
			[ 'many', 25 ],
			[ 'many', 200 ],
			[ 'many', 201 ],
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
			[ 'other', 4 ],
			[ 'other', 5 ],
			[ 'other', 9 ],
			[ 'other', 10 ],
			[ 'other', 11 ],
			[ 'other', 21 ],
			[ 'other', 22 ],
			[ 'other', 23 ],
			[ 'other', 24 ],
			[ 'other', 25 ],
			[ 'other', 200 ],
			[ 'other', 201 ],
		];
	}

	/**
	 * @covers Language::commafy()
	 * @dataProvider provideCommafyData
	 */
	public function testCommafy( $number, $numbersWithCommas ) {
		$this->assertEquals(
			$numbersWithCommas,
			$this->getLang()->commafy( $number ),
			"commafy('$number')"
		);
	}

	public static function provideCommafyData() {
		// Note that commafy() always uses English separators (',' and '.') instead of
		// Polish (' ' and ','). There is another function that converts them later.
		return [
			[ 1000, '1000' ],
			[ 10000, '10,000' ],
			[ 1000.0001, '1000.0001' ],
			[ 10000.0001, '10,000.0001' ],
			[ -1000, '-1000' ],
			[ -10000, '-10,000' ],
			[ -1000.0001, '-1000.0001' ],
			[ -10000.0001, '-10,000.0001' ],
		];
	}
}
