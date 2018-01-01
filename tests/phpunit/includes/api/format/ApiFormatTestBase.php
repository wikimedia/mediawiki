<?php

abstract class ApiFormatTestBase extends MediaWikiTestCase {

	/**
	 * Name of the formatter being tested
	 * @var string
	 */
	protected $printerName;

	/**
	 * Class being tested, if it's not already registered with
	 * the module manager
	 *
	 * @var string|null
	 */
	protected $printerClass;

	/**
	 * Factory to register with the module manager to
	 * create the class
	 *
	 * @var callable|null
	 */
	protected $printerFactory;

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
	 * @return string
	 * @throws Exception
	 */
	protected function encodeData( array $params, array $data ) {
		$context = new RequestContext;
		$context->setRequest( new FauxRequest( $params, true ) );
		$main = new ApiMain( $context );
		if ( $this->printerClass !== null ) {
			$main->getModuleManager()->addModule(
				$this->printerName, 'format', $this->printerClass, $this->printerFactory
			);
		}
		$result = $main->getResult();
		$result->addArrayType( null, 'default' );
		foreach ( $data as $k => $v ) {
			$result->addValue( null, $k, $v );
		}

		$printer = $main->createPrinterByName( $this->printerName );
		$printer->initPrinter();
		$printer->execute();

		if ( isset( $params['mime'] ) ) {
			$this->assertEquals( $params['mime'], $printer->getMimeType() );
		}
		if ( isset( $params['filename'] ) ) {
			$this->assertEquals( $params['filename'], $printer->getFilename() );
		}

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
	public function testGeneralEncoding( array $data, $expect, array $params = [] ) {
		if ( $expect instanceof Exception ) {
			$this->setExpectedException( get_class( $expect ), $expect->getMessage() );
		}
		$this->assertSame( $expect, $this->encodeData( $params, $data ) );
	}

}
