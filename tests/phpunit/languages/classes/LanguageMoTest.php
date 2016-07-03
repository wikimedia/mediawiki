<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageMo.php */
class LanguageMoTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providePlural
	 * @covers Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = [ 'one', 'few', 'other' ];
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
			[ 'few', 0 ],
			[ 'one', 1 ],
			[ 'few', 2 ],
			[ 'few', 19 ],
			[ 'other', 20 ],
			[ 'other', 99 ],
			[ 'other', 100 ],
			[ 'few', 101 ],
			[ 'few', 119 ],
			[ 'other', 120 ],
			[ 'other', 200 ],
			[ 'few', 201 ],
			[ 'few', 219 ],
			[ 'other', 220 ],
		];
	}
}
