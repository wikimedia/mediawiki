<?php
/**
 * @author Amir E. Aharoni
 * @copyright Copyright © 2012, Amir E. Aharoni
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageSh.php */
class LanguageShTest extends LanguageClassesTestCase {
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
			array( 'many', 0 ),
			array( 'one', 1 ),
			array( 'few', 2 ),
			array( 'few', 4 ),
			array( 'many', 5 ),
			array( 'many', 10 ),
			array( 'many', 11 ),
			array( 'many', 12 ),
			array( 'one', 101 ),
			array( 'few', 102 ),
			array( 'many', 111 ),
		);
	}
}
