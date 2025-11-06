<?php
// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps

namespace MediaWiki\Tests\Languages;

use MediaWiki\Tests\Language\LanguageClassesTestCase;

/**
 * @group Language
 * @covers \LanguageBe_tarask
 */
class LanguageBe_taraskTest extends LanguageClassesTestCase {
	// phpcs:enable

	/**
	 * Make sure the language code we are given is indeed
	 * be-tarask. This is to ensure LanguageClassesTestCase
	 * does not give us the wrong language.
	 */
	public function testBeTaraskTestsUsesBeTaraskCode() {
		$this->assertEquals( 'be-tarask',
			$this->getLang()->getCode()
		);
	}

	/**
	 * @see T25156 & r64981
	 * @covers \MediaWiki\Language\Language::normalizeForSearch
	 */
	public function testSearchRightSingleQuotationMarkAsApostroph() {
		$this->assertEquals(
			"'",
			$this->getLang()->normalizeForSearch( '’' ),
			'T25156: U+2019 conversion to U+0027'
		);
	}

	/**
	 * @see T25156 & r64981
	 * @covers \MediaWiki\Language\Language::formatNum
	 */
	public function testFormatNum() {
		$this->assertEquals( '1 234 567', $this->getLang()->formatNum( '1234567' ) );
		$this->assertEquals( '12 345', $this->getLang()->formatNum( '12345' ) );
	}

	/**
	 * @see T25156 & r64981
	 * @covers \MediaWiki\Language\Language::formatNum
	 */
	public function testDoesNotCommafyFourDigitsNumber() {
		$this->assertSame( '1234', $this->getLang()->formatNum( '1234' ) );
	}

	/**
	 * @dataProvider providePlural
	 * @covers \MediaWiki\Language\Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = [ 'one', 'few', 'many', 'other' ];
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
			[ 'one', 1 ],
			[ 'many', 11 ],
			[ 'one', 91 ],
			[ 'one', 121 ],
			[ 'few', 2 ],
			[ 'few', 3 ],
			[ 'few', 4 ],
			[ 'few', 334 ],
			[ 'many', 5 ],
			[ 'many', 15 ],
			[ 'many', 120 ],
		];
	}

	/**
	 * @dataProvider providePluralTwoForms
	 * @covers \MediaWiki\Language\Language::convertPlural
	 */
	public function testPluralTwoForms( $result, $value ) {
		$forms = [ '1=one', 'other' ];
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	public static function providePluralTwoForms() {
		return [
			[ 'other', 0 ],
			[ 'one', 1 ],
			[ 'other', 11 ],
			[ 'other', 91 ],
			[ 'other', 121 ],
		];
	}
}
