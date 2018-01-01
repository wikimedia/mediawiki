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
		];
	}

	/**
	 * Prepare factory of ApiFormatRaw printer
	 */
	public function setUp() {
		parent::setUp();
		$this->printerFactory = function( ApiMain $main ) {
			return new ApiFormatRaw( $main );
		};
	}

	/**
	 * Check that ApiFormatRaw throws exception if mime type is not set
	 */
	 public function testMissingMimeError() {
		 $this->testGeneralEncoding(
			 [ 'text' => 'some text' ],
			 null,
			 [ 'exception' => [ MWException::class, 'No MIME type set for raw formatter' ] ]
		 );
	 }

	/**
	 * Check that ApiFormatRaw throws exception if no text is given
	 */
	 public function testNoTextError() {
		 $this->testGeneralEncoding(
			 [ 'mime' => 'text/plain' ],
			 null,
			 [ 'exception' => [ MWException::class, 'No text given for raw formatter' ] ]
		 );
	 }

}
