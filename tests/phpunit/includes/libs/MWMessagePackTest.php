<?php
/**
 * PHP Unit tests for MWMessagePack
 * @covers MWMessagePack
 */
class MWMessagePackTest extends PHPUnit_Framework_TestCase {

	/**
	 * Provides test cases for MWMessagePackTest::testMessagePack
	 *
	 * Returns an array of test cases. Each case is an array of (type, value,
	 * expected encoding as hex string). The expected values were generated
	 * using <https://github.com/msgpack/msgpack-php>, which includes a
	 * serialization function.
	 */
	public static function providePacks() {
		$tests = array(
			array( 'nil', null, 'c0' ),
			array( 'bool', true, 'c3' ),
			array( 'bool', false, 'c2' ),
			array( 'positive fixnum', 0, '00' ),
			array( 'positive fixnum', 1, '01' ),
			array( 'positive fixnum', 5, '05' ),
			array( 'positive fixnum', 35, '23' ),
			array( 'uint 8', 128, 'cc80' ),
			array( 'uint 16', 1000, 'cd03e8' ),
			array( 'uint 32', 100000, 'ce000186a0' ),
			array( 'negative fixnum', -1, 'ff' ),
			array( 'negative fixnum', -2, 'fe' ),
			array( 'int 8', -128, 'd080' ),
			array( 'int 8', -35, 'd0dd' ),
			array( 'int 16', -1000, 'd1fc18' ),
			array( 'int 32', -100000, 'd2fffe7960' ),
			array( 'double', 0.1, 'cb3fb999999999999a' ),
			array( 'double', 1.1, 'cb3ff199999999999a' ),
			array( 'double', 123.456, 'cb405edd2f1a9fbe77' ),
			array( 'fix raw', '', 'a0' ),
			array( 'fix raw', 'foobar', 'a6666f6f626172' ),
			array(
				'raw 16',
				'Lorem ipsum dolor sit amet amet.',
				'da00204c6f72656d20697073756d20646f6c6f722073697420616d657420616d65742e'
			),
			array(
				'fix array',
				array( 'abc', 'def', 'ghi' ),
				'93a3616263a3646566a3676869'
			),
			array(
				'fix map',
				array( 'one' => 1, 'two' => 2 ),
				'82a36f6e6501a374776f02'
			),
		);

		if ( PHP_INT_SIZE > 4 ) {
			$tests[] = array( 'uint 64', 10000000000, 'cf00000002540be400' );
			$tests[] = array( 'int 64', -10000000000, 'd3fffffffdabf41c00' );
			$tests[] = array( 'int 64', -223372036854775807, 'd3fce66c50e2840001' );
			$tests[] = array( 'int 64', -9223372036854775807, 'd38000000000000001' );
		}

		return $tests;
	}

	/**
	 * Verify that values are serialized correctly.
	 * @covers MWMessagePack::pack
	 * @dataProvider providePacks
	 */
	public function testPack( $type, $value, $expected ) {
		$actual = bin2hex( MWMessagePack::pack( $value ) );
		$this->assertEquals( $expected, $actual, $type );
	}
}
