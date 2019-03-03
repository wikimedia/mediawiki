<?php

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiParse
 */
class ApiParseTest extends ApiTestCase {

	protected static $pageId;
	protected static $revIds = [];

	public function addDBDataOnce() {
		$title = Title::newFromText( __CLASS__ );

		$status = $this->editPage( __CLASS__, 'Test for revdel' );
		self::$pageId = $status->value['revision']->getPage();
		self::$revIds['revdel'] = $status->value['revision']->getId();

		$status = $this->editPage( __CLASS__, 'Test for suppressed' );
		self::$revIds['suppressed'] = $status->value['revision']->getId();

		$status = $this->editPage( __CLASS__, 'Test for oldid' );
		self::$revIds['oldid'] = $status->value['revision']->getId();

		$status = $this->editPage( __CLASS__, 'Test for latest' );
		self::$revIds['latest'] = $status->value['revision']->getId();

		$this->revisionDelete( self::$revIds['revdel'] );
		$this->revisionDelete(
			self::$revIds['suppressed'],
			[ Revision::DELETED_TEXT => 1, Revision::DELETED_RESTRICTED => 1 ]
		);

		Title::clearCaches(); // Otherwise it has the wrong latest revision for some reason
	}

	/**
	 * Assert that the given result of calling $this->doApiRequest() with
	 * action=parse resulted in $html, accounting for the boilerplate that the
	 * parser adds around the parsed page.  Also asserts that warnings match
	 * the provided $warning.
	 *
	 * @param string $html Expected HTML
	 * @param array $res Returned from doApiRequest()
	 * @param string|null $warnings Exact value of expected warnings, null for
	 *   no warnings
	 */
	protected function assertParsedTo( $expected, array $res, $warnings = null ) {
		$this->doAssertParsedTo( $expected, $res, $warnings, [ $this, 'assertSame' ] );
	}

	/**
	 * Same as above, but asserts that the HTML matches a regexp instead of a
	 * literal string match.
	 *
	 * @param string $html Expected HTML
	 * @param array $res Returned from doApiRequest()
	 * @param string|null $warnings Exact value of expected warnings, null for
	 *   no warnings
	 */
	protected function assertParsedToRegExp( $expected, array $res, $warnings = null ) {
		$this->doAssertParsedTo( $expected, $res, $warnings, [ $this, 'assertRegExp' ] );
	}

	private function doAssertParsedTo( $expected, array $res, $warnings, callable $callback ) {
		$html = $res[0]['parse']['text'];

		$expectedStart = '<div class="mw-parser-output">';
		$this->assertSame( $expectedStart, substr( $html, 0, strlen( $expectedStart ) ) );

		$html = substr( $html, strlen( $expectedStart ) );

		if ( $res[1]->getBool( 'disablelimitreport' ) ) {
			$expectedEnd = "</div>";
			$this->assertSame( $expectedEnd, substr( $html, -strlen( $expectedEnd ) ) );

			$unexpectedEnd = '#<!-- \nNewPP limit report|' .
				'<!--\nTransclusion expansion time report#s';
			$this->assertNotRegExp( $unexpectedEnd, $html );

			$html = substr( $html, 0, strlen( $html ) - strlen( $expectedEnd ) );
		} else {
			$expectedEnd = '#\n<!-- \nNewPP limit report\n(?>.+?\n-->)\n' .
				'<!--\nTransclusion expansion time report \(%,ms,calls,template\)\n(?>.*?\n-->)\n' .
				'(\n<!-- Saved in parser cache (?>.*?\n -->)\n)?</div>$#s';
			$this->assertRegExp( $expectedEnd, $html );

			$html = preg_replace( $expectedEnd, '', $html );
		}

		call_user_func( $callback, $expected, $html );

		if ( $warnings === null ) {
			$this->assertCount( 1, $res[0] );
		} else {
			$this->assertCount( 2, $res[0] );
			$this->assertSame( [ 'warnings' => $warnings ], $res[0]['warnings']['parse'] );
		}
	}

