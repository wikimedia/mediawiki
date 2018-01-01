<?php

/**
 * @group API
 * @covers ApiFormatRaw
 */
class ApiFormatRawTest extends ApiFormatTestBase {

	protected $printerName = 'raw';

	/**
	 * Only basic types are tested here as ApiFormatRaw does not work with
	 * arrays or objects
	 * @return array datasets
	 */
	public static function provideGeneralEncoding() {
		return [
			[ [ true ], '1' ],
			[ [ false ], '' ],
			[ [ 42 ], '42' ],
			[ [ 42.5 ], '42.5' ],
			[ [ 1e42 ], '1.0E+42' ],
			[ [ 'foo' ], 'foo' ],
			[ [ 'fóo' ], 'fóo' ],
		];
	}

	/**
	 * Get the ApiFormatRaw formatter output for the given input data
	 * @param array $params Query parameters
	 * @param array $data Data to encode
	 * @param string $class This argument is ignored
	 * @return string
	 * @throws Exception
	 */
	protected function encodeData( array $params, array $data, $class = null ) {
		$context = new RequestContext;
		$context->setRequest( new FauxRequest( $params, true ) );
		$main = new ApiMain( $context );

		$result = $main->getResult();
		$result->addValue( null, 'mime', 'text/plain' );
		$result->addArrayType( null, 'default' );
		$result->addValue( null, 'text', $data[0] );

		$printer = new ApiFormatRaw( $main );
		$printer->initPrinter();
		$printer->execute();
		ob_start();
		try {
			$printer->closePrinter();
			return ob_get_clean();
		} catch ( Exception $ex ) {
			ob_end_clean();
			throw $ex;
		}
	}

	/**
	 * Check that ApiFormatRaw throws exception if mime type is not set
	 * @expectedException MWException
	 */
	public function testMissingMimeError() {
		$context = new RequestContext;
		$context->setRequest( new FauxRequest( [], true ) );
		$main = new ApiMain( $context );

		$result = $main->getResult();
		$result->addArrayType( null, 'default' );
		$result->addValue( null, 'text', 'some text' );

		$printer = new ApiFormatRaw( $main );
		$printer->initPrinter();
		$printer->execute();
		ob_start();
		try {
			$printer->closePrinter();
		} catch ( Exception $ex ) {
			throw $ex;
		}
		ob_end_clean();
	}

	/**
	 * Check that ApiFormatRaw throws exception if no text is given
	 * @expectedException MWException
	 */
	public function testNoTextError() {
		$context = new RequestContext;
		$context->setRequest( new FauxRequest( [], true ) );
		$main = new ApiMain( $context );

		$result = $main->getResult();
		$result->addValue( null, 'mime', 'text/plain' );
		$result->addArrayType( null, 'default' );
		$result->addValue( null, 'some key', 'some value' );

		$printer = new ApiFormatRaw( $main );
		$printer->initPrinter();
		$printer->execute();
		ob_start();
		try {
			$printer->closePrinter();
		} catch ( Exception $ex ) {
			throw $ex;
		}
		ob_end_clean();
	}

}
