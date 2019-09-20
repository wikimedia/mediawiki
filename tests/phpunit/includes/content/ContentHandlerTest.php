<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\TestingAccessWrapper;

/**
 * @group ContentHandler
 * @group Database
 */
class ContentHandlerTest extends MediaWikiTestCase {

	protected function setUp() {
		parent::setUp();

		$this->setMwGlobals( [
			'wgExtraNamespaces' => [
				12312 => 'Dummy',
				12313 => 'Dummy_talk',
			],
			// The below tests assume that namespaces not mentioned here (Help, User, MediaWiki, ..)
			// default to CONTENT_MODEL_WIKITEXT.
			'wgNamespaceContentModels' => [
				12312 => 'testing',
			],
			'wgContentHandlers' => [
				CONTENT_MODEL_WIKITEXT => WikitextContentHandler::class,
				CONTENT_MODEL_JAVASCRIPT => JavaScriptContentHandler::class,
				CONTENT_MODEL_JSON => JsonContentHandler::class,
				CONTENT_MODEL_CSS => CssContentHandler::class,
				CONTENT_MODEL_TEXT => TextContentHandler::class,
				'testing' => DummyContentHandlerForTesting::class,
				'testing-callbacks' => function ( $modelId ) {
					return new DummyContentHandlerForTesting( $modelId );
				}
			],
		] );

		// Reset LinkCache
		MediaWikiServices::getInstance()->resetServiceForTesting( 'LinkCache' );
	}

	protected function tearDown() {
		// Reset LinkCache
		MediaWikiServices::getInstance()->resetServiceForTesting( 'LinkCache' );

		parent::tearDown();
	}

	public function addDBDataOnce() {
		$this->insertPage( 'Not_Main_Page', 'This is not a main page' );
		$this->insertPage( 'Smithee', 'A smithee is one who smiths. See also [[Alan Smithee]]' );
	}

	public static function dataGetDefaultModelFor() {
		return [
			[ 'Help:Foo', CONTENT_MODEL_WIKITEXT ],
			[ 'Help:Foo.js', CONTENT_MODEL_WIKITEXT ],
			[ 'Help:Foo.css', CONTENT_MODEL_WIKITEXT ],
			[ 'Help:Foo.json', CONTENT_MODEL_WIKITEXT ],
			[ 'Help:Foo/bar.js', CONTENT_MODEL_WIKITEXT ],
			[ 'User:Foo', CONTENT_MODEL_WIKITEXT ],
			[ 'User:Foo.js', CONTENT_MODEL_WIKITEXT ],
			[ 'User:Foo.css', CONTENT_MODEL_WIKITEXT ],
			[ 'User:Foo.json', CONTENT_MODEL_WIKITEXT ],
			[ 'User:Foo/bar.js', CONTENT_MODEL_JAVASCRIPT ],
			[ 'User:Foo/bar.css', CONTENT_MODEL_CSS ],
			[ 'User:Foo/bar.json', CONTENT_MODEL_JSON ],
			[ 'User:Foo/bar.json.nope', CONTENT_MODEL_WIKITEXT ],
			[ 'User talk:Foo/bar.css', CONTENT_MODEL_WIKITEXT ],
			[ 'User:Foo/bar.js.xxx', CONTENT_MODEL_WIKITEXT ],
			[ 'User:Foo/bar.xxx', CONTENT_MODEL_WIKITEXT ],
			[ 'MediaWiki:Foo.js', CONTENT_MODEL_JAVASCRIPT ],
			[ 'MediaWiki:Foo.JS', CONTENT_MODEL_WIKITEXT ],
			[ 'MediaWiki:Foo.css', CONTENT_MODEL_CSS ],
			[ 'MediaWiki:Foo.css.xxx', CONTENT_MODEL_WIKITEXT ],
			[ 'MediaWiki:Foo.CSS', CONTENT_MODEL_WIKITEXT ],
			[ 'MediaWiki:Foo.json', CONTENT_MODEL_JSON ],
			[ 'MediaWiki:Foo.JSON', CONTENT_MODEL_WIKITEXT ],
		];
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
		MediaWikiServices::getInstance()->getLinkCache()->addBadLinkObj( $title );
		$handler = ContentHandler::getForTitle( $title );
		$this->assertEquals( $expectedContentModel, $handler->getModelID() );
	}

