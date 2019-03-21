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

		// phpcs:disable Generic.Files.LineLength
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
				'<span dir="auto"><span class="autocomment"><a href="/wiki/Special:BlankPage#autocomment" title="Special:BlankPage">→‎autocomment</a></span></span>',
				"/* autocomment */",
			],
			[
				'<span dir="auto"><span class="autocomment"><a href="/wiki/Special:BlankPage#linkie.3F" title="Special:BlankPage">→‎&#91;[linkie?]]</a></span></span>',
				"/* [[linkie?]] */",
			],
			[
				'<span dir="auto"><span class="autocomment"><a href="/wiki/Special:BlankPage#autocomment" title="Special:BlankPage">→‎autocomment</a>: </span> post</span>',
				"/* autocomment */ post",
			],
			[
				'pre <span dir="auto"><span class="autocomment"><a href="/wiki/Special:BlankPage#autocomment" title="Special:BlankPage">→‎autocomment</a></span></span>',
				"pre /* autocomment */",
			],
			[
				'pre <span dir="auto"><span class="autocomment"><a href="/wiki/Special:BlankPage#autocomment" title="Special:BlankPage">→‎autocomment</a>: </span> post</span>',
				"pre /* autocomment */ post",
			],
			[
				'<span dir="auto"><span class="autocomment"><a href="/wiki/Special:BlankPage#autocomment" title="Special:BlankPage">→‎autocomment</a>: </span> multiple? <span dir="auto"><span class="autocomment"><a href="/wiki/Special:BlankPage#autocomment2" title="Special:BlankPage">→‎autocomment2</a></span></span></span>',
				"/* autocomment */ multiple? /* autocomment2 */",
			],
			[
				'<span dir="auto"><span class="autocomment"><a href="/wiki/Special:BlankPage#autocomment_containing_.2F.2A" title="Special:BlankPage">→‎autocomment containing /*</a>: </span> T70361</span>',
				"/* autocomment containing /* */ T70361"
			],
			[
				'<span dir="auto"><span class="autocomment"><a href="/wiki/Special:BlankPage#autocomment_containing_.22quotes.22" title="Special:BlankPage">→‎autocomment containing &quot;quotes&quot;</a></span></span>',
				"/* autocomment containing \"quotes\" */"
			],
			[
				'<span dir="auto"><span class="autocomment"><a href="/wiki/Special:BlankPage#autocomment_containing_.3Cscript.3Etags.3C.2Fscript.3E" title="Special:BlankPage">→‎autocomment containing &lt;script&gt;tags&lt;/script&gt;</a></span></span>',
				"/* autocomment containing <script>tags</script> */"
			],
			[
				'<span dir="auto"><span class="autocomment"><a href="#autocomment">→‎autocomment</a></span></span>',
				"/* autocomment */",
				false, true
			],
			[
				'<span dir="auto"><span class="autocomment">autocomment</span></span>',
				"/* autocomment */",
				null
			],
			[
				'<span dir="auto"><span class="autocomment"><a href="/wiki/Special:BlankPage#autocomment" title="Special:BlankPage">→‎autocomment</a></span></span>',
				"/* autocomment */",
				false, false
			],
			[
				'<span dir="auto"><span class="autocomment"><a class="external" rel="nofollow" href="//en.example.org/w/Special:BlankPage#autocomment">→‎autocomment</a></span></span>',
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
		// phpcs:enable
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

	/**
	 * @covers Linker::generateRollback
	 * @dataProvider provideCasesForRollbackGeneration
	 */
	public function testGenerateRollback( $rollbackEnabled, $expectedModules ) {
		$this->markTestSkippedIfDbType( 'postgres' );

		$context = RequestContext::getMain();
		$user = $context->getUser();
		$user->setOption( 'showrollbackconfirmation', $rollbackEnabled );

		$pageData = $this->insertPage( 'Rollback_Test_Page' );
		$page = WikiPage::factory( $pageData['title'] );

		$updater = $page->newPageUpdater( $user );
		$updater->setContent( \MediaWiki\Revision\SlotRecord::MAIN,
			new TextContent( 'Technical Wishes 123!' )
		);
		$summary = CommentStoreComment::newUnsavedComment( 'Some comment!' );
		$updater->saveRevision( $summary );

		$rollbackOutput = Linker::generateRollback( $page->getRevision(), $context );
		$modules = $context->getOutput()->getModules();

		$this->assertEquals( $expectedModules, $modules );
		$this->assertContains( 'rollback 1 edit', $rollbackOutput );
	}

	public static function provideCasesForRollbackGeneration() {
		return [
			[
				true,
				[ 'mediawiki.page.rollback.confirmation' ]

			],
			[
				false,
				[]
			]
		];
	}

	public static function provideCasesForFormatLinksInComment() {
		// phpcs:disable Generic.Files.LineLength
		return [
			[
				'foo bar <a href="/wiki/Special:BlankPage" title="Special:BlankPage">Special:BlankPage</a>',
				'foo bar [[Special:BlankPage]]',
				null,
			],
			[
				'<a href="/wiki/Special:BlankPage" title="Special:BlankPage">Special:BlankPage</a>',
				'[[ :Special:BlankPage]]',
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
			[
				'foo bar <a class="external" rel="nofollow" href="//en.example.org/w/File:Example">Image:Example</a>',
				'foo bar [[Image:Example]]',
				'enwiki',
			],
		];
		// phpcs:enable
	}

	public static function provideLinkBeginHook() {
		// phpcs:disable Generic.Files.LineLength
		return [
			// Modify $html
			[
				function ( $dummy, $title, &$html, &$attribs, &$query, &$options, &$ret ) {
					$html = 'foobar';
				},
				'<a href="/wiki/Special:BlankPage" title="Special:BlankPage">foobar</a>'
			],
			// Modify $attribs
			[
				function ( $dummy, $title, &$html, &$attribs, &$query, &$options, &$ret ) {
					$attribs['bar'] = 'baz';
				},
				'<a href="/wiki/Special:BlankPage" title="Special:BlankPage" bar="baz">Special:BlankPage</a>'
			],
			// Modify $query
			[
				function ( $dummy, $title, &$html, &$attribs, &$query, &$options, &$ret ) {
					$query['bar'] = 'baz';
				},
				'<a href="/w/index.php?title=Special:BlankPage&amp;bar=baz" title="Special:BlankPage">Special:BlankPage</a>'
			],
			// Force HTTP $options
			[
				function ( $dummy, $title, &$html, &$attribs, &$query, &$options, &$ret ) {
					$options = [ 'http' ];
				},
				'<a href="http://example.org/wiki/Special:BlankPage" title="Special:BlankPage">Special:BlankPage</a>'
			],
			// Force 'forcearticlepath' in $options
			[
				function ( $dummy, $title, &$html, &$attribs, &$query, &$options, &$ret ) {
					$options = [ 'forcearticlepath' ];
					$query['foo'] = 'bar';
				},
				'<a href="/wiki/Special:BlankPage?foo=bar" title="Special:BlankPage">Special:BlankPage</a>'
			],
			// Abort early
			[
				function ( $dummy, $title, &$html, &$attribs, &$query, &$options, &$ret ) {
					$ret = 'foobar';
					return false;
				},
				'foobar'
			],
		];
		// phpcs:enable
	}

	/**
	 * @covers MediaWiki\Linker\LinkRenderer::runLegacyBeginHook
	 * @dataProvider provideLinkBeginHook
	 */
	public function testLinkBeginHook( $callback, $expected ) {
		$this->hideDeprecated( 'LinkBegin hook (used in hook-LinkBegin-closure)' );
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
				function ( $dummy, $title, $options, &$html, &$attribs, &$ret ) {
					$html = 'foobar';
				},
				'<a href="/wiki/Special:BlankPage" title="Special:BlankPage">foobar</a>'
			],
			// Modify $attribs
			[
				function ( $dummy, $title, $options, &$html, &$attribs, &$ret ) {
					$attribs['bar'] = 'baz';
				},
				'<a href="/wiki/Special:BlankPage" title="Special:BlankPage" bar="baz">Special:BlankPage</a>'
			],
			// Fully override return value and abort hook
			[
				function ( $dummy, $title, $options, &$html, &$attribs, &$ret ) {
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
		$this->hideDeprecated( 'LinkEnd hook (used in hook-LinkEnd-closure)' );
		$this->setMwGlobals( [
			'wgArticlePath' => '/wiki/$1',
		] );

		$this->setMwGlobals( 'wgHooks', [ 'LinkEnd' => [ $callback ] ] );

		$title = SpecialPage::getTitleFor( 'Blankpage' );
		$out = Linker::link( $title );
		$this->assertEquals( $expected, $out );
	}
}
