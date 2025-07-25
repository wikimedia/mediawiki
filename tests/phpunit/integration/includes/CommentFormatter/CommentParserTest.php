<?php

namespace MediaWiki\Tests\Integration\CommentFormatter;

use LinkCacheTestTrait;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\CommentFormatter\CommentParser;
use MediaWiki\CommentFormatter\CommentParserFactory;
use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Config\SiteConfiguration;
use MediaWiki\Context\RequestContext;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\MainConfigNames;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use MediaWiki\Title\Title;

/**
 * @group Database
 * @covers \MediaWiki\CommentFormatter\CommentParser
 * @group Database
 */
class CommentParserTest extends \MediaWikiIntegrationTestCase {
	use DummyServicesTrait;
	use LinkCacheTestTrait;

	/**
	 * @return RepoGroup
	 */
	private function getRepoGroup() {
		$repoGroup = $this->createNoOpMock( RepoGroup::class, [ 'findFiles' ] );
		$repoGroup->method( 'findFiles' )->willReturn( [] );
		return $repoGroup;
	}

	private function getParser() {
		$services = $this->getServiceContainer();
		return new CommentParser(
			$services->getLinkRenderer(),
			$services->getLinkBatchFactory(),
			$services->getLinkCache(),
			$this->getRepoGroup(),
			$services->getContentLanguage(),
			$services->getContentLanguage(),
			$services->getTitleParser(),
			$services->getNamespaceInfo(),
			$services->getHookContainer()
		);
	}

	private function getFormatter() {
		$parserFactory = $this->createNoOpMock( CommentParserFactory::class, [ 'create' ] );
		$parserFactory->method( 'create' )->willReturnCallback( function () {
			return $this->getParser();
		} );
		return new CommentFormatter( $parserFactory );
	}

	/**
	 * @before
	 */
	public function interwikiSetUp() {
		$this->setService( 'InterwikiLookup', function () {
			return $this->getDummyInterwikiLookup( [
				'interwiki' => [
					'iw_prefix' => 'interwiki',
					'iw_url' => 'https://interwiki/$1',
				]
			] );
		} );
	}

	/**
	 * @before
	 */
	public function configSetUp() {
		$conf = new SiteConfiguration();
		$conf->settings = [
			'wgServer' => [
				'foowiki' => '//foo.example.org'
			],
			'wgArticlePath' => [
				'foowiki' => '/foo/$1',
			],
		];
		$conf->suffixes = [ 'wiki' ];
		$this->setMwGlobals( 'wgConf', $conf );
		$this->overrideConfigValues( [
			MainConfigNames::Script => '/w/index.php',
			MainConfigNames::ArticlePath => '/wiki/$1',
			MainConfigNames::CapitalLinks => true,
			MainConfigNames::LanguageCode => 'en',
		] );
	}

