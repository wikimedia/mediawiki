<?php
/**
 * Based on LanguagMlTest
 * @file
 */

/** Tests for MediaWiki languages/LanguageAr.php */
class LanguageArTest extends MediaWikiTestCase {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'Ar' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	function testFormatNum() {
		$this->assertEquals( '١٬٢٣٤٬٥٦٧', $this->lang->formatNum( '1234567' ) );
		$this->assertEquals( '-١٢٫٨٩', $this->lang->formatNum( -12.89 ) );
	}

	/**
	 * Mostly to test the raw ascii feature.
	 * @dataProvider providerSprintfDate
	 */
	function testSprintfDate( $format, $date, $expected ) {
		$this->assertEquals( $expected, $this->lang->sprintfDate( $format, $date ) );
	}

	function providerSprintfDate() {
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
}
