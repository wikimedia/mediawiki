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

	// Ensure that we don't affect the global ResourceLoader state.
	protected function setUp() {
		parent::setUp();
		ResourceLoader::clearCache();
	}
	protected function tearDown() {
		parent::tearDown();
		ResourceLoader::clearCache();
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
	 * production).
	 *
	 * @covers OutputPage::addScriptFile
	 */
	public function testAddDeprecatedScriptFileNoOp() {
		$this->hideDeprecated( 'OutputPage::addScriptFile' );
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
	 * @covers OutputPage::getHeadItemsArray
	 * @covers OutputPage::addParserOutputMetadata
	 */
	public function testHeadItemsParserOutput() {
		$op = $this->newInstance();
		$stubPO1 = $this->createParserOutputStub( 'getHeadItems', [ 'a' => 'b' ] );
		$op->addParserOutputMetadata( $stubPO1 );
		$stubPO2 = $this->createParserOutputStub( 'getHeadItems',
			[ 'c' => '<d>&amp;', 'e' => 'f', 'a' => 'q' ] );
		$op->addParserOutputMetadata( $stubPO2 );
		$stubPO3 = $this->createParserOutputStub( 'getHeadItems', [ 'e' => 'g' ] );
		$op->addParserOutputMetadata( $stubPO3 );
		$stubPO4 = $this->createParserOutputStub( 'getHeadItems', [ 'x' ] );
		$op->addParserOutputMetadata( $stubPO4 );

		$this->assertSame( [ 'a' => 'q', 'c' => '<d>&amp;', 'e' => 'g', 'x' ],
			$op->getHeadItemsArray() );

		$this->assertTrue( $op->hasHeadItem( 'a' ) );
		$this->assertTrue( $op->hasHeadItem( 'c' ) );
		$this->assertTrue( $op->hasHeadItem( 'e' ) );
		$this->assertTrue( $op->hasHeadItem( '0' ) );
		$this->assertFalse( $op->hasHeadItem( 'b' ) );

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
	public function testBuildBacklinkSubtitle( $titles, $queries, $contains, $notContains ) {
		if ( count( $titles ) > 1 ) {
			// Not applicable
			$this->assertTrue( true );
			return;
		}

		$title = Title::newFromText( $titles[0] );
		$query = $queries[0];

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
	public function testAddBacklinkSubtitle( $titles, $queries, $contains, $notContains ) {
		$this->editPage( 'Page 1', '' );
		$this->editPage( 'Page 2', '#REDIRECT [[Page 1]]' );

		$op = $this->newInstance();
		foreach ( $titles as $i => $unused ) {
			$op->addBacklinkSubtitle( Title::newFromText( $titles[$i] ), $queries[$i] );
		}

		$str = $op->getSubtitle();

		foreach ( $contains as $substr ) {
			$this->assertContains( $substr, $str );
		}

		foreach ( $notContains as $substr ) {
			$this->assertNotContains( $substr, $str );
		}
	}

	public function provideBacklinkSubtitle() {
		return [
			[
				[ 'Page 1' ],
				[ [] ],
				[ 'Page 1' ],
				[ 'redirect', 'Page 2' ],
			],
			[
				[ 'Page 2' ],
				[ [] ],
				[ 'redirect=no' ],
				[ 'Page 1' ],
			],
			[
				[ 'Page 1' ],
				[ [ 'action' => 'edit' ] ],
				[ 'action=edit' ],
				[],
			],
			[
				[ 'Page 1', 'Page 2' ],
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
	 */
	public function testShowNewSectionLink() {
		$op = $this->newInstance();

		$this->assertFalse( $op->showNewSectionLink() );

		$po = new ParserOutput();
		$po->setNewSection( true );
		$op->addParserOutputMetadata( $po );

		$this->assertTrue( $op->showNewSectionLink() );
	}

	/**
	 * @covers OutputPage::forceHideNewSectionLink
	 * @covers OutputPage::addParserOutputMetadata
	 */
	public function testForceHideNewSectionLink() {
		$op = $this->newInstance();

		$this->assertFalse( $op->forceHideNewSectionLink() );

		$po = new ParserOutput();
		$po->hideNewSection( true );
		$op->addParserOutputMetadata( $po );

		$this->assertTrue( $op->forceHideNewSectionLink() );
	}

	/**
	 * @covers OutputPage::setSyndicated
	 * @covers OutputPage::isSyndicated
	 */
	public function testSetSyndicated() {
		$op = $this->newInstance();
		$this->assertFalse( $op->isSyndicated() );

		$op->setSyndicated();
		$this->assertTrue( $op->isSyndicated() );

		$op->setSyndicated( false );
		$this->assertFalse( $op->isSyndicated() );
	}

	/**
	 * @covers OutputPage::isSyndicated
	 * @covers OutputPage::setFeedAppendQuery
	 * @covers OutputPage::addFeedLink
	 * @covers OutputPage::getSyndicationLinks()
	 */
	public function testFeedLinks() {
		$op = $this->newInstance();
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
	}

	/**
	 * @covers OutputPage::setArticleFlag
	 * @covers OutputPage::isArticle
	 * @covers OutputPage::setArticleRelated
	 * @covers OutputPage::isArticleRelated
	 */
	function testArticleFlags() {
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
	 */
	function testLanguageLinks() {
		$op = $this->newInstance();
		$this->assertSame( [], $op->getLanguageLinks() );

		$op->addLanguageLinks( [ 'fr:A', 'it:B' ] );
		$this->assertSame( [ 'fr:A', 'it:B' ], $op->getLanguageLinks() );

		$op->addLanguageLinks( [ 'de:C', 'es:D' ] );
		$this->assertSame( [ 'fr:A', 'it:B', 'de:C', 'es:D' ], $op->getLanguageLinks() );

		$op->setLanguageLinks( [ 'pt:E' ] );
		$this->assertSame( [ 'pt:E' ], $op->getLanguageLinks() );

		$po = new ParserOutput();
		$po->setLanguageLinks( [ 'he:F', 'ar:G' ] );
		$op->addParserOutputMetadata( $po );
		$this->assertSame( [ 'pt:E', 'he:F', 'ar:G' ], $op->getLanguageLinks() );
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
	 * @param callback $variantLinkCallback Callback to replace findVariantLink() call
	 * @param array $expectedNormal Expected return value of getCategoryLinks['normal']
	 * @param array $expectedHidden Expected return value of getCategoryLinks['hidden']
	 */
	public function testAddCategoryLinks(
		array $args, array $fakeResults, callable $variantLinkCallback = null,
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
		array $args, array $fakeResults, callable $variantLinkCallback = null,
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
		array $args, array $fakeResults, callable $variantLinkCallback = null,
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
	 * @covers OutputPage::getCategories
	 * @covers OutputPage::getCategoryLinks
	 */
	public function testParserOutputCategoryLinks(
		array $args, array $fakeResults, callable $variantLinkCallback = null,
		array $expectedNormal, array $expectedHidden
	) {
		$expectedNormal = $this->extractExpectedCategories( $expectedNormal, 'pout' );
		$expectedHidden = $this->extractExpectedCategories( $expectedHidden, 'pout' );

		$op = $this->setupCategoryTests( $fakeResults, $variantLinkCallback );

		$stubPO = $this->createParserOutputStub( 'getCategories', $args );

		$op->addParserOutputMetadata( $stubPO );

		$this->doCategoryAsserts( $op, $expectedNormal, $expectedHidden );
		$this->doCategoryLinkAsserts( $op, $expectedNormal, $expectedHidden );
	}

	/**
	 * We allow different expectations for different tests as an associative array, like
	 * [ 'set' => [ ... ], 'default' => [ ... ] ] if setCategoryLinks() will give a different
	 * result.
	 */
	private function extractExpectedCategories( array $expected, $key ) {
		if ( !$expected || isset( $expected[0] ) ) {
			return $expected;
		}
		return $expected[$key] ?? $expected['default'];
	}

	private function setupCategoryTests(
		array $fakeResults, callable $variantLinkCallback = null
	) : OutputPage {
		$this->setMwGlobals( 'wgUsePigLatinVariant', true );

		$op = $this->getMockBuilder( OutputPage::class )
			->setConstructorArgs( [ new RequestContext() ] )
			->setMethods( [ 'addCategoryLinksToLBAndGetResult' ] )
			->getMock();

		$op->expects( $this->any() )
			->method( 'addCategoryLinksToLBAndGetResult' )
			->will( $this->returnCallback( function ( array $categories ) use ( $fakeResults ) {
				$return = [];
				foreach ( $categories as $category => $unused ) {
					if ( isset( $fakeResults[$category] ) ) {
						$return[] = $fakeResults[$category];
					}
				}
				return new FakeResultWrapper( $return );
			} ) );

		if ( $variantLinkCallback ) {
			$mockContLang = $this->getMockBuilder( Language::class )
				->setConstructorArgs( [ 'en' ] )
				->setMethods( [ 'findVariantLink' ] )
				->getMock();
			$mockContLang->expects( $this->any() )
				->method( 'findVariantLink' )
				->will( $this->returnCallback( $variantLinkCallback ) );
			$this->setContentLang( $mockContLang );
		}

		$this->assertSame( [], $op->getCategories() );

		return $op;
	}

	private function doCategoryAsserts( $op, $expectedNormal, $expectedHidden ) {
		$this->assertSame( array_merge( $expectedHidden, $expectedNormal ), $op->getCategories() );
		$this->assertSame( $expectedNormal, $op->getCategories( 'normal' ) );
		$this->assertSame( $expectedHidden, $op->getCategories( 'hidden' ) );
	}

	private function doCategoryLinkAsserts( $op, $expectedNormal, $expectedHidden ) {
		$catLinks = $op->getCategoryLinks();
		$this->assertSame( (bool)$expectedNormal + (bool)$expectedHidden, count( $catLinks ) );
		if ( $expectedNormal ) {
			$this->assertSame( count( $expectedNormal ), count( $catLinks['normal'] ) );
		}
		if ( $expectedHidden ) {
			$this->assertSame( count( $expectedHidden ), count( $catLinks['hidden'] ) );
		}

		foreach ( $expectedNormal as $i => $name ) {
			$this->assertContains( $name, $catLinks['normal'][$i] );
		}
		foreach ( $expectedHidden as $i => $name ) {
			$this->assertContains( $name, $catLinks['hidden'][$i] );
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
				function ( &$link, &$title ) {
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
		$this->setExpectedException( InvalidArgumentException::class,
			'Invalid category type given: hiddne' );

		$op = $this->newInstance();
		$op->getCategories( 'hiddne' );
	}

	// @todo Should we test addCategoryLinksToLBAndGetResult?  If so, how?  Insert some test rows in
	// the DB?

	/**
	 * @covers OutputPage::setIndicators
	 * @covers OutputPage::getIndicators
	 * @covers OutputPage::addParserOutputMetadata
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

		// Test with ParserOutput
		$stubPO = $this->createParserOutputStub( 'getIndicators', [ 'c' => 'u', 'd' => 'v' ] );
		$op->addParserOutputMetadata( $stubPO );
		$this->assertSame( [ 'a' => 'w', 'b' => 'x', 'c' => 'u', 'd' => 'v' ],
			$op->getIndicators() );
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
		$this->assertContains( 'Manual:PHP_unit_testing', $indicators['mw-helplink'] );

		$op->addHelpLink( 'https://phpunit.de', true );
		$indicators = $op->getIndicators();
		$this->assertSame( [ 'mw-helplink' ], array_keys( $indicators ) );
		$this->assertContains( 'https://phpunit.de', $indicators['mw-helplink'] );
		$this->assertNotContains( 'mediawiki', $indicators['mw-helplink'] );
		$this->assertNotContains( 'Manual:PHP', $indicators['mw-helplink'] );
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

		$op->setFileVersion( $stubFile );

		$this->assertEquals(
			[ 'time' => '12211221123321', 'sha1' => 'bf3ffa7047dc080f5855377a4f83cd18887e3b05' ],
			$op->getFileVersion()
		);

		$stubMissingFile = $this->createMock( File::class );
		$stubMissingFile->method( 'exists' )->willReturn( false );

		$op->setFileVersion( $stubMissingFile );
		$this->assertNull( $op->getFileVersion() );

		$op->setFileVersion( $stubFile );
		$this->assertNotNull( $op->getFileVersion() );

		$op->setFileVersion( null );
		$this->assertNull( $op->getFileVersion() );
	}

	private function createParserOutputStub( $method = '', $retVal = [] ) {
		$pOut = $this->getMock( ParserOutput::class );
		if ( $method !== '' ) {
			$pOut->method( $method )->willReturn( $retVal );
		}

		$arrayReturningMethods = [
			'getCategories',
			'getFileSearchOptions',
			'getHeadItems',
			'getIndicators',
			'getLanguageLinks',
			'getOutputHooks',
			'getTemplateIds',
		];

		foreach ( $arrayReturningMethods as $method ) {
			$pOut->method( $method )->willReturn( [] );
		}

		return $pOut;
	}

	/**
	 * @covers OutputPage::getTemplateIds
	 * @covers OutputPage::addParserOutputMetadata
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

		$op->addParserOutputMetadata( $stubPO2 );
		$this->assertSame( $finalIds, $op->getTemplateIds() );

		// Test merging with an empty set of id's
		$op->addParserOutputMetadata( $stubPOEmpty );
		$this->assertSame( $finalIds, $op->getTemplateIds() );
	}

	/**
	 * @covers OutputPage::getFileSearchOptions
	 * @covers OutputPage::addParserOutputMetadata
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

		$op->addParserOutputMetadata( $stubPO1 );
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
		$op->addParserOutputMetadata( $stubPOEmpty );
		$this->assertSame( array_merge( $files1, $files2 ), $op->getFileSearchOptions() );
	}

	/**
	 * @dataProvider provideAddWikiText
	 * @covers OutputPage::addWikiText
	 * @covers OutputPage::addWikiTextWithTitle
	 * @covers OutputPage::addWikiTextTitle
	 * @covers OutputPage::getHTML
	 */
	public function testAddWikiText( $method, array $args, $expected ) {
		$op = $this->newInstance();
		$this->assertSame( '', $op->getHTML() );

		if ( in_array(
			$method,
			[ 'addWikiTextWithTitle', 'addWikiTextTitleTidy', 'addWikiTextTitle' ]
		) && count( $args ) >= 2 && $args[1] === null ) {
			// Special placeholder because we can't get the actual title in the provider
			$args[1] = $op->getTitle();
		}

		$op->$method( ...$args );
		$this->assertSame( $expected, $op->getHTML() );
	}

	public function provideAddWikiText() {
		$tests = [
			'addWikiText' => [
				'Simple wikitext' => [
					[ "'''Bold'''" ],
					"<p><b>Bold</b>\n</p>",
				], 'List at start' => [
					[ '* List' ],
					"<ul><li>List</li></ul>\n",
				], 'List not at start' => [
					[ '* Not a list', false ],
					'* Not a list',
				], 'Non-interface' => [
					[ "'''Bold'''", true, false ],
					"<div class=\"mw-parser-output\"><p><b>Bold</b>\n</p></div>",
				], 'No section edit links' => [
					[ '== Title ==' ],
					"<h2><span class=\"mw-headline\" id=\"Title\">Title</span></h2>\n",
				],
			],
			'addWikiTextWithTitle' => [
				'With title at start' => [
					[ '* {{PAGENAME}}', Title::newFromText( 'Talk:Some page' ) ],
					"<div class=\"mw-parser-output\"><ul><li>Some page</li></ul>\n</div>",
				], 'With title at start' => [
					[ '* {{PAGENAME}}', Title::newFromText( 'Talk:Some page' ), false ],
					"<div class=\"mw-parser-output\">* Some page</div>",
				],
			],
		];

		// Test all the others on addWikiTextTitle as well
		foreach ( $tests['addWikiText'] as $key => $val ) {
			$args = [ $val[0][0], null, $val[0][1] ?? true, false, $val[0][2] ?? true ];
			$tests['addWikiTextTitle']["$key (addWikiTextTitle)"] =
				array_merge( [ $args ], array_slice( $val, 1 ) );
		}
		foreach ( $tests['addWikiTextWithTitle'] as $key => $val ) {
			$args = [ $val[0][0], $val[0][1], $val[0][2] ?? true ];
			$tests['addWikiTextTitle']["$key (addWikiTextTitle)"] =
				array_merge( [ $args ], array_slice( $val, 1 ) );
		}

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
	 * @covers OutputPage::addWikiText
	 */
	public function testAddWikiTextNoTitle() {
		$this->setExpectedException( MWException::class, 'Title is null' );

		$op = $this->newInstance( [], null, 'notitle' );
		$op->addWikiText( 'a' );
	}

	// @todo How should we cover the Tidy variants?

	/**
	 * @covers OutputPage::addParserOutputMetadata
	 */
	public function testNoGallery() {
		$op = $this->newInstance();
		$this->assertFalse( $op->mNoGallery );

		$stubPO1 = $this->createParserOutputStub( 'getNoGallery', true );
		$op->addParserOutputMetadata( $stubPO1 );
		$this->assertTrue( $op->mNoGallery );

		$stubPO2 = $this->createParserOutputStub( 'getNoGallery', false );
		$op->addParserOutputMetadata( $stubPO2 );
		$this->assertFalse( $op->mNoGallery );
	}

	// @todo Make sure to test the following in addParserOutputMetadata() as well when we add tests
	// for them:
	//   * enableClientCache()
	//   * addModules()
	//   * addModuleScripts()
	//   * addModuleStyles()
	//   * addJsConfigVars()
	//   * preventClickJacking()
	// Otherwise those lines of addParserOutputMetadata() will be reported as covered, but we won't
	// be testing they actually work.

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
				'<link rel="stylesheet" href="/w/load.php?debug=false&amp;lang=en&amp;modules=user.styles&amp;only=styles&amp;skin=fallback&amp;version=1ai9g6t"/>',
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
				'<link rel="stylesheet" href="/w/load.php?debug=false&amp;lang=en&amp;modules=user.styles&amp;only=styles&amp;skin=fallback&amp;version=1ai9g6t"/>',
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
	 * @return OutputPage
	 */
	private function newInstance( $config = [], WebRequest $request = null, $options = [] ) {
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

		if ( !in_array( 'notitle', (array)$options ) ) {
			$context->setTitle( Title::newFromText( 'My test page' ) );
		}

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
