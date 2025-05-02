<?php

use MediaWiki\Config\HashConfig;
use MediaWiki\Config\MultiConfig;
use MediaWiki\Context\RequestContext;
use MediaWiki\FileRepo\File\File;
use MediaWiki\Html\Html;
use MediaWiki\Language\ILanguageConverter;
use MediaWiki\Language\Language;
use MediaWiki\Language\LanguageCode;
use MediaWiki\Language\RawMessage;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Output\OutputPage;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Page\PageStoreRecord;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Parser\ParserOutputLinkTypes;
use MediaWiki\Permissions\Authority;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Request\ContentSecurityPolicy;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Request\WebRequest;
use MediaWiki\ResourceLoader as RL;
use MediaWiki\ResourceLoader\ResourceLoader;
use MediaWiki\Session\SessionManager;
use MediaWiki\Skin\QuickTemplate;
use MediaWiki\Tests\ResourceLoader\ResourceLoaderTestCase;
use MediaWiki\Tests\ResourceLoader\ResourceLoaderTestModule;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use MediaWiki\User\User;
use MediaWiki\Utils\MWTimestamp;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\DependencyStore\DependencyStore;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\TestingAccessWrapper;

/**
 * @author Matthew Flaschen
 *
 * @group Database
 * @group Output
 * @covers \MediaWiki\Output\OutputPage
 */
class OutputPageTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;
	use MockTitleTrait;

	private const SCREEN_MEDIA_QUERY = 'screen and (min-width: 982px)';
	private const SCREEN_ONLY_MEDIA_QUERY = 'only screen and (min-width: 982px)';
	private const RSS_RC_LINK = '<link rel="alternate" type="application/rss+xml" title=" RSS feed" href="/w/index.php?title=Special:RecentChanges&amp;feed=rss">';
	private const ATOM_RC_LINK = '<link rel="alternate" type="application/atom+xml" title=" Atom feed" href="/w/index.php?title=Special:RecentChanges&amp;feed=atom">';

	private const RSS_TEST_LINK = '<link rel="alternate" type="application/rss+xml" title="&quot;Test&quot; RSS feed" href="fake-link">';
	private const ATOM_TEST_LINK = '<link rel="alternate" type="application/atom+xml" title="&quot;Test&quot; Atom feed" href="fake-link">';
	// phpcs:enable

	// Ensure that we don't affect the global ResourceLoader state.
	protected function setUp(): void {
		parent::setUp();
		ResourceLoader::clearCache();

		$this->overrideConfigValues( [
			MainConfigNames::ScriptPath => '/mw',
			MainConfigNames::Script => '/mw/index.php',
			MainConfigNames::ArticlePath => '/wikipage/$1',
			MainConfigNames::Server => 'http://example.org',
			MainConfigNames::CanonicalServer => 'https://www.example.org',
		] );
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'en' );
	}

	protected function tearDown(): void {
		ResourceLoader::clearCache();
		parent::tearDown();
	}

	/**
	 * @dataProvider provideRedirect
	 */
	public function testRedirect( $url, $code = null ) {
		$op = $this->newInstance();
		if ( isset( $code ) ) {
			$op->redirect( $url, $code );
		} else {
			$op->redirect( $url );
		}
		$expectedUrl = str_replace( "\n", '', $url );
		$this->assertSame( $expectedUrl, $op->getRedirect() );
		$this->assertSame( $expectedUrl, $op->mRedirect );
		$this->assertSame( $code ?? '302', $op->mRedirectCode );
	}

	public static function provideRedirect() {
		return [
			[ 'http://example.com' ],
			[ 'http://example.com', '400' ],
			[ 'http://example.com', 'squirrels!!!' ],
			[ "a\nb" ],
		];
	}

	private function setupFeedLinks( $feed, $types ): OutputPage {
		$outputPage = $this->newInstance( [
			MainConfigNames::AdvertisedFeedTypes => $types,
			MainConfigNames::Feed => $feed,
			MainConfigNames::OverrideSiteFeed => false,
			MainConfigNames::Script => '/w',
			MainConfigNames::Sitename => false,
		] );
		$outputPage->setTitle( Title::makeTitle( NS_MAIN, 'Test' ) );
		$this->overrideConfigValue( MainConfigNames::Script, '/w/index.php' );
		return $outputPage;
	}

	private function assertFeedLinks( OutputPage $outputPage, $message, $present, $non_present ) {
		$links = $outputPage->getHeadLinksArray();
		foreach ( $present as $link ) {
			$this->assertContains( $link, $links, $message );
		}
		foreach ( $non_present as $link ) {
			$this->assertNotContains( $link, $links, $message );
		}
	}

	private function assertFeedUILinks( OutputPage $outputPage, $ui_links ) {
		if ( $ui_links ) {
			$this->assertTrue( $outputPage->isSyndicated(), 'Syndication should be offered' );
			$this->assertGreaterThan( 0, count( $outputPage->getSyndicationLinks() ),
				'Some syndication links should be there' );
		} else {
			$this->assertFalse( $outputPage->isSyndicated(), 'No syndication should be offered' );
			$this->assertSame( [], $outputPage->getSyndicationLinks(),
				'No syndication links should be there' );
		}
	}

	public static function provideFeedLinkData() {
		return [
			[
				true, [ 'rss' ], 'Only RSS RC link should be offerred',
				[ self::RSS_RC_LINK ], [ self::ATOM_RC_LINK ]
			],
			[
				true, [ 'atom' ], 'Only Atom RC link should be offerred',
				[ self::ATOM_RC_LINK ], [ self::RSS_RC_LINK ]
			],
			[
				true, [], 'No RC feed formats should be offerred',
				[], [ self::ATOM_RC_LINK, self::RSS_RC_LINK ]
			],
			[
				false, [ 'atom' ], 'No RC feeds should be offerred',
				[], [ self::ATOM_RC_LINK, self::RSS_RC_LINK ]
			],
		];
	}

	public function testSetCanonicalUrl() {
		$op = $this->newInstance();
		$op->setCanonicalUrl( 'http://example.comm' );
		$op->setCanonicalUrl( 'http://example.com' );

		$this->assertSame( 'http://example.com', $op->getCanonicalUrl() );

		$headLinks = $op->getHeadLinksArray();

		$this->assertContains( Html::element( 'link', [
			'rel' => 'canonical', 'href' => 'http://example.com'
		] ), $headLinks );

		$this->assertNotContains( Html::element( 'link', [
			'rel' => 'canonical', 'href' => 'http://example.comm'
		] ), $headLinks );
	}

	public static function provideGetHeadLinksArray() {
		return [
			[
				[ MainConfigNames::EnableCanonicalServerLink => true ],
				'https://www.example.org/xyzzy/Hello',
				true,
				'/xyzzy/Hello'
			],
			[
				[ MainConfigNames::EnableCanonicalServerLink => true ],
				'https://www.example.org/wikipage/My_test_page',
				true,
				null
			],
			[
				[ MainConfigNames::EnableCanonicalServerLink => true ],
				'https://www.mediawiki.org/wiki/Manual:FauxRequest.php',
				false,
				null
			],
		];
	}

	/**
	 * @dataProvider provideGetHeadLinksArray
	 */
	public function testGetHeadLinksArray( $config, $canonicalUrl, $isArticleRelated, $canonicalUrlToSet = null ) {
		$request = new FauxRequest();
		$request->setRequestURL( 'https://www.mediawiki.org/wiki/Manual:FauxRequest.php' );
		$op = $this->newInstance( $config, $request );
		if ( $canonicalUrlToSet ) {
			$op->setCanonicalUrl( $canonicalUrlToSet );
		}
		$op->setArticleRelated( $isArticleRelated );
		$headLinks = $op->getHeadLinksArray();
		$this->assertSame(
			Html::element( 'link',
				[ 'rel' => 'canonical', 'href' => $canonicalUrl ]
			),
			$headLinks['link-canonical']
		);
	}

	/**
	 * Test the generation of hreflang Tags when site language has variants
	 */
	public function testGetLanguageVariantUrl() {
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'zh' );

		$op = $this->newInstance();
		$headLinks = $op->getHeadLinksArray();

		# T123901, T305540, T108443: Don't use language variant link for mixed-variant variant
		#  (the language code with converter / the main code)
		$this->assertSame(
			Html::element( 'link', [ 'rel' => 'alternate', 'hreflang' => 'zh',
				'href' => 'http://example.org/wikipage/My_test_page' ] ),
			$headLinks['link-alternate-language-zh']
		);

		# Make sure alternate URLs use BCP 47 codes in hreflang
		$this->assertSame(
			Html::element( 'link', [ 'rel' => 'alternate', 'hreflang' => 'zh-Hant-TW',
				'href' => 'http://example.org/mw/index.php?title=My_test_page&variant=zh-tw' ] ),
			$headLinks['link-alternate-language-zh-hant-tw']
		);

		# Make sure $wgVariantArticlePath work
		# We currently use MediaWiki internal language code as the primary variant URL parameter
		$this->overrideConfigValues( [
			MainConfigNames::LanguageCode => 'zh',
			MainConfigNames::VariantArticlePath => '/$2/$1',
		] );

		$op = $this->newInstance();
		$headLinks = $op->getHeadLinksArray();

		$this->assertSame(
			Html::element( 'link', [ 'rel' => 'alternate', 'hreflang' => 'zh-Hant-TW',
				'href' => 'http://example.org/zh-tw/My_test_page' ] ),
			$headLinks['link-alternate-language-zh-hant-tw']
		);
	}

	public static function provideCanonicalUrlAndAlternateUrlData() {
		# $messsage, $action, $urlVariant, $canonicalUrl, $altUrlLangCode, $present, $nonpresent
		return [
			[
				'Non-specified variant with view action - '
					. 'We currently use MediaWiki internal codes as the primary URL parameter',
				null,
				null,
				'https://www.example.org/wikipage/My_test_page',
				'zh-tw',
				'http://example.org/mw/index.php?title=My_test_page&variant=zh-tw',
				'http://example.org/mw/index.php?title=My_test_page&variant=zh-hant-tw',
			],
			[
				'Specified zh-tw variant with view action - '
					. 'Canonical URL and alternate URL should be the same; '
					. 'Alternate URL should be kept even when it is the current page view language',
				null,
				'zh-tw',
				'https://www.example.org/mw/index.php?title=My_test_page&variant=zh-tw',
				'zh-tw',
				'http://example.org/mw/index.php?title=My_test_page&variant=zh-tw',
				'http://example.org/mw/index.php?title=My_test_page&variant=zh-hant-tw',
			],
			[
				'Non-specified variant with history action - '
					. 'There should be no alternate URLs for language variants'
					. 'There should be no alternate URLs for language variants',
				'history',
				null,
				'https://www.example.org/mw/index.php?title=My_test_page&action=history',
				'zh-tw',
				null,
				'https://www.example.org/mw/index.php?title=My_test_page&action=history&variant=zh-tw',
			],
			[
				'Specified zh-tw variant with history action - '
					. 'There should be no alternate URLs for language variants',
				'history',
				'zh-tw',
				'https://www.example.org/mw/index.php?title=My_test_page&action=history',
				'zh-tw',
				null,
				'https://www.example.org/mw/index.php?title=My_test_page&action=history&variant=zh-tw',
			],
		];
	}

	/**
	 * @dataProvider provideCanonicalUrlAndAlternateUrlData
	 */
	public function testCanonicalUrlAndAlternateUrls(
		$messsage, $action, $urlVariant, $canonicalUrl, $altUrlLangCode, $present, $nonpresent
	) {
		$req = new FauxRequest( [
			'title' => 'My_test_page',
			'action' => $action,
			'variant' => $urlVariant,
		] );
		$this->setRequest( $req );
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'zh' );
		$op = $this->newInstance( [ MainConfigNames::EnableCanonicalServerLink => true ], $req );
		$bcp47 = LanguageCode::bcp47( $altUrlLangCode );
		$bcp47Lowercase = strtolower( $bcp47 );
		$headLinks = $op->getHeadLinksArray();

		$this->assertSame(
			Html::element( 'link', [ 'rel' => 'canonical', 'href' => $canonicalUrl ] ),
			$headLinks['link-canonical'],
			$messsage
		);

		if ( isset( $present ) ) {
			$this->assertSame(
				Html::element(
					'link',
					[
						'rel' => 'alternate',
						'hreflang' => $bcp47,
						'href' => $present,
					]
				),
				$headLinks['link-alternate-language-' . $bcp47Lowercase],
				$messsage
			);
		}

		$this->assertNotContains(
			Html::element(
				'link',
				[ 'rel' => 'alternate', 'hreflang' => $bcp47, 'href' => $nonpresent, ]
			),
			$headLinks,
			$messsage
		);
	}

	public function testSetCopyrightUrl() {
		$op = $this->newInstance();
		$op->setCopyrightUrl( 'http://example.com' );

		$this->assertSame(
			Html::element( 'link', [ 'rel' => 'license', 'href' => 'http://example.com' ] ),
			$op->getHeadLinksArray()['copyright']
		);
	}

	/**
	 * @dataProvider provideFeedLinkData
	 */
	public function testRecentChangesFeed( $feed, $advertised_feed_types,
				$message, $present, $non_present ) {
		$outputPage = $this->setupFeedLinks( $feed, $advertised_feed_types );
		$this->assertFeedLinks( $outputPage, $message, $present, $non_present );
	}

	public static function provideAdditionalFeedData() {
		return [
			[
				true, [ 'atom' ], 'Additional Atom feed should be offered',
				'atom',
				[ self::ATOM_TEST_LINK, self::ATOM_RC_LINK ],
				[ self::RSS_TEST_LINK, self::RSS_RC_LINK ],
				true,
			],
			[
				true, [ 'rss' ], 'Additional RSS feed should be offered',
				'rss',
				[ self::RSS_TEST_LINK, self::RSS_RC_LINK ],
				[ self::ATOM_TEST_LINK, self::ATOM_RC_LINK ],
				true,
			],
			[
				true, [ 'rss' ], 'Additional Atom feed should NOT be offered with RSS enabled',
				'atom',
				[ self::RSS_RC_LINK ],
				[ self::RSS_TEST_LINK, self::ATOM_TEST_LINK, self::ATOM_RC_LINK ],
				false,
			],
			[
				false, [ 'atom' ], 'Additional Atom feed should NOT be offered, all feeds disabled',
				'atom',
				[],
				[
					self::RSS_TEST_LINK, self::ATOM_TEST_LINK,
					self::ATOM_RC_LINK, self::ATOM_RC_LINK,
				],
				false,
			],
		];
	}

	/**
	 * @dataProvider provideAdditionalFeedData
	 */
	public function testAdditionalFeeds( $feed, $advertised_feed_types, $message,
			$additional_feed_type, $present, $non_present, $any_ui_links ) {
		$outputPage = $this->setupFeedLinks( $feed, $advertised_feed_types );
		$outputPage->addFeedLink( $additional_feed_type, 'fake-link' );
		$this->assertFeedLinks( $outputPage, $message, $present, $non_present );
		$this->assertFeedUILinks( $outputPage, $any_ui_links );
	}

	// @todo How to test setStatusCode?

	public function testMetaTags() {
		$op = $this->newInstance();
		$op->addMeta( 'http:expires', '0' );
		$op->addMeta( 'keywords', 'first' );
		$op->addMeta( 'keywords', 'second' );
		$op->addMeta( 'og:title', 'Ta-duh' );

		$expected = [
			[ 'http:expires', '0' ],
			[ 'keywords', 'first' ],
			[ 'keywords', 'second' ],
			[ 'og:title', 'Ta-duh' ],
		];
		$this->assertSame( $expected, $op->getMetaTags() );

		$links = $op->getHeadLinksArray();
		$this->assertContains( '<meta http-equiv="expires" content="0">', $links );
		$this->assertContains( '<meta name="keywords" content="first">', $links );
		$this->assertContains( '<meta name="keywords" content="second">', $links );
		$this->assertContains( '<meta property="og:title" content="Ta-duh">', $links );
		$this->assertArrayHasKey( 'meta-robots', $links );
	}

	public function testAddLink() {
		$op = $this->newInstance();

		$links = [
			[],
			[ 'rel' => 'foo', 'href' => 'http://example.com' ],
		];

		foreach ( $links as $link ) {
			$op->addLink( $link );
		}

		$this->assertSame( $links, $op->getLinkTags() );

		$result = $op->getHeadLinksArray();

		foreach ( $links as $link ) {
			$this->assertContains( Html::element( 'link', $link ), $result );
		}
	}

	public function testAddScript() {
		$op = $this->newInstance();
		$op->addScript( 'some random string' );

		$this->assertStringContainsString(
			"\nsome random string\n",
			"\n" . $op->getBottomScripts() . "\n"
		);
	}

	public function testAddScriptFile() {
		$op = $this->newInstance();
		$op->addScriptFile( '/somescript.js' );
		$op->addScriptFile( '//example.com/somescript.js' );

		$this->assertStringContainsString(
			"\n" . Html::linkedScript( '/somescript.js' ) .
				Html::linkedScript( '//example.com/somescript.js' ) . "\n",
			"\n" . $op->getBottomScripts() . "\n"
		);
	}

	public function testAddInlineScript() {
		$op = $this->newInstance();
		$op->addInlineScript( 'let foo = "bar";' );
		$op->addInlineScript( 'alert( foo );' );

		$this->assertStringContainsString(
			"\n" . Html::inlineScript( "\nlet foo = \"bar\";\n" ) . "\n" .
				Html::inlineScript( "\nalert( foo );\n" ) . "\n",
			"\n" . $op->getBottomScripts() . "\n"
		);
	}

	// @todo How to test addContentOverride(Callback)?

	public function testHeadItems() {
		$op = $this->newInstance();
		$op->addHeadItem( 'a', 'b' );
		$op->addHeadItems( [ 'c' => '<d>&amp;', 'e' => 'f', 'a' => 'q' ] );
		$op->addHeadItem( 'e', 'g' );
		$op->addHeadItems( 'x' );

		$this->assertSame( [ 'a' => 'q', 'c' => '<d>&amp;', 'e' => 'g', 'x' ],
			$op->getHeadItemsArray() );

		$this->assertTrue( $op->hasHeadItem( 'a' ) );
		$this->assertTrue( $op->hasHeadItem( 'c' ) );
		$this->assertTrue( $op->hasHeadItem( 'e' ) );
		$this->assertTrue( $op->hasHeadItem( '0' ) );

		$this->assertStringContainsString( "\nq\n<d>&amp;\ng\nx\n",
			'' . $op->headElement( $op->getContext()->getSkin() ) );
	}

	public function testHeadItemsParserOutput() {
		$op = $this->newInstance();
		$stubPO1 = $this->createParserOutputStub( 'getHeadItems', [ 'a' => 'b' ] );
		$op->addParserOutputMetadata( $stubPO1 );
		$stubPO2 = $this->createParserOutputStub( 'getHeadItems',
			[ 'c' => '<d>&amp;', 'e' => 'f', 'a' => 'q' ] );
		$op->addParserOutputMetadata( $stubPO2 );
		$stubPO3 = $this->createParserOutputStub( 'getHeadItems', [ 'e' => 'g' ] );
		$op->addParserOutput( $stubPO3, ParserOptions::newFromAnon() );
		$stubPO4 = $this->createParserOutputStub( 'getHeadItems', [ 'x' ] );
		$op->addParserOutputMetadata( $stubPO4 );

		$this->assertSame( [ 'a' => 'q', 'c' => '<d>&amp;', 'e' => 'g', 'x' ],
			$op->getHeadItemsArray() );

		$this->assertTrue( $op->hasHeadItem( 'a' ) );
		$this->assertTrue( $op->hasHeadItem( 'c' ) );
		$this->assertTrue( $op->hasHeadItem( 'e' ) );
		$this->assertTrue( $op->hasHeadItem( '0' ) );
		$this->assertFalse( $op->hasHeadItem( 'b' ) );

		$this->assertStringContainsString( "\nq\n<d>&amp;\ng\nx\n",
			'' . $op->headElement( $op->getContext()->getSkin() ) );
	}

	public function testCSPParserOutput() {
		$this->overrideConfigValue( MainConfigNames::CSPHeader, [] );
		foreach ( [ 'Default', 'Script', 'Style' ] as $type ) {
			$op = $this->newInstance();
			$ltype = strtolower( $type );
			$stubPO1 = $this->createParserOutputStub( "getExtraCSP{$type}Srcs", [ "{$ltype}src.com" ] );
			$op->addParserOutputMetadata( $stubPO1 );
			$csp = TestingAccessWrapper::newFromObject( $op->getCSP() );
			$actual = $csp->makeCSPDirectives( [ 'default-src' => [] ], false );
			$regex = '/(^|;)\s*' . $ltype . '-src\s[^;]*' . $ltype . 'src\.com[\s;]/';
			$this->assertMatchesRegularExpression( $regex, $actual, $type );
		}
	}

	public function testAddBodyClasses() {
		$op = $this->newInstance();
		$op->addBodyClasses( 'a' );
		$op->addBodyClasses( 'mediawiki' );
		$op->addBodyClasses( 'b c' );
		$op->addBodyClasses( [ 'd', 'e' ] );
		$op->addBodyClasses( 'a' );

		$this->assertStringContainsString( '<body class="a mediawiki b c d e ',
			'' . $op->headElement( $op->getContext()->getSkin() ) );
	}

	public function testArticleBodyOnly() {
		$op = $this->newInstance();
		$this->assertFalse( $op->getArticleBodyOnly() );

		$op->setArticleBodyOnly( true );
		$this->assertTrue( $op->getArticleBodyOnly() );

		$op->addHTML( '<b>a</b>' );

		$this->assertSame( '<b>a</b>', $op->output( true ) );
	}

	public function testProperties() {
		$op = $this->newInstance();

		$this->assertNull( $op->getProperty( 'foo' ) );

		$op->setProperty( 'foo', 'bar' );
		$op->setProperty( 'baz', 'quz' );

		$this->assertSame( 'bar', $op->getProperty( 'foo' ) );
		$this->assertSame( 'quz', $op->getProperty( 'baz' ) );
	}

	/**
	 * @dataProvider provideCheckLastModified
	 */
	public function testCheckLastModified(
		$timestamp, $ifModifiedSince, $expected, $config = [], $callback = null
	) {
		$request = new FauxRequest();
		if ( $ifModifiedSince ) {
			if ( is_numeric( $ifModifiedSince ) ) {
				// Unix timestamp
				$ifModifiedSince = date( 'D, d M Y H:i:s', $ifModifiedSince ) . ' GMT';
			}
			$request->setHeader( 'If-Modified-Since', $ifModifiedSince );
		}

		// Make sure it's not too recent
		$config['CacheEpoch'] ??= '20000101000000';
		$config['CachePages'] ??= true;

		$op = $this->newInstance( $config, $request );

		if ( $callback ) {
			$callback( $op, $this );
		}

		// Ignore complaint about not being able to disable compression
		$this->assertEquals( $expected, @$op->checkLastModified( $timestamp ) );
	}

	public function provideCheckLastModified() {
		$lastModified = time() - 3600;
		return [
			'Timestamp 0' =>
				[ '0', $lastModified, false ],
			'Timestamp Unix epoch' =>
				[ '19700101000000', $lastModified, false ],
			'Timestamp same as If-Modified-Since' =>
				[ $lastModified, $lastModified, true ],
			'Timestamp one second after If-Modified-Since' =>
				[ $lastModified + 1, $lastModified, false ],
			'No If-Modified-Since' =>
				[ $lastModified + 1, null, false ],
			'Malformed If-Modified-Since' =>
				[ $lastModified + 1, 'GIBBERING WOMBATS !!!', false ],
			'Non-standard IE-style If-Modified-Since' =>
				[ $lastModified, date( 'D, d M Y H:i:s', $lastModified ) . ' GMT; length=5202',
					true ],
			// @todo Should we fix this behavior to match the spec?  Probably no reason to.
			'If-Modified-Since not per spec but we accept it anyway because strtotime does' =>
				[ $lastModified, "@$lastModified", true ],
			'$wgCachePages = false' =>
				[ $lastModified, $lastModified, false, [ MainConfigNames::CachePages => false ] ],
			'$wgCacheEpoch' =>
				[ $lastModified, $lastModified, false,
					[ MainConfigNames::CacheEpoch => wfTimestamp( TS_MW, $lastModified + 1 ) ] ],
			'Recently-touched user' =>
				[ $lastModified, $lastModified, false, [],
				function ( OutputPage $op ) {
					$op->getContext()->setUser( $this->getTestUser()->getUser() );
				} ],
			'After CDN expiry' =>
				[ $lastModified, $lastModified, false,
					[ MainConfigNames::UseCdn => true, MainConfigNames::CdnMaxAge => 3599 ] ],
			'Hook allows cache use' =>
				[ $lastModified + 1, $lastModified, true, [],
				static function ( $op, $that ) {
					$that->setTemporaryHook( 'OutputPageCheckLastModified',
						static function ( &$modifiedTimes ) {
							$modifiedTimes = [ 1 ];
						}
					);
				} ],
			'Hooks prohibits cache use' =>
				[ $lastModified, $lastModified, false, [],
				static function ( $op, $that ) {
					$that->setTemporaryHook( 'OutputPageCheckLastModified',
						static function ( &$modifiedTimes ) {
							$modifiedTimes = [ max( $modifiedTimes ) + 1 ];
						}
					);
				} ],
		];
	}

	// @todo How to test setLastModified?

	public function testSetRobotPolicy() {
		$op = $this->newInstance();
		$op->setRobotPolicy( 'noindex, nofollow' );

		$links = $op->getHeadLinksArray();
		$this->assertContains( '<meta name="robots" content="noindex,nofollow,max-image-preview:standard">', $links );
	}

	public function testSetRobotsOptions() {
		$op = $this->newInstance();
		$op->setRobotPolicy( 'nofollow' );
		$op->setRobotsOptions( [ 'max-snippet' => '500' ] );
		$op->setIndexPolicy( 'index' );

		$links = $op->getHeadLinksArray();
		$this->assertContains( '<meta name="robots" content="index,nofollow,max-image-preview:standard,max-snippet:500">', $links );

		$op->setFollowPolicy( 'follow' );
		$links = $op->getHeadLinksArray();
		$this->assertContains(
			'<meta name="robots" content="max-image-preview:standard,max-snippet:500">',
			$links,
			'When index,follow (browser default) omit'
		);

		$op->setIndexPolicy( 'noindex' );
		$links = $op->getHeadLinksArray();
		$this->assertContains(
			'<meta name="robots" content="noindex,follow,max-image-preview:standard,max-snippet:500">',
			$links,
			'noindex takes precedence over index'
		);

		// Deprecated behavior: for OutputPage (unlike ParserOutput) we can
		// reset to 'index' after 'noindex' has been set.
		$this->filterDeprecated( '/OutputPage::setIndexPolicy with index after noindex/' );
		$op->setIndexPolicy( 'index' );
		$links = $op->getHeadLinksArray();
		$this->assertContains(
			'<meta name="robots" content="max-image-preview:standard,max-snippet:500">',
			$links,
			'index can reset noindex (deprecated)'
		);
	}

	public function testGetRobotPolicy() {
		$op = $this->newInstance();
		$op->setRobotPolicy( 'noindex, follow' );

		$policy = $op->getRobotPolicy();
		$this->assertSame( 'noindex,follow', $policy );
	}

	/**
	 * This test is safe to remove once ::setIndexPolicy() is removed.
	 * @covers \MediaWiki\Output\OutputPage::setIndexPolicy
	 */
	public function testSetIndexPoliciesBackCompat() {
		$op = $this->newInstance();
		$this->assertSame( "", $op->getMetadata()->getIndexPolicy() );
		$op->setIndexPolicy( 'index' );
		$this->assertEquals( "index", $op->getIndexPolicy() );
		$this->assertEquals( "index", $op->getMetadata()->getIndexPolicy() );
		$op->setIndexPolicy( 'noindex' );
		$this->assertEquals( "noindex", $op->getIndexPolicy() );
		$this->assertEquals( "noindex", $op->getMetadata()->getIndexPolicy() );
	}

	/**
	 * @covers \MediaWiki\Output\OutputPage::getMetadata
	 * @covers \MediaWiki\Output\OutputPage::getIndexPolicy
	 * @covers \MediaWiki\Output\OutputPage::setFollowPolicy
	 * @covers \MediaWiki\Output\OutputPage::getHeadLinksArray
	 */
	public function testSetIndexFollowPolicies() {
		$op = $this->newInstance();
		$this->assertSame( "", $op->getMetadata()->getIndexPolicy() );
		$this->assertEquals( "index", $op->getIndexPolicy() );
		$op->getMetadata()->setIndexPolicy( 'noindex' );
		$this->assertEquals( "noindex", $op->getIndexPolicy() );
		$op->setFollowPolicy( 'nofollow' );

		$links = $op->getHeadLinksArray();
		$this->assertContains( '<meta name="robots" content="noindex,nofollow,max-image-preview:standard">', $links );
	}

	private function extractHTMLTitle( OutputPage $op ) {
		$html = $op->headElement( $op->getContext()->getSkin() );

		// OutputPage should always output the title in a nice format such that regexes will work
		// fine.  If it doesn't, we'll fail the tests.
		preg_match_all( '!<title>(.*?)</title>!', $html, $matches );

		$this->assertLessThanOrEqual( 1, count( $matches[1] ), 'More than one <title>!' );

		return $matches[1][0] ?? null;
	}

	/**
	 * Shorthand for getting the text of a message, in content language.
	 * @param MessageLocalizer $op
	 * @param mixed ...$msgParams
	 * @return string
	 */
	private static function getMsgText( MessageLocalizer $op, ...$msgParams ) {
		return $op->msg( ...$msgParams )->inContentLanguage()->text();
	}

	public function testHTMLTitle() {
		$op = $this->newInstance();

		// Default
		$this->assertSame( '', $op->getHTMLTitle() );
		$this->assertSame( '', $op->getPageTitle() );
		$this->assertSame(
			$this->getMsgText( $op, 'pagetitle', '' ),
			$this->extractHTMLTitle( $op )
		);

		// Set to string
		$op->setHTMLTitle( 'Potatoes will eat me' );

		$this->assertSame( 'Potatoes will eat me', $op->getHTMLTitle() );
		$this->assertSame( 'Potatoes will eat me', $this->extractHTMLTitle( $op ) );
		// Shouldn't have changed the page title
		$this->assertSame( '', $op->getPageTitle() );

		// Set to message
		$msg = $op->msg( 'mainpage' );

		$op->setHTMLTitle( $msg );
		$this->assertSame( $msg->text(), $op->getHTMLTitle() );
		$this->assertSame( $msg->text(), $this->extractHTMLTitle( $op ) );
		$this->assertSame( '', $op->getPageTitle() );
	}

	public function testSetRedirectedFrom() {
		$op = $this->newInstance();

		$op->setRedirectedFrom( new PageReferenceValue( NS_TALK, 'Some page', PageReference::LOCAL ) );
		$this->assertSame( 'Talk:Some_page', $op->getJSVars()['wgRedirectedFrom'] );
	}

	public function testPageTitle() {
		// We don't test the actual HTML output anywhere, because that's up to the skin.
		$op = $this->newInstance();

		// Test default
		$this->assertSame( '', $op->getPageTitle() );
		$this->assertSame( '', $op->getHTMLTitle() );

		// Test set to plain text
		$op->setPageTitle( 'foobar' );

		$this->assertSame( 'foobar', $op->getPageTitle() );
		// HTML title should change as well
		$this->assertSame( $this->getMsgText( $op, 'pagetitle', 'foobar' ), $op->getHTMLTitle() );

		// Test set to text with good and bad HTML.  We don't try to be *too*
		// comprehensive here, that belongs in Sanitizer tests, but we'll
		// address the issues specifically noted in T298401/T67747 at least...
		$sanitizerTests = [
			[
				'input' => '<script>a</script>&amp;<i>b</i>',
				'getPageTitle' => '&lt;script&gt;a&lt;/script&gt;&amp;<i>b</i>',
				'getHTMLTitle' => '<script>a</script>&b',
			],
			[
				'input' => '<code style="display:none">', # T298401
				'getPageTitle' => '<code style="display:none"></code>',
				'getHTMLTitle' => '',
			],
			[
				'input' => '<b>Foo bar<b>', # T67747
				'getPageTitle' => '<b>Foo bar<b></b></b>',
				'getHTMLTitle' => 'Foo bar',
			],
		];
		foreach ( $sanitizerTests as $case ) {
			$op->setPageTitle( $case['input'] );

			$this->assertSame( $case['getPageTitle'], $op->getPageTitle() );
			$this->assertSame(
				$this->getMsgText( $op, 'pagetitle', $case['getHTMLTitle'] ),
				$op->getHTMLTitle()
			);
		}

		// Test set to message (deprecated unescaped)
		$text = $this->getMsgText( $op, 'mainpage' );

		$op->setPageTitleMsg( $op->msg( 'mainpage' )->inContentLanguage() );
		$this->assertSame( $text, $op->getPageTitle() );
		$this->assertSame( $this->getMsgText( $op, 'pagetitle', $text ), $op->getHTMLTitle() );

		// Test set to message (::setPageTitleMsg(), escaped)
		$msg = ( new RawMessage( 'nope:<span>$1 yes:$2' ) )
			->plaintextParams( '</span>' )
			->rawParams( '<span>!</span>' );
		// preferred ::setPageTitleMsg(Msg)
		$op->setPageTitleMsg( $msg );
		$this->assertSame( 'nope:&lt;span&gt;&lt;/span&gt; yes:<span>!</span>', $op->getPageTitle() );
		// Note that HTML title is unescaped plaintext, it is expected to be
		// HTML escaped before becoming the <title> element.
		$this->assertSame( $this->getMsgText( $op, 'pagetitle', 'nope:<span></span> yes:!' ), $op->getHTMLTitle() );

		// deprecated ::setPageTitle(Message), doesn't escape either
		// the localized message or the plaintext parameters
		$this->filterDeprecated( '/OutputPage::setPageTitle with Message argument/' );
		$op->setPageTitle( $msg );
		$this->assertSame( "nope:<span></span> yes:<span>!</span>", $op->getPageTitle() );
	}

	public function testSetTitle() {
		$op = $this->newInstance();

		$this->assertSame( 'My test page', $op->getTitle()->getPrefixedText() );

		$op->setTitle( Title::makeTitle( NS_MAIN, 'Another test page' ) );

		$this->assertSame( 'Another test page', $op->getTitle()->getPrefixedText() );
	}

	public function testSubtitle() {
		$op = $this->newInstance();

		$this->assertSame( '', $op->getSubtitle() );

		$op->addSubtitle( '<b>foo</b>' );

		$this->assertSame( '<b>foo</b>', $op->getSubtitle() );

		$op->addSubtitle( $op->msg( 'mainpage' )->inContentLanguage() );

		$this->assertSame(
			"<b>foo</b><br />\n\t\t\t\t" . $this->getMsgText( $op, 'mainpage' ),
			$op->getSubtitle()
		);

		$op->setSubtitle( 'There can be only one' );

		$this->assertSame( 'There can be only one', $op->getSubtitle() );

		$op->clearSubtitle();

		$this->assertSame( '', $op->getSubtitle() );
	}

	/**
	 * @dataProvider provideBacklinkSubtitle
	 */
	public function testBuildBacklinkSubtitle( $titles, $queries, $contains, $notContains ) {
		if ( count( $titles ) > 1 ) {
			// Not applicable
			$this->assertTrue( true );
			return;
		}

		$title = $titles[0];
		$query = $queries[0];

		$str = OutputPage::buildBacklinkSubtitle( $title, $query )->text();

		foreach ( $contains as $substr ) {
			$this->assertStringContainsString( $substr, $str );
		}

		foreach ( $notContains as $substr ) {
			$this->assertStringNotContainsString( $substr, $str );
		}
	}

	/**
	 * @dataProvider provideBacklinkSubtitle
	 */
	public function testAddBacklinkSubtitle( $titles, $queries, $contains, $notContains ) {
		$op = $this->newInstance();
		foreach ( $titles as $i => $unused ) {
			$op->addBacklinkSubtitle( $titles[$i], $queries[$i] );
		}

		$str = $op->getSubtitle();

		foreach ( $contains as $substr ) {
			$this->assertStringContainsString( $substr, $str );
		}

		foreach ( $notContains as $substr ) {
			$this->assertStringNotContainsString( $substr, $str );
		}
	}

	public function provideBacklinkSubtitle() {
		$page1title = $this->makeMockTitle( 'Page 1', [ 'redirect' => true ] );
		$page1ref = new PageReferenceValue( NS_MAIN, 'Page 1', PageReference::LOCAL );

		$row = [
			'page_id' => 28,
			'page_namespace' => NS_MAIN,
			'page_title' => 'Page 2',
			'page_latest' => 75,
			'page_is_redirect' => true,
			'page_is_new' => true,
			'page_touched' => '20200101221133',
			'page_lang' => 'en',
		];
		$page2rec = new PageStoreRecord( (object)$row, PageReference::LOCAL );

		$special = new PageReferenceValue( NS_SPECIAL, 'BlankPage', PageReference::LOCAL );

		return [
			[
				[ $page1title ],
				[ [] ],
				[ 'Page 1' ],
				[ 'redirect', 'Page 2' ],
			],
			[
				[ $page2rec ],
				[ [] ],
				[ 'redirect=no' ],
				[ 'Page 1' ],
			],
			[
				[ $special ],
				[ [] ],
				[ 'Special:BlankPage' ],
				[ 'redirect=no' ],
			],
			[
				[ $page1ref ],
				[ [ 'action' => 'edit' ] ],
				[ 'action=edit' ],
				[],
			],
			[
				[ $page1ref, $page2rec ],
				[ [], [] ],
				[ 'Page 1', 'Page 2', "<br />\n\t\t\t\t" ],
				[],
			],
			// @todo Anything else to test?
		];
	}

	public function testPrintable() {
		$op = $this->newInstance();

		$this->assertFalse( $op->isPrintable() );

		$op->setPrintable();

		$this->assertTrue( $op->isPrintable() );
	}

	public function testDisable() {
		$op = $this->newInstance();

		$this->assertFalse( $op->isDisabled() );
		$this->assertNotSame( '', $op->output( true ) );

		$op->disable();

		$this->assertTrue( $op->isDisabled() );
		$this->assertSame( '', $op->output( true ) );
	}

	public function testShowNewSectionLink() {
		$this->filterDeprecated( '/OutputPage::showNewSectionLink was deprecated/' );
		$op = $this->newInstance();

		$this->assertFalse( $op->showNewSectionLink() );
		$this->assertFalse( $op->getOutputFlag( ParserOutputFlags::NEW_SECTION ) );

		$pOut1 = $this->createParserOutputStubWithFlags(
			[ 'getNewSection' => true ], [ ParserOutputFlags::NEW_SECTION ]
		);
		$op->addParserOutputMetadata( $pOut1 );
		$this->assertTrue( $op->showNewSectionLink() );
		$this->assertTrue( $op->getOutputFlag( ParserOutputFlags::NEW_SECTION ) );

		$pOut2 = $this->createParserOutputStub( 'getNewSection', false );
		$op->addParserOutput( $pOut2, ParserOptions::newFromAnon() );
		$this->assertFalse( $op->showNewSectionLink() );
		// Note that flags are OR'ed together, and not reset.
		$this->assertTrue( $op->getOutputFlag( ParserOutputFlags::NEW_SECTION ) );
	}

	public function testForceHideNewSectionLink() {
		$this->filterDeprecated( '/OutputPage::forceHideNewSectionLink was deprecated/' );
		$op = $this->newInstance();

		$this->assertFalse( $op->forceHideNewSectionLink() );
		$this->assertFalse( $op->getOutputFlag( ParserOutputFlags::HIDE_NEW_SECTION ) );

		$pOut1 = $this->createParserOutputStubWithFlags(
			[ 'getHideNewSection' => true ], [ ParserOutputFlags::HIDE_NEW_SECTION ]
		);
		$op->addParserOutputMetadata( $pOut1 );
		$this->assertTrue( $op->forceHideNewSectionLink() );
		$this->assertTrue( $op->getOutputFlag( ParserOutputFlags::HIDE_NEW_SECTION ) );

		$pOut2 = $this->createParserOutputStub( 'getHideNewSection', false );
		$op->addParserOutput( $pOut2, ParserOptions::newFromAnon() );
		$this->assertFalse( $op->forceHideNewSectionLink() );
		// Note that flags are OR'ed together, and not reset.
		$this->assertTrue( $op->getOutputFlag( ParserOutputFlags::HIDE_NEW_SECTION ) );
	}

	public function testSetSyndicated() {
		$op = $this->newInstance( [ MainConfigNames::Feed => true ] );
		$this->assertFalse( $op->isSyndicated() );

		$op->setSyndicated();
		$this->assertTrue( $op->isSyndicated() );

		$op->setSyndicated( false );
		$this->assertFalse( $op->isSyndicated() );

		$op = $this->newInstance(); // Feed => false by default
		$this->assertFalse( $op->isSyndicated() );

		$op->setSyndicated();
		$this->assertFalse( $op->isSyndicated() );
	}

	public function testFeedLinks() {
		$op = $this->newInstance( [ MainConfigNames::Feed => true ] );
		$this->assertSame( [], $op->getSyndicationLinks() );

		$op->addFeedLink( 'not a supported format', 'abc' );
		$this->assertFalse( $op->isSyndicated() );
		$this->assertSame( [], $op->getSyndicationLinks() );

		$feedTypes = $op->getConfig()->get( MainConfigNames::AdvertisedFeedTypes );

		$op->addFeedLink( $feedTypes[0], 'def' );
		$this->assertTrue( $op->isSyndicated() );
		$this->assertSame( [ $feedTypes[0] => 'def' ], $op->getSyndicationLinks() );

		$op->setFeedAppendQuery( false );
		$expected = [];
		foreach ( $feedTypes as $type ) {
			$expected[$type] = $op->getTitle()->getLocalURL( "feed=$type" );
		}
		$this->assertSame( $expected, $op->getSyndicationLinks() );

		$op->setFeedAppendQuery( 'apples=oranges' );
		foreach ( $feedTypes as $type ) {
			$expected[$type] = $op->getTitle()->getLocalURL( "feed=$type&apples=oranges" );
		}
		$this->assertSame( $expected, $op->getSyndicationLinks() );

		$op = $this->newInstance(); // Feed => false by default
		$this->assertSame( [], $op->getSyndicationLinks() );

		$op->addFeedLink( $feedTypes[0], 'def' );
		$this->assertFalse( $op->isSyndicated() );
		$this->assertSame( [], $op->getSyndicationLinks() );
	}

	public function testArticleFlags() {
		$op = $this->newInstance();
		$this->assertFalse( $op->isArticle() );
		$this->assertTrue( $op->isArticleRelated() );

		$op->setArticleRelated( false );
		$this->assertFalse( $op->isArticle() );
		$this->assertFalse( $op->isArticleRelated() );

		$op->setArticleFlag( true );
		$this->assertTrue( $op->isArticle() );
		$this->assertTrue( $op->isArticleRelated() );

		$op->setArticleFlag( false );
		$this->assertFalse( $op->isArticle() );
		$this->assertTrue( $op->isArticleRelated() );

		$op->setArticleFlag( true );
		$op->setArticleRelated( false );
		$this->assertFalse( $op->isArticle() );
		$this->assertFalse( $op->isArticleRelated() );
	}

	public function testLanguageLinks() {
		$op = $this->newInstance();
		$this->assertSame( [], $op->getLanguageLinks() );

		$op->addLanguageLinks( [ 'fr:A#x', 'it:B' ] );
		$this->assertSame( [ 'fr:A#x', 'it:B' ], $op->getLanguageLinks() );

		$op->addLanguageLinks( [
			TitleValue::tryNew( NS_MAIN, 'C', '', 'de' ),
			TitleValue::tryNew( NS_MAIN, 'D', '', 'es' ),
		] );
		$this->assertSame( [ 'fr:A#x', 'it:B', 'de:C', 'es:D' ], $op->getLanguageLinks() );

		$op->setLanguageLinks( [ TitleValue::tryNew( NS_MAIN, 'E', '', 'pt' ) ] );
		$this->assertSame( [ 'pt:E' ], $op->getLanguageLinks() );

		$pOut1 = $this->createParserOutputStub( [
			'getLanguageLinks' => [ 'he:F', 'ar:G#y' ],
			'getLinkList' => static function ( $type ) {
				if ( $type !== ParserOutputLinkTypes::LANGUAGE ) {
					return [];
				}
				return [
					[ 'link' => TitleValue::tryNew( NS_MAIN, 'F', '', 'he' ) ],
					[ 'link' => TitleValue::tryNew( NS_MAIN, 'G', 'y', 'ar' ) ],
				];
			},
		] );
		$op->addParserOutputMetadata( $pOut1 );
		$this->assertSame( [ 'pt:E', 'he:F', 'ar:G#y' ], $op->getLanguageLinks() );

		# Duplicates are removed in OutputPage (T26502)
		$pOut2 = $this->createParserOutputStub( [
			'getLanguageLinks' => [ 'pt:H' ],
			'getLinkList' => static function ( $type ) {
				if ( $type !== ParserOutputLinkTypes::LANGUAGE ) {
					return [];
				}
				return [
					[ 'link' => TitleValue::tryNew( NS_MAIN, 'H', '', 'pt' ) ],
				];
			},
		] );
		$op->addParserOutput( $pOut2, ParserOptions::newFromAnon() );
		$this->assertSame( [ 'pt:E', 'he:F', 'ar:G#y' ], $op->getLanguageLinks() );
	}

	// @todo Are these category links tests too abstract and complicated for what they test?  Would
	// it make sense to just write out all the tests by hand with maybe some copy-and-paste?

	/**
	 * @dataProvider provideGetCategories
	 *
	 *
	 * @param array $args Array of form [ category name => sort key ]
	 * @param array $fakeResults Array of form [ category name => value to return from mocked
	 *   LinkBatch ]
	 * @param callable|null $variantLinkCallback Callback to replace findVariantLink() call
	 * @param array $expectedNormal Expected return value of getCategoryLinks['normal']
	 * @param array $expectedHidden Expected return value of getCategoryLinks['hidden']
	 */
	public function testAddCategoryLinks(
		array $args, array $fakeResults, ?callable $variantLinkCallback,
		array $expectedNormal, array $expectedHidden
	) {
		$expectedNormal = $this->extractExpectedCategories( $expectedNormal, 'add' );
		$expectedHidden = $this->extractExpectedCategories( $expectedHidden, 'add' );

		$op = $this->setupCategoryTests( $fakeResults, $variantLinkCallback );

		$op->addCategoryLinks( $args );

		$this->doCategoryAsserts( $op, $expectedNormal, $expectedHidden );
		$this->doCategoryLinkAsserts( $op, $expectedNormal, $expectedHidden );
	}

	/**
	 * @dataProvider provideGetCategories
	 */
	public function testAddCategoryLinksOneByOne(
		array $args, array $fakeResults, ?callable $variantLinkCallback,
		array $expectedNormal, array $expectedHidden
	) {
		if ( count( $args ) <= 1 ) {
			// @todo Should this be skipped instead of passed?
			$this->assertTrue( true );
			return;
		}

		$expectedNormal = $this->extractExpectedCategories( $expectedNormal, 'onebyone' );
		$expectedHidden = $this->extractExpectedCategories( $expectedHidden, 'onebyone' );

		$op = $this->setupCategoryTests( $fakeResults, $variantLinkCallback );

		foreach ( $args as $key => $val ) {
			$op->addCategoryLinks( [ $key => $val ] );
		}

		$this->doCategoryAsserts( $op, $expectedNormal, $expectedHidden );
		$this->doCategoryLinkAsserts( $op, $expectedNormal, $expectedHidden );
	}

	/**
	 * @dataProvider provideGetCategories
	 */
	public function testSetCategoryLinks(
		array $args, array $fakeResults, ?callable $variantLinkCallback,
		array $expectedNormal, array $expectedHidden
	) {
		$expectedNormal = $this->extractExpectedCategories( $expectedNormal, 'set' );
		$expectedHidden = $this->extractExpectedCategories( $expectedHidden, 'set' );

		$op = $this->setupCategoryTests( $fakeResults, $variantLinkCallback );

		$this->filterDeprecated( '/OutputPage::setCategoryLinks was deprecated/' );
		$op->setCategoryLinks( [ 'Initial page' => 'Initial page' ] );
		$op->setCategoryLinks( $args );

		// We don't reset the categories, for some reason, only the links
		$expectedNormalCats = array_merge( [ 'Initial page' ], $expectedNormal );

		$this->doCategoryAsserts( $op, $expectedNormalCats, $expectedHidden );
		$this->doCategoryLinkAsserts( $op, $expectedNormal, $expectedHidden );
	}

	/**
	 * @dataProvider provideGetCategories
	 */
	public function testParserOutputCategoryLinks(
		array $args, array $fakeResults, ?callable $variantLinkCallback,
		array $expectedNormal, array $expectedHidden
	) {
		$expectedNormal = $this->extractExpectedCategories( $expectedNormal, 'pout' );
		$expectedHidden = $this->extractExpectedCategories( $expectedHidden, 'pout' );

		$op = $this->setupCategoryTests( $fakeResults, $variantLinkCallback );

		$stubPO = $this->createParserOutputStub( [
			'getCategoryMap' => $args,
			'getLinkList' => static function ( $type ) use ( $args ) {
				if ( $type !== ParserOutputLinkTypes::CATEGORY ) {
					return [];
				}
				$result = [];
				foreach ( $args as $cat => $sort ) {
					$result[] = [
						'link' => TitleValue::tryNew( NS_CATEGORY, $cat ),
						'sort' => $sort,
					];
				}
				return $result;
			},
		] );

		// addParserOutput and addParserOutputMetadata should behave identically for us, so
		// alternate to get coverage for both without adding extra tests
		static $idx = 0;
		if ( ( ( ++$idx ) % 2 ) === 0 ) {
			$op->addParserOutputMetadata( $stubPO );
		} else {
			$op->addParserOutput( $stubPO, ParserOptions::newFromAnon() );
		}

		$this->doCategoryAsserts( $op, $expectedNormal, $expectedHidden );
		$this->doCategoryLinkAsserts( $op, $expectedNormal, $expectedHidden );
	}

	/**
	 * @dataProvider provideGetCategories
	 */
	public function testOutputPageRenderCategoryLinkHook(
		array $args, array $fakeResults, ?callable $variantLinkCallback,
		array $expectedNormal, array $expectedHidden, array $expectedText
	) {
		$expectedNormal = $this->extractExpectedCategories( $expectedNormal, 'add' );
		$expectedHidden = $this->extractExpectedCategories( $expectedHidden, 'add' );

		$op = $this->setupCategoryTests( $fakeResults, $variantLinkCallback );

		$tf = $this->getServiceContainer()->getTitleFormatter();
		$this->setTemporaryHook( 'OutputPageRenderCategoryLink',
			static function ( $outputPage, $categoryTitle, $text, &$link ) use ( $tf ) {
				$link = 'Custom link: ' . $tf->getPrefixedText( $categoryTitle ) . ", text: ($text)";
			}
		);
		$op->addCategoryLinks( $args );

		$this->doCategoryAsserts( $op, $expectedNormal, $expectedHidden );
		$this->doCategoryLinkAsserts( $op, $expectedNormal, $expectedHidden );
		$i = 0;
		foreach ( $op->getCategoryLinks() as $type => $actual ) {
			foreach ( $actual as $link ) {
				$text = $expectedText[$i++];
				$this->assertStringContainsString( 'Custom link: Category:', $link );
				$this->assertStringContainsString( ", text: ($text)", $link );
			}
		}
		$this->assertCount( $i, $expectedText );
	}

	/**
	 * We allow different expectations for different tests as an associative array, like
	 * [ 'set' => [ ... ], 'default' => [ ... ] ] if setCategoryLinks() will give a different
	 * result.
	 * @param array $expected
	 * @param string $key
	 * @return array
	 */
	private function extractExpectedCategories( array $expected, $key ) {
		if ( !$expected || isset( $expected[0] ) ) {
			return $expected;
		}
		return $expected[$key] ?? $expected['default'];
	}

	private function setupCategoryTests(
		array $fakeResults, ?callable $variantLinkCallback = null
	): OutputPage {
		$this->overrideConfigValue( MainConfigNames::UsePigLatinVariant, true );

		if ( $variantLinkCallback ) {
			$mockLanguageConverter = $this
				->createMock( ILanguageConverter::class );
			$mockLanguageConverter
				->method( 'findVariantLink' )
				->willReturnCallback( $variantLinkCallback );
			$mockLanguageConverter
				->method( 'convertHtml' )
				->willReturnCallback( 'strrev' );

			$languageConverterFactory = $this
				->createMock( LanguageConverterFactory::class );
			$languageConverterFactory
				->method( 'getLanguageConverter' )
				->willReturn( $mockLanguageConverter );
			$this->setService(
				'LanguageConverterFactory',
				$languageConverterFactory
			);
		}

		$op = $this->getMockBuilder( OutputPage::class )
			->setConstructorArgs( [ new RequestContext() ] )
			->onlyMethods( [ 'addCategoryLinksToLBAndGetResult', 'getTitle' ] )
			->getMock();

		$title = Title::makeTitle( NS_MAIN, 'My test page' );
		$op->method( 'getTitle' )
			->willReturn( $title );

		$op->method( 'addCategoryLinksToLBAndGetResult' )
			->willReturnCallback( static function ( array $categories ) use ( $fakeResults ) {
				$return = [];
				foreach ( $categories as $category => $unused ) {
					if ( isset( $fakeResults[$category] ) ) {
						$return[] = $fakeResults[$category];
					}
				}
				return new FakeResultWrapper( $return );
			} );

		$this->assertSame( [], $op->getCategories() );

		return $op;
	}

	private function doCategoryAsserts( OutputPage $op, $expectedNormal, $expectedHidden ) {
		$this->assertSame( array_merge( $expectedHidden, $expectedNormal ), $op->getCategories() );
		$this->assertSame( $expectedNormal, $op->getCategories( 'normal' ) );
		$this->assertSame( $expectedHidden, $op->getCategories( 'hidden' ) );
	}

	private function doCategoryLinkAsserts( OutputPage $op, $expectedNormal, $expectedHidden ) {
		$catLinks = $op->getCategoryLinks();
		$this->assertCount( (bool)$expectedNormal + (bool)$expectedHidden, $catLinks );
		if ( $expectedNormal ) {
			$this->assertSameSize( $expectedNormal, $catLinks['normal'] );
		}
		if ( $expectedHidden ) {
			$this->assertSameSize( $expectedHidden, $catLinks['hidden'] );
		}

		foreach ( $expectedNormal as $i => $name ) {
			$this->assertStringContainsString( $name, $catLinks['normal'][$i] );
		}
		foreach ( $expectedHidden as $i => $name ) {
			$this->assertStringContainsString( $name, $catLinks['hidden'][$i] );
		}
	}

	public static function provideGetCategories() {
		return [
			'No categories' => [ [], [], null, [], [], [] ],
			'Simple test' => [
				[ 'Test1' => 'Some sortkey', 'Test2' => 'A different sortkey' ],
				[ 'Test1' => (object)[ 'pp_value' => 1, 'page_title' => 'Test1' ],
					'Test2' => (object)[ 'page_title' => 'Test2' ] ],
				null,
				[ 'Test2' ],
				[ 'Test1' ],
				[ 'Test1', 'Test2' ],
			],
			'Invalid title' => [
				[ '[' => '[', 'Test' => 'Test' ],
				[ 'Test' => (object)[ 'page_title' => 'Test' ] ],
				null,
				[ 'Test' ],
				[],
				[ 'Test' ],
			],
			'Variant link' => [
				[ 'Test' => 'Test', 'Estay' => 'Estay' ],
				[ 'Test' => (object)[ 'page_title' => 'Test' ] ],
				static function ( &$link, &$title ) {
					if ( $link === 'Estay' ) {
						$link = 'Test';
						$title = Title::makeTitleSafe( NS_CATEGORY, $link );
					}
				},
				// For adding one by one, the variant gets added as well as the original category,
				// but if you add them all together the second time gets skipped.
				[ 'onebyone' => [ 'Test', 'Test' ], 'default' => [ 'Test' ] ],
				[],
				[ 'tseT' ],
			],
		];
	}

	public function testGetCategoriesInvalid() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Invalid category type given: hiddne' );

		$op = $this->newInstance();
		$op->getCategories( 'hiddne' );
	}

	// @todo Should we test addCategoryLinksToLBAndGetResult?  If so, how?  Insert some test rows in
	// the DB?

	public function testIndicators() {
		$op = $this->newInstance();
		$this->assertSame( [], $op->getIndicators() );

		$op->setIndicators( [] );
		$this->assertSame( [], $op->getIndicators() );

		// Test sorting alphabetically
		$op->setIndicators( [ 'b' => 'x', 'a' => 'y' ] );
		$this->assertSame( [ 'a' => 'y', 'b' => 'x' ], $op->getIndicators() );

		// Test overwriting existing keys
		$op->setIndicators( [ 'c' => 'z', 'a' => 'w' ] );
		$this->assertSame( [ 'a' => 'w', 'b' => 'x', 'c' => 'z' ], $op->getIndicators() );

		// Test with addParserOutputMetadata
		// Note that the indicators are wrapped.
		$pOut1 = $this->createParserOutputStub( [
			'getIndicators' => [ 'c' => 'u', 'd' => 'v' ],
			'getWrapperDivClass' => 'wrapper1',
		] );
		$op->addParserOutputMetadata( $pOut1 );
		$this->assertSame( [
			'a' => 'w',
			'b' => 'x',
			'c' => '<div class="wrapper1">u</div>',
			'd' => '<div class="wrapper1">v</div>',
		], $op->getIndicators() );

		// Test with addParserOutput
		$pOut2 = $this->createParserOutputStub( [
			'getIndicators' => [ 'a' => '!!!' ],
			'getWrapperDivClass' => 'wrapper2',
		] );
		$op->addParserOutput( $pOut2, ParserOptions::newFromAnon() );
		$this->assertSame( [
			'a' => '<div class="wrapper2">!!!</div>',
			'b' => 'x',
			'c' => '<div class="wrapper1">u</div>',
			'd' => '<div class="wrapper1">v</div>',
		], $op->getIndicators() );
	}

	public function testAddHelpLink() {
		$op = $this->newInstance();

		$op->addHelpLink( 'Manual:PHP unit testing' );
		$indicators = $op->getIndicators();
		$this->assertSame( [ 'mw-helplink' ], array_keys( $indicators ) );
		$this->assertStringContainsString( 'Manual:PHP_unit_testing', $indicators['mw-helplink'] );

		$op->addHelpLink( 'https://phpunit.de', true );
		$indicators = $op->getIndicators();
		$this->assertSame( [ 'mw-helplink' ], array_keys( $indicators ) );
		$this->assertStringContainsString( 'https://phpunit.de', $indicators['mw-helplink'] );
		$this->assertStringNotContainsString( 'mediawiki', $indicators['mw-helplink'] );
		$this->assertStringNotContainsString( 'Manual:PHP', $indicators['mw-helplink'] );
	}

	public function testBodyHTML() {
		$op = $this->newInstance();
		$this->assertSame( '', $op->getHTML() );

		$op->addHTML( 'a' );
		$this->assertSame( 'a', $op->getHTML() );

		$op->addHTML( 'b' );
		$this->assertSame( 'ab', $op->getHTML() );

		$op->prependHTML( 'c' );
		$this->assertSame( 'cab', $op->getHTML() );

		$op->addElement( 'p', [ 'id' => 'foo' ], 'd' );
		$this->assertSame( 'cab<p id="foo">d</p>', $op->getHTML() );

		$op->clearHTML();
		$this->assertSame( '', $op->getHTML() );
	}

	/**
	 * @dataProvider provideRevisionId
	 */
	public function testRevisionId( $newVal, $expected ) {
		$op = $this->newInstance();

		$this->assertNull( $op->setRevisionId( $newVal ) );
		$this->assertSame( $expected, $op->getRevisionId() );
		$this->assertSame( $expected, $op->setRevisionId( null ) );
		$this->assertNull( $op->getRevisionId() );
	}

	public static function provideRevisionId() {
		return [
			[ null, null ],
			[ 7, 7 ],
			[ -1, -1 ],
			[ 3.2, 3 ],
			[ '0', 0 ],
			[ '32% finished', 32 ],
			[ false, 0 ],
		];
	}

	public function testRevisionTimestamp() {
		$this->filterDeprecated( '/OutputPage::setRevisionTimestamp was deprecated/' );
		$op = $this->newInstance();
		$this->assertNull( $op->getRevisionTimestamp() );

		$this->assertNull( $op->setRevisionTimestamp( 'abc' ) );
		$this->assertSame( 'abc', $op->getRevisionTimestamp() );
		$this->assertSame( 'abc', $op->setRevisionTimestamp( null ) );
		$this->assertNull( $op->getRevisionTimestamp() );
	}

	public function testFileVersion() {
		$op = $this->newInstance();
		$this->assertNull( $op->getFileVersion() );

		$stubFile = $this->createMock( File::class );
		$stubFile->method( 'exists' )->willReturn( true );
		$stubFile->method( 'getTimestamp' )->willReturn( '12211221123321' );
		$stubFile->method( 'getSha1' )->willReturn( 'bf3ffa7047dc080f5855377a4f83cd18887e3b05' );

		/** @var File $stubFile */
		$op->setFileVersion( $stubFile );

		$this->assertEquals(
			[ 'time' => '12211221123321', 'sha1' => 'bf3ffa7047dc080f5855377a4f83cd18887e3b05' ],
			$op->getFileVersion()
		);

		$stubMissingFile = $this->createMock( File::class );
		$stubMissingFile->method( 'exists' )->willReturn( false );

		/** @var File $stubMissingFile */
		$op->setFileVersion( $stubMissingFile );
		$this->assertNull( $op->getFileVersion() );

		$op->setFileVersion( $stubFile );
		$this->assertNotNull( $op->getFileVersion() );

		$op->setFileVersion( null );
		$this->assertNull( $op->getFileVersion() );
	}

	/**
	 * Call either with arguments $methodName, $returnValue; or an array
	 * [ $methodName => $returnValue, $methodName => $returnValue, ... ]
	 * @param mixed ...$args
	 * @return ParserOutput
	 */
	private function createParserOutputStub( ...$args ): ParserOutput {
		if ( count( $args ) === 0 ) {
			$retVals = [];
		} elseif ( count( $args ) === 1 ) {
			$retVals = $args[0];
		} elseif ( count( $args ) === 2 ) {
			$retVals = [ $args[0] => $args[1] ];
		}
		return $this->createParserOutputStubWithFlags( $retVals, [] );
	}

	/**
	 * First argument is an array
	 * [ $methodName => $returnValue, $methodName => $returnValue, ... ]
	 * Second argument is an array of parser flags for which ::getOutputFlag()
	 * should return 'TRUE'.
	 * @param array $retVals
	 * @param array $flags
	 * @return ParserOutput
	 */
	private function createParserOutputStubWithFlags( array $retVals, array $flags ): ParserOutput {
		$pOut = $this->createMock( ParserOutput::class );

		$mockedRunOutputPipeline = false;
		foreach ( $retVals as $method => $retVal ) {
			if ( is_callable( $retVal ) ) {
				$pOut->method( $method )->willReturnCallback( $retVal );
			} else {
				$pOut->method( $method )->willReturn( $retVal );
			}
			if ( $method === 'runOutputPipeline' ) {
				$mockedRunOutputPipeline = true;
			}
		}

		// Needed to ensure OutputPage::getParserOutputText doesn't return null
		if ( !$mockedRunOutputPipeline ) {
			$pOut->method( 'runOutputPipeline' )->willReturn( new ParserOutput( '' ) );
		}

		$arrayReturningMethods = [
			'getCategoryMap',
			'getFileSearchOptions',
			'getHeadItems',
			'getImages',
			'getIndicators',
			'getSections',
			'getLanguageLinks',
			'getTemplateIds',
			'getExtraCSPDefaultSrcs',
			'getExtraCSPStyleSrcs',
			'getExtraCSPScriptSrcs',
		];

		foreach ( $arrayReturningMethods as $method ) {
			$pOut->method( $method )->willReturn( [] );
		}

		$pOut->method( 'getOutputFlag' )->willReturnCallback( static function ( $name ) use ( $flags ) {
			return in_array( $name, $flags, true );
		} );

		return $pOut;
	}

	public function testTemplateIds() {
		$op = $this->newInstance();
		$this->assertSame( [], $op->getTemplateIds() );

		// Test with no template id's
		$stubPOEmpty = $this->createParserOutputStub();
		$op->addParserOutputMetadata( $stubPOEmpty );
		$this->assertSame( [], $op->getTemplateIds() );

		// Test with some arbitrary template id's
		$ids = [
			NS_MAIN => [ 'A' => 3, 'B' => 17 ],
			NS_TALK => [ 'C' => 31 ],
			NS_MEDIA => [ 'D' => -1 ],
		];

		$stubPO1 = $this->createParserOutputStub( 'getTemplateIds', $ids );

		$op->addParserOutputMetadata( $stubPO1 );
		$this->assertSame( $ids, $op->getTemplateIds() );

		// Test merging with a second set of id's
		$stubPO2 = $this->createParserOutputStub( 'getTemplateIds', [
			NS_MAIN => [ 'E' => 1234 ],
			NS_PROJECT => [ 'F' => 5678 ],
		] );

		$finalIds = [
			NS_MAIN => [ 'E' => 1234, 'A' => 3, 'B' => 17 ],
			NS_TALK => [ 'C' => 31 ],
			NS_MEDIA => [ 'D' => -1 ],
			NS_PROJECT => [ 'F' => 5678 ],
		];

		$op->addParserOutput( $stubPO2, ParserOptions::newFromAnon() );
		$this->assertSame( $finalIds, $op->getTemplateIds() );

		// Test merging with an empty set of id's
		$op->addParserOutputMetadata( $stubPOEmpty );
		$this->assertSame( $finalIds, $op->getTemplateIds() );
	}

	public function testFileSearchOptions() {
		$op = $this->newInstance();
		$this->assertSame( [], $op->getFileSearchOptions() );

		// Test with no files
		$stubPOEmpty = $this->createParserOutputStub();

		$op->addParserOutputMetadata( $stubPOEmpty );
		$this->assertSame( [], $op->getFileSearchOptions() );

		// Test with some arbitrary files
		$files1 = [
			'A' => [ 'time' => null, 'sha1' => '' ],
			'B' => [
				'time' => '12211221123321',
				'sha1' => 'bf3ffa7047dc080f5855377a4f83cd18887e3b05',
			],
		];

		$stubPO1 = $this->createParserOutputStub( 'getFileSearchOptions', $files1 );

		$op->addParserOutput( $stubPO1, ParserOptions::newFromAnon() );
		$this->assertSame( $files1, $op->getFileSearchOptions() );

		// Test merging with a second set of files
		$files2 = [
			'C' => [ 'time' => null, 'sha1' => '' ],
			'B' => [ 'time' => null, 'sha1' => '' ],
		];

		$stubPO2 = $this->createParserOutputStub( 'getFileSearchOptions', $files2 );

		$op->addParserOutputMetadata( $stubPO2 );
		$this->assertSame( array_merge( $files1, $files2 ), $op->getFileSearchOptions() );

		// Test merging with an empty set of files
		$op->addParserOutput( $stubPOEmpty, ParserOptions::newFromAnon() );
		$this->assertSame( array_merge( $files1, $files2 ), $op->getFileSearchOptions() );
	}

	/**
	 * @dataProvider provideAddWikiText
	 */
	public function testAddWikiText( $method, array $args, $expected ) {
		$op = $this->newInstance();
		$this->assertSame( '', $op->getHTML() );

		if ( in_array(
			$method,
			[ 'addWikiTextAsInterface', 'addWikiTextAsContent' ]
		) && count( $args ) >= 3 && $args[2] === null ) {
			// Special placeholder because we can't get the actual title in the provider
			$args[2] = $op->getTitle();
		}

		$op->$method( ...$args );
		$this->assertSame( $expected, $op->getHTML() );
	}

	public static function provideAddWikiText() {
		$somePageRef = new PageReferenceValue( NS_TALK, 'Some page', PageReference::LOCAL );

		$tests = [
			'addWikiTextAsInterface' => [
				'Simple wikitext' => [
					[ "'''Bold'''" ],
					"<p><b>Bold</b>\n</p>",
				], 'Untidy wikitext' => [
					[ "<b>Bold" ],
					"<p><b>Bold\n</b></p>",
				], 'List at start' => [
					[ '* List' ],
					"<ul><li>List</li></ul>\n",
				], 'List not at start' => [
					[ '* Not a list', false ],
					'<p>* Not a list</p>',
				], 'No section edit links' => [
					[ '== Title ==' ],
					'<div class="mw-heading mw-heading2"><h2 id="Title">Title</h2></div>',
				], 'With title at start' => [
					[ '* {{PAGENAME}}', true, Title::makeTitle( NS_TALK, 'Some page' ) ],
					"<ul><li>Some page</li></ul>\n",
				], 'With title not at start' => [
					[ '* {{PAGENAME}}', false, Title::makeTitle( NS_TALK, 'Some page' ) ],
					"<p>* Some page</p>",
				], 'Untidy input' => [
					[ '<b>{{PAGENAME}}', true, $somePageRef ],
					"<p><b>Some page\n</b></p>",
				],
			],
			'addWikiTextAsContent' => [
				'SpecialNewimages' => [
					[ "<p lang='en' dir='ltr'>\nMy message" ],
					'<p lang="en" dir="ltr">' . "\nMy message</p>"
				], 'List at start' => [
					[ '* List' ],
					"<ul><li>List</li></ul>",
				], 'List not at start' => [
					[ '* <b>Not a list', false ],
					'<p>* <b>Not a list</b></p>',
				], 'With title at start' => [
					[ '* {{PAGENAME}}', true, Title::makeTitle( NS_TALK, 'Some page' ) ],
					"<ul><li>Some page</li></ul>",
				], 'With title not at start' => [
					[ '* {{PAGENAME}}', false, Title::makeTitle( NS_TALK, 'Some page' ) ],
					"<p>* Some page</p>",
				], 'EditPage' => [
					[ "<div class='mw-editintro'>{{PAGENAME}}", true, $somePageRef ],
					'<div class="mw-editintro">' . "Some page</div>"
				],
			],
			'wrapWikiTextAsInterface' => [
				'Simple' => [
					[ 'wrapperClass', 'text' ],
					"<div class=\"mw-content-ltr wrapperClass\" lang=\"en\" dir=\"ltr\"><p>text\n</p></div>"
				], 'Spurious </div>' => [
					[ 'wrapperClass', 'text</div><div>more' ],
					"<div class=\"mw-content-ltr wrapperClass\" lang=\"en\" dir=\"ltr\"><p>text</p><div>more</div></div>"
				], 'Extra newlines would break <p> wrappers' => [
					[ 'two classes', "1\n\n2\n\n3" ],
					"<div class=\"mw-content-ltr two classes\" lang=\"en\" dir=\"ltr\"><p>1\n</p><p>2\n</p><p>3\n</p></div>"
				], 'Other unclosed tags' => [
					[ 'error', 'a<b>c<i>d' ],
					"<div class=\"mw-content-ltr error\" lang=\"en\" dir=\"ltr\"><p>a<b>c<i>d\n</i></b></p></div>"
				],
			],
		];

		// We have to reformat our array to match what PHPUnit wants
		$ret = [];
		foreach ( $tests as $key => $subarray ) {
			foreach ( $subarray as $subkey => $val ) {
				$val = array_merge( [ $key ], $val );
				$ret[$subkey] = $val;
			}
		}

		return $ret;
	}

	public function testAddWikiTextAsInterfaceNoTitle() {
		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'No title' );

		$op = $this->newInstance( [], null, 'notitle' );
		$op->addWikiTextAsInterface( 'a' );
	}

	public function testAddWikiTextAsContentNoTitle() {
		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'No title' );

		$op = $this->newInstance( [], null, 'notitle' );
		$op->addWikiTextAsContent( 'a' );
	}

	public function testAddWikiMsg() {
		$msg = wfMessage( 'parentheses' );
		$this->assertSame( '(a)', $msg->rawParams( 'a' )->plain() );

		$op = $this->newInstance();
		$this->assertSame( '', $op->getHTML() );
		$op->addWikiMsg( 'parentheses', "<b>a" );
		// The input is bad unbalanced HTML, but the output is tidied
		$this->assertSame( "<p>(<b>a)\n</b></p>", $op->getHTML() );
	}

	public function testWrapWikiMsg() {
		$msg = wfMessage( 'parentheses' );
		$this->assertSame( '(a)', $msg->rawParams( 'a' )->plain() );

		$op = $this->newInstance();
		$this->assertSame( '', $op->getHTML() );
		$op->wrapWikiMsg( '[$1]', [ 'parentheses', "<b>a" ] );
		// The input is bad unbalanced HTML, but the output is tidied
		$this->assertSame( "<p>[(<b>a)]\n</b></p>", $op->getHTML() );
	}

	public function testNoGallery() {
		$this->filterDeprecated( '/OutputPage::getNoGallery was deprecated/' );
		$op = $this->newInstance();
		$this->assertFalse( $op->getNoGallery() );
		$this->assertFalse( $op->getOutputFlag( ParserOutputFlags::NO_GALLERY ) );

		$stubPO1 = $this->createParserOutputStubWithFlags(
			[ 'getNoGallery' => true ], [ ParserOutputFlags::NO_GALLERY ]
		);
		$op->addParserOutputMetadata( $stubPO1 );
		$this->assertTrue( $op->getNoGallery() );
		$this->assertTrue( $op->getOutputFlag( ParserOutputFlags::NO_GALLERY ) );

		$stubPO2 = $this->createParserOutputStub( 'getNoGallery', false );
		$op->addParserOutput( $stubPO2, ParserOptions::newFromAnon() );
		$this->assertFalse( $op->getNoGallery() );
		// Note that flags are OR'ed together, and not reset.
		$this->assertTrue( $op->getOutputFlag( ParserOutputFlags::NO_GALLERY ) );
	}

	// @todo Make sure to test the following in addParserOutputMetadata() as well when we add tests
	// for them:
	//   * addModules()
	//   * addModuleStyles()
	//   * addJsConfigVars()
	//   * enableOOUI()
	// Otherwise those lines of addParserOutputMetadata() will be reported as covered, but we won't
	// be testing they actually work.

	public function testAddParserOutputText() {
		$op = $this->newInstance();
		$this->assertSame( '', $op->getHTML() );

		$text = '<some text>';
		$pOut = $this->createParserOutputStub( 'runOutputPipeline', new ParserOutput( $text ) );

		$op->addParserOutputMetadata( $pOut );
		$this->assertSame( '', $op->getHTML() );

		$op->addParserOutputText( $text );
		$this->assertSame( '<some text>', $op->getHTML() );
	}

	public function testAddParserOutput() {
		$op = $this->newInstance();
		$this->assertSame( '', $op->getHTML() );
		$this->filterDeprecated( '/OutputPage::showNewSectionLink was deprecated/' );
		$this->assertFalse( $op->showNewSectionLink() );
		$this->assertFalse( $op->getOutputFlag( ParserOutputFlags::NEW_SECTION ) );

		$pOut = $this->createParserOutputStubWithFlags( [
			'getContentHolderText' => '<some text>',
			'getNewSection' => true,
		], [
			ParserOutputFlags::NEW_SECTION,
		] );

		$op->addParserOutput( $pOut, ParserOptions::newFromAnon() );
		$this->assertSame( '<some text>', $op->getHTML() );
		$this->assertTrue( $op->showNewSectionLink() );
		$this->assertTrue( $op->getOutputFlag( ParserOutputFlags::NEW_SECTION ) );
	}

	public function testAddTemplate() {
		$template = $this->createMock( QuickTemplate::class );
		$template->method( 'getHTML' )->willReturn( '<abc>&def;' );

		$op = $this->newInstance();
		$op->addTemplate( $template );

		$this->assertSame( '<abc>&def;', $op->getHTML() );
	}

	/**
	 * @dataProvider provideParseAs
	 */
	public function testParseAsContent(
		array $args, $expectedHTML, $expectedHTMLInline = null
	) {
		$op = $this->newInstance();
		$this->assertSame( $expectedHTML, $op->parseAsContent( ...$args ) );
	}

	/**
	 * @dataProvider provideParseAs
	 */
	public function testParseAsInterface(
		array $args, $expectedHTML, $expectedHTMLInline = null
	) {
		$op = $this->newInstance();
		$this->assertSame( $expectedHTML, $op->parseAsInterface( ...$args ) );
	}

	/**
	 * @dataProvider provideParseAs
	 */
	public function testParseInlineAsInterface(
		array $args, $expectedHTML, $expectedHTMLInline = null
	) {
		$op = $this->newInstance();
		$this->assertSame(
			$expectedHTMLInline ?? $expectedHTML,
			$op->parseInlineAsInterface( ...$args )
		);
	}

	public static function provideParseAs() {
		return [
			'List at start of line' => [
				[ '* List', true ],
				"<ul><li>List</li></ul>",
			],
			'List not at start' => [
				[ "* ''Not'' list", false ],
				'<p>* <i>Not</i> list</p>',
				'* <i>Not</i> list',
			],
			'Italics' => [
				[ "''Italic''", true ],
				"<p><i>Italic</i>\n</p>",
				'<i>Italic</i>',
			],
			'formatnum' => [
				[ '{{formatnum:123456.789}}', true ],
				"<p>123,456.789\n</p>",
				"123,456.789",
			],
			'No section edit links' => [
				[ '== Header ==' ],
				'<div class="mw-heading mw-heading2"><h2 id="Header">Header</h2></div>',
			]
		];
	}

	public function testParseAsContentNullTitle() {
		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'No title' );
		$op = $this->newInstance( [], null, 'notitle' );
		$op->parseAsContent( '' );
	}

	public function testParseAsInterfaceNullTitle() {
		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'No title' );
		$op = $this->newInstance( [], null, 'notitle' );
		$op->parseAsInterface( '' );
	}

	public function testParseInlineAsInterfaceNullTitle() {
		$this->expectException( RuntimeException::class );
		$this->expectExceptionMessage( 'No title' );
		$op = $this->newInstance( [], null, 'notitle' );
		$op->parseInlineAsInterface( '' );
	}

	public function testCdnMaxage() {
		$op = $this->newInstance();
		$wrapper = TestingAccessWrapper::newFromObject( $op );
		$this->assertSame( 0, $wrapper->mCdnMaxage );

		$op->setCdnMaxage( -1 );
		$this->assertSame( -1, $wrapper->mCdnMaxage );

		$op->setCdnMaxage( 120 );
		$this->assertSame( 120, $wrapper->mCdnMaxage );

		$op->setCdnMaxage( 60 );
		$this->assertSame( 60, $wrapper->mCdnMaxage );

		$op->setCdnMaxage( 180 );
		$this->assertSame( 180, $wrapper->mCdnMaxage );

		$op->lowerCdnMaxage( 240 );
		$this->assertSame( 180, $wrapper->mCdnMaxage );

		$op->setCdnMaxage( 300 );
		$this->assertSame( 240, $wrapper->mCdnMaxage );

		$op->lowerCdnMaxage( 120 );
		$this->assertSame( 120, $wrapper->mCdnMaxage );

		$op->setCdnMaxage( 180 );
		$this->assertSame( 120, $wrapper->mCdnMaxage );

		$op->setCdnMaxage( 60 );
		$this->assertSame( 60, $wrapper->mCdnMaxage );

		$op->setCdnMaxage( 240 );
		$this->assertSame( 120, $wrapper->mCdnMaxage );
	}

	/** @var int Faked time to set for tests that need it */
	private static $fakeTime;

	/**
	 * @dataProvider provideAdaptCdnTTL
	 * @param array $args To pass to adaptCdnTTL()
	 * @param int $expected Expected new value of mCdnMaxageLimit
	 * @param array $options Associative array:
	 *  initialMaxage => Maxage to set before calling adaptCdnTTL() (default 86400)
	 */
	public function testAdaptCdnTTL( array $args, $expected, array $options = [] ) {
		MWTimestamp::setFakeTime( self::$fakeTime );

		$op = $this->newInstance();
		// Set a high maxage so that it will get reduced by adaptCdnTTL().  The default maxage
		// is 0, so adaptCdnTTL() won't mutate the object at all.
		$initial = $options['initialMaxage'] ?? 86400;
		$op->setCdnMaxage( $initial );
		$op->adaptCdnTTL( ...$args );

		$wrapper = TestingAccessWrapper::newFromObject( $op );

		// Special rules for false/null
		if ( $args[0] === null || $args[0] === false ) {
			$this->assertSame( $initial, $wrapper->mCdnMaxage, 'member value' );
			$op->setCdnMaxage( $expected + 1 );
			$this->assertSame( $expected + 1, $wrapper->mCdnMaxage, 'member value after new set' );
			return;
		}

		$this->assertSame( $expected, $wrapper->mCdnMaxageLimit, 'limit value' );

		if ( $initial >= $expected ) {
			$this->assertSame( $expected, $wrapper->mCdnMaxage, 'member value' );
		} else {
			$this->assertSame( $initial, $wrapper->mCdnMaxage, 'member value' );
		}

		$op->setCdnMaxage( $expected + 1 );
		$this->assertSame( $expected, $wrapper->mCdnMaxage, 'member value after new set' );
	}

	public static function provideAdaptCdnTTL() {
		global $wgCdnMaxAge;
		$now = time();
		self::$fakeTime = $now;
		$oneMinute = 60;
		return [
			'Five minutes ago' => [ [ $now - 300 ], 270 ],
			'Now' => [ [ +0 ], $oneMinute ],
			'Five minutes from now' => [ [ $now + 300 ], $oneMinute ],
			'Five minutes ago, initial maxage four minutes' =>
				[ [ $now - 300 ], 270, [ 'initialMaxage' => 240 ] ],
			'A very long time ago' => [ [ $now - 1000000000 ], $wgCdnMaxAge ],
			'Initial maxage zero' => [ [ $now - 300 ], 270, [ 'initialMaxage' => 0 ] ],

			'false' => [ [ false ], $oneMinute ],
			'null' => [ [ null ], $oneMinute ],
			"'0'" => [ [ '0' ], $oneMinute ],
			'Empty string' => [ [ '' ], $oneMinute ],
			// @todo These give incorrect results due to timezones, how to test?
			//"'now'" => [ [ 'now' ], $oneMinute ],
			//"'parse error'" => [ [ 'parse error' ], $oneMinute ],

			'Now, minTTL 0' => [ [ $now, 0 ], $oneMinute ],
			'Now, minTTL 0.000001' => [ [ $now, 0.000001 ], 0 ],
			'A very long time ago, maxTTL even longer' =>
				[ [ $now - 1000000000, 0, 1000000001 ], 900000000 ],
		];
	}

	public function testClientCache() {
		$op = $this->newInstance();
		$op->considerCacheSettingsFinal();

		// Test initial value
		$this->assertSame( true, $op->couldBePublicCached() );

		// Test setting to false
		$op->disableClientCache();
		$this->assertSame( false, $op->couldBePublicCached() );

		// Test setting to true
		$op->enableClientCache();
		$this->assertSame( true, $op->couldBePublicCached() );

		// set back to false
		$op->disableClientCache();

		// Test that a cacheable ParserOutput doesn't set to true
		$pOutCacheable = $this->createParserOutputStub( 'isCacheable', true );
		$op->addParserOutputMetadata( $pOutCacheable );
		$this->assertSame( false, $op->couldBePublicCached() );

		// Reset to true
		$op = $this->newInstance();
		$op->considerCacheSettingsFinal();
		$this->assertSame( true, $op->couldBePublicCached() );

		// Test that an uncacheable ParserOutput does set to false
		$pOutUncacheable = $this->createParserOutputStub( 'isCacheable', false );
		$op->addParserOutput( $pOutUncacheable, ParserOptions::newFromAnon() );
		$this->assertSame( false, $op->couldBePublicCached() );
	}

	public function testGetCacheVaryCookies() {
		$op = $this->newInstance();

		$expectedCookies = array_merge(
			SessionManager::singleton()->getVaryCookies(),
			[
				'forceHTTPS',
				'cookie1',
				'cookie2',
			]
		);

		$expectedCookies = array_values( array_unique( $expectedCookies ) );

		// We have to reset the cookies because getCacheVaryCookies may have already been called
		TestingAccessWrapper::newFromClass( OutputPage::class )->cacheVaryCookies = null;

		$this->overrideConfigValue( MainConfigNames::CacheVaryCookies, [ 'cookie1' ] );

		// Clear out any extension hooks that may interfere with cookies.
		$this->clearHook( 'GetCacheVaryCookies' );
		$this->setTemporaryHook( 'GetCacheVaryCookies',
			function ( $innerOP, &$cookies ) use ( $op, $expectedCookies ) {
				$this->assertSame( $op, $innerOP );
				$cookies[] = 'cookie2';
				$this->assertSame( $expectedCookies, $cookies );
			}
		);

		$this->assertSame( $expectedCookies, $op->getCacheVaryCookies() );
	}

	public function testHaveCacheVaryCookies() {
		$request = new FauxRequest();
		$op = $this->newInstance( [], $request );

		// No cookies are set.
		$this->assertFalse( $op->haveCacheVaryCookies() );

		// 'Token' is present but empty, so it shouldn't count.
		$request->setCookie( 'Token', '' );
		$this->assertFalse( $op->haveCacheVaryCookies() );

		// 'Token' present and nonempty.
		$request->setCookie( 'Token', '123' );
		$this->assertTrue( $op->haveCacheVaryCookies() );
	}

	/**
	 * @dataProvider provideVaryHeaders
	 *
	 *
	 * @param array[] $calls For each array, call addVaryHeader() with those arguments
	 * @param string[] $cookies Array of cookie names to vary on
	 * @param string $vary Text of expected Vary header (including the 'Vary: ')
	 */
	public function testVaryHeaders( array $calls, array $cookies, $vary ) {
		// Get rid of default Vary fields
		$op = $this->getMockBuilder( OutputPage::class )
			->setConstructorArgs( [ new RequestContext() ] )
			->onlyMethods( [ 'getCacheVaryCookies' ] )
			->getMock();
		$op->method( 'getCacheVaryCookies' )
			->willReturn( $cookies );
		TestingAccessWrapper::newFromObject( $op )->mVaryHeader = [];

		foreach ( $calls as $call ) {
			$op->addVaryHeader( ...$call );
		}
		$this->assertEquals( $vary, $op->getVaryHeader(), 'Vary:' );
	}

	public static function provideVaryHeaders() {
		return [
			'No header' => [
				[],
				[],
				'Vary: ',
			],
			'Single header' => [
				[
					[ 'Cookie' ],
				],
				[],
				'Vary: Cookie',
			],
			'Non-unique headers' => [
				[
					[ 'Cookie' ],
					[ 'Accept-Language' ],
					[ 'Cookie' ],
				],
				[],
				'Vary: Cookie, Accept-Language',
			],
			'Two headers with single options' => [
				// Options are deprecated since 1.34
				[
					[ 'Cookie', [ 'param=phpsessid' ] ],
					[ 'Accept-Language', [ 'substr=en' ] ],
				],
				[],
				'Vary: Cookie, Accept-Language',
			],
			'One header with multiple options' => [
				// Options are deprecated since 1.34
				[
					[ 'Cookie', [ 'param=phpsessid', 'param=userId' ] ],
				],
				[],
				'Vary: Cookie',
			],
			'Duplicate option' => [
				// Options are deprecated since 1.34
				[
					[ 'Cookie', [ 'param=phpsessid' ] ],
					[ 'Cookie', [ 'param=phpsessid' ] ],
					[ 'Accept-Language', [ 'substr=en', 'substr=en' ] ],
				],
				[],
				'Vary: Cookie, Accept-Language',
			],
			'Same header, different options' => [
				// Options are deprecated since 1.34
				[
					[ 'Cookie', [ 'param=phpsessid' ] ],
					[ 'Cookie', [ 'param=userId' ] ],
				],
				[],
				'Vary: Cookie',
			],
			'No header, vary cookies' => [
				[],
				[ 'cookie1', 'cookie2' ],
				'Vary: Cookie',
			],
			'Cookie header with option plus vary cookies' => [
				// Options are deprecated since 1.34
				[
					[ 'Cookie', [ 'param=cookie1' ] ],
				],
				[ 'cookie2', 'cookie3' ],
				'Vary: Cookie',
			],
			'Non-cookie header plus vary cookies' => [
				[
					[ 'Accept-Language' ],
				],
				[ 'cookie' ],
				'Vary: Accept-Language, Cookie',
			],
			'Cookie and non-cookie headers plus vary cookies' => [
				// Options are deprecated since 1.34
				[
					[ 'Cookie', [ 'param=cookie1' ] ],
					[ 'Accept-Language' ],
				],
				[ 'cookie2' ],
				'Vary: Cookie, Accept-Language',
			],
		];
	}

	public function testVaryHeaderDefault() {
		$op = $this->newInstance();
		$this->assertSame( 'Vary: Accept-Encoding, Cookie', $op->getVaryHeader() );
	}

	/**
	 * @dataProvider provideLinkHeaders
	 */
	public function testLinkHeaders( array $headers, $result ) {
		$op = $this->newInstance();

		foreach ( $headers as $header ) {
			$op->addLinkHeader( $header );
		}

		$this->assertEquals( $result, $op->getLinkHeader() );
	}

	public static function provideLinkHeaders() {
		return [
			[
				[],
				false
			],
			[
				[ '<https://foo/bar.jpg>;rel=preload;as=image' ],
				'Link: <https://foo/bar.jpg>;rel=preload;as=image',
			],
			[
				[
					'<https://foo/bar.jpg>;rel=preload;as=image',
					'<https://foo/baz.jpg>;rel=preload;as=image'
				],
				'Link: <https://foo/bar.jpg>;rel=preload;as=image,<https://foo/baz.jpg>;' .
					'rel=preload;as=image',
			],
		];
	}

	/**
	 * @dataProvider provideAddAcceptLanguage
	 */
	public function testAddAcceptLanguage(
		$code, array $variants, $expected, array $options = []
	) {
		$req = new FauxRequest( in_array( 'varianturl', $options ) ? [ 'variant' => 'x' ] : [] );
		$op = $this->newInstance( [], $req, in_array( 'notitle', $options ) ? 'notitle' : null );

		if ( !in_array( 'notitle', $options ) ) {
			$mockLang = $this->createMock( Language::class );
			$mockLang->method( 'getCode' )->willReturn( $code );

			$mockLanguageConverter = $this
				->createMock( ILanguageConverter::class );
			if ( in_array( 'varianturl', $options ) ) {
				$mockLanguageConverter->expects( $this->never() )->method( $this->anything() );
			} else {
				$mockLanguageConverter->method( 'hasVariants' )->willReturn( count( $variants ) > 1 );
				$mockLanguageConverter->method( 'getVariants' )->willReturn( $variants );
			}

			$languageConverterFactory = $this
				->createMock( LanguageConverterFactory::class );
			$languageConverterFactory
				->method( 'getLanguageConverter' )
				->willReturn( $mockLanguageConverter );
			$this->setService(
				'LanguageConverterFactory',
				$languageConverterFactory
			);

			$mockTitle = $this->createMock( Title::class );
			$mockTitle->method( 'getPageLanguage' )->willReturn( $mockLang );

			$op->setTitle( $mockTitle );
		}

		// This will run addAcceptLanguage()
		$op->sendCacheControl();
		$this->assertSame( "Vary: $expected", $op->getVaryHeader() );
	}

	public static function provideAddAcceptLanguage() {
		return [
			'No variants' => [
				'en',
				[ 'en' ],
				'Accept-Encoding, Cookie',
			],
			'One simple variant' => [
				'en',
				[ 'en', 'en-x-piglatin' ],
				'Accept-Encoding, Cookie, Accept-Language',
			],
			'Multiple variants with BCP47 alternatives' => [
				'zh',
				[ 'zh', 'zh-hans', 'zh-cn', 'zh-tw' ],
				'Accept-Encoding, Cookie, Accept-Language',
			],
			'No title' => [
				'en',
				[ 'en', 'en-x-piglatin' ],
				'Accept-Encoding, Cookie',
				[ 'notitle' ]
			],
			'Variant in URL' => [
				'en',
				[ 'en', 'en-x-piglatin' ],
				'Accept-Encoding, Cookie',
				[ 'varianturl' ]
			],
		];
	}

	public function testClickjacking() {
		$op = $this->newInstance();
		$this->assertTrue( $op->getPreventClickjacking() );

		$op->setPreventClickjacking( false );
		$this->assertFalse( $op->getPreventClickjacking() );

		$op->setPreventClickjacking( true );
		$this->assertTrue( $op->getPreventClickjacking() );

		$op->setPreventClickjacking( false );
		$this->assertFalse( $op->getPreventClickjacking() );

		$pOut1 = $this->createParserOutputStub( 'getPreventClickjacking', true );
		$op->addParserOutputMetadata( $pOut1 );
		$this->assertTrue( $op->getPreventClickjacking() );

		// The ParserOutput can't allow, only prevent
		$pOut2 = $this->createParserOutputStub( 'getPreventClickjacking', false );
		$op->addParserOutputMetadata( $pOut2 );
		$this->assertTrue( $op->getPreventClickjacking() );

		// Reset to test with addParserOutput()
		$op->setPreventClickjacking( false );
		$this->assertFalse( $op->getPreventClickjacking() );

		$op->addParserOutput( $pOut1, ParserOptions::newFromAnon() );
		$this->assertTrue( $op->getPreventClickjacking() );

		$op->addParserOutput( $pOut2, ParserOptions::newFromAnon() );
		$this->assertTrue( $op->getPreventClickjacking() );
	}

	/**
	 * @dataProvider provideGetFrameOptions
	 */
	public function testGetFrameOptions(
		$breakFrames, $preventClickjacking, $editPageFrameOptions, $expected
	) {
		$op = $this->newInstance( [
			MainConfigNames::BreakFrames => $breakFrames,
			MainConfigNames::EditPageFrameOptions => $editPageFrameOptions,
		] );
		$op->setPreventClickjacking( $preventClickjacking );

		$this->assertSame( $expected, $op->getFrameOptions() );
	}

	public static function provideGetFrameOptions() {
		return [
			'BreakFrames true' => [ true, false, false, 'DENY' ],
			'Allow clickjacking locally' => [ false, false, 'DENY', false ],
			'Allow clickjacking globally' => [ false, true, false, false ],
			'DENY globally' => [ false, true, 'DENY', 'DENY' ],
			'SAMEORIGIN' => [ false, true, 'SAMEORIGIN', 'SAMEORIGIN' ],
			'BreakFrames with SAMEORIGIN' => [ true, true, 'SAMEORIGIN', 'DENY' ],
		];
	}

	/**
	 * See ClientHtmlTest for full coverage.
	 *
	 * @dataProvider provideMakeResourceLoaderLink
	 */
	public function testMakeResourceLoaderLink( $args, $expectedHtml ) {
		$this->overrideConfigValues( [
			MainConfigNames::ResourceLoaderDebug => false,
			MainConfigNames::LoadScript => 'http://127.0.0.1:8080/w/load.php',
			MainConfigNames::CSPReportOnlyHeader => true,
		] );
		$class = new ReflectionClass( OutputPage::class );
		$method = $class->getMethod( 'makeResourceLoaderLink' );
		$method->setAccessible( true );
		$ctx = new RequestContext();
		$skinFactory = $this->getServiceContainer()->getSkinFactory();
		$ctx->setSkin( $skinFactory->makeSkin( 'fallback' ) );
		$ctx->setLanguage( 'en' );
		$out = new OutputPage( $ctx );
		$reflectCSP = new ReflectionClass( ContentSecurityPolicy::class );
		$rl = $out->getResourceLoader();
		$rl->setMessageBlobStore( $this->createMock( RL\MessageBlobStore::class ) );
		$rl->setDependencyStore( $this->createMock( DependencyStore::class ) );
		$rl->register( [
			'test.foo' => [
				'class' => ResourceLoaderTestModule::class,
				'script' => 'mw.test.foo( { a: true } );',
				'styles' => '.mw-test-foo { content: "style"; }',
			],
			'test.bar' => [
				'class' => ResourceLoaderTestModule::class,
				'script' => 'mw.test.bar( { a: true } );',
				'styles' => '.mw-test-bar { content: "style"; }',
			],
			'test.baz' => [
				'class' => ResourceLoaderTestModule::class,
				'script' => 'mw.test.baz( { a: true } );',
				'styles' => '.mw-test-baz { content: "style"; }',
			],
			'test.quux' => [
				'class' => ResourceLoaderTestModule::class,
				'script' => 'mw.test.baz( { token: 123 } );',
				'styles' => '/* pref-animate=off */ .mw-icon { transition: none; }',
				'group' => 'private',
			],
			'test.noscript' => [
				'class' => ResourceLoaderTestModule::class,
				'styles' => '.stuff { color: red; }',
				'group' => 'noscript',
			],
			'test.group.foo' => [
				'class' => ResourceLoaderTestModule::class,
				'script' => 'mw.doStuff( "foo" );',
				'group' => 'foo',
			],
			'test.group.bar' => [
				'class' => ResourceLoaderTestModule::class,
				'script' => 'mw.doStuff( "bar" );',
				'group' => 'bar',
			],
		] );
		$links = $method->invokeArgs( $out, $args );
		$actualHtml = strval( $links );
		$this->assertEquals( $expectedHtml, $actualHtml );
	}

	public static function provideMakeResourceLoaderLink() {
		return [
			// Single only=scripts load
			[
				[ 'test.foo', RL\Module::TYPE_SCRIPTS ],
				"<script>(RLQ=window.RLQ||[]).push(function(){"
					. 'mw.loader.load("http://127.0.0.1:8080/w/load.php?lang=en\u0026modules=test.foo\u0026only=scripts");'
					. "});</script>"
			],
			// Multiple only=styles load
			[
				[ [ 'test.baz', 'test.foo', 'test.bar' ], RL\Module::TYPE_STYLES ],

				'<link rel="stylesheet" href="http://127.0.0.1:8080/w/load.php?lang=en&amp;modules=test.bar%2Cbaz%2Cfoo&amp;only=styles">'
			],
			// Private embed (only=scripts)
			[
				[ 'test.quux', RL\Module::TYPE_SCRIPTS ],
				"<script>(RLQ=window.RLQ||[]).push(function(){"
					. "mw.test.baz({token:123});\nmw.loader.state({\"test.quux\":\"ready\"});"
					. "});</script>"
			],
			// Load private module (combined)
			[
				[ 'test.quux', RL\Module::TYPE_COMBINED ],
				"<script>(RLQ=window.RLQ||[]).push(function(){"
					. "mw.loader.impl(function(){return[\"test.quux@1b4i1\",function($,jQuery,require,module){"
					. "mw.test.baz({token:123});\n"
					. "},{\"css\":[\".mw-icon{transition:none}"
					. "\"]}];});});</script>"
			],
			// Load no modules
			[
				[ [], RL\Module::TYPE_COMBINED ],
				'',
			],
			// noscript group
			[
				[ 'test.noscript', RL\Module::TYPE_STYLES ],
				'<noscript><link rel="stylesheet" href="http://127.0.0.1:8080/w/load.php?lang=en&amp;modules=test.noscript&amp;only=styles"></noscript>'
			],
			// Load two modules in separate groups
			[
				[ [ 'test.group.foo', 'test.group.bar' ], RL\Module::TYPE_COMBINED ],
				"<script>(RLQ=window.RLQ||[]).push(function(){"
					. 'mw.loader.load("http://127.0.0.1:8080/w/load.php?lang=en\u0026modules=test.group.bar");'
					. 'mw.loader.load("http://127.0.0.1:8080/w/load.php?lang=en\u0026modules=test.group.foo");'
					. "});</script>"
			],
		];
		// phpcs:enable
	}

	/**
	 * @dataProvider provideBuildExemptModules
	 */
	public function testBuildExemptModules( array $exemptStyleModules, $expect ) {
		$this->overrideConfigValues( [
			MainConfigNames::ResourceLoaderDebug => false,
			MainConfigNames::LoadScript => '/w/load.php',
			// Stub wgCacheEpoch as it influences getVersionHash used for the
			// urls in the expected HTML
			MainConfigNames::CacheEpoch => '20140101000000',
		] );

		// Set up stubs
		$ctx = new RequestContext();
		$skinFactory = $this->getServiceContainer()->getSkinFactory();
		$ctx->setSkin( $skinFactory->makeSkin( 'fallback' ) );
		$ctx->setLanguage( 'en' );
		$op = $this->getMockBuilder( OutputPage::class )
			->setConstructorArgs( [ $ctx ] )
			->onlyMethods( [ 'buildCssLinksArray' ] )
			->getMock();
		$op->method( 'buildCssLinksArray' )
			->willReturn( [] );
		/** @var OutputPage $op */
		$rl = $op->getResourceLoader();
		$rl->setMessageBlobStore( $this->createMock( RL\MessageBlobStore::class ) );

		// Register custom modules
		$rl->register( [
			'example.site.a' => [ 'class' => ResourceLoaderTestModule::class, 'group' => 'site' ],
			'example.site.b' => [ 'class' => ResourceLoaderTestModule::class, 'group' => 'site' ],
			'example.user' => [ 'class' => ResourceLoaderTestModule::class, 'group' => 'user' ],
		] );

		$op = TestingAccessWrapper::newFromObject( $op );
		$op->rlExemptStyleModules = $exemptStyleModules;
		$expect = strtr( $expect, [
			'{blankCombi}' => ResourceLoaderTestCase::BLANK_COMBI,
		] );
		$this->assertEquals(
			$expect,
			strval( $op->buildExemptModules() )
		);
	}

	public static function provideBuildExemptModules() {
		return [
			'empty' => [
				'exemptStyleModules' => [],
				'',
			],
			'empty sets' => [
				'exemptStyleModules' => [ 'site' => [], 'noscript' => [], 'private' => [], 'user' => [] ],
				'',
			],
			'default logged-out' => [
				'exemptStyleModules' => [ 'site' => [ 'site.styles' ] ],
				'<meta name="ResourceLoaderDynamicStyles" content="">' . "\n" .
				'<link rel="stylesheet" href="/w/load.php?lang=en&amp;modules=site.styles&amp;only=styles">',
			],
			'default logged-in' => [
				'exemptStyleModules' => [ 'site' => [ 'site.styles' ], 'user' => [ 'user.styles' ] ],
				'<meta name="ResourceLoaderDynamicStyles" content="">' . "\n" .
				'<link rel="stylesheet" href="/w/load.php?lang=en&amp;modules=site.styles&amp;only=styles">' . "\n" .
				'<link rel="stylesheet" href="/w/load.php?lang=en&amp;modules=user.styles&amp;only=styles&amp;version=94mvi">',
			],
			'custom modules' => [
				'exemptStyleModules' => [
					'site' => [ 'site.styles', 'example.site.a', 'example.site.b' ],
					'user' => [ 'user.styles', 'example.user' ],
				],
				'<meta name="ResourceLoaderDynamicStyles" content="">' . "\n" .
				'<link rel="stylesheet" href="/w/load.php?lang=en&amp;modules=example.site.a%2Cb&amp;only=styles">' . "\n" .
				'<link rel="stylesheet" href="/w/load.php?lang=en&amp;modules=site.styles&amp;only=styles">' . "\n" .
				'<link rel="stylesheet" href="/w/load.php?lang=en&amp;modules=example.user&amp;only=styles&amp;version={blankCombi}">' . "\n" .
				'<link rel="stylesheet" href="/w/load.php?lang=en&amp;modules=user.styles&amp;only=styles&amp;version=94mvi">',
			],
		];
		// phpcs:enable
	}

	/**
	 * @dataProvider provideTransformFilePath
	 */
	public function testTransformResourcePath( $basePath, $uploadDir = null,
		$uploadPath = null, $path = null, $expected = null
	) {
		if ( $path === null ) {
			// Skip optional $uploadDir and $uploadPath
			$path = $uploadDir;
			$expected = $uploadPath;
			$uploadDir = MW_INSTALL_PATH . '/images';
			$uploadPath = "$basePath/images";
		}
		$conf = new HashConfig( [
			MainConfigNames::ResourceBasePath => $basePath,
			MainConfigNames::UploadDirectory => $uploadDir,
			MainConfigNames::UploadPath => $uploadPath,
		] );

		// Some of these paths don't exist and will cause warnings
		$actual = @OutputPage::transformResourcePath( $conf, $path );

		$this->assertEquals( $expected ?: $path, $actual );
	}

	public static function provideTransformFilePath() {
		$baseDir = dirname( __DIR__ ) . '/../data/media';
		return [
			// File that matches basePath, and exists. Hash found and appended.
			[
				'/w',
				'/w/tests/phpunit/data/media/test.jpg',
				'/w/tests/phpunit/data/media/test.jpg?edcf2'
			],
			// File that matches basePath, but not found on disk. Empty query.
			[
				'/w',
				'/w/unknown.png',
				'/w/unknown.png'
			],
			// File not matching basePath. Ignored.
			[
				'/w',
				'/files/test.jpg'
			],
			// Empty string. Ignored.
			[
				'/w',
				'',
				''
			],
			// Similar path, but with domain component. Ignored.
			[
				'/w',
				'//example.org/w/test.jpg'
			],
			[
				'/w',
				'https://www.example.org/w/test.jpg'
			],
			// Unrelated path with domain component. Ignored.
			[
				'/w',
				'https://www.example.org/files/test.jpg'
			],
			[
				'/w',
				'//example.org/files/test.jpg'
			],
			// Unrelated path with domain, and empty base path (root mw install). Ignored.
			[
				'',
				'https://www.example.org/files/test.jpg'
			],
			// T155310
			[
				'',
				'//example.org/files/test.jpg'
			],
			// Check UploadPath before ResourceBasePath (T155146)
			[
				'',
				'uploadDir' => $baseDir, 'uploadPath' => '/images',
				'/images/test.jpg',
				'/images/test.jpg?edcf2'
			],
		];
	}

	/**
	 * Tests a particular case of transformCssMedia, using the given input, globals,
	 * expected return, and message
	 *
	 * Asserts that $expectedReturn is returned.
	 *
	 * options['queryData'] - value of query string
	 * options['media'] - passed into the method under the same name
	 * options['expectedReturn'] - expected return value
	 * options['message'] - PHPUnit message for assertion
	 *
	 * @param array $args Key-value array of arguments as shown above
	 */
	protected function assertTransformCssMediaCase( $args ) {
		$queryData = $args['queryData'] ?? [];

		$fauxRequest = new FauxRequest( $queryData, false );
		$this->setRequest( $fauxRequest );

		$actualReturn = OutputPage::transformCssMedia( $args['media'] );
		$this->assertSame( $args['expectedReturn'], $actualReturn, $args['message'] );
	}

	public function testPrintRequests() {
		$this->assertTransformCssMediaCase( [
			'queryData' => [ 'printable' => '1' ],
			'media' => 'screen',
			'expectedReturn' => null,
			'message' => 'On printable request, screen returns null'
		] );

		$this->assertTransformCssMediaCase( [
			'queryData' => [ 'printable' => '1' ],
			'media' => self::SCREEN_MEDIA_QUERY,
			'expectedReturn' => null,
			'message' => 'On printable request, screen media query returns null'
		] );

		$this->assertTransformCssMediaCase( [
			'queryData' => [ 'printable' => '1' ],
			'media' => self::SCREEN_ONLY_MEDIA_QUERY,
			'expectedReturn' => null,
			'message' => 'On printable request, screen media query with only returns null'
		] );

		$this->assertTransformCssMediaCase( [
			'queryData' => [ 'printable' => '1' ],
			'media' => 'print',
			'expectedReturn' => '',
			'message' => 'On printable request, media print returns empty string'
		] );
	}

	/**
	 * Test screen requests, without either query parameter set
	 */
	public function testScreenRequests() {
		$this->assertTransformCssMediaCase( [
			'media' => 'screen',
			'expectedReturn' => 'screen',
			'message' => 'On screen request, screen media type is preserved'
		] );

		$this->assertTransformCssMediaCase( [
			'media' => 'handheld',
			'expectedReturn' => 'handheld',
			'message' => 'On screen request, handheld media type is preserved'
		] );

		$this->assertTransformCssMediaCase( [
			'media' => self::SCREEN_MEDIA_QUERY,
			'expectedReturn' => self::SCREEN_MEDIA_QUERY,
			'message' => 'On screen request, screen media query is preserved.'
		] );

		$this->assertTransformCssMediaCase( [
			'media' => self::SCREEN_ONLY_MEDIA_QUERY,
			'expectedReturn' => self::SCREEN_ONLY_MEDIA_QUERY,
			'message' => 'On screen request, screen media query with only is preserved.'
		] );

		$this->assertTransformCssMediaCase( [
			'media' => 'print',
			'expectedReturn' => 'print',
			'message' => 'On screen request, print media type is preserved'
		] );
	}

	public function testIsTOCEnabled() {
		$op = $this->newInstance();
		$this->assertFalse( $op->isTOCEnabled() );
		$this->assertFalse( $op->getOutputFlag( ParserOutputFlags::SHOW_TOC ) );

		$pOut1 = $this->createParserOutputStub();
		$op->addParserOutputMetadata( $pOut1 );
		$this->assertFalse( $op->isTOCEnabled() );
		$this->assertFalse( $op->getOutputFlag( ParserOutputFlags::SHOW_TOC ) );

		$pOut2 = $this->createParserOutputStubWithFlags(
			[], [ ParserOutputFlags::SHOW_TOC ]
		);
		$op->addParserOutput( $pOut2, ParserOptions::newFromAnon() );
		$this->assertTrue( $op->isTOCEnabled() );
		$this->assertTrue( $op->getOutputFlag( ParserOutputFlags::SHOW_TOC ) );

		// The parser output doesn't disable the TOC after it was enabled
		$op->addParserOutputMetadata( $pOut1 );
		$this->assertTrue( $op->isTOCEnabled() );
		$this->assertTrue( $op->getOutputFlag( ParserOutputFlags::SHOW_TOC ) );
	}

	public function testNoTOC() {
		$op = $this->newInstance();
		$this->assertFalse( $op->getOutputFlag( ParserOutputFlags::NO_TOC ) );

		$stubPO1 = $this->createParserOutputStubWithFlags(
			[], [ ParserOutputFlags::NO_TOC ]
		);
		$op->addParserOutputMetadata( $stubPO1 );
		$this->assertTrue( $op->getOutputFlag( ParserOutputFlags::NO_TOC ) );

		$stubPO2 = $this->createParserOutputStub();
		$this->assertFalse( $stubPO2->getOutputFlag( ParserOutputFlags::NO_TOC ) );
		$op->addParserOutput( $stubPO2, ParserOptions::newFromAnon() );
		// Note that flags are OR'ed together, and not reset.
		$this->assertTrue( $op->getOutputFlag( ParserOutputFlags::NO_TOC ) );
	}

	/**
	 * @dataProvider providePreloadLinkHeaders
	 * @covers \MediaWiki\ResourceLoader\SkinModule
	 */
	public function testPreloadLinkHeaders( $config, $result ) {
		$ctx = $this->createMock( RL\Context::class );
		$module = new RL\SkinModule();
		$module->setConfig( new HashConfig( $config + ResourceLoaderTestCase::getSettings() ) );

		$this->assertEquals( [ $result ], $module->getHeaders( $ctx ) );
	}

	public static function providePreloadLinkHeaders() {
		return [
			[
				[
					MainConfigNames::ResourceBasePath => '/w',
					MainConfigNames::Logo => '/img/default.png',
					MainConfigNames::Logos => [
						'1.5x' => '/img/one-point-five.png',
						'2x' => '/img/two-x.png',
					],
				],
				'Link: </img/default.png>;rel=preload;as=image;media=' .
				'not all and (min-resolution: 1.5dppx),' .
				'</img/one-point-five.png>;rel=preload;as=image;media=' .
				'(min-resolution: 1.5dppx) and (max-resolution: 1.999999dppx),' .
				'</img/two-x.png>;rel=preload;as=image;media=(min-resolution: 2dppx)'
			],
			[
				[
					MainConfigNames::ResourceBasePath => '/w',
					MainConfigNames::Logos => [
						'1x' => '/img/default.png',
					],
				],
				'Link: </img/default.png>;rel=preload;as=image'
			],
			[
				[
					MainConfigNames::ResourceBasePath => '/w',
					MainConfigNames::Logos => [
						'1x' => '/img/default.png',
						'2x' => '/img/two-x.png',
					],
				],
				'Link: </img/default.png>;rel=preload;as=image;media=' .
				'not all and (min-resolution: 2dppx),' .
				'</img/two-x.png>;rel=preload;as=image;media=(min-resolution: 2dppx)'
			],
			[
				[
					MainConfigNames::ResourceBasePath => '/w',
					MainConfigNames::Logos => [
						'1x' => '/img/default.png',
						'svg' => '/img/vector.svg',
					],
				],
				'Link: </img/vector.svg>;rel=preload;as=image'

			],
			[
				[
					MainConfigNames::ResourceBasePath => '/w',
					MainConfigNames::Logos => [
						'1x' => '/w/tests/phpunit/data/media/test.jpg',
					],
					MainConfigNames::UploadPath => '/w/images',
				],
				'Link: </w/tests/phpunit/data/media/test.jpg?edcf2>;rel=preload;as=image',
			],
		];
	}

	/**
	 * @param int $titleLastRevision Last Title revision to set
	 * @param int $outputRevision Revision stored in OutputPage
	 * @param bool $expectedResult Expected result of $output->isRevisionCurrent call
	 * @dataProvider provideIsRevisionCurrent
	 */
	public function testIsRevisionCurrent( $titleLastRevision, $outputRevision, $expectedResult ) {
		$titleMock = $this->createMock( Title::class );
		$titleMock->method( 'getLatestRevID' )
			->willReturn( $titleLastRevision );

		$output = $this->newInstance( [], null );
		$output->setTitle( $titleMock );
		$output->setRevisionId( $outputRevision );
		$this->assertEquals( $expectedResult, $output->isRevisionCurrent() );
	}

	public static function provideIsRevisionCurrent() {
		return [
			[ 10, null, true ],
			[ 42, 42, true ],
			[ null, 0, true ],
			[ 42, 47, false ],
			[ 47, 42, false ]
		];
	}

	/**
	 * @dataProvider provideSendCacheControl
	 */
	public function testSendCacheControl( array $options = [], array $expectations = [] ) {
		$this->overrideConfigValue( MainConfigNames::UsePigLatinVariant, $options['variant'] ?? false );

		$output = $this->newInstance( [
			MainConfigNames::UseCdn => $options['useCdn'] ?? false,
		] );
		$output->considerCacheSettingsFinal();

		$cacheable = $options['enableClientCache'] ?? true;
		if ( !$cacheable ) {
			$output->disableClientCache();
		}
		$this->assertEquals( $cacheable, $output->couldBePublicCached() );

		$output->setCdnMaxage( $options['cdnMaxAge'] ?? 0 );

		if ( isset( $options['lastModified'] ) ) {
			$output->setLastModified( $options['lastModified'] );
		}

		$response = $output->getRequest()->response();
		if ( isset( $options['cookie'] ) ) {
			$response->setCookie( 'test', 1234 );
		}

		$output->sendCacheControl();

		$headers = [
			'Vary' => 'Accept-Encoding, Cookie',
			'Cache-Control' => 'private, must-revalidate, max-age=0',
			'Expires' => true,
			'Last-Modified' => false,
		];

		foreach ( $headers as $header => $default ) {
			$value = $expectations[$header] ?? $default;
			if ( $value === true ) {
				$this->assertNotEmpty( $response->getHeader( $header ), "$header header" );
			} elseif ( $value === false ) {
				$this->assertNull( $response->getHeader( $header ), "$header header" );
			} else {
				$this->assertEquals( $value, $response->getHeader( $header ), "$header header" );
			}
		}
	}

	public static function provideSendCacheControl() {
		return [
			'Vary on variant' => [
				[
					'variant' => true,
				],
				[
					'Vary' => 'Accept-Encoding, Cookie, Accept-Language',
				]
			],
			'Private by default' => [
				[],
				[
					'Cache-Control' => 'private, must-revalidate, max-age=0',
				],
			],
			'Cookies force private' => [
				[
					'cookie' => true,
					'useCdn' => true,
					'cdnMaxAge' => 300,
				],
				[
					'Cache-Control' => 'private, must-revalidate, max-age=0',
				]
			],
			'Disable client cache' => [
				[
					'enableClientCache' => false,
					'useCdn' => true,
					'cdnMaxAge' => 300,
				],
				[
					'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
				],
			],
			'Set last modified' => [
				[
					// 0 is the current time, so we'll use 1 instead.
					'lastModified' => 1,
				],
				[
					'Last-Modified' => 'Thu, 01 Jan 1970 00:00:01 GMT',
				]
			],
			'Public' => [
				[
					'useCdn' => true,
					'cdnMaxAge' => 300,
				],
				[
					'Cache-Control' => 's-maxage=300, must-revalidate, max-age=0',
					'Expires' => false,
				],
			],
		];
	}

	public static function provideGetJsVarsEditable() {
		yield 'can edit and create' => [
			'performerSpec' => 'with',
			'expectedEditableConfig' => [
				'wgIsProbablyEditable' => true,
				'wgRelevantPageIsProbablyEditable' => true,
			]
		];
		yield 'cannot edit or create' => [
			'performerSpec' => 'without',
			'expectedEditableConfig' => [
				'wgIsProbablyEditable' => false,
				'wgRelevantPageIsProbablyEditable' => false,
			]
		];
		yield 'only can edit relevant title' => [
			'performerSpec' => static function (
				string $permission,
				PageIdentity $page
			) {
				return ( $permission === 'edit' || $permission === 'create' ) && $page->getDBkey() === 'RelevantTitle';
			},
			'expectedEditableConfig' => [
				'wgIsProbablyEditable' => false,
				'wgRelevantPageIsProbablyEditable' => true,
			]
		];
	}

	/**
	 * @dataProvider provideGetJsVarsEditable
	 */
	public function testGetJsVarsEditable( $performerSpec, array $expectedEditableConfig ) {
		if ( is_string( $performerSpec ) ) {
			$performer = $performerSpec === 'with'
				? $this->mockAnonAuthorityWithPermissions( [ 'edit', 'create' ] )
				: $this->mockAnonAuthorityWithoutPermissions( [ 'edit', 'create' ] );
		} else {
			$performer = $this->mockAnonAuthority( $performerSpec );
		}
		$op = $this->newInstance( [], null, null, $performer );
		$op->getContext()->getSkin()->setRelevantTitle( Title::makeTitle( NS_MAIN, 'RelevantTitle' ) );
		$this->assertArraySubmapSame( $expectedEditableConfig, $op->getJSVars() );
	}

	public static function provideJsVarsAboutPageLang() {
		// Format:
		// - expected
		// - title
		// - site content language
		// - user language
		// - wgDefaultLanguageVariant
		return [
			[ 'fr', [ NS_HELP, 'I_need_somebody' ], 'fr', 'fr', false ],
			[ 'es', [ NS_HELP, 'I_need_somebody' ], 'es', 'zh-tw', false ],
			[ 'zh', [ NS_HELP, 'I_need_somebody' ], 'zh', 'zh-tw', false ],
			[ 'es', [ NS_HELP, 'I_need_somebody' ], 'es', 'zh-tw', 'zh-cn' ],
			[ 'es', [ NS_MEDIAWIKI, 'About' ], 'es', 'zh-tw', 'zh-cn' ],
			[ 'es', [ NS_MEDIAWIKI, 'About/' ], 'es', 'zh-tw', 'zh-cn' ],
			[ 'de', [ NS_MEDIAWIKI, 'About/de' ], 'es', 'zh-tw', 'zh-cn' ],
			[ 'en', [ NS_MEDIAWIKI, 'Common.js' ], 'es', 'zh-tw', 'zh-cn' ],
			[ 'en', [ NS_MEDIAWIKI, 'Common.css' ], 'es', 'zh-tw', 'zh-cn' ],
			[ 'en', [ NS_USER, 'JohnDoe/Common.js' ], 'es', 'zh-tw', 'zh-cn' ],
			[ 'en', [ NS_USER, 'JohnDoe/Monobook.css' ], 'es', 'zh-tw', 'zh-cn' ],

			[ 'zh-cn', [ NS_HELP, 'I_need_somebody' ], 'zh', 'zh-tw', 'zh-cn' ],
			[ 'zh', [ NS_MEDIAWIKI, 'About' ], 'zh', 'zh-tw', 'zh-cn' ],
			[ 'zh', [ NS_MEDIAWIKI, 'About/' ], 'zh', 'zh-tw', 'zh-cn' ],
			[ 'de', [ NS_MEDIAWIKI, 'About/de' ], 'zh', 'zh-tw', 'zh-cn' ],
			[ 'zh-cn', [ NS_MEDIAWIKI, 'About/zh-cn' ], 'zh', 'zh-tw', 'zh-cn' ],
			[ 'zh-tw', [ NS_MEDIAWIKI, 'About/zh-tw' ], 'zh', 'zh-tw', 'zh-cn' ],
			[ 'en', [ NS_MEDIAWIKI, 'Common.js' ], 'zh', 'zh-tw', 'zh-cn' ],
			[ 'en', [ NS_MEDIAWIKI, 'Common.css' ], 'zh', 'zh-tw', 'zh-cn' ],
			[ 'en', [ NS_USER, 'JohnDoe/Common.js' ], 'zh', 'zh-tw', 'zh-cn' ],
			[ 'en', [ NS_USER, 'JohnDoe/Monobook.css' ], 'zh', 'zh-tw', 'zh-cn' ],

			[ 'nl', [ NS_SPECIAL, 'BlankPage' ], 'en', 'nl', false ],
			[ 'zh-tw', [ NS_SPECIAL, 'NewPages' ], 'es', 'zh-tw', 'zh-cn' ],
			[ 'zh-tw', [ NS_SPECIAL, 'NewPages' ], 'zh', 'zh-tw', 'zh-cn' ],

			[ 'sr-ec', [ NS_FILE, 'Example' ], 'sr', 'sr', 'sr-ec' ],
			[ 'sr', [ NS_FILE, 'Example' ], 'sr', 'sr', 'sr' ],
			[ 'sr-ec', [ NS_MEDIAWIKI, 'Example' ], 'sr-ec', 'sr-ec', 'sr' ],
			[ 'sr', [ NS_MEDIAWIKI, 'Example' ], 'sr', 'sr', 'sr-ec' ],
		];
	}

	/**
	 * @dataProvider provideJsVarsAboutPageLang
	 */
	public function testGetJsVarsAboutPageLang( $expected, $title, $contLang, $userLang, $variant ) {
		$this->overrideConfigValues( [
			MainConfigNames::DefaultLanguageVariant => $variant,
		] );
		$this->setContentLang( $contLang );
		$output = $this->newInstance(
			[ MainConfigNames::LanguageCode => $contLang ],
			new FauxRequest( [ 'uselang' => $userLang ] ),
			'notitle'
		);
		$output->setTitle( Title::makeTitle( $title[0], $title[1] ) );

		$this->assertArraySubmapSame( [
			'wgPageViewLanguage' => $expected,
			'wgPageContentLanguage' => $expected,
		], $output->getJSVars() );
	}

	/**
	 * @param bool $registered
	 * @param bool $matchToken
	 * @return MockObject|User
	 */
	private function mockUser( bool $registered, bool $matchToken ) {
		$user = $this->createNoOpMock( User::class, [ 'isRegistered', 'matchEditToken' ] );
		$user->method( 'isRegistered' )->willReturn( $registered );
		$user->method( 'matchEditToken' )->willReturn( $matchToken );
		return $user;
	}

	public static function provideUserCanPreview() {
		yield 'all good' => [
			'performerSpec' => [ 'with', true, true ],
			'request' => new FauxRequest( [ 'action' => 'submit' ], true ),
			true
		];
		yield 'get request' => [
			'performerSpec' => [ 'with', true, true ],
			'request' => new FauxRequest( [ 'action' => 'submit' ], false ),
			false
		];
		yield 'not a submit action' => [
			'performerSpec' => [ 'with', true, true ],
			'request' => new FauxRequest( [ 'action' => 'something' ], true ),
			false
		];
		yield 'anon can not' => [
			'performerSpec' => [ 'with', false, true ],
			'request' => new FauxRequest( [ 'action' => 'submit' ], true ),
			false
		];
		yield 'token not match' => [
			'performerSpec' => [ 'with', true, false ],
			'request' => new FauxRequest( [ 'action' => 'submit' ], true ),
			false
		];
		yield 'no permission' => [
			'performerSpec' => [ 'without', true, true ],
			'request' => new FauxRequest( [ 'action' => 'submit' ], true ),
			false
		];
	}

	/**
	 * @dataProvider provideUserCanPreview
	 */
	public function testUserCanPreview( $performerSpec, WebRequest $request, bool $expected ) {
		$mockedUser = $this->mockUser( $performerSpec[1], $performerSpec[2] );
		$performer = $performerSpec[0] === 'with'
			? $this->mockUserAuthorityWithPermissions( $mockedUser, [ 'edit' ] )
			: $this->mockUserAuthorityWithoutPermissions( $mockedUser, [ 'edit' ] );
		$op = $this->newInstance( [], $request, null, $performer );
		$this->assertSame( $expected, $op->userCanPreview() );
	}

	public static function providePermissionStatus() {
		yield 'no errors' => [
			PermissionStatus::newEmpty(),
			'',
		];

		yield 'one message' => [
			PermissionStatus::newEmpty()->fatal( 'badaccess-group0' ),
			'(permissionserrorstext: 1)

<div class="permissions-errors"><div class="mw-permissionerror-badaccess-group0">(badaccess-group0)</div></div>',
		];

		yield 'two messages' => [
			PermissionStatus::newEmpty()->fatal( 'badaccess-group0' )->fatal( 'foobar' ),
			'(permissionserrorstext: 2)

<ul class="permissions-errors"><li class="mw-permissionerror-badaccess-group0">(badaccess-group0)</li><li class="mw-permissionerror-foobar">(foobar)</li></ul>',
		];
	}

	public static function provideFormatPermissionStatus() {
		yield 'RawMessage' => [
			PermissionStatus::newEmpty()->fatal( new RawMessage( 'Foo Bar' ) ),
			'(permissionserrorstext: 1)

<div class="permissions-errors"><div class="mw-permissionerror-rawmessage">Foo Bar</div></div>',
		];
	}

	/**
	 * @dataProvider providePermissionStatus
	 * @dataProvider provideFormatPermissionStatus
	 */
	public function testFormatPermissionStatus( PermissionStatus $status, string $expected ) {
		$this->overrideConfigValue( MainConfigNames::LanguageCode, 'qqx' );

		$actual = self::newInstance()->formatPermissionStatus( $status );
		$this->assertEquals( $expected, $actual );
	}

	private function newInstance(
		array $config = [],
		?WebRequest $request = null,
		$option = null,
		?Authority $performer = null
	): OutputPage {
		$this->overrideConfigValues( [
			// Avoid configured skin affecting the headings
			MainConfigNames::ParserEnableLegacyHeadingDOM => false,
			MainConfigNames::DefaultSkin => 'fallback',
			MainConfigNames::HiddenPrefs => [ 'skin' ],
		] );

		$context = new RequestContext();

		$context->setConfig( new MultiConfig( [
			new HashConfig( $config + [
				MainConfigNames::AppleTouchIcon => false,
				MainConfigNames::EnableCanonicalServerLink => false,
				MainConfigNames::Favicon => false,
				MainConfigNames::Feed => false,
				MainConfigNames::LanguageCode => false,
				MainConfigNames::ReferrerPolicy => false,
				MainConfigNames::RightsPage => false,
				MainConfigNames::RightsUrl => false,
				MainConfigNames::UniversalEditButton => false,
			] ),
			$this->getServiceContainer()->getMainConfig(),
		] ) );

		if ( $option !== 'notitle' ) {
			$context->setTitle( Title::makeTitle( NS_MAIN, 'My test page' ) );
		}

		if ( $request ) {
			$context->setRequest( $request );
		}

		if ( $performer ) {
			$context->setAuthority( $performer );
		}

		return new OutputPage( $context );
	}
}
