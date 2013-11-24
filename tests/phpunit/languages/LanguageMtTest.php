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
			array( 'few', 0 ),
			array( 'one', 1 ),
			array( 'few', 2 ),
			array( 'few', 10 ),
			array( 'many', 11 ),
			array( 'many', 19 ),
			array( 'other', 20 ),
			array( 'other', 99 ),
			array( 'other', 100 ),
			array( 'other', 101 ),
			array( 'few', 102 ),
			array( 'few', 110 ),
			array( 'many', 111 ),
			array( 'many', 119 ),
			array( 'other', 120 ),
			array( 'other', 201 ),
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
			array( 'other', 10 ),
			array( 'other', 11 ),
			array( 'other', 19 ),
			array( 'other', 20 ),
			array( 'other', 99 ),
			array( 'other', 100 ),
			array( 'other', 101 ),
			array( 'other', 102 ),
			array( 'other', 110 ),
			array( 'other', 111 ),
			array( 'other', 119 ),
			array( 'other', 120 ),
			array( 'other', 201 ),
		);
	}
}
