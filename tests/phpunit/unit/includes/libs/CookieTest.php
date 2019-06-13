<?php

/**
 * @covers Cookie
 */
class CookieTest extends \PHPUnit\Framework\TestCase {

	/**
	 * @dataProvider cookieDomains
	 * @covers Cookie::validateCookieDomain
	 */
	public function testValidateCookieDomain( $expected, $domain, $origin = null ) {
		if ( $origin ) {
			$ok = Cookie::validateCookieDomain( $domain, $origin );
			$msg = "$domain against origin $origin";
		} else {
			$ok = Cookie::validateCookieDomain( $domain );
			$msg = "$domain";
		}
		$this->assertEquals( $expected, $ok, $msg );
	}

	public static function cookieDomains() {
		return [
			[ false, "org" ],
			[ false, ".org" ],
			[ true, "wikipedia.org" ],
			[ true, ".wikipedia.org" ],
			[ false, "co.uk" ],
			[ false, ".co.uk" ],
			[ false, "gov.uk" ],
			[ false, ".gov.uk" ],
			[ true, "supermarket.uk" ],
			[ false, "uk" ],
			[ false, ".uk" ],
			[ false, "127.0.0." ],
			[ false, "127." ],
			[ false, "127.0.0.1." ],
			[ true, "127.0.0.1" ],
			[ false, "333.0.0.1" ],
			[ true, "example.com" ],
			[ false, "example.com." ],
			[ true, ".example.com" ],

			[ true, ".example.com", "www.example.com" ],
			[ false, "example.com", "www.example.com" ],
			[ true, "127.0.0.1", "127.0.0.1" ],
			[ false, "127.0.0.1", "localhost" ],
		];
	}

}
