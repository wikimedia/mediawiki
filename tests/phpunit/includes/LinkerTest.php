<?php

/**
 * @group Database
 */

class LinkerTest extends MediaWikiLangTestCase {

	/**
	 * @dataProvider provideCasesForUserLink
	 * @covers Linker::userLink
	 */
	public function testUserLink( $expected, $userId, $userName, $altUserName = false, $msg = '' ) {
		$this->setMwGlobals( array(
			'wgArticlePath' => '/wiki/$1',
			'wgWellFormedXml' => true,
		) );

		$this->assertEquals( $expected,
			Linker::userLink( $userId, $userName, $altUserName, $msg )
		);
	}

	public static function provideCasesForUserLink() {
		# Format:
		# - expected
		# - userid
		# - username
		# - optional altUserName
		# - optional message
		return array(

			### ANONYMOUS USER ########################################
			array(
				'<a href="/wiki/Special:Contributions/JohnDoe" '
					. 'title="Special:Contributions/JohnDoe" '
					. 'class="mw-userlink mw-anonuserlink">JohnDoe</a>',
				0, 'JohnDoe', false,
			),
			array(
				'<a href="/wiki/Special:Contributions/::1" '
					. 'title="Special:Contributions/::1" '
					. 'class="mw-userlink mw-anonuserlink">::1</a>',
				0, '::1', false,
				'Anonymous with pretty IPv6'
			),
			array(
				'<a href="/wiki/Special:Contributions/0:0:0:0:0:0:0:1" '
					. 'title="Special:Contributions/0:0:0:0:0:0:0:1" '
					. 'class="mw-userlink mw-anonuserlink">::1</a>',
				0, '0:0:0:0:0:0:0:1', false,
				'Anonymous with almost pretty IPv6'
			),
			array(
				'<a href="/wiki/Special:Contributions/0000:0000:0000:0000:0000:0000:0000:0001" '
					. 'title="Special:Contributions/0000:0000:0000:0000:0000:0000:0000:0001" '
					. 'class="mw-userlink mw-anonuserlink">::1</a>',
				0, '0000:0000:0000:0000:0000:0000:0000:0001', false,
				'Anonymous with full IPv6'
			),
			array(
				'<a href="/wiki/Special:Contributions/::1" '
					. 'title="Special:Contributions/::1" '
					. 'class="mw-userlink mw-anonuserlink">AlternativeUsername</a>',
				0, '::1', 'AlternativeUsername',
				'Anonymous with pretty IPv6 and an alternative username'
			),

			# IPV4
			array(
				'<a href="/wiki/Special:Contributions/127.0.0.1" '
					. 'title="Special:Contributions/127.0.0.1" '
					. 'class="mw-userlink mw-anonuserlink">127.0.0.1</a>',
				0, '127.0.0.1', false,
				'Anonymous with IPv4'
			),
			array(
				'<a href="/wiki/Special:Contributions/127.0.0.1" '
					. 'title="Special:Contributions/127.0.0.1" '
					. 'class="mw-userlink mw-anonuserlink">AlternativeUsername</a>',
				0, '127.0.0.1', 'AlternativeUsername',
				'Anonymous with IPv4 and an alternative username'
			),

			### Regular user ##########################################
			# TODO!
		);
	}

	/**
	 * @dataProvider provideCasesForFormatComment
	 * @covers Linker::formatComment
	 * @covers Linker::formatAutocomments
	 * @covers Linker::formatLinksInComment
	 */
	public function testFormatComment( $expected, $comment, $title = false, $local = false ) {
		$this->setMwGlobals( array(
			'wgScript' => '/wiki/index.php',
			'wgArticlePath' => '/wiki/$1',
			'wgWellFormedXml' => true,
			'wgCapitalLinks' => true,
		) );

		if ( $title === false ) {
			// We need a page title that exists
			$title = Title::newFromText( 'Special:BlankPage' );
		}

		$this->assertEquals(
			$expected,
			Linker::formatComment( $comment, $title, $local )
		);
	}

