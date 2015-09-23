<?php

/**
 * @group ContentHandler
 */
class ContentHandlerTest extends MediaWikiTestCase {

	protected function setUp() {
		global $wgContLang;
		parent::setUp();

		$this->setMwGlobals( array(
			'wgExtraNamespaces' => array(
				12312 => 'Dummy',
				12313 => 'Dummy_talk',
			),
			// The below tests assume that namespaces not mentioned here (Help, User, MediaWiki, ..)
			// default to CONTENT_MODEL_WIKITEXT.
			'wgNamespaceContentModels' => array(
				12312 => 'testing',
			),
			'wgContentHandlers' => array(
				CONTENT_MODEL_WIKITEXT => 'WikitextContentHandler',
				CONTENT_MODEL_JAVASCRIPT => 'JavaScriptContentHandler',
				CONTENT_MODEL_JSON => 'JsonContentHandler',
				CONTENT_MODEL_CSS => 'CssContentHandler',
				CONTENT_MODEL_TEXT => 'TextContentHandler',
				'testing' => 'DummyContentHandlerForTesting',
			),
		) );

		// Reset namespace cache
		MWNamespace::getCanonicalNamespaces( true );
		$wgContLang->resetNamespaces();
		// And LinkCache
		LinkCache::destroySingleton();
	}

	protected function tearDown() {
		global $wgContLang;

		// Reset namespace cache
		MWNamespace::getCanonicalNamespaces( true );
		$wgContLang->resetNamespaces();
		// And LinkCache
		LinkCache::destroySingleton();

		parent::tearDown();
	}

	public static function dataGetDefaultModelFor() {
		return array(
			array( 'Help:Foo', CONTENT_MODEL_WIKITEXT ),
			array( 'Help:Foo.js', CONTENT_MODEL_WIKITEXT ),
			array( 'Help:Foo.css', CONTENT_MODEL_WIKITEXT ),
			array( 'Help:Foo.json', CONTENT_MODEL_WIKITEXT ),
			array( 'Help:Foo/bar.js', CONTENT_MODEL_WIKITEXT ),
			array( 'User:Foo', CONTENT_MODEL_WIKITEXT ),
			array( 'User:Foo.js', CONTENT_MODEL_WIKITEXT ),
			array( 'User:Foo.css', CONTENT_MODEL_WIKITEXT ),
			array( 'User:Foo.json', CONTENT_MODEL_WIKITEXT ),
			array( 'User:Foo/bar.js', CONTENT_MODEL_JAVASCRIPT ),
			array( 'User:Foo/bar.css', CONTENT_MODEL_CSS ),
			array( 'User:Foo/bar.json', CONTENT_MODEL_JSON ),
			array( 'User:Foo/bar.json.nope', CONTENT_MODEL_WIKITEXT ),
			array( 'User talk:Foo/bar.css', CONTENT_MODEL_WIKITEXT ),
			array( 'User:Foo/bar.js.xxx', CONTENT_MODEL_WIKITEXT ),
			array( 'User:Foo/bar.xxx', CONTENT_MODEL_WIKITEXT ),
			array( 'MediaWiki:Foo.js', CONTENT_MODEL_JAVASCRIPT ),
			array( 'MediaWiki:Foo.JS', CONTENT_MODEL_WIKITEXT ),
			array( 'MediaWiki:Foo.css', CONTENT_MODEL_CSS ),
			array( 'MediaWiki:Foo.css.xxx', CONTENT_MODEL_WIKITEXT ),
			array( 'MediaWiki:Foo.CSS', CONTENT_MODEL_WIKITEXT ),
			array( 'MediaWiki:Foo.json', CONTENT_MODEL_JSON ),
			array( 'MediaWiki:Foo.JSON', CONTENT_MODEL_WIKITEXT ),
		);
	}

	/**
	 * @dataProvider dataGetDefaultModelFor
	 * @covers ContentHandler::getDefaultModelFor
	 */
	public function testGetDefaultModelFor( $title, $expectedModelId ) {
		$title = Title::newFromText( $title );
		$this->assertEquals( $expectedModelId, ContentHandler::getDefaultModelFor( $title ) );
	}

	/**
	 * @dataProvider dataGetDefaultModelFor
	 * @covers ContentHandler::getForTitle
	 */
	public function testGetForTitle( $title, $expectedContentModel ) {
		$title = Title::newFromText( $title );
		LinkCache::singleton()->addBadLinkObj( $title );
		$handler = ContentHandler::getForTitle( $title );
		$this->assertEquals( $expectedContentModel, $handler->getModelID() );
	}

