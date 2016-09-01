<?php

use MediaWiki\MediaWikiServices;

/**
 * @group Database
 */

class LinkerTest extends MediaWikiLangTestCase {

	/**
	 * @dataProvider provideCasesForUserLink
	 * @covers Linker::userLink
	 */
	public function testUserLink( $expected, $userId, $userName, $altUserName = false, $msg = '' ) {
		$this->setMwGlobals( [
			'wgArticlePath' => '/wiki/$1',
		] );

		$this->assertEquals(
			$expected,
			Linker::userLink( $userId, $userName, $altUserName ),
			$msg
		);
	}

	public static function provideCasesForUserLink() {
		# Format:
		# - expected
		# - userid
		# - username
		# - optional altUserName
		# - optional message
		return [

			# ## ANONYMOUS USER ########################################
			[
				'<a href="/wiki/Special:Contributions/JohnDoe" '
					. 'class="mw-userlink mw-anonuserlink" '
					. 'title="Special:Contributions/JohnDoe"><bdi>JohnDoe</bdi></a>',
				0, 'JohnDoe', false,
			],
			[
				'<a href="/wiki/Special:Contributions/::1" '
					. 'class="mw-userlink mw-anonuserlink" '
					. 'title="Special:Contributions/::1"><bdi>::1</bdi></a>',
				0, '::1', false,
				'Anonymous with pretty IPv6'
			],
			[
				'<a href="/wiki/Special:Contributions/0:0:0:0:0:0:0:1" '
					. 'class="mw-userlink mw-anonuserlink" '
					. 'title="Special:Contributions/0:0:0:0:0:0:0:1"><bdi>::1</bdi></a>',
				0, '0:0:0:0:0:0:0:1', false,
				'Anonymous with almost pretty IPv6'
			],
			[
				'<a href="/wiki/Special:Contributions/0000:0000:0000:0000:0000:0000:0000:0001" '
					. 'class="mw-userlink mw-anonuserlink" '
					. 'title="Special:Contributions/0000:0000:0000:0000:0000:0000:0000:0001"><bdi>::1</bdi></a>',
				0, '0000:0000:0000:0000:0000:0000:0000:0001', false,
				'Anonymous with full IPv6'
			],
			[
				'<a href="/wiki/Special:Contributions/::1" '
					. 'class="mw-userlink mw-anonuserlink" '
					. 'title="Special:Contributions/::1"><bdi>AlternativeUsername</bdi></a>',
				0, '::1', 'AlternativeUsername',
				'Anonymous with pretty IPv6 and an alternative username'
			],

			# IPV4
			[
				'<a href="/wiki/Special:Contributions/127.0.0.1" '
					. 'class="mw-userlink mw-anonuserlink" '
					. 'title="Special:Contributions/127.0.0.1"><bdi>127.0.0.1</bdi></a>',
				0, '127.0.0.1', false,
				'Anonymous with IPv4'
			],
			[
				'<a href="/wiki/Special:Contributions/127.0.0.1" '
					. 'class="mw-userlink mw-anonuserlink" '
					. 'title="Special:Contributions/127.0.0.1"><bdi>AlternativeUsername</bdi></a>',
				0, '127.0.0.1', 'AlternativeUsername',
				'Anonymous with IPv4 and an alternative username'
			],

			# ## Regular user ##########################################
			# TODO!
		];
	}

	/**
	 * @dataProvider provideCasesForFormatComment
	 * @covers Linker::formatComment
	 * @covers Linker::formatAutocomments
	 * @covers Linker::formatLinksInComment
	 */
	public function testFormatComment(
		$expected, $comment, $title = false, $local = false, $wikiId = null
	) {
		$conf = new SiteConfiguration();
		$conf->settings = [
			'wgServer' => [
				'enwiki' => '//en.example.org',
				'dewiki' => '//de.example.org',
			],
			'wgArticlePath' => [
				'enwiki' => '/w/$1',
				'dewiki' => '/w/$1',
			],
		];
		$conf->suffixes = [ 'wiki' ];

		$this->setMwGlobals( [
			'wgScript' => '/wiki/index.php',
			'wgArticlePath' => '/wiki/$1',
			'wgCapitalLinks' => true,
			'wgConf' => $conf,
		] );

		if ( $title === false ) {
			// We need a page title that exists
			$title = Title::newFromText( 'Special:BlankPage' );
		}

		$this->assertEquals(
			$expected,
			Linker::formatComment( $comment, $title, $local, $wikiId )
		);
	}

