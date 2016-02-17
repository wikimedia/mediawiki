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
			[ 'other', 0 ],
			[ 'one', 1 ],
			[ 'few', 2 ],
			[ 'few', 9 ],
			[ 'other', 10 ],
			[ 'other', 11 ],
			[ 'other', 20 ],
			[ 'one', 21 ],
			[ 'few', 32 ],
			[ 'one', 41 ],
			[ 'one', 40001 ],
		];
	}

	/**
	 * @dataProvider providePluralTwoForms
	 * @covers Language::convertPlural
	 */
	public function testOneFewPlural( $result, $value ) {
		$forms = [ 'one', 'other' ];
		// This fails for 21, but not sure why.
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	public static function providePluralTwoForms() {
		return [
			[ 'one', 1 ],
			[ 'other', 2 ],
			[ 'other', 15 ],
			[ 'other', 20 ],
			[ 'one', 21 ],
			[ 'other', 22 ],
		];
	}
}
