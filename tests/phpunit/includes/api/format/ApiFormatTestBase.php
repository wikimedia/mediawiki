<?php

abstract class ApiFormatTestBase extends MediaWikiTestCase {

	/**
	 * Name of the formatter being tested
	 * @var string
	 */
	protected $printerName;

	/**
	 * Return general data to be encoded for testing
	 * @return array See self::testGeneralEncoding
	 * @throws Exception
	 */
	public static function provideGeneralEncoding() {
		throw new Exception( 'Subclass must implement ' . __METHOD__ );
	}

	/**
	 * Get the formatter output for the given input data
	 * @param array $params Query parameters
	 * @param array $data Data to encode
	 * @param string $class Printer class to use instead of the normal one
	 * @return string
	 * @throws Exception
	 */
	protected function encodeData( array $params, array $data, $class = null ) {
		$context = new RequestContext;
		$context->setRequest( new FauxRequest( $params, true ) );
		$main = new ApiMain( $context );
		if ( $class !== null ) {
			$main->getModuleManager()->addModule( $this->printerName, 'format', $class );
		}
		$result = $main->getResult();
		$result->addArrayType( null, 'default' );
		foreach ( $data as $k => $v ) {
			$result->addValue( null, $k, $v );
		}

		$printer = $main->createPrinterByName( $this->printerName );
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
	 * @dataProvider provideGeneralEncoding
	 */
	public function testGeneralEncoding( array $data, $expect, array $params = array() ) {
		if ( isset( $params['SKIP'] ) ) {
			$this->markTestSkipped( $expect );
		}
		$this->assertSame( $expect, $this->encodeData( $params, $data ) );
	}

}
