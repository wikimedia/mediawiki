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
		$forms = [ 'one', 'two', 'few', 'other' ];
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
		return [
			[ 'other', 0 ],
			[ 'one', 1 ],
			[ 'two', 2 ],
			[ 'few', 3 ],
			[ 'few', 4 ],
			[ 'other', 5 ],
			[ 'other', 99 ],
			[ 'other', 100 ],
			[ 'one', 101 ],
			[ 'two', 102 ],
			[ 'few', 103 ],
			[ 'one', 201 ],
		];
	}
}
