<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Amir E. Aharoni
 * based on LanguageSkTest.php
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageSl.php */
class LanguageSlTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providerPlural
	 * @covers Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = array( 'one', 'two', 'few', 'other' );
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	/**
	 * @dataProvider providerPlural
	 * @covers Language::getPluralRuleType
	 */
	public function testGetPluralRuleType( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->getPluralRuleType( $value ) );
	}

	public static function providerPlural() {
		return array(
			array( 'other', 0 ),
			array( 'one', 1 ),
			array( 'two', 2 ),
			array( 'few', 3 ),
			array( 'few', 4 ),
			array( 'other', 5 ),
			array( 'other', 99 ),
			array( 'other', 100 ),
			array( 'one', 101 ),
			array( 'two', 102 ),
			array( 'few', 103 ),
			array( 'one', 201 ),
		);
	}
}
