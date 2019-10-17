<?php

namespace MediaWiki\Tests\Rest\HeaderParser;

use MediaWiki\Rest\HeaderParser\HttpDate;

/**
 * @covers \MediaWiki\Rest\HeaderParser\HttpDate
 */
class HttpDateTest extends \MediaWikiUnitTestCase {
	public static function provideParse() {
		return [
			'RFC 7231 example 1' => [
				'Sun, 06 Nov 1994 08:49:37 GMT',
				784111777
			],
			'RFC 7231 example 2' => [
				'Sunday, 06-Nov-94 08:49:37 GMT',
				784111777
			],
			'RFC 7231 example 3' => [
				'Sun Nov  6 08:49:37 1994',
				784111777
			],
			'asctime whitespace nitpick' => [
				'Sun Nov 6 08:49:37 1994',
				null
			],
			'PHP "r" format' => [
				'Sun, 06 Nov 1994 08:49:37 +0000',
				null
			],
			'RFC 7231 example 1 with trail' => [
				'Sun, 06 Nov 1994 08:49:37 GMT.',
				null
			],
			'RFC 7231 example 2 with trail' => [
				'Sunday, 06-Nov-94 08:49:37 GMT.',
				null
			],
			'RFC 7231 example 3 with trail' => [
				'Sun Nov  6 08:49:37 1994.',
				null
			],
		];
	}

	/**
	 * @dataProvider provideParse
	 * @param string $input
	 * @param string|null $expected
	 */
	public function testParse( $input, $expected ) {
		$result = HttpDate::parse( $input );
		$this->assertSame( $expected, $result );
	}

	public static function provideFormatRoundtrip() {
		for ( $ts = 1000000000; $ts < 2000000000; $ts += 100000000 ) {
			yield [ $ts ];
		}
	}

	/**
	 * @dataProvider provideFormatRoundtrip
	 */
	public function testFormatRoundtrip( $ts ) {
		$this->assertSame( $ts, HttpDate::parse( HttpDate::format( $ts ) ) );
	}
}
