<?php

// @codingStandardsIgnoreStart Ignore Squiz.Classes.ValidClassName.NotCamelCaps
class LanguageBe_taraskTest extends LanguageClassesTestCase {
	// @codingStandardsIgnoreEnd
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
	 * @see bug 23156 & r64981
	 * @covers Language::commafy
	 */
	public function testSearchRightSingleQuotationMarkAsApostroph() {
		$this->assertEquals(
			"'",
			$this->getLang()->normalizeForSearch( '’' ),
			'bug 23156: U+2019 conversion to U+0027'
		);
	}

	/**
	 * @see bug 23156 & r64981
	 * @covers Language::commafy
	 */
	public function testCommafy() {
		$this->assertEquals( '1,234,567', $this->getLang()->commafy( '1234567' ) );
		$this->assertEquals( '12,345', $this->getLang()->commafy( '12345' ) );
	}

	/**
	 * @see bug 23156 & r64981
	 * @covers Language::commafy
	 */
	public function testDoesNotCommafyFourDigitsNumber() {
		$this->assertEquals( '1234', $this->getLang()->commafy( '1234' ) );
	}

	/**
	 * @dataProvider providePlural
	 * @covers Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = array( 'one', 'few', 'many', 'other' );
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
			array( 'one', 1 ),
			array( 'many', 11 ),
			array( 'one', 91 ),
			array( 'one', 121 ),
			array( 'few', 2 ),
			array( 'few', 3 ),
			array( 'few', 4 ),
			array( 'few', 334 ),
			array( 'many', 5 ),
			array( 'many', 15 ),
			array( 'many', 120 ),
		);
	}

	/**
	 * @dataProvider providePluralTwoForms
	 * @covers Language::convertPlural
	 */
	public function testPluralTwoForms( $result, $value ) {
		$forms = array( '1=one', 'other' );
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	public static function providePluralTwoForms() {
		return array(
			array( 'other', 0 ),
			array( 'one', 1 ),
			array( 'other', 11 ),
			array( 'other', 91 ),
			array( 'other', 121 ),
		);
	}
}
