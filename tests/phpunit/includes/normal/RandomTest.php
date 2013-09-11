<?php
/**
 * Test feeds random 16-byte strings to both the pure PHP and ICU-based
 * UtfNormal::cleanUp() code paths, and checks to see if there's a
 * difference. Will run forever until it finds one or you kill it.
 *
 * Copyright (C) 2004 Brion Vibber <brion@pobox.com>
 * http://www.mediawiki.org/
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup UtfNormal
 */

class RandomTest extends MediaWikiTestCase {
	protected function setUp() {
		parent::setUp();
		if ( !extension_loaded( 'php_utfnormal' ) ) {
			$this->markTestSkipped( 'These tests require utfnormal extension' );
		}
	}

	private function randomString( $length = 16, $nullOk = true, $ascii = false ) {
		$out = '';
		for( $i = 0; $i < $length; $i++ ) {
			$out .= chr( mt_rand( $nullOk ? 0 : 1, $ascii ? 127 : 255 ) );
		}
		return $out;
	}

	/* Duplicate of the cleanUp() path for ICU usage */
	private function donorm( $str ) {
		# We exclude a few chars that ICU would not.
		$str = preg_replace( '/[\x00-\x08\x0b\x0c\x0e-\x1f]/', UTF8_REPLACEMENT, $str );
		$str = str_replace( UTF8_FFFE, UTF8_REPLACEMENT, $str );
		$str = str_replace( UTF8_FFFF, UTF8_REPLACEMENT, $str );

		# UnicodeString constructor fails if the string ends with a head byte.
		# Add a junk char at the end, we'll strip it off
		return rtrim( utf8_normalize( $str . "\x01", UtfNormal::UNORM_NFC ), "\x01" );
	}

	/**
	 * @dataProvider provideRandomStrings
	 */
	public function testRandom( $str ) {
		$this->assertEquals(
			$this->donorm( $str ),
			UtfNormal::cleanUp( $str ),
			"Random input '$str'"
		);
	}

	public function provideRandomStrings() {
		$strings = array();
		for( $i = 0; $i < 100; $i++ ) {
			$strings[] = array( $this->randomString() );
		}
		return $strings;
	}
}
