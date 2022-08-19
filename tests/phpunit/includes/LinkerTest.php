<?php

use MediaWiki\MainConfigNames;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;

/**
 * @group Database
 */
class LinkerTest extends MediaWikiLangTestCase {
	/**
	 * @dataProvider provideCasesForUserLink
	 * @covers Linker::userLink
	 */
	public function testUserLink( $expected, $userId, $userName, $altUserName = false, $msg = '' ) {
		$this->overrideConfigValue( MainConfigNames::ArticlePath, '/wiki/$1' );

		// We'd also test the warning, but injecting a mock logger into a static method is tricky.
		if ( !$userName ) {
			$actual = @Linker::userLink( $userId, $userName, $altUserName );
		} else {
			$actual = Linker::userLink( $userId, $userName, $altUserName );
		}

		$this->assertEquals( $expected, $actual, $msg );
	}

	public static function provideCasesForUserLink() {
		# Format:
		# - expected
		# - userid
		# - username
		# - optional altUserName
		# - optional message
		return [
			# Empty name (T222529)
			'Empty username, userid 0' => [ '(no username available)', 0, '' ],
			'Empty username, userid > 0' => [ '(no username available)', 73, '' ],

			'false instead of username' => [ '(no username available)', 73, false ],
			'null instead of username' => [ '(no username available)', 0, null ],

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

			# IP ranges
			[
				'<a href="/wiki/Special:Contributions/1.2.3.4/31" '
					. 'class="mw-userlink mw-anonuserlink" '
					. 'title="Special:Contributions/1.2.3.4/31"><bdi>1.2.3.4/31</bdi></a>',
				0, '1.2.3.4/31', false,
				'Anonymous with IPv4 range'
			],
			[
				'<a href="/wiki/Special:Contributions/2001:db8::1/43" '
					. 'class="mw-userlink mw-anonuserlink" '
					. 'title="Special:Contributions/2001:db8::1/43"><bdi>2001:db8::1/43</bdi></a>',
				0, '2001:db8::1/43', false,
				'Anonymous with IPv6 range'
			],

			# External (imported) user, unknown prefix
			[
				'<span class="mw-userlink mw-extuserlink mw-anonuserlink"><bdi>acme&gt;Alice</bdi></span>',
				0, "acme>Alice", false,
				'User from acme wiki'
			],

			# Corrupt user names
			[
				"<span class=\"mw-userlink mw-anonuserlink\"><bdi>Foo\nBar</bdi></span>",
				0, "Foo\nBar", false,
				'User name with line break'
			],
			[
				'<span class="mw-userlink mw-anonuserlink"><bdi>Barf_</bdi></span>',
				0, "Barf_", false,
				'User name with trailing underscore'
			],
			[
				'<span class="mw-userlink mw-anonuserlink"><bdi>abcd</bdi></span>',
				0, "abcd", false,
				'Lower case user name'
			],
			[
				'<span class="mw-userlink mw-anonuserlink"><bdi>For/Bar</bdi></span>',
				0, "For/Bar", false,
				'User name with slash'
			],
			[
				'<span class="mw-userlink mw-anonuserlink"><bdi>For#Bar</bdi></span>',
				0, "For#Bar", false,
				'User name with hash'
			],

			# ## Regular user ##########################################
			# TODO!
		];
	}

	/**
	 * @dataProvider provideUserToolLinks
	 * @covers Linker::userToolLinks
	 * @param string $expected
	 * @param int $userId
	 * @param string $userText
	 */
	public function testUserToolLinks( $expected, $userId, $userText ) {
		// We'd also test the warning, but injecting a mock logger into a static method is tricky.
		if ( $userText === '' ) {
			$actual = @Linker::userToolLinks( $userId, $userText );
		} else {
			$actual = Linker::userToolLinks( $userId, $userText );
		}

		$this->assertSame( $expected, $actual );
	}

	public static function provideUserToolLinks() {
		return [
			// Empty name (T222529)
			'Empty username, userid 0' => [ ' (no username available)', 0, '' ],
			'Empty username, userid > 0' => [ ' (no username available)', 73, '' ],
		];
	}

	/**
	 * @dataProvider provideUserTalkLink
	 * @covers Linker::userTalkLink
	 * @param string $expected
	 * @param int $userId
	 * @param string $userText
	 */
	public function testUserTalkLink( $expected, $userId, $userText ) {
		// We'd also test the warning, but injecting a mock logger into a static method is tricky.
		if ( $userText === '' ) {
			$actual = @Linker::userTalkLink( $userId, $userText );
		} else {
			$actual = Linker::userTalkLink( $userId, $userText );
		}

		$this->assertSame( $expected, $actual );
	}