	/**
	 * Set up an interwiki entry for testing.
	 */
	protected function setupInterwiki() {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->insert(
			'interwiki',
			[
				'iw_prefix' => 'madeuplanguage',
				'iw_url' => "https://example.com/wiki/$1",
				'iw_api' => '',
				'iw_wikiid' => '',
				'iw_local' => false,
			],
			__METHOD__,
			'IGNORE'
		);

		$this->setMwGlobals( 'wgExtraInterlanguageLinkPrefixes', [ 'madeuplanguage' ] );
		$this->tablesUsed[] = 'interwiki';
	}

	/**
	 * Set up a skin for testing.
	 *
	 * @todo Should this code be in MediaWikiTestCase or something?
	 */
	protected function setupSkin() {
		$factory = new SkinFactory();
		$factory->register( 'testing', 'Testing', function () {
			$skin = $this->getMockBuilder( SkinFallback::class )
				->setMethods( [ 'getDefaultModules', 'setupSkinUserCss' ] )
				->getMock();
			$skin->expects( $this->once() )->method( 'getDefaultModules' )
				->willReturn( [
					'styles' => [ 'core' => [ 'quux.styles' ] ],
					'core' => [ 'foo', 'bar' ],
					'content' => [ 'baz' ]
				] );
			$skin->expects( $this->once() )->method( 'setupSkinUserCss' )
				->will( $this->returnCallback( function ( OutputPage $out ) {
					$out->addModuleStyles( 'foo.styles' );
				} ) );
			return $skin;
		} );
		$this->setService( 'SkinFactory', $factory );
	}

	public function testParseByName() {
		$res = $this->doApiRequest( [
			'action' => 'parse',
			'page' => __CLASS__,
		] );
		$this->assertParsedTo( "<p>Test for latest\n</p>", $res );

		$res = $this->doApiRequest( [
			'action' => 'parse',
			'page' => __CLASS__,
			'disablelimitreport' => 1,
		] );
		$this->assertParsedTo( "<p>Test for latest\n</p>", $res );
	}

	public function testParseById() {
		$res = $this->doApiRequest( [
			'action' => 'parse',
			'pageid' => self::$pageId,
		] );
		$this->assertParsedTo( "<p>Test for latest\n</p>", $res );
	}

	public function testParseByOldId() {
		$res = $this->doApiRequest( [
			'action' => 'parse',
			'oldid' => self::$revIds['oldid'],
		] );
		$this->assertParsedTo( "<p>Test for oldid\n</p>", $res );
		$this->assertArrayNotHasKey( 'textdeleted', $res[0]['parse'] );
		$this->assertArrayNotHasKey( 'textsuppressed', $res[0]['parse'] );
	}

	public function testRevDel() {
		$res = $this->doApiRequest( [
			'action' => 'parse',
			'oldid' => self::$revIds['revdel'],
		] );

		$this->assertParsedTo( "<p>Test for revdel\n</p>", $res );
		$this->assertArrayHasKey( 'textdeleted', $res[0]['parse'] );
		$this->assertArrayNotHasKey( 'textsuppressed', $res[0]['parse'] );
	}

	public function testRevDelNoPermission() {
		$this->setExpectedException( ApiUsageException::class,
			"You don't have permission to view deleted revision text." );

		$this->doApiRequest( [
			'action' => 'parse',
			'oldid' => self::$revIds['revdel'],
		], null, null, static::getTestUser()->getUser() );
	}

	public function testSuppressed() {
		$this->setGroupPermissions( 'sysop', 'viewsuppressed', true );

		$res = $this->doApiRequest( [
			'action' => 'parse',
			'oldid' => self::$revIds['suppressed']
		] );

		$this->assertParsedTo( "<p>Test for suppressed\n</p>", $res );
		$this->assertArrayHasKey( 'textsuppressed', $res[0]['parse'] );
		$this->assertArrayHasKey( 'textdeleted', $res[0]['parse'] );
	}

