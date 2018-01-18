<?php

/**
 * @group Media
 */
class IPTCTest extends MediaWikiTestCase {

	/**
	 * @covers IPTC::getCharset
	 */
	public function testRecognizeUtf8() {
		// utf-8 is the only one used in practise.
		$res = IPTC::getCharset( "\x1b%G" );
		$this->assertEquals( 'UTF-8', $res );
	}

	/**
	 * @covers IPTC::parse
	 */
	public function testIPTCParseNoCharset88591() {
		// basically IPTC for keyword with value of 0xBC which is 1/4 in iso-8859-1
		// This data doesn't specify a charset. We're supposed to guess
		// (which basically means utf-8 if valid, windows 1252 (iso 8859-1) if not)
		$iptcData = "Photoshop 3.0\08BIM\4\4\0\0\0\0\0\x06\x1c\x02\x19\x00\x01\xBC";
		$res = IPTC::parse( $iptcData );
		$this->assertEquals( [ '¼' ], $res['Keywords'] );
	}

	/**
	 * @covers IPTC::parse
	 */
	public function testIPTCParseNoCharset88591b() {
		/* This one contains a sequence that's valid iso 8859-1 but not valid utf8 */
		/* \xC3 = Ã, \xB8 = ¸  */
		$iptcData = "Photoshop 3.0\08BIM\4\4\0\0\0\0\0\x09\x1c\x02\x19\x00\x04\xC3\xC3\xC3\xB8";
		$res = IPTC::parse( $iptcData );
		$this->assertEquals( [ 'ÃÃÃ¸' ], $res['Keywords'] );
	}

	/**
	 * Same as testIPTCParseNoCharset88591b, but forcing the charset to utf-8.
	 * What should happen is the first "\xC3\xC3" should be dropped as invalid,
	 * leaving \xC3\xB8, which is ø
	 * @covers IPTC::parse
	 */
	public function testIPTCParseForcedUTFButInvalid() {
		$iptcData = "Photoshop 3.0\08BIM\4\4\0\0\0\0\0\x11\x1c\x02\x19\x00\x04\xC3\xC3\xC3\xB8"
			. "\x1c\x01\x5A\x00\x03\x1B\x25\x47";
		$res = IPTC::parse( $iptcData );
		$this->assertEquals( [ 'ø' ], $res['Keywords'] );
	}

	/**
	 * @covers IPTC::parse
	 */
	public function testIPTCParseNoCharsetUTF8() {
		$iptcData = "Photoshop 3.0\08BIM\4\4\0\0\0\0\0\x07\x1c\x02\x19\x00\x02¼";
		$res = IPTC::parse( $iptcData );
		$this->assertEquals( [ '¼' ], $res['Keywords'] );
	}

	/**
	 * Testing something that has 2 values for keyword
	 * @covers IPTC::parse
	 */
	public function testIPTCParseMulti() {
		$iptcData = /* identifier */ "Photoshop 3.0\08BIM\4\4"
			/* length */ . "\0\0\0\0\0\x0D"
			. "\x1c\x02\x19" . "\x00\x01" . "\xBC"
			. "\x1c\x02\x19" . "\x00\x02" . "\xBC\xBD";
		$res = IPTC::parse( $iptcData );
		$this->assertEquals( [ '¼', '¼½' ], $res['Keywords'] );
	}

	/**
	 * @covers IPTC::parse
	 */
	public function testIPTCParseUTF8() {
		// This has the magic "\x1c\x01\x5A\x00\x03\x1B\x25\x47" which marks content as UTF8.
		$iptcData =
			"Photoshop 3.0\08BIM\4\4\0\0\0\0\0\x0F\x1c\x02\x19\x00\x02¼\x1c\x01\x5A\x00\x03\x1B\x25\x47";
		$res = IPTC::parse( $iptcData );
		$this->assertEquals( [ '¼' ], $res['Keywords'] );
	}
}
