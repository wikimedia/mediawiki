<?php
/**
 * @group GlobalFunctions
 * @covers ::wfAssembleUrl
 */
class WfAssembleUrlTest extends MediaWikiTestCase {
	/**
	 * @dataProvider provideURLParts
	 */
	public function testWfAssembleUrl( $parts, $output ) {
		$partsDump = print_r( $parts, true );
		$this->assertEquals(
			$output,
			wfAssembleUrl( $parts ),
			"Testing $partsDump assembles to $output"
		);
	}

	/**
	 * Provider of URL parts for testing wfAssembleUrl()
	 *
	 * @return array
	 */
	public static function provideURLParts() {
		$schemes = [
			'' => [],
			'//' => [
				'delimiter' => '//',
			],
			'http://' => [
				'scheme' => 'http',
				'delimiter' => '://',
			],
		];

		$hosts = [
			'' => [],
			'example.com' => [
				'host' => 'example.com',
			],
			'example.com:123' => [
				'host' => 'example.com',
				'port' => 123,
			],
			'id@example.com' => [
				'user' => 'id',
				'host' => 'example.com',
			],
			'id@example.com:123' => [
				'user' => 'id',
				'host' => 'example.com',
				'port' => 123,
			],
			'id:key@example.com' => [
				'user' => 'id',
				'pass' => 'key',
				'host' => 'example.com',
			],
			'id:key@example.com:123' => [
				'user' => 'id',
				'pass' => 'key',
				'host' => 'example.com',
				'port' => 123,
			],
		];

		$cases = [];
		foreach ( $schemes as $scheme => $schemeParts ) {
			foreach ( $hosts as $host => $hostParts ) {
				foreach ( [ '', '/path' ] as $path ) {
					foreach ( [ '', 'query' ] as $query ) {
						foreach ( [ '', 'fragment' ] as $fragment ) {
							$parts = array_merge(
								$schemeParts,
								$hostParts
							);
							$url = $scheme .
								$host .
								$path;

							if ( $path ) {
								$parts['path'] = $path;
							}
							if ( $query ) {
								$parts['query'] = $query;
								$url .= '?' . $query;
							}
							if ( $fragment ) {
								$parts['fragment'] = $fragment;
								$url .= '#' . $fragment;
							}

							$cases[] = [
								$parts,
								$url,
							];
						}
					}
				}
			}
		}

		$complexURL = 'http://id:key@example.org:321' .
			'/over/there?name=ferret&foo=bar#nose';
		$cases[] = [
			wfParseUrl( $complexURL ),
			$complexURL,
		];

		return $cases;
	}
}
