<?php

require_once( 'PHPUnit.php' );
require_once( '../includes/Defines.php' );
require_once( '../includes/Article.php' );

class ArticleTest extends PHPUnit_TestCase {
	var $saveGlobals = array();

	function ArticleTest( $name ) {
		$this->PHPUnit_TestCase( $name );
	}

	function setUp() {
		$globalSet = array(
			'wgLegacyEncoding' => false,
			'wgUseLatin1' => false,
			'wgCompressRevisions' => false,
			'wgInputEncoding' => 'utf-8',
			'wgOutputEncoding' => 'utf-8' );
		foreach( $globalSet as $var => $data ) {
			$this->saveGlobals[$var] = $GLOBALS[$var];
			$GLOBALS[$var] = $data;
		}
	}

	function tearDown() {
		foreach( $this->saveGlobals as $var => $data ) {
			$GLOBALS[$var] = $data;
		}
	}

	function testGetRevisionText() {
		$row = new stdClass;
		$row->old_flags = '';
		$row->old_text = 'This is a bunch of revision text.';
		$this->assertEquals(
			'This is a bunch of revision text.',
			Revision::getRevisionText( $row ) );
	}

	function testGetRevisionTextGzip() {
		$row = new stdClass;
		$row->old_flags = 'gzip';
		$row->old_text = gzdeflate( 'This is a bunch of revision text.' );
		$this->assertEquals(
			'This is a bunch of revision text.',
			Revision::getRevisionText( $row ) );
	}

	function testGetRevisionTextUtf8Native() {
		$row = new stdClass;
		$row->old_flags = 'utf-8';
		$row->old_text = "Wiki est l'\xc3\xa9cole superieur !";
		$GLOBALS['wgLegacyEncoding'] = 'iso-8859-1';
		$this->assertEquals(
			"Wiki est l'\xc3\xa9cole superieur !",
			Revision::getRevisionText( $row ) );
	}

	function testGetRevisionTextUtf8Legacy() {
		$row = new stdClass;
		$row->old_flags = '';
		$row->old_text = "Wiki est l'\xe9cole superieur !";
		$GLOBALS['wgLegacyEncoding'] = 'iso-8859-1';
		$this->assertEquals(
			"Wiki est l'\xc3\xa9cole superieur !",
			Revision::getRevisionText( $row ) );
	}

	function testGetRevisionTextUtf8NativeGzip() {
		$row = new stdClass;
		$row->old_flags = 'gzip,utf-8';
		$row->old_text = gzdeflate( "Wiki est l'\xc3\xa9cole superieur !" );
		$GLOBALS['wgLegacyEncoding'] = 'iso-8859-1';
		$this->assertEquals(
			"Wiki est l'\xc3\xa9cole superieur !",
			Revision::getRevisionText( $row ) );
	}

	function testGetRevisionTextUtf8LegacyGzip() {
		$row = new stdClass;
		$row->old_flags = 'gzip';
		$row->old_text = gzdeflate( "Wiki est l'\xe9cole superieur !" );
		$GLOBALS['wgLegacyEncoding'] = 'iso-8859-1';
		$this->assertEquals(
			"Wiki est l'\xc3\xa9cole superieur !",
			Revision::getRevisionText( $row ) );
	}

	function testCompressRevisionTextUtf8() {
		$row->old_text = "Wiki est l'\xc3\xa9cole superieur !";
		$row->old_flags = Revision::compressRevisionText( $row->old_text );
		$this->assertTrue( false !== strpos( $row->old_flags, 'utf-8' ),
			"Flags should contain 'utf-8'" );
		$this->assertFalse( false !== strpos( $row->old_flags, 'gzip' ),
			"Flags should not contain 'gzip'" );
		$this->assertEquals( "Wiki est l'\xc3\xa9cole superieur !",
			$row->old_text, "Direct check" );
		$this->assertEquals( "Wiki est l'\xc3\xa9cole superieur !",
			Revision::getRevisionText( $row ), "getRevisionText" );
	}

	function testCompressRevisionTextLatin1() {
		$GLOBALS['wgUseLatin1'] = true;
		$row->old_text = "Wiki est l'\xe9cole superieur !";
		$row->old_flags = Revision::compressRevisionText( $row->old_text );
		$this->assertFalse( false !== strpos( $row->old_flags, 'utf-8' ),
			"Flags should not contain 'utf-8'" );
		$this->assertFalse( false !== strpos( $row->old_flags, 'gzip' ),
			"Flags should not contain 'gzip'" );
		$this->assertEquals( "Wiki est l'\xe9cole superieur !",
			$row->old_text, "Direct check" );
		$this->assertEquals( "Wiki est l'\xe9cole superieur !",
			Revision::getRevisionText( $row ), "getRevisionText" );
	}

	function testCompressRevisionTextUtf8Gzip() {
		$GLOBALS['wgCompressRevisions'] = true;
		$row->old_text = "Wiki est l'\xc3\xa9cole superieur !";
		$row->old_flags = Revision::compressRevisionText( $row->old_text );
		$this->assertTrue( false !== strpos( $row->old_flags, 'utf-8' ),
			"Flags should contain 'utf-8'" );
		$this->assertTrue( false !== strpos( $row->old_flags, 'gzip' ),
			"Flags should contain 'gzip'" );
		$this->assertEquals( "Wiki est l'\xc3\xa9cole superieur !",
			gzinflate( $row->old_text ), "Direct check" );
		$this->assertEquals( "Wiki est l'\xc3\xa9cole superieur !",
			Revision::getRevisionText( $row ), "getRevisionText" );
	}

	function testCompressRevisionTextLatin1Gzip() {
		$GLOBALS['wgCompressRevisions'] = true;
		$GLOBALS['wgUseLatin1'] = true;
		$row = new stdClass;
		$row->old_text = "Wiki est l'\xe9cole superieur !";
		$row->old_flags = Revision::compressRevisionText( $row->old_text );
		$this->assertFalse( false !== strpos( $row->old_flags, 'utf-8' ),
			"Flags should not contain 'utf-8'" );
		$this->assertTrue( false !== strpos( $row->old_flags, 'gzip' ),
			"Flags should contain 'gzip'" );
		$this->assertEquals( "Wiki est l'\xe9cole superieur !",
			gzinflate( $row->old_text ), "Direct check" );
		$this->assertEquals( "Wiki est l'\xe9cole superieur !",
			Revision::getRevisionText( $row ), "getRevisionText" );
	}

}

?>