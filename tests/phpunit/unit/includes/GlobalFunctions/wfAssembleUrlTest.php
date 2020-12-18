<?php
/**
 * @group GlobalFunctions
 * @covers ::wfAssembleUrl
 */
class WfAssembleUrlTest extends MediaWikiUnitTestCase {
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
				foreach ( [ '', '/', '/0', '/path' ] as $path ) {
					foreach ( [ '', '0', 'query' ] as $query ) {
						foreach ( [ '', '0', 'fragment' ] as $fragment ) {
							$parts = array_merge(
								$schemeParts,
								$hostParts
							);
							$url = $scheme .
								$host .
								$path;

							if ( $path !== '' ) {
								$parts['path'] = $path;
							}
							if ( $query !== '' ) {
								$parts['query'] = $query;
								$url .= '?' . $query;
							}
							if ( $fragment !== '' ) {
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

		// Account for parse_url() on PHP >= 8 returning an empty query field
		// for URLs ending with '?' such as "http://url.with.empty.query/foo?"
		// (T268852)
		$urlWithEmptyQuery = [
			'scheme' => 'http',
			'delimiter' => '://',
			'host' => 'url.with.empty.query',
			'path' => '/foo',
			'query' => '',
		];

		$cases[] = [
			$urlWithEmptyQuery,
			'http://url.with.empty.query/foo'
		];

		return $cases;
	}
}
