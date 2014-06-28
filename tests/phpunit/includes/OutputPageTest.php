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
	 * @param array $args key-value array of arguments as shown above
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
		$this->setMWGlobals( array(
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
				'<script src="http://127.0.0.1:8080/w/load.php?debug=false&amp;lang=en&amp;modules=test.foo&amp;only=scripts&amp;skin=vector&amp;*"></script>
'
			),
			// Load module styles only
			// This also tests the order the modules are put into the url
			array(
				array( array( 'test.baz', 'test.foo', 'test.bar' ), ResourceLoaderModule::TYPE_STYLES ),
				'<link rel=stylesheet href="http://127.0.0.1:8080/w/load.php?debug=false&amp;lang=en&amp;modules=test.bar%2Cbaz%2Cfoo&amp;only=styles&amp;skin=vector&amp;*">
'
			),
			// Load private module (combined)
			array(
				array( 'test.quux', ResourceLoaderModule::TYPE_COMBINED ),
				'<script>if(window.mw){
mw.loader.implement("test.quux",function($,jQuery){mw.test.baz({token:123});},{"css":[".mw-icon{transition:none}\n/* cache key: ' . wfWikiId() . ':resourceloader:filter:minify-css:7:fd8ea20b3336b2bfb230c789d430067a */"]},{});
/* cache key: ' . wfWikiId() . ':resourceloader:filter:minify-js:7:274ccee17be73cd5f4dda5dc2a819188 */
}</script>
'
			),
			// Load module script with with ESI
			array(
				array( 'test.foo', ResourceLoaderModule::TYPE_SCRIPTS, true ),
				'<script><esi:include src="http://127.0.0.1:8080/w/load.php?debug=false&amp;lang=en&amp;modules=test.foo&amp;only=scripts&amp;skin=vector&amp;*" /></script>
'
			),
			// Load module styles with with ESI
			array(
				array( 'test.foo', ResourceLoaderModule::TYPE_STYLES, true ),
				'<style><esi:include src="http://127.0.0.1:8080/w/load.php?debug=false&amp;lang=en&amp;modules=test.foo&amp;only=styles&amp;skin=vector&amp;*" /></style>
',
			),
		);
	}


	/**
	 * @dataProvider provideMakeResourceLoaderLink
	 * @covers OutputPage::makeResourceLoaderLink
	 */
	public function testMakeResourceLoaderLink( $args, $expectedHtml) {
		$this->setMwGlobals( array(
			'wgResourceLoaderUseESI' => true,
			'wgLoadScript' => 'http://127.0.0.1:8080/w/load.php',
			// Affects whether CDATA is inserted
			'wgWellFormedXml' => false,
			// Cache key is based on database name, and affects output;
			// this test should not touch the database anyways.
			'wgDBname' => 'wiki',
			'wgDBprefix' => '',
		) );
		$class = new ReflectionClass( 'OutputPage' );
		$method = $class->getMethod( 'makeResourceLoaderLink' );
		$method->setAccessible( true );
		$ctx = new RequestContext();
		$out = new OutputPage( $ctx );
		$rl = $out->getResourceLoader();
		$rl->register( array(
			'test.foo' => new ResourceLoaderTestModule(array(
				'script' => 'mw.test.foo( { a: true } );',
				'styles' => '.mw-test-foo { content: "style"; }',
			)),
			'test.bar' => new ResourceLoaderTestModule(array(
				'script' => 'mw.test.bar( { a: true } );',
				'styles' => '.mw-test-bar { content: "style"; }',
			)),
			'test.baz' => new ResourceLoaderTestModule(array(
				'script' => 'mw.test.baz( { a: true } );',
				'styles' => '.mw-test-baz { content: "style"; }',
			)),
			'test.quux' => new ResourceLoaderTestModule(array(
				'script' => 'mw.test.baz( { token: 123 } );',
				'styles' => '/* pref-animate=off */ .mw-icon { transition: none; }',
				'group' => 'private',
			)),
		) );
		$links = $method->invokeArgs( $out, $args );
		$this->assertEquals( $expectedHtml, $links['html'] );
	}
}