	public function provideCasesForFormatComment() {
		$wikiId = 'enwiki'; // $wgConf has a fake entry for this

		// @codingStandardsIgnoreStart Generic.Files.LineLength
		return [
			// Linker::formatComment
			[
				'a&lt;script&gt;b',
				'a<script>b',
			],
			[
				'a—b',
				'a&mdash;b',
			],
			[
				"&#039;&#039;&#039;not bolded&#039;&#039;&#039;",
				"'''not bolded'''",
			],
			[
				"try &lt;script&gt;evil&lt;/scipt&gt; things",
				"try <script>evil</scipt> things",
			],
			// Linker::formatAutocomments
			[
				'<a href="/wiki/Special:BlankPage#autocomment" title="Special:BlankPage">→</a>‎<span dir="auto"><span class="autocomment">autocomment</span></span>',
				"/* autocomment */",
			],
			[
				'<a href="/wiki/Special:BlankPage#linkie.3F" title="Special:BlankPage">→</a>‎<span dir="auto"><span class="autocomment"><a href="/wiki/index.php?title=Linkie%3F&amp;action=edit&amp;redlink=1" class="new" title="Linkie? (page does not exist)">linkie?</a></span></span>',
				"/* [[linkie?]] */",
			],
			[
				'<a href="/wiki/Special:BlankPage#autocomment" title="Special:BlankPage">→</a>‎<span dir="auto"><span class="autocomment">autocomment: </span> post</span>',
				"/* autocomment */ post",
			],
			[
				'pre <a href="/wiki/Special:BlankPage#autocomment" title="Special:BlankPage">→</a>‎<span dir="auto"><span class="autocomment">autocomment</span></span>',
				"pre /* autocomment */",
			],
			[
				'pre <a href="/wiki/Special:BlankPage#autocomment" title="Special:BlankPage">→</a>‎<span dir="auto"><span class="autocomment">autocomment: </span> post</span>',
				"pre /* autocomment */ post",
			],
			[
				'<a href="/wiki/Special:BlankPage#autocomment" title="Special:BlankPage">→</a>‎<span dir="auto"><span class="autocomment">autocomment: </span> multiple? <a href="/wiki/Special:BlankPage#autocomment2" title="Special:BlankPage">→</a>‎<span dir="auto"><span class="autocomment">autocomment2: </span> </span></span>',
				"/* autocomment */ multiple? /* autocomment2 */ ",
			],
			[
				'<a href="/wiki/Special:BlankPage#autocomment_containing_.2F.2A" title="Special:BlankPage">→</a>‎<span dir="auto"><span class="autocomment">autocomment containing /*: </span> T70361</span>',
				"/* autocomment containing /* */ T70361"
			],
			[
				'<a href="/wiki/Special:BlankPage#autocomment_containing_.22quotes.22" title="Special:BlankPage">→</a>‎<span dir="auto"><span class="autocomment">autocomment containing &quot;quotes&quot;</span></span>',
				"/* autocomment containing \"quotes\" */"
			],
			[
				'<a href="/wiki/Special:BlankPage#autocomment_containing_.3Cscript.3Etags.3C.2Fscript.3E" title="Special:BlankPage">→</a>‎<span dir="auto"><span class="autocomment">autocomment containing &lt;script&gt;tags&lt;/script&gt;</span></span>',
				"/* autocomment containing <script>tags</script> */"
			],
			[
				'<a href="#autocomment">→</a>‎<span dir="auto"><span class="autocomment">autocomment</span></span>',
				"/* autocomment */",
				false, true
			],
			[
				'‎<span dir="auto"><span class="autocomment">autocomment</span></span>',
				"/* autocomment */",
				null
			],
			[
				'<a href="/wiki/Special:BlankPage#autocomment" title="Special:BlankPage">→</a>‎<span dir="auto"><span class="autocomment">autocomment</span></span>',
				"/* autocomment */",
				false, false
			],
			[
				'<a class="external" rel="nofollow" href="//en.example.org/w/Special:BlankPage#autocomment">→</a>‎<span dir="auto"><span class="autocomment">autocomment</span></span>',
				"/* autocomment */",
				false, false, $wikiId
			],
			// Linker::formatLinksInComment
			[
				'abc <a href="/wiki/index.php?title=Link&amp;action=edit&amp;redlink=1" class="new" title="Link (page does not exist)">link</a> def',
				"abc [[link]] def",
			],
			[
				'abc <a href="/wiki/index.php?title=Link&amp;action=edit&amp;redlink=1" class="new" title="Link (page does not exist)">text</a> def',
				"abc [[link|text]] def",
			],
			[
				'abc <a href="/wiki/Special:BlankPage" title="Special:BlankPage">Special:BlankPage</a> def',
				"abc [[Special:BlankPage|]] def",
			],
			[
				'abc <a href="/wiki/index.php?title=%C4%84%C5%9B%C5%BC&amp;action=edit&amp;redlink=1" class="new" title="Ąśż (page does not exist)">ąśż</a> def',
				"abc [[%C4%85%C5%9B%C5%BC]] def",
			],
			[
				'abc <a href="/wiki/Special:BlankPage#section" title="Special:BlankPage">#section</a> def',
				"abc [[#section]] def",
			],
			[
				'abc <a href="/wiki/index.php?title=/subpage&amp;action=edit&amp;redlink=1" class="new" title="/subpage (page does not exist)">/subpage</a> def',
				"abc [[/subpage]] def",
			],
			[
				'abc <a href="/wiki/index.php?title=%22evil!%22&amp;action=edit&amp;redlink=1" class="new" title="&quot;evil!&quot; (page does not exist)">&quot;evil!&quot;</a> def',
				"abc [[\"evil!\"]] def",
			],
			[
				'abc [[&lt;script&gt;very evil&lt;/script&gt;]] def',
				"abc [[<script>very evil</script>]] def",
			],
			[
				'abc [[|]] def',
				"abc [[|]] def",
			],
			[
				'abc <a href="/wiki/index.php?title=Link&amp;action=edit&amp;redlink=1" class="new" title="Link (page does not exist)">link</a> def',
				"abc [[link]] def",
				false, false
			],
			[
				'abc <a class="external" rel="nofollow" href="//en.example.org/w/Link">link</a> def',
				"abc [[link]] def",
				false, false, $wikiId
			],
		];
		// @codingStandardsIgnoreEnd
	}