	public static function provideFormatComment() {
		return [
			// MediaWiki\CommentFormatter\CommentFormatter::format
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
			// MediaWiki\CommentFormatter\CommentParser::doSectionLinks
			[
				'<span class="autocomment"><a href="/wiki/Special:BlankPage#autocomment" title="Special:BlankPage">→<bdi dir="ltr">autocomment</bdi></a></span>',
				"/* autocomment */",
			],
			[
				'<span class="autocomment"><a href="/wiki/Special:BlankPage#linkie.3F" title="Special:BlankPage">→<bdi dir="ltr">&#91;[linkie?]]</bdi></a></span>',
				"/* [[linkie?]] */",
			],
			[
				'<span class="autocomment">: </span> // Edit via via',
				// Regression test for T222857
				"/*  */ // Edit via via",
			],
			[
				'<span class="autocomment">: </span> foobar',
				// Regression test for T222857
				"/**/ foobar",
			],
			[
				'<span class="autocomment"><a href="/wiki/Special:BlankPage#autocomment" title="Special:BlankPage">→<bdi dir="ltr">autocomment</bdi></a>: </span> post',
				"/* autocomment */ post",
			],
			[
				'pre <span class="autocomment"><a href="/wiki/Special:BlankPage#autocomment" title="Special:BlankPage">→<bdi dir="ltr">autocomment</bdi></a></span>',
				"pre /* autocomment */",
			],
			[
				'pre <span class="autocomment"><a href="/wiki/Special:BlankPage#autocomment" title="Special:BlankPage">→<bdi dir="ltr">autocomment</bdi></a>: </span> post',
				"pre /* autocomment */ post",
			],
			[
				'<span class="autocomment"><a href="/wiki/Special:BlankPage#autocomment" title="Special:BlankPage">→<bdi dir="ltr">autocomment</bdi></a>: </span> multiple? <span class="autocomment"><a href="/wiki/Special:BlankPage#autocomment2" title="Special:BlankPage">→<bdi dir="ltr">autocomment2</bdi></a></span>',
				"/* autocomment */ multiple? /* autocomment2 */",
			],
			[
				'<span class="autocomment"><a href="/wiki/Special:BlankPage#autocomment_containing_.2F.2A" title="Special:BlankPage">→<bdi dir="ltr">autocomment containing /*</bdi></a>: </span> T70361',
				"/* autocomment containing /* */ T70361"
			],
			[
				'<span class="autocomment"><a href="/wiki/Special:BlankPage#autocomment_containing_.22quotes.22" title="Special:BlankPage">→<bdi dir="ltr">autocomment containing &quot;quotes&quot;</bdi></a></span>',
				"/* autocomment containing \"quotes\" */"
			],
			[
				'<span class="autocomment"><a href="/wiki/Special:BlankPage#autocomment_containing_.3Cscript.3Etags.3C.2Fscript.3E" title="Special:BlankPage">→<bdi dir="ltr">autocomment containing &lt;script&gt;tags&lt;/script&gt;</bdi></a></span>',
				"/* autocomment containing <script>tags</script> */"
			],
			[
				'<span class="autocomment"><a href="#autocomment">→<bdi dir="ltr">autocomment</bdi></a></span>',
				"/* autocomment */",
				false, true
			],
			[
				'<span class="autocomment">autocomment</span>',
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
				'<span class="autocomment">[[</span>',
				"/* [[ */",
				false, true
			],
			[
				'<span class="autocomment">[[</span>',
				"/* [[ */",
				null
			],
			[
				"foo <span class=\"autocomment\"><a href=\"#.23\">→<bdi dir=\"ltr\">&#91;[#_\t_]]</bdi></a></span>",
				"foo /* [[#_\t_]] */",
				false, true
			],
			[
				"foo <span class=\"autocomment\"><a href=\"#_.09\">#_\t_</a></span>",
				"foo /* [[#_\t_]] */",
				null
			],
			[
				'<span class="autocomment"><a href="/wiki/Special:BlankPage#autocomment" title="Special:BlankPage">→<bdi dir="ltr">autocomment</bdi></a></span>',
				"/* autocomment */",
				false, false
			],
			[
				'<span class="autocomment"><a class="external" rel="nofollow" href="//foo.example.org/foo/Special:BlankPage#autocomment">→<bdi dir="ltr">autocomment</bdi></a></span>',
				"/* autocomment */",
				false, false, 'foowiki'
			],
			// MediaWiki\CommentFormatter\CommentParser::doWikiLinks
			[
				'abc <a href="/w/index.php?title=Link&amp;action=edit&amp;redlink=1" class="new" title="Link (page does not exist)">link</a> def',
				"abc [[link]] def",
			],
			[
				'abc <a href="/w/index.php?title=Link&amp;action=edit&amp;redlink=1" class="new" title="Link (page does not exist)">text</a> def',
				"abc [[link|text]] def",
			],
			[
				'abc <a href="/wiki/Special:BlankPage" title="Special:BlankPage">Special:BlankPage</a> def',
				"abc [[Special:BlankPage|]] def",
			],
			[
				'abc <a href="/w/index.php?title=%C4%84%C5%9B%C5%BC&amp;action=edit&amp;redlink=1" class="new" title="Ąśż (page does not exist)">ąśż</a> def',
				"abc [[%C4%85%C5%9B%C5%BC]] def",
			],
			[
				'abc <a href="/wiki/Special:BlankPage#section" title="Special:BlankPage">#section</a> def',
				"abc [[#section]] def",
			],
			[
				'abc <a href="/w/index.php?title=/subpage&amp;action=edit&amp;redlink=1" class="new" title="/subpage (page does not exist)">/subpage</a> def',
				"abc [[/subpage]] def",
			],
			[
				'abc <a href="/w/index.php?title=%22evil!%22&amp;action=edit&amp;redlink=1" class="new" title="&quot;evil!&quot; (page does not exist)">&quot;evil!&quot;</a> def',
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
				'abc <a href="/w/index.php?title=Link&amp;action=edit&amp;redlink=1" class="new" title="Link (page does not exist)">link</a> def',
				"abc [[link]] def",
				false, false
			],
			[
				'abc <a class="external" rel="nofollow" href="//foo.example.org/foo/Link">link</a> def',
				"abc [[link]] def",
				false, false, 'foowiki'
			],
			[
				'<a href="/w/index.php?title=Special:Upload&amp;wpDestFile=LinkerTest.jpg" class="new" title="LinkerTest.jpg">Media:LinkerTest.jpg</a>',
				'[[Media:LinkerTest.jpg]]'
			],
			[
				'<a href="/wiki/Special:BlankPage" title="Special:BlankPage">Special:BlankPage</a>',
				'[[:Special:BlankPage]]'
			],
			[
				'<a href="/w/index.php?title=Link&amp;action=edit&amp;redlink=1" class="new" title="Link (page does not exist)">linktrail</a>...',
				'[[link]]trail...'
			],
			[
				'<a href="/wiki/Present" title="Present">Present</a>',
				'[[Present]]',
			],
			[
				'<a href="https://interwiki/Some_page" class="extiw" title="interwiki:Some page">interwiki:Some page</a>',
				'[[interwiki:Some page]]',
			],
			[
				'<a href="https://interwiki/Present" class="extiw" title="interwiki:Present">interwiki:Present</a> <a href="/wiki/Present" title="Present">Present</a>',
				'[[interwiki:Present]] [[Present]]'
			]
		];
		// phpcs:enable
	}

