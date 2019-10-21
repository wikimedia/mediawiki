<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers ResourceLoaderWikiModule
 */
class ResourceLoaderWikiModuleTest extends ResourceLoaderTestCase {

	/**
	 * @dataProvider provideConstructor
	 */
	public function testConstructor( $params ) {
		$module = new ResourceLoaderWikiModule( $params );
		$this->assertInstanceOf( ResourceLoaderWikiModule::class, $module );
	}

	public static function provideConstructor() {
		yield 'null' => [ null ];
		yield 'empty' => [ [] ];
		yield 'unknown settings' => [ [ 'foo' => 'baz' ] ];
		yield 'real settings' => [ [ 'MediaWiki:Common.js' ] ];
	}

	private function prepareTitleInfo( array $mockInfo ) {
		$module = TestingAccessWrapper::newFromClass( ResourceLoaderWikiModule::class );
		$info = [];
		foreach ( $mockInfo as $key => $val ) {
			$info[ $module->makeTitleKey( Title::newFromText( $key ) ) ] = $val;
		}
		return $info;
	}

	/**
	 * @dataProvider provideGetPages
	 */
	public function testGetPages( $params, Config $config, $expected ) {
		$module = new ResourceLoaderWikiModule( $params );
		$module->setConfig( $config );

		// Because getPages is protected..
		$getPages = new ReflectionMethod( $module, 'getPages' );
		$getPages->setAccessible( true );
		$out = $getPages->invoke( $module, ResourceLoaderContext::newDummyContext() );
		$this->assertSame( $expected, $out );
	}

	public static function provideGetPages() {
		$settings = self::getSettings() + [
			'UseSiteJs' => true,
			'UseSiteCss' => true,
		];

		$params = [
			'styles' => [ 'MediaWiki:Common.css' ],
			'scripts' => [ 'MediaWiki:Common.js' ],
		];

		return [
			[ [], new HashConfig( $settings ), [] ],
			[ $params, new HashConfig( $settings ), [
				'MediaWiki:Common.js' => [ 'type' => 'script' ],
				'MediaWiki:Common.css' => [ 'type' => 'style' ]
			] ],
			[ $params, new HashConfig( [ 'UseSiteCss' => false ] + $settings ), [
				'MediaWiki:Common.js' => [ 'type' => 'script' ],
			] ],
			[ $params, new HashConfig( [ 'UseSiteJs' => false ] + $settings ), [
				'MediaWiki:Common.css' => [ 'type' => 'style' ],
			] ],
			[ $params,
				new HashConfig(
					[ 'UseSiteJs' => false, 'UseSiteCss' => false ]
				),
				[]
			],
		];
	}

	/**
	 * @dataProvider provideGetGroup
	 */
	public function testGetGroup( $params, $expected ) {
		$module = new ResourceLoaderWikiModule( $params );
		$this->assertSame( $expected, $module->getGroup() );
	}

	public static function provideGetGroup() {
		yield 'no group' => [ [], null ];
		yield 'some group' => [ [ 'group' => 'foobar' ], 'foobar' ];
	}

	/**
	 * @dataProvider provideGetType
	 */
	public function testGetType( $params, $expected ) {
		$module = new ResourceLoaderWikiModule( $params );
		$this->assertSame( $expected, $module->getType() );
	}

	public static function provideGetType() {
		yield 'empty' => [
			[],
			ResourceLoaderWikiModule::LOAD_GENERAL,
		];
		yield 'scripts' => [
			[ 'scripts' => [ 'Example.js' ] ],
			ResourceLoaderWikiModule::LOAD_GENERAL,
		];
		yield 'styles' => [
			[ 'styles' => [ 'Example.css' ] ],
			ResourceLoaderWikiModule::LOAD_STYLES,
		];
		yield 'styles and scripts' => [
			[ 'styles' => [ 'Example.css' ], 'scripts' => [ 'Example.js' ] ],
			ResourceLoaderWikiModule::LOAD_GENERAL,
		];
	}

