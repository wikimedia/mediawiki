<?php

namespace MediaWiki\Tests\Api\Format;

use BadMethodCallException;
use Exception;
use MediaWiki\Api\ApiMain;
use MediaWiki\Context\RequestContext;
use MediaWiki\Request\FauxRequest;
use MediaWikiIntegrationTestCase;

abstract class ApiFormatTestBase extends MediaWikiIntegrationTestCase {

	protected function setUp(): void {
		parent::setUp();
		// These tests cover page rendering end-to-end, and run lots of extension hooks
		// that don't expect to be executed in tests.
		$this->clearHooks();
	}

	/**
	 * Name of the formatter being tested
	 * @var string
	 */
	protected $printerName;

	/**
	 * Return general data to be encoded for testing
	 * @return array See self::testGeneralEncoding
	 */
	public static function provideGeneralEncoding() {
		throw new BadMethodCallException( static::class . ' must implement ' . __METHOD__ );
	}

	/**
	 * Get the formatter output for the given input data
	 * @param array $params Query parameters
	 * @param array $data Data to encode
	 * @param array $options Options. If passed a string, the string is treated
	 *  as the 'class' option.
	 *  - name: Format name, rather than $this->printerName
	 *  - class: If set, register 'name' with this class (and 'factory', if that's set)
	 *  - factory: Used with 'class' to register at runtime
	 *  - returnPrinter: Return the printer object
	 * @return string|array The string if $options['returnPrinter'] isn't set, or an array if it is:
	 *  - text: Output text string
	 *  - printer: ApiFormatBase
	 * @throws Exception
	 */
	protected function encodeData( array $params, array $data, $options = [] ) {
		if ( is_string( $options ) ) {
			$options = [ 'class' => $options ];
		}
		$printerName = $options['name'] ?? $this->printerName;
		$flags = $options['flags'] ?? 0;

		$context = new RequestContext;
		$fauxRequest = new FauxRequest( $params, true );
		$fauxRequest->setRequestURL( 'https://' );
		$context->setRequest( $fauxRequest );
		$main = new ApiMain( $context );
		if ( isset( $options['class'] ) ) {
			$spec = [
				'class' => $options['class']
			];

			if ( isset( $options['factory'] ) ) {
				$spec['factory'] = $options['factory'];
			}

			$main->getModuleManager()->addModule( $printerName, 'format', $spec );
		}
		$result = $main->getResult();
		$result->addArrayType( null, 'default' );
		foreach ( $data as $k => $v ) {
			$result->addValue( null, $k, $v, $flags );
		}

		$ret = [];
		$printer = $main->createPrinterByName( $printerName );
		$printer->initPrinter();
		$printer->execute();
		ob_start();
		try {
			$printer->closePrinter();
			$ret['text'] = ob_get_clean();
		} catch ( Exception $ex ) {
			ob_end_clean();
			throw $ex;
		}

		if ( !empty( $options['returnPrinter'] ) ) {
			$ret['printer'] = $printer;
		}

		return count( $ret ) === 1 ? $ret['text'] : $ret;
	}

	/**
	 * @dataProvider provideGeneralEncoding
	 * @param array $data Data to be encoded
	 * @param string|Exception $expect String to expect, or exception expected to be thrown
	 * @param array $params Query parameters to set in the MediaWiki\Request\FauxRequest
	 * @param array $options Options to pass to self::encodeData()
	 */
	public function testGeneralEncoding(
		array $data, $expect, array $params = [], array $options = []
	) {
		if ( $expect instanceof Exception ) {
			$this->expectException( get_class( $expect ) );
			$this->expectExceptionMessage( $expect->getMessage() );
			$this->encodeData( $params, $data, $options ); // Should throw
		} else {
			$this->assertSame( $expect, $this->encodeData( $params, $data, $options ) );
		}
	}

}
