<?php

use MediaWiki\Exception\HttpError;
use MediaWiki\Message\Message;

/**
 * @todo tests for HttpError::report
 *
 * @covers \MediaWiki\Exception\HttpError
 */
class HttpErrorTest extends MediaWikiUnitTestCase {

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
	public function testGetHtml( array $expected, $contentSpec, $headerSpec ) {
		// Avoid parsing logic in real Message class which includes text transformations
		// that require MediaWikiServices
		if ( is_array( $contentSpec ) ) {
			$content = $this->createMock( Message::class );
			$content->method( 'escaped' )->willReturn( ...$contentSpec );
		} else {
			$content = $contentSpec;
		}
		if ( is_array( $headerSpec ) ) {
			$header = $this->createMock( Message::class );
			$header->method( 'escaped' )->willReturn( ...$headerSpec );
		} else {
			$header = $headerSpec;
		}

		$httpError = new HttpError( 500, $content, $header );
		$errorHtml = $httpError->getHTML();

		foreach ( $expected as $key => $html ) {
			$this->assertStringContainsString( $html, $errorHtml, $key );
		}
	}

	public static function getHtmlProvider() {
		return [
			[
				[
					'head html' => '<head><title>Server Error 123</title>',
					'body html' => '<body><h1>Server Error 123</h1>'
						. '<p>a server error!</p></body>'
				],
				'a server error!',
				'Server Error 123'
			],
			[
				[
					'head html' => '<head><title>loginerror</title>',
					'body html' => '<body><h1>loginerror</h1>'
					. '<p>blahblah</p></body>'
				],
				[ 'blahblah' ],
				[ 'loginerror' ]
			],
			[
				[
					'head html' => '<html><head><title>Internal Server Error</title>',
					'body html' => '<body><h1>Internal Server Error</h1>'
						. '<p>a server error!</p></body></html>'
				],
				'a server error!',
				null
			]
		];
	}
}
