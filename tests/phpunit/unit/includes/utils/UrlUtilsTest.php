<?php
use MediaWiki\Utils\UrlUtils;

/**
 * @coversDefaultClass \MediaWiki\Utils\UrlUtils
 * @covers ::__construct
 */
class UrlUtilsTest extends MediaWikiUnitTestCase {
	private const DEFAULT_PROTOS = [
		'http' => PROTO_HTTP,
		'https' => PROTO_HTTPS,
		'protocol-relative' => PROTO_RELATIVE,
		'current' => PROTO_CURRENT,
		'fallback' => PROTO_FALLBACK,
		'canonical' => PROTO_CANONICAL,
		'internal' => PROTO_INTERNAL,
	];

	public function testConstructError(): void {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Unrecognized option "unrecognized"' );
		new UrlUtils( [ 'unrecognized' => true ] );
	}

	/**
	 * @covers ::expand
	 * @dataProvider provideExpandException
	 * @param array $options
	 * @param string|int|null $defaultProto
	 * @param string $expectedClass Expected class of exception
	 * @param string $expectedMsg Expected exception message
	 */
	public function testExpandException(
		array $options, $defaultProto, string $expectedClass, string $expectedMsg
	): void {
		$this->expectException( $expectedClass );
		$this->expectExceptionMessage( $expectedMsg );

		$urlUtils = new UrlUtils( $options );
		$urlUtils->expand( '/', $defaultProto );
	}

	public static function provideExpandException(): Generator {
		foreach ( self::DEFAULT_PROTOS as $protoDesc => $defaultProto ) {
			$options = [
				UrlUtils::SERVER => null,
				UrlUtils::CANONICAL_SERVER => 'http://example.com',
				UrlUtils::INTERNAL_SERVER => 'http://example.com',
			];

			if ( $defaultProto === PROTO_CANONICAL ) {
				$options[UrlUtils::CANONICAL_SERVER] = null;
			} elseif ( $defaultProto === PROTO_INTERNAL ) {
				$options[UrlUtils::INTERNAL_SERVER] = null;
			}

			yield "defaultProto $protoDesc with appropriate options unset" => [
				$options, $defaultProto, BadMethodCallException::class,
				'Cannot call expand() if the appropriate SERVER/CANONICAL_SERVER/INTERNAL_SERVER' .
				' option was not passed to the constructor',
			];
		}

		yield 'protocol-relative server with port and custom HTTPS port' => [
			[ UrlUtils::SERVER => '//example.com:123', UrlUtils::HTTPS_PORT => 456 ],
			PROTO_HTTPS,
			Exception::class,
			'A protocol-relative server may not contain a port number',
		];
	}

	/**
	 * @covers ::expand
	 * @dataProvider provideExpand
	 * @param string $input
	 * @param array $options
	 * @param string|int|null $defaultProto
	 * @param ?string $expected
	 */
	public function testExpand(
		string $input, array $options, $defaultProto, ?string $expected
	): void {
		$urlUtils = new UrlUtils( $options );
		$this->assertSame( $expected, $urlUtils->expand( $input, $defaultProto ) );
	}

	/**
	 * Helper method to reduce nesting in provideExpand()
	 *
	 * @return Generator
	 */
	private static function provideExpandCases(): Generator {
		$servers = [
			'http://example.com',
			'https://example.com',
			'//example.com',
		];

		foreach ( $servers as $server ) {
			foreach ( [ 'http://example2.com', 'https://example2.com' ] as $canServer ) {
				foreach ( [ 'http://internal.com', 'https://internal.com' ] as $intServer ) {
					foreach ( [ 'http', 'https' ] as $fallbackProto ) {
						foreach ( [ 111, 443 ] as $httpsPort ) {
							$options = [
								UrlUtils::SERVER => $server,
								UrlUtils::CANONICAL_SERVER => $canServer,
								UrlUtils::INTERNAL_SERVER => $intServer,
								UrlUtils::FALLBACK_PROTOCOL => $fallbackProto,
								UrlUtils::HTTPS_PORT => $httpsPort,
							];
							foreach ( self::DEFAULT_PROTOS as $protoDesc => $defaultProto ) {
								yield [ $options, $protoDesc, $defaultProto ];
							}
						}
					}
				}
			}
		}
	}

