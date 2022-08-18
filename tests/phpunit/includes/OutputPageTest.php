<?php

use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\PageReference;
use MediaWiki\Page\PageReferenceValue;
use MediaWiki\Page\PageStoreRecord;
use MediaWiki\Permissions\Authority;
use MediaWiki\ResourceLoader as RL;
use MediaWiki\ResourceLoader\ResourceLoader;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use PHPUnit\Framework\MockObject\MockObject;
use Wikimedia\DependencyStore\KeyValueDependencyStore;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\TestingAccessWrapper;

/**
 * @author Matthew Flaschen
 *
 * @group Database
 * @group Output
 */
class OutputPageTest extends MediaWikiIntegrationTestCase {
	use MockAuthorityTrait;
	use MockTitleTrait;

	private const SCREEN_MEDIA_QUERY = 'screen and (min-width: 982px)';
	private const SCREEN_ONLY_MEDIA_QUERY = 'only screen and (min-width: 982px)';
	private const RSS_RC_LINK = '<link rel="alternate" type="application/rss+xml" title=" RSS feed" href="/w/index.php?title=Special:RecentChanges&amp;feed=rss"/>';
	private const ATOM_RC_LINK = '<link rel="alternate" type="application/atom+xml" title=" Atom feed" href="/w/index.php?title=Special:RecentChanges&amp;feed=atom"/>';

	private const RSS_TEST_LINK = '<link rel="alternate" type="application/rss+xml" title="&quot;Test&quot; RSS feed" href="fake-link"/>';
	private const ATOM_TEST_LINK = '<link rel="alternate" type="application/atom+xml" title="&quot;Test&quot; Atom feed" href="fake-link"/>';
	// phpcs:enable

	// Ensure that we don't affect the global ResourceLoader state.
	protected function setUp(): void {
		parent::setUp();
		ResourceLoader::clearCache();
	}

	protected function tearDown(): void {
		ResourceLoader::clearCache();
		parent::tearDown();
	}

	/**
	 * @dataProvider provideRedirect
	 *
	 * @covers OutputPage::__construct
	 * @covers OutputPage::redirect
	 * @covers OutputPage::getRedirect
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

	public function provideRedirect() {
		return [
			[ 'http://example.com' ],
			[ 'http://example.com', '400' ],
			[ 'http://example.com', 'squirrels!!!' ],
			[ "a\nb" ],
		];
	}

	private function setupFeedLinks( $feed, $types ): OutputPage {
		$outputPage = $this->newInstance( [
			'AdvertisedFeedTypes' => $types,
			'Feed' => $feed,
			'OverrideSiteFeed' => false,
			'Script' => '/w',
			'Sitename' => false,
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

	/**
	 * @covers OutputPage::setCopyrightUrl
	 * @covers OutputPage::getHeadLinksArray
	 */
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
	 * @covers OutputPage::getHeadLinksArray
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
	 * @covers OutputPage::getHeadLinksArray
	 * @covers OutputPage::addFeedLink
	 * @covers OutputPage::getSyndicationLinks
	 * @covers OutputPage::isSyndicated
	 */
	public function testAdditionalFeeds( $feed, $advertised_feed_types, $message,
			$additional_feed_type, $present, $non_present, $any_ui_links ) {
		$outputPage = $this->setupFeedLinks( $feed, $advertised_feed_types );
		$outputPage->addFeedLink( $additional_feed_type, 'fake-link' );
		$this->assertFeedLinks( $outputPage, $message, $present, $non_present );
		$this->assertFeedUILinks( $outputPage, $any_ui_links );
	}

	// @todo How to test setStatusCode?

