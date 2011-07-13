<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class wfExpandUrl extends MediaWikiTestCase {
	/** @dataProvider provideExpandableUrls */
	public function testWfExpandUrl( $fullUrl, $shortUrl, $message ) {
		$this->assertEquals( $fullUrl, wfExpandUrl( $shortUrl ), $message );
	}

	/**
	 * Provider of URL examples for testing wfExpandUrl()
	 */
	public function provideExpandableUrls() {
		global $wgServer;
		return array(
			array( "$wgServer/wiki/FooBar", '/wiki/FooBar', 'Testing expanding URL beginning with /' ),
			array( 'http://example.com', 'http://example.com', 'Testing fully qualified http URLs (no need to expand)' ),
			array( 'https://example.com', 'https://example.com', 'Testing fully qualified https URLs (no need to expand)' ),
			# Would be nice to support this, see fixme on wfExpandUrl()
			array( "wiki/FooBar", 'wiki/FooBar', "Test non-expandable relative URLs" ),
		);
	}
}