	public static function provideUserTalkLink() {
		return [
			// Empty name (T222529)
			'Empty username, userid 0' => [ '(no username available)', 0, '' ],
			'Empty username, userid > 0' => [ '(no username available)', 73, '' ],
		];
	}

	/**
	 * @dataProvider provideBlockLink
	 * @covers Linker::blockLink
	 * @param string $expected
	 * @param int $userId
	 * @param string $userText
	 */
	public function testBlockLink( $expected, $userId, $userText ) {
		// We'd also test the warning, but injecting a mock logger into a static method is tricky.
		if ( $userText === '' ) {
			$actual = @Linker::blockLink( $userId, $userText );
		} else {
			$actual = Linker::blockLink( $userId, $userText );
		}

		$this->assertSame( $expected, $actual );
	}

	public static function provideBlockLink() {
		return [
			// Empty name (T222529)
			'Empty username, userid 0' => [ '(no username available)', 0, '' ],
			'Empty username, userid > 0' => [ '(no username available)', 73, '' ],
		];
	}

	/**
	 * @dataProvider provideEmailLink
	 * @covers Linker::emailLink
	 * @param string $expected
	 * @param int $userId
	 * @param string $userText
	 */
	public function testEmailLink( $expected, $userId, $userText ) {
		// We'd also test the warning, but injecting a mock logger into a static method is tricky.
		if ( $userText === '' ) {
			$actual = @Linker::emailLink( $userId, $userText );
		} else {
			$actual = Linker::emailLink( $userId, $userText );
		}

		$this->assertSame( $expected, $actual );
	}

	public static function provideEmailLink() {
		return [
			// Empty name (T222529)
			'Empty username, userid 0' => [ '(no username available)', 0, '' ],
			'Empty username, userid > 0' => [ '(no username available)', 73, '' ],
		];
	}

