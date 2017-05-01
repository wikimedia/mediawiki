<?php

use Wikimedia\TestingAccessWrapper;

/**
 *
 * @author Matthew Flaschen
 *
 * @group Database
 * @group Output
 *
 * @todo factor tests in this class into providers and test methods
 */
class OutputPageTest extends MediaWikiTestCase {
	const SCREEN_MEDIA_QUERY = 'screen and (min-width: 982px)';
	const SCREEN_ONLY_MEDIA_QUERY = 'only screen and (min-width: 982px)';

	/**
	 * @covers OutputPage::addMeta
	 * @covers OutputPage::getMetaTags
	 * @covers OutputPage::getHeadLinksArray
	 */
	public function testMetaTags() {
		$outputPage = $this->newInstance();
		$outputPage->addMeta( 'http:expires', '0' );
		$outputPage->addMeta( 'keywords', 'first' );
		$outputPage->addMeta( 'keywords', 'second' );
		$outputPage->addMeta( 'og:title', 'Ta-duh' );

		$expected = [
			[ 'http:expires', '0' ],
			[ 'keywords', 'first' ],
			[ 'keywords', 'second' ],
			[ 'og:title', 'Ta-duh' ],
		];
		$this->assertSame( $expected, $outputPage->getMetaTags() );

		$links = $outputPage->getHeadLinksArray();
		$this->assertContains( '<meta http-equiv="expires" content="0"/>', $links );
		$this->assertContains( '<meta name="keywords" content="first"/>', $links );
		$this->assertContains( '<meta name="keywords" content="second"/>', $links );
		$this->assertContains( '<meta property="og:title" content="Ta-duh"/>', $links );
		$this->assertArrayNotHasKey( 'meta-robots', $links );
	}

	/**
	 * @covers OutputPage::setIndexPolicy
	 * @covers OutputPage::setFollowPolicy
	 * @covers OutputPage::getHeadLinksArray
	 */
	public function testRobotsPolicies() {
		$outputPage = $this->newInstance();
		$outputPage->setIndexPolicy( 'noindex' );
		$outputPage->setFollowPolicy( 'nofollow' );

		$links = $outputPage->getHeadLinksArray();
		$this->assertContains( '<meta name="robots" content="noindex,nofollow"/>', $links );
	}

	/**
	 * Tests a particular case of transformCssMedia, using the given input, globals,
	 * expected return, and message
	 *
	 * Asserts that $expectedReturn is returned.
	 *
	 * options['printableQuery'] - value of query string for printable, or omitted for none
	 * options['handheldQuery'] - value of query string for handheld, or omitted for none
	 * options['media'] - passed into the method under the same name
	 * options['expectedReturn'] - expected return value
	 * options['message'] - PHPUnit message for assertion
	 *
	 * @param array $args Key-value array of arguments as shown above
	 */
	protected function assertTransformCssMediaCase( $args ) {
		$queryData = [];
		if ( isset( $args['printableQuery'] ) ) {
			$queryData['printable'] = $args['printableQuery'];
		}

		if ( isset( $args['handheldQuery'] ) ) {
			$queryData['handheld'] = $args['handheldQuery'];
		}

		$fauxRequest = new FauxRequest( $queryData, false );
		$this->setMwGlobals( [
			'wgRequest' => $fauxRequest,
		] );

		$actualReturn = OutputPage::transformCssMedia( $args['media'] );
		$this->assertSame( $args['expectedReturn'], $actualReturn, $args['message'] );
	}

	/**
	 * Tests print requests
	 * @covers OutputPage::transformCssMedia
	 */
	public function testPrintRequests() {
		$this->assertTransformCssMediaCase( [
			'printableQuery' => '1',
			'media' => 'screen',
			'expectedReturn' => null,
			'message' => 'On printable request, screen returns null'
		] );

		$this->assertTransformCssMediaCase( [
			'printableQuery' => '1',
			'media' => self::SCREEN_MEDIA_QUERY,
			'expectedReturn' => null,
			'message' => 'On printable request, screen media query returns null'
		] );

		$this->assertTransformCssMediaCase( [
			'printableQuery' => '1',
			'media' => self::SCREEN_ONLY_MEDIA_QUERY,
			'expectedReturn' => null,
			'message' => 'On printable request, screen media query with only returns null'
		] );

		$this->assertTransformCssMediaCase( [
			'printableQuery' => '1',
			'media' => 'print',
			'expectedReturn' => '',
			'message' => 'On printable request, media print returns empty string'
		] );
	}

