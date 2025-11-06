<?php
/**
 * @author Amir E. Aharoni
 * @copyright Copyright © 2012, Amir E. Aharoni
 * @file
 */
namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

/**
 * @group Language
 */
class LanguagePlTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePlural
	 * @covers \MediaWiki\Language\Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = [ 'one', 'few', 'many' ];
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	/**
	 * @dataProvider providePlural
	 * @covers \MediaWiki\Language\Language::getPluralRuleType
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
	 * @covers \MediaWiki\Language\Language::convertPlural
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
	 * @covers \MediaWiki\Language\Language::formatNum()
	 * @dataProvider provideFormatNum
	 */
	public function testFormatNum( $number, $formattedNum, $desc ) {
		$this->assertEquals(
			$formattedNum,
			$this->getLang()->formatNum( $number ),
			$desc
		);
	}

	public static function provideFormatNum() {
		return [
			[ 1000, '1000', 'No change' ],
			[ 10000, '10 000', 'Only separator transform. Separator is NO-BREAK Space, not Space' ],
			[ 1000.0001, '1000,0001',
				'No change since this is below minimumGroupingDigits, just separator transform' ],
			[ 10000.123456, '10 000,123456', 'separator transform' ],
			[ -1000, '−1000', 'No change, other than minus replacement' ],
			[ -10000, '−10 000', 'Only separator transform' ],
			[ -1000.0001, '−1000,0001',
				'No change since this is below minimumGroupingDigits, just separator transform' ],
			[ -10000.789, '−10 000,789', '' ],
		];
	}
}
