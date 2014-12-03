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
			array( array( (object)array() ), '' ),
			array( array( array( 1, ApiResult::META_TYPE => 'assoc' ) ), '' ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'array' ) ), '' ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'kvp' ) ), '' ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'BCkvp', ApiResult::META_KVP_KEY_NAME => 'key' ) ), '' ),
			array( array( array( 'x' => 1, ApiResult::META_TYPE => 'BCarray' ) ), '' ),
			array( array( array( 'a', 'b', ApiResult::META_TYPE => 'BCassoc' ) ), '' ),

			// Content
			array( array( '*' => 'foo' ), '' ),

			// BC Subelements
			array( array( 'foo' => 'foo', ApiResult::META_BC_SUBELEMENTS => array( 'foo' ) ), '' ),
		);
	}

}
