<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageCy.php */
class LanguageCyTest extends LanguageClassesTestCase {

	/** @dataProvider providerPlural */
	function testPlural( $result, $value ) {
		$forms = array( 'zero', 'one', 'two', 'few', 'many', 'other' );
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	function providerPlural() {
		return array(
			array( 'zero', 0 ),
			array( 'one', 1 ),
			array( 'two', 2 ),
			array( 'few', 3 ),
			array( 'many', 6 ),
			array( 'other', 4 ),
			array( 'other', 5 ),
			array( 'other', 11 ),
			array( 'other', 20 ),
			array( 'other', 22 ),
			array( 'other', 223 ),
			array( 'other', 200.00 ),
		);
	}

}