	public static function provideExpand(): Generator {
		$modes = [ 'http', 'https' ];

		foreach ( self::provideExpandCases() as [ $options, $protoDesc, $defaultProto ] ) {
			$case = "default: $protoDesc";
			foreach ( $options as $key => $val ) {
				$case .= ", $key: $val";
			}

			yield "No-op fully-qualified http URL ($case)" => [
				'http://example.com',
				$options, $defaultProto,
				'http://example.com',
			];
			yield "No-op fully-qualified https URL ($case)" => [
				'https://example.com',
				$options, $defaultProto,
				'https://example.com',
			];
			yield "No-op fully-qualified https URL with port ($case)" => [
				'https://example.com:222',
				$options, $defaultProto,
				'https://example.com:222',
			];
			yield "No-op fully-qualified https URL with standard port ($case)" => [
				'https://example.com:443',
				$options, $defaultProto,
				'https://example.com:443',
			];
			yield "No-op rootless path-only URL ($case)" => [
				"wiki/FooBar",
				$options, $defaultProto,
				'wiki/FooBar',
			];

			// Determine expected protocol
			switch ( $protoDesc ) {
				case 'protocol-relative':
					$p = '';
					break;

				case 'fallback':
				case 'current':
					$p = $options[UrlUtils::FALLBACK_PROTOCOL] . ':';
					break;

				case 'canonical':
					$p = strtok( $options[UrlUtils::CANONICAL_SERVER], ':' ) . ':';
					break;

				case 'internal':
					$p = strtok( $options[UrlUtils::INTERNAL_SERVER], ':' ) . ':';
					break;

				case 'http':
				case 'https':
					$p = "$protoDesc:";
			}
			yield "Expand protocol-relative URL ($case)" => [
				'//wikipedia.org',
				$options, $defaultProto,
				"$p//wikipedia.org",
			];

			// Determine expected server name
			if ( $protoDesc === 'canonical' ) {
				$srv = $options[UrlUtils::CANONICAL_SERVER];
			} elseif ( $protoDesc === 'internal' ) {
				$srv = $options[UrlUtils::INTERNAL_SERVER];
			} elseif ( substr( $options[UrlUtils::SERVER], 0, 2 ) === '//' ) {
				$srv = $p . $options[UrlUtils::SERVER];
			} else {
				$srv = $options[UrlUtils::SERVER];
			}

			// Add a port only if it's not the default port, the server
			// protocol is relative, and we're actually trying to produce an
			// HTTPS URL
			if ( $options[UrlUtils::HTTPS_PORT] !== 443 &&
				substr( $options[UrlUtils::SERVER], 0, 2 ) === '//' &&
				( $defaultProto === PROTO_HTTPS || ( $defaultProto === PROTO_FALLBACK &&
					$options[UrlUtils::FALLBACK_PROTOCOL] === 'https' ) )
			) {
				$srv .= ':' . $options[UrlUtils::HTTPS_PORT];
			}

			yield "Expand path that starts with slash ($case)" => [
				'/wiki/FooBar',
				$options, $defaultProto,
				"$srv/wiki/FooBar",
			];
		}

		// Silly corner cases
		yield 'CanonicalServer with no protocol' => [
			'/',
			[
				UrlUtils::CANONICAL_SERVER => '//dont.do.this',
				UrlUtils::FALLBACK_PROTOCOL => 'https',
			],
			PROTO_CANONICAL,
			'http://dont.do.this/',
		];
		yield 'InternalServer with no protocol' => [
			'/',
			[
				UrlUtils::INTERNAL_SERVER => '//dont.do.this',
				UrlUtils::FALLBACK_PROTOCOL => 'https',
			],
			PROTO_INTERNAL,
			'http://dont.do.this/',
		];

		// XXX Find something that this will actually return null for

		// XXX Add dot-removing tests
	}

	/**
	 * @covers ::getServer
	 * @dataProvider provideGetServer
	 * @param array $options
	 * @param string|int|null $defaultProto
	 * @param string $expected
	 */
	public function testGetServer( array $options, $defaultProto, string $expected ): void {
		$urlUtils = new UrlUtils( $options );
		$this->assertSame( $expected, $urlUtils->getServer( $defaultProto ) );
	}

	public static function provideGetServer(): Generator {
		foreach ( self::provideExpand() as $desc => [ $input, $options, $defaultProto, $expected ]
		) {
			if ( $input !== '/wiki/FooBar' ) {
				continue;
			}
			$desc = str_replace( 'Expand path that starts with slash (', '', $desc );
			$desc = str_replace( ')', '', $desc );
			$expected = str_replace( '/wiki/FooBar', '', $expected );
			yield $desc => [ $options, $defaultProto, $expected ];
		}
	}