	/**
	 * @covers Linker::formatLinksInComment
	 * @dataProvider provideCasesForFormatLinksInComment
	 */
	public function testFormatLinksInComment( $expected, $input, $wiki ) {

		$conf = new SiteConfiguration();
		$conf->settings = [
			'wgServer' => [
				'enwiki' => '//en.example.org'
			],
			'wgArticlePath' => [
				'enwiki' => '/w/$1',
			],
		];
		$conf->suffixes = [ 'wiki' ];
		$this->setMwGlobals( [
			'wgScript' => '/wiki/index.php',
			'wgArticlePath' => '/wiki/$1',
			'wgCapitalLinks' => true,
			'wgConf' => $conf,
		] );

		$this->assertEquals(
			$expected,
			Linker::formatLinksInComment( $input, Title::newFromText( 'Special:BlankPage' ), false, $wiki )
		);
	}

	public static function provideCasesForFormatLinksInComment() {
		// @codingStandardsIgnoreStart Generic.Files.LineLength
		return [
			[
				'foo bar <a href="/wiki/Special:BlankPage" title="Special:BlankPage">Special:BlankPage</a>',
				'foo bar [[Special:BlankPage]]',
				null,
			],
			[
				'<a class="external" rel="nofollow" href="//en.example.org/w/Foo%27bar">Foo\'bar</a>',
				"[[Foo'bar]]",
				'enwiki',
			],
			[
				'foo bar <a class="external" rel="nofollow" href="//en.example.org/w/Special:BlankPage">Special:BlankPage</a>',
				'foo bar [[Special:BlankPage]]',
				'enwiki',
			],
		];
		// @codingStandardsIgnoreEnd
	}

