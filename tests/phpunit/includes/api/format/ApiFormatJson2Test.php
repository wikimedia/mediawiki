<?php

/**
 * @group API
 * @covers ApiFormatJson
 */
class ApiFormatJson2Test extends ApiFormatTestBase {

	protected $printerName = 'json2';

	public static function provideGeneralEncoding() {
		return array(
			// Basic types
			array( array( null ), '[null]' ),
			array( array( true ), '[true]' ),
			array( array( false ), '[false]' ),
			array( array( true, ApiResult::META_BC_BOOLS => array( 0 ) ), '[true]' ),
			array( array( false, ApiResult::META_BC_BOOLS => array( 0 ) ), '[false]' ),
			array( array( 42 ), '[42]' ),
			array( array( 42.5 ), '[42.5]' ),
			array( array( 1e42 ), '[1.0e+42]' ),
			array( array( 'foo' ), '["foo"]' ),
			array( array( 'fóo' ), '["fóo"]' ),
			array( array( 'fóo' ), '["f\u00f3o"]', array( 'ascii' => 1 ) ),

			// Arrays and objects
			array( array( array() ), '[[]]' ),
			array( array( array( 'x' => 1 ) ), '[{"x":1}]' ),
			array( array( array( 2 => 1 ) ), '[{"2":1}]' ),
			array( array( (object)array() ), '[{}]' ),
			array( array( array( 1, ApiResult::META_TYPE => 'assoc' ) ), '[{"0":1}]' ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'array' ) ), '[[1]]' ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'kvp' ) ), '[{"x":1}]' ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'BCkvp', ApiResult::META_KVP_KEY_NAME => 'key' ) ),
				'[{"x":1}]' ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'BCarray' ) ), '[[1]]' ),

			// Content
			array( array( 'content' => 'foo', ApiResult::META_CONTENT => 'content' ),
				'{"content":"foo"}' ),

			// BC Subelements
			array( array( 'foo' => 'foo', ApiResult::META_BC_SUBELEMENTS => array( 'foo' ) ),
				'{"foo":"foo"}' ),

			// Callbacks
			array( array( 1 ), '/**/myCallback([1])', array( 'callback' => 'myCallback' ) ),

			// Cross-domain mangling
			array( array( '< Cross-Domain-Policy >' ), '["\u003C Cross-Domain-Policy \u003E"]' ),
		);
	}

}
