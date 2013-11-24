<?php
/**
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
		$schemes = array(
			'' => array(),
			'//' => array(
				'delimiter' => '//',
			),
			'http://' => array(
				'scheme' => 'http',
				'delimiter' => '://',
			),
		);

		$hosts = array(
			'' => array(),
			'example.com' => array(
				'host' => 'example.com',
			),
			'example.com:123' => array(
				'host' => 'example.com',
				'port' => 123,
			),
			'id@example.com' => array(
				'user' => 'id',
				'host' => 'example.com',
			),
			'id@example.com:123' => array(
				'user' => 'id',
				'host' => 'example.com',
				'port' => 123,
			),
			'id:key@example.com' => array(
				'user' => 'id',
				'pass' => 'key',
				'host' => 'example.com',
			),
			'id:key@example.com:123' => array(
				'user' => 'id',
				'pass' => 'key',
				'host' => 'example.com',
				'port' => 123,
			),
		);

		$cases = array();
		foreach ( $schemes as $scheme => $schemeParts ) {
			foreach ( $hosts as $host => $hostParts ) {
				foreach ( array( '', '/path' ) as $path ) {
					foreach ( array( '', 'query' ) as $query ) {
						foreach ( array( '', 'fragment' ) as $fragment ) {
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

							$cases[] = array(
								$parts,
								$url,
							);
						}
					}
				}
			}
		}

		$complexURL = 'http://id:key@example.org:321' .
			'/over/there?name=ferret&foo=bar#nose';
		$cases[] = array(
			wfParseUrl( $complexURL ),
			$complexURL,
		);

		return $cases;
	}
}
