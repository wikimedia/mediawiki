<?php

use MediaWiki\ExternalLinks\LinkFilter;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Unit\Libs\Rdbms\AddQuoterMock;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeMatch;

/**
 * @covers \MediaWiki\ExternalLinks\LinkFilter
 * @group Database
 */
class LinkFilterTest extends MediaWikiLangTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValue( MainConfigNames::UrlProtocols, [
			'http://',
			'https://',
			'ftp://',
			'irc://',
			'ircs://',
			'gopher://',
			'telnet://',
			'nntp://',
			'worldwind://',
			'mailto:',
			'news:',
			'svn://',
			'git://',
			'mms://',
			'//',
		] );
	}

	/**
	 * Take an array from LinkFilter::makeLikeArray(), and create a regex from it
	 *
	 * @param array $like Array as created by LinkFilter::makeLikeArray()
	 * @return string Regex
	 */
	private function createRegexFromLIKE( $like ) {
		$regex = '!^';

		foreach ( $like as $item ) {
			if ( $item instanceof LikeMatch ) {
				if ( $item->toString() == '%' ) {
					$regex .= '.*';
				} elseif ( $item->toString() == '_' ) {
					$regex .= '.';
				}
			} else {
				$regex .= preg_quote( $item, '!' );
			}

		}

		$regex .= '$!';

		return $regex;
	}

	public static function provideValidPatterns() {
		return [
			// Protocol, Search pattern, URL which matches the pattern
			[ 'http://', '*.test.com', 'http://www.test.com' ],
			[ 'http://', 'test.com:8080/dir/file', 'http://name:pass@test.com:8080/dir/file' ],
			[ 'https://', '*.com', 'https://s.s.test..com:88/dir/file?a=1&b=2' ],
			[ 'https://', '*.com', 'https://name:pass@secure.com/index.html' ],
			[ 'http://', 'name:pass@test.com', 'http://test.com' ],
			[ 'http://', 'test.com', 'http://name:pass@test.com' ],
			[ 'http://', '*.test.com', 'http://a.b.c.test.com/dir/dir/file?a=6' ],
			[ null, 'http://*.test.com', 'http://www.test.com' ],
			[ 'http://', '.test.com', 'http://.test.com' ],
			[ 'http://', '*..test.com', 'http://foo..test.com' ],
			[ 'mailto:', 'name@mail.test123.com', 'mailto:name@mail.test123.com' ],
			[ 'mailto:', '*@mail.test123.com', 'mailto:name@mail.test123.com' ],
			[ '',
				'http://name:pass@www.test.com:12345/dir/dir/file.xyz.php#__se__?arg1=_&arg2[]=4rtg',
				'http://name:pass@www.test.com:12345/dir/dir/file.xyz.php#__se__?arg1=_&arg2[]=4rtg'
			],
			[ '', 'http://name:pass@*.test.com:12345/dir/dir/file.xyz.php#__se__?arg1=_&arg2[]=4rtg',
				'http://name:pass@www.test.com:12345/dir/dir/file.xyz.php#__se__?arg1=_&arg2[]=4rtg' ],
			[ '', 'http://name:wrongpass@*.com:12345/dir/dir/file.xyz.php#__se__?arg1=_&arg2[]',
				'http://name:pass@www.test.com:12345/dir/dir/file.xyz.php#__se__?arg1=_&arg2[]=4rtg' ],
			[ 'http://', 'name:pass@*.test.com:12345/dir/dir/file.xyz.php#__se__?arg1=_&arg2[]=4rtg',
				'http://name:pass@www.test.com:12345/dir/dir/file.xyz.php#__se__?arg1=_&arg2[]=4rtg' ],
			[ '', 'http://name:pass@www.test.com:12345',
				'http://name:pass@www.test.com:12345/dir/dir/file.xyz.php#__se__?arg1=_&arg2[]=4rtg' ],
			[ 'ftp://', 'user:pass@ftp.test.com:1233/home/user/file;type=efw',
				'ftp://user:pass@ftp.test.com:1233/home/user/file;type=efw' ],
			[ null, 'ftp://otheruser:otherpass@ftp.test.com:1233/home/user/file;type=',
				'ftp://user:pass@ftp.test.com:1233/home/user/file;type=efw' ],
			[ null, 'ftp://@ftp.test.com:1233/home/user/file;type=',
				'ftp://user:pass@ftp.test.com:1233/home/user/file;type=efw' ],
			[ null, 'ftp://ftp.test.com/',
				'ftp://user:pass@ftp.test.com/home/user/file;type=efw' ],
			[ null, 'ftp://ftp.test.com/',
				'ftp://user:pass@ftp.test.com/home/user/file;type=efw' ],
			[ null, 'ftp://*.test.com:222/',
				'ftp://user:pass@ftp.test.com:222/home' ],
			[ 'irc://', '*.myserver:6667/', 'irc://test.myserver:6667/' ],
			[ 'irc://', 'name:pass@*.myserver/', 'irc://test.myserver:6667/' ],
			[ 'irc://', 'name:pass@*.myserver/', 'irc://other:@test.myserver:6667/' ],
			[ '', 'irc://test/name,string,abc?msg=t', 'irc://test/name,string,abc?msg=test' ],
			[ '', 'https://gerrit.wikimedia.org/r/#/q/status:open,n,z',
				'https://gerrit.wikimedia.org/r/#/q/status:open,n,z' ],
			[ '', 'https://gerrit.wikimedia.org',
				'https://gerrit.wikimedia.org/r/#/q/status:open,n,z' ],
			[ 'mailto:', '*.test.com', 'mailto:name@pop3.test.com' ],
			[ 'mailto:', 'test.com', 'mailto:name@test.com' ],
			[ 'news:', 'test.1234afc@news.test.com', 'news:test.1234afc@news.test.com' ],
			[ 'news:', '*.test.com', 'news:test.1234afc@news.test.com' ],
			[ '', 'news:4df8kh$iagfewewf(at)newsbf02aaa.news.aol.com',
				'news:4df8kh$iagfewewf(at)newsbf02aaa.news.aol.com' ],
			[ '', 'news:*.aol.com',
				'news:4df8kh$iagfewewf(at)newsbf02aaa.news.aol.com' ],

			// (T347574) Only set host if it's not already set (if // is used)
			[ 'news:', 'comp.compression', 'news://comp.compression' ],
			[ 'news:', 'comp.compression', 'news:comp.compression' ],
			// (T364743) Also only set host if it's not already set, but in a different code path
			[ '', 'news://*.example.com', 'news://example.org', [ 'found' => false ] ],

			[ '', 'git://github.com/prwef/abc-def.git', 'git://github.com/prwef/abc-def.git' ],
			[ 'git://', 'github.com/', 'git://github.com/prwef/abc-def.git' ],
			[ 'git://', '*.github.com/', 'git://a.b.c.d.e.f.github.com/prwef/abc-def.git' ],
			[ '', 'gopher://*.test.com/', 'gopher://gopher.test.com/0/v2/vstat' ],
			[ 'telnet://', '*.test.com', 'telnet://shell.test.com/~home/' ],
			[ '', 'http://test.com', 'http://test.com/index?arg=1' ],
			[ 'http://', '*.test.com', 'http://www.test.com/index?arg=1' ],
			[ '',
				'http://xx23124:__ffdfdef__@www.test.com:12345/dir',
				'http://name:pass@www.test.com:12345/dir/dir/file.xyz.php#__se__?arg1=_&arg2[]=4rtg'
			],
			[ 'http://', '127.0.0.1', 'http://127.000.000.001' ],
			[ 'http://', '127.0.0.*', 'http://127.000.000.010' ],
			[ 'http://', '127.0.*', 'http://127.000.123.010' ],
			[ 'http://', '127.*', 'http://127.127.127.127' ],
			[ 'http://', '[0:0:0:0:0:0:0:0001]', 'http://[::1]' ],
			[ 'http://', '[2001:db8:0:0:*]', 'http://[2001:0DB8::]' ],
			[ 'http://', '[2001:db8:0:0:*]', 'http://[2001:0DB8::123]' ],
			[ 'http://', '[2001:db8:0:0:*]', 'http://[2001:0DB8::123:456]' ],
			[ 'http://', 'xn--f-vgaa.example.com', 'http://fóó.example.com' ],
			[ 'http://', 'xn--f-vgaa.example.com', 'http://f%c3%b3%C3%B3.example.com' ],
			[ 'http://', 'fóó.example.com', 'http://xn--f-vgaa.example.com' ],
			[ 'http://', 'f%c3%b3%C3%B3.example.com', 'http://xn--f-vgaa.example.com' ],
			[ 'http://', 'f%c3%b3%C3%B3.example.com', 'http://fóó.example.com' ],
			[ 'http://', 'fóó.example.com', 'http://f%c3%b3%C3%B3.example.com' ],

			[ 'http://', 'example.com./foo', 'http://example.com/foo' ],
			[ 'http://', 'example.com/foo', 'http://example.com./foo' ],
			[ 'http://', '127.0.0.1./foo', 'http://127.0.0.1/foo' ],
			[ 'http://', '127.0.0.1/foo', 'http://127.0.0.1./foo' ],

			// Tests for false positives
			[ 'http://', 'test.com', 'http://www.test.com', [ 'found' => false ] ],
			[ 'http://', 'www1.test.com', 'http://www.test.com', [ 'found' => false ] ],
			[ 'http://', '*.test.com', 'http://www.test.t.com', [ 'found' => false ] ],
			[ 'http://', 'test.com', 'http://xtest.com', [ 'found' => false ] ],
			[ 'http://', '*.test.com', 'http://xtest.com', [ 'found' => false ] ],
			[ 'http://', '.test.com', 'http://test.com', [ 'found' => false ] ],
			[ 'http://', '.test.com', 'http://www.test.com', [ 'found' => false ] ],
			[ 'http://', '*..test.com', 'http://test.com', [ 'found' => false ] ],
			[ 'http://', '*..test.com', 'http://www.test.com', [ 'found' => false ] ],
			[ '', 'http://test.com:8080', 'http://www.test.com:8080', [ 'found' => false ] ],
			[ '', 'https://test.com', 'http://test.com', [ 'found' => false ] ],
			[ '', 'http://test.com', 'https://test.com', [ 'found' => false ] ],
			[ 'http://', 'http://test.com', 'http://test.com', [ 'found' => false ] ],
			[ null, 'http://www.test.com', 'http://www.test.com:80', [ 'found' => false ] ],
			[ null, 'http://www.test.com:80', 'http://www.test.com', [ 'found' => false ] ],
			[ null, 'http://*.test.com:80', 'http://www.test.com', [ 'found' => false ] ],
			[ '', 'https://gerrit.wikimedia.org/r/#/XXX/status:open,n,z',
				'https://gerrit.wikimedia.org/r/#/q/status:open,n,z', [ 'found' => false ] ],
			[ '', 'https://*.wikimedia.org/r/#/q/status:open,n,z',
				'https://gerrit.wikimedia.org/r/#/XXX/status:open,n,z', [ 'found' => false ] ],
			[ 'mailto:', '@test.com', '@abc.test.com', [ 'found' => false ] ],
			[ 'mailto:', 'mail@test.com', 'mail2@test.com', [ 'found' => false ] ],
			[ '', 'mailto:mail@test.com', 'mail2@test.com', [ 'found' => false ] ],
			[ '', 'mailto:@test.com', '@abc.test.com', [ 'found' => false ] ],
			[ 'ftp://', '*.co', 'ftp://www.co.uk', [ 'found' => false ] ],
			[ 'ftp://', '*.co', 'ftp://www.co.m', [ 'found' => false ] ],
			[ 'ftp://', '*.co/dir/', 'ftp://www.co/dir2/', [ 'found' => false ] ],
			[ 'ftp://', 'www.co/dir/', 'ftp://www.co/dir2/', [ 'found' => false ] ],
			[ 'ftp://', 'test.com/dir/', 'ftp://test.com/', [ 'found' => false ] ],
			[ '', 'http://test.com:8080/dir/', 'http://test.com:808/dir/', [ 'found' => false ] ],
			[ '', 'http://test.com/dir/index.html', 'http://test.com/dir/index.php', [ 'found' => false ] ],
			[ 'http://', '127.0.0.*', 'http://127.0.1.0', [ 'found' => false ] ],
			[ 'http://', '[2001:db8::*]', 'http://[2001:0DB8::123:456]', [ 'found' => false ] ],

			// These are false positives too and ideally shouldn't match, but that
			// would require using regexes and RLIKE instead of LIKE
			// [ null, 'http://*.test.com', 'http://www.test.com:80', [ 'found' => false ] ],
			// [ '', 'https://*.wikimedia.org/r/#/q/status:open,n,z',
			// 	'https://gerrit.wikimedia.org/XXX/r/#/q/status:open,n,z', [ 'found' => false ] ],
		];
	}

	/**
	 * Tests whether the LIKE clause produced by LinkFilter::makeLikeArray($pattern, $protocol)
	 * will find one of the URL indexes produced by LinkFilter::makeIndexes($url)
	 *
	 * @dataProvider provideValidPatterns
	 *
	 * @param string $protocol Protocol, e.g. 'http://' or 'mailto:'
	 * @param string $pattern Search pattern to feed to LinkFilter::makeLikeArray
	 * @param string $url URL to feed to LinkFilter::makeIndexes
	 * @param array $options
	 *  - found: (bool) Should the URL be found? (defaults true)
	 */
	public function testMakeLikeArrayWithValidPatterns( $protocol, $pattern, $url, $options = [] ) {
		$options += [ 'found' => true ];

		$indexes = LinkFilter::makeIndexes( $url );
		$likeArrays = LinkFilter::makeLikeArray( $pattern, $protocol );
		$likeArray = array_merge( $likeArrays[0], $likeArrays[1] );

		$this->assertIsArray( $likeArrays,
			"LinkFilter::makeLikeArray('$pattern', '$protocol') returned false on a valid pattern"
		);

		$regex = $this->createRegexFromLIKE( $likeArray );
		$debugmsg = "Regex: '" . $regex . "'\n";
		$debugmsg .= count( $indexes ) . " index(es) created by LinkFilter::makeIndexes():\n";

		$matches = 0;

		foreach ( $indexes as $index ) {
			$indexString = implode( '', $index );
			$matches += preg_match( $regex, $indexString );
			$debugmsg .= "\t'$indexString'\n";
		}

		if ( $options['found'] ) {
			$this->assertTrue(
				$matches > 0,
				"Search pattern '$protocol$pattern' does not find url '$url' \n$debugmsg"
			);
		} else {
			$this->assertFalse(
				$matches > 0,
				"Search pattern '$protocol$pattern' should not find url '$url' \n$debugmsg"
			);
		}
	}

	public static function provideInvalidPatterns() {
		return [
			[ '' ],
			[ '*' ],
			[ 'http://*' ],
			[ 'http://*/' ],
			[ 'http://*/dir/file' ],
			[ 'test.*.com' ],
			[ 'http://test.*.com' ],
			[ 'test.*.com' ],
			[ 'http://*.test.*' ],
			[ 'http://*test.com' ],
			[ 'https://*' ],
			[ '*://test.com' ],
			[ 'mailto:name:pass@t*est.com' ],
			[ 'http://*:888/' ],
			[ '*http://' ],
			[ 'test.com/*/index' ],
			[ 'test.com/dir/index?arg=*' ],
		];
	}

	/**
	 * testMakeLikeArrayWithInvalidPatterns()
	 *
	 * Tests whether LinkFilter::makeLikeArray($pattern) will reject invalid search patterns
	 *
	 * @dataProvider provideInvalidPatterns
	 *
	 * @param string $pattern Invalid search pattern
	 */
	public function testMakeLikeArrayWithInvalidPatterns( $pattern ) {
		$this->assertFalse(
			LinkFilter::makeLikeArray( $pattern ),
			"'$pattern' is not a valid pattern and should be rejected"
		);
	}

	/**
	 * @dataProvider provideMakeIndexes
	 */
	public function testMakeIndexes( $url, $expected ) {
		// Set global so file:// tests can work
		$this->overrideConfigValue(
			MainConfigNames::UrlProtocols, [
				'http://',
				'https://',
				'mailto:',
				'//',
				'file://', # Non-default
			] );

		$index = LinkFilter::makeIndexes( $url );
		$this->assertEquals( $expected, $index, "LinkFilter::makeIndexes(\"$url\")" );
	}

	public static function provideMakeIndexes() {
		return [
			// Testcase for T30627
			[
				'https://example.org/test.cgi?id=12345',
				[ [ 'https://org.example.', '/test.cgi?id=12345' ] ]
			],
			[
				// mailtos are handled special
				'mailto:wiki@wikimedia.org',
				[ [ 'mailto:org.wikimedia.@wiki', '' ] ]
			],
			[
				// mailtos are handled special
				'mailto:wiki',
				[ [ 'mailto:@wiki', '' ] ]
			],

			// file URL cases per T30627...
			[
				// three slashes: local filesystem path Unix-style
				'file:///whatever/you/like.txt',
				[ [ 'file://.', '/whatever/you/like.txt' ] ]
			],
			[
				// three slashes: local filesystem path Windows-style
				'file:///c:/whatever/you/like.txt',
				[ [ 'file://.', '/c:/whatever/you/like.txt' ] ]
			],
			[
				// two slashes: UNC filesystem path Windows-style
				'file://intranet/whatever/you/like.txt',
				[ [ 'file://intranet.', '/whatever/you/like.txt' ] ]
			],
			// Multiple-slash cases that can sorta work on Mozilla
			// if you hack it just right are kinda pathological,
			// and unreliable cross-platform or on IE which means they're
			// unlikely to appear on intranets.
			// Those will survive the algorithm but with results that
			// are less consistent.

			// protocol-relative URL cases per T31854...
			[
				'//example.org/test.cgi?id=12345',
				[ [ 'https://org.example.', '/test.cgi?id=12345' ] ]
			],

			// IP addresses
			[
				'http://192.0.2.0/foo',
				[ [ 'http://V4.192.0.2.0.', '/foo' ] ]
			],
			[
				'http://192.0.0002.0/foo',
				[ [ 'http://V4.192.0.2.0.', '/foo' ] ]
			],
			[
				'http://[2001:db8::1]/foo',
				[ [ 'http://V6.2001.DB8.0.0.0.0.0.1.', '/foo' ] ]
			],

			// Explicit specification of the DNS root
			[
				'http://example.com./foo',
				[ [ 'http://com.example.', '/foo' ] ]
			],
			[
				'http://192.0.2.0./foo',
				[ [ 'http://V4.192.0.2.0.', '/foo' ] ]
			],

			// Weird edge case
			[
				'http://.example.com/foo',
				[ [ 'http://com.example..', '/foo' ] ]
			],
		];
	}

	/**
	 * @dataProvider provideReverseIndexes
	 */
	public function testReverseIndex( $url, $expected ) {
		// Set global so file:// tests can work
		$this->overrideConfigValue(
			MainConfigNames::UrlProtocols, [
			'http://',
			'https://',
			'mailto:',
			'//',
			'file://', # Non-default
			] );

		$index = LinkFilter::reverseIndexes( $url );
		$this->assertEquals( $expected, $index, "LinkFilter::reverseIndexe(\"$url\")" );
	}

	public static function provideReverseIndexes() {
		return [
			// Testcase for T30627
			[
				'https://org.example./test.cgi?id=12345',
				'https://example.org'
			],
			[
				// mailtos are handled special
				'mailto:org.wikimedia.@wiki',
				'mailto:wiki@wikimedia.org'
			],
			[
				// mailtos are handled special
				'mailto:@wiki',
				'mailto:wiki@'
			],
			[
				// mailtos are handled special
				'mailto:wiki',
				'mailto:wiki'
			],

			// file URL cases per T30627...
			[
				// three slashes: local filesystem path Unix-style
				'file:///whatever/you/like.txt',
				'file://'
			],
			[
				// three slashes: local filesystem path Windows-style
				'file:///c:/whatever/you/like.txt',
				'file://'
			],
			[
				// two slashes: UNC filesystem path Windows-style
				'file://intranet/whatever/you/like.txt',
				'file://intranet'
			],
			// Multiple-slash cases that can sorta work on Mozilla
			// if you hack it just right are kinda pathological,
			// and unreliable cross-platform or on IE which means they're
			// unlikely to appear on intranets.
			// Those will survive the algorithm but with results that
			// are less consistent.

			// protocol-relative URL cases per T31854...
			[
				'//org.example/test.cgi?id=12345',
				'//example.org'
			],

			// IP addresses
			[
				'http://V4.192.0.2.0./foo',
				'http://192.0.2.0'
			],
			[
				'http://V4.192.0.2.0./foo',
				'http://192.0.2.0'
			],
			[
				'http://V6.2001.DB8.0.0.0.0.0.1./foo',
				'http://[2001:DB8:0:0:0:0:0:1]'
			],

			// Explicit specification of the DNS root
			[
				'http://com.example./foo',
				'http://example.com'
			],
			[
				'http://192.0.2.0./foo',
				'http://V4.192.0.2.0'
			],

			// Weird edge case
			[
				'http://com.example../foo',
				'http://.example.com'
			],

			// port
			[
				'http://com.example.:8000/foo',
				'http://example.com:8000'
			],
		];
	}

	/**
	 * @dataProvider provideGetQueryConditions
	 */
	public function testGetQueryConditions( $query, $options, $expected ) {
		$this->overrideConfigValues( [
			MainConfigNames::ExternalLinksDomainGaps => [
				'http://example.gaps.' => [
					10 => 20,
					100 => 200,
					1000 => 2000,
				],
				'https://example.gaps.' => [
					30 => 40,
					5000 => 60000,
				],
			],
		] );
		$conds = LinkFilter::getQueryConditions( $query, $options );
		if ( !$conds ) {
			$this->assertEquals( $expected, $conds );
			return;
		}
		$sqlConds = [];
		foreach ( $conds as $cond ) {
			if ( $cond instanceof IExpression ) {
				$sqlConds[] = $cond->toSql( new AddQuoterMock() );
			} else {
				$sqlConds[] = $cond;
			}
		}
		$this->assertEquals( $expected, $sqlConds );
	}

	public static function provideGetQueryConditions() {
		return [
			'Basic example' => [
				'example.com',
				[],
				[
					'(el_to_domain_index LIKE \'http://com.example.%\' ESCAPE \'`\' OR ' .
					'el_to_domain_index LIKE \'https://com.example.%\' ESCAPE \'`\')',
					'el_to_path LIKE \'/%\' ESCAPE \'`\'',
				],
			],
			'Basic example with path' => [
				'example.com/foobar',
				[],
				[
					'(el_to_domain_index LIKE \'http://com.example.%\' ESCAPE \'`\' OR ' .
					'el_to_domain_index LIKE \'https://com.example.%\' ESCAPE \'`\')',
					'el_to_path LIKE \'/foobar%\' ESCAPE \'`\'',
				],
			],
			'Wildcard domain' => [
				'*.example.com',
				[],
				[
					'(el_to_domain_index LIKE \'http://com.example.%\' ESCAPE \'`\' OR ' .
					'el_to_domain_index LIKE \'https://com.example.%\' ESCAPE \'`\')',
					'el_to_path LIKE \'/%\' ESCAPE \'`\'',
				],
			],
			'Wildcard domain with path' => [
				'*.example.com/foobar',
				[],
				[
					'(el_to_domain_index LIKE \'http://com.example.%\' ESCAPE \'`\' OR ' .
					'el_to_domain_index LIKE \'https://com.example.%\' ESCAPE \'`\')',
					'el_to_path LIKE \'/foobar%\' ESCAPE \'`\'',
				],
			],
			'Wildcard domain with path, oneWildcard=true' => [
				'*.example.com/foobar',
				[ 'oneWildcard' => true ],
				[
					'(el_to_domain_index = \'http://com.example.\' OR ' .
					'el_to_domain_index = \'https://com.example.\')',
					'el_to_path LIKE \'/foobar%\' ESCAPE \'`\'',
				],
			],
			'Constant prefix' => [
				'example.com/blah/blah/blah/blah/blah/blah/blah/blah/blah/blah?foo=',
				[],
				[
					'(el_to_domain_index LIKE \'http://com.example.%\' ESCAPE \'`\' OR ' .
					'el_to_domain_index LIKE \'https://com.example.%\' ESCAPE \'`\')',
					'el_to_path LIKE ' .
					'\'/blah/blah/blah/blah/blah/blah/blah/blah/blah/blah?foo=%\' ' .
					'ESCAPE \'`\'',
				],
			],
			'Bad protocol' => [
				'test/',
				[ 'protocol' => 'invalid://' ],
				false
			],
			'domains with gaps' => [
				'gaps.example',
				[],
				[
					'((el_to_domain_index LIKE \'http://example.gaps.%\' ESCAPE \'`\' AND ' .
					'(el_id < 10 OR el_id > 20) AND ' .
					'(el_id < 100 OR el_id > 200) AND ' .
					'(el_id < 1000 OR el_id > 2000)) OR ' .
					'(el_to_domain_index LIKE \'https://example.gaps.%\' ESCAPE \'`\' AND ' .
					'(el_id < 30 OR el_id > 40) AND ' .
					'(el_id < 5000 OR el_id > 60000)))',
					'el_to_path LIKE \'/%\' ESCAPE \'`\'',
				],
			],
			'Various options' => [
				'example.com',
				[ 'protocol' => 'https://' ],
				[
					"(el_to_domain_index LIKE 'https://com.example.%' ESCAPE '`')",
					"el_to_path LIKE '/%' ESCAPE '`'",
				],
			],
		];
	}

	/**
	 * @dataProvider provideGetIndexedUrlsNonReversed
	 */
	public function testGetIndexedUrlsNonReversed( $urls, $expected ) {
		$list = LinkFilter::getIndexedUrlsNonReversed( $urls );
		$this->assertEquals( $expected, $list );
	}

	public static function provideGetIndexedUrlsNonReversed() {
		return [
			'Basic example' => [
				[ 'https://example.com' ],
				[ 'https://example.com/' ],
			],
			'Basic example with path' => [
				[ 'https://example.com/foobar' ],
				[ 'https://example.com/foobar' ],
			],
			'Proto-relative' => [
				[ '//example.com/foobar' ],
				[ 'https://example.com/foobar' ],
			],
			'Links with port' => [
				[ 'https://spam.com/index.html:8000' ],
				[ 'https://spam.com/index.html:8000' ],
			],
			'Links with user' => [
				[ 'https://foo@spam.com/bar.html' ],
				[ 'https://spam.com/bar.html' ],
			],
			'Mailto' => [
				[ 'mailto:foo@example.com' ],
				[ 'mailto:foo@example.com' ],
			],
			'IPv6 (gosh I hate ipv6)' => [
				[ 'http://[::1]/' ],
				[ 'http://[0:0:0:0:0:0:0:1]/' ]
			],
			'IPv4' => [
				[ 'http://0.0.0.1/' ],
				[ 'http://0.0.0.1/' ]
			]
		];
	}
}
