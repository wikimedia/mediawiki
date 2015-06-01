<?php

/**
 * @todo tests for HttpError::report
 *
 * @covers HttpError
 */
class HttpErrorTest extends MediaWikiLangTestCase {

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
	public function testGetHtml( $expected, $content, $header ) {
		$httpError = new HttpError( 500, $content, $header );
		$errorHtml = $httpError->getHtml();

		foreach( $expected as $key => $html ) {
			$this->assertContains( $html, $errorHtml, $key );
		}
	}

	public function getHtmlProvider() {
		return array(
			array(
				array(
					'head html' => '<head><title>Server Error 123</title></head>',
					'body html' => '<body><h1>Server Error 123</h1>'
						. '<p>a server error!</p></body>'
				),
				'a server error!',
				'Server Error 123'
			),
			array(
				array(
					'head html' => '<head><title>Login error</title></head>',
					'body html' => '<body><h1>Login error</h1>'
						. '<p>Your request to log out was denied because it looks '
						. 'like it was sent by a broken browser or caching proxy.</p></body>'
				),
				new Message( 'suspicious-userlogout' ),
				new Message( 'loginerror' )
			),
			array(
				array(
					'head html' => '<html><head><title>Internal Server Error</title></head>',
					'body html' => '<body><h1>Internal Server Error</h1>'
						. '<p>a server error!</p></body></html>'
				),
				'a server error!',
				null
			)
		);
	}


}