	public function testNonexistentPage() {
		try {
			$this->doApiRequest( [
				'action' => 'parse',
				'page' => 'DoesNotExist',
			] );

			$this->fail( "API did not return an error when parsing a nonexistent page" );
		} catch ( ApiUsageException $ex ) {
			$this->assertTrue( ApiTestCase::apiExceptionHasCode( $ex, 'missingtitle' ),
				"Parse request for nonexistent page must give 'missingtitle' error: "
					. var_export( self::getErrorFormatter()->arrayFromStatus( $ex->getStatusValue() ), true )
			);
		}
	}

	public function testTitleProvided() {
		$res = $this->doApiRequest( [
			'action' => 'parse',
			'title' => 'Some interesting page',
			'text' => '{{PAGENAME}} has attracted my attention',
		] );

		$this->assertParsedTo( "<p>Some interesting page has attracted my attention\n</p>", $res );
	}

	public function testSection() {
		$name = ucfirst( __FUNCTION__ );

		$this->editPage( $name,
			"Intro\n\n== Section 1 ==\n\nContent 1\n\n== Section 2 ==\n\nContent 2" );

		$res = $this->doApiRequest( [
			'action' => 'parse',
			'page' => $name,
			'section' => 1,
		] );

		$this->assertParsedToRegExp( '!<h2>.*Section 1.*</h2>\n<p>Content 1\n</p>!', $res );
	}

	public function testInvalidSection() {
		$this->setExpectedException( ApiUsageException::class,
			'The "section" parameter must be a valid section ID or "new".' );

		$this->doApiRequest( [
			'action' => 'parse',
			'section' => 'T-new',
		] );
	}

	public function testSectionNoContent() {
		$name = ucfirst( __FUNCTION__ );

		$status = $this->editPage( $name,
			"Intro\n\n== Section 1 ==\n\nContent 1\n\n== Section 2 ==\n\nContent 2" );

		$this->setExpectedException( ApiUsageException::class,
			"Missing content for page ID {$status->value['revision']->getPage()}." );

		$this->db->delete( 'revision', [ 'rev_id' => $status->value['revision']->getId() ] );

		// Suppress warning in WikiPage::getContentModel
		Wikimedia\suppressWarnings();
		try {
			$this->doApiRequest( [
				'action' => 'parse',
				'page' => $name,
				'section' => 1,
			] );
		} finally {
			Wikimedia\restoreWarnings();
		}
	}

	public function testNewSectionWithPage() {
		$this->setExpectedException( ApiUsageException::class,
			'"section=new" cannot be combined with the "oldid", "pageid" or "page" ' .
			'parameters. Please use "title" and "text".' );

		$this->doApiRequest( [
			'action' => 'parse',
			'page' => __CLASS__,
			'section' => 'new',
		] );
	}

	public function testNonexistentOldId() {
		$this->setExpectedException( ApiUsageException::class,
			'There is no revision with ID 2147483647.' );

		$this->doApiRequest( [
			'action' => 'parse',
			'oldid' => pow( 2, 31 ) - 1,
		] );
	}

