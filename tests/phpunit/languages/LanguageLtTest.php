<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2012, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/LanguageLt.php */
class LanguageLtTest extends MediaWikiTestCase {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'Lt' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	function testPlural() {
		$forms =  array( 'one', 'few', 'other' );
		$this->assertEquals( 'other', $this->lang->convertPlural( 0, $forms ) );
		$this->assertEquals( 'one', $this->lang->convertPlural( 1, $forms ) );
		$this->assertEquals( 'few', $this->lang->convertPlural( 2, $forms ) );
		$this->assertEquals( 'few', $this->lang->convertPlural( 9, $forms ) );
		$this->assertEquals( 'other', $this->lang->convertPlural( 10, $forms ) );
		$this->assertEquals( 'other', $this->lang->convertPlural( 11, $forms ) );
		$this->assertEquals( 'other', $this->lang->convertPlural( 20, $forms ) );
		$this->assertEquals( 'one', $this->lang->convertPlural( 21, $forms ) );
		$this->assertEquals( 'few', $this->lang->convertPlural( 32, $forms ) );
		$this->assertEquals( 'one', $this->lang->convertPlural( 41, $forms ) );
		$this->assertEquals( 'one', $this->lang->convertPlural( 40001, $forms ) );
		$forms =  array( 'one', 'few' );
		$this->assertEquals( 'one', $this->lang->convertPlural( 1, $forms ) );
		$this->assertEquals( 'few', $this->lang->convertPlural( 15, $forms ) );
	}
}
