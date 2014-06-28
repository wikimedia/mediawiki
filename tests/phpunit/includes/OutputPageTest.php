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
			// JavaScript module
			array(
				array( 'mediawiki.api', ResourceLoaderModule::TYPE_SCRIPTS ),
				'<script src="http://127.0.0.1:8080/w/load.php?debug=false&amp;lang=en&amp;modules=mediawiki.api&amp;only=scripts&amp;skin=vector&amp;*"></script>
'
			),
			// Two styles-only modules
			array(
				array( array( 'mediawiki.skinning.interface', 'mediawiki.ui.button' ), ResourceLoaderModule::TYPE_STYLES ),
				'<link rel="stylesheet" href="http://127.0.0.1:8080/w/load.php?debug=false&amp;lang=en&amp;modules=mediawiki.skinning.interface%7Cmediawiki.ui.button&amp;only=styles&amp;skin=vector&amp;*" />
'
			),
			// A private module that gets inlined
			array(
				array( 'user.tokens', ResourceLoaderModule::TYPE_COMBINED ),
				'<script>if(window.mw){
mw.loader.implement("user.tokens",function($,jQuery){mw.user.tokens.set({"editToken":"+\\\","patrolToken":false,"watchToken":false});},{},{});
/* cache key: ' . wfWikiID() . ':resourceloader:filter:minify-js:7:f47f038f09d6ac0c11c8b132f0717602 */
}</script>
'
			),
			// a JavaScript module with ESI enabled
			array(
				array( 'mediawiki.api', ResourceLoaderModule::TYPE_SCRIPTS, true ),
				'<script>/*<![CDATA[*/<esi:include src="http://127.0.0.1:8080/w/load.php?debug=false&amp;lang=en&amp;modules=mediawiki.api&amp;only=scripts&amp;skin=vector&amp;*" />/*]]>*/</script>
'
			),
			// A style module with ESI enabled
			array(
				array( 'mediawiki.ui.button', ResourceLoaderModule::TYPE_STYLES, true ),
				'<style>/*<![CDATA[*/<esi:include src="http://127.0.0.1:8080/w/load.php?debug=false&amp;lang=en&amp;modules=mediawiki.ui.button&amp;only=styles&amp;skin=vector&amp;*" />/*]]>*/</style>
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
			'wgLoadScript' => 'http://127.0.0.1:8080/w/load.php'
		) );
		$class = new ReflectionClass( 'OutputPage' );
		$method = $class->getMethod( 'makeResourceLoaderLink' );
		$method->setAccessible( true );
		$ctx = new RequestContext();
		$out = new OutputPage( $ctx );
		$links = $method->invokeArgs( $out, $args );
		$this->assertEquals( $links['html'], $expectedHtml );
	}
}