	/**
	 * Tests screen requests, without either query parameter set
	 * @covers OutputPage::transformCssMedia
	 */
	public function testScreenRequests() {
		$this->assertTransformCssMediaCase( [
			'media' => 'screen',
			'expectedReturn' => 'screen',
			'message' => 'On screen request, screen media type is preserved'
		] );

		$this->assertTransformCssMediaCase( [
			'media' => 'handheld',
			'expectedReturn' => 'handheld',
			'message' => 'On screen request, handheld media type is preserved'
		] );

		$this->assertTransformCssMediaCase( [
			'media' => self::SCREEN_MEDIA_QUERY,
			'expectedReturn' => self::SCREEN_MEDIA_QUERY,
			'message' => 'On screen request, screen media query is preserved.'
		] );

		$this->assertTransformCssMediaCase( [
			'media' => self::SCREEN_ONLY_MEDIA_QUERY,
			'expectedReturn' => self::SCREEN_ONLY_MEDIA_QUERY,
			'message' => 'On screen request, screen media query with only is preserved.'
		] );

		$this->assertTransformCssMediaCase( [
			'media' => 'print',
			'expectedReturn' => 'print',
			'message' => 'On screen request, print media type is preserved'
		] );
	}

	/**
	 * Tests handheld behavior
	 * @covers OutputPage::transformCssMedia
	 */
	public function testHandheld() {
		$this->assertTransformCssMediaCase( [
			'handheldQuery' => '1',
			'media' => 'handheld',
			'expectedReturn' => '',
			'message' => 'On request with handheld querystring and media is handheld, returns empty string'
		] );

		$this->assertTransformCssMediaCase( [
			'handheldQuery' => '1',
			'media' => 'screen',
			'expectedReturn' => null,
			'message' => 'On request with handheld querystring and media is screen, returns null'
		] );
	}

	public static function provideTransformFilePath() {
		$baseDir = dirname( __DIR__ ) . '/data/media';
		return [
			// File that matches basePath, and exists. Hash found and appended.
			[
				'baseDir' => $baseDir, 'basePath' => '/w',
				'/w/test.jpg',
				'/w/test.jpg?edcf2'
			],
			// File that matches basePath, but not found on disk. Empty query.
			[
				'baseDir' => $baseDir, 'basePath' => '/w',
				'/w/unknown.png',
				'/w/unknown.png?'
			],
			// File not matching basePath. Ignored.
			[
				'baseDir' => $baseDir, 'basePath' => '/w',
				'/files/test.jpg'
			],
			// Empty string. Ignored.
			[
				'baseDir' => $baseDir, 'basePath' => '/w',
				'',
				''
			],
			// Similar path, but with domain component. Ignored.
			[
				'baseDir' => $baseDir, 'basePath' => '/w',
				'//example.org/w/test.jpg'
			],
			[
				'baseDir' => $baseDir, 'basePath' => '/w',
				'https://example.org/w/test.jpg'
			],
			// Unrelated path with domain component. Ignored.
			[
				'baseDir' => $baseDir, 'basePath' => '/w',
				'https://example.org/files/test.jpg'
			],
			[
				'baseDir' => $baseDir, 'basePath' => '/w',
				'//example.org/files/test.jpg'
			],
			// Unrelated path with domain, and empty base path (root mw install). Ignored.
			[
				'baseDir' => $baseDir, 'basePath' => '',
				'https://example.org/files/test.jpg'
			],
			[
				'baseDir' => $baseDir, 'basePath' => '',
				// T155310
				'//example.org/files/test.jpg'
			],
			// Check UploadPath before ResourceBasePath (T155146)
			[
				'baseDir' => dirname( $baseDir ), 'basePath' => '',
				'uploadDir' => $baseDir, 'uploadPath' => '/images',
				'/images/test.jpg',
				'/images/test.jpg?edcf2'
			],
		];
	}

