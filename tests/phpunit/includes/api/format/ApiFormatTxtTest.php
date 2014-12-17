<?php

/**
 * @group API
 * @covers ApiFormatTxt
 */
class ApiFormatTxtTest extends ApiFormatTestBase {

	protected $printerName = 'txt';

	public function provideGeneralEncoding() {
		$warning = "\n    [warnings] => Array\n        (\n            [txt] => Array\n                (\n" .
			"                    [*] => format=txt has been deprecated. Please use format=json instead.\n" .
			"                )\n\n        )\n";

		return array(
			// Basic types
			array( array( null ), "Array\n({$warning}\n    [0] => \n)\n" ),
			array( array( true ), "Array\n({$warning}\n    [0] => 1\n)\n" ),
			array( array( false ), "Array\n({$warning}\n    [0] => \n)\n" ),
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

			// Content
			array( array( '*' => 'foo' ), "Array\n({$warning}\n    [*] => foo\n)\n" ),
		);
	}

}
