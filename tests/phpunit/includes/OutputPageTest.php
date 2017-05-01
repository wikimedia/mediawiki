<?php

/**
 *
 * @author Matthew Flaschen
 *
 * @group Output
 *
 * @todo factor tests in this class into providers and test methods
 *
 */
class OutputPageTest extends MediaWikiTestCase {
	const SCREEN_MEDIA_QUERY = 'screen and (min-width: 982px)';
	const SCREEN_ONLY_MEDIA_QUERY = 'only screen and (min-width: 982px)';

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
	function testHaveCacheVaryCookies() {
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
