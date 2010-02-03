<?php

class GlobalTest extends PHPUnit_Framework_TestCase {
	function setUp() {
		global $wgReadOnlyFile;
		$this->originals['wgReadOnlyFile'] = $wgReadOnlyFile;
		$wgReadOnlyFile = tempnam(wfTempDir(), "mwtest_readonly");
		unlink( $wgReadOnlyFile );
	}
	
	function tearDown() {
		global $wgReadOnlyFile;
		if( file_exists( $wgReadOnlyFile ) ) {
			unlink( $wgReadOnlyFile );
		}
		$wgReadOnlyFile = $this->originals['wgReadOnlyFile'];
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

	function testReadOnlyEmpty() {
		global $wgReadOnly;
		$wgReadOnly = null;
		
		$this->assertFalse( wfReadOnly() );
		$this->assertFalse( wfReadOnly() );
	}

	function testReadOnlySet() {
		global $wgReadOnly, $wgReadOnlyFile;
		
		$f = fopen( $wgReadOnlyFile, "wt" );
		fwrite( $f, 'Message' );
		fclose( $f );
		$wgReadOnly = null;
		
		$this->assertTrue( wfReadOnly() );
		$this->assertTrue( wfReadOnly() );

		unlink( $wgReadOnlyFile );
		$wgReadOnly = null;
		
		$this->assertFalse( wfReadOnly() );
		$this->assertFalse( wfReadOnly() );
	}

	function testQuotedPrintable() {
		$this->assertEquals(
			"=?UTF-8?Q?=C4=88u=20legebla=3F?=",
			wfQuotedPrintable( "\xc4\x88u legebla?", "UTF-8" ) );
	}

	function testTime() {
		$start = wfTime();
		$this->assertType( 'float', $start );
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

	function testMimeTypeMatch() {
		$this->assertEquals(
			'text/html',
			mimeTypeMatch( 'text/html',
				array( 'application/xhtml+xml' => 1.0,
				       'text/html'             => 0.7,
				       'text/plain'            => 0.3 ) ) );
		$this->assertEquals(
			'text/*',
			mimeTypeMatch( 'text/html',
				array( 'image/*' => 1.0,
				       'text/*'  => 0.5 ) ) );
		$this->assertEquals(
			'*/*',
			mimeTypeMatch( 'text/html',
				array( '*/*' => 1.0 ) ) );
		$this->assertNull(
			mimeTypeMatch( 'text/html',
				array( 'image/png'     => 1.0,
				       'image/svg+xml' => 0.5 ) ) );
	}

	function testNegotiateType() {
		$this->assertEquals(
			'text/html',
			wfNegotiateType(
				array( 'application/xhtml+xml' => 1.0,
				       'text/html'             => 0.7,
				       'text/plain'            => 0.5,
				       'text/*'                => 0.2 ),
				array( 'text/html'             => 1.0 ) ) );
		$this->assertEquals(
			'application/xhtml+xml',
			wfNegotiateType(
				array( 'application/xhtml+xml' => 1.0,
				       'text/html'             => 0.7,
				       'text/plain'            => 0.5,
				       'text/*'                => 0.2 ),
				array( 'application/xhtml+xml' => 1.0,
				       'text/html'             => 0.5 ) ) );
		$this->assertEquals(
			'text/html',
			wfNegotiateType(
				array( 'text/html'             => 1.0,
				       'text/plain'            => 0.5,
				       'text/*'                => 0.5,
				       'application/xhtml+xml' => 0.2 ),
				array( 'application/xhtml+xml' => 1.0,
				       'text/html'             => 0.5 ) ) );
		$this->assertEquals(
			'text/html',
			wfNegotiateType(
				array( 'text/*'                => 1.0,
				       'image/*'               => 0.7,
				       '*/*'                   => 0.3 ),
				array( 'application/xhtml+xml' => 1.0,
				       'text/html'             => 0.5 ) ) );
		$this->assertNull(
			wfNegotiateType(
				array( 'text/*'                => 1.0 ),
				array( 'application/xhtml+xml' => 1.0 ) ) );
	}

	function testTimestamp() {
		$t = gmmktime( 12, 34, 56, 1, 15, 2001 );
		$this->assertEquals(
			'20010115123456',
			wfTimestamp( TS_MW, $t ),
			'TS_UNIX to TS_MW' );
		$this->assertEquals(
			979562096,
			wfTimestamp( TS_UNIX, $t ),
			'TS_UNIX to TS_UNIX' );
		$this->assertEquals(
			'2001-01-15 12:34:56',
			wfTimestamp( TS_DB, $t ),
			'TS_UNIX to TS_DB' );

		$this->assertEquals(
			'20010115123456',
			wfTimestamp( TS_MW, '20010115123456' ),
			'TS_MW to TS_MW' );
		$this->assertEquals(
			979562096,
			wfTimestamp( TS_UNIX, '20010115123456' ),
			'TS_MW to TS_UNIX' );
		$this->assertEquals(
			'2001-01-15 12:34:56',
			wfTimestamp( TS_DB, '20010115123456' ),
			'TS_MW to TS_DB' );

		$this->assertEquals(
			'20010115123456',
			wfTimestamp( TS_MW, '2001-01-15 12:34:56' ),
			'TS_DB to TS_MW' );
		$this->assertEquals(
			979562096,
			wfTimestamp( TS_UNIX, '2001-01-15 12:34:56' ),
			'TS_DB to TS_UNIX' );
		$this->assertEquals(
			'2001-01-15 12:34:56',
			wfTimestamp( TS_DB, '2001-01-15 12:34:56' ),
			'TS_DB to TS_DB' );
	}
	
	function testBasename() {
		$sets = array(
			'' => '',
			'/' => '',
			'\\' => '',
			'//' => '',
			'\\\\' => '',
			'a' => 'a',
			'aaaa' => 'aaaa',
			'/a' => 'a',
			'\\a' => 'a',
			'/aaaa' => 'aaaa',
			'\\aaaa' => 'aaaa',
			'/aaaa/' => 'aaaa',
			'\\aaaa\\' => 'aaaa',
			'\\aaaa\\' => 'aaaa',
			'/mnt/upload3/wikipedia/en/thumb/8/8b/Zork_Grand_Inquisitor_box_cover.jpg/93px-Zork_Grand_Inquisitor_box_cover.jpg' => '93px-Zork_Grand_Inquisitor_box_cover.jpg',
			'C:\\Progra~1\\Wikime~1\\Wikipe~1\\VIEWER.EXE' => 'VIEWER.EXE',
			'Östergötland_coat_of_arms.png' => 'Östergötland_coat_of_arms.png',
			);
		foreach( $sets as $from => $to ) {
			$this->assertEquals( $to, wfBaseName( $from ),
				"wfBaseName('$from') => '$to'");
		}
	}

	/* TODO: many more! */
}


