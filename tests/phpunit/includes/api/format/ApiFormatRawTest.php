<?php

/**
 * @group API
 * @covers ApiFormatRaw
 */
class ApiFormatRawTest extends ApiFormatTestBase {

	protected $printerName = 'raw';
	protected $printerClass = 'ApiFormatRaw';

	/**
	 * Test basic encoding and missing mime and text exceptions
	 * @return array datasets
	 */
	public static function provideGeneralEncoding() {
		$options = [
			'class' => 'ApiFormatRaw',
			'factory' => function ( ApiMain $main ) {
				return new ApiFormatRaw( $main );
			}
		];

		return [
			[
				[ 'mime' => 'text/plain', 'text' => 'foo' ],
				'foo',
				[ 'mime' => 'text/plain' ],
				$options
			],
			[
				[ 'mime' => 'text/plain', 'text' => 'fóo' ],
				'fóo',
				[ 'mime' => 'text/plain' ],
				$options
			],
			[
				[ 'text' => 'some text' ],
				new MWException( 'No MIME type set for raw formatter' ),
				[],
				$options
			],
			[
				[ 'mime' => 'text/plain' ],
				new MWException( 'No text given for raw formatter' ),
				[],
				$options
			]
		];
	}

	/**
	 * Test specifying filename
	 */
	public function testFilename() {
		$result = $this->encodeData(
			[],
			[ 'mime' => 'text/plain', 'text' => 'some text', 'filename' => 'whatever.raw' ],
			[
				'returnPrinter' => true,
				'class' => 'ApiFormatRaw',
				'factory' => function ( ApiMain $main ) {
					return new ApiFormatRaw( $main );
				}
			]
		);

		$this->assertSame( 'some text', $result['text'] );
		$this->assertSame( 'whatever.raw', $result['printer']->getFilename() );
	}

	/**
	 * Test specifying filename with error fallback printer
	 */
	public function testErrorFallbackFilename() {
		$result = $this->encodeData(
			[],
			[
				'mime' => 'text/plain',
				'text' => 'some text',
				'error' => 'some error',
				'filename' => 'whatever.raw'
			],
			[
				'returnPrinter' => true,
				'class' => 'ApiFormatRaw',
				'factory' => function ( ApiMain $main ) {
					return new ApiFormatRaw( $main, new ApiFormatJson( $main, 'json' ) );
				}
			]
		);

		$this->assertSame(
			'{"mime":"text/plain","text":"some text","error":"some error","filename":"whatever.raw"}',
			$result['text']
		);
		$this->assertSame( 'api-result.json', $result['printer']->getFilename() );
	}

	/**
	 * Test specifying mime
	 */
	public function testMime() {
		$result = $this->encodeData(
			[],
			[ 'mime' => 'text/plain', 'text' => 'some text' ],
			[
				'returnPrinter' => true,
				'class' => 'ApiFormatRaw',
				'factory' => function ( ApiMain $main ) {
					return new ApiFormatRaw( $main );
				}
			]
		);

		$this->assertSame( 'some text', $result['text'] );
		$this->assertSame( 'text/plain', $result['printer']->getMimeType() );
	}

	/**
	 * Test specifying mime with error fallback printer
	 */
	public function testErrorFallbackMime() {
		$result = $this->encodeData(
			[],
			[ 'mime' => 'text/plain', 'text' => 'some text', 'error' => 'some error' ],
			[
				'returnPrinter' => true,
				'class' => 'ApiFormatRaw',
				'factory' => function ( ApiMain $main ) {
					return new ApiFormatRaw( $main, new ApiFormatJson( $main, 'json' ) );
				}
			]
		);

		$this->assertSame( '{"mime":"text/plain","text":"some text","error":"some error"}', $result['text'] );
		$this->assertSame( 'application/json', $result['printer']->getMimeType() );
	}

	/**
	 * Check that setting failWithHTTPError to true will result in 400 response status code
	 */
	public function testFailWithHTTPError() {
		$apiMain = null;

		$this->testGeneralEncoding(
			[ 'mime' => 'text/plain', 'text' => 'some text', 'error' => 'some error' ],
			'{"mime":"text/plain","text":"some text","error":"some error"}',
			[],
			[
				'class' => 'ApiFormatRaw',
				'factory' => function ( ApiMain $main ) use ( &$apiMain ) {
					$apiMain = $main;
					$printer = new ApiFormatRaw( $main, new ApiFormatJson( $main, 'json' ) );
					$printer->setFailWithHTTPError( true );
					return $printer;
				}
			]
		);
		$this->assertEquals( 400, $apiMain->getRequest()->response()->getStatusCode() );
	}

}