	/**
	 * @covers ::assemble
	 * @dataProvider provideAssemble
	 * @param array $bits
	 * @param string $expected
	 */
	public function testAssemble( array $bits, string $expected ): void {
		$urlUtils = new UrlUtils( [ UrlUtils::VALID_PROTOCOLS => [
			'//',
			'http://',
			'https://',
			'file://',
			'mailto:',
		] ] );
		$this->assertSame( $expected, $urlUtils->assemble( $bits ) );
	}

	public static function provideAssemble(): Generator {
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

							yield [ $parts, $url ];
						}
					}
				}
			}
		}

		yield [
			[
				'scheme' => 'http',
				'delimiter' => '://',
				'user' => 'id',
				'pass' => 'key',
				'host' => 'example.org',
				'port' => 321,
				'path' => '/over/there',
				'query' => 'name=ferret&foo=bar',
				'fragment' => 'nose',
			],
			'http://id:key@example.org:321/over/there?name=ferret&foo=bar#nose',
		];

		// Account for parse_url() on PHP >= 8 returning an empty query field for URLs ending with
		// '?' such as "http://url.with.empty.query/foo?" (T268852)
		yield [
			[
				'scheme' => 'http',
				'delimiter' => '://',
				'host' => 'url.with.empty.query',
				'path' => '/foo',
				'query' => '',
			],
			'http://url.with.empty.query/foo',
		];
	}

	/**
	 * @covers ::removeDotSegments
	 * @dataProvider provideRemoveDotSegments
	 * @param string $input
	 * @param string $expected
	 */
	public function testRemoveDotSegments( string $input, string $expected ): void {
		$this->assertSame( $expected, ( new UrlUtils )->removeDotSegments( $input ) );
	}

	public static function provideRemoveDotSegments(): Generator {
		yield [ '/a/b/c/./../../g', '/a/g' ];
		yield [ 'mid/content=5/../6', 'mid/6' ];
		yield [ '/a//../b', '/a/b' ];
		yield [ '/.../a', '/.../a' ];
		yield [ '.../a', '.../a' ];
		yield [ '', '' ];
		yield [ '/', '/' ];
		yield [ '//', '//' ];
		yield [ '.', '' ];
		yield [ '..', '' ];
		yield [ '...', '...' ];
		yield [ '/.', '/' ];
		yield [ '/..', '/' ];
		yield [ './', '' ];
		yield [ '../', '' ];
		yield [ './a', 'a' ];
		yield [ '../a', 'a' ];
		yield [ '../../a', 'a' ];
		yield [ '.././a', 'a' ];
		yield [ './../a', 'a' ];
		yield [ '././a', 'a' ];
		yield [ '../../', '' ];
		yield [ '.././', '' ];
		yield [ './../', '' ];
		yield [ '././', '' ];
		yield [ '../..', '' ];
		yield [ '../.', '' ];
		yield [ './..', '' ];
		yield [ './.', '' ];
		yield [ '/../../a', '/a' ];
		yield [ '/.././a', '/a' ];
		yield [ '/./../a', '/a' ];
		yield [ '/././a', '/a' ];
		yield [ '/../../', '/' ];
		yield [ '/.././', '/' ];
		yield [ '/./../', '/' ];
		yield [ '/././', '/' ];
		yield [ '/../..', '/' ];
		yield [ '/../.', '/' ];
		yield [ '/./..', '/' ];
		yield [ '/./.', '/' ];
		yield [ 'b/../../a', '/a' ];
		yield [ 'b/.././a', '/a' ];
		yield [ 'b/./../a', '/a' ];
		yield [ 'b/././a', 'b/a' ];
		yield [ 'b/../../', '/' ];
		yield [ 'b/.././', '/' ];
		yield [ 'b/./../', '/' ];
		yield [ 'b/././', 'b/' ];
		yield [ 'b/../..', '/' ];
		yield [ 'b/../.', '/' ];
		yield [ 'b/./..', '/' ];
		yield [ 'b/./.', 'b/' ];
		yield [ '/b/../../a', '/a' ];
		yield [ '/b/.././a', '/a' ];
		yield [ '/b/./../a', '/a' ];
		yield [ '/b/././a', '/b/a' ];
		yield [ '/b/../../', '/' ];
		yield [ '/b/.././', '/' ];
		yield [ '/b/./../', '/' ];
		yield [ '/b/././', '/b/' ];
		yield [ '/b/../..', '/' ];
		yield [ '/b/../.', '/' ];
		yield [ '/b/./..', '/' ];
		yield [ '/b/./.', '/b/' ];
	}

	/**
	 * @covers ::validProtocols
	 * @covers ::validAbsoluteProtocols
	 * @covers ::validProtocolsInternal
	 * @dataProvider provideValidProtocols
	 * @param string $method 'validProtocols' or 'validAbsoluteProtocols'
	 * @param array|string $validProtocols Value of option passed to UrlUtils
	 * @param string $expected
	 */
	public function testValidProtocols( string $method, $validProtocols, string $expected ): void {
		if ( !is_array( $validProtocols ) ) {
			$this->expectDeprecationAndContinue(
				'/Use of \$wgUrlProtocols that is not an array was deprecated in MediaWiki 1\.39/' );
		}
		$urlUtils = new UrlUtils( [ UrlUtils::VALID_PROTOCOLS => $validProtocols ] );
		$this->assertSame( $expected, $urlUtils->$method() );
	}

	public static function provideValidProtocols(): Generator {
		foreach ( [ 'validProtocols', 'validAbsoluteProtocols' ] as $method ) {
			yield "$method with string for UrlProtocols" =>
				[ $method, 'some|string', 'some|string' ];
			yield "$method simple case" => [ $method, [ 'foo', 'bar' ], 'foo|bar' ];
			yield "$method reserved characters" => [ $method,
				[ '^si|lly', 'in/valid', 'p[ro*t?o.c+o(ls$' ],
				'\^si\|lly|in\/valid|p\[ro\*t\?o\.c\+o\(ls\$' ];
		}
		yield 'validProtocols with relative' =>
			[ 'validProtocols', [ 'a', '//', 'b' ], 'a|\/\/|b' ];
		yield 'validAbsoluteProtocols with relative' =>
			[ 'validAbsoluteProtocols', [ 'a', '//', 'b' ], 'a|b' ];
	}

	/**
	 * @covers ::parse
	 * @dataProvider provideParse
	 * @param string $url
	 * @param ?array $expected
	 */
	public function testParse( string $url, ?array $expected ): void {
		$urlUtils = new UrlUtils( [ UrlUtils::VALID_PROTOCOLS => [
			'//',
			'http://',
			'https://',
			'file://',
			'mailto:',
		] ] );
		$actual = $urlUtils->parse( $url );
		if ( $expected ) {
			ksort( $expected );
		}
		if ( $actual ) {
			ksort( $actual );
		}
		$this->assertSame( $expected, $actual );
	}

	public static function provideParse(): Generator {
		yield [
			'//example.org',
			[
				'scheme' => '',
				'delimiter' => '//',
				'host' => 'example.org',
			]
		];
		yield [
			'http://example.org',
			[
				'scheme' => 'http',
				'delimiter' => '://',
				'host' => 'example.org',
			]
		];
		yield [
			'https://example.org',
			[
				'scheme' => 'https',
				'delimiter' => '://',
				'host' => 'example.org',
			]
		];
		yield [
			'http://id:key@example.org:123/path?foo=bar#baz',
			[
				'scheme' => 'http',
				'delimiter' => '://',
				'user' => 'id',
				'pass' => 'key',
				'host' => 'example.org',
				'port' => 123,
				'path' => '/path',
				'query' => 'foo=bar',
				'fragment' => 'baz',
			]
		];
		yield [
			'file://example.org/etc/php.ini',
			[
				'scheme' => 'file',
				'delimiter' => '://',
				'host' => 'example.org',
				'path' => '/etc/php.ini',
			]
		];
		yield [
			'file:///etc/php.ini',
			[
				'scheme' => 'file',
				'delimiter' => '://',
				'host' => '',
				'path' => '/etc/php.ini',
			]
		];
		yield [
			'file:///c:/',
			[
				'scheme' => 'file',
				'delimiter' => '://',
				'host' => '',
				'path' => '/c:/',
			]
		];
		yield [
			'mailto:id@example.org',
			[
				'scheme' => 'mailto',
				'delimiter' => ':',
				'host' => 'id@example.org',
				'path' => '',
			]
		];
		yield [
			'mailto:id@example.org?subject=Foo',
			[
				'scheme' => 'mailto',
				'delimiter' => ':',
				'host' => 'id@example.org',
				'path' => '',
				'query' => 'subject=Foo',
			]
		];
		yield [
			'mailto:?subject=Foo',
			[
				'scheme' => 'mailto',
				'delimiter' => ':',
				'host' => '',
				'path' => '',
				'query' => 'subject=Foo',
			]
		];
		yield [
			'invalid://test/',
			null
		];
		// T212067
		yield [
			'//evil.com?example.org/foo/bar',
			[
				'scheme' => '',
				'delimiter' => '//',
				'host' => 'evil.com',
				'query' => 'example.org/foo/bar',
			]
		];
		yield [
			'//evil.com?example.org/foo/bar?baz#quux',
			[
				'scheme' => '',
				'delimiter' => '//',
				'host' => 'evil.com',
				'query' => 'example.org/foo/bar?baz',
				'fragment' => 'quux',
			]
		];
		yield [
			'//evil.com?example.org?baz#quux',
			[
				'scheme' => '',
				'delimiter' => '//',
				'host' => 'evil.com',
				'query' => 'example.org?baz',
				'fragment' => 'quux',
			]
		];
		yield [
			'//evil.com?example.org#quux',
			[
				'scheme' => '',
				'delimiter' => '//',
				'host' => 'evil.com',
				'query' => 'example.org',
				'fragment' => 'quux',
			]
		];
		yield [
			'%0Ahttp://example.com',
			null,
		];
		yield [
			'http:///test.com',
			null,
		];
		// T294559
		yield [
			'//xy.wikimedia.org/wiki/Foo:1234',
			[
				'scheme' => '',
				'delimiter' => '//',
				'host' => 'xy.wikimedia.org',
				'path' => '/wiki/Foo:1234'
			]
		];
		yield [
			'//xy.wikimedia.org:8888/wiki/Foo:1234',
			[
				'scheme' => '',
				'delimiter' => '//',
				'host' => 'xy.wikimedia.org',
				'path' => '/wiki/Foo:1234',
				'port' => 8888,
			]
		];
	}

	/**
	 * @covers ::expandIRI
	 */
	public function testExpandIRI(): void {
		$this->assertSame( "https://te.wikibooks.org/wiki/ఉబుంటు_వాడుకరి_మార్గదర్శని",
			( new UrlUtils )->expandIRI( "https://te.wikibooks.org/wiki/"
				. "%E0%B0%89%E0%B0%AC%E0%B1%81%E0%B0%82%E0%B0%9F%E0%B1%81_"
				. "%E0%B0%B5%E0%B0%BE%E0%B0%A1%E0%B1%81%E0%B0%95%E0%B0%B0%E0%B0%BF_"
				. "%E0%B0%AE%E0%B0%BE%E0%B0%B0%E0%B1%8D%E0%B0%97%E0%B0%A6%E0%B0%B0"
				. "%E0%B1%8D%E0%B0%B6%E0%B0%A8%E0%B0%BF" ) );
	}

	/**
	 * @covers ::matchesDomainList
	 * @dataProvider provideMatchesDomainList
	 * @param string $url
	 * @param array $domains
	 * @param bool $expected
	 */
	public function testMatchesDomainList( string $url, array $domains, bool $expected ): void {
		$this->assertSame( $expected, ( new UrlUtils )->matchesDomainList( $url, $domains ) );
	}

	public static function provideMatchesDomainList(): Generator {
		$protocols = [ 'HTTP' => 'http:', 'HTTPS' => 'https:', 'protocol-relative' => '' ];
		foreach ( $protocols as $pDesc => $p ) {
			yield "No matches for empty domains array, $pDesc URL" => [
				"$p//www.example.com",
				[],
				false,
			];
			yield "Exact match in domains array, $pDesc URL" => [
				"$p//www.example.com",
				[ 'www.example.com' ],
				true,
			];
			yield "Match without subdomain in domains array, $pDesc URL" => [
				"$p//www.example.com",
				[ 'example.com' ],
				true,
			];
			yield "Exact match with other domains in array, $pDesc URL" => [
				"$p//www.example2.com",
				[ 'www.example.com', 'www.example2.com', 'www.example3.com' ],
				true,
			];
			yield "Match without subdomain with other domains in array, $pDesc URL" => [
				"$p//www.example2.com",
				[ 'example.com', 'example2.com', 'example3,com' ],
				true,
			];
			yield "Domain not in array, $pDesc URL" => [
				"$p//www.example4.com",
				[ 'example.com', 'example2.com', 'example3,com' ],
				false,
			];
			yield "Non-matching substring of domain, $pDesc URL" => [
				"$p//nds-nl.wikipedia.org",
				[ 'nl.wikipedia.org' ],
				false,
			];
		}
	}

}