	/**
	 * @covers OutputPage::addMeta
	 * @covers OutputPage::getMetaTags
	 * @covers OutputPage::getHeadLinksArray
	 */
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
		$this->assertContains( '<meta http-equiv="expires" content="0"/>', $links );
		$this->assertContains( '<meta name="keywords" content="first"/>', $links );
		$this->assertContains( '<meta name="keywords" content="second"/>', $links );
		$this->assertContains( '<meta property="og:title" content="Ta-duh"/>', $links );
		$this->assertArrayNotHasKey( 'meta-robots', $links );
	}

	/**
	 * @covers OutputPage::addLink
	 * @covers OutputPage::getLinkTags
	 * @covers OutputPage::getHeadLinksArray
	 */
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

	/**
	 * @covers OutputPage::setCanonicalUrl
	 * @covers OutputPage::getCanonicalUrl
	 * @covers OutputPage::getHeadLinksArray
	 */
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

	/**
	 * @covers OutputPage::addScript
	 */
	public function testAddScript() {
		$op = $this->newInstance();
		$op->addScript( 'some random string' );

		$this->assertStringContainsString(
			"\nsome random string\n",
			"\n" . $op->getBottomScripts() . "\n"
		);
	}

	/**
	 * @covers OutputPage::addScriptFile
	 */
	public function testAddScriptFile() {
		$op = $this->newInstance();
		$op->addScriptFile( '/somescript.js' );
		$op->addScriptFile( '//example.com/somescript.js' );

		$this->assertStringContainsString(
			"\n" . Html::linkedScript( '/somescript.js', $op->getCSP()->getNonce() ) .
				Html::linkedScript( '//example.com/somescript.js', $op->getCSP()->getNonce() ) . "\n",
			"\n" . $op->getBottomScripts() . "\n"
		);
	}

	/**
	 * @covers OutputPage::addInlineScript
	 */
	public function testAddInlineScript() {
		$op = $this->newInstance();
		$op->addInlineScript( 'let foo = "bar";' );
		$op->addInlineScript( 'alert( foo );' );

		$this->assertStringContainsString(
			"\n" . Html::inlineScript( "\nlet foo = \"bar\";\n", $op->getCSP()->getNonce() ) . "\n" .
				Html::inlineScript( "\nalert( foo );\n", $op->getCSP()->getNonce() ) . "\n",
			"\n" . $op->getBottomScripts() . "\n"
		);
	}

	// @todo How to test filterModules(), warnModuleTargetFilter(), getModules(), etc.?

	/**
	 * @covers OutputPage::getTarget
	 * @covers OutputPage::setTarget
	 */
	public function testSetTarget() {
		$op = $this->newInstance();
		$op->setTarget( 'foo' );

		$this->assertSame( 'foo', $op->getTarget() );
		// @todo What else?  Test some actual effect?
	}

	// @todo How to test addContentOverride(Callback)?

	/**
	 * @covers OutputPage::getHeadItemsArray
	 * @covers OutputPage::addHeadItem
	 * @covers OutputPage::addHeadItems
	 * @covers OutputPage::hasHeadItem
	 */
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

	/**
	 * @covers OutputPage::getHeadItemsArray
	 * @covers OutputPage::addParserOutputMetadata
	 * @covers OutputPage::addParserOutput
	 */
	public function testHeadItemsParserOutput() {
		$op = $this->newInstance();
		$stubPO1 = $this->createParserOutputStub( 'getHeadItems', [ 'a' => 'b' ] );
		$op->addParserOutputMetadata( $stubPO1 );
		$stubPO2 = $this->createParserOutputStub( 'getHeadItems',
			[ 'c' => '<d>&amp;', 'e' => 'f', 'a' => 'q' ] );
		$op->addParserOutputMetadata( $stubPO2 );
		$stubPO3 = $this->createParserOutputStub( 'getHeadItems', [ 'e' => 'g' ] );
		$op->addParserOutput( $stubPO3 );
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

	/**
	 * @covers OutputPage::addParserOutputMetadata
	 * @covers OutputPage::addParserOutput
	 */
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
			$this->assertRegExp( $regex, $actual, $type );
		}
	}

	/**
	 * @covers OutputPage::addBodyClasses
	 */
	public function testAddBodyClasses() {
		$op = $this->newInstance();
		$op->addBodyClasses( 'a' );
		$op->addBodyClasses( 'mediawiki' );
		$op->addBodyClasses( 'b c' );
		$op->addBodyClasses( [ 'd', 'e' ] );
		$op->addBodyClasses( 'a' );

		$this->assertStringContainsString( '"a mediawiki b c d e ltr',
			'' . $op->headElement( $op->getContext()->getSkin() ) );
	}

	/**
	 * @covers OutputPage::setArticleBodyOnly
	 * @covers OutputPage::getArticleBodyOnly
	 */
	public function testArticleBodyOnly() {
		$op = $this->newInstance();
		$this->assertFalse( $op->getArticleBodyOnly() );

		$op->setArticleBodyOnly( true );
		$this->assertTrue( $op->getArticleBodyOnly() );

		$op->addHTML( '<b>a</b>' );

		$this->assertSame( '<b>a</b>', $op->output( true ) );
	}

	/**
	 * @covers OutputPage::setProperty
	 * @covers OutputPage::getProperty
	 */
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
	 *
	 * @covers OutputPage::checkLastModified
	 * @covers OutputPage::getCdnCacheEpoch
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

		if ( !isset( $config['CacheEpoch'] ) ) {
			// Make sure it's not too recent
			$config['CacheEpoch'] = '20000101000000';
		}

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
				[ $lastModified, $lastModified, false, [ 'CachePages' => false ] ],
			'$wgCacheEpoch' =>
				[ $lastModified, $lastModified, false,
					[ 'CacheEpoch' => wfTimestamp( TS_MW, $lastModified + 1 ) ] ],
			'Recently-touched user' =>
				[ $lastModified, $lastModified, false, [],
				function ( OutputPage $op ) {
					$op->getContext()->setUser( $this->getTestUser()->getUser() );
				} ],
			'After CDN expiry' =>
				[ $lastModified, $lastModified, false,
					[ 'UseCdn' => true, 'CdnMaxAge' => 3599 ] ],
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

	/**
	 * @dataProvider provideCdnCacheEpoch
	 *
	 * @covers OutputPage::getCdnCacheEpoch
	 */
	public function testCdnCacheEpoch( $params ) {
		$out = TestingAccessWrapper::newFromObject( $this->newInstance() );
		$reqTime = strtotime( $params['reqTime'] );
		$pageTime = strtotime( $params['pageTime'] );
		$actual = max( $pageTime, $out->getCdnCacheEpoch( $reqTime, $params['maxAge'] ) );

		$this->assertEquals(
			$params['expect'],
			gmdate( DateTime::ATOM, $actual ),
			'cdn epoch'
		);
	}

	public static function provideCdnCacheEpoch() {
		$base = [
			'pageTime' => '2011-04-01T12:00:00+00:00',
			'maxAge' => 24 * 3600,
		];
		return [
			'after 1s' => [ $base + [
				'reqTime' => '2011-04-01T12:00:01+00:00',
				'expect' => '2011-04-01T12:00:00+00:00',
			] ],
			'after 23h' => [ $base + [
				'reqTime' => '2011-04-02T11:00:00+00:00',
				'expect' => '2011-04-01T12:00:00+00:00',
			] ],
			'after 24h and a bit' => [ $base + [
				'reqTime' => '2011-04-02T12:34:56+00:00',
				'expect' => '2011-04-01T12:34:56+00:00',
			] ],
			'after a year' => [ $base + [
				'reqTime' => '2012-05-06T00:12:07+00:00',
				'expect' => '2012-05-05T00:12:07+00:00',
			] ],
		];
	}

	// @todo How to test setLastModified?

	/**
	 * @covers OutputPage::setRobotPolicy
	 * @covers OutputPage::getHeadLinksArray
	 */
	public function testSetRobotPolicy() {
		$op = $this->newInstance();
		$op->setRobotPolicy( 'noindex, nofollow' );

		$links = $op->getHeadLinksArray();
		$this->assertContains( '<meta name="robots" content="noindex,nofollow"/>', $links );
	}

	/**
	 * @covers OutputPage::setRobotPolicy
	 * @covers OutputPage::setRobotsOptions
	 * @covers OutputPage::setIndexPolicy
	 * @covers OutputPage::getHeadLinksArray
	 */
	public function testSetRobotsOptions() {
		$op = $this->newInstance();
		$op->setRobotPolicy( 'noindex, nofollow' );
		$op->setRobotsOptions( [ 'max-snippet' => '500' ] );
		$op->setIndexPolicy( 'index' );

		$links = $op->getHeadLinksArray();
		$this->assertContains( '<meta name="robots" content="index,nofollow,max-snippet:500"/>', $links );

		$op->setFollowPolicy( 'follow' );
		$links = $op->getHeadLinksArray();
		$this->assertContains(
			'<meta name="robots" content="max-snippet:500"/>',
			$links,
			'When index,follow (browser default) omit'
		);
	}

	/**
	 * @covers OutputPage::setRobotPolicy
	 * @covers OutputPage::getRobotPolicy
	 */
	public function testGetRobotPolicy() {
		$op = $this->newInstance();
		$op->setRobotPolicy( 'noindex, follow' );

		$policy = $op->getRobotPolicy();
		$this->assertSame( 'noindex,follow', $policy );
	}

	/**
	 * @covers OutputPage::setIndexPolicy
	 * @covers OutputPage::setFollowPolicy
	 * @covers OutputPage::getHeadLinksArray
	 */
	public function testSetIndexFollowPolicies() {
		$op = $this->newInstance();
		$op->setIndexPolicy( 'noindex' );
		$op->setFollowPolicy( 'nofollow' );

		$links = $op->getHeadLinksArray();
		$this->assertContains( '<meta name="robots" content="noindex,nofollow"/>', $links );
	}

	private function extractHTMLTitle( OutputPage $op ) {
		$html = $op->headElement( $op->getContext()->getSkin() );

		// OutputPage should always output the title in a nice format such that regexes will work
		// fine.  If it doesn't, we'll fail the tests.
		preg_match_all( '!<title>(.*?)</title>!', $html, $matches );

		$this->assertLessThanOrEqual( 1, count( $matches[1] ), 'More than one <title>!' );

		if ( !count( $matches[1] ) ) {
			return null;
		}

		return $matches[1][0];
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

	/**
	 * @covers OutputPage::setHTMLTitle
	 * @covers OutputPage::getHTMLTitle
	 */
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

	/**
	 * @covers OutputPage::setRedirectedFrom
	 */
	public function testSetRedirectedFrom() {
		$op = $this->newInstance();

		$op->setRedirectedFrom( new PageReferenceValue( NS_TALK, 'Some page', PageReference::LOCAL ) );
		$this->assertSame( 'Talk:Some_page', $op->getJSVars()['wgRedirectedFrom'] );
	}

	/**
	 * @covers OutputPage::setPageTitle
	 * @covers OutputPage::getPageTitle
	 */
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

		// Test set to message
		$text = $this->getMsgText( $op, 'mainpage' );

		$op->setPageTitle( $op->msg( 'mainpage' )->inContentLanguage() );
		$this->assertSame( $text, $op->getPageTitle() );
		$this->assertSame( $this->getMsgText( $op, 'pagetitle', $text ), $op->getHTMLTitle() );
	}

	/**
	 * @covers OutputPage::setTitle
	 */
	public function testSetTitle() {
		$op = $this->newInstance();

		$this->assertSame( 'My test page', $op->getTitle()->getPrefixedText() );

		$op->setTitle( Title::newFromText( 'Another test page' ) );

		$this->assertSame( 'Another test page', $op->getTitle()->getPrefixedText() );
	}

	/**
	 * @covers OutputPage::setSubtitle
	 * @covers OutputPage::clearSubtitle
	 * @covers OutputPage::addSubtitle
	 * @covers OutputPage::getSubtitle
	 */
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
	 *
	 * @covers OutputPage::buildBacklinkSubtitle
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
	 *
	 * @covers OutputPage::addBacklinkSubtitle
	 * @covers OutputPage::getSubtitle
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

	/**
	 * @covers OutputPage::setPrintable
	 * @covers OutputPage::isPrintable
	 */
	public function testPrintable() {
		$op = $this->newInstance();

		$this->assertFalse( $op->isPrintable() );

		$op->setPrintable();

		$this->assertTrue( $op->isPrintable() );
	}

	/**
	 * @covers OutputPage::disable
	 * @covers OutputPage::isDisabled
	 */
	public function testDisable() {
		$op = $this->newInstance();

		$this->assertFalse( $op->isDisabled() );
		$this->assertNotSame( '', $op->output( true ) );

		$op->disable();

		$this->assertTrue( $op->isDisabled() );
		$this->assertSame( '', $op->output( true ) );
	}

	/**
	 * @covers OutputPage::showNewSectionLink
	 * @covers OutputPage::addParserOutputMetadata
	 * @covers OutputPage::addParserOutput
	 */
	public function testShowNewSectionLink() {
		$op = $this->newInstance();

		$this->assertFalse( $op->showNewSectionLink() );

		$pOut1 = $this->createParserOutputStub( 'getNewSection', true );
		$op->addParserOutputMetadata( $pOut1 );
		$this->assertTrue( $op->showNewSectionLink() );

		$pOut2 = $this->createParserOutputStub( 'getNewSection', false );
		$op->addParserOutput( $pOut2 );
		$this->assertFalse( $op->showNewSectionLink() );
	}

	/**
	 * @covers OutputPage::forceHideNewSectionLink
	 * @covers OutputPage::addParserOutputMetadata
	 * @covers OutputPage::addParserOutput
	 */
	public function testForceHideNewSectionLink() {
		$op = $this->newInstance();

		$this->assertFalse( $op->forceHideNewSectionLink() );

		$pOut1 = $this->createParserOutputStub( 'getHideNewSection', true );
		$op->addParserOutputMetadata( $pOut1 );
		$this->assertTrue( $op->forceHideNewSectionLink() );

		$pOut2 = $this->createParserOutputStub( 'getHideNewSection', false );
		$op->addParserOutput( $pOut2 );
		$this->assertFalse( $op->forceHideNewSectionLink() );
	}

	/**
	 * @covers OutputPage::setSyndicated
	 * @covers OutputPage::isSyndicated
	 */
	public function testSetSyndicated() {
		$op = $this->newInstance( [ 'Feed' => true ] );
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

	/**
	 * @covers OutputPage::isSyndicated
	 * @covers OutputPage::setFeedAppendQuery
	 * @covers OutputPage::addFeedLink
	 * @covers OutputPage::getSyndicationLinks()
	 */
	public function testFeedLinks() {
		$op = $this->newInstance( [ 'Feed' => true ] );
		$this->assertSame( [], $op->getSyndicationLinks() );

		$op->addFeedLink( 'not a supported format', 'abc' );
		$this->assertFalse( $op->isSyndicated() );
		$this->assertSame( [], $op->getSyndicationLinks() );

		$feedTypes = $op->getConfig()->get( 'AdvertisedFeedTypes' );

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

	/**
	 * @covers OutputPage::setArticleFlag
	 * @covers OutputPage::isArticle
	 * @covers OutputPage::setArticleRelated
	 * @covers OutputPage::isArticleRelated
	 */
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

	/**
	 * @covers OutputPage::addLanguageLinks
	 * @covers OutputPage::setLanguageLinks
	 * @covers OutputPage::getLanguageLinks
	 * @covers OutputPage::addParserOutputMetadata
	 * @covers OutputPage::addParserOutput
	 */
	public function testLanguageLinks() {
		$op = $this->newInstance();
		$this->assertSame( [], $op->getLanguageLinks() );

		$op->addLanguageLinks( [ 'fr:A', 'it:B' ] );
		$this->assertSame( [ 'fr:A', 'it:B' ], $op->getLanguageLinks() );

		$op->addLanguageLinks( [ 'de:C', 'es:D' ] );
		$this->assertSame( [ 'fr:A', 'it:B', 'de:C', 'es:D' ], $op->getLanguageLinks() );

		$op->setLanguageLinks( [ 'pt:E' ] );
		$this->assertSame( [ 'pt:E' ], $op->getLanguageLinks() );

		$pOut1 = $this->createParserOutputStub( 'getLanguageLinks', [ 'he:F', 'ar:G' ] );
		$op->addParserOutputMetadata( $pOut1 );
		$this->assertSame( [ 'pt:E', 'he:F', 'ar:G' ], $op->getLanguageLinks() );

		$pOut2 = $this->createParserOutputStub( 'getLanguageLinks', [ 'pt:H' ] );
		$op->addParserOutput( $pOut2 );
		$this->assertSame( [ 'pt:E', 'he:F', 'ar:G', 'pt:H' ], $op->getLanguageLinks() );
	}

	// @todo Are these category links tests too abstract and complicated for what they test?  Would
	// it make sense to just write out all the tests by hand with maybe some copy-and-paste?

	/**
	 * @dataProvider provideGetCategories
	 *
	 * @covers OutputPage::addCategoryLinks
	 * @covers OutputPage::getCategories
	 * @covers OutputPage::getCategoryLinks
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
	 *
	 * @covers OutputPage::addCategoryLinks
	 * @covers OutputPage::getCategories
	 * @covers OutputPage::getCategoryLinks
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
	 *
	 * @covers OutputPage::setCategoryLinks
	 * @covers OutputPage::getCategories
	 * @covers OutputPage::getCategoryLinks
	 */
	public function testSetCategoryLinks(
		array $args, array $fakeResults, ?callable $variantLinkCallback,
		array $expectedNormal, array $expectedHidden
	) {
		$expectedNormal = $this->extractExpectedCategories( $expectedNormal, 'set' );
		$expectedHidden = $this->extractExpectedCategories( $expectedHidden, 'set' );

		$op = $this->setupCategoryTests( $fakeResults, $variantLinkCallback );

		$op->setCategoryLinks( [ 'Initial page' => 'Initial page' ] );
		$op->setCategoryLinks( $args );

		// We don't reset the categories, for some reason, only the links
		$expectedNormalCats = array_merge( [ 'Initial page' ], $expectedNormal );
		$expectedCats = array_merge( $expectedHidden, $expectedNormalCats );

		$this->doCategoryAsserts( $op, $expectedNormalCats, $expectedHidden );
		$this->doCategoryLinkAsserts( $op, $expectedNormal, $expectedHidden );
	}

	/**
	 * @dataProvider provideGetCategories
	 *
	 * @covers OutputPage::addParserOutputMetadata
	 * @covers OutputPage::addParserOutput
	 * @covers OutputPage::getCategories
	 * @covers OutputPage::getCategoryLinks
	 */
	public function testParserOutputCategoryLinks(
		array $args, array $fakeResults, ?callable $variantLinkCallback,
		array $expectedNormal, array $expectedHidden
	) {
		$expectedNormal = $this->extractExpectedCategories( $expectedNormal, 'pout' );
		$expectedHidden = $this->extractExpectedCategories( $expectedHidden, 'pout' );

		$op = $this->setupCategoryTests( $fakeResults, $variantLinkCallback );

		$stubPO = $this->createParserOutputStub( 'getCategories', $args );

		// addParserOutput and addParserOutputMetadata should behave identically for us, so
		// alternate to get coverage for both without adding extra tests
		static $idx = 0;
		$idx++;
		$method = [ 'addParserOutputMetadata', 'addParserOutput' ][$idx % 2];
		$op->$method( $stubPO );

		$this->doCategoryAsserts( $op, $expectedNormal, $expectedHidden );
		$this->doCategoryLinkAsserts( $op, $expectedNormal, $expectedHidden );
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
		array $fakeResults, callable $variantLinkCallback = null
	): OutputPage {
		$this->overrideConfigValue( MainConfigNames::UsePigLatinVariant, true );

		if ( $variantLinkCallback ) {
			$mockContLang = $this->createMock( Language::class );
			$mockContLang
				->method( 'convertHtml' )
				->willReturnArgument( 0 );

			$mockLanguageConverter = $this
				->createMock( ILanguageConverter::class );
			$mockLanguageConverter
				->method( 'findVariantLink' )
				->willReturnCallback( $variantLinkCallback );

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

		$title = Title::newFromText( 'My test page' );
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
			$this->assertSame( count( $expectedNormal ), count( $catLinks['normal'] ) );
		}
		if ( $expectedHidden ) {
			$this->assertSame( count( $expectedHidden ), count( $catLinks['hidden'] ) );
		}

		foreach ( $expectedNormal as $i => $name ) {
			$this->assertStringContainsString( $name, $catLinks['normal'][$i] );
		}
		foreach ( $expectedHidden as $i => $name ) {
			$this->assertStringContainsString( $name, $catLinks['hidden'][$i] );
		}
	}

	public function provideGetCategories() {
		return [
			'No categories' => [ [], [], null, [], [] ],
			'Simple test' => [
				[ 'Test1' => 'Some sortkey', 'Test2' => 'A different sortkey' ],
				[ 'Test1' => (object)[ 'pp_value' => 1, 'page_title' => 'Test1' ],
					'Test2' => (object)[ 'page_title' => 'Test2' ] ],
				null,
				[ 'Test2' ],
				[ 'Test1' ],
			],
			'Invalid title' => [
				[ '[' => '[', 'Test' => 'Test' ],
				[ 'Test' => (object)[ 'page_title' => 'Test' ] ],
				null,
				[ 'Test' ],
				[],
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
			],
		];
	}

	/**
	 * @covers OutputPage::getCategories
	 */
	public function testGetCategoriesInvalid() {
		$this->expectException( InvalidArgumentException::class );
		$this->expectExceptionMessage( 'Invalid category type given: hiddne' );

		$op = $this->newInstance();
		$op->getCategories( 'hiddne' );
	}

	// @todo Should we test addCategoryLinksToLBAndGetResult?  If so, how?  Insert some test rows in
	// the DB?

	/**
	 * @covers OutputPage::setIndicators
	 * @covers OutputPage::getIndicators
	 * @covers OutputPage::addParserOutputMetadata
	 * @covers OutputPage::addParserOutput
	 */
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
		$op->addParserOutput( $pOut2 );
		$this->assertSame( [
			'a' => '<div class="wrapper2">!!!</div>',
			'b' => 'x',
			'c' => '<div class="wrapper1">u</div>',
			'd' => '<div class="wrapper1">v</div>',
		], $op->getIndicators() );
	}

	/**
	 * @covers OutputPage::addHelpLink
	 * @covers OutputPage::getIndicators
	 */
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

	/**
	 * @covers OutputPage::prependHTML
	 * @covers OutputPage::addHTML
	 * @covers OutputPage::addElement
	 * @covers OutputPage::clearHTML
	 * @covers OutputPage::getHTML
	 */
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
	 * @covers OutputPage::setRevisionId
	 * @covers OutputPage::getRevisionId
	 */
	public function testRevisionId( $newVal, $expected ) {
		$op = $this->newInstance();

		$this->assertNull( $op->setRevisionId( $newVal ) );
		$this->assertSame( $expected, $op->getRevisionId() );
		$this->assertSame( $expected, $op->setRevisionId( null ) );
		$this->assertNull( $op->getRevisionId() );
	}

	public function provideRevisionId() {
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

	/**
	 * @covers OutputPage::setRevisionTimestamp
	 * @covers OutputPage::getRevisionTimestamp
	 */
	public function testRevisionTimestamp() {
		$op = $this->newInstance();
		$this->assertNull( $op->getRevisionTimestamp() );

		$this->assertNull( $op->setRevisionTimestamp( 'abc' ) );
		$this->assertSame( 'abc', $op->getRevisionTimestamp() );
		$this->assertSame( 'abc', $op->setRevisionTimestamp( null ) );
		$this->assertNull( $op->getRevisionTimestamp() );
	}

	/**
	 * @covers OutputPage::setFileVersion
	 * @covers OutputPage::getFileVersion
	 */
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
		$pOut = $this->createMock( ParserOutput::class );
		foreach ( $retVals as $method => $retVal ) {
			$pOut->method( $method )->willReturn( $retVal );
		}

		$arrayReturningMethods = [
			'getCategories',
			'getFileSearchOptions',
			'getHeadItems',
			'getImages',
			'getIndicators',
			'getLanguageLinks',
			'getOutputHooks',
			'getTemplateIds',
			'getExtraCSPDefaultSrcs',
			'getExtraCSPStyleSrcs',
			'getExtraCSPScriptSrcs',
		];

		foreach ( $arrayReturningMethods as $method ) {
			$pOut->method( $method )->willReturn( [] );
		}

		return $pOut;
	}

	/**
	 * @covers OutputPage::getTemplateIds
	 * @covers OutputPage::addParserOutputMetadata
	 * @covers OutputPage::addParserOutput
	 */
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

		$op->addParserOutput( $stubPO2 );
		$this->assertSame( $finalIds, $op->getTemplateIds() );

		// Test merging with an empty set of id's
		$op->addParserOutputMetadata( $stubPOEmpty );
		$this->assertSame( $finalIds, $op->getTemplateIds() );
	}

	/**
	 * @covers OutputPage::getFileSearchOptions
	 * @covers OutputPage::addParserOutputMetadata
	 * @covers OutputPage::addParserOutput
	 */
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

		$op->addParserOutput( $stubPO1 );
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
		$op->addParserOutput( $stubPOEmpty );
		$this->assertSame( array_merge( $files1, $files2 ), $op->getFileSearchOptions() );
	}

	/**
	 * @dataProvider provideAddWikiText
	 * @covers OutputPage::addWikiTextAsInterface
	 * @covers OutputPage::wrapWikiTextAsInterface
	 * @covers OutputPage::addWikiTextAsContent
	 * @covers OutputPage::getHTML
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

	public function provideAddWikiText() {
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
					"<h2><span class=\"mw-headline\" id=\"Title\">Title</span></h2>",
				], 'With title at start' => [
					[ '* {{PAGENAME}}', true, Title::newFromText( 'Talk:Some page' ) ],
					"<ul><li>Some page</li></ul>\n",
				], 'With title not at start' => [
					[ '* {{PAGENAME}}', false, Title::newFromText( 'Talk:Some page' ) ],
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
					[ '* {{PAGENAME}}', true, Title::newFromText( 'Talk:Some page' ) ],
					"<ul><li>Some page</li></ul>",
				], 'With title not at start' => [
					[ '* {{PAGENAME}}', false, Title::newFromText( 'Talk:Some page' ) ],
					"<p>* Some page</p>",
				], 'EditPage' => [
					[ "<div class='mw-editintro'>{{PAGENAME}}", true, $somePageRef ],
					'<div class="mw-editintro">' . "Some page</div>"
				],
			],
			'wrapWikiTextAsInterface' => [
				'Simple' => [
					[ 'wrapperClass', 'text' ],
					"<div class=\"wrapperClass\"><p>text\n</p></div>"
				], 'Spurious </div>' => [
					[ 'wrapperClass', 'text</div><div>more' ],
					"<div class=\"wrapperClass\"><p>text</p><div>more</div></div>"
				], 'Extra newlines would break <p> wrappers' => [
					[ 'two classes', "1\n\n2\n\n3" ],
					"<div class=\"two classes\"><p>1\n</p><p>2\n</p><p>3\n</p></div>"
				], 'Other unclosed tags' => [
					[ 'error', 'a<b>c<i>d' ],
					"<div class=\"error\"><p>a<b>c<i>d\n</i></b></p></div>"
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

	/**
	 * @covers OutputPage::addWikiTextAsInterface
	 */
	public function testAddWikiTextAsInterfaceNoTitle() {
		$this->expectException( MWException::class );
		$this->expectExceptionMessage( 'Title is null' );

		$op = $this->newInstance( [], null, 'notitle' );
		$op->addWikiTextAsInterface( 'a' );
	}

	/**
	 * @covers OutputPage::addWikiTextAsContent
	 */
	public function testAddWikiTextAsContentNoTitle() {
		$this->expectException( MWException::class );
		$this->expectExceptionMessage( 'Title is null' );

		$op = $this->newInstance( [], null, 'notitle' );
		$op->addWikiTextAsContent( 'a' );
	}

	/**
	 * @covers OutputPage::addWikiMsg
	 */
	public function testAddWikiMsg() {
		$msg = wfMessage( 'parentheses' );
		$this->assertSame( '(a)', $msg->rawParams( 'a' )->plain() );

		$op = $this->newInstance();
		$this->assertSame( '', $op->getHTML() );
		$op->addWikiMsg( 'parentheses', "<b>a" );
		// The input is bad unbalanced HTML, but the output is tidied
		$this->assertSame( "<p>(<b>a)\n</b></p>", $op->getHTML() );
	}

	/**
	 * @covers OutputPage::wrapWikiMsg
	 */
	public function testWrapWikiMsg() {
		$msg = wfMessage( 'parentheses' );
		$this->assertSame( '(a)', $msg->rawParams( 'a' )->plain() );

		$op = $this->newInstance();
		$this->assertSame( '', $op->getHTML() );
		$op->wrapWikiMsg( '[$1]', [ 'parentheses', "<b>a" ] );
		// The input is bad unbalanced HTML, but the output is tidied
		$this->assertSame( "<p>[(<b>a)]\n</b></p>", $op->getHTML() );
	}

	/**
	 * @covers OutputPage::addParserOutputMetadata
	 * @covers OutputPage::addParserOutput
	 */
	public function testNoGallery() {
		$op = $this->newInstance();
		$this->assertFalse( $op->mNoGallery );

		$stubPO1 = $this->createParserOutputStub( 'getNoGallery', true );
		$op->addParserOutputMetadata( $stubPO1 );
		$this->assertTrue( $op->mNoGallery );

		$stubPO2 = $this->createParserOutputStub( 'getNoGallery', false );
		$op->addParserOutput( $stubPO2 );
		$this->assertFalse( $op->mNoGallery );
	}

	private static $parserOutputHookCalled;

	/**
	 * @covers OutputPage::addParserOutputMetadata
	 */
	public function testParserOutputHooks() {
		$op = $this->newInstance();
		$pOut = $this->createParserOutputStub( 'getOutputHooks', [
			[ 'myhook', 'banana' ],
			[ 'yourhook', 'kumquat' ],
			[ 'theirhook', 'hippopotamus' ],
		] );

		self::$parserOutputHookCalled = [];

		$this->overrideConfigValue( MainConfigNames::ParserOutputHooks, [
			'myhook' => function ( OutputPage $innerOp, ParserOutput $innerPOut, $data )
			use ( $op, $pOut ) {
				$this->assertSame( $op, $innerOp );
				$this->assertSame( $pOut, $innerPOut );
				$this->assertSame( 'banana', $data );
				self::$parserOutputHookCalled[] = 'closure';
			},
			'yourhook' => [ $this, 'parserOutputHookCallback' ],
			'theirhook' => [ __CLASS__, 'parserOutputHookCallbackStatic' ],
			'uncalled' => function () {
				$this->fail();
			},
		] );

		$op->addParserOutputMetadata( $pOut );

		$this->assertSame( [ 'closure', 'callback', 'static' ], self::$parserOutputHookCalled );
	}

	public function parserOutputHookCallback(
		OutputPage $op, ParserOutput $pOut, $data
	) {
		$this->assertSame( 'kumquat', $data );

		self::$parserOutputHookCalled[] = 'callback';
	}

	public static function parserOutputHookCallbackStatic(
		OutputPage $op, ParserOutput $pOut, $data
	) {
		// All the assert methods are actually static, who knew!
		self::assertSame( 'hippopotamus', $data );

		self::$parserOutputHookCalled[] = 'static';
	}

	// @todo Make sure to test the following in addParserOutputMetadata() as well when we add tests
	// for them:
	//   * addModules()
	//   * addModuleStyles()
	//   * addJsConfigVars()
	//   * enableOOUI()
	// Otherwise those lines of addParserOutputMetadata() will be reported as covered, but we won't
	// be testing they actually work.

	/**
	 * @covers OutputPage::addParserOutputText
	 */
	public function testAddParserOutputText() {
		$op = $this->newInstance();
		$this->assertSame( '', $op->getHTML() );

		$pOut = $this->createParserOutputStub( 'getText', '<some text>' );

		$op->addParserOutputMetadata( $pOut );
		$this->assertSame( '', $op->getHTML() );

		$op->addParserOutputText( $pOut );
		$this->assertSame( '<some text>', $op->getHTML() );
	}

	/**
	 * @covers OutputPage::addParserOutput
	 */
	public function testAddParserOutput() {
		$op = $this->newInstance();
		$this->assertSame( '', $op->getHTML() );
		$this->assertFalse( $op->showNewSectionLink() );

		$pOut = $this->createParserOutputStub( [
			'getText' => '<some text>',
			'getNewSection' => true,
		] );

		$op->addParserOutput( $pOut );
		$this->assertSame( '<some text>', $op->getHTML() );
		$this->assertTrue( $op->showNewSectionLink() );
	}

	/**
	 * @covers OutputPage::addTemplate
	 */
	public function testAddTemplate() {
		$template = $this->createMock( QuickTemplate::class );
		$template->method( 'getHTML' )->willReturn( '<abc>&def;' );

		$op = $this->newInstance();
		$op->addTemplate( $template );

		$this->assertSame( '<abc>&def;', $op->getHTML() );
	}

	/**
	 * @dataProvider provideParseAs
	 * @covers OutputPage::parseAsContent
	 */
	public function testParseAsContent(
		array $args, $expectedHTML, $expectedHTMLInline = null
	) {
		$op = $this->newInstance();
		$this->assertSame( $expectedHTML, $op->parseAsContent( ...$args ) );
	}

	/**
	 * @dataProvider provideParseAs
	 * @covers OutputPage::parseAsInterface
	 */
	public function testParseAsInterface(
		array $args, $expectedHTML, $expectedHTMLInline = null
	) {
		$op = $this->newInstance();
		$this->assertSame( $expectedHTML, $op->parseAsInterface( ...$args ) );
	}

	/**
	 * @dataProvider provideParseAs
	 * @covers OutputPage::parseInlineAsInterface
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

	public function provideParseAs() {
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
				'<h2><span class="mw-headline" id="Header">Header</span></h2>',
			]
		];
	}

	/**
	 * @covers OutputPage::parseAsContent
	 */
	public function testParseAsContentNullTitle() {
		$this->expectException( MWException::class );
		$this->expectExceptionMessage( 'Empty $mTitle in OutputPage::parseInternal' );
		$op = $this->newInstance( [], null, 'notitle' );
		$op->parseAsContent( '' );
	}

	/**
	 * @covers OutputPage::parseAsInterface
	 */
	public function testParseAsInterfaceNullTitle() {
		$this->expectException( MWException::class );
		$this->expectExceptionMessage( 'Empty $mTitle in OutputPage::parseInternal' );
		$op = $this->newInstance( [], null, 'notitle' );
		$op->parseAsInterface( '' );
	}

	/**
	 * @covers OutputPage::parseInlineAsInterface
	 */
	public function testParseInlineAsInterfaceNullTitle() {
		$this->expectException( MWException::class );
		$this->expectExceptionMessage( 'Empty $mTitle in OutputPage::parseInternal' );
		$op = $this->newInstance( [], null, 'notitle' );
		$op->parseInlineAsInterface( '' );
	}

	/**
	 * @covers OutputPage::setCdnMaxage
	 * @covers OutputPage::lowerCdnMaxage
	 */
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
	 * @covers OutputPage::adaptCdnTTL
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

	public function provideAdaptCdnTTL() {
		global $wgCdnMaxAge;
		$now = time();
		self::$fakeTime = $now;
		return [
			'Five minutes ago' => [ [ $now - 300 ], 270 ],
			'Now' => [ [ +0 ], IExpiringStore::TTL_MINUTE ],
			'Five minutes from now' => [ [ $now + 300 ], IExpiringStore::TTL_MINUTE ],
			'Five minutes ago, initial maxage four minutes' =>
				[ [ $now - 300 ], 270, [ 'initialMaxage' => 240 ] ],
			'A very long time ago' => [ [ $now - 1000000000 ], $wgCdnMaxAge ],
			'Initial maxage zero' => [ [ $now - 300 ], 270, [ 'initialMaxage' => 0 ] ],

			'false' => [ [ false ], IExpiringStore::TTL_MINUTE ],
			'null' => [ [ null ], IExpiringStore::TTL_MINUTE ],
			"'0'" => [ [ '0' ], IExpiringStore::TTL_MINUTE ],
			'Empty string' => [ [ '' ], IExpiringStore::TTL_MINUTE ],
			// @todo These give incorrect results due to timezones, how to test?
			//"'now'" => [ [ 'now' ], IExpiringStore::TTL_MINUTE ],
			//"'parse error'" => [ [ 'parse error' ], IExpiringStore::TTL_MINUTE ],

			'Now, minTTL 0' => [ [ $now, 0 ], IExpiringStore::TTL_MINUTE ],
			'Now, minTTL 0.000001' => [ [ $now, 0.000001 ], 0 ],
			'A very long time ago, maxTTL even longer' =>
				[ [ $now - 1000000000, 0, 1000000001 ], 900000000 ],
		];
	}

	/**
	 * @covers OutputPage::disableClientCache
	 * @covers OutputPage::addParserOutputMetadata
	 * @covers OutputPage::addParserOutput
	 */
	public function testClientCache() {
		$op = $this->newInstance();
		$op->considerCacheSettingsFinal();

		// Test initial value
		$this->assertSame( true, $op->couldBePublicCached() );

		// Test setting to false
		$op->disableClientCache();
		$this->assertSame( false, $op->couldBePublicCached() );

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
		$op->addParserOutput( $pOutUncacheable );
		$this->assertSame( false, $op->couldBePublicCached() );
	}

	/**
	 * This test can be safely removed when the deprecated
	 * OutputPage::enableClientCache() is removed.
	 * @covers OutputPage::enableClientCache
	 */
	public function testEnableClientCache() {
		// OutputPage::enableClientCache() is deprecated, so this test
		// will emit warnings.
		$this->hideDeprecated( 'OutputPage::enableClientCache' );

		$op = $this->newInstance();

		// Test initial value
		$this->assertSame( true, $op->enableClientCache( null ) );
		// Test that calling with null doesn't change the value
		$this->assertSame( true, $op->enableClientCache( null ) );

		// Test setting to false
		$this->assertSame( true, $op->enableClientCache( false ) );
		$this->assertSame( false, $op->enableClientCache( null ) );
		// Test that calling with null doesn't change the value
		$this->assertSame( false, $op->enableClientCache( null ) );
		// Using ::disableClientCache() works, too
		$op->disableClientCache();
		$this->assertSame( false, $op->enableClientCache( null ) );

		// Test setting back to true
		$this->assertSame( false, $op->enableClientCache( true ) );
		$this->assertSame( true, $op->enableClientCache( null ) );

		// For completeness, test that ::disableClientCache() also sets it
		// to false.
		$this->assertSame( true, $op->enableClientCache( null ) );
		$op->disableClientCache();
		$this->assertSame( false, $op->enableClientCache( null ) );
	}

	/**
	 * @covers OutputPage::getCacheVaryCookies
	 */
	public function testGetCacheVaryCookies() {
		global $wgCookiePrefix, $wgDBname;
		$op = $this->newInstance();
		$prefix = $wgCookiePrefix !== false ? $wgCookiePrefix : $wgDBname;
		$expectedCookies = [
			"{$prefix}Token",
			"{$prefix}LoggedOut",
			"{$prefix}_session",
			'forceHTTPS',
			'cookie1',
			'cookie2',
		];

		// We have to reset the cookies because getCacheVaryCookies may have already been called
		TestingAccessWrapper::newFromClass( OutputPage::class )->cacheVaryCookies = null;

		$this->overrideConfigValue( MainConfigNames::CacheVaryCookies, [ 'cookie1' ] );
		$this->setTemporaryHook( 'GetCacheVaryCookies',
			function ( $innerOP, &$cookies ) use ( $op, $expectedCookies ) {
				$this->assertSame( $op, $innerOP );
				$cookies[] = 'cookie2';
				$this->assertSame( $expectedCookies, $cookies );
			}
		);

		$this->assertSame( $expectedCookies, $op->getCacheVaryCookies() );
	}

	/**
	 * @covers OutputPage::haveCacheVaryCookies
	 */
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
	 * @covers OutputPage::addVaryHeader
	 * @covers OutputPage::getVaryHeader
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

		$this->filterDeprecated( '/The \$option parameter to addVaryHeader is ignored/' );
		foreach ( $calls as $call ) {
			$op->addVaryHeader( ...$call );
		}
		$this->assertEquals( $vary, $op->getVaryHeader(), 'Vary:' );
	}

	public function provideVaryHeaders() {
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

	/**
	 * @covers OutputPage::getVaryHeader
	 */
	public function testVaryHeaderDefault() {
		$op = $this->newInstance();
		$this->assertSame( 'Vary: Accept-Encoding, Cookie', $op->getVaryHeader() );
	}

	/**
	 * @dataProvider provideLinkHeaders
	 *
	 * @covers OutputPage::addLinkHeader
	 * @covers OutputPage::getLinkHeader
	 */
	public function testLinkHeaders( array $headers, $result ) {
		$op = $this->newInstance();

		foreach ( $headers as $header ) {
			$op->addLinkHeader( $header );
		}

		$this->assertEquals( $result, $op->getLinkHeader() );
	}

	public function provideLinkHeaders() {
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
	 * @covers OutputPage::addAcceptLanguage
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

	public function provideAddAcceptLanguage() {
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

	/**
	 * @covers OutputPage::setPreventClickjacking
	 * @covers OutputPage::getPreventClickjacking
	 * @covers OutputPage::addParserOutputMetadata
	 * @covers OutputPage::addParserOutput
	 */
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

		$op->addParserOutput( $pOut1 );
		$this->assertTrue( $op->getPreventClickjacking() );

		$op->addParserOutput( $pOut2 );
		$this->assertTrue( $op->getPreventClickjacking() );
	}

	/**
	 * @dataProvider provideGetFrameOptions
	 * @covers OutputPage::getFrameOptions
	 * @covers OutputPage::setPreventClickjacking
	 */
	public function testGetFrameOptions(
		$breakFrames, $preventClickjacking, $editPageFrameOptions, $expected
	) {
		$op = $this->newInstance( [
			'BreakFrames' => $breakFrames,
			'EditPageFrameOptions' => $editPageFrameOptions,
		] );
		$op->setPreventClickjacking( $preventClickjacking );

		$this->assertSame( $expected, $op->getFrameOptions() );
	}

	public function provideGetFrameOptions() {
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
	 *
	 * @covers OutputPage::makeResourceLoaderLink
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
		$nonce = $reflectCSP->getProperty( 'nonce' );
		$nonce->setAccessible( true );
		$nonce->setValue( $out->getCSP(), 'secret' );
		$rl = $out->getResourceLoader();
		$rl->setMessageBlobStore( $this->createMock( RL\MessageBlobStore::class ) );
		$rl->setDependencyStore( $this->createMock( KeyValueDependencyStore::class ) );
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
				"<script nonce=\"secret\">(RLQ=window.RLQ||[]).push(function(){"
					. 'mw.loader.load("http://127.0.0.1:8080/w/load.php?lang=en\u0026modules=test.foo\u0026only=scripts");'
					. "});</script>"
			],
			// Multiple only=styles load
			[
				[ [ 'test.baz', 'test.foo', 'test.bar' ], RL\Module::TYPE_STYLES ],

				'<link rel="stylesheet" href="http://127.0.0.1:8080/w/load.php?lang=en&amp;modules=test.bar%2Cbaz%2Cfoo&amp;only=styles"/>'
			],
			// Private embed (only=scripts)
			[
				[ 'test.quux', RL\Module::TYPE_SCRIPTS ],
				"<script nonce=\"secret\">(RLQ=window.RLQ||[]).push(function(){"
					. "mw.test.baz({token:123});\nmw.loader.state({\"test.quux\":\"ready\"});"
					. "});</script>"
			],
			// Load private module (combined)
			[
				[ 'test.quux', RL\Module::TYPE_COMBINED ],
				"<script nonce=\"secret\">(RLQ=window.RLQ||[]).push(function(){"
					. "mw.loader.implement(\"test.quux@1ev0i\",function($,jQuery,require,module){"
					. "mw.test.baz({token:123});},{\"css\":[\".mw-icon{transition:none}"
					. "\"]});});</script>"
			],
			// Load no modules
			[
				[ [], RL\Module::TYPE_COMBINED ],
				'',
			],
			// noscript group
			[
				[ 'test.noscript', RL\Module::TYPE_STYLES ],
				'<noscript><link rel="stylesheet" href="http://127.0.0.1:8080/w/load.php?lang=en&amp;modules=test.noscript&amp;only=styles"/></noscript>'
			],
			// Load two modules in separate groups
			[
				[ [ 'test.group.foo', 'test.group.bar' ], RL\Module::TYPE_COMBINED ],
				"<script nonce=\"secret\">(RLQ=window.RLQ||[]).push(function(){"
					. 'mw.loader.load("http://127.0.0.1:8080/w/load.php?lang=en\u0026modules=test.group.bar");'
					. 'mw.loader.load("http://127.0.0.1:8080/w/load.php?lang=en\u0026modules=test.group.foo");'
					. "});</script>"
			],
		];
		// phpcs:enable
	}

	/**
	 * @dataProvider provideBuildExemptModules
	 *
	 * @covers OutputPage::buildExemptModules
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
				'<meta name="ResourceLoaderDynamicStyles" content=""/>' . "\n" .
				'<link rel="stylesheet" href="/w/load.php?lang=en&amp;modules=site.styles&amp;only=styles"/>',
			],
			'default logged-in' => [
				'exemptStyleModules' => [ 'site' => [ 'site.styles' ], 'user' => [ 'user.styles' ] ],
				'<meta name="ResourceLoaderDynamicStyles" content=""/>' . "\n" .
				'<link rel="stylesheet" href="/w/load.php?lang=en&amp;modules=site.styles&amp;only=styles"/>' . "\n" .
				'<link rel="stylesheet" href="/w/load.php?lang=en&amp;modules=user.styles&amp;only=styles&amp;version=94mvi"/>',
			],
			'custom modules' => [
				'exemptStyleModules' => [
					'site' => [ 'site.styles', 'example.site.a', 'example.site.b' ],
					'user' => [ 'user.styles', 'example.user' ],
				],
				'<meta name="ResourceLoaderDynamicStyles" content=""/>' . "\n" .
				'<link rel="stylesheet" href="/w/load.php?lang=en&amp;modules=example.site.a%2Cb&amp;only=styles"/>' . "\n" .
				'<link rel="stylesheet" href="/w/load.php?lang=en&amp;modules=site.styles&amp;only=styles"/>' . "\n" .
				'<link rel="stylesheet" href="/w/load.php?lang=en&amp;modules=example.user&amp;only=styles&amp;version={blankCombi}"/>' . "\n" .
				'<link rel="stylesheet" href="/w/load.php?lang=en&amp;modules=user.styles&amp;only=styles&amp;version=94mvi"/>',
			],
		];
		// phpcs:enable
	}

	/**
	 * @dataProvider provideTransformFilePath
	 * @covers OutputPage::transformFilePath
	 * @covers OutputPage::transformResourcePath
	 */
	public function testTransformResourcePath( $baseDir, $basePath, $uploadDir = null,
		$uploadPath = null, $path = null, $expected = null
	) {
		if ( $path === null ) {
			// Skip optional $uploadDir and $uploadPath
			$path = $uploadDir;
			$expected = $uploadPath;
			$uploadDir = "$baseDir/images";
			$uploadPath = "$basePath/images";
		}
		$this->setMwGlobals( 'IP', $baseDir );
		$conf = new HashConfig( [
			MainConfigNames::ResourceBasePath => $basePath,
			MainConfigNames::UploadDirectory => $uploadDir,
			MainConfigNames::UploadPath => $uploadPath,
			MainConfigNames::BaseDirectory => $baseDir
		] );

		// Some of these paths don't exist and will cause warnings
		$actual = @OutputPage::transformResourcePath( $conf, $path );

		$this->assertEquals( $expected ?: $path, $actual );
	}

	public static function provideTransformFilePath() {
		$baseDir = dirname( __DIR__ ) . '/data/media';
		return [
			// File that matches basePath, and exists. Hash found and appended.
			[
				'baseDir' => $baseDir, 'basePath' => '/w',
				'/w/test.jpg',
				'/w/test.jpg?edcf2'
			],
			// File that matches basePath, but not found on disk. Empty query.
			[
				'baseDir' => $baseDir, 'basePath' => '/w',
				'/w/unknown.png',
				'/w/unknown.png'
			],
			// File not matching basePath. Ignored.
			[
				'baseDir' => $baseDir, 'basePath' => '/w',
				'/files/test.jpg'
			],
			// Empty string. Ignored.
			[
				'baseDir' => $baseDir, 'basePath' => '/w',
				'',
				''
			],
			// Similar path, but with domain component. Ignored.
			[
				'baseDir' => $baseDir, 'basePath' => '/w',
				'//example.org/w/test.jpg'
			],
			[
				'baseDir' => $baseDir, 'basePath' => '/w',
				'https://example.org/w/test.jpg'
			],
			// Unrelated path with domain component. Ignored.
			[
				'baseDir' => $baseDir, 'basePath' => '/w',
				'https://example.org/files/test.jpg'
			],
			[
				'baseDir' => $baseDir, 'basePath' => '/w',
				'//example.org/files/test.jpg'
			],
			// Unrelated path with domain, and empty base path (root mw install). Ignored.
			[
				'baseDir' => $baseDir, 'basePath' => '',
				'https://example.org/files/test.jpg'
			],
			[
				'baseDir' => $baseDir, 'basePath' => '',
				// T155310
				'//example.org/files/test.jpg'
			],
			// Check UploadPath before ResourceBasePath (T155146)
			[
				'baseDir' => dirname( $baseDir ), 'basePath' => '',
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

	/**
	 * Tests print requests
	 *
	 * @covers OutputPage::transformCssMedia
	 */
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
	 * Tests screen requests, without either query parameter set
	 *
	 * @covers OutputPage::transformCssMedia
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

	/**
	 * @covers OutputPage::isTOCEnabled
	 * @covers OutputPage::addParserOutputMetadata
	 * @covers OutputPage::addParserOutput
	 */
	public function testIsTOCEnabled() {
		$op = $this->newInstance();
		$this->assertFalse( $op->isTOCEnabled() );

		$pOut1 = $this->createParserOutputStub( 'getTOCHTML', false );
		$op->addParserOutputMetadata( $pOut1 );
		$this->assertFalse( $op->isTOCEnabled() );

		$pOut2 = $this->createParserOutputStub( 'getTOCHTML', true );
		$op->addParserOutput( $pOut2 );
		$this->assertTrue( $op->isTOCEnabled() );

		// The parser output doesn't disable the TOC after it was enabled
		$op->addParserOutputMetadata( $pOut1 );
		$this->assertTrue( $op->isTOCEnabled() );
	}

	/**
	 * @dataProvider providePreloadLinkHeaders
	 * @covers \MediaWiki\ResourceLoader\SkinModule::getPreloadLinks
	 */
	public function testPreloadLinkHeaders( $config, $result ) {
		$ctx = $this->createMock( RL\Context::class );
		$module = new RL\SkinModule();
		$module->setConfig( new HashConfig( $config + ResourceLoaderTestCase::getSettings() ) );

		$this->assertEquals( [ $result ], $module->getHeaders( $ctx ) );
	}

	public function providePreloadLinkHeaders() {
		return [
			[
				[
					'ResourceBasePath' => '/w',
					'Logo' => '/img/default.png',
					'Logos' => [
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
					'ResourceBasePath' => '/w',
					'Logos' => [
						'1x' => '/img/default.png',
					],
				],
				'Link: </img/default.png>;rel=preload;as=image'
			],
			[
				[
					'ResourceBasePath' => '/w',
					'Logos' => [
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
					'ResourceBasePath' => '/w',
					'Logos' => [
						'1x' => '/img/default.png',
						'svg' => '/img/vector.svg',
					],
				],
				'Link: </img/vector.svg>;rel=preload;as=image'

			],
			[
				[
					'ResourceBasePath' => '/w',
					'Logos' => [
						'1x' => '/w/test.jpg',
					],
					'UploadPath' => '/w/images',
					'BaseDirectory' => dirname( __DIR__ ) . '/data/media'
				],
				'Link: </w/test.jpg?edcf2>;rel=preload;as=image',
			],
		];
	}

	/**
	 * @param int $titleLastRevision Last Title revision to set
	 * @param int $outputRevision Revision stored in OutputPage
	 * @param bool $expectedResult Expected result of $output->isRevisionCurrent call
	 * @covers OutputPage::isRevisionCurrent
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

	public function provideIsRevisionCurrent() {
		return [
			[ 10, null, true ],
			[ 42, 42, true ],
			[ null, 0, true ],
			[ 42, 47, false ],
			[ 47, 42, false ]
		];
	}

	/**
	 * @covers OutputPage::sendCacheControl
	 * @dataProvider provideSendCacheControl
	 */
	public function testSendCacheControl( array $options = [], array $expectations = [] ) {
		$output = $this->newInstance( [
			'UseCdn' => $options['useCdn'] ?? false,
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
			'Pragma' => false,
			'Expires' => true,
			'Last-Modified' => false,
		];

		foreach ( $headers as $header => $default ) {
			$value = $expectations[$header] ?? $default;
			if ( $value === true ) {
				$this->assertNotEmpty( $response->getHeader( $header ) );
			} elseif ( $value === false ) {
				$this->assertNull( $response->getHeader( $header ) );
			} else {
				$this->assertEquals( $value, $response->getHeader( $header ) );
			}
		}
	}

	public function provideSendCacheControl() {
		return [
			'Default' => [],
			'Logged out max-age' => [
				[
					'Cache-Control' => 'private, must-revalidate, max-age=0',
				],
			],
			'Cookies' => [
				[
					'cookie' => true,
				],
			],
			'Disable client cache' => [
				[
					'enableClientCache' => false,
				],
				[
					'Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate',
					'Pragma' => 'no-cache'
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

	public function provideGetJsVarsEditable() {
		yield 'can edit and create' => [
			'performer' => $this->mockAnonAuthorityWithPermissions( [ 'edit', 'create' ] ),
			'expectedEditableConfig' => [
				'wgIsProbablyEditable' => true,
				'wgRelevantPageIsProbablyEditable' => true,
			]
		];
		yield 'cannot edit or create' => [
			'performer' => $this->mockAnonAuthorityWithoutPermissions( [ 'edit', 'create' ] ),
			'expectedEditableConfig' => [
				'wgIsProbablyEditable' => false,
				'wgRelevantPageIsProbablyEditable' => false,
			]
		];
		yield 'only can edit relevant title' => [
			'performer' => $this->mockAnonAuthority( static function (
				string $permission,
				PageIdentity $page
			) {
				if ( $permission === 'edit' | $permission === 'create' ) {
					if ( $page->getDBkey() === 'RelevantTitle' ) {
						return true;
					}
					return false;
				}
				return false;
			} ),
			'expectedEditableConfig' => [
				'wgIsProbablyEditable' => false,
				'wgRelevantPageIsProbablyEditable' => true,
			]
		];
	}

	/**
	 * @dataProvider provideGetJsVarsEditable
	 * @covers OutputPage::getJSVars
	 */
	public function testGetJsVarsEditable( Authority $performer, array $expectedEditableConfig ) {
		$op = $this->newInstance( [], null, null, $performer );
		$op->getContext()->getSkin()->setRelevantTitle( Title::newFromText( 'RelevantTitle' ) );
		$this->assertArraySubmapSame( $expectedEditableConfig, $op->getJSVars() );
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

	public function provideUserCanPreview() {
		yield 'all good' => [
			'performer' => $this->mockUserAuthorityWithPermissions(
				$this->mockUser( true, true ),
				[ 'edit' ]
			),
			'request' => new FauxRequest( [ 'action' => 'submit' ], true ),
			true
		];
		yield 'get request' => [
			'performer' => $this->mockUserAuthorityWithPermissions(
				$this->mockUser( true, true ),
				[ 'edit' ]
			),
			'request' => new FauxRequest( [ 'action' => 'submit' ], false ),
			false
		];
		yield 'not a submit action' => [
			'performer' => $this->mockUserAuthorityWithPermissions(
				$this->mockUser( true, true ),
				[ 'edit' ]
			),
			'request' => new FauxRequest( [ 'action' => 'something' ], true ),
			false
		];
		yield 'anon can not' => [
			'performer' => $this->mockUserAuthorityWithPermissions(
				$this->mockUser( false, true ),
				[ 'edit' ]
			),
			'request' => new FauxRequest( [ 'action' => 'submit' ], true ),
			false
		];
		yield 'token not match' => [
			'performer' => $this->mockUserAuthorityWithPermissions(
				$this->mockUser( true, false ),
				[ 'edit' ]
			),
			'request' => new FauxRequest( [ 'action' => 'submit' ], true ),
			false
		];
		yield 'no permission' => [
			'performer' => $this->mockUserAuthorityWithoutPermissions(
				$this->mockUser( true, true ),
				[ 'edit' ]
			),
			'request' => new FauxRequest( [ 'action' => 'submit' ], true ),
			false
		];
	}

	/**
	 * @dataProvider provideUserCanPreview
	 * @covers OutputPage::userCanPreview
	 */
	public function testUserCanPreview( Authority $performer, WebRequest $request, bool $expected ) {
		$op = $this->newInstance( [], $request, null, $performer );
		$this->assertSame( $expected, $op->userCanPreview() );
	}

	private function newInstance(
		array $config = [],
		WebRequest $request = null,
		$option = null,
		Authority $performer = null
	): OutputPage {
		$context = new RequestContext();

		$context->setConfig( new MultiConfig( [
			new HashConfig( $config + [
				'AppleTouchIcon' => false,
				'EnableCanonicalServerLink' => false,
				'Favicon' => false,
				'Feed' => false,
				'LanguageCode' => false,
				'ReferrerPolicy' => false,
				'RightsPage' => false,
				'RightsUrl' => false,
				'UniversalEditButton' => false,
			] ),
			$context->getConfig()
		] ) );

		if ( $option !== 'notitle' ) {
			$context->setTitle( Title::newFromText( 'My test page' ) );
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
