<?php

/**
 * @group Media
 */
class IPTCTest extends \MediaWikiUnitTestCase {

	/**
	 * @covers \IPTC::getCharset
	 */
	public function testRecognizeUtf8() {
		// utf-8 is the only one used in practise.
		$res = IPTC::getCharset( "\x1b%G" );
		$this->assertEquals( 'UTF-8', $res );
	}

	public static function provideParse() {
		// $rawData, $expectedKeywords

		// basically IPTC for keyword with value of 0xBC which is 1/4 in iso-8859-1
		// This data doesn't specify a charset. We're supposed to guess
		// (which basically means utf-8 if valid, windows 1252 (iso 8859-1) if not)
		yield 'No charset 88591' => [
			"Photoshop 3.0\08BIM\4\4\0\0\0\0\0\x06\x1c\x02\x19\x00\x01\xBC",
			[ '¼' ]
		];

		/* This one contains a sequence that's valid iso 8859-1 but not valid utf8 */
		/* \xC3 = Ã, \xB8 = ¸ */
		yield 'No charset 88591b' => [
			"Photoshop 3.0\08BIM\4\4\0\0\0\0\0\x09\x1c\x02\x19\x00\x04\xC3\xC3\xC3\xB8",
			[ 'ÃÃÃ¸' ]
		];

		// Same as above, but forcing the charset to utf-8. What should happen is the
		// first "\xC3\xC3" should be dropped as invalid, leaving \xC3\xB8, which is ø
		yield 'Forced UTF but invalid' => [
			"Photoshop 3.0\08BIM\4\4\0\0\0\0\0\x11\x1c\x02\x19\x00\x04\xC3\xC3\xC3\xB8"
				. "\x1c\x01\x5A\x00\x03\x1B\x25\x47",
			[ 'ø' ]
		];

		yield 'No charset UTF8' => [
			"Photoshop 3.0\08BIM\4\4\0\0\0\0\0\x07\x1c\x02\x19\x00\x02¼",
			[ '¼' ]
		];

		// Testing something that has 2 values for keyword
		yield 'Multiple keywords (2)' => [
			"Photoshop 3.0\08BIM\4\4" . /* identifier */
				"\0\0\0\0\0\x0D" . /* length */
				"\x1c\x02\x19\x00\x01\xBC" .
				"\x1c\x02\x19\x00\x02\xBC\xBD",
			[ '¼', '¼½' ]
		];

		// This has the magic "\x1c\x01\x5A\x00\x03\x1B\x25\x47" which marks content as UTF8.
		yield 'UTF8' => [
			"Photoshop 3.0\08BIM\4\4\0\0\0\0\0\x0F\x1c\x02\x19\x00\x02¼\x1c\x01\x5A\x00\x03\x1B\x25\x47",
			[ '¼' ]
		];
	}

	/**
	 * @covers \IPTC::parse
	 * @dataProvider provideParse
	 */
	public function testIPTCParseUTF8( $rawData, $expectedKeywords ) {
		$res = IPTC::parse( $rawData );
		$this->assertEquals( $expectedKeywords, $res['Keywords'] );
	}
}
