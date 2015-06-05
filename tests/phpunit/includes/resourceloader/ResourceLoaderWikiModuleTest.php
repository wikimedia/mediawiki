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
		return array(
			// Nothing
			array( null ),
			array( array() ),
			// Unrecognized settings
			array( array( 'foo' => 'baz' ) ),
			// Real settings
			array( array( 'scripts' => array( 'MediaWiki:Common.js' ) ) ),
		);
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
		$settings = self::getSettings() + array(
			'UseSiteJs' => true,
			'UseSiteCss' => true,
		);

		$params = array(
			'styles' => array( 'MediaWiki:Common.css' ),
			'scripts' => array( 'MediaWiki:Common.js' ),
		);

		return array(
			array( array(), new HashConfig( $settings ), array() ),
			array( $params, new HashConfig( $settings ), array(
				'MediaWiki:Common.js' => array( 'type' => 'script' ),
				'MediaWiki:Common.css' => array( 'type' => 'style' )
			) ),
			array( $params, new HashConfig( array( 'UseSiteCss' => false ) + $settings ), array(
				'MediaWiki:Common.js' => array( 'type' => 'script' ),
			) ),
			array( $params, new HashConfig( array( 'UseSiteJs' => false ) + $settings ), array(
				'MediaWiki:Common.css' => array( 'type' => 'style' ),
			) ),
			array( $params, new HashConfig( array( 'UseSiteJs' => false, 'UseSiteCss' => false ) ), array() ),
		);
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
		return array(
			// No group specified
			array( array(), null ),
			// A random group
			array( array( 'group' => 'foobar' ), 'foobar' ),
		);
	}

	/**
	 * @covers ResourceLoaderWikiModule::isKnownEmpty
	 * @dataProvider provideIsKnownEmpty
	 */
	public function testIsKnownEmpty( $titleInfo, $group, $expected ) {
		$module = $this->getMockBuilder( 'ResourceLoaderWikiModule' )
			->setMethods( array( 'getTitleInfo', 'getGroup' ) )
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
		return array(
			// No valid pages
			array( array(), 'test1', true ),
			// 'site' module with a non-empty page
			array(
				array( 'MediaWiki:Common.js' => array( 'rev_sha1' => 'dmh6qn', 'rev_len' => 1234 ) ),
				'site',
				false,
			),
			// 'site' module with an empty page
			array(
				array( 'MediaWiki:Foo.js' => array( 'rev_sha1' => 'phoi', 'rev_len' => 0 ) ),
				'site',
				false,
			),
			// 'user' module with a non-empty page
			array(
				array( 'User:Example/common.js' => array( 'rev_sha1' => 'j7ssba', 'rev_len' => 25 ) ),
				'user',
				false,
			),
			// 'user' module with an empty page
			array(
				array( 'User:Example/foo.js' => array( 'rev_sha1' => 'phoi', 'rev_len' => 0 ) ),
				'user',
				true,
			),
		);
	}
}
