<?php

/**
 * @group API
 * @covers ApiFormatDump
 */
class ApiFormatDumpTest extends ApiFormatTestBase {

	protected $printerName = 'dump';

	public static function provideGeneralEncoding() {
		// Sigh. Docs claim it's a boolean, but can have values 0, 1, or 2.
		// Fortunately wfIniGetBool does the right thing.
		if ( wfIniGetBool( 'xdebug.overload_var_dump' ) ) {
			return array(
				array( array(), 'Cannot test ApiFormatDump when xDebug overloads var_dump', array( 'SKIP' => true ) ),
			);
		}

		$warning = "\n  [\"warnings\"]=>\n  array(1) {\n    [\"dump\"]=>\n    array(1) {\n      [\"*\"]=>\n" .
			"      string(64) \"format=dump has been deprecated. Please use format=json instead.\"\n" .
			"    }\n  }";

		return array(
			// Basic types
			array( array( null ), "array(2) {{$warning}\n  [0]=>\n  NULL\n}\n" ),
			array( array( true ), "array(2) {{$warning}\n  [0]=>\n  string(0) \"\"\n}\n" ),
			array( array( false ), "array(1) {{$warning}\n}\n" ),
			array( array( true, ApiResult::META_BC_BOOLS => array( 0 ) ),
				"array(2) {{$warning}\n  [0]=>\n  bool(true)\n}\n" ),
			array( array( false, ApiResult::META_BC_BOOLS => array( 0 ) ),
				"array(2) {{$warning}\n  [0]=>\n  bool(false)\n}\n" ),
			array( array( 42 ), "array(2) {{$warning}\n  [0]=>\n  int(42)\n}\n" ),
			array( array( 42.5 ), "array(2) {{$warning}\n  [0]=>\n  float(42.5)\n}\n" ),
			array( array( 1e42 ), "array(2) {{$warning}\n  [0]=>\n  float(1.0E+42)\n}\n" ),
			array( array( 'foo' ), "array(2) {{$warning}\n  [0]=>\n  string(3) \"foo\"\n}\n" ),
			array( array( 'fóo' ), "array(2) {{$warning}\n  [0]=>\n  string(4) \"fóo\"\n}\n" ),

			// Arrays
			array( array( array() ), "array(2) {{$warning}\n  [0]=>\n  array(0) {\n  }\n}\n" ),
			array( array( array( 1 ) ), "array(2) {{$warning}\n  [0]=>\n  array(1) {\n    [0]=>\n    int(1)\n  }\n}\n" ),
			array( array( array( 'x' => 1 ) ), "array(2) {{$warning}\n  [0]=>\n  array(1) {\n    [\"x\"]=>\n    int(1)\n  }\n}\n" ),
			array( array( array( 2 => 1 ) ), "array(2) {{$warning}\n  [0]=>\n  array(1) {\n    [2]=>\n    int(1)\n  }\n}\n" ),
			array( array( (object)array() ), "array(2) {{$warning}\n  [0]=>\n  array(0) {\n  }\n}\n" ),
			array( array( array( 1, ApiResult::META_TYPE => 'assoc' ) ), "array(2) {{$warning}\n  [0]=>\n  array(1) {\n    [0]=>\n    int(1)\n  }\n}\n" ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'array' ) ), "array(2) {{$warning}\n  [0]=>\n  array(1) {\n    [0]=>\n    int(1)\n  }\n}\n" ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'kvp' ) ), "array(2) {{$warning}\n  [0]=>\n  array(1) {\n    [\"x\"]=>\n    int(1)\n  }\n}\n" ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'BCkvp', ApiResult::META_KVP_KEY_NAME => 'key' ) ),
				"array(2) {{$warning}\n  [0]=>\n  array(1) {\n    [0]=>\n    array(2) {\n      [\"key\"]=>\n      string(1) \"x\"\n      [\"*\"]=>\n      int(1)\n    }\n  }\n}\n" ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'BCarray' ) ), "array(2) {{$warning}\n  [0]=>\n  array(1) {\n    [\"x\"]=>\n    int(1)\n  }\n}\n" ),
			array( array( array( 'a', 'b', ApiResult::META_TYPE => 'BCassoc' ) ), "array(2) {{$warning}\n  [0]=>\n  array(2) {\n    [0]=>\n    string(1) \"a\"\n    [1]=>\n    string(1) \"b\"\n  }\n}\n" ),

			// Content
			array( array( 'content' => 'foo', ApiResult::META_CONTENT => 'content' ),
				"array(2) {{$warning}\n  [\"*\"]=>\n  string(3) \"foo\"\n}\n" ),

			// BC Subelements
			array( array( 'foo' => 'foo', ApiResult::META_BC_SUBELEMENTS => array( 'foo' ) ),
				"array(2) {{$warning}\n  [\"foo\"]=>\n  array(1) {\n    [\"*\"]=>\n    string(3) \"foo\"\n  }\n}\n" ),
		);
	}

}
