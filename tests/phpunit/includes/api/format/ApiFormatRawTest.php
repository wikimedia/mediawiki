<?php

/**
 * @group API
 * @covers ApiFormatRaw
 */
class ApiFormatRawTest extends ApiFormatTestBase {

	protected $printerName = 'raw';
	protected $printerClass = 'ApiFormatRaw';

	/**
	 * Only basic types are tested here as ApiFormatRaw does not work with
	 * arrays or objects
	 * @return array datasets
	 */
	public static function provideGeneralEncoding() {
		return [
			[
				[ 'mime' => 'text/plain', 'text' => 'foo' ],
				'foo',
				[ 'mime' => 'text/plain' ]
			],
			[
				[ 'mime' => 'text/plain', 'text' => 'fóo' ],
				'fóo',
				[ 'mime' => 'text/plain' ]
			],
			[
				[ 'text' => 'some text' ],
				new MWException( 'No MIME type set for raw formatter' ),
			],
			[
				[ 'mime' => 'text/plain' ],
				new MWException( 'No text given for raw formatter' ),
			],
			'test error fallback' => [
				[ 'mime' => 'text/plain', 'text' => 'some text', 'error' => 'some error' ],
				'{"mime":"text/plain","text":"some text","error":"some error"}',
				[ 'mime' => 'application/json' ]
			],
			'test setting filename' => [
				[ 'mime' => 'text/plain', 'text' => 'some text', 'filename' => 'whatever.raw' ],
				'some text',
				[ 'mime' => 'text/plain', 'filename' => 'whatever.raw' ]
			],
			'test error fallback with filename' => [
				[
					'mime' => 'text/plain',
					'text' => 'some text',
					'error' => 'some error',
					'filename' => 'whatever.raw'
				],
				'{"mime":"text/plain","text":"some text","error":"some error","filename":"whatever.raw"}',
				[ 'mime' => 'application/json', 'filename' => 'api-result.json' ]
			]
		];
	}

	/**
	 * Prepare factory of ApiFormatRaw printer
	 */
	public function setUp() {
		parent::setUp();
		$this->printerFactory = function ( ApiMain $main ) {
			return new ApiFormatRaw( $main, new ApiFormatJson( $main, 'json' ) );
		};
	}

	/**
	 * Test using ApiFormatRaw without fallback formatter
	 */
	public function testNoFallback() {
		$this->printerFactory = function ( ApiMain $main ) {
			return new ApiFormatRaw( $main );
		};
		$this->testGeneralEncoding(
			[ 'mime' => 'text/plain', 'text' => 'some text' ],
			'some text'
		);
	}

	/**
	 * Check that setting failWithHTTPError to true will result in 400 response status code
	 */
	public function testFailWithHTTPError() {
		$apiMain = null;

		$this->printerFactory = function ( ApiMain $main ) use ( &$apiMain ) {
			$apiMain = $main;
			$printer = new ApiFormatRaw( $main, new ApiFormatJson( $main, 'json' ) );
			$printer->setFailWithHTTPError( true );
			return $printer;
		};

		$this->testGeneralEncoding(
			[ 'mime' => 'text/plain', 'text' => 'some text', 'error' => 'some error' ],
			'{"mime":"text/plain","text":"some text","error":"some error"}'
		);
		$this->assertEquals( 400, $apiMain->getRequest()->response()->getStatusCode() );
	}

}
