<?php

/**
 * @group API
 * @covers ApiFormatJson
 */
class ApiFormatJsonTest extends ApiFormatTestBase {

	protected $printerName = 'json';

	private static function addFormatVersion( $format, $arr ) {
		$ret = [];
		foreach ( $arr as $val ) {
			if ( !isset( $val[2] ) ) {
				$val[2] = [];
			}
			$val[2]['formatversion'] = $format;
			$ret[] = $val;
			if ( $format === 2 ) {
				// Add a test for 'latest' as well
				$val[2]['formatversion'] = 'latest';
				$ret[] = $val;
			}
		}
		return $ret;
	}

	public static function provideGeneralEncoding() {
		return array_merge(
			self::addFormatVersion( 1, [
				// Basic types
				[ [ null ], '[null]' ],
				[ [ true ], '[""]' ],
				[ [ false ], '[]' ],
				[ [ true, ApiResult::META_BC_BOOLS => [ 0 ] ], '[true]' ],
				[ [ false, ApiResult::META_BC_BOOLS => [ 0 ] ], '[false]' ],
				[ [ 42 ], '[42]' ],
				[ [ 42.5 ], '[42.5]' ],
				[ [ 1e42 ], '[1.0e+42]' ],
				[ [ 'foo' ], '["foo"]' ],
				[ [ 'fóo' ], '["f\u00f3o"]' ],
				[ [ 'fóo' ], '["fóo"]', [ 'utf8' => 1 ] ],

				// Arrays and objects
				[ [ [] ], '[[]]' ],
				[ [ [ 1 ] ], '[[1]]' ],
				[ [ [ 'x' => 1 ] ], '[{"x":1}]' ],
				[ [ [ 2 => 1 ] ], '[{"2":1}]' ],
				[ [ (object)[] ], '[{}]' ],
				[ [ [ 1, ApiResult::META_TYPE => 'assoc' ] ], '[{"0":1}]' ],
				[ [ [ 'x' => 1, ApiResult::META_TYPE => 'array' ] ], '[[1]]' ],
				[ [ [ 'x' => 1, ApiResult::META_TYPE => 'kvp' ] ], '[{"x":1}]' ],
				[
					[ [
						'x' => 1,
						ApiResult::META_TYPE => 'BCkvp',
						ApiResult::META_KVP_KEY_NAME => 'key'
					] ],
					'[[{"key":"x","*":1}]]'
				],
				[ [ [ 'x' => 1, ApiResult::META_TYPE => 'BCarray' ] ], '[{"x":1}]' ],
				[ [ [ 'a', 'b', ApiResult::META_TYPE => 'BCassoc' ] ], '[["a","b"]]' ],

				// Content
				[ [ 'content' => 'foo', ApiResult::META_CONTENT => 'content' ],
					'{"*":"foo"}' ],

				// BC Subelements
				[ [ 'foo' => 'foo', ApiResult::META_BC_SUBELEMENTS => [ 'foo' ] ],
					'{"foo":{"*":"foo"}}' ],

				// Callbacks
				[ [ 1 ], '/**/myCallback([1])', [ 'callback' => 'myCallback' ] ],

				// Cross-domain mangling
				[ [ '< Cross-Domain-Policy >' ], '["\u003C Cross-Domain-Policy >"]' ],
			] ),
			self::addFormatVersion( 2, [
				// Basic types
				[ [ null ], '[null]' ],
				[ [ true ], '[true]' ],
				[ [ false ], '[false]' ],
				[ [ true, ApiResult::META_BC_BOOLS => [ 0 ] ], '[true]' ],
				[ [ false, ApiResult::META_BC_BOOLS => [ 0 ] ], '[false]' ],
				[ [ 42 ], '[42]' ],
				[ [ 42.5 ], '[42.5]' ],
				[ [ 1e42 ], '[1.0e+42]' ],
				[ [ 'foo' ], '["foo"]' ],
				[ [ 'fóo' ], '["fóo"]' ],
				[ [ 'fóo' ], '["f\u00f3o"]', [ 'ascii' => 1 ] ],

				// Arrays and objects
				[ [ [] ], '[[]]' ],
				[ [ [ 'x' => 1 ] ], '[{"x":1}]' ],
				[ [ [ 2 => 1 ] ], '[{"2":1}]' ],
				[ [ (object)[] ], '[{}]' ],
				[ [ [ 1, ApiResult::META_TYPE => 'assoc' ] ], '[{"0":1}]' ],
				[ [ [ 'x' => 1, ApiResult::META_TYPE => 'array' ] ], '[[1]]' ],
				[ [ [ 'x' => 1, ApiResult::META_TYPE => 'kvp' ] ], '[{"x":1}]' ],
				[
					[ [
						'x' => 1,
						ApiResult::META_TYPE => 'BCkvp',
						ApiResult::META_KVP_KEY_NAME => 'key'
					] ],
					'[{"x":1}]'
				],
				[ [ [ 'x' => 1, ApiResult::META_TYPE => 'BCarray' ] ], '[[1]]' ],
				[
					[ [
						'a',
						'b',
						ApiResult::META_TYPE => 'BCassoc'
					] ],
					'[{"0":"a","1":"b"}]'
				],

				// Content
				[ [ 'content' => 'foo', ApiResult::META_CONTENT => 'content' ],
					'{"content":"foo"}' ],

				// BC Subelements
				[ [ 'foo' => 'foo', ApiResult::META_BC_SUBELEMENTS => [ 'foo' ] ],
					'{"foo":"foo"}' ],

				// Callbacks
				[ [ 1 ], '/**/myCallback([1])', [ 'callback' => 'myCallback' ] ],

				// Cross-domain mangling
				[ [ '< Cross-Domain-Policy >' ], '["\u003C Cross-Domain-Policy >"]' ],

				// Invalid UTF-8: bytes 192, 193, and 245-255 are off-limits
				[
					[ 'foo' => "\xFF" ],
					"{\"foo\":\"\u{FFFD}\"}", // Mangled when validated (T210548)
				],
				[
					[ 'foo' => "\xFF" ],
					new MWException(
						'Internal error in ApiFormatJson::execute: ' .
						'Unable to encode API result as JSON'
					),
					[],
					[ 'flags' => ApiResult::NO_VALIDATE ],
				],
				// NaN is also not allowed
				[
					[ 'foo' => NAN ],
					new InvalidArgumentException(
						'Cannot add non-finite floats to ApiResult'
					),
				],
				[
					[ 'foo' => NAN ],
					new MWException(
						'Internal error in ApiFormatJson::execute: ' .
						'Unable to encode API result as JSON'
					),
					[],
					[ 'flags' => ApiResult::NO_VALIDATE ],
				],
			] )
			// @todo Test rawfm
		);
	}

}
