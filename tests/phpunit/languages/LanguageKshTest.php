<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageKsh.php */
class LanguageKshTest extends LanguageClassesTestCase {

	/** @dataProvider providerPlural */
	function testPlural( $result, $value ) {
		$forms = array( 'one', 'other', 'zero' );
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	function providerPlural() {
		return array(
			array( 'zero', 0 ),
			array( 'one', 1 ),
			array( 'other', 2 ),
			array( 'other', 200 ),
		);
	}

}
