<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2011, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/LanguageNl.php */
class LanguageNlTest extends MediaWikiTestCase {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'Nl' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	function testFormatNum() {
		$this->assertEquals( '1.234.567', $this->lang->formatNum( '1234567' ) );
		$this->assertEquals( '12.345', $this->lang->formatNum( '12345' ) );
		$this->assertEquals( '1', $this->lang->formatNum( '1' ) );
		$this->assertEquals( '123', $this->lang->formatNum( '123' ) );
		$this->assertEquals( '1.234', $this->lang->formatNum( '1234' ) );
		$this->assertEquals( '12.345,56', $this->lang->formatNum( '12345.56' ) );
		$this->assertEquals( ',1234556', $this->lang->formatNum( '.1234556' ) );
	}
}
