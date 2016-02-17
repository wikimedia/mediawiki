<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012-2013, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageGd.php */
class LanguageGdTest extends LanguageClassesTestCase {
	/**
	 * @dataProvider providerPlural
	 * @covers Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = [ 'one', 'two', 'few', 'other' ];
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	public static function providerPlural() {
		return [
			[ 'other', 0 ],
			[ 'one', 1 ],
			[ 'two', 2 ],
			[ 'one', 11 ],
			[ 'two', 12 ],
			[ 'few', 3 ],
			[ 'few', 19 ],
			[ 'other', 200 ],
		];
	}

	/**
	 * @dataProvider providerPluralExplicit
	 * @covers Language::convertPlural
	 */
	public function testExplicitPlural( $result, $value ) {
		$forms = [ 'one', 'two', 'few', 'other', '11=Form11', '12=Form12' ];
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	public static function providerPluralExplicit() {
		return [
			[ 'other', 0 ],
			[ 'one', 1 ],
			[ 'two', 2 ],
			[ 'Form11', 11 ],
			[ 'Form12', 12 ],
			[ 'few', 3 ],
			[ 'few', 19 ],
			[ 'other', 200 ],
		];
	}
}
