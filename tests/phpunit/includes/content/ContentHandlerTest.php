<?php

use MediaWiki\Content\ContentHandler;
use MediaWiki\Content\CssContentHandler;
use MediaWiki\Content\JavaScriptContentHandler;
use MediaWiki\Content\JsonContent;
use MediaWiki\Content\JsonContentHandler;
use MediaWiki\Content\TextContentHandler;
use MediaWiki\Content\ValidationParams;
use MediaWiki\Content\WikitextContent;
use MediaWiki\Content\WikitextContentHandler;
use MediaWiki\Context\RequestContext;
use MediaWiki\Exception\MWException;
use MediaWiki\Language\Language;
use MediaWiki\Languages\LanguageNameUtils;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\Hook\OpportunisticLinksUpdateHook;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\Page\WikiPage;
use MediaWiki\Parser\MagicWordFactory;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\Parsoid\ParsoidParserFactory;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFactory;
use Wikimedia\TestingAccessWrapper;
use Wikimedia\UUID\GlobalIdGenerator;

/**
 * @group ContentHandler
 * @group Database
 * @covers \MediaWiki\Content\ContentHandler
 */
class ContentHandlerTest extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();

		$this->overrideConfigValues( [
			MainConfigNames::ExtraNamespaces => [
				12312 => 'Dummy',
				12313 => 'Dummy_talk',
			],
			// The below tests assume that namespaces not mentioned here (Help, User, MediaWiki, ..)
			// default to CONTENT_MODEL_WIKITEXT.
			MainConfigNames::NamespaceContentModels => [
				12312 => 'testing',
			],
			MainConfigNames::ContentHandlers => [
				CONTENT_MODEL_WIKITEXT => [
					'class' => WikitextContentHandler::class,
					'services' => [
						'TitleFactory',
						'ParserFactory',
						'GlobalIdGenerator',
						'LanguageNameUtils',
						'LinkRenderer',
						'MagicWordFactory',
						'ParsoidParserFactory',
					],
				],
				CONTENT_MODEL_JAVASCRIPT => [
					'class' => JavaScriptContentHandler::class,
					'services' => [
						'MainConfig',
						'ParserFactory',
						'UserOptionsLookup',
					],
				],
				CONTENT_MODEL_JSON => [
					'class' => JsonContentHandler::class,
					'services' => [
						'ParsoidParserFactory',
						'TitleFactory',
					],
				],
				CONTENT_MODEL_CSS => [
					'class' => CssContentHandler::class,
					'services' => [
						'MainConfig',
						'ParserFactory',
						'UserOptionsLookup',
					],
				],
				CONTENT_MODEL_TEXT => TextContentHandler::class,
				'testing' => DummyContentHandlerForTesting::class,
				'testing-callbacks' => static function ( $modelId ) {
					return new DummyContentHandlerForTesting( $modelId );
				}
			],
		] );
	}

	public function addDBDataOnce() {
		$this->insertPage( 'Not_Main_Page', 'This is not a main page' );
		$this->insertPage( 'Smithee', 'A smithee is one who smiths. See also [[Alan Smithee]]' );
	}

	public static function provideGetDefaultModelFor() {
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
	 * @dataProvider provideGetDefaultModelFor
	 */
	public function testGetDefaultModelFor( $title, $expectedModelId ) {
		$title = Title::newFromText( $title );
		$this->hideDeprecated( 'MediaWiki\\Content\\ContentHandler::getDefaultModelFor' );
		$this->assertEquals( $expectedModelId, ContentHandler::getDefaultModelFor( $title ) );
	}

	public static function provideGetLocalizedName() {
		return [
			[ null, null ],
			[ "xyzzy", null ],

			// XXX: depends on content language
			[ CONTENT_MODEL_JAVASCRIPT, '/javascript/i' ],
		];
	}

	/**
	 * @dataProvider provideGetLocalizedName
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

	public static function provideGetPageLanguage() {
		global $wgLanguageCode;

		return [
			[ "Main", $wgLanguageCode ],
			[ "Dummy:Foo", $wgLanguageCode ],
			[ "MediaWiki:common.js", 'en' ],
			[ "User:Foo/common.js", 'en' ],
			[ "MediaWiki:common.css", 'en' ],
			[ "User:Foo/common.css", 'en' ],
			[ "User:Foo", $wgLanguageCode ],
		];
	}

	/**
	 * @dataProvider provideGetPageLanguage
	 */
	public function testGetPageLanguage( $title, $expected ) {
		$title = Title::newFromText( $title );
		$this->getServiceContainer()->getLinkCache()->addBadLinkObj( $title );

		$handler = $this->getServiceContainer()
			->getContentHandlerFactory()
			->getContentHandler( $title->getContentModel() );
		$lang = $handler->getPageLanguage( $title );

		$this->assertInstanceOf( Language::class, $lang );
		$this->assertEquals( $expected, $lang->getCode() );
	}

	public function testGetContentText_Null() {
		$this->hideDeprecated( 'MediaWiki\\Content\\ContentHandler::getContentText' );
		$content = null;
		$text = ContentHandler::getContentText( $content );
		$this->assertSame( '', $text );
	}

	public function testGetContentText_TextContent() {
		$this->hideDeprecated( 'MediaWiki\\Content\\ContentHandler::getContentText' );
		$content = new WikitextContent( "hello world" );
		$text = ContentHandler::getContentText( $content );
		$this->assertEquals( $content->getText(), $text );
	}

	public function testGetContentText_NonTextContent() {
		$this->hideDeprecated( 'MediaWiki\\Content\\ContentHandler::getContentText' );
		$content = new DummyContentForTesting( "hello world" );
		$text = ContentHandler::getContentText( $content );
		$this->assertNull( $text );
	}

	public static function provideMakeContent() {
		return [
			[ 'hallo', 'Help:Test', null, null, CONTENT_MODEL_WIKITEXT, false ],
			[ 'hallo', 'MediaWiki:Test.js', null, null, CONTENT_MODEL_JAVASCRIPT, false ],
			[ 'hallo', 'Dummy:Test', null, null, "testing", false ],

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
			[ 'hallo', 'Dummy:Test', null, "testing", "testing", false ],

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
	 * @dataProvider provideMakeContent
	 */
	public function testMakeContent( $data, $title, $modelId, $format,
		$expectedModelId, $shouldFail
	) {
		$title = Title::newFromText( $title );
		$this->getServiceContainer()->getLinkCache()->addBadLinkObj( $title );
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
	 * getAutoSummary() should set "Created blank page" summary if we save an empy string.
	 */
	public function testGetAutosummary() {
		$this->setContentLang( 'en' );

		$content = new DummyContentHandlerForTesting( CONTENT_MODEL_WIKITEXT );
		$title = Title::makeTitle( NS_HELP, 'Test' );
		// Create a new content object with no content
		$newContent = ContentHandler::makeContent( '', $title, CONTENT_MODEL_WIKITEXT, null );
		// first check, if we become a blank page created summary with the right bitmask
		$autoSummary = $content->getAutosummary( null, $newContent, 97 );
		$this->assertEquals(
			wfMessage( 'autosumm-newblank' )->inContentLanguage()->text(),
			$autoSummary
		);
		// now check, what we become with another bitmask
		$autoSummary = $content->getAutosummary( null, $newContent, 92 );
		$this->assertSame( '', $autoSummary );
	}

	/**
	 * Test software tag that is added when content model of the page changes
	 */
	public function testGetChangeTag() {
		$this->overrideConfigValue( MainConfigNames::SoftwareTags, [ 'mw-contentmodelchange' => true ] );
		$wikitextContentHandler = new DummyContentHandlerForTesting( CONTENT_MODEL_WIKITEXT );
		// Create old content object with javascript content model
		$oldContent = ContentHandler::makeContent( '', null, CONTENT_MODEL_JAVASCRIPT, null );
		// Create new content object with wikitext content model
		$newContent = ContentHandler::makeContent( '', null, CONTENT_MODEL_WIKITEXT, null );
		// Get the tag for this edit
		$tag = $wikitextContentHandler->getChangeTag( $oldContent, $newContent, EDIT_UPDATE );
		$this->assertSame( 'mw-contentmodelchange', $tag );
	}

	public function testSupportsCategories() {
		$handler = new DummyContentHandlerForTesting( CONTENT_MODEL_WIKITEXT );
		$this->assertTrue( $handler->supportsCategories(), 'content model supports categories' );
	}

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

	public static function provideGetModelForID() {
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
	 * @dataProvider provideGetModelForID
	 */
	public function testGetModelForID( $modelId, $handlerClass ) {
		$handler = $this->getServiceContainer()->getContentHandlerFactory()
			->getContentHandler( $modelId );

		$this->assertInstanceOf( $handlerClass, $handler );
	}

	public function testGetFieldsForSearchIndex() {
		$searchEngine = $this->newSearchEngine();

		$handler = $this->getMockBuilder( ContentHandler::class )
			->onlyMethods(
				[ 'serializeContent', 'unserializeContent', 'makeEmptyContent' ]
			)
			->disableOriginalConstructor()
			->getMock();

		$fields = $handler->getFieldsForSearchIndex( $searchEngine );

		$this->assertArrayHasKey( 'category', $fields );
		$this->assertArrayHasKey( 'external_link', $fields );
		$this->assertArrayHasKey( 'outgoing_link', $fields );
		$this->assertArrayHasKey( 'template', $fields );
		$this->assertArrayHasKey( 'content_model', $fields );
	}

	private function newSearchEngine() {
		$searchEngine = $this->createMock( SearchEngine::class );

		$searchEngine->method( 'makeSearchFieldMapping' )
			->willReturnCallback( static function ( $name, $type ) {
					return new DummySearchIndexFieldDefinition( $name, $type );
			} );

		return $searchEngine;
	}

	public function testDataIndexFields() {
		$mockEngine = $this->createMock( SearchEngine::class );
		$title = Title::makeTitle( NS_MAIN, 'Not_Main_Page' );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );

		$this->setTemporaryHook( 'SearchDataForIndex',
			static function (
				&$fields,
				ContentHandler $handler,
				WikiPage $page,
				ParserOutput $output,
				SearchEngine $engine
			) {
				$fields['testDataField'] = 'test content';
			} );

		$revision = $page->getRevisionRecord();
		$output = $page->getContentHandler()->getParserOutputForIndexing( $page, null, $revision );
		$data = $page->getContentHandler()->getDataForSearchIndex( $page, $output, $mockEngine, $revision );
		$this->assertArrayHasKey( 'text', $data );
		$this->assertArrayHasKey( 'text_bytes', $data );
		$this->assertArrayHasKey( 'language', $data );
		$this->assertArrayHasKey( 'testDataField', $data );
		$this->assertEquals( 'test content', $data['testDataField'] );
		$this->assertEquals( 'wikitext', $data['content_model'] );
	}

	public function testParserOutputForIndexing() {
		$opportunisticUpdateHook =
			$this->createMock( OpportunisticLinksUpdateHook::class );
		// WikiPage::triggerOpportunisticLinksUpdate should not be triggered when
		// getParserOutputForIndexing is called
		$opportunisticUpdateHook->expects( $this->never() )
			->method( 'onOpportunisticLinksUpdate' )
			->willReturn( false );
		$this->setTemporaryHook( 'OpportunisticLinksUpdate', $opportunisticUpdateHook );

		$title = Title::makeTitle( NS_MAIN, 'Smithee' );
		$page = $this->getServiceContainer()->getWikiPageFactory()->newFromTitle( $title );
		$revision = $page->getRevisionRecord();

		$out = $page->getContentHandler()->getParserOutputForIndexing( $page, null, $revision );
		$this->assertInstanceOf( ParserOutput::class, $out );
		$this->assertStringContainsString( 'one who smiths', $out->getRawText() );
	}

	public function testGetContentModelsHook() {
		$this->setTemporaryHook( 'GetContentModels', static function ( &$models ) {
			$models[] = 'Ferrari';
		} );
		$this->hideDeprecated( 'MediaWiki\\Content\\ContentHandler::getContentModels' );
		$this->assertContains( 'Ferrari', ContentHandler::getContentModels() );
	}

	public function testGetSlotDiffRenderer_default() {
		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'GetSlotDiffRenderer' => [],
		] );

		// test default renderer
		$contentHandler = new WikitextContentHandler(
			CONTENT_MODEL_WIKITEXT,
			$this->createMock( TitleFactory::class ),
			$this->createMock( ParserFactory::class ),
			$this->createMock( GlobalIdGenerator::class ),
			$this->createMock( LanguageNameUtils::class ),
			$this->createMock( LinkRenderer::class ),
			$this->createMock( MagicWordFactory::class ),
			$this->createMock( ParsoidParserFactory::class )
		);
		$slotDiffRenderer = $contentHandler->getSlotDiffRenderer( RequestContext::getMain() );
		$this->assertInstanceOf( TextSlotDiffRenderer::class, $slotDiffRenderer );
	}

	public function testGetSlotDiffRenderer_bc() {
		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'GetSlotDiffRenderer' => [],
		] );

		// test B/C renderer
		$customDifferenceEngine = $this->createMock( DifferenceEngine::class );
		// hack to track object identity across cloning
		$customDifferenceEngine->objectId = 12345;
		$customContentHandler = $this->getMockBuilder( ContentHandler::class )
			->setConstructorArgs( [ 'foo', [] ] )
			->onlyMethods( [ 'createDifferenceEngine' ] )
			->getMockForAbstractClass();
		$customContentHandler->method( 'createDifferenceEngine' )
			->willReturn( $customDifferenceEngine );
		$slotDiffRenderer = $customContentHandler->getSlotDiffRenderer( RequestContext::getMain() );
		$this->assertInstanceOf( DifferenceEngineSlotDiffRenderer::class, $slotDiffRenderer );
		$this->assertSame(
			$customDifferenceEngine->objectId,
			TestingAccessWrapper::newFromObject( $slotDiffRenderer )->differenceEngine->objectId
		);
	}

	public function testGetSlotDiffRenderer_nobc() {
		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'GetSlotDiffRenderer' => [],
		] );

		// test that B/C renderer does not get used when getSlotDiffRendererInternal is overridden
		$customDifferenceEngine = $this->createMock( DifferenceEngine::class );
		$customSlotDiffRenderer = $this->getMockBuilder( SlotDiffRenderer::class )
			->disableOriginalConstructor()
			->getMockForAbstractClass();
		$customContentHandler2 = $this->getMockBuilder( ContentHandler::class )
			->setConstructorArgs( [ 'bar', [] ] )
			->onlyMethods( [ 'createDifferenceEngine', 'getSlotDiffRendererInternal' ] )
			->getMockForAbstractClass();
		$customContentHandler2->method( 'createDifferenceEngine' )
			->willReturn( $customDifferenceEngine );
		$customContentHandler2->method( 'getSlotDiffRendererInternal' )
			->willReturn( $customSlotDiffRenderer );
		$this->hideDeprecated( 'ContentHandler::getSlotDiffRendererInternal' );
		$slotDiffRenderer = $customContentHandler2->getSlotDiffRenderer( RequestContext::getMain() );
		$this->assertSame( $customSlotDiffRenderer, $slotDiffRenderer );
	}

	public function testGetSlotDiffRenderer_hook() {
		$this->mergeMwGlobalArrayValue( 'wgHooks', [
			'GetSlotDiffRenderer' => [],
		] );

		// test that the hook handler takes precedence
		$customDifferenceEngine = $this->createMock( DifferenceEngine::class );
		$customContentHandler = $this->getMockBuilder( ContentHandler::class )
			->setConstructorArgs( [ 'foo', [] ] )
			->onlyMethods( [ 'createDifferenceEngine' ] )
			->getMockForAbstractClass();
		$customContentHandler->method( 'createDifferenceEngine' )
			->willReturn( $customDifferenceEngine );

		$customSlotDiffRenderer = $this->getMockBuilder( SlotDiffRenderer::class )
			->disableOriginalConstructor()
			->getMockForAbstractClass();
		$customContentHandler2 = $this->getMockBuilder( ContentHandler::class )
			->setConstructorArgs( [ 'bar', [] ] )
			->onlyMethods( [ 'createDifferenceEngine', 'getSlotDiffRendererInternal' ] )
			->getMockForAbstractClass();
		$customContentHandler2->method( 'createDifferenceEngine' )
			->willReturn( $customDifferenceEngine );
		$customContentHandler2->method( 'getSlotDiffRendererInternal' )
			->willReturn( $customSlotDiffRenderer );

		$customSlotDiffRenderer2 = $this->getMockBuilder( SlotDiffRenderer::class )
			->disableOriginalConstructor()
			->getMockForAbstractClass();
		$this->setTemporaryHook( 'GetSlotDiffRenderer',
			static function ( $handler, &$slotDiffRenderer ) use ( $customSlotDiffRenderer2 ) {
				$slotDiffRenderer = $customSlotDiffRenderer2;
			} );

		$this->hideDeprecated( 'ContentHandler::getSlotDiffRendererInternal' );
		$slotDiffRenderer = $customContentHandler->getSlotDiffRenderer( RequestContext::getMain() );
		$this->assertSame( $customSlotDiffRenderer2, $slotDiffRenderer );

		$this->hideDeprecated( 'ContentHandler::getSlotDiffRendererInternal' );
		$slotDiffRenderer = $customContentHandler2->getSlotDiffRenderer( RequestContext::getMain() );
		$this->assertSame( $customSlotDiffRenderer2, $slotDiffRenderer );
	}

	public static function providerGetPageViewLanguage() {
		yield [ NS_FILE, 'sr', 'sr-ec', 'sr-ec' ];
		yield [ NS_FILE, 'sr', 'sr', 'sr' ];
		yield [ NS_MEDIAWIKI, 'sr-ec', 'sr', 'sr-ec' ];
		yield [ NS_MEDIAWIKI, 'sr', 'sr-ec', 'sr' ];
	}

	/**
	 * Superseded by OutputPageTest::testGetJsVarsAboutPageLang
	 *
	 * @dataProvider providerGetPageViewLanguage
	 */
	public function testGetPageViewLanguage( $namespace, $lang, $variant, $expected ) {
		$contentHandler = $this->getMockBuilder( ContentHandler::class )
			->disableOriginalConstructor()
			->getMockForAbstractClass();

		$title = Title::makeTitle( $namespace, 'SimpleTitle' );

		$this->overrideConfigValue( MainConfigNames::DefaultLanguageVariant, $variant );

		$this->setUserLang( $lang );
		$this->setContentLang( $lang );

		$pageViewLanguage = $contentHandler->getPageViewLanguage( $title );
		$this->assertEquals( $expected, $pageViewLanguage->getCode() );
	}

	public static function provideValidateSave() {
		yield 'wikitext' => [
			new WikitextContent( 'hello world' ),
			true
		];

		yield 'valid json' => [
			new JsonContent( '{ "0": "bar" }' ),
			true
		];

		yield 'invalid json' => [
			new JsonContent( 'foo' ),
			false
		];
	}

	/**
	 * @dataProvider provideValidateSave
	 */
	public function testValidateSave( $content, $expectedResult ) {
		$page = PageIdentityValue::localIdentity( 0, 1, 'Foo' );
		$contentHandlerFactory = $this->getServiceContainer()->getContentHandlerFactory();
		$contentHandler = $contentHandlerFactory->getContentHandler( $content->getModel() );
		$validateParams = new ValidationParams( $page, 0 );

		$status = $contentHandler->validateSave( $content, $validateParams );
		$this->assertEquals( $expectedResult, $status->isOK() );
	}
}
