<?php
/**
 * PHP Unit tests for MWMessagePack
 * @covers MWMessagePack
 */
class MWMessagePackTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/**
	 * Provides test cases for MWMessagePackTest::testMessagePack
	 *
	 * Returns an array of test cases. Each case is an array of (type, value,
	 * expected encoding as hex string). The expected values were generated
	 * using <https://github.com/msgpack/msgpack-php>, which includes a
	 * serialization function.
	 */
	public static function providePacks() {
		$tests = [
			[ 'nil', null, 'c0' ],
			[ 'bool', true, 'c3' ],
			[ 'bool', false, 'c2' ],
			[ 'positive fixnum', 0, '00' ],
			[ 'positive fixnum', 1, '01' ],
			[ 'positive fixnum', 5, '05' ],
			[ 'positive fixnum', 35, '23' ],
			[ 'uint 8', 128, 'cc80' ],
			[ 'uint 16', 1000, 'cd03e8' ],
			[ 'uint 32', 100000, 'ce000186a0' ],
			[ 'negative fixnum', -1, 'ff' ],
			[ 'negative fixnum', -2, 'fe' ],
			[ 'int 8', -128, 'd080' ],
			[ 'int 8', -35, 'd0dd' ],
			[ 'int 16', -1000, 'd1fc18' ],
			[ 'int 32', -100000, 'd2fffe7960' ],
			[ 'double', 0.1, 'cb3fb999999999999a' ],
			[ 'double', 1.1, 'cb3ff199999999999a' ],
			[ 'double', 123.456, 'cb405edd2f1a9fbe77' ],
			[ 'fix raw', '', 'a0' ],
			[ 'fix raw', 'foobar', 'a6666f6f626172' ],
			[
				'raw 16',
				'Lorem ipsum dolor sit amet amet.',
				'da00204c6f72656d20697073756d20646f6c6f722073697420616d657420616d65742e'
			],
			[
				'fix array',
				[ 'abc', 'def', 'ghi' ],
				'93a3616263a3646566a3676869'
			],
			[
				'fix map',
				[ 'one' => 1, 'two' => 2 ],
				'82a36f6e6501a374776f02'
			],
		];

		if ( PHP_INT_SIZE > 4 ) {
			$tests[] = [ 'uint 64', 10000000000, 'cf00000002540be400' ];
			$tests[] = [ 'int 64', -10000000000, 'd3fffffffdabf41c00' ];
			$tests[] = [ 'int 64', -223372036854775807, 'd3fce66c50e2840001' ];
			$tests[] = [ 'int 64', -9223372036854775807, 'd38000000000000001' ];
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