	public static function dataGetLocalizedName() {
		return [
			[ null, null ],
			[ "xyzzy", null ],

			// XXX: depends on content language
			[ CONTENT_MODEL_JAVASCRIPT, '/javascript/i' ],
		];
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

		return [
			[ "Main", $wgLanguageCode ],
			[ "Dummy:Foo", $wgLanguageCode ],
			[ "MediaWiki:common.js", 'en' ],
			[ "User:Foo/common.js", 'en' ],
			[ "MediaWiki:common.css", 'en' ],
			[ "User:Foo/common.css", 'en' ],
			[ "User:Foo", $wgLanguageCode ],

			[ CONTENT_MODEL_JAVASCRIPT, 'javascript' ],
		];
	}

	/**
	 * @dataProvider dataGetPageLanguage
	 * @covers ContentHandler::getPageLanguage
	 */
	public function testGetPageLanguage( $title, $expected ) {
		if ( is_string( $title ) ) {
			$title = Title::newFromText( $title );
			MediaWikiServices::getInstance()->getLinkCache()->addBadLinkObj( $title );
		}

		$expected = wfGetLangObj( $expected );

		$handler = ContentHandler::getForTitle( $title );
		$lang = $handler->getPageLanguage( $title );

		$this->assertInstanceOf( Language::class, $lang );
		$this->assertEquals( $expected->getCode(), $lang->getCode() );
	}

	public static function dataGetContentText_Null() {
		return [
			[ 'fail' ],
			[ 'serialize' ],
			[ 'ignore' ],
		];
	}

	/**
	 * @dataProvider dataGetContentText_Null
	 * @covers ContentHandler::getContentText
	 */
	public function testGetContentText_Null( $contentHandlerTextFallback ) {
		$this->setMwGlobals( 'wgContentHandlerTextFallback', $contentHandlerTextFallback );

		$content = null;

		$text = ContentHandler::getContentText( $content );
		$this->assertSame( '', $text );
	}

	public static function dataGetContentText_TextContent() {
		return [
			[ 'fail' ],
			[ 'serialize' ],
			[ 'ignore' ],
		];
	}

