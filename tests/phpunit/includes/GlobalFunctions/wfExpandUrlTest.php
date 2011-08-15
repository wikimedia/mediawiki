<?php
/*
 * Unit tests for wfExpandUrl()
 */

class wfExpandUrl extends MediaWikiTestCase {
	/** @dataProvider provideExpandableUrls */
	public function testWfExpandUrl( $fullUrl, $shortUrl, $defaultProto, $server, $httpsMode, $message ) {
		// Fake $wgServer
		global $wgServer;
		$oldServer = $wgServer;
		$wgServer = $server;
		
		// Fake $_SERVER['HTTPS'] if needed
		if ( $httpsMode ) {
			$_SERVER['HTTPS'] = 'on';
		} else {
			unset( $_SERVER['HTTPS'] );
		}
		
		$this->assertEquals( $fullUrl, wfExpandUrl( $shortUrl, $defaultProto ), $message );
		
		// Restore $wgServer
		$wgServer = $oldServer;
	}

	/**
	 * Provider of URL examples for testing wfExpandUrl()
	 *
	 * @return array
	 */
	public function provideExpandableUrls() {
		$modes = array( 'http', 'https' );
		$servers = array( 'http' => 'http://example.com', 'https' => 'https://example.com', 'protocol-relative' => '//example.com' );
		$defaultProtos = array( 'http' => PROTO_HTTP, 'https' => PROTO_HTTPS, 'protocol-relative' => PROTO_RELATIVE, 'current' => PROTO_CURRENT );
		
		$retval = array();
		foreach ( $modes as $mode ) {
			$httpsMode = $mode == 'https';
			foreach ( $servers as $serverDesc => $server ) {
				foreach ( $defaultProtos as $protoDesc => $defaultProto ) {
					$retval[] = array( 'http://example.com', 'http://example.com', $defaultProto, $server, $httpsMode, "Testing fully qualified http URLs (no need to expand) (defaultProto: $protoDesc , wgServer: $server, current request protocol: $mode )" );
					$retval[] = array( 'https://example.com', 'https://example.com', $defaultProto, $server, $httpsMode, "Testing fully qualified https URLs (no need to expand) (defaultProto: $protoDesc , wgServer: $server, current request protocol: $mode )" );
					# Would be nice to support this, see fixme on wfExpandUrl()
					$retval[] = array( "wiki/FooBar", 'wiki/FooBar', $defaultProto, $server, $httpsMode, "Test non-expandable relative URLs (defaultProto: $protoDesc , wgServer: $server, current request protocol: $mode )" );
					
					// Determine expected protocol
					$p = $protoDesc . ':'; // default case
					if ( $protoDesc == 'protocol-relative' ) {
						$p = '';
					} else if ( $protoDesc == 'current' ) {
						$p = "$mode:";
					} else {
						$p = $protoDesc . ':';
					}
					// Determine expected server name
					$srv = $serverDesc == 'protocol-relative' ? $p . $server : $server;
					
					$retval[] = array( "$p//wikipedia.org", '//wikipedia.org', $defaultProto, $server, $httpsMode, "Test protocol-relative URL (defaultProto: $protoDesc, wgServer: $server, current request protocol: $mode )" );
					$retval[] = array( "$srv/wiki/FooBar", '/wiki/FooBar', $defaultProto, $server, $httpsMode, "Testing expanding URL beginning with / (defaultProto: $protoDesc , wgServer: $server, current request protocol: $mode )" );
				}
			}
		}
		return $retval;
	}
}
