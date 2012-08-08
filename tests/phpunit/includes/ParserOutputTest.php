<?php

class ParserOutputTest extends MediaWikiTestCase {

	function dataIsLinkInternal() {
		return array(
			// Different domains
			array( false, 'http://example.org', 'http://mediawiki.org' ),
			// Same domains
			array( true, 'http://example.org', 'http://example.org' ),
			// Same domain different cases
			array( true, 'http://example.org', 'http://EXAMPLE.ORG' ),
			// Paths, queries, and fragments are not relevant
			array( true, 'http://example.org', 'http://example.org/wiki/Main_Page' ),
			array( true, 'http://example.org', 'http://example.org?my=query' ),
			array( true, 'http://example.org', 'http://example.org#its-a-fragment' ),
			// Different protocols
			array( false, 'http://example.org', 'https://example.org' ),
			array( false, 'https://example.org', 'http://example.org' ),
			// Protocol relative urls match all server protocols
			array( true, 'http://example.org', '//example.org' ),
			array( true, 'https://example.org', '//example.org' ),
			array( true, '//example.org', '//example.org' ),
			// Protocol relative servers always match http and https links
			array( true, '//example.org', 'http://example.org' ),
			array( true, '//example.org', 'https://example.org' ),
			// But they don't match strange things like this
			array( false, '//example.org', 'irc://example.org' ),
			// Matching ports on server and url are a match
			array( true, 'http://example.org:8080', 'http://example.org:8080' ),
			// But a port on a url when server doesn't use one is not
			array( false, 'http://example.org', 'http://example.org:8080' ),
			// Likewise when server uses port the url must too
			array( false, 'http://example.org:8080', 'http://example.org' ),
			// On http a port 80 url is a match even when the url uses protocol relative
			array( true, 'http://example.org', 'http://example.org:80' ),
			array( true, 'http://example.org', '//example.org:80' ),
			// But https whether it's 80 or 443 is not
			array( false, 'http://example.org', 'https://example.org:80' ),
			array( false, 'http://example.org', 'https://example.org:443' ),
			// On https a port 443 url is a match even when the url uses protocol relative
			array( true, 'https://example.org', 'https://example.org:443' ),
			array( true, 'https://example.org', '//example.org:443' ),
			// But http whether it's 443 or 80 is not
			array( true, 'https://example.org', 'https://example.org:443' ),
			array( false, 'https://example.org', 'https://example.org:80' ),
			// When server is protocol relative http and https links with their default are a match
			array( true, '//example.org', 'http://example.org:80' ),
			array( true, '//example.org', 'https://example.org:443' ),
			// Protocol relatives with default ports for one are stupid but we don't reject them
			array( true, '//example.org', '//example.org:80' ),
			array( true, '//example.org', '//example.org:443' ),
		);
	}

	/**
	 * Test to make sure ParserOutput::isLinkInternal behaves properly
	 * @dataProvider dataIsLinkInternal
	 */
	function testIsLinkInternal( $shouldMatch, $server, $url ) {

		$this->assertEquals( $shouldMatch, ParserOutput::isLinkInternal( $server, $url ) );
	}
}
