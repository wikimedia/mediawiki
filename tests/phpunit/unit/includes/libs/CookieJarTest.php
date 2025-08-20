<?php

namespace Wikimedia\Tests;

use CookieJar;
use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CookieJar
 */
class CookieJarTest extends TestCase {
	use MediaWikiCoversValidator;

	/**
	 * @dataProvider provideCookieResponseHeaders
	 */
	public function testParseCookieResponseHeader( string $cookie, string $expected ) {
		$jar = new CookieJar();
		$domain = '.example.com';
		$jar->parseCookieResponseHeader( $cookie, $domain );
		$this->assertSame( $expected, $jar->serializeToHttpRequest( '/', $domain ) );
	}

	public static function provideCookieResponseHeaders() {
		return [
			[ '', '=' ],
			[ 'a=', 'a=' ],
			[ '=b', '=b' ],
			[ 'a=b;c=d', 'a=b' ],
			[ 'a=b;expires=1999-01-01', '' ],
			[ 'a=b;expires=9999', 'a=b' ],
			[ 'a=b ; path=/different/', '' ],
			[ 'a=b;domain=.different.com', '' ],
		];
	}

}
