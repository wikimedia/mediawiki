<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\TestingAccessWrapper;

class ResourceLoaderWikiModuleTest extends ResourceLoaderTestCase {

	/**
	 * @covers ResourceLoaderWikiModule::__construct
	 * @dataProvider provideConstructor
	 */
	public function testConstructor( $params ) {
		$module = new ResourceLoaderWikiModule( $params );
		$this->assertInstanceOf( ResourceLoaderWikiModule::class, $module );
	}

	private function prepareTitleInfo( array $mockInfo ) {
		$module = TestingAccessWrapper::newFromClass( ResourceLoaderWikiModule::class );
		$info = [];
		foreach ( $mockInfo as $key => $val ) {
			$info[ $module->makeTitleKey( Title::newFromText( $key ) ) ] = $val;
		}
		return $info;
	}

	public static function provideConstructor() {
		return [
			// Nothing
			[ null ],
			[ [] ],
			// Unrecognized settings
			[ [ 'foo' => 'baz' ] ],
			// Real settings
			[ [ 'scripts' => [ 'MediaWiki:Common.js' ] ] ],
		];
	}

	/**
	 * @dataProvider provideGetPages
	 * @covers ResourceLoaderWikiModule::getPages
	 */
	public function testGetPages( $params, Config $config, $expected ) {
		$module = new ResourceLoaderWikiModule( $params );
		$module->setConfig( $config );

		// Because getPages is protected..
		$getPages = new ReflectionMethod( $module, 'getPages' );
		$getPages->setAccessible( true );
		$out = $getPages->invoke( $module, ResourceLoaderContext::newDummyContext() );
		$this->assertEquals( $expected, $out );
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
	 * @covers ResourceLoaderWikiModule::getGroup
	 * @dataProvider provideGetGroup
	 */
	public function testGetGroup( $params, $expected ) {
		$module = new ResourceLoaderWikiModule( $params );
		$this->assertEquals( $expected, $module->getGroup() );
	}

	public static function provideGetGroup() {
		return [
			// No group specified
			[ [], null ],
			// A random group
			[ [ 'group' => 'foobar' ], 'foobar' ],
		];
	}

	/**
	 * @covers ResourceLoaderWikiModule::isKnownEmpty
	 * @dataProvider provideIsKnownEmpty
	 */
	public function testIsKnownEmpty( $titleInfo, $group, $dependencies, $expected ) {
		$module = $this->getMockBuilder( ResourceLoaderWikiModule::class )
			->setMethods( [ 'getTitleInfo', 'getGroup', 'getDependencies' ] )
			->getMock();
		$module->expects( $this->any() )
			->method( 'getTitleInfo' )
			->will( $this->returnValue( $this->prepareTitleInfo( $titleInfo ) ) );
		$module->expects( $this->any() )
			->method( 'getGroup' )
			->will( $this->returnValue( $group ) );
		$module->expects( $this->any() )
			->method( 'getDependencies' )
			->will( $this->returnValue( $dependencies ) );
		$context = $this->getMockBuilder( ResourceLoaderContext::class )
			->disableOriginalConstructor()
			->getMock();
		$this->assertEquals( $expected, $module->isKnownEmpty( $context ) );
	}

	public static function provideIsKnownEmpty() {
		return [
			// No valid pages
			[ [], 'test1', [], true ],
			// 'site' module with a non-empty page
			[
				[ 'MediaWiki:Common.js' => [ 'page_len' => 1234 ] ],
				'site',
				[],
				false,
			],
			// 'site' module without existing pages but dependencies
			[
				[],
				'site',
				[ 'mobile.css' ],
				false,
			],
			// 'site' module which is empty but has dependencies
			[
				[ 'MediaWiki:Common.js' => [ 'page_len' => 0 ] ],
				'site',
				[ 'mobile.css' ],
				false,
			],
			// 'site' module with an empty page
			[
				[ 'MediaWiki:Foo.js' => [ 'page_len' => 0 ] ],
				'site',
				[],
				false,
			],
			// 'user' module with a non-empty page
			[
				[ 'User:Example/common.js' => [ 'page_len' => 25 ] ],
				'user',
				[],
				false,
			],
			// 'user' module with an empty page
			[
				[ 'User:Example/foo.js' => [ 'page_len' => 0 ] ],
				'user',
				[],
				true,
			],
		];
	}

	/**
	 * @covers ResourceLoaderWikiModule::getTitleInfo
	 */
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

		$module = $this->getMockBuilder( TestResourceLoaderWikiModule::class )
			->setMethods( [ 'getPages' ] )
			->getMock();
		$module->method( 'getPages' )->willReturn( $pages );
		// Can't mock static methods
		$module::$returnFetchTitleInfo = $titleInfo;

		$context = $this->getMockBuilder( ResourceLoaderContext::class )
			->disableOriginalConstructor()
			->getMock();

		$module = TestingAccessWrapper::newFromObject( $module );
		$this->assertEquals( $expected, $module->getTitleInfo( $context ), 'Title info' );
	}

	/**
	 * @covers ResourceLoaderWikiModule::getTitleInfo
	 * @covers ResourceLoaderWikiModule::setTitleInfo
	 * @covers ResourceLoaderWikiModule::preloadTitleInfo
	 */
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
		$rl->register( 'testmodule', $module );
		$context = new ResourceLoaderContext( $rl, new FauxRequest() );

