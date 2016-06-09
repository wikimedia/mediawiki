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
				[ 'MediaWiki:Common.js' => [ 'rev_sha1' => 'dmh6qn', 'rev_len' => 1234 ] ],
				'site',
				false,
			],
			// 'site' module with an empty page
			[
				[ 'MediaWiki:Foo.js' => [ 'rev_sha1' => 'phoi', 'rev_len' => 0 ] ],
				'site',
				false,
			],
			// 'user' module with a non-empty page
			[
				[ 'User:Example/common.js' => [ 'rev_sha1' => 'j7ssba', 'rev_len' => 25 ] ],
				'user',
				false,
			],
			// 'user' module with an empty page
			[
				[ 'User:Example/foo.js' => [ 'rev_sha1' => 'phoi', 'rev_len' => 0 ] ],
				'user',
				true,
			],
		];
	}
}
