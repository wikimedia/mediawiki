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
		$queryData = array();
		if ( isset( $args['printableQuery'] ) ) {
			$queryData['printable'] = $args['printableQuery'];
		}

		if ( isset( $args['handheldQuery'] ) ) {
			$queryData['handheld'] = $args['handheldQuery'];
		}

		$fauxRequest = new FauxRequest( $queryData, false );
		$this->setMwGlobals( array(
			'wgRequest' => $fauxRequest,
		) );

		$actualReturn = OutputPage::transformCssMedia( $args['media'] );
		$this->assertSame( $args['expectedReturn'], $actualReturn, $args['message'] );
	}

	/**
	 * Tests print requests
	 * @covers OutputPage::transformCssMedia
	 */
	public function testPrintRequests() {
		$this->assertTransformCssMediaCase( array(
			'printableQuery' => '1',
			'media' => 'screen',
			'expectedReturn' => null,
			'message' => 'On printable request, screen returns null'
		) );

		$this->assertTransformCssMediaCase( array(
			'printableQuery' => '1',
			'media' => self::SCREEN_MEDIA_QUERY,
			'expectedReturn' => null,
			'message' => 'On printable request, screen media query returns null'
		) );

		$this->assertTransformCssMediaCase( array(
			'printableQuery' => '1',
			'media' => self::SCREEN_ONLY_MEDIA_QUERY,
			'expectedReturn' => null,
			'message' => 'On printable request, screen media query with only returns null'
		) );

		$this->assertTransformCssMediaCase( array(
			'printableQuery' => '1',
			'media' => 'print',
			'expectedReturn' => '',
			'message' => 'On printable request, media print returns empty string'
		) );
	}

	/**
	 * Tests screen requests, without either query parameter set
	 * @covers OutputPage::transformCssMedia
	 */
	public function testScreenRequests() {
		$this->assertTransformCssMediaCase( array(
			'media' => 'screen',
			'expectedReturn' => 'screen',
			'message' => 'On screen request, screen media type is preserved'
		) );

		$this->assertTransformCssMediaCase( array(
			'media' => 'handheld',
			'expectedReturn' => 'handheld',
			'message' => 'On screen request, handheld media type is preserved'
		) );

		$this->assertTransformCssMediaCase( array(
			'media' => self::SCREEN_MEDIA_QUERY,
			'expectedReturn' => self::SCREEN_MEDIA_QUERY,
			'message' => 'On screen request, screen media query is preserved.'
		) );

		$this->assertTransformCssMediaCase( array(
			'media' => self::SCREEN_ONLY_MEDIA_QUERY,
			'expectedReturn' => self::SCREEN_ONLY_MEDIA_QUERY,
			'message' => 'On screen request, screen media query with only is preserved.'
		) );

		$this->assertTransformCssMediaCase( array(
			'media' => 'print',
			'expectedReturn' => 'print',
			'message' => 'On screen request, print media type is preserved'
		) );
	}

	/**
	 * Tests handheld behavior
	 * @covers OutputPage::transformCssMedia
	 */
	public function testHandheld() {
		$this->assertTransformCssMediaCase( array(
			'handheldQuery' => '1',
			'media' => 'handheld',
			'expectedReturn' => '',
			'message' => 'On request with handheld querystring and media is handheld, returns empty string'
		) );

		$this->assertTransformCssMediaCase( array(
			'handheldQuery' => '1',
			'media' => 'screen',
			'expectedReturn' => null,
			'message' => 'On request with handheld querystring and media is screen, returns null'
		) );
	}

	public static function provideMakeResourceLoaderLink() {
		return array(
			// Load module script only
			array(
				array( 'test.foo', ResourceLoaderModule::TYPE_SCRIPTS ),
				'<script>if(window.mw){
document.write("\u003Cscript src=\"http://127.0.0.1:8080/w/load.php?debug=false\u0026amp;lang=en\u0026amp;modules=test.foo\u0026amp;only=scripts\u0026amp;skin=fallback\u0026amp;*\"\u003E\u003C/script\u003E");
}</script>
'
			),
			array(
				// Don't condition wrap raw modules (like the startup module)
				array( 'test.raw', ResourceLoaderModule::TYPE_SCRIPTS ),
				'<script src="http://127.0.0.1:8080/w/load.php?debug=false&amp;lang=en&amp;modules=test.raw&amp;only=scripts&amp;skin=fallback&amp;*"></script>
'
			),
			// Load module styles only
			// This also tests the order the modules are put into the url
			array(
				array( array( 'test.baz', 'test.foo', 'test.bar' ), ResourceLoaderModule::TYPE_STYLES ),
				'<link rel=stylesheet href="http://127.0.0.1:8080/w/load.php?debug=false&amp;lang=en&amp;modules=test.bar%2Cbaz%2Cfoo&amp;only=styles&amp;skin=fallback&amp;*">
'
			),
			// Load private module (only=scripts)
			array(
				array( 'test.quux', ResourceLoaderModule::TYPE_SCRIPTS ),
				'<script>if(window.mw){
mw.test.baz({token:123});mw.loader.state({"test.quux":"ready"});

}</script>
'
			),
			// Load private module (combined)
			array(
				array( 'test.quux', ResourceLoaderModule::TYPE_COMBINED ),
				'<script>if(window.mw){
mw.loader.implement("test.quux",function($,jQuery){mw.test.baz({token:123});},{"css":[".mw-icon{transition:none}\n"]});

}</script>
'
			),
			// Load module script with ESI
			array(
				array( 'test.foo', ResourceLoaderModule::TYPE_SCRIPTS, true ),
				'<script><esi:include src="http://127.0.0.1:8080/w/load.php?debug=false&amp;lang=en&amp;modules=test.foo&amp;only=scripts&amp;skin=fallback&amp;*" /></script>
'
			),
			// Load module styles with ESI
			array(
				array( 'test.foo', ResourceLoaderModule::TYPE_STYLES, true ),
				'<style><esi:include src="http://127.0.0.1:8080/w/load.php?debug=false&amp;lang=en&amp;modules=test.foo&amp;only=styles&amp;skin=fallback&amp;*" /></style>
',
			),
			// Load no modules
			array(
				array( array(), ResourceLoaderModule::TYPE_COMBINED ),
				'',
			),
			// noscript group
			array(
				array( 'test.noscript', ResourceLoaderModule::TYPE_STYLES ),
				'<noscript><link rel=stylesheet href="http://127.0.0.1:8080/w/load.php?debug=false&amp;lang=en&amp;modules=test.noscript&amp;only=styles&amp;skin=fallback&amp;*"></noscript>
'
			),
			// Load two modules in separate groups
			array(
				array( array( 'test.group.foo', 'test.group.bar' ), ResourceLoaderModule::TYPE_COMBINED ),
				'<script>if(window.mw){
document.write("\u003Cscript src=\"http://127.0.0.1:8080/w/load.php?debug=false\u0026amp;lang=en\u0026amp;modules=test.group.bar\u0026amp;skin=fallback\u0026amp;*\"\u003E\u003C/script\u003E");
}</script>
<script>if(window.mw){
document.write("\u003Cscript src=\"http://127.0.0.1:8080/w/load.php?debug=false\u0026amp;lang=en\u0026amp;modules=test.group.foo\u0026amp;skin=fallback\u0026amp;*\"\u003E\u003C/script\u003E");
}</script>
'
			),
		);
	}

	/**
	 * @dataProvider provideMakeResourceLoaderLink
	 * @covers OutputPage::makeResourceLoaderLink
	 * @covers ResourceLoader::makeLoaderImplementScript
	 * @covers ResourceLoader::makeModuleResponse
	 * @covers ResourceLoader::makeInlineScript
	 * @covers ResourceLoader::makeLoaderStateScript
	 * @covers ResourceLoader::createLoaderURL
	 */
	public function testMakeResourceLoaderLink( $args, $expectedHtml ) {
		$this->setMwGlobals( array(
			'wgResourceLoaderDebug' => false,
			'wgResourceLoaderUseESI' => true,
			'wgLoadScript' => 'http://127.0.0.1:8080/w/load.php',
			// Affects whether CDATA is inserted
			'wgWellFormedXml' => false,
		) );
		$class = new ReflectionClass( 'OutputPage' );
		$method = $class->getMethod( 'makeResourceLoaderLink' );
		$method->setAccessible( true );
		$ctx = new RequestContext();
		$ctx->setSkin( SkinFactory::getDefaultInstance()->makeSkin( 'fallback' ) );
		$ctx->setLanguage( 'en' );
		$out = new OutputPage( $ctx );
		$rl = $out->getResourceLoader();
		$rl->setMessageBlobStore( new NullMessageBlobStore() );
		$rl->register( array(
			'test.foo' => new ResourceLoaderTestModule( array(
				'script' => 'mw.test.foo( { a: true } );',
				'styles' => '.mw-test-foo { content: "style"; }',
			)),
			'test.bar' => new ResourceLoaderTestModule( array(
				'script' => 'mw.test.bar( { a: true } );',
				'styles' => '.mw-test-bar { content: "style"; }',
			)),
			'test.baz' => new ResourceLoaderTestModule( array(
				'script' => 'mw.test.baz( { a: true } );',
				'styles' => '.mw-test-baz { content: "style"; }',
			)),
			'test.quux' => new ResourceLoaderTestModule( array(
				'script' => 'mw.test.baz( { token: 123 } );',
				'styles' => '/* pref-animate=off */ .mw-icon { transition: none; }',
				'group' => 'private',
			)),
			'test.raw' => new ResourceLoaderTestModule( array(
				'script' => 'mw.test.baz( { token: 123 } );',
				'isRaw' => true,
			)),
			'test.noscript' => new ResourceLoaderTestModule( array(
				'styles' => '.mw-test-noscript { content: "style"; }',
				'group' => 'noscript',
			)),
			'test.group.bar' => new ResourceLoaderTestModule( array(
				'styles' => '.mw-group-bar { content: "style"; }',
				'group' => 'bar',
			)),
			'test.group.foo' => new ResourceLoaderTestModule( array(
				'styles' => '.mw-group-foo { content: "style"; }',
				'group' => 'foo',
			)),
		) );
		$links = $method->invokeArgs( $out, $args );
		// Strip comments to avoid variation due to wgDBname in WikiID and cache key
		$actualHtml = preg_replace( '#/\*[^*]+\*/#', '', $links['html'] );
		$this->assertEquals( $expectedHtml, $actualHtml );
	}
}

/**
 * MessageBlobStore that doesn't do anything
 */
class NullMessageBlobStore extends MessageBlobStore {
	public function get ( ResourceLoader $resourceLoader, $modules, $lang ) {
		return array();
	}

	public function insertMessageBlob ( $name, ResourceLoaderModule $module, $lang ) {
		return false;
	}

	public function updateModule ( $name, ResourceLoaderModule $module, $lang ) {
		return;
	}

	public function updateMessage ( $key ) {
	}
	public function clear() {
	}
}

