<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @author Matthew Flaschen
 *
 * @group Database
 * @group Output
 */
class OutputPageTest extends MediaWikiTestCase {
	const SCREEN_MEDIA_QUERY = 'screen and (min-width: 982px)';
	const SCREEN_ONLY_MEDIA_QUERY = 'only screen and (min-width: 982px)';

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

		$this->assertContains( "\nsome random string\n", "\n" . $op->getBottomScripts() . "\n" );
	}

	/**
	 * @covers OutputPage::addScriptFile
	 */
	public function testAddScriptFile() {
		$op = $this->newInstance();
		$op->addScriptFile( '/somescript.js' );
		$op->addScriptFile( '//example.com/somescript.js' );

		$this->assertContains(
			"\n" . Html::linkedScript( '/somescript.js', $op->getCSPNonce() ) .
				Html::linkedScript( '//example.com/somescript.js', $op->getCSPNonce() ) . "\n",
			"\n" . $op->getBottomScripts() . "\n"
		);
	}

	/**
	 * Test that addScriptFile() throws due to deprecation.
	 *
	 * @covers OutputPage::addScriptFile
	 */
	public function testAddDeprecatedScriptFileWarning() {
		$this->setExpectedException( PHPUnit_Framework_Error_Deprecated::class,
			'Use of OutputPage::addScriptFile was deprecated in MediaWiki 1.24.' );

		$op = $this->newInstance();
		$op->addScriptFile( 'ignored-script.js' );
	}

	/**
	 * Test the actual behavior of the method (in the case where it doesn't throw, e.g., in
	 * production).  Since it threw an exception once in this file, it won't when we call it again.
	 *
	 * @covers OutputPage::addScriptFile
	 */
	public function testAddDeprecatedScriptFileNoOp() {
		$op = $this->newInstance();
		$op->addScriptFile( 'ignored-script.js' );

		$this->assertNotContains( 'ignored-script.js', '' . $op->getBottomScripts() );
	}

	/**
	 * @covers OutputPage::addInlineScript
	 */
	public function testAddInlineScript() {
		$op = $this->newInstance();
		$op->addInlineScript( 'let foo = "bar";' );
		$op->addInlineScript( 'alert( foo );' );

		$this->assertContains(
			"\n" . Html::inlineScript( "\nlet foo = \"bar\";\n", $op->getCSPNonce() ) . "\n" .
				Html::inlineScript( "\nalert( foo );\n", $op->getCSPNonce() ) . "\n",
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

		$this->assertContains( "\nq\n<d>&amp;\ng\nx\n",
			'' . $op->headElement( $op->getContext()->getSkin() ) );
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

		$this->assertContains( '"a mediawiki b c d e ltr',
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

		// Avoid a complaint about not being able to disable compression
		Wikimedia\suppressWarnings();
		try {
			$this->assertEquals( $expected, $op->checkLastModified( $timestamp ) );
		} finally {
			Wikimedia\restoreWarnings();
		}
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
				function ( $op ) {
					$op->getContext()->setUser( $this->getTestUser()->getUser() );
				} ],
			'After Squid expiry' =>
				[ $lastModified, $lastModified, false,
					[ 'UseSquid' => true, 'SquidMaxage' => 3599 ] ],
			'Hook allows cache use' =>
				[ $lastModified + 1, $lastModified, true, [],
				function ( $op, $that ) {
					$that->setTemporaryHook( 'OutputPageCheckLastModified',
						function ( &$modifiedTimes ) {
							$modifiedTimes = [ 1 ];
						}
					);
				} ],
			'Hooks prohibits cache use' =>
				[ $lastModified, $lastModified, false, [],
				function ( $op, $that ) {
					$that->setTemporaryHook( 'OutputPageCheckLastModified',
						function ( &$modifiedTimes ) {
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

	// @todo mPageTitleActionText has done nothing and has no callers for a long time:
	//
	//   * e4d21170 inadvertently made it do nothing (Apr 2009)
	//   * 10ecfcb0/cadc951d removed the dead code that would have at least indicated what it was
	//   supposed to do (Nov 2010)
	//   * 9e230f30/2d045fa1 removed from history pages because it did nothing (Oct/Aug 2011)
	//   * e275ea28 removed from articles (Oct 2011)
	//   * ae45908c removed from EditPage (Oct 2011)
	//
	// Nice if we had had tests so these things couldn't happen by mistake, right?!
	//
	// https://phabricator.wikimedia.org/T200643

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
	 */
	private static function getMsgText( $op, ...$msgParams ) {
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

		$op->setRedirectedFrom( Title::newFromText( 'Talk:Some page' ) );
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

		// Test set to text with good and bad HTML.  We don't try to be comprehensive here, that
		// belongs in Sanitizer tests.
		$op->setPageTitle( '<script>a</script>&amp;<i>b</i>' );

		$this->assertSame( '&lt;script&gt;a&lt;/script&gt;&amp;<i>b</i>', $op->getPageTitle() );
		$this->assertSame(
			$this->getMsgText( $op, 'pagetitle', '<script>a</script>&b' ),
			$op->getHTMLTitle()
		);

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
	public function testBuildBacklinkSubtitle( Title $title, $query, $contains, $notContains ) {
		$this->editPage( 'Page 1', '' );
		$this->editPage( 'Page 2', '#REDIRECT [[Page 1]]' );

		$str = OutputPage::buildBacklinkSubtitle( $title, $query )->text();

		foreach ( $contains as $substr ) {
			$this->assertContains( $substr, $str );
		}

		foreach ( $notContains as $substr ) {
			$this->assertNotContains( $substr, $str );
		}
	}

	/**
	 * @dataProvider provideBacklinkSubtitle
	 *
	 * @covers OutputPage::addBacklinkSubtitle
	 * @covers OutputPage::getSubtitle
	 */
	public function testAddBacklinkSubtitle( Title $title, $query, $contains, $notContains ) {
		$this->editPage( 'Page 1', '' );
		$this->editPage( 'Page 2', '#REDIRECT [[Page 1]]' );

		$op = $this->newInstance();
		$op->addBacklinkSubtitle( $title, $query );

		$str = $op->getSubtitle();

		foreach ( $contains as $substr ) {
			$this->assertContains( $substr, $str );
		}

		foreach ( $notContains as $substr ) {
			$this->assertNotContains( $substr, $str );
		}
	}

	public function provideBacklinkSubtitle() {
		$page1 = Title::newFromText( 'Page 1' );
		$page2 = Title::newFromText( 'Page 2' );

		return [
			[ $page1, [], [ 'Page 1' ], [ 'redirect', 'Page 2' ] ],
			[ $page2, [], [ 'redirect=no' ], [ 'Page 1' ] ],
			[ $page1, [ 'action' => 'edit' ], [ 'action=edit' ], [] ],
			// @todo Anything else to test?
		];
	}

	/**
	 * @covers OutputPage::addCategoryLinks
	 * @covers OutputPage::getCategories
	 */
	public function testGetCategories() {
		$fakeResultWrapper = new FakeResultWrapper( [
			(object)[
				'pp_value' => 1,
				'page_title' => 'Test'
			],
			(object)[
				'page_title' => 'Test2'
			]
		] );
		$op = $this->getMockBuilder( OutputPage::class )
			->setConstructorArgs( [ new RequestContext() ] )
			->setMethods( [ 'addCategoryLinksToLBAndGetResult' ] )
			->getMock();
		$op->expects( $this->any() )
			->method( 'addCategoryLinksToLBAndGetResult' )
			->will( $this->returnValue( $fakeResultWrapper ) );

		$op->addCategoryLinks( [
			'Test' => 'Test',
			'Test2' => 'Test2',
		] );
		$this->assertEquals( [ 0 => 'Test', '1' => 'Test2' ], $op->getCategories() );
		$this->assertEquals( [ 0 => 'Test2' ], $op->getCategories( 'normal' ) );
		$this->assertEquals( [ 0 => 'Test' ], $op->getCategories( 'hidden' ) );
	}

	/**
	 * @covers OutputPage::haveCacheVaryCookies
	 */
	public function testHaveCacheVaryCookies() {
		$request = new FauxRequest();
		$context = new RequestContext();
		$context->setRequest( $request );
		$op = new OutputPage( $context );

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
	 * @covers OutputPage::getKeyHeader
	 */
	public function testVaryHeaders( $calls, $vary, $key ) {
		// get rid of default Vary fields
		$op = $this->getMockBuilder( OutputPage::class )
			->setConstructorArgs( [ new RequestContext() ] )
			->setMethods( [ 'getCacheVaryCookies' ] )
			->getMock();
		$op->expects( $this->any() )
			->method( 'getCacheVaryCookies' )
			->will( $this->returnValue( [] ) );
		TestingAccessWrapper::newFromObject( $op )->mVaryHeader = [];

		foreach ( $calls as $call ) {
			call_user_func_array( [ $op, 'addVaryHeader' ], $call );
		}
		$this->assertEquals( $vary, $op->getVaryHeader(), 'Vary:' );
		$this->assertEquals( $key, $op->getKeyHeader(), 'Key:' );
	}

	public function provideVaryHeaders() {
		// note: getKeyHeader() automatically adds Vary: Cookie
		return [
			[ // single header
				[
					[ 'Cookie' ],
				],
				'Vary: Cookie',
				'Key: Cookie',
			],
			[ // non-unique headers
				[
					[ 'Cookie' ],
					[ 'Accept-Language' ],
					[ 'Cookie' ],
				],
				'Vary: Cookie, Accept-Language',
				'Key: Cookie,Accept-Language',
			],
			[ // two headers with single options
				[
					[ 'Cookie', [ 'param=phpsessid' ] ],
					[ 'Accept-Language', [ 'substr=en' ] ],
				],
				'Vary: Cookie, Accept-Language',
				'Key: Cookie;param=phpsessid,Accept-Language;substr=en',
			],
			[ // one header with multiple options
				[
					[ 'Cookie', [ 'param=phpsessid', 'param=userId' ] ],
				],
				'Vary: Cookie',
				'Key: Cookie;param=phpsessid;param=userId',
			],
			[ // Duplicate option
				[
					[ 'Cookie', [ 'param=phpsessid' ] ],
					[ 'Cookie', [ 'param=phpsessid' ] ],
					[ 'Accept-Language', [ 'substr=en', 'substr=en' ] ],
				],
				'Vary: Cookie, Accept-Language',
				'Key: Cookie;param=phpsessid,Accept-Language;substr=en',
			],
			[ // Same header, different options
				[
					[ 'Cookie', [ 'param=phpsessid' ] ],
					[ 'Cookie', [ 'param=userId' ] ],
				],
				'Vary: Cookie',
				'Key: Cookie;param=phpsessid;param=userId',
			],
		];
	}

	/**
	 * @dataProvider provideLinkHeaders
	 *
	 * @covers OutputPage::addLinkHeader
	 * @covers OutputPage::getLinkHeader
	 */
	public function testLinkHeaders( $headers, $result ) {
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
				[ '<https://foo/bar.jpg>;rel=preload;as=image','<https://foo/baz.jpg>;rel=preload;as=image' ],
				'Link: <https://foo/bar.jpg>;rel=preload;as=image,<https://foo/baz.jpg>;rel=preload;as=image',
			],
		];
	}

	/**
	 * See ResourceLoaderClientHtmlTest for full coverage.
	 *
	 * @dataProvider provideMakeResourceLoaderLink
	 *
	 * @covers OutputPage::makeResourceLoaderLink
	 */
	public function testMakeResourceLoaderLink( $args, $expectedHtml ) {
		$this->setMwGlobals( [
			'wgResourceLoaderDebug' => false,
			'wgLoadScript' => 'http://127.0.0.1:8080/w/load.php',
			'wgCSPReportOnlyHeader' => true,
		] );
		$class = new ReflectionClass( OutputPage::class );
		$method = $class->getMethod( 'makeResourceLoaderLink' );
		$method->setAccessible( true );
		$ctx = new RequestContext();
		$ctx->setSkin( SkinFactory::getDefaultInstance()->makeSkin( 'fallback' ) );
		$ctx->setLanguage( 'en' );
		$out = new OutputPage( $ctx );
		$nonce = $class->getProperty( 'CSPNonce' );
		$nonce->setAccessible( true );
		$nonce->setValue( $out, 'secret' );
		$rl = $out->getResourceLoader();
		$rl->setMessageBlobStore( new NullMessageBlobStore() );
		$rl->register( [
			'test.foo' => new ResourceLoaderTestModule( [
				'script' => 'mw.test.foo( { a: true } );',
				'styles' => '.mw-test-foo { content: "style"; }',
			] ),
			'test.bar' => new ResourceLoaderTestModule( [
				'script' => 'mw.test.bar( { a: true } );',
				'styles' => '.mw-test-bar { content: "style"; }',
			] ),
			'test.baz' => new ResourceLoaderTestModule( [
				'script' => 'mw.test.baz( { a: true } );',
				'styles' => '.mw-test-baz { content: "style"; }',
			] ),
			'test.quux' => new ResourceLoaderTestModule( [
				'script' => 'mw.test.baz( { token: 123 } );',
				'styles' => '/* pref-animate=off */ .mw-icon { transition: none; }',
				'group' => 'private',
			] ),
			'test.noscript' => new ResourceLoaderTestModule( [
				'styles' => '.stuff { color: red; }',
				'group' => 'noscript',
			] ),
			'test.group.foo' => new ResourceLoaderTestModule( [
				'script' => 'mw.doStuff( "foo" );',
				'group' => 'foo',
			] ),
			'test.group.bar' => new ResourceLoaderTestModule( [
				'script' => 'mw.doStuff( "bar" );',
				'group' => 'bar',
			] ),
		] );
		$links = $method->invokeArgs( $out, $args );
		$actualHtml = strval( $links );
		$this->assertEquals( $expectedHtml, $actualHtml );
	}

	public static function provideMakeResourceLoaderLink() {
		// phpcs:disable Generic.Files.LineLength
		return [
			// Single only=scripts load
			[
				[ 'test.foo', ResourceLoaderModule::TYPE_SCRIPTS ],
				"<script nonce=\"secret\">(window.RLQ=window.RLQ||[]).push(function(){"
					. 'mw.loader.load("http://127.0.0.1:8080/w/load.php?debug=false\u0026lang=en\u0026modules=test.foo\u0026only=scripts\u0026skin=fallback");'
					. "});</script>"
			],
			// Multiple only=styles load
			[
				[ [ 'test.baz', 'test.foo', 'test.bar' ], ResourceLoaderModule::TYPE_STYLES ],

				'<link rel="stylesheet" href="http://127.0.0.1:8080/w/load.php?debug=false&amp;lang=en&amp;modules=test.bar%2Cbaz%2Cfoo&amp;only=styles&amp;skin=fallback"/>'
			],
			// Private embed (only=scripts)
			[
				[ 'test.quux', ResourceLoaderModule::TYPE_SCRIPTS ],
				"<script nonce=\"secret\">(window.RLQ=window.RLQ||[]).push(function(){"
					. "mw.test.baz({token:123});\nmw.loader.state({\"test.quux\":\"ready\"});"
					. "});</script>"
			],
			// Load private module (combined)
			[
				[ 'test.quux', ResourceLoaderModule::TYPE_COMBINED ],
				"<script nonce=\"secret\">(window.RLQ=window.RLQ||[]).push(function(){"
					. "mw.loader.implement(\"test.quux@1ev0ijv\",function($,jQuery,require,module){"
					. "mw.test.baz({token:123});},{\"css\":[\".mw-icon{transition:none}"
					. "\"]});});</script>"
			],
			// Load no modules
			[
				[ [], ResourceLoaderModule::TYPE_COMBINED ],
				'',
			],
			// noscript group
			[
				[ 'test.noscript', ResourceLoaderModule::TYPE_STYLES ],
				'<noscript><link rel="stylesheet" href="http://127.0.0.1:8080/w/load.php?debug=false&amp;lang=en&amp;modules=test.noscript&amp;only=styles&amp;skin=fallback"/></noscript>'
			],
			// Load two modules in separate groups
			[
				[ [ 'test.group.foo', 'test.group.bar' ], ResourceLoaderModule::TYPE_COMBINED ],
				"<script nonce=\"secret\">(window.RLQ=window.RLQ||[]).push(function(){"
					. 'mw.loader.load("http://127.0.0.1:8080/w/load.php?debug=false\u0026lang=en\u0026modules=test.group.bar\u0026skin=fallback");'
					. 'mw.loader.load("http://127.0.0.1:8080/w/load.php?debug=false\u0026lang=en\u0026modules=test.group.foo\u0026skin=fallback");'
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
		$this->setMwGlobals( [
			'wgResourceLoaderDebug' => false,
			'wgLoadScript' => '/w/load.php',
			// Stub wgCacheEpoch as it influences getVersionHash used for the
			// urls in the expected HTML
			'wgCacheEpoch' => '20140101000000',
		] );

		// Set up stubs
		$ctx = new RequestContext();
		$ctx->setSkin( SkinFactory::getDefaultInstance()->makeSkin( 'fallback' ) );
		$ctx->setLanguage( 'en' );
		$op = $this->getMockBuilder( OutputPage::class )
			->setConstructorArgs( [ $ctx ] )
			->setMethods( [ 'buildCssLinksArray' ] )
			->getMock();
		$op->expects( $this->any() )
			->method( 'buildCssLinksArray' )
			->willReturn( [] );
		$rl = $op->getResourceLoader();
		$rl->setMessageBlobStore( new NullMessageBlobStore() );

		// Register custom modules
		$rl->register( [
			'example.site.a' => new ResourceLoaderTestModule( [ 'group' => 'site' ] ),
			'example.site.b' => new ResourceLoaderTestModule( [ 'group' => 'site' ] ),
			'example.user' => new ResourceLoaderTestModule( [ 'group' => 'user' ] ),
		] );

		$op = TestingAccessWrapper::newFromObject( $op );
		$op->rlExemptStyleModules = $exemptStyleModules;
		$this->assertEquals(
			$expect,
			strval( $op->buildExemptModules() )
		);
	}

	public static function provideBuildExemptModules() {
		// phpcs:disable Generic.Files.LineLength
		return [
			'empty' => [
				'exemptStyleModules' => [],
				'<meta name="ResourceLoaderDynamicStyles" content=""/>',
			],
			'empty sets' => [
				'exemptStyleModules' => [ 'site' => [], 'noscript' => [], 'private' => [], 'user' => [] ],
				'<meta name="ResourceLoaderDynamicStyles" content=""/>',
			],
			'default logged-out' => [
				'exemptStyleModules' => [ 'site' => [ 'site.styles' ] ],
				'<meta name="ResourceLoaderDynamicStyles" content=""/>' . "\n" .
				'<link rel="stylesheet" href="/w/load.php?debug=false&amp;lang=en&amp;modules=site.styles&amp;only=styles&amp;skin=fallback"/>',
			],
			'default logged-in' => [
				'exemptStyleModules' => [ 'site' => [ 'site.styles' ], 'user' => [ 'user.styles' ] ],
				'<meta name="ResourceLoaderDynamicStyles" content=""/>' . "\n" .
				'<link rel="stylesheet" href="/w/load.php?debug=false&amp;lang=en&amp;modules=site.styles&amp;only=styles&amp;skin=fallback"/>' . "\n" .
				'<link rel="stylesheet" href="/w/load.php?debug=false&amp;lang=en&amp;modules=user.styles&amp;only=styles&amp;skin=fallback&amp;version=1e9z0ox"/>',
			],
			'custom modules' => [
				'exemptStyleModules' => [
					'site' => [ 'site.styles', 'example.site.a', 'example.site.b' ],
					'user' => [ 'user.styles', 'example.user' ],
				],
				'<meta name="ResourceLoaderDynamicStyles" content=""/>' . "\n" .
				'<link rel="stylesheet" href="/w/load.php?debug=false&amp;lang=en&amp;modules=example.site.a%2Cb&amp;only=styles&amp;skin=fallback"/>' . "\n" .
				'<link rel="stylesheet" href="/w/load.php?debug=false&amp;lang=en&amp;modules=site.styles&amp;only=styles&amp;skin=fallback"/>' . "\n" .
				'<link rel="stylesheet" href="/w/load.php?debug=false&amp;lang=en&amp;modules=example.user&amp;only=styles&amp;skin=fallback&amp;version=0a56zyi"/>' . "\n" .
				'<link rel="stylesheet" href="/w/load.php?debug=false&amp;lang=en&amp;modules=user.styles&amp;only=styles&amp;skin=fallback&amp;version=1e9z0ox"/>',
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
			'ResourceBasePath' => $basePath,
			'UploadDirectory' => $uploadDir,
			'UploadPath' => $uploadPath,
		] );

		// Some of these paths don't exist and will cause warnings
		Wikimedia\suppressWarnings();
		$actual = OutputPage::transformResourcePath( $conf, $path );
		Wikimedia\restoreWarnings();

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
				'/w/unknown.png?'
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
	 * options['printableQuery'] - value of query string for printable, or omitted for none
	 * options['handheldQuery'] - value of query string for handheld, or omitted for none
	 * options['media'] - passed into the method under the same name
	 * options['expectedReturn'] - expected return value
	 * options['message'] - PHPUnit message for assertion
	 *
	 * @param array $args Key-value array of arguments as shown above
	 */
	protected function assertTransformCssMediaCase( $args ) {
		$queryData = [];
		if ( isset( $args['printableQuery'] ) ) {
			$queryData['printable'] = $args['printableQuery'];
		}

		if ( isset( $args['handheldQuery'] ) ) {
			$queryData['handheld'] = $args['handheldQuery'];
		}

		$fauxRequest = new FauxRequest( $queryData, false );
		$this->setMwGlobals( [
			'wgRequest' => $fauxRequest,
		] );

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
			'printableQuery' => '1',
			'media' => 'screen',
			'expectedReturn' => null,
			'message' => 'On printable request, screen returns null'
		] );

		$this->assertTransformCssMediaCase( [
			'printableQuery' => '1',
			'media' => self::SCREEN_MEDIA_QUERY,
			'expectedReturn' => null,
			'message' => 'On printable request, screen media query returns null'
		] );

		$this->assertTransformCssMediaCase( [
			'printableQuery' => '1',
			'media' => self::SCREEN_ONLY_MEDIA_QUERY,
			'expectedReturn' => null,
			'message' => 'On printable request, screen media query with only returns null'
		] );

		$this->assertTransformCssMediaCase( [
			'printableQuery' => '1',
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
	 * Tests handheld behavior
	 *
	 * @covers OutputPage::transformCssMedia
	 */
	public function testHandheld() {
		$this->assertTransformCssMediaCase( [
			'handheldQuery' => '1',
			'media' => 'handheld',
			'expectedReturn' => '',
			'message' => 'On request with handheld querystring and media is handheld, returns empty string'
		] );

		$this->assertTransformCssMediaCase( [
			'handheldQuery' => '1',
			'media' => 'screen',
			'expectedReturn' => null,
			'message' => 'On request with handheld querystring and media is screen, returns null'
		] );
	}

	/**
	 * @dataProvider providePreloadLinkHeaders
	 * @covers OutputPage::addLogoPreloadLinkHeaders
	 * @covers ResourceLoaderSkinModule::getLogo
	 */
	public function testPreloadLinkHeaders( $config, $result, $baseDir = null ) {
		if ( $baseDir ) {
			$this->setMwGlobals( 'IP', $baseDir );
		}
		$out = TestingAccessWrapper::newFromObject( $this->newInstance( $config ) );
		$out->addLogoPreloadLinkHeaders();

		$this->assertEquals( $result, $out->getLinkHeader() );
	}

	public function providePreloadLinkHeaders() {
		return [
			[
				[
					'ResourceBasePath' => '/w',
					'Logo' => '/img/default.png',
					'LogoHD' => [
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
					'Logo' => '/img/default.png',
					'LogoHD' => false,
				],
				'Link: </img/default.png>;rel=preload;as=image'
			],
			[
				[
					'ResourceBasePath' => '/w',
					'Logo' => '/img/default.png',
					'LogoHD' => [
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
					'Logo' => '/img/default.png',
					'LogoHD' => [
						'svg' => '/img/vector.svg',
					],
				],
				'Link: </img/vector.svg>;rel=preload;as=image'

			],
			[
				[
					'ResourceBasePath' => '/w',
					'Logo' => '/w/test.jpg',
					'LogoHD' => false,
					'UploadPath' => '/w/images',
				],
				'Link: </w/test.jpg?edcf2>;rel=preload;as=image',
				'baseDir' => dirname( __DIR__ ) . '/data/media',
			],
		];
	}

	/**
	 * @return OutputPage
	 */
	private function newInstance( $config = [], WebRequest $request = null ) {
		$context = new RequestContext();

		$context->setConfig( new MultiConfig( [
			new HashConfig( $config + [
				'AppleTouchIcon' => false,
				'DisableLangConversion' => true,
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

		$context->setTitle( Title::newFromText( 'My test page' ) );

		if ( $request ) {
			$context->setRequest( $request );
		}

		return new OutputPage( $context );
	}
}

/**
 * MessageBlobStore that doesn't do anything
 */
class NullMessageBlobStore extends MessageBlobStore {
	public function get( ResourceLoader $resourceLoader, $modules, $lang ) {
		return [];
	}

	public function updateModule( $name, ResourceLoaderModule $module, $lang ) {
	}

	public function updateMessage( $key ) {
	}

	public function clear() {
	}
}
