<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */

/** Tests for Latvian */
class LanguageLvTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePlural
	 * @covers Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = [ 'zero', 'one', 'other' ];
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
		return [
			[ 'zero', 0 ],
			[ 'one', 1 ],
			[ 'zero', 11 ],
			[ 'one', 21 ],
			[ 'zero', 411 ],
			[ 'other', 2 ],
			[ 'other', 9 ],
			[ 'zero', 12 ],
			[ 'other', 12.345 ],
			[ 'zero', 20 ],
			[ 'other', 22 ],
			[ 'one', 31 ],
			[ 'zero', 200 ],
		];
	}
}
