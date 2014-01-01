<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */

/** Tests for македонски/Macedonian */
class LanguageMkTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePlural
	 * @covers Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = array( 'one', 'other' );
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
			array( 'one', 11 ),
			array( 'one', 21 ),
			array( 'one', 411 ),
			array( 'other', 12.345 ),
			array( 'other', 20 ),
			array( 'one', 31 ),
			array( 'other', 200 ),
		);
	}
}