	/**
	 * @dataProvider provideTransformFilePath
	 * @covers OutputPage::transformFilePath
	 * @covers OutputPage::transformResourcePath
	 */
	public function testTransformResourcePath( $baseDir, $basePath, $uploadDir = null,
		$uploadPath = null, $path = null, $expected = null
	) {
		if ( $path === null ) {
			// Skip optional $uploadDir and $uploadPath
			$path = $uploadDir;
			$expected = $uploadPath;
			$uploadDir = "$baseDir/images";
			$uploadPath = "$basePath/images";
		}
		$this->setMwGlobals( 'IP', $baseDir );
		$conf = new HashConfig( [
			'ResourceBasePath' => $basePath,
			'UploadDirectory' => $uploadDir,
			'UploadPath' => $uploadPath,
		] );

		MediaWiki\suppressWarnings();
		$actual = OutputPage::transformResourcePath( $conf, $path );
		MediaWiki\restoreWarnings();

		$this->assertEquals( $expected ?: $path, $actual );
	}

	public static function provideMakeResourceLoaderLink() {
		// @codingStandardsIgnoreStart Generic.Files.LineLength
		return [
			// Single only=scripts load
			[
				[ 'test.foo', ResourceLoaderModule::TYPE_SCRIPTS ],
				"<script>(window.RLQ=window.RLQ||[]).push(function(){"
					. 'mw.loader.load("http://127.0.0.1:8080/w/load.php?debug=false\u0026lang=en\u0026modules=test.foo\u0026only=scripts\u0026skin=fallback");'
					. "});</script>"
			],
			// Multiple only=styles load
			[
				[ [ 'test.baz', 'test.foo', 'test.bar' ], ResourceLoaderModule::TYPE_STYLES ],

				'<link rel="stylesheet" href="http://127.0.0.1:8080/w/load.php?debug=false&amp;lang=en&amp;modules=test.bar%2Cbaz%2Cfoo&amp;only=styles&amp;skin=fallback"/>'
			],
			// Private embed (only=scripts)
			[
				[ 'test.quux', ResourceLoaderModule::TYPE_SCRIPTS ],
				"<script>(window.RLQ=window.RLQ||[]).push(function(){"
					. "mw.test.baz({token:123});mw.loader.state({\"test.quux\":\"ready\"});"
					. "});</script>"
			],
		];
		// @codingStandardsIgnoreEnd
	}

	/**
	 * See ResourceLoaderClientHtmlTest for full coverage.
	 *
	 * @dataProvider provideMakeResourceLoaderLink
	 * @covers OutputPage::makeResourceLoaderLink
	 */
	public function testMakeResourceLoaderLink( $args, $expectedHtml ) {
		$this->setMwGlobals( [
			'wgResourceLoaderDebug' => false,
			'wgLoadScript' => 'http://127.0.0.1:8080/w/load.php',
		] );
		$class = new ReflectionClass( 'OutputPage' );
		$method = $class->getMethod( 'makeResourceLoaderLink' );
		$method->setAccessible( true );
		$ctx = new RequestContext();
		$ctx->setSkin( SkinFactory::getDefaultInstance()->makeSkin( 'fallback' ) );
		$ctx->setLanguage( 'en' );
		$out = new OutputPage( $ctx );
		$rl = $out->getResourceLoader();
		$rl->setMessageBlobStore( new NullMessageBlobStore() );
		$rl->register( [
			'test.foo' => new ResourceLoaderTestModule( [
				'script' => 'mw.test.foo( { a: true } );',
				'styles' => '.mw-test-foo { content: "style"; }',
			] ),
			'test.bar' => new ResourceLoaderTestModule( [
				'script' => 'mw.test.bar( { a: true } );',
				'styles' => '.mw-test-bar { content: "style"; }',
			] ),
			'test.baz' => new ResourceLoaderTestModule( [
				'script' => 'mw.test.baz( { a: true } );',
				'styles' => '.mw-test-baz { content: "style"; }',
			] ),
			'test.quux' => new ResourceLoaderTestModule( [
				'script' => 'mw.test.baz( { token: 123 } );',
				'styles' => '/* pref-animate=off */ .mw-icon { transition: none; }',
				'group' => 'private',
			] ),
		] );
		$links = $method->invokeArgs( $out, $args );
		$actualHtml = strval( $links );
		$this->assertEquals( $expectedHtml, $actualHtml );
	}

