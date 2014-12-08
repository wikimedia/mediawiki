<?php
/**
 * Based on LanguagMlTest
 * @file
 */

/** Tests for MediaWiki languages/LanguageAr.php */
class LanguageArTest extends LanguageClassesTestCase {
	/**
	 * @covers Language::formatNum
	 * @todo split into a test and a dataprovider
	 */
	public function testFormatNum() {
		$this->assertEquals( '١٬٢٣٤٬٥٦٧', $this->getLang()->formatNum( '1234567' ) );
		$this->assertEquals( '-١٢٫٨٩', $this->getLang()->formatNum( -12.89 ) );
	}

	/**
	 * Mostly to test the raw ascii feature.
	 * @dataProvider providerSprintfDate
	 * @covers Language::sprintfDate
	 */
	public function testSprintfDate( $format, $date, $expected ) {
		$this->assertEquals( $expected, $this->getLang()->sprintfDate( $format, $date ) );
	}

	public static function providerSprintfDate() {
		return array(
			array(
				'xg "vs" g',
				'20120102030410',
				'يناير vs ٣'
			),
			array(
				'xmY',
				'20120102030410',
				'١٤٣٣'
			),
			array(
				'xnxmY',
				'20120102030410',
				'1433'
			),
			array(
				'xN xmj xmn xN xmY',
				'20120102030410',
				' 7 2  ١٤٣٣'
			),
		);
	}

	/**
	 * @dataProvider providePlural
	 * @covers Language::convertPlural
	 */
	public function testPlural( $result, $value ) {
		$forms = array( 'zero', 'one', 'two', 'few', 'many', 'other' );
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
			array( 'zero', 0 ),
			array( 'one', 1 ),
			array( 'two', 2 ),
			array( 'few', 3 ),
			array( 'few', 9 ),
			array( 'few', 110 ),
			array( 'many', 11 ),
			array( 'many', 15 ),
			array( 'many', 99 ),
			array( 'many', 9999 ),
			array( 'other', 100 ),
			array( 'other', 102 ),
			array( 'other', 1000 ),
			array( 'other', 1.7 ),
		);
	}
}
