<?php
/**
 * @author Amir E. Aharoni
 * @copyright Copyright © 2012, Amir E. Aharoni
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageNso.php */
class LanguageNsoTest extends LanguageClassesTestCase {

	/** @dataProvider providerPlural */
	function testPlural( $result, $value ) {
		$forms = array( 'one', 'many' );
		$this->assertEquals( $result, $this->getLang()->convertPlural( $value, $forms ) );
	}

	function providerPlural() {
		return array(
			array( 'one', 0 ),
			array( 'one', 1 ),
			array( 'many', 2 ),
		);
	}
}