	/**
	 * @dataProvider provideVaryHeaders
	 * @covers OutputPage::addVaryHeader
	 * @covers OutputPage::getVaryHeader
	 * @covers OutputPage::getKeyHeader
	 */
	public function testVaryHeaders( $calls, $vary, $key ) {
		// get rid of default Vary fields
		$outputPage = $this->getMockBuilder( 'OutputPage' )
			->setConstructorArgs( [ new RequestContext() ] )
			->setMethods( [ 'getCacheVaryCookies' ] )
			->getMock();
		$outputPage->expects( $this->any() )
			->method( 'getCacheVaryCookies' )
			->will( $this->returnValue( [] ) );
		TestingAccessWrapper::newFromObject( $outputPage )->mVaryHeader = [];

		foreach ( $calls as $call ) {
			call_user_func_array( [ $outputPage, 'addVaryHeader' ], $call );
		}
		$this->assertEquals( $vary, $outputPage->getVaryHeader(), 'Vary:' );
		$this->assertEquals( $key, $outputPage->getKeyHeader(), 'Key:' );
	}

	public function provideVaryHeaders() {
		// note: getKeyHeader() automatically adds Vary: Cookie
		return [
			[ // single header
				[
					[ 'Cookie' ],
				],
				'Vary: Cookie',
				'Key: Cookie',
			],
			[ // non-unique headers
				[
					[ 'Cookie' ],
					[ 'Accept-Language' ],
					[ 'Cookie' ],
				],
				'Vary: Cookie, Accept-Language',
				'Key: Cookie,Accept-Language',
			],
			[ // two headers with single options
				[
					[ 'Cookie', [ 'param=phpsessid' ] ],
					[ 'Accept-Language', [ 'substr=en' ] ],
				],
				'Vary: Cookie, Accept-Language',
				'Key: Cookie;param=phpsessid,Accept-Language;substr=en',
			],
			[ // one header with multiple options
				[
					[ 'Cookie', [ 'param=phpsessid', 'param=userId' ] ],
				],
				'Vary: Cookie',
				'Key: Cookie;param=phpsessid;param=userId',
			],
			[ // Duplicate option
				[
					[ 'Cookie', [ 'param=phpsessid' ] ],
					[ 'Cookie', [ 'param=phpsessid' ] ],
					[ 'Accept-Language', [ 'substr=en', 'substr=en' ] ],
				],
				'Vary: Cookie, Accept-Language',
				'Key: Cookie;param=phpsessid,Accept-Language;substr=en',
			],
			[ // Same header, different options
				[
					[ 'Cookie', [ 'param=phpsessid' ] ],
					[ 'Cookie', [ 'param=userId' ] ],
				],
				'Vary: Cookie',
				'Key: Cookie;param=phpsessid;param=userId',
			],
		];
	}

	/**
	 * @covers OutputPage::haveCacheVaryCookies
	 */
	public function testHaveCacheVaryCookies() {
		$request = new FauxRequest();
		$context = new RequestContext();
		$context->setRequest( $request );
		$outputPage = new OutputPage( $context );

		// No cookies are set.
		$this->assertFalse( $outputPage->haveCacheVaryCookies() );

		// 'Token' is present but empty, so it shouldn't count.
		$request->setCookie( 'Token', '' );
		$this->assertFalse( $outputPage->haveCacheVaryCookies() );

		// 'Token' present and nonempty.
		$request->setCookie( 'Token', '123' );
		$this->assertTrue( $outputPage->haveCacheVaryCookies() );
	}

	/*
	 * @covers OutputPage::addCategoryLinks
	 * @covers OutputPage::getCategories
	 */
	public function testGetCategories() {
		$fakeResultWrapper = new FakeResultWrapper( [
			(object) [
				'pp_value' => 1,
				'page_title' => 'Test'
			],
			(object) [
				'page_title' => 'Test2'
			]
		] );
		$outputPage = $this->getMockBuilder( 'OutputPage' )
			->setConstructorArgs( [ new RequestContext() ] )
			->setMethods( [ 'addCategoryLinksToLBAndGetResult' ] )
			->getMock();
		$outputPage->expects( $this->any() )
			->method( 'addCategoryLinksToLBAndGetResult' )
			->will( $this->returnValue( $fakeResultWrapper ) );

		$outputPage->addCategoryLinks( [
			'Test' => 'Test',
			'Test2' => 'Test2',
		] );
		$this->assertEquals( [ 0 => 'Test', '1' => 'Test2' ], $outputPage->getCategories() );
		$this->assertEquals( [ 0 => 'Test2' ], $outputPage->getCategories( 'normal' ) );
		$this->assertEquals( [ 0 => 'Test' ], $outputPage->getCategories( 'hidden' ) );
	}

