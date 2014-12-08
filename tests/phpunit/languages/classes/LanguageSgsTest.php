<?php
/**
 * @author Amir E. Aharoni
 * @copyright Copyright © 2012, Amir E. Aharoni
 * @file
 */

/** Tests for Samogitian */
class LanguageSgsTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePluralAllForms
	 * @covers Language::convertPlural
	 */
	public function testPluralAllForms( $result, $value ) {
		$forms = array( 'one', 'two', 'few', 'other' );
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
		return array(
			array( 'few', 0 ),
			array( 'one', 1 ),
			array( 'two', 2 ),
			array( 'other', 3 ),
			array( 'few', 10 ),
			array( 'few', 11 ),
			array( 'few', 12 ),
			array( 'few', 19 ),
			array( 'other', 20 ),
			array( 'few', 100 ),
			array( 'one', 101 ),
			array( 'few', 111 ),
			array( 'few', 112 ),
		);
	}

	/**
	 * @dataProvider providePluralTwoForms
	 * @covers Language::convertPlural
	 */
	public function testPluralTwoForms( $result, $value ) {
		$forms = array( 'one', 'other' );
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	public static function providePluralTwoForms() {
		return array(
			array( 'other', 0 ),
			array( 'one', 1 ),
			array( 'other', 2 ),
			array( 'other', 3 ),
			array( 'other', 10 ),
			array( 'other', 11 ),
			array( 'other', 12 ),
			array( 'other', 19 ),
			array( 'other', 20 ),
			array( 'other', 100 ),
			array( 'one', 101 ),
			array( 'other', 111 ),
			array( 'other', 112 ),
		);
	}
}
