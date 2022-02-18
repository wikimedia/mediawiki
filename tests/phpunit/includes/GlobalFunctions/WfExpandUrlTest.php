<?php
/**
 * @group GlobalFunctions
 * @covers ::wfExpandUrl
 */
class WfExpandUrlTest extends MediaWikiIntegrationTestCase {
	/**
	 * @dataProvider provideExpandableUrls
	 */
	public function testWfExpandUrl( string $input, array $conf,
		string $currentProto, $defaultProto, string $expected
	) {
		$this->setMwGlobals( $conf );
		$this->setRequest( new FauxRequest( [], false, null, $currentProto ) );
		$this->assertEquals( $expected, wfExpandUrl( $input, $defaultProto ) );
	}

	public static function provideExpandableUrls() {
		$modes = [ 'http', 'https' ];
		$servers = [
			'http://example.com',
			'https://example.com',
			'//example.com',
		];
		$defaultProtos = [
			'http' => PROTO_HTTP,
			'https' => PROTO_HTTPS,
			'protocol-relative' => PROTO_RELATIVE,
			'current' => PROTO_CURRENT,
			'canonical' => PROTO_CANONICAL,
		];

		foreach ( $modes as $currentProto ) {
			foreach ( $servers as $server ) {
				foreach ( $modes as $canServerMode ) {
					$canServer = "$canServerMode://example2.com";
					$conf = [
						'wgServer' => $server,
						'wgCanonicalServer' => $canServer,
						'wgHttpsPort' => 443,
					];
					foreach ( $defaultProtos as $protoDesc => $defaultProto ) {
						$case = "current: $currentProto, default: $protoDesc, server: $server, canonical: $canServer";
						yield "No-op fully-qualified http URL ($case)" => [
							'http://example.com',
							$conf, $currentProto, $defaultProto,
							'http://example.com',
						];
						yield "No-op fully-qualified https URL ($case)" => [
							'https://example.com',
							$conf, $currentProto, $defaultProto,
							'https://example.com',
						];
						yield "No-op rootless path-only URL ($case)" => [
							"wiki/FooBar",
							$conf, $currentProto, $defaultProto,
							'wiki/FooBar',
						];

						// Determine expected protocol
						if ( $protoDesc === 'protocol-relative' ) {
							$p = '';
						} elseif ( $protoDesc === 'current' ) {
							$p = "$currentProto:";
						} elseif ( $protoDesc === 'canonical' ) {
							$p = "$canServerMode:";
						} else {
							$p = $protoDesc . ':';
						}
						yield "Expand protocol-relative URL ($case)" => [
							'//wikipedia.org',
							$conf, $currentProto, $defaultProto,
							"$p//wikipedia.org",
						];

						// Determine expected server name
						if ( $protoDesc === 'canonical' ) {
							$srv = $canServer;
						} elseif ( $server === '//example.com' ) {
							$srv = $p . $server;
						} else {
							$srv = $server;
						}
						yield "Expand path that starts with slash ($case)" => [
							'/wiki/FooBar',
							$conf, $currentProto, $defaultProto,
							"$srv/wiki/FooBar",
						];
					}
				}
			}
		}

		$confRel111 = [
			'wgServer' => '//wiki.example.com',
			'wgCanonicalServer' => 'http://wiki.example.com',
			'wgHttpsPort' => 111,
		];
		yield "No-op foreign URL, ignore custom port config" => [
			'https://foreign.example.com/foo',
			$confRel111, 'https', PROTO_HTTPS,
			'https://foreign.example.com/foo',
		];
		yield "No-op foreign URL, preserve existing port" => [
			'https://foreign.example.com:222/foo',
			$confRel111, 'https', PROTO_HTTPS,
			'https://foreign.example.com:222/foo',
		];
		yield "Expand path with custom HTTPS port" => [
			'/foo',
			$confRel111, 'https', PROTO_HTTPS,
			'https://wiki.example.com:111/foo',
		];
	}
}
