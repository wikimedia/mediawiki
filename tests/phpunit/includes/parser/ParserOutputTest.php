<?php

class ParserOutputTest extends MediaWikiTestCase {

	function dataIsLinkInternal() {
		return array(
			// Different domains
			array( false, 'http://example.org', 'http://mediawiki.org' ),
			// Same domains
			array( true, 'http://example.org', 'http://example.org' ),
			array( true, 'https://example.org', 'https://example.org' ),
			array( true, '//example.org', '//example.org' ),
			// Same domain different cases
			array( true, 'http://example.org', 'http://EXAMPLE.ORG' ),
			// Paths, queries, and fragments are not relevant
			array( true, 'http://example.org', 'http://example.org/wiki/Main_Page' ),
			array( true, 'http://example.org', 'http://example.org?my=query' ),
			array( true, 'http://example.org', 'http://example.org#its-a-fragment' ),
			// Different protocols
			array( false, 'http://example.org', 'https://example.org' ),
			array( false, 'https://example.org', 'http://example.org' ),
			// Protocol relative servers always match http and https links
			array( true, '//example.org', 'http://example.org' ),
			array( true, '//example.org', 'https://example.org' ),
			// But they don't match strange things like this
			array( false, '//example.org', 'irc://example.org' ),
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
