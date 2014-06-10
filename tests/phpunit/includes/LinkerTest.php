<?php

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
			'wgArticlePath' => '/wiki/$1',
			'wgWellFormedXml' => true,
		) );
		
		if ( $title === false ) {
			$title = Title::newFromText( 'Test title' );
			$title->mArticleID = 123; // pretend that this page exists
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
				'<a href="/wiki/index.php?title=Test_title&amp;action=edit&amp;redlink=1" title="Test title (page does not exist)">→</a>‎<span dir="auto"><span class="autocomment">autocomment</span></span>',
				"/* autocomment */",
			),
			array(
				'<a href="/wiki/index.php?title=Test_title&amp;action=edit&amp;redlink=1" title="Test title (page does not exist)">→</a>‎<span dir="auto"><span class="autocomment"><a href="/wiki/index.php?title=Linkie%3F&amp;action=edit&amp;redlink=1" class="new" title="Linkie? (page does not exist)">linkie?</a></span></span>',
				"/* [[linkie?]] */",
			),
			array(
				'<a href="/wiki/index.php?title=Test_title&amp;action=edit&amp;redlink=1" title="Test title (page does not exist)">→</a>‎<span dir="auto"><span class="autocomment">autocomment: </span> post</span>',
				"/* autocomment */ post",
			),
			array(
				"???",
				"pre /* autocomment */",
			),
			array(
				"???",
				"pre /* autocomment */ post",
			),
			array(
				"???",
				"/* autocomment */ multiple? /* autocomment2 */ ",
			),
			array(
				"???",
				"/* autocomment */",
				false, true
			),
			array(
				"???",
				"/* autocomment */",
				null
			),
			// Linker::formatLinksInComment
			array(
				"???",
				"abc [[link]] def",
			),
			array(
				"???",
				"abc [[link|text]] def",
			),
			array(
				"???",
				"abc [[no-pipe-trick|]] def",
			),
			array(
				"???",
				"abc [[%C4%85%C5%9B%C5%BC]] def",
			),
			array(
				"???",
				"abc [[link#section]] def",
			),
			array(
				"???",
				"abc [[#section]] def",
			),
			array(
				"???",
				"abc [[/subpage]] def",
			),
		);
	}
}
