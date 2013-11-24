<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/LanguageLt.php */
class LanguageLtTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePlural
	 * @covers Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = array( 'one', 'few', 'other' );
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
			array( 'other', 0 ),
			array( 'one', 1 ),
			array( 'few', 2 ),
			array( 'few', 9 ),
			array( 'other', 10 ),
			array( 'other', 11 ),
			array( 'other', 20 ),
			array( 'one', 21 ),
			array( 'few', 32 ),
			array( 'one', 41 ),
			array( 'one', 40001 ),
		);
	}

	/**
	 * @dataProvider providePluralTwoForms
	 * @covers Language::convertPlural
	 */
	public function testOneFewPlural( $result, $value ) {
		$forms = array( 'one', 'other' );
		// This fails for 21, but not sure why.
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	public static function providePluralTwoForms() {
		return array(
			array( 'one', 1 ),
			array( 'other', 2 ),
			array( 'other', 15 ),
			array( 'other', 20 ),
			array( 'one', 21 ),
			array( 'other', 22 ),
		);
	}
}