	/**
	 * @dataProvider provideFormatComment
	 */
	public function testFormatComment(
		$expected, $comment, $title = false, $local = false, $wikiId = null
	) {
		$conf = new SiteConfiguration();
		$conf->settings = [
			'wgServer' => [
				'foowiki' => '//foo.example.org',
			],
			'wgArticlePath' => [
				'foowiki' => '/foo/$1',
			],
		];
		$conf->suffixes = [ 'wiki' ];

		$this->setMwGlobals( 'wgConf', $conf );
		$this->overrideConfigValues( [
			MainConfigNames::Script => '/w/index.php',
			MainConfigNames::ArticlePath => '/wiki/$1',
			MainConfigNames::CapitalLinks => true,
			// TODO: update tests when the default changes
			MainConfigNames::FragmentMode => [ 'legacy' ],
			MainConfigNames::LanguageCode => 'en',
		] );

		$this->addGoodLinkObject( 1, Title::makeTitle( NS_MAIN, 'Present' ) );

		if ( $title === false ) {
			// We need a page title that exists
			$title = Title::makeTitle( NS_SPECIAL, 'BlankPage' );
		}

		$parser = $this->getParser();
		$result = $parser->finalize(
			$parser->preprocess(
				$comment,
				$title,
				$local,
				$wikiId
			)
		);

		$this->assertEquals( $expected, $result );
	}