	public static function provideLinkBeginHook() {
		// @codingStandardsIgnoreStart Generic.Files.LineLength
		return [
			// Modify $html
			[
				function( $dummy, $title, &$html, &$attribs, &$query, &$options, &$ret ) {
					$html = 'foobar';
				},
				'<a href="/wiki/Special:BlankPage" title="Special:BlankPage">foobar</a>'
			],
			// Modify $attribs
			[
				function( $dummy, $title, &$html, &$attribs, &$query, &$options, &$ret ) {
					$attribs['bar'] = 'baz';
				},
				'<a href="/wiki/Special:BlankPage" title="Special:BlankPage" bar="baz">Special:BlankPage</a>'
			],
			// Modify $query
			[
				function( $dummy, $title, &$html, &$attribs, &$query, &$options, &$ret ) {
					$query['bar'] = 'baz';
				},
				'<a href="/w/index.php?title=Special:BlankPage&amp;bar=baz" title="Special:BlankPage">Special:BlankPage</a>'
			],
			// Force HTTP $options
			[
				function( $dummy, $title, &$html, &$attribs, &$query, &$options, &$ret ) {
					$options = [ 'http' ];
				},
				'<a href="http://example.org/wiki/Special:BlankPage" title="Special:BlankPage">Special:BlankPage</a>'
			],
			// Force 'forcearticlepath' in $options
			[
				function( $dummy, $title, &$html, &$attribs, &$query, &$options, &$ret ) {
					$options = [ 'forcearticlepath' ];
					$query['foo'] = 'bar';
				},
				'<a href="/wiki/Special:BlankPage?foo=bar" title="Special:BlankPage">Special:BlankPage</a>'
			],
			// Abort early
			[
				function( $dummy, $title, &$html, &$attribs, &$query, &$options, &$ret ) {
					$ret = 'foobar';
					return false;
				},
				'foobar'
			],
		];
		// @codingStandardsIgnoreEnd
	}

	/**
	 * @covers MediaWiki\Linker\LinkRenderer::runLegacyBeginHook
	 * @dataProvider provideLinkBeginHook
	 */
	public function testLinkBeginHook( $callback, $expected ) {
		$this->setMwGlobals( [
			'wgArticlePath' => '/wiki/$1',
			'wgServer' => '//example.org',
			'wgCanonicalServer' => 'http://example.org',
			'wgScriptPath' => '/w',
			'wgScript' => '/w/index.php',
		] );

		$this->setMwGlobals( 'wgHooks', [ 'LinkBegin' => [ $callback ] ] );
		$title = SpecialPage::getTitleFor( 'Blankpage' );
		$out = Linker::link( $title );
		$this->assertEquals( $expected, $out );
	}

	public static function provideLinkEndHook() {
		return [
			// Override $html
			[
				function( $dummy, $title, $options, &$html, &$attribs, &$ret ) {
					$html = 'foobar';
				},
				'<a href="/wiki/Special:BlankPage" title="Special:BlankPage">foobar</a>'
			],
			// Modify $attribs
			[
				function( $dummy, $title, $options, &$html, &$attribs, &$ret ) {
					$attribs['bar'] = 'baz';
				},
				'<a href="/wiki/Special:BlankPage" title="Special:BlankPage" bar="baz">Special:BlankPage</a>'
			],
			// Fully override return value and abort hook
			[
				function( $dummy, $title, $options, &$html, &$attribs, &$ret ) {
					$ret = 'blahblahblah';
					return false;
				},
				'blahblahblah'
			],

		];
	}

	/**
	 * @covers MediaWiki\Linker\LinkRenderer::buildAElement
	 * @dataProvider provideLinkEndHook
	 */
	public function testLinkEndHook( $callback, $expected ) {
		$this->setMwGlobals( [
			'wgArticlePath' => '/wiki/$1',
		] );

		$this->setMwGlobals( 'wgHooks', [ 'LinkEnd' => [ $callback ] ] );

		$title = SpecialPage::getTitleFor( 'Blankpage' );
		$out = Linker::link( $title );
		$this->assertEquals( $expected, $out );
	}

	/**
	 * @covers Linker::getLinkColour
	 */
	public function testGetLinkColour() {
		$this->hideDeprecated( 'Linker::getLinkColour' );
		$linkCache = MediaWikiServices::getInstance()->getLinkCache();
		$foobarTitle = Title::makeTitle( NS_MAIN, 'FooBar' );
		$redirectTitle = Title::makeTitle( NS_MAIN, 'Redirect' );
		$userTitle = Title::makeTitle( NS_USER, 'Someuser' );
		$linkCache->addGoodLinkObj(
			1, // id
			$foobarTitle,
			10, // len
			0 // redir
		);
		$linkCache->addGoodLinkObj(
			2, // id
			$redirectTitle,
			10, // len
			1 // redir
		);

		$linkCache->addGoodLinkObj(
			3, // id
			$userTitle,
			10, // len
			0 // redir
		);

		$this->assertEquals(
			'',
			Linker::getLinkColour( $foobarTitle, 0 )
		);

		$this->assertEquals(
			'stub',
			Linker::getLinkColour( $foobarTitle, 20 )
		);

		$this->assertEquals(
			'mw-redirect',
			Linker::getLinkColour( $redirectTitle, 0 )
		);

		$this->assertEquals(
			'',
			Linker::getLinkColour( $userTitle, 20 )
		);
	}
}
