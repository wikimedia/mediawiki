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
	 * options['handheldForIPhone'] - value of the $wgHandheldForIPhone global
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
			'wgHandheldForIPhone' => $args['handheldForIPhone']
		) );

		$actualReturn = OutputPage::transformCssMedia( $args['media'] );
		$this->assertSame( $args['expectedReturn'], $actualReturn, $args['message'] );
	}

	/**
	 * Tests a case of transformCssMedia with both values of wgHandheldForIPhone.
	 * Used to verify that behavior is orthogonal to that option.
	 *
	 * If the value of wgHandheldForIPhone should matter, use assertTransformCssMediaCase.
	 *
	 * @param array $args key-value array of arguments as shown in assertTransformCssMediaCase.
	 * Will be mutated.
	 */
	protected function assertTransformCssMediaCaseWithBothHandheldForIPhone( $args ) {
		$message = $args['message'];
		foreach ( array( true, false ) as $handheldForIPhone ) {
			$args['handheldForIPhone'] = $handheldForIPhone;
			$stringHandheldForIPhone = var_export( $handheldForIPhone, true );
			$args['message'] = "$message. \$wgHandheldForIPhone was $stringHandheldForIPhone";
			$this->assertTransformCssMediaCase( $args );
		}
	}

	/**
	 * Tests print requests
	 */
	public function testPrintRequests() {
		$this->assertTransformCssMediaCaseWithBothHandheldForIPhone( array(
			'printableQuery' => '1',
			'media' => 'screen',
			'expectedReturn' => null,
			'message' => 'On printable request, screen returns null'
		) );

		$this->assertTransformCssMediaCaseWithBothHandheldForIPhone( array(
			'printableQuery' => '1',
			'media' => self::SCREEN_MEDIA_QUERY,
			'expectedReturn' => null,
			'message' => 'On printable request, screen media query returns null'
		) );

		$this->assertTransformCssMediaCaseWithBothHandheldForIPhone( array(
			'printableQuery' => '1',
			'media' => self::SCREEN_ONLY_MEDIA_QUERY,
			'expectedReturn' => null,
			'message' => 'On printable request, screen media query with only returns null'
		) );

		$this->assertTransformCssMediaCaseWithBothHandheldForIPhone( array(
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
			'handheldForIPhone' => false,
			'media' => 'screen',
			'expectedReturn' => 'screen',
			'message' => 'On screen request, with handheldForIPhone false, screen media type is preserved'
		) );

		$this->assertTransformCssMediaCaseWithBothHandheldForIPhone( array(
			'media' => self::SCREEN_MEDIA_QUERY,
			'expectedReturn' => self::SCREEN_MEDIA_QUERY,
			'message' => 'On screen request, screen media query is preserved.'
		) );

		$this->assertTransformCssMediaCaseWithBothHandheldForIPhone( array(
			'media' => self::SCREEN_ONLY_MEDIA_QUERY,
			'expectedReturn' => self::SCREEN_ONLY_MEDIA_QUERY,
			'message' => 'On screen request, screen media query with only is preserved.'
		) );

		$this->assertTransformCssMediaCaseWithBothHandheldForIPhone( array(
			'media' => 'print',
			'expectedReturn' => 'print',
			'message' => 'On screen request, print media type is preserved'
		) );
	}

	/**
	 * Tests handheld and wgHandheldForIPhone behavior
	 */
	public function testHandheld() {
		$this->assertTransformCssMediaCaseWithBothHandheldForIPhone( array(
			'handheldQuery' => '1',
			'media' => 'handheld',
			'expectedReturn' => '',
			'message' => 'On request with handheld querystring and media is handheld, returns empty string'
		) );

		$this->assertTransformCssMediaCaseWithBothHandheldForIPhone( array(
			'handheldQuery' => '1',
			'media' => 'screen',
			'expectedReturn' => null,
			'message' => 'On request with handheld querystring and media is screen, returns null'
		) );

		// A bit counter-intuitively, $wgHandheldForIPhone should only matter if the query handheld is false or omitted
		$this->assertTransformCssMediaCase( array(
			'handheldQuery' => '0',
			'media' => 'screen',
			'handheldForIPhone' => true,
			'expectedReturn' => 'screen and (min-device-width: 481px)',
			'message' => 'With $wgHandheldForIPhone true, screen media type is transformed'
		) );

		$this->assertTransformCssMediaCase( array(
			'media' => 'handheld',
			'handheldForIPhone' => true,
			'expectedReturn' => 'handheld, only screen and (max-device-width: 480px)',
			'message' => 'With $wgHandheldForIPhone true, handheld media type is transformed'
		) );

		$this->assertTransformCssMediaCase( array(
			'media' => 'handheld',
			'handheldForIPhone' => false,
			'expectedReturn' => 'handheld',
			'message' => 'With $wgHandheldForIPhone false, handheld media type is preserved'
		) );
	}
}