	public function testUnfollowedRedirect() {
		$name = ucfirst( __FUNCTION__ );

		$this->editPage( $name, "#REDIRECT [[$name 2]]" );
		$this->editPage( "$name 2", "Some ''text''" );

		$res = $this->doApiRequest( [
			'action' => 'parse',
			'page' => $name,
		] );

		// Can't use assertParsedTo because the parser output is different for
		// redirects
		$this->assertRegExp( "/Redirect to:.*$name 2/", $res[0]['parse']['text'] );
		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testFollowedRedirect() {
		$name = ucfirst( __FUNCTION__ );

		$this->editPage( $name, "#REDIRECT [[$name 2]]" );
		$this->editPage( "$name 2", "Some ''text''" );

		$res = $this->doApiRequest( [
			'action' => 'parse',
			'page' => $name,
			'redirects' => true,
		] );

		$this->assertParsedTo( "<p>Some <i>text</i>\n</p>", $res );
	}

	public function testFollowedRedirectById() {
		$name = ucfirst( __FUNCTION__ );

		$id = $this->editPage( $name, "#REDIRECT [[$name 2]]" )->value['revision']->getPage();
		$this->editPage( "$name 2", "Some ''text''" );

		$res = $this->doApiRequest( [
			'action' => 'parse',
			'pageid' => $id,
			'redirects' => true,
		] );

		$this->assertParsedTo( "<p>Some <i>text</i>\n</p>", $res );
	}

	public function testInvalidTitle() {
		$this->setExpectedException( ApiUsageException::class, 'Bad title "|".' );

		$this->doApiRequest( [
			'action' => 'parse',
			'title' => '|',
		] );
	}

	public function testTitleWithNonexistentRevId() {
		$this->setExpectedException( ApiUsageException::class,
			'There is no revision with ID 2147483647.' );

		$this->doApiRequest( [
			'action' => 'parse',
			'title' => __CLASS__,
			'revid' => pow( 2, 31 ) - 1,
		] );
	}

	public function testTitleWithNonMatchingRevId() {
		$name = ucfirst( __FUNCTION__ );

		$res = $this->doApiRequest( [
			'action' => 'parse',
			'title' => $name,
			'revid' => self::$revIds['latest'],
			'text' => 'Some text',
		] );

		$this->assertParsedTo( "<p>Some text\n</p>", $res,
			'r' . self::$revIds['latest'] . " is not a revision of $name." );
	}

	public function testRevId() {
		$res = $this->doApiRequest( [
			'action' => 'parse',
			'revid' => self::$revIds['latest'],
			'text' => 'My revid is {{REVISIONID}}!',
		] );

		$this->assertParsedTo( "<p>My revid is " . self::$revIds['latest'] . "!\n</p>", $res );
	}

	public function testTitleNoText() {
		$res = $this->doApiRequest( [
			'action' => 'parse',
			'title' => 'Special:AllPages',
		] );

		$this->assertParsedTo( '', $res,
			'"title" used without "text", and parsed page properties were requested. ' .
				'Did you mean to use "page" instead of "title"?' );
	}

	public function testRevidNoText() {
		$res = $this->doApiRequest( [
			'action' => 'parse',
			'revid' => self::$revIds['latest'],
		] );

		$this->assertParsedTo( '', $res,
			'"revid" used without "text", and parsed page properties were requested. ' .
				'Did you mean to use "oldid" instead of "revid"?' );
	}

	public function testTextNoContentModel() {
		$res = $this->doApiRequest( [
			'action' => 'parse',
			'text' => "Some ''text''",
		] );

		$this->assertParsedTo( "<p>Some <i>text</i>\n</p>", $res,
			'No "title" or "contentmodel" was given, assuming wikitext.' );
	}

	public function testSerializationError() {
		$this->setExpectedException( APIUsageException::class,
			'Content serialization failed: Could not unserialize content' );

		$this->mergeMwGlobalArrayValue( 'wgContentHandlers',
			[ 'testing-serialize-error' => 'DummySerializeErrorContentHandler' ] );

		$this->doApiRequest( [
			'action' => 'parse',
			'text' => "Some ''text''",
			'contentmodel' => 'testing-serialize-error',
		] );
	}

	public function testNewSection() {
		$res = $this->doApiRequest( [
			'action' => 'parse',
			'title' => __CLASS__,
			'section' => 'new',
			'sectiontitle' => 'Title',
			'text' => 'Content',
		] );

		$this->assertParsedToRegExp( '!<h2>.*Title.*</h2>\n<p>Content\n</p>!', $res );
	}

	public function testExistingSection() {
		$res = $this->doApiRequest( [
			'action' => 'parse',
			'title' => __CLASS__,
			'section' => 1,
			'text' => "Intro\n\n== Section 1 ==\n\nContent\n\n== Section 2 ==\n\nMore content",
		] );

		$this->assertParsedToRegExp( '!<h2>.*Section 1.*</h2>\n<p>Content\n</p>!', $res );
	}

	public function testNoPst() {
		$name = ucfirst( __FUNCTION__ );

		$this->editPage( "Template:$name", "Template ''text''" );

		$res = $this->doApiRequest( [
			'action' => 'parse',
			'text' => "{{subst:$name}}",
			'contentmodel' => 'wikitext',
		] );

		$this->assertParsedTo( "<p>{{subst:$name}}\n</p>", $res );
	}

	public function testPst() {
		$name = ucfirst( __FUNCTION__ );

		$this->editPage( "Template:$name", "Template ''text''" );

		$res = $this->doApiRequest( [
			'action' => 'parse',
			'pst' => '',
			'text' => "{{subst:$name}}",
			'contentmodel' => 'wikitext',
			'prop' => 'text|wikitext',
		] );

		$this->assertParsedTo( "<p>Template <i>text</i>\n</p>", $res );
		$this->assertSame( "{{subst:$name}}", $res[0]['parse']['wikitext'] );
	}

	public function testOnlyPst() {
		$name = ucfirst( __FUNCTION__ );

		$this->editPage( "Template:$name", "Template ''text''" );

		$res = $this->doApiRequest( [
			'action' => 'parse',
			'onlypst' => '',
			'text' => "{{subst:$name}}",
			'contentmodel' => 'wikitext',
			'prop' => 'text|wikitext',
			'summary' => 'Summary',
		] );

		$this->assertSame(
			[ 'parse' => [
				'text' => "Template ''text''",
				'wikitext' => "{{subst:$name}}",
				'parsedsummary' => 'Summary',
			] ],
			$res[0]
		);
	}

	public function testHeadHtml() {
		$res = $this->doApiRequest( [
			'action' => 'parse',
			'page' => __CLASS__,
			'prop' => 'headhtml',
		] );

		// Just do a rough sanity check
		$this->assertRegExp( '#<!DOCTYPE.*<html.*<head.*</head>.*<body#s',
			$res[0]['parse']['headhtml'] );
		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testCategoriesHtml() {
		$name = ucfirst( __FUNCTION__ );

		$this->editPage( $name, "[[Category:$name]]" );

		$res = $this->doApiRequest( [
			'action' => 'parse',
			'page' => $name,
			'prop' => 'categorieshtml',
		] );

		$this->assertRegExp( "#Category.*Category:$name.*$name#",
			$res[0]['parse']['categorieshtml'] );
		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testEffectiveLangLinks() {
		$hookRan = false;
		$this->setTemporaryHook( 'LanguageLinks',
			function () use ( &$hookRan ) {
				$hookRan = true;
			}
		);

		$res = $this->doApiRequest( [
			'action' => 'parse',
			'title' => __CLASS__,
			'text' => '[[zh:' . __CLASS__ . ']]',
			'effectivelanglinks' => '',
		] );

		$this->assertTrue( $hookRan );
		$this->assertSame( 'The parameter "effectivelanglinks" has been deprecated.',
			$res[0]['warnings']['parse']['warnings'] );
	}

	/**
	 * @param array $arr Extra params to add to API request
	 */
	private function doTestLangLinks( array $arr = [] ) {
		$this->setupInterwiki();

		$res = $this->doApiRequest( array_merge( [
			'action' => 'parse',
			'title' => 'Omelette',
			'text' => '[[madeuplanguage:Omelette]]',
			'prop' => 'langlinks',
		], $arr ) );

		$langLinks = $res[0]['parse']['langlinks'];

		$this->assertCount( 1, $langLinks );
		$this->assertSame( 'madeuplanguage', $langLinks[0]['lang'] );
		$this->assertSame( 'Omelette', $langLinks[0]['title'] );
		$this->assertSame( 'https://example.com/wiki/Omelette', $langLinks[0]['url'] );
		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testLangLinks() {
		$this->doTestLangLinks();
	}

	public function testLangLinksWithSkin() {
		$this->setupSkin();
		$this->doTestLangLinks( [ 'useskin' => 'testing' ] );
	}

	public function testHeadItems() {
		$res = $this->doApiRequest( [
			'action' => 'parse',
			'title' => __CLASS__,
			'text' => '',
			'prop' => 'headitems',
		] );

		$this->assertSame( [], $res[0]['parse']['headitems'] );
		$this->assertSame(
			'"prop=headitems" is deprecated since MediaWiki 1.28. ' .
				'Use "prop=headhtml" when creating new HTML documents, ' .
				'or "prop=modules|jsconfigvars" when updating a document client-side.',
			$res[0]['warnings']['parse']['warnings']
		);
	}

	public function testHeadItemsWithSkin() {
		$this->setupSkin();

		$res = $this->doApiRequest( [
			'action' => 'parse',
			'title' => __CLASS__,
			'text' => '',
			'prop' => 'headitems',
			'useskin' => 'testing',
		] );

		$this->assertSame( [], $res[0]['parse']['headitems'] );
		$this->assertSame(
			'"prop=headitems" is deprecated since MediaWiki 1.28. ' .
				'Use "prop=headhtml" when creating new HTML documents, ' .
				'or "prop=modules|jsconfigvars" when updating a document client-side.',
			$res[0]['warnings']['parse']['warnings']
		);
	}

	public function testModules() {
		$this->setTemporaryHook( 'ParserAfterParse',
			function ( $parser ) {
				$output = $parser->getOutput();
				$output->addModules( [ 'foo', 'bar' ] );
				$output->addModuleStyles( [ 'aaa', 'zzz' ] );
				$output->addJsConfigVars( [ 'x' => 'y', 'z' => -3 ] );
			}
		);
		$res = $this->doApiRequest( [
			'action' => 'parse',
			'title' => __CLASS__,
			'text' => 'Content',
			'prop' => 'modules|jsconfigvars|encodedjsconfigvars',
		] );

		$this->assertSame( [ 'foo', 'bar' ], $res[0]['parse']['modules'] );
		$this->assertSame( [], $res[0]['parse']['modulescripts'] );
		$this->assertSame( [ 'aaa', 'zzz' ], $res[0]['parse']['modulestyles'] );
		$this->assertSame( [ 'x' => 'y', 'z' => -3 ], $res[0]['parse']['jsconfigvars'] );
		$this->assertSame( '{"x":"y","z":-3}', $res[0]['parse']['encodedjsconfigvars'] );
		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testModulesWithSkin() {
		$this->setupSkin();

		$res = $this->doApiRequest( [
			'action' => 'parse',
			'pageid' => self::$pageId,
			'useskin' => 'testing',
			'prop' => 'modules',
		] );
		$this->assertSame(
			[ 'foo', 'bar', 'baz' ],
			$res[0]['parse']['modules'],
			'resp.parse.modules'
		);
		$this->assertSame(
			[],
			$res[0]['parse']['modulescripts'],
			'resp.parse.modulescripts'
		);
		$this->assertSame(
			[ 'foo.styles', 'quux.styles' ],
			$res[0]['parse']['modulestyles'],
			'resp.parse.modulestyles'
		);
		$this->assertSame(
			[ 'parse' =>
				[ 'warnings' =>
					'Property "modules" was set but not "jsconfigvars" or ' .
					'"encodedjsconfigvars". Configuration variables are necessary for ' .
					'proper module usage.'
				]
			],
			$res[0]['warnings']
		);
	}

	public function testIndicators() {
		$res = $this->doApiRequest( [
			'action' => 'parse',
			'title' => __CLASS__,
			'text' =>
				'<indicator name="b">BBB!</indicator>Some text<indicator name="a">aaa</indicator>',
			'prop' => 'indicators',
		] );

		$this->assertSame(
			// It seems we return in markup order and not display order
			[ 'b' => 'BBB!', 'a' => 'aaa' ],
			$res[0]['parse']['indicators']
		);
		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testIndicatorsWithSkin() {
		$this->setupSkin();

		$res = $this->doApiRequest( [
			'action' => 'parse',
			'title' => __CLASS__,
			'text' =>
				'<indicator name="b">BBB!</indicator>Some text<indicator name="a">aaa</indicator>',
			'prop' => 'indicators',
			'useskin' => 'testing',
		] );

		$this->assertSame(
			// Now we return in display order rather than markup order
			[ 'a' => 'aaa', 'b' => 'BBB!' ],
			$res[0]['parse']['indicators']
		);
		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testIwlinks() {
		$this->setupInterwiki();

		$res = $this->doApiRequest( [
			'action' => 'parse',
			'title' => 'Omelette',
			'text' => '[[:madeuplanguage:Omelette]][[madeuplanguage:Spaghetti]]',
			'prop' => 'iwlinks',
		] );

		$iwlinks = $res[0]['parse']['iwlinks'];

		$this->assertCount( 1, $iwlinks );
		$this->assertSame( 'madeuplanguage', $iwlinks[0]['prefix'] );
		$this->assertSame( 'https://example.com/wiki/Omelette', $iwlinks[0]['url'] );
		$this->assertSame( 'madeuplanguage:Omelette', $iwlinks[0]['title'] );
		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testLimitReports() {
		$res = $this->doApiRequest( [
			'action' => 'parse',
			'pageid' => self::$pageId,
			'prop' => 'limitreportdata|limitreporthtml',
		] );

		// We don't bother testing the actual values here
		$this->assertInternalType( 'array', $res[0]['parse']['limitreportdata'] );
		$this->assertInternalType( 'string', $res[0]['parse']['limitreporthtml'] );
		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testParseTreeNonWikitext() {
		$this->setExpectedException( ApiUsageException::class,
			'"prop=parsetree" is only supported for wikitext content.' );

		$this->doApiRequest( [
			'action' => 'parse',
			'text' => '',
			'contentmodel' => 'json',
			'prop' => 'parsetree',
		] );
	}

	public function testParseTree() {
		$res = $this->doApiRequest( [
			'action' => 'parse',
			'text' => "Some ''text'' is {{nice|to have|i=think}}",
			'contentmodel' => 'wikitext',
			'prop' => 'parsetree',
		] );

		// Preprocessor_DOM and Preprocessor_Hash give different results here,
		// so we'll accept either
		$this->assertRegExp(
			'#^<root>Some \'\'text\'\' is <template><title>nice</title>' .
				'<part><name index="1"/><value>to have</value></part>' .
				'<part><name>i</name>(?:<equals>)?=(?:</equals>)?<value>think</value></part>' .
				'</template></root>$#',
			$res[0]['parse']['parsetree']
		);
		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}

	public function testDisableTidy() {
		$this->setMwGlobals( 'wgTidyConfig', [ 'driver' => 'RemexHtml' ] );

		// Check that disabletidy doesn't have an effect just because tidying
		// doesn't work for some other reason
		$res1 = $this->doApiRequest( [
			'action' => 'parse',
			'text' => "<b>Mixed <i>up</b></i>",
			'contentmodel' => 'wikitext',
		] );
		$this->assertParsedTo( "<p><b>Mixed <i>up</i></b>\n</p>", $res1 );

		$res2 = $this->doApiRequest( [
			'action' => 'parse',
			'text' => "<b>Mixed <i>up</b></i>",
			'contentmodel' => 'wikitext',
			'disabletidy' => '',
		] );

		$this->assertParsedTo( "<p><b>Mixed <i>up</b></i>\n</p>", $res2,
			'The parameter "disabletidy" has been deprecated.' );
	}

	public function testFormatCategories() {
		$name = ucfirst( __FUNCTION__ );

		$this->editPage( "Category:$name", 'Content' );
		$this->editPage( 'Category:Hidden', '__HIDDENCAT__' );

		$res = $this->doApiRequest( [
			'action' => 'parse',
			'title' => __CLASS__,
			'text' => "[[Category:$name]][[Category:Foo|Sort me]][[Category:Hidden]]",
			'prop' => 'categories',
		] );

		$this->assertSame(
			[ [ 'sortkey' => '', 'category' => $name ],
				[ 'sortkey' => 'Sort me', 'category' => 'Foo', 'missing' => true ],
				[ 'sortkey' => '', 'category' => 'Hidden', 'hidden' => true ] ],
			$res[0]['parse']['categories']
		);
		$this->assertArrayNotHasKey( 'warnings', $res[0] );
	}
}