	/**
	 * @dataProvider provideLinkHeaders
	 * @covers OutputPage::addLinkHeader
	 * @covers OutputPage::getLinkHeader
	 */
	public function testLinkHeaders( $headers, $result ) {
		$outputPage = $this->newInstance();

		foreach ( $headers as $header ) {
			$outputPage->addLinkHeader( $header );
		}

		$this->assertEquals( $result, $outputPage->getLinkHeader() );
	}

	public function provideLinkHeaders() {
		return [
			[
				[],
				false
			],
			[
				[ '<https://foo/bar.jpg>;rel=preload;as=image' ],
				'Link: <https://foo/bar.jpg>;rel=preload;as=image',
			],
			[
				[ '<https://foo/bar.jpg>;rel=preload;as=image','<https://foo/baz.jpg>;rel=preload;as=image' ],
				'Link: <https://foo/bar.jpg>;rel=preload;as=image,<https://foo/baz.jpg>;rel=preload;as=image',
			],
		];
	}

	/**
	 * @dataProvider providePreloadLinkHeaders
	 * @covers OutputPage::addLogoPreloadLinkHeaders
	 * @covers ResourceLoaderSkinModule::getLogo
	 */
	public function testPreloadLinkHeaders( $config, $result, $baseDir = null ) {
		if ( $baseDir ) {
			$this->setMwGlobals( 'IP', $baseDir );
		}
		$out = TestingAccessWrapper::newFromObject( $this->newInstance( $config ) );
		$out->addLogoPreloadLinkHeaders();

		$this->assertEquals( $result, $out->getLinkHeader() );
	}

	public function providePreloadLinkHeaders() {
		return [
			[
				[
					'ResourceBasePath' => '/w',
					'Logo' => '/img/default.png',
					'LogoHD' => [
						'1.5x' => '/img/one-point-five.png',
						'2x' => '/img/two-x.png',
					],
				],
				'Link: </img/default.png>;rel=preload;as=image;media=' .
				'not all and (min-resolution: 1.5dppx),' .
				'</img/one-point-five.png>;rel=preload;as=image;media=' .
				'(min-resolution: 1.5dppx) and (max-resolution: 1.999999dppx),' .
				'</img/two-x.png>;rel=preload;as=image;media=(min-resolution: 2dppx)'
			],
			[
				[
					'ResourceBasePath' => '/w',
					'Logo' => '/img/default.png',
					'LogoHD' => false,
				],
				'Link: </img/default.png>;rel=preload;as=image'
			],
			[
				[
					'ResourceBasePath' => '/w',
					'Logo' => '/img/default.png',
					'LogoHD' => [
						'2x' => '/img/two-x.png',
					],
				],
				'Link: </img/default.png>;rel=preload;as=image;media=' .
				'not all and (min-resolution: 2dppx),' .
				'</img/two-x.png>;rel=preload;as=image;media=(min-resolution: 2dppx)'
			],
			[
				[
					'ResourceBasePath' => '/w',
					'Logo' => '/w/test.jpg',
					'LogoHD' => false,
					'UploadPath' => '/w/images',
				],
				'Link: </w/test.jpg?edcf2>;rel=preload;as=image',
				'baseDir' => dirname( __DIR__ ) . '/data/media',
			],
		];
	}

	/**
	 * @return OutputPage
	 */
	private function newInstance( $config = [] ) {
		$context = new RequestContext();

		$context->setConfig( new HashConfig( $config + [
			'AppleTouchIcon' => false,
			'DisableLangConversion' => true,
			'EnableAPI' => false,
			'EnableCanonicalServerLink' => false,
			'Favicon' => false,
			'Feed' => false,
			'LanguageCode' => false,
			'ReferrerPolicy' => false,
			'RightsPage' => false,
			'RightsUrl' => false,
			'UniversalEditButton' => false,
		] ) );

		return new OutputPage( $context );
	}
}

/**
 * MessageBlobStore that doesn't do anything
 */
class NullMessageBlobStore extends MessageBlobStore {
	public function get( ResourceLoader $resourceLoader, $modules, $lang ) {
		return [];
	}

	public function insertMessageBlob( $name, ResourceLoaderModule $module, $lang ) {
		return false;
	}

	public function updateModule( $name, ResourceLoaderModule $module, $lang ) {
	}

	public function updateMessage( $key ) {
	}

	public function clear() {
	}
}
