<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageGv.php */
class LanguageGvTest extends MediaWikiTestCase {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'gv' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	/** @dataProvider providerPlural */
	function testPlural( $result, $value ) {
		// This is not compatible with CLDR plural rules http://unicode.org/repos/cldr-tmp/trunk/diff/supplemental/language_plural_rules.html#gv
		$forms =  array( 'Form 1', 'Form 2', 'Form 3', 'Form 4' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}
	function providerPlural() {
		return array (
			array( 'Form 4', 0 ),
			array( 'Form 2', 1 ),
			array( 'Form 3', 2 ),
			array( 'Form 4', 3 ),
			array( 'Form 1', 20 ),
			array( 'Form 2', 21 ),
			array( 'Form 3', 22 ),
			array( 'Form 4', 23 ),
			array( 'Form 4', 50 ),
		);
	}

}
