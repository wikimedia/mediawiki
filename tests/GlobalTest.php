<?php

require_once( 'PHPUnit.php' );
require_once( '../includes/Defines.php' );
require_once( '../includes/GlobalFunctions.php' );

class GlobalTest extends PHPUnit_TestCase {
	function GlobalTest( $name ) {
		$this->PHPUnit_TestCase( $name );
	}
	
	function setUp() {
		$this->save = array();
		$saveVars = array( 'wgReadOnlyFile' );
		foreach( $saveVars as $var ) {
			if( isset( $GLOBALS[$var] ) ) {
				$this->save[$var] = $GLOBALS[$var];
			}
		}
		$GLOBALS['wgReadOnlyFile'] = '/tmp/testReadOnly-' . mt_rand();
	}
	
	function tearDown() {
		foreach( $this->save as $var => $data ) {
			$GLOBALS[$var] = $data;
		}
	}
	
	function testDecodeLatin() {
		$this->assertEquals(
			"\xe9cole",
			do_html_entity_decode( '&eacute;cole', ENT_COMPAT, 'iso-8859-1' ) );
	}

	function testDecodeUnicode() {
		$this->assertEquals(
			"\xc3\xa9cole",
			do_html_entity_decode( '&eacute;cole', ENT_COMPAT, 'utf-8' ) );
	}

	function testRandom() {
		# This could hypothetically fail, but it shouldn't ;)
		$this->assertFalse(
			wfRandom() == wfRandom() );
	}
	
	function testUrlencode() {
		$this->assertEquals(
			"%E7%89%B9%E5%88%A5:Contributions/Foobar",
			wfUrlencode( "\xE7\x89\xB9\xE5\x88\xA5:Contributions/Foobar" ) );
	}
	
	function testUtf8Sequence1() {
		$this->assertEquals(
			'A',
			wfUtf8Sequence( 65 ) );
	}
	
	function testUtf8Sequence2() {
		$this->assertEquals(
			"\xc4\x88",
			wfUtf8Sequence( 0x108 ) );
	}

	function testUtf8Sequence3() {
		$this->assertEquals(
			"\xe3\x81\x8b",
			wfUtf8Sequence( 0x304b ) );
	}

	function testUtf8Sequence4() {
		$this->assertEquals(
			"\xf0\x90\x91\x90",
			wfUtf8Sequence( 0x10450 ) );
	}
	
	function testMungeToUtf8() {
		$this->assertEquals(
			"\xc4\x88io bonas dans l'\xc3\xa9cole!",
			wfMungeToUtf8( "&#x108;io bonas dans l'&#233;cole!" ) );
	}
	
	function testUtf8ToHTML() {
		$this->assertEquals(
			"&#264;io bonas dans l'&#233;cole!",
			wfUtf8ToHTML( "\xc4\x88io bonas dans l'\xc3\xa9cole!" ) );
	}
	
	function testReadOnlyEmpty() {
		$this->assertFalse( wfReadOnly() );
	}
	
	function testReadOnlySet() {
		$f = fopen( $GLOBALS['wgReadOnlyFile'], "wt" );
		fwrite( $f, 'Message' );
		fclose( $f );
		$this->assertTrue( wfReadOnly() );
		
		unlink( $GLOBALS['wgReadOnlyFile'] );
		$this->assertFalse( wfReadOnly() );
	}
	
	function testQuotedPrintable() {
		$this->assertEquals(
			"=?UTF-8?Q?=C4=88u=20legebla=3F?=",
			wfQuotedPrintable( "\xc4\x88u legebla?", "UTF-8" ) );
	}
	
	function testTime() {
		$start = wfTime();
		$this->assertType( 'double', $start );
		$end = wfTime();
		$this->assertTrue( $end > $start, "Time is running backwards!" );
	}
	
	function testArrayToCGI() {
		$this->assertEquals(
			"baz=AT%26T&foo=bar",
			wfArrayToCGI(
				array( 'baz' => 'AT&T', 'ignore' => '' ),
				array( 'foo' => 'bar', 'baz' => 'overridden value' ) ) );
	}
	
	/* TODO: many more! */
}

?>