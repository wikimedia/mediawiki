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
		$user = static::getTestSysop()->getUser();
		$title = Title::newFromText( __CLASS__ );
		$page = WikiPage::factory( $title );

		$status = $page->doEditContent(
			ContentHandler::makeContent( 'Test for revdel', $title, CONTENT_MODEL_WIKITEXT ),
			__METHOD__ . ' Test for revdel', 0, false, $user
		);
		if ( !$status->isOK() ) {
			$this->fail( "Failed to create $title: " . $status->getWikiText( false, false, 'en' ) );
		}
		self::$pageId = $status->value['revision']->getPage();
		self::$revIds['revdel'] = $status->value['revision']->getId();

		$status = $page->doEditContent(
			ContentHandler::makeContent( 'Test for suppressed', $title, CONTENT_MODEL_WIKITEXT ),
			__METHOD__ . ' Test for suppressed', 0, false, $user
		);
		if ( !$status->isOK() ) {
			$this->fail( "Failed to create $title: " . $status->getWikiText( false, false, 'en' ) );
		}
		self::$revIds['suppressed'] = $status->value['revision']->getId();

		$status = $page->doEditContent(
			ContentHandler::makeContent( 'Test for oldid', $title, CONTENT_MODEL_WIKITEXT ),
			__METHOD__ . ' Test for oldid', 0, false, $user
		);
		if ( !$status->isOK() ) {
			$this->fail( "Failed to edit $title: " . $status->getWikiText( false, false, 'en' ) );
		}
		self::$revIds['oldid'] = $status->value['revision']->getId();

		$status = $page->doEditContent(
			ContentHandler::makeContent( 'Test for latest', $title, CONTENT_MODEL_WIKITEXT ),
			__METHOD__ . ' Test for latest', 0, false, $user
		);
		if ( !$status->isOK() ) {
			$this->fail( "Failed to edit $title: " . $status->getWikiText( false, false, 'en' ) );
		}
		self::$revIds['latest'] = $status->value['revision']->getId();

		RevisionDeleter::createList(
			'revision', RequestContext::getMain(), $title, [ self::$revIds['revdel'] ]
		)->setVisibility( [
			'value' => [
				Revision::DELETED_TEXT => 1,
			],
			'comment' => 'Test for revdel',
		] );

		RevisionDeleter::createList(
			'revision', RequestContext::getMain(), $title, [ self::$revIds['suppressed'] ]
		)->setVisibility( [
			'value' => [
				Revision::DELETED_TEXT => 1,
				Revision::DELETED_RESTRICTED => 1,
			],
			'comment' => 'Test for suppressed',
		] );

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

			$html = substr( $html, 0, strlen( $html ) - strlen( $expectedEnd ) );
		} else {
			$expectedEnd = '#\n<!-- \nNewPP limit report\n.+\n-->\n' .
				'<!--\nTransclusion expansion time report \(%,ms,calls,template\)\n.*\n-->\n' .
				'</div>(\n<!-- Saved in parser cache .*\n -->\n)?$#s';
			$this->assertRegExp( $expectedEnd, $html );

			$html = preg_replace( $expectedEnd, '', $html );
		}

		call_user_func( $callback, $expected, $html );

		if ( $warnings === null ) {
			$this->assertCount( 1, $res[0] );
		} else {
			$this->assertCount( 2, $res[0] );
			$this->assertSame( [ 'parse' => [ 'warnings' => $warnings ] ], $res[0]['warnings'] );
		}
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

	public function testInvalidSection() {
		$this->setExpectedException( ApiUsageException::class,
			'The "section" parameter must be a valid section ID or "new".' );

		$this->doApiRequest( [
			'action' => 'parse',
			'section' => 'T-new',
		] );
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

		$page = $this->editPage( $name, "#REDIRECT [[$name 2]]" );
		$page = $this->editPage( "$name 2", "Some ''text''" );

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

	public function testSpecialPageNoText() {
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
			'section' => 'new',
			'sectiontitle' => 'Title',
			'text' => 'Content',
			'contentmodel' => 'wikitext',
		] );

		$this->assertParsedToRegExp( '!<h2>.*Title.*</h2>\n<p>Content\n</p>!', $res );
	}

	public function testExistingSection() {
		$name = ucfirst( __FUNCTION__ );

		// @todo Why is this exception thrown?
		$this->setExpectedException( ApiUsageException::class, "There is no section 1 in $name." );

		$this->editPage( $name, "== Title ==\n\nContent" );

		$res = $this->doApiRequest( [
			'action' => 'parse',
			'title' => $name,
			'section' => 1,
		] );

		$this->assertParsedToRegExp( '!<h2>.*Title.*</h2>\n<p>Content\n</p>!', $res );
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
		] );

		$this->assertParsedTo( "<p>Template <i>text</i>\n</p>", $res );
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

	public function testHeadItems() {
		$res = $this->doApiRequest( [
			'action' => 'parse',
			'title' => __CLASS__,
			'text' => '',
			'prop' => 'headitems',
		] );

		// @todo Is there any case that's more interesting than an empty array?
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

		// @todo Is there any case that's more interesting than an empty array?
		$this->assertSame( [], $res[0]['parse']['headitems'] );
		$this->assertSame(
			'"prop=headitems" is deprecated since MediaWiki 1.28. ' .
				'Use "prop=headhtml" when creating new HTML documents, ' .
				'or "prop=modules|jsconfigvars" when updating a document client-side.',
			$res[0]['warnings']['parse']['warnings']
		);
	}

	// @todo What would be a good test for prop=modules with no skin?  How
	// about (encoded)jsconfigvars, indicators?

	public function testSkinModules() {
		$this->setupSkin();

		$res = $this->doApiRequest( [
			'action' => 'parse',
			'pageid' => self::$pageId,
			'useskin' => 'testing',
			'prop' => 'modules|langlinks',
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
			[ 'foo.styles' ],
			$res[0]['parse']['modulestyles'],
			'resp.parse.modulestyles'
		);
	}
}
