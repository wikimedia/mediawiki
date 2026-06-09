<?php
/**
 * Copyright (C) 2018 Kunal Mehta <legoktm@debian.org>
 *
 * @license GPL-2.0-or-later
 */

namespace Wikimedia\Tests;

use MediaWikiCoversValidator;
use MediaWikiTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Wikimedia\Deflate;

/**
 * @covers \Wikimedia\Deflate
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
