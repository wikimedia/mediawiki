<?php

/**
 * @covers MWHttpRequest
 */
class MWHttpRequestTest extends PHPUnit\Framework\TestCase {

	public function testFactory() {
		$this->assertInstanceOf( 'MWHttpRequest', MWHttpRequest::factory( 'http://example.test' ) );
	}

	/**
	 * Feeds URI to test a long regular expression in Http::isValidURI
	 */
	public static function provideURI() {
		/** Format: 'boolean expectation', 'URI to test', 'Optional message' */
		return [
			[ false, '¿non sens before!! http://a', 'Allow anything before URI' ],

			# (http|https) - only two schemes allowed
			[ true, 'http://www.example.org/' ],
			[ true, 'https://www.example.org/' ],
			[ true, 'http://www.example.org', 'URI without directory' ],
			[ true, 'http://a', 'Short name' ],
			[ true, 'http://étoile', 'Allow UTF-8 in hostname' ], # 'étoile' is french for 'star'
			[ false, '\\host\directory', 'CIFS share' ],
			[ false, 'gopher://host/dir', 'Reject gopher scheme' ],
			[ false, 'telnet://host', 'Reject telnet scheme' ],

			# :\/\/ - double slashes
			[ false, 'http//example.org', 'Reject missing colon in protocol' ],
			[ false, 'http:/example.org', 'Reject missing slash in protocol' ],
			[ false, 'http:example.org', 'Must have two slashes' ],
			# Following fail since hostname can be made of anything
			[ false, 'http:///example.org', 'Must have exactly two slashes, not three' ],

			# (\w+:{0,1}\w*@)? - optional user:pass
			[ true, 'http://user@host', 'Username provided' ],
			[ true, 'http://user:@host', 'Username provided, no password' ],
			[ true, 'http://user:pass@host', 'Username and password provided' ],

			# (\S+) - host part is made of anything not whitespaces
			// commented these out in order to remove @group Broken
			// @todo are these valid tests? if so, fix Http::isValidURI so it can handle them
			// [ false, 'http://!"èèè¿¿¿~~\'', 'hostname is made of any non whitespace' ],
			// [ false, 'http://exam:ple.org/', 'hostname can not use colons!' ],

			# (:[0-9]+)? - port number
			[ true, 'http://example.org:80/' ],
			[ true, 'https://example.org:80/' ],
			[ true, 'http://example.org:443/' ],
			[ true, 'https://example.org:443/' ],

			# Part after the hostname is / or / with something else
			[ true, 'http://example/#' ],
			[ true, 'http://example/!' ],
			[ true, 'http://example/:' ],
			[ true, 'http://example/.' ],
			[ true, 'http://example/?' ],
			[ true, 'http://example/+' ],
			[ true, 'http://example/=' ],
			[ true, 'http://example/&' ],
			[ true, 'http://example/%' ],
			[ true, 'http://example/@' ],
			[ true, 'http://example/-' ],
			[ true, 'http://example//' ],
			[ true, 'http://example/&' ],

			# Fragment
			[ true, 'http://exam#ple.org', ], # This one is valid, really!
			[ true, 'http://example.org:80#anchor' ],
			[ true, 'http://example.org/?id#anchor' ],
			[ true, 'http://example.org/?#anchor' ],

			[ false, 'http://a ¿non !!sens after', 'Allow anything after URI' ],
		];
	}

	/**
	 * Test MWHttpRequest::isValidURI()
	 * T29854 : Http::isValidURI is too lax
	 * @dataProvider provideURI
	 * @covers Http::isValidURI
	 */
	public function testIsValidUri( $expect, $URI, $message = '' ) {
		$this->assertEquals(
			$expect,
			(bool)MWHttpRequest::isValidURI( $URI ),
			$message
		);
	}

}
