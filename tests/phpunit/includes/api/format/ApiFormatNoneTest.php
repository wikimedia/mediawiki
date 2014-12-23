<?php

/**
 * @group API
 * @covers ApiFormatNone
 */
class ApiFormatNoneTest extends ApiFormatTestBase {

	protected $printerName = 'none';

	public static function provideGeneralEncoding() {
		return array(
			// Basic types
			array( array( null ), '' ),
			array( array( true ), '' ),
			array( array( false ), '' ),
			array( array( 42 ), '' ),
			array( array( 42.5 ), '' ),
			array( array( 1e42 ), '' ),
			array( array( 'foo' ), '' ),
			array( array( 'fóo' ), '' ),

			// Arrays and objects
			array( array( array() ), '' ),
			array( array( array( 1 ) ), '' ),
			array( array( array( 'x' => 1 ) ), '' ),
			array( array( array( 2 => 1 ) ), '' ),

			// Content
			array( array( '*' => 'foo' ), '' ),
		);
	}

}
