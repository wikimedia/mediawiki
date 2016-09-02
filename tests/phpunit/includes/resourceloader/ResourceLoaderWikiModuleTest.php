<?php

class ResourceLoaderWikiModuleTest extends ResourceLoaderTestCase {

	/**
	 * @covers ResourceLoaderWikiModule::__construct
	 * @dataProvider provideConstructor
	 */
	public function testConstructor( $params ) {
		$module = new ResourceLoaderWikiModule( $params );
		$this->assertInstanceOf( 'ResourceLoaderWikiModule', $module );
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
	public function testIsKnownEmpty( $titleInfo, $group, $expected ) {
		$module = $this->getMockBuilder( 'ResourceLoaderWikiModule' )
			->setMethods( [ 'getTitleInfo', 'getGroup' ] )
			->getMock();
		$module->expects( $this->any() )
			->method( 'getTitleInfo' )
			->will( $this->returnValue( $titleInfo ) );
		$module->expects( $this->any() )
			->method( 'getGroup' )
			->will( $this->returnValue( $group ) );
		$context = $this->getMockBuilder( 'ResourceLoaderContext' )
			->disableOriginalConstructor()
			->getMock();
		$this->assertEquals( $expected, $module->isKnownEmpty( $context ) );
	}

	public static function provideIsKnownEmpty() {
		return [
			// No valid pages
			[ [], 'test1', true ],
			// 'site' module with a non-empty page
			[
				[ 'MediaWiki:Common.js' => [ 'page_len' => 1234 ] ],
				'site',
				false,
			],
			// 'site' module with an empty page
			[
				[ 'MediaWiki:Foo.js' => [ 'page_len' => 0 ] ],
				'site',
				false,
			],
			// 'user' module with a non-empty page
			[
				[ 'User:Example/common.js' => [ 'page_len' => 25 ] ],
				'user',
				false,
			],
			// 'user' module with an empty page
			[
				[ 'User:Example/foo.js' => [ 'page_len' => 0 ] ],
				'user',
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
		$titleInfo = [
			'MediaWiki:Common.css' => [ 'page_len' => 1234 ],
			'MediaWiki:Fallback.css' => [ 'page_len' => 0 ],
		];
		$expected = $titleInfo;

		$module = $this->getMockBuilder( 'TestResourceLoaderWikiModule' )
			->setMethods( [ 'getPages' ] )
			->getMock();
		$module->method( 'getPages' )->willReturn( $pages );
		// Can't mock static methods
		$module::$returnFetchTitleInfo = $titleInfo;

		$context = $this->getMockBuilder( 'ResourceLoaderContext' )
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
		$titleInfo = [
			'MediaWiki:Common.css' => [ 'page_len' => 1234 ],
			'MediaWiki:Fallback.css' => [ 'page_len' => 0 ],
		];
		$expected = $titleInfo;

		$module = $this->getMockBuilder( 'TestResourceLoaderWikiModule' )
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
}

class TestResourceLoaderWikiModule extends ResourceLoaderWikiModule {
	public static $returnFetchTitleInfo = null;
	protected static function fetchTitleInfo( IDatabase $db, array $pages, $fname = null ) {
		$ret = self::$returnFetchTitleInfo;
		self::$returnFetchTitleInfo = null;
		return $ret;
	}
}
