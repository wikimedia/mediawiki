<?php

/**
 * @group API
 * @covers ApiFormatDump
 */
class ApiFormatDumpTest extends ApiFormatTestBase {

	protected $printerName = 'dump';

	public static function provideGeneralEncoding() {
		$warning = "\n  [\"warnings\"]=>\n  array(1) {\n    [\"dump\"]=>\n    array(1) {\n      [\"*\"]=>\n" .
			"      string(64) \"format=dump has been deprecated. Please use format=json instead.\"\n" .
			"    }\n  }";

		return array(
			// Basic types
			array( array( null ), "array(2) {{$warning}\n  [0]=>\n  NULL\n}\n" ),
			array( array( true ), "array(2) {{$warning}\n  [0]=>\n  bool(true)\n}\n" ),
			array( array( false ), "array(2) {{$warning}\n  [0]=>\n  bool(false)\n}\n" ),
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

			// Content
			array( array( '*' => 'foo' ), "array(2) {{$warning}\n  [\"*\"]=>\n  string(3) \"foo\"\n}\n" ),
		);
	}

}