	public static function dataGetLocalizedName() {
		return array(
			array( null, null ),
			array( "xyzzy", null ),

			// XXX: depends on content language
			array( CONTENT_MODEL_JAVASCRIPT, '/javascript/i' ),
		);
	}

	/**
	 * @dataProvider dataGetLocalizedName
	 * @covers ContentHandler::getLocalizedName
	 */
	public function testGetLocalizedName( $id, $expected ) {
		$name = ContentHandler::getLocalizedName( $id );

		if ( $expected ) {
			$this->assertNotNull( $name, "no name found for content model $id" );
			$this->assertTrue( preg_match( $expected, $name ) > 0,
				"content model name for #$id did not match pattern $expected"
			);
		} else {
			$this->assertEquals( $id, $name, "localization of unknown model $id should have "
				. "fallen back to use the model id directly."
			);
		}
	}

	public static function dataGetPageLanguage() {
		global $wgLanguageCode;

		return array(
			array( "Main", $wgLanguageCode ),
			array( "Dummy:Foo", $wgLanguageCode ),
			array( "MediaWiki:common.js", 'en' ),
			array( "User:Foo/common.js", 'en' ),
			array( "MediaWiki:common.css", 'en' ),
			array( "User:Foo/common.css", 'en' ),
			array( "User:Foo", $wgLanguageCode ),

			array( CONTENT_MODEL_JAVASCRIPT, 'javascript' ),
		);
	}

	/**
	 * @dataProvider dataGetPageLanguage
	 * @covers ContentHandler::getPageLanguage
	 */
	public function testGetPageLanguage( $title, $expected ) {
		if ( is_string( $title ) ) {
			$title = Title::newFromText( $title );
			LinkCache::singleton()->addBadLinkObj( $title );
		}

		$expected = wfGetLangObj( $expected );

		$handler = ContentHandler::getForTitle( $title );
		$lang = $handler->getPageLanguage( $title );

		$this->assertEquals( $expected->getCode(), $lang->getCode() );
	}

	public static function dataGetContentText_Null() {
		return array(
			array( 'fail' ),
			array( 'serialize' ),
			array( 'ignore' ),
		);
	}

	/**
	 * @dataProvider dataGetContentText_Null
	 * @covers ContentHandler::getContentText
	 */
	public function testGetContentText_Null( $contentHandlerTextFallback ) {
		$this->setMwGlobals( 'wgContentHandlerTextFallback', $contentHandlerTextFallback );

		$content = null;

		$text = ContentHandler::getContentText( $content );
		$this->assertEquals( '', $text );
	}

	public static function dataGetContentText_TextContent() {
		return array(
			array( 'fail' ),
			array( 'serialize' ),
			array( 'ignore' ),
		);
	}

	/**
	 * @dataProvider dataGetContentText_TextContent
	 * @covers ContentHandler::getContentText
	 */
	public function testGetContentText_TextContent( $contentHandlerTextFallback ) {
		$this->setMwGlobals( 'wgContentHandlerTextFallback', $contentHandlerTextFallback );

		$content = new WikitextContent( "hello world" );

		$text = ContentHandler::getContentText( $content );
		$this->assertEquals( $content->getNativeData(), $text );
	}

	/**
	 * ContentHandler::getContentText should have thrown an exception for non-text Content object
	 * @expectedException MWException
	 * @covers ContentHandler::getContentText
	 */
	public function testGetContentText_NonTextContent_fail() {
		$this->setMwGlobals( 'wgContentHandlerTextFallback', 'fail' );

		$content = new DummyContentForTesting( "hello world" );

		ContentHandler::getContentText( $content );
	}

	/**
	 * @covers ContentHandler::getContentText
	 */
	public function testGetContentText_NonTextContent_serialize() {
		$this->setMwGlobals( 'wgContentHandlerTextFallback', 'serialize' );

		$content = new DummyContentForTesting( "hello world" );

		$text = ContentHandler::getContentText( $content );
		$this->assertEquals( $content->serialize(), $text );
	}

	/**
	 * @covers ContentHandler::getContentText
	 */
	public function testGetContentText_NonTextContent_ignore() {
		$this->setMwGlobals( 'wgContentHandlerTextFallback', 'ignore' );

		$content = new DummyContentForTesting( "hello world" );

		$text = ContentHandler::getContentText( $content );
		$this->assertNull( $text );
	}

	/*
	public static function makeContent( $text, Title $title, $modelId = null, $format = null ) {}
	*/

