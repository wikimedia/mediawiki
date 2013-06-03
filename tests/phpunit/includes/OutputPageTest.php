<?php

/**
 *
 * @author Matthew Flaschen
 *
 * @group Output
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

	/**
	 * Tests that OutputPage's handling of RDFa prefixes on the headElement is correct.
	 */
	public function testPrefixContext() {
		if ( !class_exists( 'DOMDocument' ) ) {
			$this->markTestSkipped( 'DOMDocument is required.' );
			return;
		}

		// Setup a fake context with an OutputPage
		$ctx = new RequestContext;
		$ctx->setRequest( new FauxRequest() );
		$ctx->setLanguage( 'en' );
		$ctx->setTitle( Title::newMainPage() );
		$out = $ctx->getOutput();

		// Setup prefixes and data
		$prefixContext = $out->getPrefixContext();
		$og = $prefixContext->prefix( 'og', 'http://ogp.me/ns#' );
		$schema = $prefixContext->prefix( 'schema', 'http://schema.org/' );
		$out->addGraphProperty( $og->curie( 'title' ), 'FooBar' );
		$out->addGraphLink( $schema->curie( 'url' ), 'http://example.org/' );
		$out->addGraphRelation( $schema->curie( 'about' ), '_:' );

		// Build the head element and then load it into a DOM
		$doc = new DOMDocument;
		$doc->loadHTML( $out->headElement( $ctx->getSkin() ) . '</body></html>' );
		$xpath = new DOMXPath( $doc );

		// Assert that the prefix is present and has the correct value.		
		$this->assertEquals(
			"og: http://ogp.me/ns# schema: http://schema.org/",
			$xpath->evaluate( 'string(/html/@prefix)' ),
			'Looking for the proper RDFa prefix attribute value.'
		);

		// Assert that the RDFa items are present.
		$this->assertEquals(
			"FooBar",
			$xpath->evaluate( 'string(/html/head/meta[@property="og:title"]/@content)' ),
			'Looking for <meta property="og:title" content="FooBar">'
		);
		$this->assertEquals(
			"http://example.org/",
			$xpath->evaluate( 'string(/html/head/link[@rel="schema:url"]/@href)' ),
			'Looking for <link rel="schema:url" href="http://example.org/">'
		);
		$this->assertEquals(
			"_:",
			$xpath->evaluate( 'string(/html/head/link[@rel="schema:about"]/@resource)' ),
			'Looking for <link rel="schema:about" resource="_:">'
		);
	}
}