	/**
	 * @dataProvider dataGetContentText_TextContent
	 * @covers ContentHandler::getContentText
	 */
	public function testGetContentText_TextContent( $contentHandlerTextFallback ) {
		$this->setMwGlobals( 'wgContentHandlerTextFallback', $contentHandlerTextFallback );

		$content = new WikitextContent( "hello world" );

		$text = ContentHandler::getContentText( $content );
		$this->assertEquals( $content->getText(), $text );
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

	public static function dataMakeContent() {
		return [
			[ 'hallo', 'Help:Test', null, null, CONTENT_MODEL_WIKITEXT, false ],
			[ 'hallo', 'MediaWiki:Test.js', null, null, CONTENT_MODEL_JAVASCRIPT, false ],
			[ serialize( 'hallo' ), 'Dummy:Test', null, null, "testing", false ],

			[
				'hallo',
				'Help:Test',
				null,
				CONTENT_FORMAT_WIKITEXT,
				CONTENT_MODEL_WIKITEXT,
				false
			],
			[
				'hallo',
				'MediaWiki:Test.js',
				null,
				CONTENT_FORMAT_JAVASCRIPT,
				CONTENT_MODEL_JAVASCRIPT,
				false
			],
			[ serialize( 'hallo' ), 'Dummy:Test', null, "testing", "testing", false ],

			[ 'hallo', 'Help:Test', CONTENT_MODEL_CSS, null, CONTENT_MODEL_CSS, false ],
			[
				'hallo',
				'MediaWiki:Test.js',
				CONTENT_MODEL_CSS,
				null,
				CONTENT_MODEL_CSS,
				false
			],
			[
				serialize( 'hallo' ),
				'Dummy:Test',
				CONTENT_MODEL_CSS,
				null,
				CONTENT_MODEL_CSS,
				false
			],

			[ 'hallo', 'Help:Test', CONTENT_MODEL_WIKITEXT, "testing", null, true ],
			[ 'hallo', 'MediaWiki:Test.js', CONTENT_MODEL_CSS, "testing", null, true ],
			[ 'hallo', 'Dummy:Test', CONTENT_MODEL_JAVASCRIPT, "testing", null, true ],
		];
	}

	/**
	 * @dataProvider dataMakeContent
	 * @covers ContentHandler::makeContent
	 */
	public function testMakeContent( $data, $title, $modelId, $format,
		$expectedModelId, $shouldFail
	) {
		$title = Title::newFromText( $title );
		MediaWikiServices::getInstance()->getLinkCache()->addBadLinkObj( $title );
		try {
			$content = ContentHandler::makeContent( $data, $title, $modelId, $format );

			if ( $shouldFail ) {
				$this->fail( "ContentHandler::makeContent should have failed!" );
			}

			$this->assertEquals( $expectedModelId, $content->getModel(), 'bad model id' );
			$this->assertEquals( $data, $content->serialize(), 'bad serialized data' );
		} catch ( MWException $ex ) {
			if ( !$shouldFail ) {
				$this->fail( "ContentHandler::makeContent failed unexpectedly: " . $ex->getMessage() );
			} else {
				// dummy, so we don't get the "test did not perform any assertions" message.
				$this->assertTrue( true );
			}
		}
	}

	/**
	 * @covers ContentHandler::getAutosummary
	 *
	 * Test if we become a "Created blank page" summary from getAutoSummary if no Content added to
	 * page.
	 */
	public function testGetAutosummary() {
		$this->setContentLang( 'en' );

		$content = new DummyContentHandlerForTesting( CONTENT_MODEL_WIKITEXT );
		$title = Title::newFromText( 'Help:Test' );
		// Create a new content object with no content
		$newContent = ContentHandler::makeContent( '', $title, CONTENT_MODEL_WIKITEXT, null );
		// first check, if we become a blank page created summary with the right bitmask
		$autoSummary = $content->getAutosummary( null, $newContent, 97 );
		$this->assertEquals( $autoSummary,
			wfMessage( 'autosumm-newblank' )->inContentLanguage()->text() );
		// now check, what we become with another bitmask
		$autoSummary = $content->getAutosummary( null, $newContent, 92 );
		$this->assertEquals( $autoSummary, '' );
	}

	/**
	 * Test software tag that is added when content model of the page changes
	 * @covers ContentHandler::getChangeTag
	 */
	public function testGetChangeTag() {
		$this->setMwGlobals( 'wgSoftwareTags', [ 'mw-contentmodelchange' => true ] );
		$wikitextContentHandler = new DummyContentHandlerForTesting( CONTENT_MODEL_WIKITEXT );
		// Create old content object with javascript content model
		$oldContent = ContentHandler::makeContent( '', null, CONTENT_MODEL_JAVASCRIPT, null );
		// Create new content object with wikitext content model
		$newContent = ContentHandler::makeContent( '', null, CONTENT_MODEL_WIKITEXT, null );
		// Get the tag for this edit
		$tag = $wikitextContentHandler->getChangeTag( $oldContent, $newContent, EDIT_UPDATE );
		$this->assertSame( $tag, 'mw-contentmodelchange' );
	}

	/**
	 * @covers ContentHandler::supportsCategories
	 */
	public function testSupportsCategories() {
		$handler = new DummyContentHandlerForTesting( CONTENT_MODEL_WIKITEXT );
		$this->assertTrue( $handler->supportsCategories(), 'content model supports categories' );
	}

	/**
	 * @covers ContentHandler::supportsDirectEditing
	 */
	public function testSupportsDirectEditing() {
		$handler = new DummyContentHandlerForTesting( CONTENT_MODEL_JSON );
		$this->assertFalse( $handler->supportsDirectEditing(), 'direct editing is not supported' );
	}

	public static function dummyHookHandler( $foo, &$text, $bar ) {
		if ( $text === null || $text === false ) {
			return false;
		}

		$text = strtoupper( $text );

		return true;
	}

	public function provideGetModelForID() {
		return [
			[ CONTENT_MODEL_WIKITEXT, WikitextContentHandler::class ],
			[ CONTENT_MODEL_JAVASCRIPT, JavaScriptContentHandler::class ],
			[ CONTENT_MODEL_JSON, JsonContentHandler::class ],
			[ CONTENT_MODEL_CSS, CssContentHandler::class ],
			[ CONTENT_MODEL_TEXT, TextContentHandler::class ],
			[ 'testing', DummyContentHandlerForTesting::class ],
			[ 'testing-callbacks', DummyContentHandlerForTesting::class ],
		];
	}

	/**
	 * @covers ContentHandler::getForModelID
	 * @dataProvider provideGetModelForID
	 */
	public function testGetModelForID( $modelId, $handlerClass ) {
		$handler = ContentHandler::getForModelID( $modelId );

		$this->assertInstanceOf( $handlerClass, $handler );
	}

	/**
	 * @covers ContentHandler::getFieldsForSearchIndex
	 */
	public function testGetFieldsForSearchIndex() {
		$searchEngine = $this->newSearchEngine();

		$handler = ContentHandler::getForModelID( CONTENT_MODEL_WIKITEXT );

		$fields = $handler->getFieldsForSearchIndex( $searchEngine );

		$this->assertArrayHasKey( 'category', $fields );
		$this->assertArrayHasKey( 'external_link', $fields );
		$this->assertArrayHasKey( 'outgoing_link', $fields );
		$this->assertArrayHasKey( 'template', $fields );
		$this->assertArrayHasKey( 'content_model', $fields );
	}

	private function newSearchEngine() {
		$searchEngine = $this->getMockBuilder( SearchEngine::class )
			->getMock();

		$searchEngine->expects( $this->any() )
			->method( 'makeSearchFieldMapping' )
			->will( $this->returnCallback( function ( $name, $type ) {
					return new DummySearchIndexFieldDefinition( $name, $type );
			} ) );

		return $searchEngine;
	}

	/**
	 * @covers ContentHandler::getDataForSearchIndex
	 */
	public function testDataIndexFields() {
		$mockEngine = $this->createMock( SearchEngine::class );
		$title = Title::newFromText( 'Not_Main_Page', NS_MAIN );
		$page = new WikiPage( $title );

		$this->setTemporaryHook( 'SearchDataForIndex',
			function (
				&$fields,
				ContentHandler $handler,
				WikiPage $page,
				ParserOutput $output,
				SearchEngine $engine
			) {
				$fields['testDataField'] = 'test content';
			} );

		$output = $page->getContent()->getParserOutput( $title );
		$data = $page->getContentHandler()->getDataForSearchIndex( $page, $output, $mockEngine );
		$this->assertArrayHasKey( 'text', $data );
		$this->assertArrayHasKey( 'text_bytes', $data );
		$this->assertArrayHasKey( 'language', $data );
		$this->assertArrayHasKey( 'testDataField', $data );
		$this->assertEquals( 'test content', $data['testDataField'] );
		$this->assertEquals( 'wikitext', $data['content_model'] );
	}

	/**
	 * @covers ContentHandler::getParserOutputForIndexing
	 */
	public function testParserOutputForIndexing() {
		$title = Title::newFromText( 'Smithee', NS_MAIN );
		$page = new WikiPage( $title );

		$out = $page->getContentHandler()->getParserOutputForIndexing( $page );
		$this->assertInstanceOf( ParserOutput::class, $out );
		$this->assertContains( 'one who smiths', $out->getRawText() );
	}

	/**
	 * @covers ContentHandler::getContentModels
	 */
	public function testGetContentModelsHook() {
		$this->setTemporaryHook( 'GetContentModels', function ( &$models ) {
			$models[] = 'Ferrari';
		} );
		$this->assertContains( 'Ferrari', ContentHandler::getContentModels() );
	}

	/**
	 * @covers ContentHandler::getSlotDiffRenderer
	 */
	public function testGetSlotDiffRenderer_default() {
		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'GetSlotDiffRenderer' => [],
		] );