	/**
	 * @dataProvider provideCasesForFormatComment
	 * @covers Linker::formatComment
	 * @covers Linker::formatLinksInComment
	 * @covers \MediaWiki\CommentFormatter\CommentParser
	 * @covers \MediaWiki\CommentFormatter\CommentFormatter
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
		$this->setMwGlobals( 'wgConf', $conf );

		$this->overrideConfigValues( [
			MainConfigNames::Script => '/wiki/index.php',
			MainConfigNames::ArticlePath => '/wiki/$1',
			MainConfigNames::CapitalLinks => true,
			// TODO: update tests when the default changes
			MainConfigNames::FragmentMode => [ 'legacy' ],
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
				'<span dir="auto"><span class="autocomment">: </span> // Edit via via</span>',
				// Regression test for T222857
				"/*  */ // Edit via via",
			],
			[
				'<span dir="auto"><span class="autocomment">: </span> foobar</span>',
				// Regression test for T222857
				"/**/ foobar",
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
				'',
				"/* */",
				false, true
			],
			[
				'',
				"/* */",
				null
			],
			[
				'<span dir="auto"><span class="autocomment">[[</span></span>',
				"/* [[ */",
				false, true
			],
			[
				'<span dir="auto"><span class="autocomment">[[</span></span>',
				"/* [[ */",
				null
			],
			[
				"foo <span dir=\"auto\"><span class=\"autocomment\"><a href=\"#.23\">→‎&#91;[#_\t_]]</a></span></span>",
				"foo /* [[#_\t_]] */",
				false, true
			],
			[
				"foo <span dir=\"auto\"><span class=\"autocomment\"><a href=\"#_.09\">#_\t_</a></span></span>",
				"foo /* [[#_\t_]] */",
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
			[
				'<a href="/wiki/index.php?title=Special:Upload&amp;wpDestFile=LinkerTest.jpg" class="new" title="LinkerTest.jpg">Media:LinkerTest.jpg</a>',
				'[[Media:LinkerTest.jpg]]'
			],
			[
				'<a href="/wiki/Special:BlankPage" title="Special:BlankPage">Special:BlankPage</a>',
				'[[:Special:BlankPage]]'
			],
			[
				'<a href="/wiki/index.php?title=Link&amp;action=edit&amp;redlink=1" class="new" title="Link (page does not exist)">linktrail</a>...',
				'[[link]]trail...'
			]
		];
		// phpcs:enable
	}

	/**
	 * @covers Linker::formatLinksInComment
	 * @covers \MediaWiki\CommentFormatter\CommentParser
	 * @covers \MediaWiki\CommentFormatter\CommentFormatter
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
		$this->setMwGlobals( 'wgConf', $conf );
		$this->overrideConfigValues( [
			MainConfigNames::Script => '/wiki/index.php',
			MainConfigNames::ArticlePath => '/wiki/$1',
			MainConfigNames::CapitalLinks => true,
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
	public function testGenerateRollback( $rollbackEnabled, $expectedModules, $title ) {
		$context = RequestContext::getMain();
		$user = $context->getUser();
		$this->getServiceContainer()->getUserOptionsManager()->setOption(
			$user,
			'showrollbackconfirmation',
			$rollbackEnabled
		);

		$this->assertSame( 0, Title::newFromText( $title )->getArticleID() );
		$pageData = $this->insertPage( $title );
		$page = WikiPage::factory( $pageData['title'] );

		$summary = CommentStoreComment::newUnsavedComment( 'Some comment!' );
		$page->newPageUpdater( $user )
			->setContent(
				SlotRecord::MAIN,
				new TextContent( 'Technical Wishes 123!' )
			)
			->saveRevision( $summary );

		$rollbackOutput = Linker::generateRollback( $page->getRevisionRecord(), $context );
		$modules = $context->getOutput()->getModules();
		$currentRev = $page->getRevisionRecord();
		$revisionLookup = $this->getServiceContainer()->getRevisionLookup();
		$oldestRev = $revisionLookup->getFirstRevision( $page->getTitle() );

		$this->assertEquals( $expectedModules, $modules );
		$this->assertInstanceOf( RevisionRecord::class, $currentRev );
		$this->assertInstanceOf( User::class, $currentRev->getUser() );
		$this->assertEquals( $user->getName(), $currentRev->getUser()->getName() );
		$this->assertEquals(
			static::getTestSysop()->getUser(),
			$oldestRev->getUser()->getName()
		);

		$ids = [];
		$r = $oldestRev;
		while ( $r ) {
			$ids[] = $r->getId();
			$r = $revisionLookup->getNextRevision( $r );
		}
		$this->assertEquals( [ $oldestRev->getId(), $currentRev->getId() ], $ids );

		$this->assertStringContainsString( 'rollback 1 edit', $rollbackOutput );
	}

	public static function provideCasesForRollbackGeneration() {
		return [
			[
				true,
				[ 'mediawiki.misc-authed-curate' ],
				'Rollback_Test_Page'
			],
			[
				false,
				[],
				'Rollback_Test_Page2'
			]
		];
	}

	public static function provideCasesForFormatLinksInComment() {
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
				'<a href="/wiki/Special:BlankPage" title="Special:BlankPage">:Special:BlankPage</a>',
				'[[::Special:BlankPage]]',
				null,
			],
			[
				'[[Foo<a href="/wiki/Special:BlankPage" title="Special:BlankPage">Special:BlankPage</a>',
				'[[Foo[[Special:BlankPage]]',
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

	public static function provideTooltipAndAccesskeyAttribs() {
		return [
			'Watch no expiry' => [
				'ca-watch', [], null, [ 'title' => 'Add this page to your watchlist [w]', 'accesskey' => 'w' ]
			],
			'Key does not exist' => [
				'key-does-not-exist', [], null, []
			],
			'Unwatch no expiry' => [
				'ca-unwatch', [], null, [ 'title' => 'Remove this page from your watchlist [w]',
					'accesskey' => 'w' ]
			],
		];
	}

	/**
	 * @covers Linker::tooltipAndAccesskeyAttribs
	 * @dataProvider provideTooltipAndAccesskeyAttribs
	 */
	public function testTooltipAndAccesskeyAttribs( $name, $msgParams, $options, $expected ) {
		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, true );
		$user = $this->createMock( User::class );
		$user->method( 'isRegistered' )->willReturn( true );

		$title = SpecialPage::getTitleFor( 'Blankpage' );

		$context = RequestContext::getMain();
		$context->setTitle( $title );
		$context->setUser( $user );

		$watchedItemWithoutExpiry = new WatchedItem( $user, $title, null, null );

		$result = Linker::tooltipAndAccesskeyAttribs( $name, $msgParams, $options );

		$this->assertEquals( $expected, $result );
	}

	/**
	 * @covers Linker::commentBlock
	 * @dataProvider provideCommentBlock
	 */
	public function testCommentBlock(
		$expected, $comment, $title = null, $local = false, $wikiId = null, $useParentheses = true
	) {
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
		$this->setMwGlobals( 'wgConf', $conf );
		$this->overrideConfigValues( [
			MainConfigNames::Script => '/wiki/index.php',
			MainConfigNames::ArticlePath => '/wiki/$1',
			MainConfigNames::CapitalLinks => true,
		] );

		$this->assertEquals( $expected, Linker::commentBlock( $comment, $title, $local, $wikiId, $useParentheses ) );
	}

	public static function provideCommentBlock() {
		return [
			[
				' <span class="comment">(Test)</span>',
				'Test'
			],
			'Empty comment' => [ '', '' ],
			'Backwards compatibility empty comment' => [ '', '*' ],
			'No parenthesis' => [
				' <span class="comment comment--without-parentheses">Test</span>',
				'Test',
				null, false, null,
				false
			],
			'Page exist link' => [
				' <span class="comment">(<a href="/wiki/Special:BlankPage" title="Special:BlankPage">Special:BlankPage</a>)</span>',
				'[[Special:BlankPage]]'
			],
			'Page does not exist link' => [
				' <span class="comment">(<a href="/wiki/index.php?title=Test&amp;action=edit&amp;redlink=1" class="new" title="Test (page does not exist)">Test</a>)</span>',
				'[[Test]]'
			],
			'Link to other page section' => [
				' <span class="comment">(<a href="/wiki/Special:BlankPage#Test" title="Special:BlankPage">#Test</a>)</span>',
				'[[#Test]]',
				Title::newFromText( 'Special:BlankPage' )
			],
			'$local is true' => [
				' <span class="comment">(<a href="#Test">#Test</a>)</span>',
				'[[#Test]]',
				Title::newFromText( 'Special:BlankPage' ),
				true
			],
			'Given wikiId' => [
				' <span class="comment">(<a class="external" rel="nofollow" href="//en.example.org/w/Test">Test</a>)</span>',
				'[[Test]]',
				null, false,
				'enwiki'
			],
			'Section link to external wiki page' => [
				' <span class="comment">(<a class="external" rel="nofollow" href="//en.example.org/w/Special:BlankPage#Test">#Test</a>)</span>',
				'[[#Test]]',
				Title::newFromText( 'Special:BlankPage' ),
				false,
				'enwiki'
			],
		];
	}

	/**
	 * @covers Linker::revComment
	 * @dataProvider provideRevComment
	 */
	public function testRevComment(
		string $expected,
		bool $isSysop = false,
		int $visibility = 0,
		bool $local = false,
		bool $isPublic = false,
		bool $useParentheses = true,
		?string $comment = 'Some comment!'
	) {
		$pageData = $this->insertPage( 'RevCommentTestPage' );
		$revisionRecord = new MutableRevisionRecord( $pageData['title'] );
		if ( $comment ) {
			$revisionRecord->setComment( CommentStoreComment::newUnsavedComment( $comment ) );
		}
		$revisionRecord->setVisibility( $visibility );

		$context = RequestContext::getMain();
		$user = $isSysop ? $this->getTestSysop()->getUser() : $this->getTestUser()->getUser();
		$context->setUser( $user );

		$this->assertEquals( $expected, Linker::revComment( $revisionRecord, $local, $isPublic, $useParentheses ) );
	}

	public static function provideRevComment() {
		return [
			'Should be visible' => [
				' <span class="comment">(Some comment!)</span>'
			],
			'Should not have parenthesis' => [
				' <span class="comment comment--without-parentheses">Some comment!</span>',
				false, 0, false, false,
				false
			],
			'Should be empty' => [
				'',
				false, 0, false, false, true,
				null
			],
			'Deleted comment should not be visible to normal users' => [
				' <span class="history-deleted comment"> <span class="comment">(edit summary removed)</span></span>',
				false,
				RevisionRecord::DELETED_COMMENT
			],
			'Deleted comment should not be visible to normal users even if public' => [
				' <span class="history-deleted comment"> <span class="comment">(edit summary removed)</span></span>',
				false,
				RevisionRecord::DELETED_COMMENT,
				false,
				true
			],
			'Deleted comment should be visible to sysops' => [
				' <span class="history-deleted comment"> <span class="comment">(Some comment!)</span></span>',
				true,
				RevisionRecord::DELETED_COMMENT
			],
		];
	}
}