	public static function provideFormatLinksInComment() {
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
				'[[Foo<a href="/wiki/Special:BlankPage" title="Special:BlankPage">Special:BlankPage</a>',
				'[[Foo[[Special:BlankPage]]',
				null,
			],
			[
				'<a class="external" rel="nofollow" href="//foo.example.org/foo/Foo%27bar">Foo&#039;bar</a>',
				"[[Foo'bar]]",
				'foowiki',
			],
			[
				'<a class="external" rel="nofollow" href="//foo.example.org/foo/Foo$100bar">Foo$100bar</a>',
				'[[Foo$100bar]]',
				'foowiki',
			],
			[
				'foo bar <a class="external" rel="nofollow" href="//foo.example.org/foo/Special:BlankPage">Special:BlankPage</a>',
				'foo bar [[Special:BlankPage]]',
				'foowiki',
			],
			[
				'foo bar <a class="external" rel="nofollow" href="//foo.example.org/foo/File:Example">Image:Example</a>',
				'foo bar [[Image:Example]]',
				'foowiki',
			],
		];
		// phpcs:enable
	}

	/**
	 * @covers \MediaWiki\CommentFormatter\CommentFormatter
	 * @covers \MediaWiki\CommentFormatter\CommentParser
	 * @dataProvider provideCommentBlock
	 */
	public function testCommentBlock(
		$expected, $comment, $title = null, $local = false, $wikiId = null, $useParentheses = true
	) {
		$conf = new SiteConfiguration();
		$conf->settings = [
			'wgServer' => [
				'foowiki' => '//foo.example.org'
			],
			'wgArticlePath' => [
				'foowiki' => '/foo/$1',
			],
		];
		$conf->suffixes = [ 'wiki' ];
		$this->setMwGlobals( 'wgConf', $conf );
		$this->overrideConfigValues( [
			MainConfigNames::Script => '/w/index.php',
			MainConfigNames::ArticlePath => '/wiki/$1',
			MainConfigNames::CapitalLinks => true,
		] );

		$formatter = $this->getFormatter();
		$this->assertEquals(
			$expected,
			$formatter->formatBlock( $comment, $title, $local, $wikiId, $useParentheses )
		);
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
				' <span class="comment">(<a href="/w/index.php?title=Test&amp;action=edit&amp;redlink=1" class="new" title="Test (page does not exist)">Test</a>)</span>',
				'[[Test]]'
			],
			'Link to other page section' => [
				' <span class="comment">(<a href="/wiki/Special:BlankPage#Test" title="Special:BlankPage">#Test</a>)</span>',
				'[[#Test]]',
				Title::makeTitle( NS_SPECIAL, 'BlankPage' )
			],
			'$local is true' => [
				' <span class="comment">(<a href="#Test">#Test</a>)</span>',
				'[[#Test]]',
				Title::makeTitle( NS_SPECIAL, 'BlankPage' ),
				true
			],
			'Given wikiId' => [
				' <span class="comment">(<a class="external" rel="nofollow" href="//foo.example.org/foo/Test">Test</a>)</span>',
				'[[Test]]',
				null, false,
				'foowiki'
			],
			'Section link to external wiki page' => [
				' <span class="comment">(<a class="external" rel="nofollow" href="//foo.example.org/foo/Special:BlankPage#Test">#Test</a>)</span>',
				'[[#Test]]',
				Title::makeTitle( NS_SPECIAL, 'BlankPage' ),
				false,
				'foowiki'
			],
		];
	}

	/**
	 * Note that we test the new HTML escaping variant.
	 *
	 * @dataProvider provideFormatLinksInComment
	 */
	public function testFormatLinksInComment( $expected, $input, $wiki ) {
		$parser = $this->getParser();
		$title = Title::makeTitle( NS_SPECIAL, 'BlankPage' );
		$result = $parser->finalize(
			$parser->preprocess(
				$input, $title, false, $wiki, false
			)
		);

		$this->assertEquals( $expected, $result );
	}

	public function testLinkCacheInteraction() {
		$services = $this->getServiceContainer();
		$present = $this->getExistingTestPage( 'Present' )->getTitle();
		$absent = $this->getNonexistingTestPage( 'Absent' )->getTitle();

		$parser = $this->getParser();
		$linkCache = $services->getLinkCache();
		$result = $parser->finalize( [
			$parser->preprocess( "[[$present]]" ),
			$parser->preprocess( "[[$absent]]" )
		] );
		$expected = [
			'<a href="/wiki/Present" title="Present">Present</a>',
			'<a href="/w/index.php?title=Absent&amp;action=edit&amp;redlink=1" class="new" title="Absent (page does not exist)">Absent</a>'
		];
		$this->assertSame( $expected, $result );
		$this->assertGreaterThan( 0, $linkCache->getGoodLinkID( $present ) );
		$this->assertTrue( $linkCache->isBadLink( $absent ) );

		// Run the comment batch again and confirm that LinkBatch does not need
		// to execute a query. This is a CommentParser responsibility since
		// LinkBatch does not provide a transparent read-through cache.
		// TODO: Generic $this->assertQueryCount() would do the job.
		$parser = new CommentParser(
			$services->getLinkRenderer(),
			$services->getLinkBatchFactory(),
			$linkCache,
			$this->getRepoGroup(),
			$services->getContentLanguage(),
			$services->getContentLanguage(),
			$services->getTitleParser(),
			$services->getNamespaceInfo(),
			$services->getHookContainer()
		);
		$result = $parser->finalize( [
			$parser->preprocess( "[[$present]]" ),
			$parser->preprocess( "[[$absent]]" )
		] );
		$this->assertSame( $expected, $result );
	}

	/**
	 * Regression test for T300311
	 */
	public function testInterwikiLinkCachePollution() {
		$present = $this->getExistingTestPage( 'Template:Present' )->getTitle();

		$this->getServiceContainer()->getLinkCache()->clear();
		$parser = $this->getParser();
		$result = $parser->finalize(
			$parser->preprocess( "[[interwiki:$present]] [[$present]]" )
		);
		$this->assertSame(
			"<a href=\"https://interwiki/$present\" class=\"extiw\" title=\"interwiki:$present\">interwiki:$present</a> <a href=\"/wiki/$present\" title=\"$present\">$present</a>",
			$result
		);
	}

	/**
	 * Regression test for T293665
	 */
	public function testAlwaysKnownPages() {
		$this->setTemporaryHook( 'TitleIsAlwaysKnown',
			static function ( $target, &$isKnown ) {
				$isKnown = $target->getText() == 'AlwaysKnownFoo';
			}
		);

		$title = Title::makeTitle( NS_USER, 'AlwaysKnownFoo' );
		$this->assertFalse( $title->exists() );

		$parser = $this->getParser();
		$result = $parser->finalize( $parser->preprocess( 'test [[User:AlwaysKnownFoo]]' ) );

		$this->assertSame(
			'test <a href="/wiki/User:AlwaysKnownFoo" title="User:AlwaysKnownFoo">User:AlwaysKnownFoo</a>',
			$result
		);
	}

	/**
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

		$formatter = $this->getFormatter();
		$authority = RequestContext::getMain()->getAuthority();
		$this->assertEquals( $expected, $formatter->formatRevision( $revisionRecord, $authority, $local, $isPublic, $useParentheses ) );
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