		// test default renderer
		$contentHandler = new WikitextContentHandler( CONTENT_MODEL_WIKITEXT );
		$slotDiffRenderer = $contentHandler->getSlotDiffRenderer( RequestContext::getMain() );
		$this->assertInstanceOf( TextSlotDiffRenderer::class, $slotDiffRenderer );
	}

	/**
	 * @covers ContentHandler::getSlotDiffRenderer
	 */
	public function testGetSlotDiffRenderer_bc() {
		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'GetSlotDiffRenderer' => [],
		] );

		// test B/C renderer
		$customDifferenceEngine = $this->getMockBuilder( DifferenceEngine::class )
			->disableOriginalConstructor()
			->getMock();
		// hack to track object identity across cloning
		$customDifferenceEngine->objectId = 12345;
		$customContentHandler = $this->getMockBuilder( ContentHandler::class )
			->setConstructorArgs( [ 'foo', [] ] )
			->setMethods( [ 'createDifferenceEngine' ] )
			->getMockForAbstractClass();
		$customContentHandler->expects( $this->any() )
			->method( 'createDifferenceEngine' )
			->willReturn( $customDifferenceEngine );
		/** @var ContentHandler $customContentHandler */
		$slotDiffRenderer = $customContentHandler->getSlotDiffRenderer( RequestContext::getMain() );
		$this->assertInstanceOf( DifferenceEngineSlotDiffRenderer::class, $slotDiffRenderer );
		$this->assertSame(
			$customDifferenceEngine->objectId,
			TestingAccessWrapper::newFromObject( $slotDiffRenderer )->differenceEngine->objectId
		);
	}

	/**
	 * @covers ContentHandler::getSlotDiffRenderer
	 */
	public function testGetSlotDiffRenderer_nobc() {
		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'GetSlotDiffRenderer' => [],
		] );

		// test that B/C renderer does not get used when getSlotDiffRendererInternal is overridden
		$customDifferenceEngine = $this->getMockBuilder( DifferenceEngine::class )
			->disableOriginalConstructor()
			->getMock();
		$customSlotDiffRenderer = $this->getMockBuilder( SlotDiffRenderer::class )
			->disableOriginalConstructor()
			->getMockForAbstractClass();
		$customContentHandler2 = $this->getMockBuilder( ContentHandler::class )
			->setConstructorArgs( [ 'bar', [] ] )
			->setMethods( [ 'createDifferenceEngine', 'getSlotDiffRendererInternal' ] )
			->getMockForAbstractClass();
		$customContentHandler2->expects( $this->any() )
			->method( 'createDifferenceEngine' )
			->willReturn( $customDifferenceEngine );
		$customContentHandler2->expects( $this->any() )
			->method( 'getSlotDiffRendererInternal' )
			->willReturn( $customSlotDiffRenderer );
		/** @var ContentHandler $customContentHandler2 */
		$slotDiffRenderer = $customContentHandler2->getSlotDiffRenderer( RequestContext::getMain() );
		$this->assertSame( $customSlotDiffRenderer, $slotDiffRenderer );
	}

	/**
	 * @covers ContentHandler::getSlotDiffRenderer
	 */
	public function testGetSlotDiffRenderer_hook() {
		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'GetSlotDiffRenderer' => [],
		] );

		// test that the hook handler takes precedence
		$customDifferenceEngine = $this->getMockBuilder( DifferenceEngine::class )
			->disableOriginalConstructor()
			->getMock();
		$customContentHandler = $this->getMockBuilder( ContentHandler::class )
			->setConstructorArgs( [ 'foo', [] ] )
			->setMethods( [ 'createDifferenceEngine' ] )
			->getMockForAbstractClass();
		$customContentHandler->expects( $this->any() )
			->method( 'createDifferenceEngine' )
			->willReturn( $customDifferenceEngine );
		/** @var ContentHandler $customContentHandler */

		$customSlotDiffRenderer = $this->getMockBuilder( SlotDiffRenderer::class )
			->disableOriginalConstructor()
			->getMockForAbstractClass();
		$customContentHandler2 = $this->getMockBuilder( ContentHandler::class )
			->setConstructorArgs( [ 'bar', [] ] )
			->setMethods( [ 'createDifferenceEngine', 'getSlotDiffRendererInternal' ] )
			->getMockForAbstractClass();
		$customContentHandler2->expects( $this->any() )
			->method( 'createDifferenceEngine' )
			->willReturn( $customDifferenceEngine );
		$customContentHandler2->expects( $this->any() )
			->method( 'getSlotDiffRendererInternal' )
			->willReturn( $customSlotDiffRenderer );
		/** @var ContentHandler $customContentHandler2 */

		$customSlotDiffRenderer2 = $this->getMockBuilder( SlotDiffRenderer::class )
			->disableOriginalConstructor()
			->getMockForAbstractClass();
		$this->setTemporaryHook( 'GetSlotDiffRenderer',
			function ( $handler, &$slotDiffRenderer ) use ( $customSlotDiffRenderer2 ) {
				$slotDiffRenderer = $customSlotDiffRenderer2;
			} );

		$slotDiffRenderer = $customContentHandler->getSlotDiffRenderer( RequestContext::getMain() );
		$this->assertSame( $customSlotDiffRenderer2, $slotDiffRenderer );
		$slotDiffRenderer = $customContentHandler2->getSlotDiffRenderer( RequestContext::getMain() );
		$this->assertSame( $customSlotDiffRenderer2, $slotDiffRenderer );
	}

}
