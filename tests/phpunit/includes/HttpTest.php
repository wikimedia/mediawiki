<?php
/**
 * @group Broken
 */
class HttpTest extends MediaWikiTestCase {
	/**
	 * @dataProvider cookieDomains
	 */
	function testValidateCookieDomain( $expected, $domain, $origin = null ) {
		if ( $origin ) {
			$ok = Cookie::validateCookieDomain( $domain, $origin );
			$msg = "$domain against origin $origin";
		} else {
			$ok = Cookie::validateCookieDomain( $domain );
			$msg = "$domain";
		}
		$this->assertEquals( $expected, $ok, $msg );
	}

	function cookieDomains() {
		return array(
			array( false, "org"),
			array( false, ".org"),
			array( true, "wikipedia.org"),
			array( true, ".wikipedia.org"),
			array( false, "co.uk" ),
			array( false, ".co.uk" ),
			array( false, "gov.uk" ),
			array( false, ".gov.uk" ),
			array( true, "supermarket.uk" ),
			array( false, "uk" ),
			array( false, ".uk" ),
			array( false, "127.0.0." ),
			array( false, "127." ),
			array( false, "127.0.0.1." ),
			array( true, "127.0.0.1" ),
			array( false, "333.0.0.1" ),
			array( true, "example.com" ),
			array( false, "example.com." ),
			array( true, ".example.com" ),

			array( true, ".example.com", "www.example.com" ),
			array( false, "example.com", "www.example.com" ),
			array( true, "127.0.0.1", "127.0.0.1" ),
			array( false, "127.0.0.1", "localhost" ),
		);
	}

	/**
	 * Test Http::isValidURI()
	 * @bug 27854 : Http::isValidURI is too lax
	 * @dataProvider provideURI
	 */
	function testIsValidUri( $expect, $URI, $message = '' ) {
		$this->assertEquals(
			$expect,
			(bool) Http::isValidURI( $URI ),
			$message
		);
	}

	/**
	 * Feeds URI to test a long regular expression in Http::isValidURI
	 */
	function provideURI() {
		/** Format: 'boolean expectation', 'URI to test', 'Optional message' */
		return array(
			array( false, '¿non sens before!! http://a', 'Allow anything before URI' ),

			# (http|https) - only two schemes allowed
			array( true,  'http://www.example.org/' ),
			array( true,  'https://www.example.org/' ),
			array( true,  'http://www.example.org', 'URI without directory' ),
			array( true,  'http://a', 'Short name' ),
			array( true, 'http://étoile', 'Allow UTF-8 in hostname' ),  # 'étoile' is french for 'star'
			array( false, '\\host\directory', 'CIFS share' ),
			array( false, 'gopher://host/dir', 'Reject gopher scheme' ),
			array( false, 'telnet://host', 'Reject telnet scheme' ),

			# :\/\/ - double slashes
			array( false,  'http//example.org', 'Reject missing colon in protocol' ),
			array( false,  'http:/example.org', 'Reject missing slash in protocol' ),
			array( false,  'http:example.org', 'Must have two slashes' ),
			# Following fail since hostname can be made of anything
			array( false,  'http:///example.org', 'Must have exactly two slashes, not three' ),

			# (\w+:{0,1}\w*@)? - optional user:pass
			array( true,  'http://user@host', 'Username provided' ),
			array( true,  'http://user:@host', 'Username provided, no password' ),
			array( true,  'http://user:pass@host', 'Username and password provided' ),

			# (\S+) - host part is made of anything not whitespaces
			array( false, 'http://!"èèè¿¿¿~~\'', 'hostname is made of any non whitespace' ),
			array( false, 'http://exam:ple.org/', 'hostname can not use colons!' ),

			# (:[0-9]+)? - port number
			array( true, 'http://example.org:80/' ),
			array( true, 'https://example.org:80/' ),
			array( true, 'http://example.org:443/' ),
			array( true, 'https://example.org:443/' ),

			# Part after the hostname is / or / with something else
			array( true, 'http://example/#' ),
			array( true, 'http://example/!' ),
			array( true, 'http://example/:' ),
			array( true, 'http://example/.' ),
			array( true, 'http://example/?' ),
			array( true, 'http://example/+' ),
			array( true, 'http://example/=' ),
			array( true, 'http://example/&' ),
			array( true, 'http://example/%' ),
			array( true, 'http://example/@' ),
			array( true, 'http://example/-' ),
			array( true, 'http://example//' ),
			array( true, 'http://example/&' ),

			# Fragment
			array( true, 'http://exam#ple.org', ),  # This one is valid, really!
			array( true, 'http://example.org:80#anchor' ),
			array( true, 'http://example.org/?id#anchor' ),
			array( true, 'http://example.org/?#anchor' ),

			array( false, 'http://a ¿non !!sens after', 'Allow anything after URI' ),
		);
	}

}
