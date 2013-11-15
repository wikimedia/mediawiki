<?php

/**
 * Unit tests for the HTMLCheckMatrix
 */
class HtmlCheckMatrixTest extends MediaWikiTestCase {
	static private $defaultOptions = array(
		'rows' => array( 'r1', 'r2' ),
		'columns' => array( 'c1', 'c2' ),
		'fieldname' => 'test',
	);

	/**
	 * @covers HTMLCheckMatrix::__construct
	 */
	public function testPlainInstantiation() {
		try {
			new HTMLCheckMatrix( array() );
		} catch ( MWException $e ) {
			$this->assertInstanceOf( 'HTMLFormFieldRequiredOptionsException', $e );
			return;
		}

		$this->fail( 'Expected MWException indicating missing parameters but none was thrown.' );
	}

	/**
	 * @covers HTMLCheckMatrix::__construct
	 */
	public function testInstantiationWithMinimumRequiredParameters() {
		new HTMLCheckMatrix( self::$defaultOptions );
		$this->assertTrue( true ); // form instantiation must throw exception on failure
	}

}
