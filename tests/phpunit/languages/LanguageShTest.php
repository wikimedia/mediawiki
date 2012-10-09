<?php
/**
 * @author Amir E. Aharoni
 * @copyright Copyright © 2012, Amir E. Aharoni
 * @file
 */

/** Tests for MediaWiki languages/classes/LanguageSh.php */
class LanguageShTest extends MediaWikiTestCase {
	private $lang;

	protected function setUp() {
		$this->lang = Language::factory( 'sh' );
	}
	protected function tearDown() {
		unset( $this->lang );
	}

	/** @dataProvider providerPlural */
	function testPlural( $result, $value ) {
		$forms = array( 'one', 'many' );
		$this->assertEquals( $result, $this->lang->convertPlural( $value, $forms ) );
	}

	function providerPlural() {
		return array (
			array( 'many', 0 ),
			array( 'one',  1 ),
			array( 'many', 2 ),
		);
	}
}
