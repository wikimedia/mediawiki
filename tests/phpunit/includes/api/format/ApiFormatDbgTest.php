<?php

/**
 * @group API
 * @covers ApiFormatDbg
 */
class ApiFormatDbgTest extends ApiFormatTestBase {

	protected $printerName = 'dbg';

	public static function provideGeneralEncoding() {
		$warning = "\n  'warnings' => \n  array (\n    'dbg' => \n    array (\n" .
			"      '*' => 'format=dbg has been deprecated. Please use format=json instead.',\n" .
			"    ),\n  ),";

		return array(
			// Basic types
			array( array( null ), "array ({$warning}\n  0 => NULL,\n)" ),
			array( array( true ), "array ({$warning}\n  0 => '',\n)" ),
			array( array( false ), "array ({$warning}\n)" ),
			array( array( true, ApiResult::META_BC_BOOLS => array( 0 ) ),
				"array ({$warning}\n  0 => true,\n)" ),
			array( array( false, ApiResult::META_BC_BOOLS => array( 0 ) ),
				"array ({$warning}\n  0 => false,\n)" ),
			array( array( 42 ), "array ({$warning}\n  0 => 42,\n)" ),
			array( array( 42.5 ), "array ({$warning}\n  0 => 42.5,\n)" ),
			array( array( 1e42 ), "array ({$warning}\n  0 => 1.0E+42,\n)" ),
			array( array( 'foo' ), "array ({$warning}\n  0 => 'foo',\n)" ),
			array( array( 'fóo' ), "array ({$warning}\n  0 => 'fóo',\n)" ),

			// Arrays and objects
			array( array( array() ), "array ({$warning}\n  0 => \n  array (\n  ),\n)" ),
			array( array( array( 1 ) ), "array ({$warning}\n  0 => \n  array (\n    0 => 1,\n  ),\n)" ),
			array( array( array( 'x' => 1 ) ), "array ({$warning}\n  0 => \n  array (\n    'x' => 1,\n  ),\n)" ),
			array( array( array( 2 => 1 ) ), "array ({$warning}\n  0 => \n  array (\n    2 => 1,\n  ),\n)" ),
			array( array( (object)array() ), "array ({$warning}\n  0 => \n  array (\n  ),\n)" ),
			array( array( array( 1, ApiResult::META_TYPE => 'assoc' ) ), "array ({$warning}\n  0 => \n  array (\n    0 => 1,\n  ),\n)" ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'array' ) ), "array ({$warning}\n  0 => \n  array (\n    0 => 1,\n  ),\n)" ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'kvp' ) ), "array ({$warning}\n  0 => \n  array (\n    'x' => 1,\n  ),\n)" ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'BCkvp', ApiResult::META_KVP_KEY_NAME => 'key' ) ),
				"array ({$warning}\n  0 => \n  array (\n    0 => \n    array (\n      'key' => 'x',\n      '*' => 1,\n    ),\n  ),\n)" ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'BCarray' ) ), "array ({$warning}\n  0 => \n  array (\n    'x' => 1,\n  ),\n)" ),
			array( array( array( 'a', 'b', ApiResult::META_TYPE => 'BCassoc' ) ), "array ({$warning}\n  0 => \n  array (\n    0 => 'a',\n    1 => 'b',\n  ),\n)" ),

			// Content
			array( array( 'content' => 'foo', ApiResult::META_CONTENT => 'content' ),
				"array ({$warning}\n  '*' => 'foo',\n)" ),

			// BC Subelements
			array( array( 'foo' => 'foo', ApiResult::META_BC_SUBELEMENTS => array( 'foo' ) ),
				"array ({$warning}\n  'foo' => \n  array (\n    '*' => 'foo',\n  ),\n)" ),
		);
	}

}
