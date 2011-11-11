<?php
/**
 * @author Santhosh Thottingal
 * @copyright Copyright © 2011, Santhosh Thottingal
 * @file
 */

/** Tests for MediaWiki languages/LanguageMl.php */
class LanguageMlTest extends MediaWikiTestCase {
	private $lang;

	function setUp() {
		$this->lang = Language::factory( 'Ml' );
	}
	function tearDown() {
		unset( $this->lang );
	}

	/** see bug 29495 */
	function testFormatNum() {
		$this->assertEquals( '12,34,567', $this->lang->formatNum( '1234567' ) );
		$this->assertEquals( '12,345', $this->lang->formatNum( '12345' ) );
		$this->assertEquals( '1', $this->lang->formatNum( '1' ) );
		$this->assertEquals( '123', $this->lang->formatNum( '123' ) );
		$this->assertEquals( '1,234', $this->lang->formatNum( '1234' ) );
		$this->assertEquals( '12,345.56', $this->lang->formatNum( '12345.56' ) );
		$this->assertEquals( '12,34,56,79,81,23,45,678', $this->lang->formatNum( '12345679812345678' ) );
		$this->assertEquals( '.12345', $this->lang->formatNum( '.12345' ) );
		$this->assertEquals( '-12,00,000', $this->lang->formatNum( '-1200000' ) );
		$this->assertEquals( '-98', $this->lang->formatNum( '-98' ) );
	}
}
