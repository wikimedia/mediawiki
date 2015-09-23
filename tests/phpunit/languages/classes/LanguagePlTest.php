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
		$forms = array( 'one', 'few', 'many' );
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
			array( 'many', 0 ),
			array( 'one', 1 ),
			array( 'few', 2 ),
			array( 'few', 3 ),
			array( 'few', 4 ),
			array( 'many', 5 ),
			array( 'many', 9 ),
			array( 'many', 10 ),
			array( 'many', 11 ),
			array( 'many', 21 ),
			array( 'few', 22 ),
			array( 'few', 23 ),
			array( 'few', 24 ),
			array( 'many', 25 ),
			array( 'many', 200 ),
			array( 'many', 201 ),
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
			array( 'other', 4 ),
			array( 'other', 5 ),
			array( 'other', 9 ),
			array( 'other', 10 ),
			array( 'other', 11 ),
			array( 'other', 21 ),
			array( 'other', 22 ),
			array( 'other', 23 ),
			array( 'other', 24 ),
			array( 'other', 25 ),
			array( 'other', 200 ),
			array( 'other', 201 ),
		);
	}
}
