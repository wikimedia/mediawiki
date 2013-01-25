<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageGd.php */
class LanguageGdTest extends LanguageClassesTestCase {

	/** @dataProvider providePlural */
	function testPlural( $result, $value ) {
		// The CLDR ticket for this plural forms is not same as mw plural forms. See http://unicode.org/cldr/trac/ticket/2883
		$forms =  array( 'one', 'two', 'eleven', 'twelve', 'few', 'other' );
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	/** @dataProvider providePlural */
	function testGetPluralRuleType( $result, $value ) {
		$this->assertEquals( $result, $this->getLang()->getPluralRuleType( $value ) );
	}

	function providePlural() {
		return array (
			array( 'other', 0 ),
			array( 'one', 1 ),
			array( 'two', 2 ),
			array( 'eleven', 11 ),
			array( 'twelve', 12 ),
			array( 'few', 3 ),
			array( 'few', 19 ),
			array( 'other', 200 ),
		);
	}

}
