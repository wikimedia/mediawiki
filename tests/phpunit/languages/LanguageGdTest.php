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
		$forms = array( 'one', 'two', 'few', 'other' );
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	public static function providerPlural() {
		return array(
			array( 'other', 0 ),
			array( 'one', 1 ),
			array( 'two', 2 ),
			array( 'one', 11 ),
			array( 'two', 12 ),
			array( 'few', 3 ),
			array( 'few', 19 ),
			array( 'other', 200 ),
		);
	}

	/**
	 * @dataProvider providerPluralExplicit
	 * @covers Language::convertPlural
	 */
	public function testExplicitPlural( $result, $value ) {
		$forms = array( 'one', 'two', 'few', 'other', '11=Form11', '12=Form12' );
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	public static function providerPluralExplicit() {
		return array(
			array( 'other', 0 ),
			array( 'one', 1 ),
			array( 'two', 2 ),
			array( 'Form11', 11 ),
			array( 'Form12', 12 ),
			array( 'few', 3 ),
			array( 'few', 19 ),
			array( 'other', 200 ),
		);
	}
}
