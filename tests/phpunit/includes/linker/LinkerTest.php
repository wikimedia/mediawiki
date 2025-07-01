<?php

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\TextContent;
use MediaWiki\Context\RequestContext;
use MediaWiki\Linker\Linker;
use MediaWiki\MainConfigNames;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

/**
 * @group Database
 */
class LinkerTest extends MediaWikiLangTestCase {

	/**
	 * @dataProvider provideCasesForUserLink
	 * @covers \MediaWiki\Linker\Linker::userLink
	 */
	public function testUserLink( $expected, $userId, $userName, $altUserName = false, $msg = '' ) {
		$actual = Linker::userLink( $userId, $userName, $altUserName );

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
	 * @covers \MediaWiki\Linker\Linker::userToolLinks
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
	 * @dataProvider provideUserLink
	 * @covers \MediaWiki\Linker\Linker::userTalkLink
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

	/**
	 * @dataProvider provideUserLink
	 * @covers \MediaWiki\Linker\Linker::blockLink
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

	/**
	 * @dataProvider provideUserLink
	 * @covers \MediaWiki\Linker\Linker::emailLink
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

	public static function provideUserLink() {
		return [
			// Empty name (T222529)
			'Empty username, userid 0' => [ '(no username available)', 0, '' ],
			'Empty username, userid > 0' => [ '(no username available)', 73, '' ],
		];
	}

	/**
	 * @covers \MediaWiki\Linker\Linker::generateRollback
	 * @dataProvider provideCasesForRollbackGeneration
	 */
	public function testGenerateRollback( $rollbackEnabled, $expectedModules, $title ) {
		$context = RequestContext::getMain();
		$user = $this->getTestUser()->getUser();
		$context->setUser( $user );
		$this->getServiceContainer()->getUserOptionsManager()->setOption(
			$user,
			'showrollbackconfirmation',
			$rollbackEnabled
		);

		$this->assertSame( 0, Title::newFromText( $title )->getArticleID() );
		$pageData = $this->insertPage( $title );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $pageData['title'] );

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
	 * @covers \MediaWiki\Linker\Linker::tooltipAndAccesskeyAttribs
	 * @dataProvider provideTooltipAndAccesskeyAttribs
	 */
	public function testTooltipAndAccesskeyAttribs( $name, $msgParams, $options, $expected ) {
		$this->overrideConfigValue( MainConfigNames::WatchlistExpiry, true );
		$user = $this->getTestUser()->getUser();

		$title = SpecialPage::getTitleFor( 'Blankpage' );

		$context = RequestContext::getMain();
		$context->setTitle( $title );
		$context->setUser( $user );

		$result = Linker::tooltipAndAccesskeyAttribs( $name, $msgParams, $options );

		$this->assertEquals( $expected, $result );
	}

	/**
	 * @covers \MediaWiki\Linker\Linker::specialLink
	 * @dataProvider provideSpecialLink
	 */
	public function testSpecialLink( $expected, $target, $key = null ) {
		$this->overrideConfigValues( [
			MainConfigNames::Script => '/w/index.php',
			MainConfigNames::ArticlePath => '/wiki/$1',
		] );

		$this->assertEquals( $expected, Linker::specialLink( $target, $key ) );
	}

	public static function provideSpecialLink() {
		yield 'Recent Changes' => [
			'<a href="/wiki/Special:RecentChanges" title="Special:RecentChanges">Recent changes</a>',
			'Recentchanges'
		];

		yield 'Recent Changes, only for a given tag' => [
			'<a href="/w/index.php?title=Special:RecentChanges&amp;tagfilter=blanking" title="Special:RecentChanges">Recent changes</a>',
			'Recentchanges?tagfilter=blanking'
		];

		yield 'Contributions' => [
			'<a href="/wiki/Special:Contributions" title="Special:Contributions">User contributions</a>',
			'Contributions'
		];

		yield 'Contributions, custom key' => [
			'<a href="/wiki/Special:Contributions" title="Special:Contributions">⧼made-up-display-key⧽</a>',
			'Contributions',
			'made-up-display-key'
		];

		yield 'Contributions, targetted' => [
			'<a href="/wiki/Special:Contributions/JohnDoe" class="mw-userlink" title="Special:Contributions/JohnDoe">User contributions</a>',
			'Contributions/JohnDoe'
		];

		yield 'Contributions, targetted, topOnly' => [
			'<a href="/w/index.php?title=Special:Contributions/JohnDoe&amp;topOnly=1" class="mw-userlink" title="Special:Contributions/JohnDoe">User contributions</a>',
			'Contributions/JohnDoe?topOnly=1'
		];

		yield 'Userlogin' => [
			'<a href="/wiki/Special:UserLogin" title="Special:UserLogin">Log in</a>',
			'Userlogin',
			'login'
		];

		yield 'Userlogin, returnto' => [
			'<a href="/w/index.php?title=Special:UserLogin&amp;returnto=Main+Page" title="Special:UserLogin">Log in</a>',
			'Userlogin?returnto=Main+Page',
			'login'
		];

		yield 'Userlogin, targetted' => [
			// Note that this special page doesn't have any support for and doesn't do anything with
			// the subtitle; this is here as demonstration that Linker doesn't care.
			'<a href="/wiki/Special:UserLogin/JohnDoe" title="Special:UserLogin/JohnDoe">Log in</a>',
			'Userlogin/JohnDoe',
			'login'
		];
	}
}