	public static function provideCasesForFormatComment() {
		return array(
			// Linker::formatComment
			array(
				'a&lt;script&gt;b',
				'a<script>b',
			),
			array(
				'a—b',
				'a&mdash;b',
			),
			array(
				"&#039;&#039;&#039;not bolded&#039;&#039;&#039;",
				"'''not bolded'''",
			),
			// Linker::formatAutocomments
			array(
				'<a href="/wiki/Special:BlankPage#autocomment" title="Special:BlankPage">→</a>‎<span dir="auto"><span class="autocomment">autocomment</span></span>',
				"/* autocomment */",
			),
			array(
				'<a href="/wiki/Special:BlankPage#linkie.3F" title="Special:BlankPage">→</a>‎<span dir="auto"><span class="autocomment"><a href="/wiki/index.php?title=Linkie%3F&amp;action=edit&amp;redlink=1" class="new" title="Linkie? (page does not exist)">linkie?</a></span></span>',
				"/* [[linkie?]] */",
			),
			array(
				'<a href="/wiki/Special:BlankPage#autocomment" title="Special:BlankPage">→</a>‎<span dir="auto"><span class="autocomment">autocomment: </span> post</span>',
				"/* autocomment */ post",
			),
			array(
				'pre <a href="/wiki/Special:BlankPage#autocomment" title="Special:BlankPage">→</a>‎<span dir="auto"><span class="autocomment">autocomment</span></span>',
				"pre /* autocomment */",
			),
			array(
				'pre <a href="/wiki/Special:BlankPage#autocomment" title="Special:BlankPage">→</a>‎<span dir="auto"><span class="autocomment">autocomment: </span> post</span>',
				"pre /* autocomment */ post",
			),
			array(
				'<a href="/wiki/Special:BlankPage#autocomment" title="Special:BlankPage">→</a>‎<span dir="auto"><span class="autocomment">autocomment: </span> multiple? <a href="/wiki/Special:BlankPage#autocomment2" title="Special:BlankPage">→</a>‎<span dir="auto"><span class="autocomment">autocomment2: </span> </span></span>',
				"/* autocomment */ multiple? /* autocomment2 */ ",
			),
			array(
				'<a href="/wiki/Special:BlankPage#autocomment_containing_.2F.2A" title="Special:BlankPage">→</a>‎<span dir="auto"><span class="autocomment">autocomment containing /*: </span> T70361</span>',
				"/* autocomment containing /* */ T70361"
			),
			array(
				'<a href="#autocomment">→</a>‎<span dir="auto"><span class="autocomment">autocomment</span></span>',
				"/* autocomment */",
				false, true
			),
			array(
				'‎<span dir="auto"><span class="autocomment">autocomment</span></span>',
				"/* autocomment */",
				null
			),
			// Linker::formatLinksInComment
			array(
				'abc <a href="/wiki/index.php?title=Link&amp;action=edit&amp;redlink=1" class="new" title="Link (page does not exist)">link</a> def',
				"abc [[link]] def",
			),
			array(
				'abc <a href="/wiki/index.php?title=Link&amp;action=edit&amp;redlink=1" class="new" title="Link (page does not exist)">text</a> def',
				"abc [[link|text]] def",
			),
			array(
				'abc <a href="/wiki/Special:BlankPage" title="Special:BlankPage">Special:BlankPage</a> def',
				"abc [[Special:BlankPage|]] def",
			),
			array(
				'abc <a href="/wiki/index.php?title=%C4%84%C5%9B%C5%BC&amp;action=edit&amp;redlink=1" class="new" title="Ąśż (page does not exist)">ąśż</a> def',
				"abc [[%C4%85%C5%9B%C5%BC]] def",
			),
			array(
				'abc <a href="/wiki/Special:BlankPage#section" title="Special:BlankPage">#section</a> def',
				"abc [[#section]] def",
			),
			array(
				'abc <a href="/wiki/index.php?title=/subpage&amp;action=edit&amp;redlink=1" class="new" title="/subpage (page does not exist)">/subpage</a> def',
				"abc [[/subpage]] def",
			),
		);
	}

	/**
	 * @covers Linker::formatLinksInComment
	 * @dataProvider provideCasesForFormatLinksInComment
	 */
	public function testFormatLinksInComment( $expected, $input, $wiki ) {

		$conf = new SiteConfiguration();
		$conf->settings = array(
			'wgServer' => array(
				'enwiki' => '//en.example.org'
			),
			'wgArticlePath' => array(
				'enwiki' => '/w/$1',
			),
		);
		$conf->suffixes = array( 'wiki' );
		$this->setMwGlobals( array(
			'wgScript' => '/wiki/index.php',
			'wgArticlePath' => '/wiki/$1',
			'wgWellFormedXml' => true,
			'wgCapitalLinks' => true,
			'wgConf' => $conf,
		) );

		$this->assertEquals(
			$expected,
			Linker::formatLinksInComment( $input, Title::newFromText( 'Special:BlankPage' ), false, $wiki )
		);
	}

	public static function provideCasesForFormatLinksInComment() {
		return array(
			array(
				'foo bar <a href="/wiki/Special:BlankPage" title="Special:BlankPage">Special:BlankPage</a>',
				'foo bar [[Special:BlankPage]]',
				null,
			),
			array(
				'<a class="external" rel="nofollow" href="//en.example.org/w/Foo%27bar">Foo\'bar</a>',
				"[[Foo'bar]]",
				'enwiki',
			),
			array(
				'foo bar <a class="external" rel="nofollow" href="//en.example.org/w/Special:BlankPage">Special:BlankPage</a>',
				'foo bar [[Special:BlankPage]]',
				'enwiki',
			),
		);
	}
}
