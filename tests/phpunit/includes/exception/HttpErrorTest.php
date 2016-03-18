<?php

/**
 * @todo tests for HttpError::report
 *
 * @covers HttpError
 */
class HttpErrorTest extends MediaWikiTestCase {

	public function testIsLoggable() {
		$httpError = new HttpError( 500, 'server error!' );
		$this->assertFalse( $httpError->isLoggable(), 'http error is not loggable' );
	}

	public function testGetStatusCode() {
		$httpError = new HttpError( 500, 'server error!' );
		$this->assertEquals( 500, $httpError->getStatusCode() );
	}

	/**
	 * @dataProvider getHtmlProvider
	 */
	public function testGetHtml( array $expected, $content, $header ) {
		$httpError = new HttpError( 500, $content, $header );
		$errorHtml = $httpError->getHTML();

		foreach ( $expected as $key => $html ) {
			$this->assertContains( $html, $errorHtml, $key );
		}
	}

	public function getHtmlProvider() {
		return [
			[
				[
					'head html' => '<head><title>Server Error 123</title></head>',
					'body html' => '<body><h1>Server Error 123</h1>'
						. '<p>a server error!</p></body>'
				],
				'a server error!',
				'Server Error 123'
			],
			[
				[
					'head html' => '<head><title>loginerror</title></head>',
					'body html' => '<body><h1>loginerror</h1>'
					. '<p>suspicious-userlogout</p></body>'
				],
				new RawMessage( 'suspicious-userlogout' ),
				new RawMessage( 'loginerror' )
			],
			[
				[
					'head html' => '<html><head><title>Internal Server Error</title></head>',
					'body html' => '<body><h1>Internal Server Error</h1>'
						. '<p>a server error!</p></body></html>'
				],
				'a server error!',
				null
			]
		];
	}
}
