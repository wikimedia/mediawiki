<?php
/**
 * @covers ::wfExpandUrl
 */
class WfExpandUrlTest extends MediaWikiTestCase {
	/**
	 * @dataProvider provideExpandableUrls
	 */
	public function testWfExpandUrl( $fullUrl, $shortUrl, $defaultProto, $server, $canServer, $httpsMode, $message ) {
		// Fake $wgServer and $wgCanonicalServer
		$this->setMwGlobals( array(
			'wgServer' => $server,
			'wgCanonicalServer' => $canServer,
		) );

		// Fake $_SERVER['HTTPS'] if needed
		if ( $httpsMode ) {
			$_SERVER['HTTPS'] = 'on';
		} else {
			unset( $_SERVER['HTTPS'] );
		}

		$this->assertEquals( $fullUrl, wfExpandUrl( $shortUrl, $defaultProto ), $message );
	}

	/**
	 * Provider of URL examples for testing wfExpandUrl()
	 *
	 * @return array
	 */
	public static function provideExpandableUrls() {
		$modes = array( 'http', 'https' );
		$servers = array(
			'http' => 'http://example.com',
			'https' => 'https://example.com',
			'protocol-relative' => '//example.com'
		);
		$defaultProtos = array(
			'http' => PROTO_HTTP,
			'https' => PROTO_HTTPS,
			'protocol-relative' => PROTO_RELATIVE,
			'current' => PROTO_CURRENT,
			'canonical' => PROTO_CANONICAL
		);

		$retval = array();
		foreach ( $modes as $mode ) {
			$httpsMode = $mode == 'https';
			foreach ( $servers as $serverDesc => $server ) {
				foreach ( $modes as $canServerMode ) {
					$canServer = "$canServerMode://example2.com";
					foreach ( $defaultProtos as $protoDesc => $defaultProto ) {
						$retval[] = array(
							'http://example.com', 'http://example.com',
							$defaultProto, $server, $canServer, $httpsMode,
							"Testing fully qualified http URLs (no need to expand) ' .
							'(defaultProto: $protoDesc , wgServer: $server, wgCanonicalServer: $canServer, current request protocol: $mode )"
						);
						$retval[] = array(
							'https://example.com', 'https://example.com',
							$defaultProto, $server, $canServer, $httpsMode,
							"Testing fully qualified https URLs (no need to expand) ' .
							'(defaultProto: $protoDesc , wgServer: $server, wgCanonicalServer: $canServer, current request protocol: $mode )"
						);
						# Would be nice to support this, see fixme on wfExpandUrl()
						$retval[] = array(
							"wiki/FooBar", 'wiki/FooBar',
							$defaultProto, $server, $canServer, $httpsMode,
							"Test non-expandable relative URLs ' .
							'(defaultProto: $protoDesc , wgServer: $server, wgCanonicalServer: $canServer, current request protocol: $mode )"
						);

						// Determine expected protocol
						if ( $protoDesc == 'protocol-relative' ) {
							$p = '';
						} elseif ( $protoDesc == 'current' ) {
							$p = "$mode:";
						} elseif ( $protoDesc == 'canonical' ) {
							$p = "$canServerMode:";
						} else {
							$p = $protoDesc . ':';
						}
						// Determine expected server name
						if ( $protoDesc == 'canonical' ) {
							$srv = $canServer;
						} elseif ( $serverDesc == 'protocol-relative' ) {
							$srv = $p . $server;
						} else {
							$srv = $server;
						}

						$retval[] = array(
							"$p//wikipedia.org", '//wikipedia.org',
							$defaultProto, $server, $canServer, $httpsMode,
							"Test protocol-relative URL ' .
							'(defaultProto: $protoDesc, wgServer: $server, wgCanonicalServer: $canServer, current request protocol: $mode )"
						);
						$retval[] = array(
							"$srv/wiki/FooBar", '/wiki/FooBar',
							$defaultProto, $server, $canServer, $httpsMode,
							"Testing expanding URL beginning with / ' .
							'(defaultProto: $protoDesc , wgServer: $server, wgCanonicalServer: $canServer, current request protocol: $mode )"
						);
					}
				}
			}
		}

		return $retval;
	}
}
