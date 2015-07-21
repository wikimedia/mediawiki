<?php

/**
 * @group API
 * @covers ApiFormatTxt
 */
class ApiFormatTxtTest extends ApiFormatTestBase {

	protected $printerName = 'txt';

	public static function provideGeneralEncoding() {
		$warning = "\n    [warnings] => Array\n        (\n            [txt] => Array\n                (\n" .
			"                    [*] => format=txt has been deprecated. Please use format=json instead.\n" .
			"                )\n\n        )\n";

		return array(
			// Basic types
			array( array( null ), "Array\n({$warning}\n    [0] => \n)\n" ),
			array( array( true ), "Array\n({$warning}\n    [0] => \n)\n" ),
			array( array( false ), "Array\n({$warning}\n)\n" ),
			array( array( true, ApiResult::META_BC_BOOLS => array( 0 ) ),
				"Array\n({$warning}\n    [0] => 1\n)\n" ),
			array( array( false, ApiResult::META_BC_BOOLS => array( 0 ) ),
				"Array\n({$warning}\n    [0] => \n)\n" ),
			array( array( 42 ), "Array\n({$warning}\n    [0] => 42\n)\n" ),
			array( array( 42.5 ), "Array\n({$warning}\n    [0] => 42.5\n)\n" ),
			array( array( 1e42 ), "Array\n({$warning}\n    [0] => 1.0E+42\n)\n" ),
			array( array( 'foo' ), "Array\n({$warning}\n    [0] => foo\n)\n" ),
			array( array( 'fóo' ), "Array\n({$warning}\n    [0] => fóo\n)\n" ),

			// Arrays and objects
			array( array( array() ), "Array\n({$warning}\n    [0] => Array\n        (\n        )\n\n)\n" ),
			array( array( array( 1 ) ), "Array\n({$warning}\n    [0] => Array\n        (\n            [0] => 1\n        )\n\n)\n" ),
			array( array( array( 'x' => 1 ) ), "Array\n({$warning}\n    [0] => Array\n        (\n            [x] => 1\n        )\n\n)\n" ),
			array( array( array( 2 => 1 ) ), "Array\n({$warning}\n    [0] => Array\n        (\n            [2] => 1\n        )\n\n)\n" ),
			array( array( (object)array() ), "Array\n({$warning}\n    [0] => Array\n        (\n        )\n\n)\n" ),
			array( array( array( 1, ApiResult::META_TYPE => 'assoc' ) ), "Array\n({$warning}\n    [0] => Array\n        (\n            [0] => 1\n        )\n\n)\n" ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'array' ) ), "Array\n({$warning}\n    [0] => Array\n        (\n            [0] => 1\n        )\n\n)\n" ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'kvp' ) ), "Array\n({$warning}\n    [0] => Array\n        (\n            [x] => 1\n        )\n\n)\n" ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'BCkvp', ApiResult::META_KVP_KEY_NAME => 'key' ) ),
				"Array\n({$warning}\n    [0] => Array\n        (\n            [0] => Array\n                (\n                    [key] => x\n                    [*] => 1\n                )\n\n        )\n\n)\n" ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'BCarray' ) ), "Array\n({$warning}\n    [0] => Array\n        (\n            [x] => 1\n        )\n\n)\n" ),
			array( array( array( 'a', 'b', ApiResult::META_TYPE => 'BCassoc' ) ), "Array\n({$warning}\n    [0] => Array\n        (\n            [0] => a\n            [1] => b\n        )\n\n)\n" ),

			// Content
			array( array( 'content' => 'foo', ApiResult::META_CONTENT => 'content' ),
				"Array\n({$warning}\n    [*] => foo\n)\n" ),

			// BC Subelements
			array( array( 'foo' => 'foo', ApiResult::META_BC_SUBELEMENTS => array( 'foo' ) ),
				"Array\n({$warning}\n    [foo] => Array\n        (\n            [*] => foo\n        )\n\n)\n" ),
		);
	}

}
