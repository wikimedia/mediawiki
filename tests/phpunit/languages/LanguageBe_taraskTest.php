<?php

class LanguageBe_taraskTest extends LanguageClassesTestCase {

	/**
	 * Make sure the language code we are given is indeed
	 * be-tarask. This is to ensure LanguageClassesTestCase
	 * does not give us the wrong language.
	 */
	function testBeTaraskTestsUsesBeTaraskCode() {
		$this->assertEquals( 'be-tarask',
			$this->getLang()->getCode()
		);
	}

	/** see bug 23156 & r64981 */
	function testSearchRightSingleQuotationMarkAsApostroph() {
		$this->assertEquals(
			"'",
			$this->getLang()->normalizeForSearch( '’' ),
			'bug 23156: U+2019 conversion to U+0027'
		);
	}

	/** see bug 23156 & r64981 */
	function testCommafy() {
		$this->assertEquals( '1,234,567', $this->getLang()->commafy( '1234567' ) );
		$this->assertEquals( '12,345', $this->getLang()->commafy( '12345' ) );
	}

	/** see bug 23156 & r64981 */
	function testDoesNotCommafyFourDigitsNumber() {
		$this->assertEquals( '1234', $this->getLang()->commafy( '1234' ) );
	}

	/** @dataProvider providePluralFourForms */
	function testPluralFourForms( $result, $value ) {
		$forms = array( 'one', 'few', 'many', 'other' );
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	function providePluralFourForms() {
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

	/** @dataProvider providePluralTwoForms */
	function testPluralTwoForms( $result, $value ) {
		$forms = array( 'one', 'several' );
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	function providePluralTwoForms() {
		return array(
			array( 'one', 1 ),
			array( 'several', 11 ),
			array( 'several', 91 ),
			array( 'several', 121 ),
		);
	}

}
