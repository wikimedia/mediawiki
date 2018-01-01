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
			[ [ 'mime' => 'text/plain', 'text' => true ], '1' ],
			[ [ 'mime' => 'text/plain', 'text' => false ], '' ],
			[ [ 'mime' => 'text/plain', 'text' => 42 ], '42' ],
			[ [ 'mime' => 'text/plain', 'text' => 42.5 ], '42.5' ],
			[ [ 'mime' => 'text/plain', 'text' => 1e42 ], '1.0E+42' ],
			[ [ 'mime' => 'text/plain', 'text' => 'foo' ], 'foo' ],
			[ [ 'mime' => 'text/plain', 'text' => 'fóo' ], 'fóo' ],
			[
				[ 'text' => 'some text' ],
				null,
				[ 'exception' => [ MWException::class, 'No MIME type set for raw formatter' ] ]
			],
			[
				[ 'mime' => 'text/plain' ],
				null,
				[ 'exception' => [ MWException::class, 'No text given for raw formatter' ] ]
			],
			[
				[ 'mime' => 'text/plain', 'text' => 'some text', 'error' => 'some error' ],
				'{"mime":"text/plain","text":"some text","error":"some error"}'
			],
			[
				[ 'mime' => 'text/plain', 'text' => 'some text', 'filename' => 'whatever.raw' ],
				'some text'
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
