<?php

/**
 * @group API
 * @covers ApiFormatJson
 */
class ApiFormatJsonTest extends ApiFormatTestBase {

	protected $printerName = 'json';

	public function provideGeneralEncoding() {
		return array(
			// Basic types
			array( array( null ), '[null]' ),
			array( array( true ), '[true]' ),
			array( array( false ), '[false]' ),
			array( array( 42 ), '[42]' ),
			array( array( 42.5 ), '[42.5]' ),
			array( array( 1e42 ), '[1.0e+42]' ),
			array( array( 'foo' ), '["foo"]' ),
			array( array( 'fóo' ), '["f\u00f3o"]' ),
			array( array( 'fóo' ), '["fóo"]', array( 'utf8' => 1 ) ),

			// Arrays and objects
			array( array( array() ), '[[]]' ),
			array( array( array( 1 ) ), '[[1]]' ),
			array( array( array( 'x' => 1 ) ), '[{"x":1}]' ),
			array( array( array( 2 => 1 ) ), '[{"2":1}]' ),
			array( array( (object)array() ), '[{}]' ),

			// Content
			array( array( '*' => 'foo' ), '{"*":"foo"}' ),

			// Callbacks
			array( array( 1 ), '/**/myCallback([1])', array( 'callback' => 'myCallback' ) ),

			// Cross-domain mangling
			array( array( '< Cross-Domain-Policy >' ), '["\u003C Cross-Domain-Policy \u003E"]' ),
		);
	}

}
