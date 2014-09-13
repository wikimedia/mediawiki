<?php

/**
 * @group Database
 */
class LinkFilterTest extends MediaWikiLangTestCase {

	protected function setUp() {

		parent::setUp();

		$this->setMwGlobals( 'wgUrlProtocols', array(
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
		) );

	}

	/**
	 * createRegexFromLike($like)
	 *
	 * Takes an array as created by LinkFilter::makeLikeArray() and creates a regex from it
	 *
	 * @param Array $like Array as created by LinkFilter::makeLikeArray()
	 * @return string Regex
	 */
	function createRegexFromLIKE( $like ) {

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

	/**
	 * provideValidPatterns()
	 *
	 * @return array
	 */
	public static function provideValidPatterns() {

		return array(
			// Protocol, Search pattern, URL which matches the pattern
			array( 'http://', '*.test.com', 'http://www.test.com' ),
			array( 'http://', 'test.com:8080/dir/file', 'http://name:pass@test.com:8080/dir/file' ),
			array( 'https://', '*.com', 'https://s.s.test..com:88/dir/file?a=1&b=2' ),
			array( 'https://', '*.com', 'https://name:pass@secure.com/index.html' ),
			array( 'http://', 'name:pass@test.com', 'http://test.com' ),
			array( 'http://', 'test.com', 'http://name:pass@test.com' ),
			array( 'http://', '*.test.com', 'http://a.b.c.test.com/dir/dir/file?a=6'),
			array( null, 'http://*.test.com', 'http://www.test.com' ),
			array( 'mailto:', 'name@mail.test123.com', 'mailto:name@mail.test123.com' ),
			array( '',
				'http://name:pass@www.test.com:12345/dir/dir/file.xyz.php#__se__?arg1=_&arg2[]=4rtg',
				'http://name:pass@www.test.com:12345/dir/dir/file.xyz.php#__se__?arg1=_&arg2[]=4rtg'
			),
			array( '', 'http://name:pass@*.test.com:12345/dir/dir/file.xyz.php#__se__?arg1=_&arg2[]=4rtg',
				'http://name:pass@www.test.com:12345/dir/dir/file.xyz.php#__se__?arg1=_&arg2[]=4rtg' ),
			array( '', 'http://name:wrongpass@*.com:12345/dir/dir/file.xyz.php#__se__?arg1=_&arg2[]',
				'http://name:pass@www.test.com:12345/dir/dir/file.xyz.php#__se__?arg1=_&arg2[]=4rtg' ),
			array( 'http://', 'name:pass@*.test.com:12345/dir/dir/file.xyz.php#__se__?arg1=_&arg2[]=4rtg',
				'http://name:pass@www.test.com:12345/dir/dir/file.xyz.php#__se__?arg1=_&arg2[]=4rtg' ),
			array( '', 'http://name:pass@www.test.com:12345',
				'http://name:pass@www.test.com:12345/dir/dir/file.xyz.php#__se__?arg1=_&arg2[]=4rtg' ),
			array( 'ftp://', 'user:pass@ftp.test.com:1233/home/user/file;type=efw',
				'ftp://user:pass@ftp.test.com:1233/home/user/file;type=efw' ),
			array( null, 'ftp://otheruser:otherpass@ftp.test.com:1233/home/user/file;type=',
				'ftp://user:pass@ftp.test.com:1233/home/user/file;type=efw' ),
			array( null, 'ftp://@ftp.test.com:1233/home/user/file;type=',
				'ftp://user:pass@ftp.test.com:1233/home/user/file;type=efw' ),
			array( null, 'ftp://ftp.test.com/',
				'ftp://user:pass@ftp.test.com/home/user/file;type=efw' ),
			array( null, 'ftp://ftp.test.com/',
				'ftp://user:pass@ftp.test.com/home/user/file;type=efw' ),
			array( null, 'ftp://*.test.com:222/',
				'ftp://user:pass@ftp.test.com:222/home' ),
			array( 'irc://', '*.myserver:6667/', 'irc://test.myserver:6667/' ),
			array( 'irc://', 'name:pass@*.myserver/', 'irc://test.myserver:6667/' ),
			array( 'irc://', 'name:pass@*.myserver/', 'irc://other:@test.myserver:6667/' ),
			array( '', 'irc://test/name,string,abc?msg=t', 'irc://test/name,string,abc?msg=test' ),
			array( '', 'https://gerrit.wikimedia.org/r/#/q/status:open,n,z',
				'https://gerrit.wikimedia.org/r/#/q/status:open,n,z' ),
			array( '', 'https://gerrit.wikimedia.org',
				'https://gerrit.wikimedia.org/r/#/q/status:open,n,z' ),
			array( 'mailto:', '*.test.com', 'mailto:name@pop3.test.com' ),
			array( 'mailto:', 'test.com', 'mailto:name@test.com' ),
			array( 'news:', 'test.1234afc@news.test.com', 'news:test.1234afc@news.test.com' ),
			array( 'news:', '*.test.com', 'news:test.1234afc@news.test.com' ),
			array( '', 'news:4df8kh$iagfewewf(at)newsbf02aaa.news.aol.com',
				'news:4df8kh$iagfewewf(at)newsbf02aaa.news.aol.com' ),
			array( '', 'news:*.aol.com',
				'news:4df8kh$iagfewewf(at)newsbf02aaa.news.aol.com' ),
			array( '', 'git://github.com/prwef/abc-def.git', 'git://github.com/prwef/abc-def.git' ),
			array( 'git://', 'github.com/', 'git://github.com/prwef/abc-def.git' ),
			array( 'git://', '*.github.com/', 'git://a.b.c.d.e.f.github.com/prwef/abc-def.git' ),
			array( '', 'gopher://*.test.com/', 'gopher://gopher.test.com/0/v2/vstat'),
			array( 'telnet://', '*.test.com', 'telnet://shell.test.com/~home/'),

			//
			// The following only work in PHP >= 5.3.7, due to a bug in parse_url which eats
			// the path from the url (https://bugs.php.net/bug.php?id=54180)
			//
			// array( '', 'http://test.com', 'http://test.com/index?arg=1' ),
			// array( 'http://', '*.test.com', 'http://www.test.com/index?arg=1' ),
			// array( '' ,
			//    'http://xx23124:__ffdfdef__@www.test.com:12345/dir' ,
			//    'http://name:pass@www.test.com:12345/dir/dir/file.xyz.php#__se__?arg1=_&arg2[]=4rtg'
			// ),
			//

			//
			// Tests for false positives
			//
			array( 'http://', 'test.com', 'http://www.test.com', false ),
			array( 'http://', 'www1.test.com', 'http://www.test.com', false ),
			array( 'http://', '*.test.com', 'http://www.test.t.com', false ),
			array( '', 'http://test.com:8080', 'http://www.test.com:8080', false ),
			array( '', 'https://test.com', 'http://test.com', false ),
			array( '', 'http://test.com', 'https://test.com', false ),
			array( 'http://', 'http://test.com', 'http://test.com', false ),
			array( null, 'http://www.test.com', 'http://www.test.com:80', false ),
			array( null, 'http://www.test.com:80', 'http://www.test.com', false ),
			array( null, 'http://*.test.com:80', 'http://www.test.com', false ),
			array( '', 'https://gerrit.wikimedia.org/r/#/XXX/status:open,n,z',
				'https://gerrit.wikimedia.org/r/#/q/status:open,n,z', false ),
			array( '', 'https://*.wikimedia.org/r/#/q/status:open,n,z',
				'https://gerrit.wikimedia.org/r/#/XXX/status:open,n,z', false ),
			array( 'mailto:', '@test.com', '@abc.test.com', false ),
			array( 'mailto:', 'mail@test.com', 'mail2@test.com', false ),
			array( '', 'mailto:mail@test.com', 'mail2@test.com', false ),
			array( '', 'mailto:@test.com', '@abc.test.com', false ),
			array( 'ftp://', '*.co', 'ftp://www.co.uk', false ),
			array( 'ftp://', '*.co', 'ftp://www.co.m', false ),
			array( 'ftp://', '*.co/dir/', 'ftp://www.co/dir2/', false ),
			array( 'ftp://', 'www.co/dir/', 'ftp://www.co/dir2/', false ),
			array( 'ftp://', 'test.com/dir/', 'ftp://test.com/', false ),
			array( '', 'http://test.com:8080/dir/', 'http://test.com:808/dir/', false ),
			array( '', 'http://test.com/dir/index.html', 'http://test.com/dir/index.php', false ),

			//
			// These are false positives too and ideally shouldn't match, but that
			// would require using regexes and RLIKE instead of LIKE
			//
			// array( null, 'http://*.test.com', 'http://www.test.com:80', false ),
			// array( '', 'https://*.wikimedia.org/r/#/q/status:open,n,z',
			//	'https://gerrit.wikimedia.org/XXX/r/#/q/status:open,n,z', false ),
		);

	}

	/**
	 * testMakeLikeArrayWithValidPatterns()
	 *
	 * Tests whether the LIKE clause produced by LinkFilter::makeLikeArray($pattern, $protocol)
	 * will find one of the URL indexes produced by wfMakeUrlIndexes($url)
	 *
	 * @dataProvider provideValidPatterns
	 *
	 * @param String $protocol Protocol, e.g. 'http://' or 'mailto:'
	 * @param String $pattern Search pattern to feed to LinkFilter::makeLikeArray
	 * @param String $url URL to feed to wfMakeUrlIndexes
	 * @param bool $shouldBeFound Should the URL be found? (defaults true)
	 */
	function testMakeLikeArrayWithValidPatterns( $protocol, $pattern, $url, $shouldBeFound = true ) {

		$indexes = wfMakeUrlIndexes( $url );
		$likeArray = LinkFilter::makeLikeArray( $pattern, $protocol );

		$this->assertTrue( $likeArray !== false,
			"LinkFilter::makeLikeArray('$pattern', '$protocol') returned false on a valid pattern"
		);

		$regex = $this->createRegexFromLIKE( $likeArray );
		$debugmsg = "Regex: '" . $regex . "'\n";
		$debugmsg .= count( $indexes ) . " index(es) created by wfMakeUrlIndexes():\n";

		$matches = 0;

		foreach ( $indexes as $index ) {
			$matches += preg_match( $regex, $index );
			$debugmsg .= "\t'$index'\n";
		}

		if ( $shouldBeFound ) {
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

	/**
	 * provideInvalidPatterns()
	 *
	 * @return array
	 */
	public static function provideInvalidPatterns() {

		return array(
			array( '' ),
			array( '*' ),
			array( 'http://*' ),
			array( 'http://*/' ),
			array( 'http://*/dir/file' ),
			array( 'test.*.com' ),
			array( 'http://test.*.com' ),
			array( 'test.*.com' ),
			array( 'http://*.test.*' ),
			array( 'http://*test.com' ),
			array( 'https://*' ),
			array( '*://test.com'),
			array( 'mailto:name:pass@t*est.com' ),
			array( 'http://*:888/'),
			array( '*http://'),
			array( 'test.com/*/index' ),
			array( 'test.com/dir/index?arg=*' ),
		);

	}

	/**
	 * testMakeLikeArrayWithInvalidPatterns()
	 *
	 * Tests whether LinkFilter::makeLikeArray($pattern) will reject invalid search patterns
	 *
	 * @dataProvider provideInvalidPatterns
	 *
	 * @param $pattern string: Invalid search pattern
	 */
	function testMakeLikeArrayWithInvalidPatterns( $pattern ) {

		$this->assertFalse(
			LinkFilter::makeLikeArray( $pattern ),
			"'$pattern' is not a valid pattern and should be rejected"
		);

	}

}
