<?php

/**
 * @group API
 * @covers ApiFormatJson
 */
class ApiFormatJsonTest extends ApiFormatTestBase {

	protected $printerName = 'json';

	private static function addFormatVersion( $format, $arr ) {
		foreach ( $arr as &$p ) {
			if ( !isset( $p[2] ) ) {
				$p[2] = array( 'formatversion' => $format );
			} else {
				$p[2]['formatversion'] = $format;
			}
		}
		return $arr;
	}

	public static function provideGeneralEncoding() {
		return array_merge(
			self::addFormatVersion( 1, array(
				// Basic types
				array( array( null ), '[null]' ),
				array( array( true ), '[""]' ),
				array( array( false ), '[]' ),
				array( array( true, ApiResult::META_BC_BOOLS => array( 0 ) ), '[true]' ),
				array( array( false, ApiResult::META_BC_BOOLS => array( 0 ) ), '[false]' ),
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
				array( array( array( 1, ApiResult::META_TYPE => 'assoc' ) ), '[{"0":1}]' ),
				array( array( array( 'x' => 1, ApiResult::META_TYPE => 'array' ) ), '[[1]]' ),
				array( array( array( 'x' => 1, ApiResult::META_TYPE => 'kvp' ) ), '[{"x":1}]' ),
				array( array( array( 'x' => 1, ApiResult::META_TYPE => 'BCkvp', ApiResult::META_KVP_KEY_NAME => 'key' ) ),
					'[[{"key":"x","*":1}]]' ),
				array( array( array( 'x' => 1, ApiResult::META_TYPE => 'BCarray' ) ), '[{"x":1}]' ),
				array( array( array( 'a', 'b', ApiResult::META_TYPE => 'BCassoc' ) ), '[["a","b"]]' ),

				// Content
				array( array( 'content' => 'foo', ApiResult::META_CONTENT => 'content' ),
					'{"*":"foo"}' ),

				// BC Subelements
				array( array( 'foo' => 'foo', ApiResult::META_BC_SUBELEMENTS => array( 'foo' ) ),
					'{"foo":{"*":"foo"}}' ),

				// Callbacks
				array( array( 1 ), '/**/myCallback([1])', array( 'callback' => 'myCallback' ) ),

				// Cross-domain mangling
				array( array( '< Cross-Domain-Policy >' ), '["\u003C Cross-Domain-Policy \u003E"]' ),
			) ),
			self::addFormatVersion( 2, array(
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
				array( array( array( 'a', 'b', ApiResult::META_TYPE => 'BCassoc' ) ), '[{"0":"a","1":"b"}]' ),

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
			) )
		);
	}

}
