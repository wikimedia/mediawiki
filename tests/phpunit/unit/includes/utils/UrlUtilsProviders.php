<?php

use MediaWiki\Utils\UrlUtils;
use Wikimedia\ArrayUtils\ArrayUtils;

/**
 * Shared data providers for UrlUtilsTest and some GlobalFunctions tests
 */
class UrlUtilsProviders {
	private const DEFAULT_PROTOS = [
		'http' => PROTO_HTTP,
		'https' => PROTO_HTTPS,
		'protocol-relative' => PROTO_RELATIVE,
		'current' => PROTO_CURRENT,
		'fallback' => PROTO_FALLBACK,
		'canonical' => PROTO_CANONICAL,
		'internal' => PROTO_INTERNAL,
	];

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

	public static function provideExpand() {
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
			'fallback' => PROTO_FALLBACK,
			'canonical' => PROTO_CANONICAL,
		];

		foreach ( ArrayUtils::cartesianProduct( $modes, $servers, $modes, array_keys( $defaultProtos ) )
			as [ $fallbackProto, $server, $canServerMode, $protoDesc ]
		) {
			$defaultProto = $defaultProtos[$protoDesc];
			$canServer = "$canServerMode://example2.com";
			$options = [
				UrlUtils::SERVER => $server,
				UrlUtils::CANONICAL_SERVER => $canServer,
				UrlUtils::HTTPS_PORT => 443,
				UrlUtils::FALLBACK_PROTOCOL => $fallbackProto,
			];
			$case = "fallback: $fallbackProto, default: $protoDesc, server: $server, canonical: $canServer";
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
			yield "No-op rootless path-only URL ($case" => [
				"wiki/FooBar",
				$options, $defaultProto,
				'wiki/FooBar',
			];

			// Determine expected protocol
			if ( $protoDesc === 'protocol-relative' ) {
				$p = '';
			} elseif ( $protoDesc === 'fallback' ) {
				$p = "$fallbackProto:";
			} elseif ( $protoDesc === 'canonical' ) {
				$p = "$canServerMode:";
			} else {
				$p = $protoDesc . ':';
			}
			yield "Expand protocol-relative URL ($case)" => [
				'//wikipedia.org',
				$options, $defaultProto,
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
				$options, $defaultProto,
				"$srv/wiki/FooBar",
			];
		}

		// Non-standard https port
		$optionsRel111 = [
			UrlUtils::SERVER => '//wiki.example.com',
			UrlUtils::CANONICAL_SERVER => 'http://wiki.example.com',
			UrlUtils::HTTPS_PORT => 111,
			UrlUtils::FALLBACK_PROTOCOL => 'https',
		];
		yield "No-op foreign URL, ignore custom port config" => [
			'https://foreign.example.com/foo',
			$optionsRel111, PROTO_HTTPS,
			'https://foreign.example.com/foo',
		];
		yield "No-op foreign URL, preserve existing port" => [
			'https://foreign.example.com:222/foo',
			$optionsRel111, PROTO_HTTPS,
			'https://foreign.example.com:222/foo',
		];
		yield "Expand path with custom HTTPS port" => [
			'/foo',
			$optionsRel111, PROTO_HTTPS,
			'https://wiki.example.com:111/foo',
		];

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

	public static function provideGetServer(): Generator {
		foreach ( self::provideExpand() as $desc => [ $input, $options, $defaultProto, $expected ] ) {
			if ( $input !== '/wiki/FooBar' ) {
				continue;
			}
			$desc = str_replace( 'Expand path that starts with slash (', '', $desc );
			$desc = str_replace( ')', '', $desc );
			$expected = str_replace( '/wiki/FooBar', '', $expected );
			yield $desc => [ $options, $defaultProto, $expected ];
		}
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
			'file:///',
			[
				'scheme' => 'file',
				'delimiter' => '://',
				'host' => '',
				'path' => '/',
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
			'file://example.org',
			[
				'scheme' => 'file',
				'delimiter' => '://',
				'host' => 'example.org',
			]
		];
		yield [
			'mailto:id@example.org',
			[
				'scheme' => 'mailto',
				'delimiter' => ':',
				'host' => '',
				'path' => 'id@example.org',
			]
		];
		yield [
			'mailto:id@example.org?subject=Foo',
			[
				'scheme' => 'mailto',
				'delimiter' => ':',
				'host' => '',
				'path' => 'id@example.org',
				'query' => 'subject=Foo',
			]
		];
		yield [
			'mailto:?subject=Foo',
			[
				'scheme' => 'mailto',
				'delimiter' => ':',
				'host' => '',
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
		yield [
			'news:test.1234afc@news.test.com',
			[
				'scheme' => 'news',
				'delimiter' => ':',
				'host' => '',
				'path' => 'test.1234afc@news.test.com'
			]
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