		TestResourceLoaderWikiModule::invalidateModuleCache(
			Title::newFromText( 'MediaWiki:Common.css' ),
			null,
			null,
			wfWikiID()
		);
		TestResourceLoaderWikiModule::preloadTitleInfo(
			$context,
			wfGetDB( DB_REPLICA ),
			[ 'testmodule' ]
		);

		$module = TestingAccessWrapper::newFromObject( $module );
		$this->assertEquals( $expected, $module->getTitleInfo( $context ), 'Title info' );
	}

	/**
	 * @covers ResourceLoaderWikiModule::preloadTitleInfo
	 */
	public function testGetPreloadedBadTitle() {
		// Mock values
		$pages = [
			// Covers else branch for invalid page name
			'[x]' => [ 'type' => 'styles' ],
		];
		$titleInfo = [];

		// Set up objects
		$module = $this->getMockBuilder( TestResourceLoaderWikiModule::class )
			->setMethods( [ 'getPages' ] )->getMock();
		$module->method( 'getPages' )->willReturn( $pages );
		$module::$returnFetchTitleInfo = $titleInfo;
		$rl = new EmptyResourceLoader();
		$rl->register( 'testmodule', $module );
		$context = new ResourceLoaderContext( $rl, new FauxRequest() );

		// Act
		TestResourceLoaderWikiModule::preloadTitleInfo(
			$context,
			wfGetDB( DB_REPLICA ),
			[ 'testmodule' ]
		);

		// Assert
		$module = TestingAccessWrapper::newFromObject( $module );
		$this->assertEquals( $titleInfo, $module->getTitleInfo( $context ), 'Title info' );
	}

	/**
	 * @covers ResourceLoaderWikiModule::preloadTitleInfo
	 */
	public function testGetPreloadedTitleInfoEmpty() {
		$context = new ResourceLoaderContext( new EmptyResourceLoader(), new FauxRequest() );
		// Covers early return
		$this->assertSame(
			null,
			ResourceLoaderWikiModule::preloadTitleInfo(
				$context,
				wfGetDB( DB_REPLICA ),
				[]
			)
		);
	}

	public static function provideGetContent() {
		return [
			'Bad title' => [ null, '[x]' ],
			'Dead redirect' => [ null, [
				'text' => 'Dead redirect',
				'title' => 'Dead_redirect',
				'redirect' => 1,
			] ],
			'Bad content model' => [ null, [
				'text' => 'MediaWiki:Wikitext',
				'ns' => NS_MEDIAWIKI,
				'title' => 'Wikitext',
			] ],
			'No JS content found' => [ null, [
				'text' => 'MediaWiki:Script.js',
				'ns' => NS_MEDIAWIKI,
				'title' => 'Script.js',
			] ],
			'No CSS content found' => [ null, [
				'text' => 'MediaWiki:Styles.css',
				'ns' => NS_MEDIAWIKI,
				'title' => 'Script.css',
			] ],
		];
	}

	/**
	 * @covers ResourceLoaderWikiModule::getContent
	 * @dataProvider provideGetContent
	 */
	public function testGetContent( $expected, $title ) {
		$context = $this->getResourceLoaderContext( [], new EmptyResourceLoader );
		$module = $this->getMockBuilder( ResourceLoaderWikiModule::class )
			->setMethods( [ 'getContentObj' ] )->getMock();
		$module->expects( $this->any() )
			->method( 'getContentObj' )->willReturn( null );

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
		$this->assertEquals(
			$expected,
			$module->getContent( $titleText, $context )
		);
	}

	/**
	 * @covers ResourceLoaderWikiModule::getContent
	 * @covers ResourceLoaderWikiModule::getContentObj
	 * @covers ResourceLoaderWikiModule::shouldEmbedModule
	 */
	public function testContentOverrides() {
		$pages = [
			'MediaWiki:Common.css' => [ 'type' => 'style' ],
		];

		$module = $this->getMockBuilder( TestResourceLoaderWikiModule::class )
			->setMethods( [ 'getPages' ] )
			->getMock();
		$module->method( 'getPages' )->willReturn( $pages );

		$rl = new EmptyResourceLoader();
		$rl->register( 'testmodule', $module );
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
		$this->assertEquals( [
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

	/**
	 * @covers ResourceLoaderWikiModule::getContent
	 * @covers ResourceLoaderWikiModule::getContentObj
	 */
	public function testGetContentForRedirects() {
		// Set up context and module object
		$context = new DerivativeResourceLoaderContext(
			$this->getResourceLoaderContext( [], new EmptyResourceLoader )
		);
		$module = $this->getMockBuilder( ResourceLoaderWikiModule::class )
			->setMethods( [ 'getPages' ] )
			->getMock();
		$module->expects( $this->any() )
			->method( 'getPages' )
			->will( $this->returnValue( [
				'MediaWiki:Redirect.js' => [ 'type' => 'script' ]
			] ) );
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

		$this->assertEquals(
			"/*\nMediaWiki:Redirect.js\n*/\ntarget;\n",
			$module->getScript( $context ),
			'Redirect resolved by getContent'
		);
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
