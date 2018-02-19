<?php

/**
 * @group GlobalFunctions
 * @covers ::wfArrayFilter
 * @covers ::wfArrayFilterByKey
 */
class WfArrayFilterTest extends \PHPUnit\Framework\TestCase {
	public function testWfArrayFilter() {
		$arr = [ 'a' => 1, 'b' => 2, 'c' => 3 ];
		$filtered = wfArrayFilter( $arr, function ( $val, $key ) {
			return $key !== 'b';
		} );
		$this->assertSame( [ 'a' => 1, 'c' => 3 ], $filtered );

		$arr = [ 'a' => 1, 'b' => 2, 'c' => 3 ];
		$filtered = wfArrayFilter( $arr, function ( $val, $key ) {
			return $val !== 2;
		} );
		$this->assertSame( [ 'a' => 1, 'c' => 3 ], $filtered );

		$arr = [ 'a', 'b', 'c' ];
		$filtered = wfArrayFilter( $arr, function ( $val, $key ) {
			return $key !== 0;
		} );
		$this->assertSame( [ 1 => 'b',  2 => 'c' ], $filtered );
	}

	public function testWfArrayFilterByKey() {
		$arr = [ 'a' => 1, 'b' => 2, 'c' => 3 ];
		$filtered = wfArrayFilterByKey( $arr, function ( $key ) {
			return $key !== 'b';
		} );
		$this->assertSame( [ 'a' => 1, 'c' => 3 ], $filtered );

		$arr = [ 'a', 'b', 'c' ];
		$filtered = wfArrayFilterByKey( $arr, function ( $key ) {
			return $key !== 0;
		} );
		$this->assertSame( [ 1 => 'b',  2 => 'c' ], $filtered );
	}
}