	public static function dataMakeContent() {
		return array(
			array( 'hallo', 'Help:Test', null, null, CONTENT_MODEL_WIKITEXT, 'hallo', false ),
			array( 'hallo', 'MediaWiki:Test.js', null, null, CONTENT_MODEL_JAVASCRIPT, 'hallo', false ),
			array( serialize( 'hallo' ), 'Dummy:Test', null, null, "testing", 'hallo', false ),

			array(
				'hallo',
				'Help:Test',
				null,
				CONTENT_FORMAT_WIKITEXT,
				CONTENT_MODEL_WIKITEXT,
				'hallo',
				false
			),
			array(
				'hallo',
				'MediaWiki:Test.js',
				null,
				CONTENT_FORMAT_JAVASCRIPT,
				CONTENT_MODEL_JAVASCRIPT,
				'hallo',
				false
			),
			array( serialize( 'hallo' ), 'Dummy:Test', null, "testing", "testing", 'hallo', false ),

			array( 'hallo', 'Help:Test', CONTENT_MODEL_CSS, null, CONTENT_MODEL_CSS, 'hallo', false ),
			array(
				'hallo',
				'MediaWiki:Test.js',
				CONTENT_MODEL_CSS,
				null,
				CONTENT_MODEL_CSS,
				'hallo',
				false
			),
			array(
				serialize( 'hallo' ),
				'Dummy:Test',
				CONTENT_MODEL_CSS,
				null,
				CONTENT_MODEL_CSS,
				serialize( 'hallo' ),
				false
			),

			array( 'hallo', 'Help:Test', CONTENT_MODEL_WIKITEXT, "testing", null, null, true ),
			array( 'hallo', 'MediaWiki:Test.js', CONTENT_MODEL_CSS, "testing", null, null, true ),
			array( 'hallo', 'Dummy:Test', CONTENT_MODEL_JAVASCRIPT, "testing", null, null, true ),
		);
	}

	/**
	 * @dataProvider dataMakeContent
	 * @covers ContentHandler::makeContent
	 */
	public function testMakeContent( $data, $title, $modelId, $format,
		$expectedModelId, $expectedNativeData, $shouldFail
	) {
		$title = Title::newFromText( $title );
		LinkCache::singleton()->addBadLinkObj( $title );
		try {
			$content = ContentHandler::makeContent( $data, $title, $modelId, $format );

			if ( $shouldFail ) {
				$this->fail( "ContentHandler::makeContent should have failed!" );
			}

			$this->assertEquals( $expectedModelId, $content->getModel(), 'bad model id' );
			$this->assertEquals( $expectedNativeData, $content->getNativeData(), 'bads native data' );
		} catch ( MWException $ex ) {
			if ( !$shouldFail ) {
				$this->fail( "ContentHandler::makeContent failed unexpectedly: " . $ex->getMessage() );
			} else {
				// dummy, so we don't get the "test did not perform any assertions" message.
				$this->assertTrue( true );
			}
		}
	}

	/*
	 * Test if we become a "Created blank page" summary from getAutoSummary if no Content added to
	 * page.
	 */
	public function testGetAutosummary() {
		$this->setMwGlobals( 'wgContLang', Language::factory( 'en' ) );

		$content = new DummyContentHandlerForTesting( CONTENT_MODEL_WIKITEXT );
		$title = Title::newFromText( 'Help:Test' );
		// Create a new content object with no content
		$newContent = ContentHandler::makeContent( '', $title, null, null, CONTENT_MODEL_WIKITEXT );
		// first check, if we become a blank page created summary with the right bitmask
		$autoSummary = $content->getAutosummary( null, $newContent, 97 );
		$this->assertEquals( $autoSummary, 'Created blank page' );
		// now check, what we become with another bitmask
		$autoSummary = $content->getAutosummary( null, $newContent, 92 );
		$this->assertEquals( $autoSummary, '' );
	}

	/*
	public function testSupportsSections() {
		$this->markTestIncomplete( "not yet implemented" );
	}
	*/

	public function testSupportsDirectEditing() {
		$handler = new DummyContentHandlerForTesting( CONTENT_MODEL_JSON );
		$this->assertFalse( $handler->supportsDirectEditing(), 'direct editing is not supported' );
	}

	/**
	 * @covers ContentHandler::runLegacyHooks
	 */
	public function testRunLegacyHooks() {
		Hooks::register( 'testRunLegacyHooks', __CLASS__ . '::dummyHookHandler' );

		$content = new WikitextContent( 'test text' );
		$ok = ContentHandler::runLegacyHooks(
			'testRunLegacyHooks',
			array( 'foo', &$content, 'bar' ),
			false
		);

		$this->assertTrue( $ok, "runLegacyHooks should have returned true" );
		$this->assertEquals( "TEST TEXT", $content->getNativeData() );
	}

	public static function dummyHookHandler( $foo, &$text, $bar ) {
		if ( $text === null || $text === false ) {
			return false;
		}

		$text = strtoupper( $text );

		return true;
	}
}