	/**
	 * @dataProvider provideIsKnownEmpty
	 */
	public function testIsKnownEmpty( $titleInfo, $group, $dependencies, $expected ) {
		$module = $this->getMockBuilder( ResourceLoaderWikiModule::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getTitleInfo', 'getGroup', 'getDependencies' ] )
			->getMock();
		$module->method( 'getTitleInfo' )
			->willReturn( $this->prepareTitleInfo( $titleInfo ) );
		$module->method( 'getGroup' )
			->willReturn( $group );
		$module->method( 'getDependencies' )
			->willReturn( $dependencies );
		$context = $this->createMock( ResourceLoaderContext::class );
		$this->assertSame( $expected, $module->isKnownEmpty( $context ) );
	}

	public static function provideIsKnownEmpty() {
		yield 'nothing' => [
			[],
			null,
			[],
			// No pages exist, considered empty.
			true,
		];

		yield 'an empty page exists (no group)' => [
			[ 'Project:Example/foo.js' => [ 'page_len' => 0 ] ],
			null,
			[],
			// There is an existing page, so we should let the module be queued.
			// Its emptiness might be temporary, hence considered non-empty (T70488).
			false,
		];
		yield 'an empty page exists (site group)' => [
			[ 'MediaWiki:Foo.js' => [ 'page_len' => 0 ] ],
			'site',
			[],
			// There is an existing page, hence considered non-empty.
			false,
		];
		yield 'an empty page exists (user group)' => [
			[ 'User:Example/foo.js' => [ 'page_len' => 0 ] ],
			'user',
			[],
			// There is an existing page, but it is empty.
			// For user-specific modules, don't bother loading a known-empty module.
			// Given user-specific HTML output, this will vary and re-appear if/when
			// the page becomes non-empty again.
			true,
		];

		yield 'no pages but having dependencies (no group)' => [
			[],
			null,
			[ 'another-module' ],
			false,
		];
		yield 'no pages but having dependencies (site group)' => [
			[],
			'site',
			[ 'another-module' ],
			false,
		];
		yield 'no pages but having dependencies (user group)' => [
			[],
			'user',
			[ 'another-module' ],
			false,
		];

		yield 'a non-empty page exists (user group)' => [
			[ 'User:Example/foo.js' => [ 'page_len' => 25 ] ],
			'user',
			[],
			false,
		];
		yield 'a non-empty page exists (site group)' => [
			[ 'MediaWiki:Foo.js' => [ 'page_len' => 25 ] ],
			'site',
			[],
			false,
		];
	}

	public function testGetTitleInfo() {
		$pages = [
			'MediaWiki:Common.css' => [ 'type' => 'styles' ],
			'mediawiki: fallback.css' => [ 'type' => 'styles' ],
		];
		$titleInfo = $this->prepareTitleInfo( [
			'MediaWiki:Common.css' => [ 'page_len' => 1234 ],
			'MediaWiki:Fallback.css' => [ 'page_len' => 0 ],
		] );
		$expected = $titleInfo;

		$module = $this->getMockBuilder( ResourceLoaderWikiModule::class )
			->setMethods( [ 'getPages', 'getTitleInfo' ] )
			->getMock();
		$module->method( 'getPages' )->willReturn( $pages );
		$module->method( 'getTitleInfo' )->willReturn( $titleInfo );

		$context = $this->getMockBuilder( ResourceLoaderContext::class )
			->disableOriginalConstructor()
			->getMock();

		$module = TestingAccessWrapper::newFromObject( $module );
		$this->assertSame( $expected, $module->getTitleInfo( $context ), 'Title info' );
	}

	public function testGetPreloadedTitleInfo() {
		$pages = [
			'MediaWiki:Common.css' => [ 'type' => 'styles' ],
			// Regression against T145673. It's impossible to statically declare page names in
			// a canonical way since the canonical prefix is localised. As such, the preload
			// cache computed the right cache key, but failed to find the results when
			// doing an intersect on the canonical result, producing an empty array.
			'mediawiki: fallback.css' => [ 'type' => 'styles' ],
		];
		$titleInfo = $this->prepareTitleInfo( [
			'MediaWiki:Common.css' => [ 'page_len' => 1234 ],
			'MediaWiki:Fallback.css' => [ 'page_len' => 0 ],
		] );
		$expected = $titleInfo;

		$module = $this->getMockBuilder( TestResourceLoaderWikiModule::class )
			->setMethods( [ 'getPages' ] )
			->getMock();
		$module->method( 'getPages' )->willReturn( $pages );
		// Can't mock static methods
		$module::$returnFetchTitleInfo = $titleInfo;

		$rl = new EmptyResourceLoader();
		$context = new ResourceLoaderContext( $rl, new FauxRequest() );

		TestResourceLoaderWikiModule::invalidateModuleCache(
			Title::newFromText( 'MediaWiki:Common.css' ),
			null,
			null,
			wfWikiID()
		);
		TestResourceLoaderWikiModule::preloadTitleInfo(
			$context,
			$this->createMock( IDatabase::class ),
			[ 'testmodule' ]
		);

		$module = TestingAccessWrapper::newFromObject( $module );
		$this->assertSame( $expected, $module->getTitleInfo( $context ), 'Title info' );
	}

	public function testGetPreloadedBadTitle() {
		// Set up
		TestResourceLoaderWikiModule::$returnFetchTitleInfo = [];
		$rl = new EmptyResourceLoader();
		$rl->getConfig()->set( 'UseSiteJs', true );
		$rl->getConfig()->set( 'UseSiteCss', true );
		$rl->register( 'testmodule', [
			'class' => TestResourceLoaderWikiModule::class,
			// Covers preloadTitleInfo branch for invalid page name
			'styles' => [ '[x]' ],
		] );
		$context = new ResourceLoaderContext( $rl, new FauxRequest() );

		// Act
		TestResourceLoaderWikiModule::preloadTitleInfo(
			$context,
			$this->createMock( IDatabase::class ),
			[ 'testmodule' ]
		);

		// Assert
		$module = TestingAccessWrapper::newFromObject( $rl->getModule( 'testmodule' ) );
		$this->assertSame( [], $module->getTitleInfo( $context ), 'Title info' );
	}

	public function testGetPreloadedTitleInfoEmpty() {
		$context = new ResourceLoaderContext( new EmptyResourceLoader(), new FauxRequest() );
		// This covers the early return case
		$this->assertSame(
			null,
			ResourceLoaderWikiModule::preloadTitleInfo(
				$context,
				$this->createMock( IDatabase::class ),
				[]
			)
		);
	}

	public static function provideGetContent() {
		yield 'Bad title' => [ null, '[x]' ];

		yield 'No JS content found' => [ null, [
			'text' => 'MediaWiki:Foo.js',
			'ns' => NS_MEDIAWIKI,
			'title' => 'Foo.js',
		] ];

		yield 'JS content' => [ 'code;', [
			'text' => 'MediaWiki:Foo.js',
			'ns' => NS_MEDIAWIKI,
			'title' => 'Foo.js',
		], new JavaScriptContent( 'code;' ) ];

		yield 'CSS content' => [ 'code {}', [
			'text' => 'MediaWiki:Foo.css',
			'ns' => NS_MEDIAWIKI,
			'title' => 'Foo.css',
		], new CssContent( 'code {}' ) ];

		yield 'Wikitext content' => [ null, [
			'text' => 'MediaWiki:Foo',
			'ns' => NS_MEDIAWIKI,
			'title' => 'Foo',
		], new WikitextContent( 'code;' ) ];
	}

	/**
	 * @dataProvider provideGetContent
	 */
	public function testGetContent( $expected, $title, Content $contentObj = null ) {
		$context = $this->getResourceLoaderContext( [], new EmptyResourceLoader );
		$module = $this->getMockBuilder( ResourceLoaderWikiModule::class )
			->setMethods( [ 'getContentObj' ] )->getMock();
		$module->method( 'getContentObj' )
			->willReturn( $contentObj );

		if ( is_array( $title ) ) {
			$title += [ 'ns' => NS_MAIN, 'id' => 1, 'len' => 1, 'redirect' => 0 ];
			$titleText = $title['text'];
			// Mock Title db access via LinkCache
			MediaWikiServices::getInstance()->getLinkCache()->addGoodLinkObj(
				$title['id'],
				new TitleValue( $title['ns'], $title['title'] ),
				$title['len'],
				$title['redirect']
			);
		} else {
			$titleText = $title;
		}

		$module = TestingAccessWrapper::newFromObject( $module );
		$this->assertSame(
			$expected,
			$module->getContent( $titleText, $context )
		);
	}

	public function testContentOverrides() {
		$pages = [
			'MediaWiki:Common.css' => [ 'type' => 'style' ],
		];

		$module = $this->getMockBuilder( ResourceLoaderWikiModule::class )
			->setMethods( [ 'getPages' ] )
			->getMock();
		$module->method( 'getPages' )->willReturn( $pages );

		$rl = new EmptyResourceLoader();
		$context = new DerivativeResourceLoaderContext(
			new ResourceLoaderContext( $rl, new FauxRequest() )
		);
		$context->setContentOverrideCallback( function ( Title $t ) {
			if ( $t->getPrefixedText() === 'MediaWiki:Common.css' ) {
				return new CssContent( '.override{}' );
			}
			return null;
		} );

		$this->assertTrue( $module->shouldEmbedModule( $context ) );
		$this->assertSame( [
			'all' => [
				"/*\nMediaWiki:Common.css\n*/\n.override{}"
			]
		], $module->getStyles( $context ) );

		$context->setContentOverrideCallback( function ( Title $t ) {
			if ( $t->getPrefixedText() === 'MediaWiki:Skin.css' ) {
				return new CssContent( '.override{}' );
			}
			return null;
		} );
		$this->assertFalse( $module->shouldEmbedModule( $context ) );
	}

	public function testGetContentForRedirects() {
		// Set up context and module object
		$context = new DerivativeResourceLoaderContext(
			$this->getResourceLoaderContext( [], new EmptyResourceLoader )
		);
		$module = $this->getMockBuilder( ResourceLoaderWikiModule::class )
			->setMethods( [ 'getPages' ] )
			->getMock();
		$module->method( 'getPages' )
			->willReturn( [
				'MediaWiki:Redirect.js' => [ 'type' => 'script' ]
			] );
		$context->setContentOverrideCallback( function ( Title $title ) {
			if ( $title->getPrefixedText() === 'MediaWiki:Redirect.js' ) {
				$handler = new JavaScriptContentHandler();
				return $handler->makeRedirectContent(
					Title::makeTitle( NS_MEDIAWIKI, 'Target.js' )
				);
			} elseif ( $title->getPrefixedText() === 'MediaWiki:Target.js' ) {
				return new JavaScriptContent( 'target;' );
			} else {
				return null;
			}
		} );

		// Mock away Title's db queries with LinkCache
		MediaWikiServices::getInstance()->getLinkCache()->addGoodLinkObj(
			1, // id
			new TitleValue( NS_MEDIAWIKI, 'Redirect.js' ),
			1, // len
			1 // redirect
		);

		$this->assertSame(
			"/*\nMediaWiki:Redirect.js\n*/\ntarget;\n",
			$module->getScript( $context ),
			'Redirect resolved by getContent'
		);
	}

	public function tearDown() {
		Title::clearCaches();
		parent::tearDown();
	}
}

class TestResourceLoaderWikiModule extends ResourceLoaderWikiModule {
	public static $returnFetchTitleInfo = null;

	protected static function fetchTitleInfo( IDatabase $db, array $pages, $fname = null ) {
		$ret = self::$returnFetchTitleInfo;
		self::$returnFetchTitleInfo = null;
		return $ret;
	}
}
