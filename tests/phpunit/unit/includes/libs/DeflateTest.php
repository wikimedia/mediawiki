<?php
/**
 * Copyright (C) 2018 Kunal Mehta <legoktm@debian.org>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 */

namespace Wikimedia\Tests;

use Deflate;
use MediaWikiCoversValidator;
use MediaWikiTestCaseTrait;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Deflate
 */
class DeflateTest extends TestCase {
	use MediaWikiCoversValidator;
	use MediaWikiTestCaseTrait;

	public static function provideIsDeflated() {
		return [
			// mw.deflate('foobar')
			[ 'rawdeflate,S8vPT0osAgA=', true ],
			[ 'abcdefghijklmnopqrstuvwxyz', false ],
		];
	}

	/**
	 * @dataProvider provideIsDeflated
	 */
	public function testIsDeflated( $data, $expected ) {
		$actual = Deflate::isDeflated( $data );
		$this->assertSame( $expected, $actual );
	}

	public static function provideInflate() {
		return [
			// foobar (all agents)
			[ 'rawdeflate,S8vPT0osAgA=', true, 'foobar' ],
			// Unicode (Chrome)
			[ 'rawdeflate,ASQA2//ihLPwnZKy4pml8J2TivCdk4PwnZK+8J2SuOKEtPCdkrnihK8=', true, 'ℳ𝒲♥𝓊𝓃𝒾𝒸ℴ𝒹ℯ' ],
			// Unicode (pako & Firefox)
			[ 'rawdeflate,e9Sy+cPcSZsezVz6Ye7kLiBuBnL3AfGORy1bgNTORy3rAQ==', true, 'ℳ𝒲♥𝓊𝓃𝒾𝒸ℴ𝒹ℯ' ],
			// Non BMP unicode (Chrome)
			[ 'rawdeflate,FcbBEUMAAADB1gmHRHDP/LR4JWTsa7t/r2RIxuT5lMwJyZKsyZa8k0+yJ9/kSM7k+gM=', true, '😂𐅀𐅁𐅂𐅃𐅄𐅅𐅆𐅇𐅈𐅉𐅊𐅋𐅌𐅍𐅎𐅏' ],
			// Non BMP unicode (pako & Firefox)
			[ 'rawdeflate,Fca3EQAgDACx1Ukmp5KOFT0CT6E76T1OtxhY/HsECCISMgoqGjoGJtYD', true, '😂𐅀𐅁𐅂𐅃𐅄𐅅𐅆𐅇𐅈𐅉𐅊𐅋𐅌𐅍𐅎𐅏' ],

			// Fails base64_decode
			[ 'rawdeflate,🌻', false, 'deflate-invaliddeflate' ],
			// Fails gzinflate
			[ 'rawdeflate,S8vPT0dfdAgB=', false, 'deflate-invaliddeflate' ],
		];
	}

	/**
	 * @dataProvider provideInflate
	 */
	public function testInflate( $data, $ok, $value ) {
		$actual = Deflate::inflate( $data );
		if ( $ok ) {
			$this->assertStatusOK( $actual );
			$this->assertStatusValue( $value, $actual );
		} else {
			$this->assertStatusError( $value, $actual );
		}
	}
}
