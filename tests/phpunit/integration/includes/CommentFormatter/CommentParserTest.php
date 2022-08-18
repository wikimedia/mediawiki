<?php

namespace MediaWiki\Tests\Integration\CommentFormatter;

use LinkCacheTestTrait;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\CommentParser;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Tests\Unit\DummyServicesTrait;
use SiteConfiguration;
use Title;

/**
 * @group Database
 * @covers \MediaWiki\CommentFormatter\CommentParser
 */
class CommentParserTest extends \MediaWikiIntegrationTestCase {
	use DummyServicesTrait;
	use LinkCacheTestTrait;

	private function getParser() {
		$services = $this->getServiceContainer();
		return new CommentParser(
			$services->getLinkRenderer(),
			$services->getLinkBatchFactory(),
			$services->getLinkCache(),
			$services->getRepoGroup(),
			$services->getContentLanguage(),
			$services->getContentLanguage(),
			$services->getTitleParser(),
			$services->getNamespaceInfo(),
			$services->getHookContainer()
		);
	}

	private function setupInterwiki() {
		$this->overrideMwServices( null, [
			'InterwikiLookup' => function () {
				return $this->getDummyInterwikiLookup( [
					'interwiki' => [
						'iw_prefix' => 'interwiki',
						'iw_url' => 'https://interwiki/$1',
					]
				] );
			}
		] );
	}

	private function setupConf() {
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
			MainConfigNames::LanguageCode => 'en',
		] );
	}

	/**
	 * Copied from LinkerTest so that LinkerTest can be deleted once deprecation
	 * and removal of Linker::formatComment() is complete.
	 *
	 * @return array[]
	 */
	public function provideFormatComment() {
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
	 * Adapted from LinkerTest
	 *
	 * @dataProvider provideFormatComment
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
			MainConfigNames::LanguageCode => 'en',
		] );

		$this->setupInterwiki();
		$this->addGoodLinkObject( 1, Title::newFromText( 'Present' ) );

		if ( $title === false ) {
			// We need a page title that exists
			$title = Title::newFromText( 'Special:BlankPage' );
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

	/**
	 * Adapted from LinkerTest
	 */
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
				'<a class="external" rel="nofollow" href="//en.example.org/w/Foo%27bar">Foo&#039;bar</a>',
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

	/**
	 * Adapted from LinkerTest. Note that we test the new HTML escaping variant.
	 *
	 * @dataProvider provideFormatLinksInComment
	 */
	public function testFormatLinksInComment( $expected, $input, $wiki ) {
		$this->setupConf();
		$parser = $this->getParser();
		$title = Title::newFromText( 'Special:BlankPage' );
		$result = $parser->finalize(
			$parser->preprocess(
				$input, $title, false, $wiki, false
			)
		);

		$this->assertEquals( $expected, $result );
	}

	public function testLinkCacheInteraction() {
		$this->tablesUsed[] = 'page';
		$this->setupConf();
		$services = $this->getServiceContainer();
		$present = Title::newFromText( 'Present' );
		$absent = Title::newFromText( 'Absent' );
		$this->editPage(
			$present,
			'content'
		);
		$parser = $this->getParser();
		$linkCache = $services->getLinkCache();
		$result = $parser->finalize( [
			$parser->preprocess( "[[$present]]" ),
			$parser->preprocess( "[[$absent]]" )
		] );
		$expected = [
			'<a href="/wiki/Present" title="Present">Present</a>',
			'<a href="/wiki/index.php?title=Absent&amp;action=edit&amp;redlink=1" class="new" title="Absent (page does not exist)">Absent</a>'
		];
		$this->assertSame( $expected, $result );
		$this->assertGreaterThan( 0, $linkCache->getGoodLinkID( $present ) );
		$this->assertTrue( $linkCache->isBadLink( $absent ) );

		// Run the comment batch again and confirm that LinkBatch does not need
		// to execute a query. This is a CommentParser responsibility since
		// LinkBatch does not provide a transparent read-through cache.
		// TODO: Generic $this->assertQueryCount() would do the job.
		$lbf = $services->getDBLoadBalancerFactory();
		$fakeLB = $lbf->newMainLB();
		$fakeLB->disable( __METHOD__ );
		$linkBatchFactory = new LinkBatchFactory(
			$services->getLinkCache(),
			$services->getTitleFormatter(),
			$services->getContentLanguage(),
			$services->getGenderCache(),
			$fakeLB,
			$services->getLinksMigration(),
			LoggerFactory::getInstance( 'LinkBatch' )
		);
		$parser = new CommentParser(
			$services->getLinkRenderer(),
			$linkBatchFactory,
			$linkCache,
			$services->getRepoGroup(),
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
		$this->tablesUsed[] = 'page';
		$this->setupConf();
		$this->setupInterwiki();
		$present = Title::newFromText( 'Template:Present' );
		$this->editPage(
			$present,
			'content'
		);
		$this->getServiceContainer()->getLinkCache()->clear();
		$parser = $this->getParser();
		$result = $parser->finalize(
			$parser->preprocess( "[[interwiki:$present]] [[$present]]" )
		);
		$this->assertSame(
			// phpcs:ignore Generic.Files.LineLength
			"<a href=\"https://interwiki/$present\" class=\"extiw\" title=\"interwiki:$present\">interwiki:$present</a> <a href=\"/wiki/$present\" title=\"$present\">$present</a>",
			$result
		);
	}

	/**
	 * Regression test for T293665
	 */
	public function testAlwaysKnownPages() {
		$this->setupConf();
		$this->setTemporaryHook( 'TitleIsAlwaysKnown',
			static function ( $target, &$isKnown ) {
				$isKnown = $target->getText() == 'AlwaysKnownFoo';
			}
		);

		$title = Title::newFromText( 'User:AlwaysKnownFoo' );
		$this->assertFalse( $title->exists() );

		$parser = $this->getParser();
		$result = $parser->finalize( $parser->preprocess( 'test [[User:AlwaysKnownFoo]]' ) );

		$this->assertSame(
			'test <a href="/wiki/User:AlwaysKnownFoo" title="User:AlwaysKnownFoo">User:AlwaysKnownFoo</a>',
			$result
		);
	}

}
